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
        $reservations = Reservation::with(['guest', 'rooms'])->get();

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

                $titleParts = [];
                if (!empty($reservation->reservation_code)) {
                    $titleParts[] = $reservation->reservation_code;
                }
                $titleParts[] = $guestName;
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
