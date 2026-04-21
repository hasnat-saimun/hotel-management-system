<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\ReservationRoom;
use App\Models\Room;
use App\Models\Stay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontDeskController extends Controller
{
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

    public function inHouse()
    {
        return view('admin.frontDesk.in-house');
    }

    public function roomRack()
    {
        return view('admin.frontDesk.room-rack');
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
