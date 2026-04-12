<?php

namespace App\Http\Controllers\frontend;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Stay;
use App\Models\Reservation;
use App\Models\Guest;
use App\Models\ReservationRoom;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DashboardController extends Controller
{
    //index
    public function index()
    {
        $rooms = RoomType::whereIn('id',[1,3,4,5])
        ->with('room', function ($query) {
            $query->where('is_active', true)->limit(1);
        })
        ->get();
    // dd( $rooms->toArray());
        return view('frontend.index',['rooms'=>$rooms]); 
    }

    //room details
    public function roomDetails(Request $request)
    {
        $data = request()->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
        ]);


        $fromDate = $data['check_in_date'];
        $toDate = $data['check_out_date'];

        // Flat list of rooms, each with its roomType data
        $rooms = Room::query()->with('roomType')
        ->whereHas('roomType', function ($q) use ($data) {
                $q->where('capacity_adults', '>=', $data['adults'])
                    ->where('capacity_children', '>=', $data['children'] ?? 0);
            })
            ->where(fn ($q) => $this->onlyAvailableRooms($q, $fromDate, $toDate))
            ->get();
        return view('frontend.roomDetails', compact('data', 'rooms'));
    }

    private function onlyAvailableRooms($roomQuery, string $fromDate, string $toDate): void
    {
        $roomQuery
            ->where('is_active', true)
            ->where('status', 'available')
            ->whereDoesntHave('reservations', fn ($q) => $this->reservationOverlaps($q, $fromDate, $toDate))
            ->whereDoesntHave('roomBlocks', function ($q) use ($fromDate, $toDate) {
                $q->active()
                    ->where('room_blocks.start_date', '<', $toDate)
                    ->where('room_blocks.end_date', '>', $fromDate)
                    ->where('room_block_rooms.status', 'blocked');
            });
    }

    private function reservationOverlaps($reservationQuery, string $fromDate, string $toDate): void
    {
        $reservationQuery
            ->whereIn('reservations.status', ['pending', 'confirmed', 'checked_in', 'booked'])
            ->where('reservations.check_in_date', '<', $toDate)
            ->where('reservations.check_out_date', '>', $fromDate);
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'note' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:1000',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'room_id' => 'required|exists:rooms,id',
            'id_type' => 'nullable|string|max:50',
            'id_number' => 'nullable|string|max:50',
        ]);

        $data['children'] = (int) ($data['children'] ?? 0);

        DB::beginTransaction();

        try {

            // Re-check room availability to prevent race conditions
            $room = Room::query()->with('roomType')->whereKey($data['room_id'])->where('is_active', true)
                ->where('status', 'available')
                ->whereDoesntHave('reservations', fn ($q) => $this->reservationOverlaps(
                    $q,
                    $data['check_in_date'],
                    $data['check_out_date']
                ))
                ->whereDoesntHave('roomBlocks', function ($q) use ($data) {
                    $q->active()
                        ->where('room_blocks.start_date', '<', $data['check_out_date'])
                        ->where('room_blocks.end_date', '>', $data['check_in_date'])
                        ->where('room_block_rooms.status', 'blocked');
                })
                ->lockForUpdate()
                ->first();

            if (!$room) {
                throw ValidationException::withMessages([
                    'room_id' => 'Selected room is no longer available for these dates.',
                ]);
            }

            $guest = Guest::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'] ?? null,
                'id_type' => $data['id_type'] ?? null,
                'id_number' => $data['id_number'] ?? null,
            ]);

            $reservation = Reservation::create([
                'guest_id' => $guest->id,
                'check_in_date' => $data['check_in_date'],
                'check_out_date' => $data['check_out_date'],
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'note' => $data['note'] ?? null,
                'adults' => $data['adults'],
                'children' => $data['children'],
                'channel' => 'website',
            ]);

            $roomPrice = $room->roomType->base_price ?? 0.00;
            $discountAmount = $room->roomType->discount_amount ?? 0.00; 

            ReservationRoom::create([
                'room_id' => $data['room_id'],
                'reservation_id' => $reservation->id,
                'room_type_id' => $room->room_type_id,
                'rate_plan_named' => 'Standard Rate',
                'nightly_rate' => $roomPrice,
                'discount_amount' => $discountAmount,
                'tax_amount' => 10.00,
                'total_amount' => $roomPrice + 10.00 - $discountAmount,
                'status' => 'reserved',
            ]);

            // Update statuses after successful reservation creation
            $room->update(['status' => 'reserved']);
            $reservation->update(['status' => 'pending']);

            DB::commit();

            return redirect()
                ->route('frontend.room_details', [   
                    'check_in_date' => $data['check_in_date'],
                    'check_out_date' => $data['check_out_date'],
                    'adults' => $data['adults'],
                    'children' => $data['children'],
                    'room_id' => $data['room_id'],
                ])
                ->with('booking_success', 'Your booking has been successfully made!')
                ->with('reservation_id', $reservation->id);

        } catch (ValidationException $e) {
            DB::rollBack();

            $message = collect($e->errors())->flatten()->first() ?? 'Selected room is no longer available for these dates.';

            return redirect()
                ->route('frontend.room_details', [
                    'check_in_date' => $data['check_in_date'],
                    'check_out_date' => $data['check_out_date'],
                    'adults' => $data['adults'],
                    'children' => $data['children'],
                    'room_id' => $data['room_id'],
                ])
                ->with('booking_error', $message)
                ->withErrors($e->errors());
        } catch (\Exception $e) {
            // return $e->getMessage();
            DB::rollBack();

            return redirect()
                ->route('frontend.room_details', [
                    'check_in_date' => $data['check_in_date'],
                    'check_out_date' => $data['check_out_date'],
                    'adults' => $data['adults'],
                    'children' => $data['children'],
                    'room_id' => $data['room_id'],
                ])
                ->with('booking_error', 'An error occurred while processing your booking. Please try again.');
        }
    }

    
}

