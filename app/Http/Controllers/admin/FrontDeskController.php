<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HousekeepingTask;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\Room;
use App\Models\Stay;
use App\Services\InHouseGuestsService;
use App\Services\RoomAvailabilityService;
use App\Services\RoomRackService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FrontDeskController extends Controller
{
    public function inHouse(Request $request, InHouseGuestsService $inHouseGuests)
    {
        $today = Carbon::today();
        $now = now();

        $filters = $inHouseGuests->filtersFromRequest($request);
        $stays = $inHouseGuests->paginate($filters, $today, 15);
        $stays = $inHouseGuests->attachComputedFields($stays, $today, $now);
        $roomTypes = $inHouseGuests->roomTypes();
        $floors = $inHouseGuests->floors();

        return view('admin.frontDesk.in-house', [
            'today' => $today,
            'now' => $now,
            'filters' => $filters,
            'roomTypes' => $roomTypes,
            'floors' => $floors,
            'stays' => $stays,
        ]);
    }

    public function showInHouse(Stay $stay, RoomAvailabilityService $availability)
    {
        $stay->load([
            'reservation:id,guest_id,reservation_code,check_in_date,check_out_date,status,channel,adults,children',
            'reservation.guest:id,first_name,last_name,phone,email,address,vip,notes',
            'room:id,room_number,room_type_id,floor_id,status',
            'room.roomType:id,name,capacity_adults,capacity_children,base_price',
            'room.floor:id,name,level_number',
        ]);

        $today = Carbon::today();
        $now = now();

        $checkIn = $stay->check_in_time ? Carbon::parse($stay->check_in_time) : null;
        $nightsStayed = $checkIn
            ? $checkIn->copy()->startOfDay()->diffInDays($now->copy()->startOfDay())
            : 0;

        $expectedCheckOut = $stay->reservation?->check_out_date
            ? Carbon::parse($stay->reservation->check_out_date)->startOfDay()
            : null;
        $isOverstay = $expectedCheckOut ? $today->copy()->startOfDay()->gt($expectedCheckOut) : false;

        $isVip = (bool) ($stay->reservation?->guest?->vip ?? false);

        $rangeFromCarbon = Carbon::today()->startOfDay();
        $rangeToCarbon = $stay->reservation?->check_out_date
            ? Carbon::parse($stay->reservation->check_out_date)->startOfDay()
            : $rangeFromCarbon->copy()->addDay();

        if ($rangeToCarbon->lte($rangeFromCarbon)) {
            $rangeToCarbon = $rangeFromCarbon->copy()->addDay();
        }

        $rangeFrom = $rangeFromCarbon->toDateString();
        $rangeTo = $rangeToCarbon->toDateString();

        $availableRoomsQuery = Room::query()
            ->select(['rooms.id', 'rooms.room_number', 'rooms.room_type_id', 'rooms.floor_id', 'rooms.status'])
            ->with([
                'roomType:id,name',
                'floor:id,name,level_number',
            ]);

        $availability->constrainToAvailableRooms($availableRoomsQuery, $rangeFrom, $rangeTo);

        $availableRooms = $availableRoomsQuery
            ->orderBy('room_number')
            ->limit(300)
            ->get();

        return view('admin.frontDesk.in-house-show', [
            'stay' => $stay,
            'today' => $today,
            'nightsStayed' => $nightsStayed,
            'expectedCheckOut' => $expectedCheckOut,
            'isOverstay' => $isOverstay,
            'isVip' => $isVip,
            'availableRooms' => $availableRooms,
        ]);
    }

    public function checkOutInHouse(Request $request, Stay $stay)
    {
        $request->validate([
            'confirm' => ['required', 'in:1'],
        ]);

        try {
            DB::transaction(function () use ($stay) {
                $lockedStay = Stay::query()
                    ->whereKey($stay->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (($lockedStay->status ?? null) !== 'in_house' || $lockedStay->check_out_time !== null) {
                    throw new \RuntimeException('Only in-house stays can be checked out.');
                }

                $lockedStay->check_out_time = now();
                $lockedStay->status = 'checked_out';
                $lockedStay->save();

                ReservationRoom::query()
                    ->where('reservation_id', $lockedStay->reservation_id)
                    ->where('room_id', $lockedStay->room_id)
                    ->lockForUpdate()
                    ->update(['status' => 'released']);

                Room::query()
                    ->whereKey($lockedStay->room_id)
                    ->update(['status' => 'dirty']);
            });
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.front-desk.in-house')
            ->with('success', 'Guest checked out successfully. Room marked as dirty.');
    }

    public function extendInHouse(Request $request, Stay $stay)
    {
        $validated = $request->validate([
            'check_out_date' => ['required', 'date'],
        ]);

        $newCheckOut = Carbon::parse($validated['check_out_date'])->startOfDay();
        $today = Carbon::today();
        if ($newCheckOut->lt($today)) {
            return redirect()->back()->with('error', 'Check-out date cannot be in the past.');
        }

        try {
            DB::transaction(function () use ($stay, $newCheckOut) {
                $lockedStay = Stay::query()
                    ->whereKey($stay->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (($lockedStay->status ?? null) !== 'in_house' || $lockedStay->check_out_time !== null) {
                    throw new \RuntimeException('Only in-house stays can be extended.');
                }

                $reservation = Reservation::query()
                    ->whereKey($lockedStay->reservation_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($reservation->check_in_date) {
                    $checkInDate = Carbon::parse($reservation->check_in_date)->startOfDay();
                    if ($newCheckOut->lt($checkInDate)) {
                        throw new \RuntimeException('Check-out date must be on or after the reservation check-in date.');
                    }
                }

                $reservation->check_out_date = $newCheckOut;
                $reservation->save();
            });
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Stay extended successfully.');
    }

    public function changeRoomInHouse(Request $request, Stay $stay, RoomAvailabilityService $availability)
    {
        $validated = $request->validate([
            'new_room_id' => ['required', 'integer', 'min:1', Rule::exists('rooms', 'id')],
            'confirm' => ['required', 'in:1'],
        ]);

        $newRoomId = (int) $validated['new_room_id'];

        try {
            DB::transaction(function () use ($stay, $newRoomId, $availability) {
                $lockedStay = Stay::query()
                    ->whereKey($stay->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (($lockedStay->status ?? null) !== 'in_house' || $lockedStay->check_out_time !== null) {
                    throw new \RuntimeException('Only in-house stays can change rooms.');
                }

                if ((int) $lockedStay->room_id === $newRoomId) {
                    throw new \RuntimeException('Guest is already assigned to this room.');
                }

                $oldRoomId = (int) $lockedStay->room_id;

                $oldRoom = Room::query()->whereKey($oldRoomId)->lockForUpdate()->first();

                $reservation = Reservation::query()
                    ->whereKey($lockedStay->reservation_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $rangeFromCarbon = Carbon::today()->startOfDay();
                $rangeToCarbon = $reservation->check_out_date
                    ? Carbon::parse($reservation->check_out_date)->startOfDay()
                    : $rangeFromCarbon->copy()->addDay();

                if ($rangeToCarbon->lte($rangeFromCarbon)) {
                    $rangeToCarbon = $rangeFromCarbon->copy()->addDay();
                }

                $rangeFrom = $rangeFromCarbon->toDateString();
                $rangeTo = $rangeToCarbon->toDateString();

                $availabilityCheckQuery = Room::query()->whereKey($newRoomId);
                $availability->constrainToAvailableRooms($availabilityCheckQuery, $rangeFrom, $rangeTo);
                $isAvailableForRange = $availabilityCheckQuery->exists();
                if (!$isAvailableForRange) {
                    throw new \RuntimeException('Selected room is not available for the current stay dates.');
                }

                $newRoom = Room::query()->whereKey($newRoomId)->lockForUpdate()->firstOrFail();

                $newRoomHasActiveStay = Stay::query()
                    ->where('room_id', $newRoomId)
                    ->where('status', 'in_house')
                    ->whereNull('check_out_time')
                    ->lockForUpdate()
                    ->exists();
                if ($newRoomHasActiveStay) {
                    throw new \RuntimeException('Selected room is currently occupied.');
                }

                // Release old reservation-room link for this stay.
                ReservationRoom::query()
                    ->where('reservation_id', $lockedStay->reservation_id)
                    ->where('room_id', $oldRoomId)
                    ->lockForUpdate()
                    ->update(['status' => 'released']);

                // Ensure a reservation-room record exists for the new room.
                $reservationRoom = ReservationRoom::query()
                    ->where('reservation_id', $lockedStay->reservation_id)
                    ->where('room_id', $newRoomId)
                    ->lockForUpdate()
                    ->first();

                if (!$reservationRoom) {
                    ReservationRoom::create([
                        'reservation_id' => $lockedStay->reservation_id,
                        'room_id' => $newRoomId,
                        'room_type_id' => $newRoom->room_type_id,
                        'status' => 'occupied',
                    ]);
                } else {
                    $reservationRoom->room_type_id = $reservationRoom->room_type_id ?: $newRoom->room_type_id;
                    $reservationRoom->status = 'occupied';
                    $reservationRoom->save();
                }

                // Move the stay.
                $lockedStay->room_id = $newRoomId;
                $lockedStay->save();

                // Update room statuses.
                if ($oldRoom) {
                    $oldRoom->status = 'dirty';
                    $oldRoom->save();
                }
                $newRoom->status = 'occupied';
                $newRoom->save();
            });
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Room changed successfully. Old room marked as dirty.');
    }

    public function addNoteInHouse(Request $request, Stay $stay)
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'max:2000'],
        ]);

        $note = trim($validated['note']);
        if ($note === '') {
            return redirect()->back()->with('error', 'Note cannot be empty.');
        }

        try {
            DB::transaction(function () use ($stay, $note) {
                $lockedStay = Stay::query()
                    ->whereKey($stay->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $reservation = Reservation::query()
                    ->whereKey($lockedStay->reservation_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $guest = $reservation->guest()
                    ->lockForUpdate()
                    ->first();

                if (!$guest) {
                    throw new \RuntimeException('Guest record not found.');
                }

                $timestamp = now()->format('Y-m-d H:i');
                $entry = '[' . $timestamp . '] ' . $note;
                $existing = (string) ($guest->notes ?? '');

                $guest->notes = trim($existing === '' ? $entry : ($existing . "\n\n" . $entry));
                $guest->save();
            });
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Note added to guest profile.');
    }

    public function index(Request $request)
    {
        return $this->inHouse($request);
    }

    public function arrivals(Request $request)
    {
        $today = Carbon::today();

        $q = trim((string) $request->query('q', ''));

        $reservations = Reservation::query()
            ->with([
                'guest:id,first_name,last_name,phone',
                'reservationRooms.room:id,room_number,status',
            ])
            ->whereDate('check_in_date', $today)
            ->whereIn('status', ['booked', 'confirmed'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner
                        ->where('reservation_code', 'like', '%' . $q . '%')
                        ->orWhere('id', $q)
                        ->orWhereHas('guest', function ($guestQuery) use ($q) {
                            $guestQuery
                                ->where('first_name', 'like', '%' . $q . '%')
                                ->orWhere('last_name', 'like', '%' . $q . '%')
                                ->orWhere('phone', 'like', '%' . $q . '%');
                        })
                        ->orWhereHas('reservationRooms.room', function ($roomQuery) use ($q) {
                            $roomQuery->where('room_number', 'like', '%' . $q . '%');
                        });
                });
            })
            ->orderBy('check_in_date')
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.frontDesk.arrivals', [
            'today' => $today,
            'reservations' => $reservations,
        ]);
    }

    public function showCheckIn(Reservation $reservation)
    {
        $reservation->load([
            'guest',
            'reservationRooms.room.floor',
            'reservationRooms.roomType',
        ]);

        return view('admin.frontDesk.checkin', [
            'reservation' => $reservation,
        ]);
    }

    public function storeCheckIn(Request $request, Reservation $reservation)
    {
        $request->validate([
            'confirm' => ['required', 'in:1'],
        ]);

        $today = Carbon::today();

        try {
            DB::transaction(function () use ($reservation, $today) {
                $reservation = Reservation::query()
                    ->whereKey($reservation->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!in_array(($reservation->status ?? null), ['booked', 'confirmed'], true)) {
                    throw new \RuntimeException('Only booked or confirmed reservations can be checked in.');
                }

                if (!$reservation->check_in_date || $reservation->check_in_date->toDateString() !== $today->toDateString()) {
                    throw new \RuntimeException("Only today's arrivals can be checked in from this screen.");
                }

                $reservationRooms = ReservationRoom::query()
                    ->with('room:id,status')
                    ->where('reservation_id', $reservation->id)
                    ->lockForUpdate()
                    ->get();

                if ($reservationRooms->isEmpty()) {
                    throw new \RuntimeException('No rooms are assigned to this reservation.');
                }

                $alreadyCheckedIn = $reservationRooms->contains(fn ($rr) => ($rr->status ?? null) === 'occupied');
                if ($alreadyCheckedIn) {
                    throw new \RuntimeException('This reservation is already checked in.');
                }

                $hasOpenStay = Stay::query()
                    ->where('reservation_id', $reservation->id)
                    ->whereNull('check_out_time')
                    ->lockForUpdate()
                    ->exists();
                if ($hasOpenStay) {
                    throw new \RuntimeException('This reservation already has an active stay.');
                }

                if (($reservation->status ?? null) === 'booked') {
                    $reservation->status = 'confirmed';
                    $reservation->save();
                }

                $checkInTime = now();
                foreach ($reservationRooms as $rr) {
                    Stay::create([
                        'reservation_id' => $reservation->id,
                        'room_id' => $rr->room_id,
                        'check_in_time' => $checkInTime,
                        'check_out_time' => null,
                        'status' => 'in_house',
                        'adults' => (int) ($reservation->adults ?? 1),
                        'children' => (int) ($reservation->children ?? 0),
                    ]);
                }

                ReservationRoom::where('reservation_id', $reservation->id)
                    ->update(['status' => 'occupied']);

                $roomIds = $reservationRooms->pluck('room_id')->filter()->values();
                if ($roomIds->isNotEmpty()) {
                    Room::whereIn('id', $roomIds)->update(['status' => 'occupied']);
                }
            });
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.front-desk.arrivals')->with('success', 'Guest checked in successfully.');
    }

    public function departures(Request $request)
    {
        $today = Carbon::today();

        $view = (string) $request->query('view', 'due');
        if (!in_array($view, ['due', 'checked_out'], true)) {
            $view = 'due';
        }

        $q = trim((string) $request->query('q', ''));
        $includeOverdue = $view === 'due' ? (bool) $request->boolean('include_overdue') : false;

        $staysQuery = Stay::query()
            ->select('stays.*')
            ->join('reservations', 'reservations.id', '=', 'stays.reservation_id')
            ->join('rooms', 'rooms.id', '=', 'stays.room_id')
            ->with([
                'reservation:id,guest_id,reservation_code,check_in_date,check_out_date',
                'reservation.guest:id,first_name,last_name,phone',
                'room:id,room_number,status',
            ])
            ->when($view === 'due', function ($query) use ($includeOverdue, $today) {
                $query
                    ->where('stays.status', 'in_house')
                    ->whereNull('stays.check_out_time')
                    ->when(
                        $includeOverdue,
                        fn ($inner) => $inner->whereDate('reservations.check_out_date', '<=', $today),
                        fn ($inner) => $inner->whereDate('reservations.check_out_date', $today)
                    );
            })
            ->when($view === 'checked_out', function ($query) use ($today) {
                $query
                    ->where('stays.status', 'checked_out')
                    ->whereNotNull('stays.check_out_time')
                    ->whereDate('stays.check_out_time', $today);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner
                        ->where('reservations.reservation_code', 'like', '%' . $q . '%')
                        ->orWhere('reservations.id', $q)
                        ->orWhereHas('reservation.guest', function ($guestQuery) use ($q) {
                            $guestQuery
                                ->where('first_name', 'like', '%' . $q . '%')
                                ->orWhere('last_name', 'like', '%' . $q . '%')
                                ->orWhere('phone', 'like', '%' . $q . '%');
                        })
                        ->orWhereHas('room', function ($roomQuery) use ($q) {
                            $roomQuery->where('room_number', 'like', '%' . $q . '%');
                        });
                });
            });

        if ($view === 'checked_out') {
            $staysQuery
                ->orderBy('stays.check_out_time', 'desc')
                ->orderBy('rooms.room_number');
        } else {
            $staysQuery
                ->orderBy('reservations.check_out_date')
                ->orderBy('rooms.room_number')
                ->orderBy('stays.id', 'desc');
        }

        $stays = $staysQuery
            ->paginate(15)
            ->withQueryString();

        return view('admin.frontDesk.departures', [
            'today' => $today,
            'view' => $view,
            'q' => $q,
            'includeOverdue' => $includeOverdue,
            'stays' => $stays,
        ]);
    }

    public function showCheckOut(Stay $stay)
    {
        $stay->load([
            'reservation.guest',
            'reservation.reservationRooms.room.floor',
            'reservation.reservationRooms.roomType',
            'room.floor',
            'room.roomType',
        ]);

        if (($stay->status ?? null) !== 'in_house' || $stay->check_out_time !== null) {
            return redirect()->route('admin.front-desk.departures')->with('error', 'Only in-house stays can be checked out.');
        }

        return view('admin.frontDesk.checkout', [
            'stay' => $stay,
        ]);
    }

    public function storeCheckOut(Request $request, Stay $stay)
    {
        $request->validate([
            'confirm' => ['required', 'in:1'],
        ]);

        try {
            DB::transaction(function () use ($stay) {
                $lockedStay = Stay::query()
                    ->whereKey($stay->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (($lockedStay->status ?? null) !== 'in_house' || $lockedStay->check_out_time !== null) {
                    throw new \RuntimeException('This stay is already checked out.');
                }

                $lockedStay->check_out_time = now();
                $lockedStay->status = 'checked_out';
                $lockedStay->save();

                ReservationRoom::query()
                    ->where('reservation_id', $lockedStay->reservation_id)
                    ->where('room_id', $lockedStay->room_id)
                    ->lockForUpdate()
                    ->update(['status' => 'released']);

                // Mark room as dirty for housekeeping after check-out.
                Room::query()
                    ->whereKey($lockedStay->room_id)
                    ->update(['status' => 'dirty']);
            });
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.front-desk.departures', ['view' => 'checked_out'])
            ->with('success', 'Guest checked out successfully. Room marked as dirty.');
    }

    public function roomRack(Request $request, RoomRackService $roomRack)
    {
        $date = $request->filled('date')
            ? Carbon::parse((string) $request->query('date'))->startOfDay()
            : Carbon::today();

        $filters = $roomRack->filtersFromRequest($request);
        $rack = $roomRack->build($date, $filters);

        return view('admin.frontDesk.room-rack', array_merge($rack, [
            'filters' => $filters,
        ]));
    }

    public function roomRackRoomDetails(Request $request, Room $room)
    {
        $rackDate = $this->rackDateFromRequest($request);

        return response()->json([
            'ok' => true,
            'room' => $this->buildRoomRackModalData($room, $rackDate),
        ]);
    }

    public function roomRackCheckIn(Request $request, Room $room)
    {
        $rackDate = $this->rackDateFromRequest($request);

        try {
            DB::transaction(function () use ($room, $rackDate) {
                $lockedRoom = Room::query()->whereKey($room->id)->lockForUpdate()->firstOrFail();

                $activeStay = Stay::query()
                    ->where('room_id', $lockedRoom->id)
                    ->where('status', 'in_house')
                    ->whereNull('check_out_time')
                    ->lockForUpdate()
                    ->exists();

                if ($activeStay) {
                    throw new \RuntimeException('Room already has an active in-house stay.');
                }

                $reservation = Reservation::query()
                    ->whereIn('status', ['booked', 'confirmed'])
                    ->whereDate('check_in_date', $rackDate)
                    ->whereHas('reservationRooms', function ($query) use ($lockedRoom) {
                        $query
                            ->where('room_id', $lockedRoom->id)
                            ->where('status', 'reserved');
                    })
                    ->lockForUpdate()
                    ->first();

                if (!$reservation) {
                    throw new \RuntimeException('No reserved arrival found for this room on the selected date.');
                }

                $reservationRooms = ReservationRoom::query()
                    ->where('reservation_id', $reservation->id)
                    ->lockForUpdate()
                    ->get();

                if ($reservationRooms->isEmpty()) {
                    throw new \RuntimeException('No rooms are assigned to this reservation.');
                }

                $alreadyCheckedIn = Stay::query()
                    ->where('reservation_id', $reservation->id)
                    ->where('status', 'in_house')
                    ->whereNull('check_out_time')
                    ->lockForUpdate()
                    ->exists();

                if ($alreadyCheckedIn) {
                    throw new \RuntimeException('This reservation already has an active stay.');
                }

                if (($reservation->status ?? null) === 'booked') {
                    $reservation->status = 'confirmed';
                    $reservation->save();
                }

                $checkInTime = now();
                foreach ($reservationRooms as $reservationRoom) {
                    Stay::create([
                        'reservation_id' => $reservation->id,
                        'room_id' => $reservationRoom->room_id,
                        'check_in_time' => $checkInTime,
                        'check_out_time' => null,
                        'status' => 'in_house',
                        'adults' => (int) ($reservation->adults ?? 1),
                        'children' => (int) ($reservation->children ?? 0),
                    ]);
                }

                ReservationRoom::query()
                    ->where('reservation_id', $reservation->id)
                    ->update(['status' => 'occupied']);

                $roomIds = $reservationRooms->pluck('room_id')->filter()->values();
                if ($roomIds->isNotEmpty()) {
                    Room::query()->whereIn('id', $roomIds)->update(['status' => 'occupied']);
                }
            });
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Guest checked in successfully.',
        ]);
    }

    public function roomRackCheckOut(Room $room)
    {
        try {
            DB::transaction(function () use ($room) {
                $lockedRoom = Room::query()->whereKey($room->id)->lockForUpdate()->firstOrFail();

                $stay = Stay::query()
                    ->where('room_id', $lockedRoom->id)
                    ->where('status', 'in_house')
                    ->whereNull('check_out_time')
                    ->lockForUpdate()
                    ->first();

                if (!$stay) {
                    throw new \RuntimeException('No active in-house stay found for this room.');
                }

                $stay->check_out_time = now();
                $stay->status = 'checked_out';
                $stay->save();

                ReservationRoom::query()
                    ->where('reservation_id', $stay->reservation_id)
                    ->where('room_id', $stay->room_id)
                    ->lockForUpdate()
                    ->update(['status' => 'released']);

                if (($lockedRoom->status ?? null) !== 'out_of_service') {
                    $lockedRoom->status = 'dirty';
                    $lockedRoom->save();
                }
            });
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Guest checked out successfully. Room marked as dirty.',
        ]);
    }

    public function roomRackUpdateHousekeeping(Request $request, Room $room)
    {
        $data = $request->validate([
            'state' => ['required', Rule::in(['dirty', 'clean'])],
        ]);

        try {
            DB::transaction(function () use ($room, $data) {
                $lockedRoom = Room::query()->whereKey($room->id)->lockForUpdate()->firstOrFail();
                $targetState = (string) $data['state'];

                $hasActiveStay = Stay::query()
                    ->where('room_id', $lockedRoom->id)
                    ->where('status', 'in_house')
                    ->whereNull('check_out_time')
                    ->lockForUpdate()
                    ->exists();

                if ($hasActiveStay) {
                    throw new \RuntimeException('Cannot change housekeeping state while room is occupied.');
                }

                if (($lockedRoom->status ?? null) === 'out_of_service') {
                    throw new \RuntimeException('Unblock the room first before changing housekeeping state.');
                }

                if ($targetState === 'dirty') {
                    $lockedRoom->status = 'dirty';
                    $lockedRoom->save();

                    return;
                }

                $lockedRoom->status = 'available';
                $lockedRoom->save();

                HousekeepingTask::query()
                    ->where('room_id', $lockedRoom->id)
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->update([
                        'status' => 'done',
                        'completed_at' => now(),
                    ]);
            });
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'message' => $data['state'] === 'dirty' ? 'Room marked dirty.' : 'Room marked clean and available.',
        ]);
    }

    public function roomRackBlock(Room $room)
    {
        try {
            DB::transaction(function () use ($room) {
                $lockedRoom = Room::query()->whereKey($room->id)->lockForUpdate()->firstOrFail();

                $hasActiveStay = Stay::query()
                    ->where('room_id', $lockedRoom->id)
                    ->where('status', 'in_house')
                    ->whereNull('check_out_time')
                    ->lockForUpdate()
                    ->exists();

                if ($hasActiveStay) {
                    throw new \RuntimeException('Cannot block a room with an active in-house stay.');
                }

                $lockedRoom->status = 'out_of_service';
                $lockedRoom->save();
            });
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Room blocked and marked out of order.',
        ]);
    }

    public function roomRackUnblock(Room $room)
    {
        try {
            DB::transaction(function () use ($room) {
                $lockedRoom = Room::query()->whereKey($room->id)->lockForUpdate()->firstOrFail();

                $hasActiveStay = Stay::query()
                    ->where('room_id', $lockedRoom->id)
                    ->where('status', 'in_house')
                    ->whereNull('check_out_time')
                    ->lockForUpdate()
                    ->exists();

                if ($hasActiveStay) {
                    throw new \RuntimeException('Cannot unblock while room has an active stay.');
                }

                if (($lockedRoom->status ?? null) !== 'out_of_service') {
                    throw new \RuntimeException('Room is not currently blocked.');
                }

                $lockedRoom->status = 'available';
                $lockedRoom->save();
            });
        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Room unblocked and available for sale.',
        ]);
    }

    private function rackDateFromRequest(Request $request): Carbon
    {
        if (!$request->filled('rack_date')) {
            return Carbon::today()->startOfDay();
        }

        return Carbon::parse((string) $request->input('rack_date'))->startOfDay();
    }

    private function buildRoomRackModalData(Room $room, Carbon $rackDate): array
    {
        $loadedRoom = Room::query()
            ->select(['id', 'room_number', 'room_type_id', 'floor_id', 'status'])
            ->with([
                'roomType:id,name',
                'floor:id,name,level_number',
            ])
            ->whereKey($room->id)
            ->firstOrFail();

        $activeStay = Stay::query()
            ->with([
                'reservation:id,guest_id,reservation_code,check_in_date,check_out_date,status,channel,adults,children',
                'reservation.guest:id,first_name,last_name,phone,vip',
            ])
            ->where('room_id', $loadedRoom->id)
            ->where('status', 'in_house')
            ->whereNull('check_out_time')
            ->orderByDesc('check_in_time')
            ->first();

        $arrivalReservation = Reservation::query()
            ->with([
                'guest:id,first_name,last_name,phone,vip',
                'reservationRooms:id,reservation_id,room_id,status',
            ])
            ->whereIn('status', ['booked', 'confirmed'])
            ->whereDate('check_in_date', $rackDate)
            ->whereHas('reservationRooms', function ($query) use ($loadedRoom) {
                $query->where('room_id', $loadedRoom->id);
            })
            ->orderBy('id')
            ->first();

        $guest = $activeStay?->reservation?->guest ?: $arrivalReservation?->guest;

        $openHousekeepingTask = HousekeepingTask::query()
            ->where('room_id', $loadedRoom->id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->whereBetween('task_date', [$rackDate->copy()->startOfDay(), $rackDate->copy()->endOfDay()])
            ->orderByDesc('task_date')
            ->first();

        $roomStatus = (string) ($loadedRoom->status ?? 'available');
        $rackStatus = $this->deriveRackStatusForModal($roomStatus, $activeStay !== null, $arrivalReservation !== null, $openHousekeepingTask !== null);

        $stayDurationNights = null;
        if ($activeStay?->check_in_time) {
            $stayDurationNights = Carbon::parse($activeStay->check_in_time)->startOfDay()->diffInDays(now()->startOfDay());
        }

        return [
            'room_id' => $loadedRoom->id,
            'room_number' => (string) ($loadedRoom->room_number ?? '-'),
            'room_type' => (string) ($loadedRoom->roomType?->name ?? '-'),
            'floor_name' => (string) ($loadedRoom->floor?->name ?? '-'),
            'raw_room_status' => $roomStatus,
            'rack_status' => $rackStatus['key'],
            'rack_status_label' => $rackStatus['label'],
            'guest' => [
                'name' => $guest ? trim(($guest->first_name ?? '') . ' ' . ($guest->last_name ?? '')) : null,
                'phone' => $guest?->phone,
                'vip' => (bool) ($guest?->vip ?? false),
            ],
            'reservation' => $arrivalReservation ? [
                'id' => $arrivalReservation->id,
                'code' => $arrivalReservation->reservation_code,
                'status' => $arrivalReservation->status,
                'channel' => $arrivalReservation->channel,
                'check_in_date' => optional($arrivalReservation->check_in_date)->toDateString(),
                'check_out_date' => optional($arrivalReservation->check_out_date)->toDateString(),
                'adults' => (int) ($arrivalReservation->adults ?? 1),
                'children' => (int) ($arrivalReservation->children ?? 0),
            ] : null,
            'stay' => $activeStay ? [
                'id' => $activeStay->id,
                'status' => $activeStay->status,
                'check_in_time' => optional($activeStay->check_in_time)->toDateTimeString(),
                'check_out_time' => optional($activeStay->check_out_time)->toDateTimeString(),
                'nights' => $stayDurationNights,
                'expected_check_out_date' => optional($activeStay->reservation?->check_out_date)->toDateString(),
            ] : null,
            'housekeeping' => [
                'status' => $openHousekeepingTask?->status,
                'priority' => $openHousekeepingTask?->priority,
            ],
            'actions' => [
                'can_check_in' => $rackStatus['key'] === 'reserved' && $arrivalReservation !== null,
                'can_check_out' => $rackStatus['key'] === 'occupied' && $activeStay !== null,
                'can_mark_dirty' => !$activeStay && $roomStatus !== 'out_of_service',
                'can_mark_clean' => !$activeStay && $roomStatus !== 'out_of_service',
                'can_block' => !$activeStay && $roomStatus !== 'out_of_service',
                'can_unblock' => !$activeStay && $roomStatus === 'out_of_service',
            ],
        ];
    }

    private function deriveRackStatusForModal(string $rawRoomStatus, bool $hasActiveStay, bool $hasArrivalReservation, bool $hasOpenHousekeeping): array
    {
        if ($hasActiveStay) {
            return ['key' => 'occupied', 'label' => 'Occupied'];
        }

        if ($hasArrivalReservation) {
            return ['key' => 'reserved', 'label' => 'Reserved'];
        }

        if ($rawRoomStatus === 'dirty' || $hasOpenHousekeeping) {
            return ['key' => 'dirty', 'label' => 'Dirty'];
        }

        if ($rawRoomStatus === 'out_of_service') {
            return ['key' => 'out_of_order', 'label' => 'Out of Order'];
        }

        return ['key' => 'available', 'label' => 'Available'];
    }

    public function walkIn()
    {
        return view('admin.frontDesk.walk-in');
    }

    public function guestRequests()
    {
        return view('admin.frontDesk.guest-requests');
    }
}
