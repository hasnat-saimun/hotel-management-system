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
        $reservation = Reservation::findOrFail($id);
        $reservation->status = 'checked_in';
        $reservation->save();


        return redirect()->route('admin.reservations.index')->with('success', 'Guest checked in successfully.');
    }

    public function checkout($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->status = 'checked_out';
        $reservation->save();

        return redirect()->route('admin.reservations.index')->with('success', 'Guest checked out successfully.');
    }

    public function calendar()
    {
        $reservations = Reservation::with('guest','rooms')->get();
        return view('admin.reservations.calendar', compact('reservations'));
    }

    public function walkin()
    {
        // Get the check-in and check-out dates from the request and find roomid  during that period on reservation room table by resevrvation table through.
        $checkInDate = request()->input('check_in_date');
        $checkOutDate = request()->input('check_out_date');

        $reservations = Reservation::whereBetween('check_in_date', [$checkInDate, $checkOutDate])
            ->orWhereBetween('check_out_date', [$checkInDate, $checkOutDate])
            ->orWhere(function ($query) use ($checkInDate, $checkOutDate) {
                $query->where('check_in_date', '<=', $checkInDate)
                    ->where('check_out_date', '>=', $checkOutDate);
            })
            ->with('reservationRooms')
            ->get();


        return view('admin.reservations.walkin', );
    }
}
