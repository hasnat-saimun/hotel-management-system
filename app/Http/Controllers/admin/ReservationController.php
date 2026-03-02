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
}
