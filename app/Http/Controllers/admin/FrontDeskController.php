<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class FrontDeskController extends Controller
{
    public function arrivals()
    {
        return view('admin.frontDesk.arrivals');
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
