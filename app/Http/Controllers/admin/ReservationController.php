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
use App\Models\Company;
use App\Models\TravelAgent;
use App\Models\Loyalty;
use App\Models\ReservationRoom;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\RoomBlockRoom;
use App\Models\RoomBlock;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

        $isCheckedIn = $reservation->reservationRooms
            && $reservation->reservationRooms->contains(fn ($rr) => ($rr->status ?? null) === 'occupied');

        if (!$isCheckedIn) {
            return redirect()->back()->with('error', 'Only checked-in reservations can be checked out.');
        }

        DB::transaction(function () use ($reservation) {
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
        $hasOccupiedRooms = $reservation->reservationRooms
            && $reservation->reservationRooms->contains(fn ($rr) => ($rr->status ?? null) === 'occupied');

        if ($hasOccupiedRooms) {
            return redirect()->back()->with('error', 'Checked-in reservations cannot be cancelled.');
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

            // If this reservation was created by converting a room block, return inventory to the block (if still active).
            $blockRoom = RoomBlockRoom::query()
                ->where('reservation_id', $reservation->id)
                ->lockForUpdate()
                ->first();

            if ($blockRoom) {
                $block = RoomBlock::query()->whereKey($blockRoom->room_block_id)->lockForUpdate()->first();

                $blockIsActive = $block
                    && ($block->status ?? null) !== 'cancelled'
                    && empty($block->released_at)
                    && (empty($block->release_at) || $block->release_at->greaterThan(now()));

                $blockRoom->reservation_id = null;
                $blockRoom->status = $blockIsActive ? 'blocked' : 'released';
                $blockRoom->save();
            }
        });

        return redirect()->route('admin.reservations.index')->with('success', 'Reservation cancelled successfully.');
    }

    public function destroy($id)
    {
        $reservation = Reservation::with(['reservationRooms'])->findOrFail($id);

        $hasOccupiedRooms = $reservation->reservationRooms
            && $reservation->reservationRooms->contains(fn ($rr) => ($rr->status ?? null) === 'occupied');

        if ($hasOccupiedRooms) {
            return redirect()->back()->with('error', 'Checked-in reservations cannot be deleted.');
        }

        $reservation->delete();

        return redirect()->route('admin.reservations.index')->with('success', 'Reservation deleted successfully.');
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

        $blockEvents = collect();

        if (!empty($roomId)) {
            $blockEvents = RoomBlockRoom::query()
                ->with(['roomBlock'])
                ->where('room_id', (int) $roomId)
                ->where('status', 'blocked')
                ->whereHas('roomBlock', function ($q) use ($monthStart, $monthEndExclusive) {
                    $q->active()
                        ->when($monthStart && $monthEndExclusive, function ($q2) use ($monthStart, $monthEndExclusive) {
                            $q2
                                ->where('start_date', '<', $monthEndExclusive->toDateString())
                                ->where('end_date', '>', $monthStart->toDateString());
                        });
                })
                ->get()
                ->map(function ($row) {
                    $block = $row->roomBlock;
                    if (!$block || empty($block->start_date) || empty($block->end_date)) {
                        return null;
                    }

                    $startDate = optional($block->start_date)->toDateString();
                    $endDate = optional($block->end_date)->toDateString();
                    if ($endDate !== null && $startDate !== null && $endDate <= $startDate) {
                        $endDate = optional($block->start_date)->copy()->addDay()->toDateString();
                    }

                    $title = 'Blocked - ' . (string) ($block->group_name ?? 'Group');

                    return [
                        'id' => 'block-' . $block->id,
                        'title' => $title,
                        'start' => $startDate,
                        'end' => $endDate,
                        'allDay' => true,
                        'type' => 'room_block',
                        'room_block_id' => $block->id,
                    ];
                })
                ->filter()
                ->values();
        }

        return view('admin.reservations.calendarByroom', [
            'rooms' => $rooms,
            'roomCalendarEvents' => $roomCalendarEvents->map(function ($ev) {
                $ev['type'] = 'reservation';
                return $ev;
            })->values()->merge($blockEvents)->values(),
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
        $datesRaw = (string) $request->input('dates', '');

        $selectedDates = collect(array_filter(array_map('trim', explode(',', $datesRaw))))
            ->filter(fn ($value) => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $value))
            ->unique()
            ->sort()
            ->values();

        $rawSegments = [];
        if ($selectedDates->isNotEmpty()) {
            $segment = null;
            $prev = null;
            foreach ($selectedDates as $dateStr) {
                try {
                    $cur = Carbon::parse($dateStr)->startOfDay();
                } catch (\Throwable $e) {
                    continue;
                }

                if (!$segment) {
                    $segment = ['start' => $cur, 'end' => $cur];
                    $prev = $cur;
                    continue;
                }

                $expectedNext = $prev->copy()->addDay();
                if ($cur->equalTo($expectedNext)) {
                    $segment['end'] = $cur;
                } else {
                    $rawSegments[] = $segment;
                    $segment = ['start' => $cur, 'end' => $cur];
                }

                $prev = $cur;
            }

            if ($segment) {
                $rawSegments[] = $segment;
            }
        }

        $dateSegments = collect($rawSegments)
            ->map(function ($seg) {
                $start = $seg['start'];
                $end = $seg['end'];

                $checkIn = $start->toDateString();
                $checkOut = $end->copy()->addDay()->toDateString();
                $nights = max(1, $end->diffInDays($start) + 1);

                $dates = [];
                $days = $end->diffInDays($start);
                for ($i = 0; $i <= $days; $i++) {
                    $dates[] = $start->copy()->addDays($i)->toDateString();
                }

                return [
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'nights' => $nights,
                    'dates' => $dates,
                ];
            })
            ->values();

        $guests = Guest::query()
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get(['id', 'first_name', 'last_name', 'id_number', 'email', 'phone', 'id_type']);

        $companies = Company::query()->orderBy('name')->get(['id', 'name']);
        $travelAgents = TravelAgent::query()->orderBy('name')->get(['id', 'name']);
        $loyalties = Loyalty::query()->orderBy('points_required')->get(['id', 'level_name']);

        return view('admin.reservations.reservationCreate', [
            'rooms' => $rooms,
            'roomId' => $roomId,
            'selectedDates' => $selectedDates,
            'dateSegments' => $dateSegments,
            'guests' => $guests,
            'companies' => $companies,
            'travelAgents' => $travelAgents,
            'loyalties' => $loyalties,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'dates' => ['required', 'string', 'max:20000'],
            'guest_id' => ['required', 'integer', 'exists:guests,id'],
            'adults' => ['nullable', 'integer', 'min:1'],
            'children' => ['nullable', 'integer', 'min:0'],
            'special_requests' => ['nullable', 'string', 'max:4000'],
            'note' => ['nullable', 'string', 'max:2000'],
            'ignore_blocks' => ['nullable', 'boolean'],
        ]);

        $room = Room::query()->with(['roomType'])->findOrFail((int) $data['room_id']);
        $guest = Guest::query()->findOrFail((int) $data['guest_id']);
        $activeReservationStatuses = ['booked', 'confirmed'];

        $segments = [];
        $datesRaw = (string) ($data['dates'] ?? '');
        $selectedDates = collect(array_filter(array_map('trim', explode(',', $datesRaw))))
            ->filter(fn ($value) => preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $value))
            ->unique()
            ->sort()
            ->values();

        if ($selectedDates->isEmpty()) {
            return back()->withErrors(['dates' => 'Please select dates from the calendar.'])->withInput();
        }

        $rawSegments = [];
        $segment = null;
        $prev = null;
        foreach ($selectedDates as $dateStr) {
            try {
                $cur = Carbon::parse($dateStr)->startOfDay();
            } catch (\Throwable $e) {
                continue;
            }

            if (!$segment) {
                $segment = ['start' => $cur, 'end' => $cur];
                $prev = $cur;
                continue;
            }

            $expectedNext = $prev->copy()->addDay();
            if ($cur->equalTo($expectedNext)) {
                $segment['end'] = $cur;
            } else {
                $rawSegments[] = $segment;
                $segment = ['start' => $cur, 'end' => $cur];
            }

            $prev = $cur;
        }

        if ($segment) {
            $rawSegments[] = $segment;
        }

        $segments = collect($rawSegments)
            ->map(function ($seg) {
                $start = $seg['start'];
                $end = $seg['end'];
                return [
                    'check_in' => $start->toDateString(),
                    'check_out' => $end->copy()->addDay()->toDateString(),
                    'nights' => max(1, $end->diffInDays($start) + 1),
                ];
            })
            ->values()
            ->all();

        if (empty($segments)) {
            return back()->withErrors(['dates' => 'No valid dates provided.'])->withInput();
        }

        // Overlap check per segment before saving anything.
        foreach ($segments as $i => $seg) {
            $segIn = $seg['check_in'];
            $segOut = $seg['check_out'];

            $overlaps = Reservation::query()
                ->whereIn('status', $activeReservationStatuses)
                ->where('check_in_date', '<', $segOut)
                ->where('check_out_date', '>', $segIn)
                ->whereHas('rooms', function ($q) use ($room) {
                    $q->where('rooms.id', $room->id);
                })
                ->exists();

            if ($overlaps) {
                $label = count($segments) > 1
                    ? ('Stay #' . ($i + 1) . ' (' . $segIn . ' - ' . $segOut . ')')
                    : ('Selected range (' . $segIn . ' - ' . $segOut . ')');

                return back()
                    ->withErrors(['dates' => $label . ' overlaps an existing reservation for this room.'])
                    ->withInput();
            }

            $ignoreBlocks = (bool) ($data['ignore_blocks'] ?? false);
            if (!$ignoreBlocks) {
                $blockedByRoomBlock = $room->roomBlocks()
                    ->active()
                    ->where('room_blocks.start_date', '<', $segOut)
                    ->where('room_blocks.end_date', '>', $segIn)
                    ->where('room_block_rooms.status', 'blocked')
                    ->exists();

                if ($blockedByRoomBlock) {
                    $label = count($segments) > 1
                        ? ('Stay #' . ($i + 1) . ' (' . $segIn . ' - ' . $segOut . ')')
                        : ('Selected range (' . $segIn . ' - ' . $segOut . ')');

                    return back()
                        ->withErrors(['dates' => $label . ' overlaps an active room block for this room. Use Override Blocks to proceed.'])
                        ->withInput();
                }
            }
        }

        $adults = (int) ($data['adults'] ?? 1);
        $children = (int) ($data['children'] ?? 0);
        $nightlyRate = (float) ($room->roomType?->base_price ?? 0);

        $ignoreBlocks = (bool) ($data['ignore_blocks'] ?? false);

        try {
            $reservations = DB::transaction(function () use ($data, $room, $guest, $segments, $adults, $children, $nightlyRate, $ignoreBlocks, $activeReservationStatuses) {
                // Lock the room row to serialize concurrent bookings for the same room
                $lockedRoom = Room::query()->whereKey($room->id)->lockForUpdate()->first();
                if (!$lockedRoom) {
                    throw ValidationException::withMessages([
                        'room_id' => 'Selected room was not found.',
                    ]);
                }

                // Re-check overlaps inside the transaction (race-condition safe)
                foreach ($segments as $seg) {
                    $segIn = $seg['check_in'];
                    $segOut = $seg['check_out'];

                    $overlaps = Reservation::query()
                        ->whereIn('status', $activeReservationStatuses)
                        ->where('check_in_date', '<', $segOut)
                        ->where('check_out_date', '>', $segIn)
                        ->whereHas('rooms', function ($q) use ($lockedRoom) {
                            $q->where('rooms.id', $lockedRoom->id);
                        })
                        ->exists();

                    if ($overlaps) {
                        throw ValidationException::withMessages([
                            'dates' => 'Selected dates overlap an existing reservation for this room.',
                        ]);
                    }

                    if (!$ignoreBlocks) {
                        $blockedByRoomBlock = $lockedRoom->roomBlocks()
                            ->active()
                            ->where('room_blocks.start_date', '<', $segOut)
                            ->where('room_blocks.end_date', '>', $segIn)
                            ->where('room_block_rooms.status', 'blocked')
                            ->exists();

                        if ($blockedByRoomBlock) {
                            throw ValidationException::withMessages([
                                'dates' => 'Selected dates overlap an active room block for this room. Use Override Blocks to proceed.',
                            ]);
                        }
                    }
                }

                $noteParts = [];
                if ($ignoreBlocks) {
                    $noteParts[] = 'Admin Override: Room Blocks ignored.';
                }
                if (!empty($data['special_requests'])) {
                    $noteParts[] = 'Special Requests: ' . trim((string) $data['special_requests']);
                }
                if (!empty($data['note'])) {
                    $noteParts[] = trim((string) $data['note']);
                }
                $finalNote = empty($noteParts) ? null : implode("\n\n", $noteParts);

            $created = [];
            foreach ($segments as $seg) {
                $reservationCode = null;
                for ($i = 0; $i < 5; $i++) {
                    $candidate = 'RSV-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
                    if (!Reservation::where('reservation_code', $candidate)->exists()) {
                        $reservationCode = $candidate;
                        break;
                    }
                }

                $nights = (int) ($seg['nights'] ?? 1);
                $totalAmount = $nightlyRate * $nights;

                $reservation = Reservation::create([
                    'guest_id' => $guest->id,
                    'reservation_code' => $reservationCode,
                    'channel' => 'admin-calendar',
                    'status' => 'confirmed',
                    'payment_status' => 'unpaid',
                    'check_in_date' => $seg['check_in'],
                    'check_out_date' => $seg['check_out'],
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

                $created[] = $reservation;
            }

            $lockedRoom->status = 'reserved';
            $lockedRoom->save();

            return $created;
            });
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }

        if (count($reservations) === 1) {
            return redirect()->route('admin.reservations.show', $reservations[0]->id)
                ->with('success', 'Reservation created successfully.');
        }

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Created ' . count($reservations) . ' reservations successfully.');
    }

    public function reservation(Request $request)
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
            return view('admin.reservations.reservation', [
                'availableRooms' => $availableRooms,
                'partiallyAvailableRooms' => $partiallyAvailableRooms,
                'missingMessage' => $missingMessage,
                'checkInDate' => null,
                'checkOutDate' => null,
            ]);
        }

        if (empty($checkInDateRaw) || empty($checkOutDateRaw)) {
            $missingMessage = 'Missing check-in or check-out date.';
            return view('admin.reservations.reservation', [
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
            return view('admin.reservations.reservation', [
                'availableRooms' => $availableRooms,
                'partiallyAvailableRooms' => $partiallyAvailableRooms,
                'missingMessage' => $missingMessage,
                'checkInDate' => $checkInDateRaw,
                'checkOutDate' => $checkOutDateRaw,
            ]);
        }

        if ($checkOutDate < $checkInDate) {
            $missingMessage = 'Check-out date must be the same as or after check-in date.';
            return view('admin.reservations.reservation', [
                'availableRooms' => $availableRooms,
                'partiallyAvailableRooms' => $partiallyAvailableRooms,
                'missingMessage' => $missingMessage,
                'checkInDate' => $checkInDate,
                'checkOutDate' => $checkOutDate,
            ]);
        }

        $activeReservationStatuses = ['booked', 'confirmed'];
        $excludedRoomStatuses = ['maintenance', 'out_of_service'];

        $ignoreBlocks = (bool) $request->boolean('ignore_blocks');

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
            ->when(!$ignoreBlocks, function ($q) use ($checkInDate, $checkOutDate) {
                $q->whereDoesntHave('roomBlocks', function ($blockQuery) use ($checkInDate, $checkOutDate) {
                    $blockQuery
                        ->active()
                        ->where('room_blocks.start_date', '<', $checkOutDate)
                        ->where('room_blocks.end_date', '>', $checkInDate)
                        ->where('room_block_rooms.status', 'blocked');
                });
            })
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

        return view('admin.reservations.reservation', [
            'availableRooms' => $availableRooms,
            'partiallyAvailableRooms' => $partiallyAvailableRooms,
            'missingMessage' => $missingMessage,
            'checkInDate' => $checkInDate,
            'checkOutDate' => $checkOutDate,
        ]);
    }

    public function storeReservation(Request $request)
    {
        $data = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'check_in_date' => ['required', 'date'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'ignore_blocks' => ['nullable', 'boolean'],
        ]);

        $start = Carbon::parse($data['check_in_date'])->startOfDay();
        $end = Carbon::parse($data['check_out_date'])->startOfDay();

        $dates = [];
        $cursor = $start->copy();
        while ($cursor->lessThan($end)) {
            $dates[] = $cursor->toDateString();
            $cursor->addDay();
        }

        if (empty($dates)) {
            return back()->withErrors(['check_out_date' => 'Check-out must be at least 1 day after check-in.'])->withInput();
        }

        return redirect()->route('admin.reservations.create', [
            'room_id' => (int) $data['room_id'],
            'dates' => implode(',', $dates),
        ]);
    }
}
