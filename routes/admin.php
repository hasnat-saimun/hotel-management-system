<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\loginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ReservationController;
use Illuminate\Http\Request;


Route::prefix('admin')->name('admin.')->group(function () {
    // Login routes (only for guests)
    // Route::middleware('admin.guest')->group(function () {
        Route::get('login', [loginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [loginController::class, 'login'])->name('login.post');
    // });

    // Protected admin routes
    // Route::middleware('admin.auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Reservations & Front Desk
        Route::prefix('reservations')->name('reservations.')->group(function () {
            Route::get('calendar', [ReservationController::class, 'calendar'])->name('calendar');
            Route::get('create-walkin', [ReservationController::class, 'walkin'])->name('walkin');
            Route::post('create-walkin', [ReservationController::class, 'storeWalkin'])->name('walkin.store');
            Route::get('/', [ReservationController::class, 'index'])->name('index');
            Route::get('{id}/checkin', [ReservationController::class, 'checkin'])->where('id','[0-9]+')->name('checkin');
            Route::get('{id}/checkout', [ReservationController::class, 'checkout'])->where('id','[0-9]+')->name('checkout');
            Route::get('{id}', [ReservationController::class, 'show'])->where('id','[0-9]+')->name('show');
        });
    // });
});
