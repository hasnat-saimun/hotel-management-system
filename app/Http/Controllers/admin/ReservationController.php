<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\ReservationRoom;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    private function freeDateRangesWithinWindow(Carbon $windowStart, Carbon $windowEnd, $overlappingReservations): array
    {
        if ($windowEnd->lessThanOrEqualTo($windowStart)) {
            return [];
        }

        $cursor = $windowStart->copy();
        $ranges = [];

        foreach (collect($overlappingReservations)->sortBy('check_in_date') as $reservation) {
            try {
                $blockedStart = Carbon::parse($reservation->check_in_date)->startOfDay();
                $blockedEnd = Carbon::parse($reservation->check_out_date)->startOfDay();
            } catch (\Throwable $e) {
                continue;
            }

            if ($blockedEnd->lessThanOrEqualTo($windowStart)) {
                continue;
            }

            if ($blockedStart->greaterThanOrEqualTo($windowEnd)) {
                break;
            }

            if ($blockedStart->lessThan($windowStart)) {
                $blockedStart = $windowStart->copy();
            }

            if ($blockedEnd->greaterThan($windowEnd)) {
                $blockedEnd = $windowEnd->copy();
            }

            if ($blockedStart->greaterThan($cursor)) {
                $ranges[] = [
                    'from' => $cursor->toDateString(),
                    'to' => $blockedStart->toDateString(),
                ];
            }

            if ($blockedEnd->greaterThan($cursor)) {
                $cursor = $blockedEnd->copy();
            }

            if ($cursor->greaterThanOrEqualTo($windowEnd)) {
                break;
            }
        }

        if ($cursor->lessThan($windowEnd)) {
            $ranges[] = [
                'from' => $cursor->toDateString(),
                'to' => $windowEnd->toDateString(),
            ];
        }

        return array_values(array_filter($ranges, fn ($range) => ($range['from'] ?? null) < ($range['to'] ?? null)));
    }

    public function index()
    {
        $reservations = Reservation::with('guest','reservationRooms','rooms')->get();
        return view('admin.reservations.index', compact('reservations'));
        
        
    }

    public function show($id)
    {
        $reservation = Reservation::with('guest','rooms','payments','invoice')->findOrFail($id);
        return view('admin.reservations.show', compact('reservation'));
    }

    public function checkin($id)
    {
        $reservation = Reservation::with(['rooms', 'reservationRooms'])->findOrFail($id);

        if (($reservation->status ?? null) !== 'confirmed') {
            return redirect()->back()->with('error', 'Only confirmed reservations can be checked in.');
        }

        DB::transaction(function () use ($reservation) {
            $reservation->status = 'checked_in';
            $reservation->save();

            ReservationRoom::where('reservation_id', $reservation->id)
                ->update(['status' => 'occupied']);

            $roomIds = $reservation->rooms->pluck('id')->filter()->values();
            if ($roomIds->isNotEmpty()) {
                Room::whereIn('id', $roomIds)->update(['status' => 'occupied']);
            }
        });

        return redirect()->route('admin.reservations.show', $reservation->id)
            ->with('success', 'Guest checked in successfully.');
    }

    public function checkout($id)
    {
        $reservation = Reservation::with(['rooms', 'reservationRooms'])->findOrFail($id);

        if (($reservation->status ?? null) !== 'checked_in') {
            return redirect()->back()->with('error', 'Only checked-in reservations can be checked out.');
        }

        DB::transaction(function () use ($reservation) {
            $reservation->status = 'checked_out';
            $reservation->save();

            ReservationRoom::where('reservation_id', $reservation->id)
                ->update(['status' => 'released']);

            $roomIds = $reservation->rooms->pluck('id')->filter()->values();
            if ($roomIds->isNotEmpty()) {
                Room::whereIn('id', $roomIds)->update(['status' => 'dirty']);
            }
        });

        return redirect()->route('admin.reservations.show', $reservation->id)
            ->with('success', 'Guest checked out successfully.');
    }

    public function cancel(Request $request, $id)
    {
        $reservation = Reservation::with(['rooms', 'reservationRooms'])->findOrFail($id);

        $data = $request->validate([
            'cancel_note' => 'nullable|string|max:2000',
        ]);

        $currentStatus = strtolower((string) ($reservation->status ?? ''));
        if (in_array($currentStatus, ['checked_in', 'checked-in', 'checkedin', 'checked_out', 'checked-out', 'checkedout'], true)) {
            return redirect()->back()->with('error', 'Checked-in/out reservations cannot be cancelled.');
        }

        if ($currentStatus === 'cancelled') {
            return redirect()->back()->with('success', 'Reservation already cancelled.');
        }

        DB::transaction(function () use ($reservation, $data) {
            $reservation->status = 'cancelled';
            $reservation->cancel_note = $data['cancel_note'] ?? null;
            $reservation->save();

            ReservationRoom::where('reservation_id', $reservation->id)
                ->update(['status' => 'released']);

            $roomIds = $reservation->rooms->pluck('id')->filter()->values();
            if ($roomIds->isNotEmpty()) {
                Room::whereIn('id', $roomIds)->update(['status' => 'available']);
            }
        });

        return redirect()->route('admin.reservations.index')->with('success', 'Reservation cancelled successfully.');
    }

    public function calendar()
    {
        $reservations = Reservation::with(['guest', 'rooms.floor'])->get();

        $calendarEvents = $reservations
            ->filter(fn ($reservation) => !empty($reservation->check_in_date) && !empty($reservation->check_out_date))
            ->map(function ($reservation) {
                $guestName = '';
                if ($reservation->relationLoaded('guest') && $reservation->guest) {
                    $guestName = trim(($reservation->guest->first_name ?? '') . ' ' . ($reservation->guest->last_name ?? ''));
                }

                if ($guestName === '') {
                    $guestName = 'Guest';
                }

                $roomNumbers = [];
                if ($reservation->relationLoaded('rooms') && $reservation->rooms) {
                    $roomNumbers = $reservation->rooms
                        ->pluck('room_number')
                        ->filter()
                        ->unique()
                        ->values()
                        ->all();
                }

                $floorLabels = [];
                if ($reservation->relationLoaded('rooms') && $reservation->rooms) {
                    $floorLabels = $reservation->rooms
                        ->map(function ($room) {
                            $floor = $room->floor ?? null;
                            if (!$floor) {
                                return null;
                            }

                            $name = trim((string) ($floor->name ?? ''));
                            $level = trim((string) ($floor->level_number ?? ''));

                            if ($name !== '') {
                                return $name;
                            }

                            if ($level !== '') {
                                return 'Floor ' . $level;
                            }

                            return null;
                        })
                        ->filter()
                        ->unique()
                        ->values()
                        ->all();
                }

                $titleParts = [];
                $statusLabel = $reservation->status
                    ? ucfirst(str_replace(['_', '-'], ' ', (string) $reservation->status))
                    : 'Reserved';

                $titleParts[] = $statusLabel;
                if (!empty($floorLabels)) {
                    $titleParts[] = 'Floor ' . implode(', ', $floorLabels);
                }
                if (!empty($roomNumbers)) {
                    $titleParts[] = 'Room ' . implode(', ', $roomNumbers);
                }

                $startDate = optional($reservation->check_in_date)->toDateString();
                $endDate = optional($reservation->check_out_date)->toDateString();
                if ($endDate !== null && $startDate !== null && $endDate <= $startDate) {
                    $endDate = optional($reservation->check_in_date)->copy()->addDay()->toDateString();
                }

                return [
                    'id' => $reservation->id,
                    'title' => implode(' - ', $titleParts),
                    'start' => $startDate,
                    'end' => $endDate,
                    'allDay' => true,
                ];
            })
            ->values();

        return view('admin.reservations.calendar', [
            'reservations' => $reservations,
            'calendarEvents' => $calendarEvents,
        ]);
    }

    public function calendarByRoom(Request $request)
    {
        $rooms = Room::query()
            ->with(['roomType'])
            ->orderBy('room_number')
            ->get();

        $roomId = $request->input('room_id');
        $month = $request->input('month'); // YYYY-MM

        $initialDate = null;
        $monthStart = null;
        $monthEndExclusive = null;

        if (!empty($month)) {
            try {
                $monthStart = Carbon::parse($month . '-01')->startOfMonth();
                $monthEndExclusive = $monthStart->copy()->addMonth();
                $initialDate = $monthStart->toDateString();
            } catch (\Throwable $e) {
                $monthStart = null;
                $monthEndExclusive = null;
                $initialDate = null;
            }
        }

        $reservations = Reservation::query()
            ->with(['guest', 'rooms.floor'])
            ->when(!empty($roomId), function ($query) use ($roomId) {
                $query->whereHas('rooms', function ($roomQuery) use ($roomId) {
                    $roomQuery->where('rooms.id', $roomId);
                });
            })
            ->when($monthStart && $monthEndExclusive, function ($query) use ($monthStart, $monthEndExclusive) {
                $query
                    ->where('check_in_date', '<', $monthEndExclusive->toDateString())
                    ->where('check_out_date', '>', $monthStart->toDateString());
            })
            ->get();

        $roomCalendarEvents = $reservations
            ->filter(fn ($reservation) => !empty($reservation->check_in_date) && !empty($reservation->check_out_date))
            ->map(function ($reservation) use ($roomId) {
                $guestName = '';
                if ($reservation->relationLoaded('guest') && $reservation->guest) {
                    $guestName = trim(($reservation->guest->first_name ?? '') . ' ' . ($reservation->guest->last_name ?? ''));
                }

                if ($guestName === '') {
                    $guestName = 'Guest';
                }

                $rooms = $reservation->relationLoaded('rooms') && $reservation->rooms
                    ? $reservation->rooms
                    : collect();

                if (!empty($roomId)) {
                    $rooms = $rooms->where('id', (int) $roomId)->values();
                }

                $roomNumbers = $rooms->pluck('room_number')->filter()->unique()->values()->all();
                $floorLabels = $rooms
                    ->map(function ($room) {
                        $floor = $room->floor ?? null;
                        if (!$floor) return null;
                        $name = trim((string) ($floor->name ?? ''));
                        $level = trim((string) ($floor->level_number ?? ''));
                        if ($name !== '') return $name;
                        if ($level !== '') return 'Floor ' . $level;
                        return null;
                    })
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                $statusLabel = $reservation->status
                    ? ucfirst(str_replace(['_', '-'], ' ', (string) $reservation->status))
                    : 'Reserved';

                $titleParts = [$statusLabel, $guestName];
                if (!empty($floorLabels)) {
                    $titleParts[] = implode(', ', $floorLabels);
                }
                if (!empty($roomNumbers)) {
                    $titleParts[] = 'Room ' . implode(', ', $roomNumbers);
                }

                $startDate = optional($reservation->check_in_date)->toDateString();
                $endDate = optional($reservation->check_out_date)->toDateString();
                if ($endDate !== null && $startDate !== null && $endDate <= $startDate) {
                    $endDate = optional($reservation->check_in_date)->copy()->addDay()->toDateString();
                }

                $title = implode(' - ', array_filter($titleParts));

                return [
                    'id' => $reservation->id,
                    'title' => $title,
                    'start' => $startDate,
                    'end' => $endDate,
                    'allDay' => true,
                ];
            })
            ->values();

        return view('admin.reservations.calendarByroom', [
            'rooms' => $rooms,
            'roomCalendarEvents' => $roomCalendarEvents,
            'initialDate' => $initialDate,
        ]);
    }

    public function calendarModal($id)
    {
        $reservation = Reservation::with([
            'guest',
            'rooms.floor',
            'reservationRooms.roomType',
            'payments',
            'invoice',
        ])->findOrFail($id);

        return response()->json([
            'title' => 'Reservation Details',
            'html' => view('admin.reservations.partials.calendar-modal-details', compact('reservation'))->render(),
        ]);
    }

    public function create(Request $request)
    {
        $rooms = Room::query()
            ->with(['roomType'])
            ->orderBy('room_number')
            ->get();

        $roomId = $request->input('room_id');
        $checkInDate = $request->input('check_in_date');
        $checkOutDate = $request->input('check_out_date');
        $datesRaw = (string) $request->input('dates', '');

        $selectedDates = collect(array_filter(array_map('trim', explode(',', $datesRaw))))
            ->filter(fn ($value) => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $value))
            ->unique()
            ->sort()
            ->values();

        if ((!$checkInDate || !$checkOutDate) && $selectedDates->isNotEmpty()) {
            $checkInDate = $checkInDate ?: $selectedDates->first();
            try {
                $last = Carbon::parse($selectedDates->last())->startOfDay();
                $checkOutDate = $checkOutDate ?: $last->copy()->addDay()->toDateString();
            } catch (\Throwable $e) {
                // fall back to empty
            }
        }

        try {
            if (!empty($checkInDate) && !empty($checkOutDate)) {
                $in = Carbon::parse($checkInDate)->toDateString();
                $out = Carbon::parse($checkOutDate)->toDateString();
                if ($out <= $in) {
                    $out = Carbon::parse($in)->addDay()->toDateString();
                }
                $checkInDate = $in;
                $checkOutDate = $out;
            }
        } catch (\Throwable $e) {
            // ignore invalid values
        }

        return view('admin.reservations.reservationCreate', [
            'rooms' => $rooms,
            'roomId' => $roomId,
            'checkInDate' => $checkInDate,
            'checkOutDate' => $checkOutDate,
            'selectedDates' => $selectedDates,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'check_in_date' => ['required', 'date'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'guest_first_name' => ['required', 'string', 'max:255'],
            'guest_last_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'guest_phone' => ['nullable', 'string', 'max:50'],
            'guest_address' => ['nullable', 'string', 'max:2000'],
            'guest_id_type' => ['nullable', 'in:passport,driver_license,national_id,other'],
            'guest_id_number' => ['nullable', 'string', 'max:255'],
            'adults' => ['nullable', 'integer', 'min:1'],
            'children' => ['nullable', 'integer', 'min:0'],
            'special_requests' => ['nullable', 'string', 'max:4000'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        try {
            $checkInDate = Carbon::parse($data['check_in_date'])->toDateString();
            $checkOutDate = Carbon::parse($data['check_out_date'])->toDateString();
        } catch (\Throwable $e) {
            return back()->withErrors(['check_in_date' => 'Invalid date range.'])->withInput();
        }

        $room = Room::query()->with(['roomType'])->findOrFail((int) $data['room_id']);

        $activeReservationStatuses = ['pending', 'confirmed', 'checked_in', 'booked'];
        $overlaps = Reservation::query()
            ->whereIn('status', $activeReservationStatuses)
            ->where('check_in_date', '<', $checkOutDate)
            ->where('check_out_date', '>', $checkInDate)
            ->whereHas('rooms', function ($q) use ($room) {
                $q->where('rooms.id', $room->id);
            })
            ->exists();

        if ($overlaps) {
            return back()
                ->withErrors(['check_in_date' => 'This room already has a reservation in the selected date range.'])
                ->withInput();
        }

        $adults = (int) ($data['adults'] ?? 1);
        $children = (int) ($data['children'] ?? 0);
        $nightlyRate = (float) ($room->roomType?->base_price ?? 0);

        $checkIn = Carbon::parse($checkInDate)->startOfDay();
        $checkOut = Carbon::parse($checkOutDate)->startOfDay();
        $nights = max(1, $checkOut->diffInDays($checkIn));
        $totalAmount = $nightlyRate * $nights;

        $reservation = DB::transaction(function () use ($data, $room, $checkInDate, $checkOutDate, $adults, $children, $nightlyRate, $totalAmount) {
            $guest = Guest::query()->where('email', $data['guest_email'])->first();
            if ($guest) {
                $guest->first_name = $data['guest_first_name'];
                $guest->last_name = $data['guest_last_name'];
                $guest->phone = $data['guest_phone'] ?? $guest->phone;
                $guest->address = array_key_exists('guest_address', $data) ? ($data['guest_address'] ?? null) : $guest->address;
                $guest->id_type = array_key_exists('guest_id_type', $data) ? ($data['guest_id_type'] ?? null) : $guest->id_type;
                $guest->id_number = array_key_exists('guest_id_number', $data) ? ($data['guest_id_number'] ?? null) : $guest->id_number;
                $guest->save();
            } else {
                $guest = Guest::create([
                    'first_name' => $data['guest_first_name'],
                    'last_name' => $data['guest_last_name'],
                    'email' => $data['guest_email'],
                    'phone' => $data['guest_phone'] ?? null,
                    'address' => $data['guest_address'] ?? null,
                    'id_type' => $data['guest_id_type'] ?? null,
                    'id_number' => $data['guest_id_number'] ?? null,
                ]);
            }

            $reservationCode = null;
            for ($i = 0; $i < 5; $i++) {
                $candidate = 'RSV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
                if (!Reservation::where('reservation_code', $candidate)->exists()) {
                    $reservationCode = $candidate;
                    break;
                }
            }

            $noteParts = [];
            if (!empty($data['special_requests'])) {
                $noteParts[] = 'Special Requests: ' . trim((string) $data['special_requests']);
            }
            if (!empty($data['note'])) {
                $noteParts[] = trim((string) $data['note']);
            }
            $finalNote = empty($noteParts) ? null : implode("\n\n", $noteParts);

            $reservation = Reservation::create([
                'guest_id' => $guest->id,
                'reservation_code' => $reservationCode,
                'channel' => 'admin-calendar',
                'status' => 'booked',
                'payment_status' => 'unpaid',
                'check_in_date' => $checkInDate,
                'check_out_date' => $checkOutDate,
                'adults' => $adults,
                'children' => $children,
                'rate' => $totalAmount,
                'note' => $finalNote,
            ]);

            ReservationRoom::create([
                'reservation_id' => $reservation->id,
                'room_id' => $room->id,
                'room_type_id' => (int) $room->room_type_id,
                'nightly_rate' => $nightlyRate,
                'discount_amount' => 0,
                'tax_amount' => 0,
                'total_amount' => $totalAmount,
                'status' => 'reserved',
            ]);

            $room->status = 'reserved';
            $room->save();

            return $reservation;
        });

        return redirect()->route('admin.reservations.show', $reservation->id)
            ->with('success', 'Reservation created successfully.');
    }

    public function walkin(Request $request)
    {
        $checkInDateRaw = $request->input('check_in_date');
        $checkOutDateRaw = $request->input('check_out_date');

        $availableRooms = collect();
        $partiallyAvailableRooms = collect();
        $missingMessage = null;

        $hasAnyInput = ($checkInDateRaw !== null && $checkInDateRaw !== '')
            || ($checkOutDateRaw !== null && $checkOutDateRaw !== '');
 
        if (!$hasAnyInput) {
            $missingMessage = 'Please select check-in and check-out dates, then click Search.';
            return view('admin.reservations.walkin', [
                'availableRooms' => $availableRooms,
                'partiallyAvailableRooms' => $partiallyAvailableRooms,
                'missingMessage' => $missingMessage,
                'checkInDate' => null,
                'checkOutDate' => null,
            ]);
        }

        if (empty($checkInDateRaw) || empty($checkOutDateRaw)) {
            $missingMessage = 'Missing check-in or check-out date.';
            return view('admin.reservations.walkin', [
                'availableRooms' => $availableRooms,
                'partiallyAvailableRooms' => $partiallyAvailableRooms,
                'missingMessage' => $missingMessage,
                'checkInDate' => $checkInDateRaw,
                'checkOutDate' => $checkOutDateRaw,
            ]);
        }

        try {
            $checkInDate = Carbon::parse($checkInDateRaw)->toDateString();
            $checkOutDate = Carbon::parse($checkOutDateRaw)->toDateString();
        } catch (\Throwable $e) {
            $missingMessage = 'Invalid date value.';
            return view('admin.reservations.walkin', [
                'availableRooms' => $availableRooms,
                'partiallyAvailableRooms' => $partiallyAvailableRooms,
                'missingMessage' => $missingMessage,
                'checkInDate' => $checkInDateRaw,
                'checkOutDate' => $checkOutDateRaw,
            ]);
        }

        if ($checkOutDate < $checkInDate) {
            $missingMessage = 'Check-out date must be the same as or after check-in date.';
            return view('admin.reservations.walkin', [
                'availableRooms' => $availableRooms,
                'partiallyAvailableRooms' => $partiallyAvailableRooms,
                'missingMessage' => $missingMessage,
                'checkInDate' => $checkInDate,
                'checkOutDate' => $checkOutDate,
            ]);
        }

        $activeReservationStatuses = ['pending', 'confirmed', 'checked_in', 'booked'];
        $excludedRoomStatuses = ['maintenance', 'out_of_service'];

        $reservationOverlapsWindow = function ($query) use ($checkInDate, $checkOutDate, $activeReservationStatuses) {
            $query
                ->whereIn('reservations.status', $activeReservationStatuses)
                ->where('reservations.check_in_date', '<', $checkOutDate)
                ->where('reservations.check_out_date', '>', $checkInDate);
        };

        $availableRooms = Room::query()
            ->with(['roomType', 'floor'])
            ->where('is_active', true)
            ->whereNotIn('status', $excludedRoomStatuses)
            ->whereDoesntHave('reservations', $reservationOverlapsWindow)
            ->orderBy('room_number')
            ->get();

        $roomsWithOverlaps = Room::query()
            ->with([
                'roomType',
                'floor',
                'reservations' => function ($query) use ($checkInDate, $checkOutDate, $activeReservationStatuses) {
                    $query
                        ->whereIn('reservations.status', $activeReservationStatuses)
                        ->where('reservations.check_in_date', '<', $checkOutDate)
                        ->where('reservations.check_out_date', '>', $checkInDate)
                        ->orderBy('reservations.check_in_date');
                },
            ])
            ->where('is_active', true)
            ->whereNotIn('status', $excludedRoomStatuses)
            ->whereHas('reservations', $reservationOverlapsWindow)
            ->orderBy('room_number')
            ->get();

        $windowStart = Carbon::parse($checkInDate)->startOfDay();
        $windowEnd = Carbon::parse($checkOutDate)->startOfDay();

        $partiallyAvailableRooms = $roomsWithOverlaps
            ->map(function ($room) use ($windowStart, $windowEnd) {
                $availableRanges = $this->freeDateRangesWithinWindow($windowStart, $windowEnd, $room->reservations);

                if (empty($availableRanges)) {
                    return null;
                }

                return [
                    'room' => $room,
                    'availableRanges' => $availableRanges,
                ];
            })
            ->filter()
            ->values();

        if ($availableRooms->isEmpty() && $partiallyAvailableRooms->isEmpty()) {
            $missingMessage = 'No rooms available within the selected period.';
        }

        return view('admin.reservations.walkin', [
            'availableRooms' => $availableRooms,
            'partiallyAvailableRooms' => $partiallyAvailableRooms,
            'missingMessage' => $missingMessage,
            'checkInDate' => $checkInDate,
            'checkOutDate' => $checkOutDate,
        ]);
    }
}
