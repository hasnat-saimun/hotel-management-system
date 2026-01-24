<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard with KPIs.
     */
    public function index()
    {
        $today = Carbon::today();

        $totalRooms = Schema::hasTable('rooms') ? DB::table('rooms')->count() : 0;
        $availableRooms = Schema::hasTable('rooms') ? DB::table('rooms')->where('status', 'available')->count() : 0;
        $occupiedRooms = Schema::hasTable('rooms') ? DB::table('rooms')->where('status', 'occupied')->count() : 0;
        $outOfService = Schema::hasTable('rooms') ? DB::table('rooms')->where('status', 'out_of_service')->count() : 0;

        $todayCheckins = Schema::hasTable('reservations') ? DB::table('reservations')->whereDate('check_in_date', $today)->count() : 0;
        $todayCheckouts = Schema::hasTable('reservations') ? DB::table('reservations')->whereDate('check_out_date', $today)->count() : 0;
        $inHouseGuests = Schema::hasTable('reservations') ? DB::table('reservations')->whereDate('check_in_date','<=',$today)->whereDate('check_out_date','>',$today)->count() : 0;

        $upcomingReservations = Schema::hasTable('reservations') ? DB::table('reservations')->whereBetween('check_in_date', [$today, $today->copy()->addDays(7)])->count() : 0;

        $occupancyRate = $totalRooms ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        $todayRevenueRooms = Schema::hasTable('payments') ? DB::table('payments')->whereDate('created_at', $today)->where('type','room')->sum('amount') : 0;
        $otherRevenue = Schema::hasTable('payments') ? DB::table('payments')->whereDate('created_at', $today)->where('type','<>','room')->sum('amount') : 0;
        $dueAmount = Schema::hasTable('invoices') ? DB::table('invoices')->where('status','due')->sum('amount') : 0;

        return view('admin.index', compact(
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'outOfService',
            'todayCheckins',
            'todayCheckouts',
            'inHouseGuests',
            'upcomingReservations',
            'occupancyRate',
            'todayRevenueRooms',
            'otherRevenue',
            'dueAmount'
        ));
    }
}
