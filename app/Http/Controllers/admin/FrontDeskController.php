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
    public function arrivals()
    {
        $today = Carbon::today();

        $reservations = Reservation::query()
            ->with([
                'guest:id,first_name,last_name,phone',
                'reservationRooms.room:id,room_number,status',
            ])
            ->whereDate('check_in_date', $today)
            ->whereIn('status', ['booked', 'confirmed'])
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

    public function departures()
    {
        return view('admin.frontDesk.departures');
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
