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

    public function walkin(Request $request)
    {
        $checkInDateRaw = $request->input('check_in_date');
        $checkOutDateRaw = $request->input('check_out_date');

        $reservationRooms = collect();
        $missingMessage = null;

        $hasAnyInput = ($checkInDateRaw !== null && $checkInDateRaw !== '')
            || ($checkOutDateRaw !== null && $checkOutDateRaw !== '');
 
        if (!$hasAnyInput) {
            $missingMessage = 'Please select check-in and check-out dates, then click Search.';
            return view('admin.reservations.walkin', [
                'reservationRooms' => $reservationRooms,
                'missingMessage' => $missingMessage,
                'checkInDate' => null,
                'checkOutDate' => null,
            ]);
        }

        if (empty($checkInDateRaw) || empty($checkOutDateRaw)) {
            $missingMessage = 'Missing check-in or check-out date.';
            return view('admin.reservations.walkin', [
                'reservationRooms' => $reservationRooms,
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
                'reservationRooms' => $reservationRooms,
                'missingMessage' => $missingMessage,
                'checkInDate' => $checkInDateRaw,
                'checkOutDate' => $checkOutDateRaw,
            ]);
        }

        if ($checkOutDate < $checkInDate) {
            $missingMessage = 'Check-out date must be the same as or after check-in date.';
            return view('admin.reservations.walkin', [
                'reservationRooms' => $reservationRooms,
                'missingMessage' => $missingMessage,
                'checkInDate' => $checkInDate,
                'checkOutDate' => $checkOutDate,
            ]);
        }

        $reservationRooms = ReservationRoom::query()
            ->with([
                'reservation.guest',
                'room',
                'roomType',
            ])
            ->whereHas('reservation', function ($query) use ($checkInDate, $checkOutDate) {
                $query->whereBetween('check_in_date', [$checkInDate, $checkOutDate])
                    ->orWhereBetween('check_out_date', [$checkInDate, $checkOutDate])
                    ->orWhere(function ($query) use ($checkInDate, $checkOutDate) {
                        $query->where('check_in_date', '<=', $checkInDate)
                            ->where('check_out_date', '>=', $checkOutDate);
                    });
            })
            ->latest('id')
            ->get();

        if ($reservationRooms->isEmpty()) {
            $missingMessage = 'No data found for the selected period.';
        }

        return view('admin.reservations.walkin', [
            'reservationRooms' => $reservationRooms,
            'missingMessage' => $missingMessage,
            'checkInDate' => $checkInDate,
            'checkOutDate' => $checkOutDate,
        ]);
    }
}
