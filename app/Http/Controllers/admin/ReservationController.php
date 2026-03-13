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

    public function calendar()
    {
        $reservations = Reservation::with('guest','rooms')->get();
        return view('admin.reservations.calendar', compact('reservations'));
    }

    public function walkin(Request $request)
    {
        $checkInDateRaw = $request->input('check_in_date');
        $checkOutDateRaw = $request->input('check_out_date');

        $availableRooms = collect();
        $missingMessage = null;

        $hasAnyInput = ($checkInDateRaw !== null && $checkInDateRaw !== '')
            || ($checkOutDateRaw !== null && $checkOutDateRaw !== '');
 
        if (!$hasAnyInput) {
            $missingMessage = 'Please select check-in and check-out dates, then click Search.';
            return view('admin.reservations.walkin', [
                'availableRooms' => $availableRooms,
                'missingMessage' => $missingMessage,
                'checkInDate' => null,
                'checkOutDate' => null,
            ]);
        }

        if (empty($checkInDateRaw) || empty($checkOutDateRaw)) {
            $missingMessage = 'Missing check-in or check-out date.';
            return view('admin.reservations.walkin', [
                'availableRooms' => $availableRooms,
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
                'missingMessage' => $missingMessage,
                'checkInDate' => $checkInDateRaw,
                'checkOutDate' => $checkOutDateRaw,
            ]);
        }

        if ($checkOutDate < $checkInDate) {
            $missingMessage = 'Check-out date must be the same as or after check-in date.';
            return view('admin.reservations.walkin', [
                'availableRooms' => $availableRooms,
                'missingMessage' => $missingMessage,
                'checkInDate' => $checkInDate,
                'checkOutDate' => $checkOutDate,
            ]);
        }

        $bookedRoomIds = ReservationRoom::query()
            ->whereHas('reservation', function ($query) use ($checkInDate, $checkOutDate) {
                $query->whereBetween('check_in_date', [$checkInDate, $checkOutDate])
                    ->orWhereBetween('check_out_date', [$checkInDate, $checkOutDate])
                    ->orWhere(function ($query) use ($checkInDate, $checkOutDate) {
                        $query->where('check_in_date', '<=', $checkInDate)
                            ->where('check_out_date', '>=', $checkOutDate);
                    });
            })
            ->distinct()
            ->pluck('room_id');

        $availableRooms = Room::query()
            ->with(['roomType', 'floor'])
            ->where('is_active', true)
            ->where('status', 'available')
            ->when($bookedRoomIds->isNotEmpty(), function ($query) use ($bookedRoomIds) {
                $query->whereNotIn('id', $bookedRoomIds);
            })
            ->orderBy('room_number')
            ->get();

        if ($availableRooms->isEmpty()) {
            $missingMessage = 'No rooms available for the selected period.';
        }

        return view('admin.reservations.walkin', [
            'availableRooms' => $availableRooms,
            'missingMessage' => $missingMessage,
            'checkInDate' => $checkInDate,
            'checkOutDate' => $checkOutDate,
        ]);
    }
}
