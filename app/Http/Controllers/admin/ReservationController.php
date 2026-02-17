<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Invoice;
use App\Models\Payment;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::query();

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($w) use ($q) {
                $w->where('guest_name', 'like', "%{$q}%")
                    ->orWhere('guest_email', 'like', "%{$q}%")
                    ->orWhereHas('rooms', function ($roomQuery) use ($q) {
                        $roomQuery->where('room_number', 'like', "%{$q}%");
                    });
            });
        }

            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }

            if ($request->filled('from') && $request->filled('to')) {
                $from = Carbon::parse($request->input('from'))->startOfDay();
                $to = Carbon::parse($request->input('to'))->endOfDay();
                $query->whereBetween('check_in_date', [$from, $to]);
            }

            $reservations = $query->with('rooms')->orderBy('check_in_date', 'desc')->paginate(25);

            return view('admin.reservations.index', compact('reservations'));
    }

    public function show($id)
    {
        $reservation = Schema::hasTable('reservations') ? DB::table('reservations')->where('id', $id)->first() : null;
            $reservation = Reservation::with(['rooms', 'payments', 'invoice'])->findOrFail($id);
            return view('admin.reservations.show', compact('reservation'));
    }

    public function calendar()
    {
        $events = Reservation::select(['id', 'guest_name', 'check_in_date', 'check_out_date', 'status'])->get();
        return view('admin.reservations.calendar', compact('events'));
    }

    public function walkin(Request $request)
    {
        $availableRooms = Room::where('status', 'available')->orderBy('room_number')->get();
        return view('admin.reservations.walkin', compact('availableRooms'));
    }

    public function checkin($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->status = 'checked_in';
        $reservation->save();

        $reservation->loadMissing('rooms');
        foreach ($reservation->rooms as $room) {
            $room->status = 'occupied';
            $room->save();
        }

        return redirect()->route('admin.reservations.show', $reservation->id)->with('success', 'Guest checked in');
    }

    public function checkout($id)
    {
        $reservation = Reservation::with('payments')->findOrFail($id);
        // simple settlement: mark reservation and room
        $reservation->status = 'checked_out';
        $reservation->save();

        $reservation->loadMissing('rooms');
        foreach ($reservation->rooms as $room) {
            $room->status = 'available';
            $room->save();
        }

        // create invoice if none exists
        if (!$reservation->invoice) {
            $invoice = $reservation->invoice()->create([
                'amount' => $reservation->rate,
                'status' => 'paid',
                'due_date' => now()->toDateString(),
            ]);
        }

        return redirect()->route('admin.reservations.show', $reservation->id)->with('success', 'Guest checked out');
    }

    public function storeWalkin(Request $request)
    {
        $data = $request->validate([
            'guest_name' => 'required|string|max:191',
            'guest_email' => 'nullable|email|max:191',
            'guest_phone' => 'nullable|string|max:50',
            'room_number' => 'nullable|string|max:50',
            'room_ids' => 'nullable|array',
            'room_ids.*' => 'integer|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after_or_equal:check_in_date',
        ]);

        $reservation = new Reservation();
        $reservation->guest_name = $data['guest_name'];
        $reservation->guest_email = $data['guest_email'] ?? null;
        $reservation->guest_phone = $data['guest_phone'] ?? null;
        $reservation->check_in_date = $data['check_in_date'];
        $reservation->check_out_date = $data['check_out_date'];
        $reservation->status = 'booked';

        // single-room reservation: accept room_ids[] (pick first) OR room_number
        if (!empty($data['room_ids']) && is_array($data['room_ids'])) {
            $reservation->save();
            $reservation->rooms()->sync($data['room_ids']);
            $rooms = Room::whereIn('id', $data['room_ids'])->get();
            foreach ($rooms as $r) {
                $r->status = 'occupied';
                $r->save();
            }
        } elseif (!empty($data['room_number'])) {
            $room = Room::where('room_number', $data['room_number'])->first();
            if ($room) {
                // mark occupied for walk-in
                $room->status = 'occupied';
                $room->save();
            } else {
                $reservation->note = trim(($reservation->note ?? '') . "\nRoom number: " . $data['room_number']);
            }
            $reservation->save();
            if ($room) {
                $reservation->rooms()->sync([$room->id]);
            }
        } else {
            $reservation->save();
        }
        return redirect()->route('admin.reservations.show', $reservation->id)->with('success', 'Walk-in reservation created');
    }
}
