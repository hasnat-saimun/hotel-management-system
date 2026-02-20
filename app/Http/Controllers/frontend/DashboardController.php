<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Stay;
use App\Models\Reservation;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //index
    public function index()
    {

        $rooms = RoomType::whereIn('id',[6,7,8,9])
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
            'from_date' => 'required|date',
            'to_date' => 'required|date|after:from_date',
            'adult' => 'required|integer|min:1',
            'child' => 'required|integer|min:0',
        ]);

 

        $fromDate = $data['from_date'];
        $toDate = $data['to_date'];

        // Flat list of rooms, each with its roomType data
        $rooms = Room::query()->with('roomType')
        ->whereHas('roomType', function ($q) use ($data) {
                $q->where('capacity_adults', '>=', $data['adult'])
                    ->where('capacity_children', '>=', $data['child']);
            })
            ->where(fn ($q) => $this->onlyAvailableRooms($q, $fromDate, $toDate))
            ->get();
        return view('frontend.roomDetails', compact('data', 'rooms'));
    }

    private function onlyAvailableRooms($roomQuery, string $fromDate, string $toDate): void
    {
        $roomQuery
            ->where('is_active', true)
            ->whereDoesntHave('reservations', fn ($q) => $this->reservationOverlaps($q, $fromDate, $toDate));
    }

    private function reservationOverlaps($reservationQuery, string $fromDate, string $toDate): void
    {
        $reservationQuery->where(function ($q) use ($fromDate, $toDate) {
            $q->whereBetween('check_in_date', [$fromDate, $toDate])
                ->orWhereBetween('check_out_date', [$fromDate, $toDate])
                ->orWhere(function ($q2) use ($fromDate, $toDate) {
                    $q2->where('check_in_date', '<=', $fromDate)
                        ->where('check_out_date', '>=', $toDate);
                });
        });
    }

    public function store(Request $request)
    {
        
       
    }
}

