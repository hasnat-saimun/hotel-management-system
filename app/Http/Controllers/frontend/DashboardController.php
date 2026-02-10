<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
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
}
