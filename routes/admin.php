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

        // Room Management
        Route::prefix('rooms')->name('rooms.')->group(function () {
            Route::get('/', [App\Http\Controllers\admin\RoomController::class, 'index'])->name('index');
            Route::get('create', [App\Http\Controllers\admin\RoomController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\admin\RoomController::class, 'store'])->name('store');
            Route::get('{id}/edit', [App\Http\Controllers\admin\RoomController::class, 'edit'])->where('id','[0-9]+')->name('edit');
            Route::put('{id}', [App\Http\Controllers\admin\RoomController::class, 'update'])->where('id','[0-9]+')->name('update');
            Route::delete('{id}', [App\Http\Controllers\admin\RoomController::class, 'destroy'])->where('id','[0-9]+')->name('destroy');
            Route::post('bulk-delete', [App\Http\Controllers\admin\RoomController::class, 'bulkDestroy'])->name('bulkDestroy');
            // Room Types, Amenities, Extra Services
            Route::get('types', [App\Http\Controllers\admin\RoomTypeController::class, 'index'])->name('types.index');
            Route::get('types/create', [App\Http\Controllers\admin\RoomTypeController::class, 'create'])->name('types.create');
            Route::post('types', [App\Http\Controllers\admin\RoomTypeController::class, 'store'])->name('types.store');
            Route::get('types/{id}/edit', [App\Http\Controllers\admin\RoomTypeController::class, 'edit'])->where('id','[0-9]+')->name('types.edit');
            Route::put('types/{id}', [App\Http\Controllers\admin\RoomTypeController::class, 'update'])->where('id','[0-9]+')->name('types.update');
            Route::delete('types/{id}', [App\Http\Controllers\admin\RoomTypeController::class, 'destroy'])->where('id','[0-9]+')->name('types.destroy');
            Route::post('types/bulk-delete', [App\Http\Controllers\admin\RoomTypeController::class, 'bulkDestroy'])->name('types.bulkDestroy');

            Route::get('amenities', [App\Http\Controllers\admin\AmenityController::class, 'index'])->name('amenities.index');
            Route::get('amenities/create', [App\Http\Controllers\admin\AmenityController::class, 'create'])->name('amenities.create');
            Route::post('amenities', [App\Http\Controllers\admin\AmenityController::class, 'store'])->name('amenities.store');
            Route::get('amenities/{id}/edit', [App\Http\Controllers\admin\AmenityController::class, 'edit'])->where('id','[0-9]+')->name('amenities.edit');
            Route::put('amenities/{id}', [App\Http\Controllers\admin\AmenityController::class, 'update'])->where('id','[0-9]+')->name('amenities.update');
            Route::delete('amenities/{id}', [App\Http\Controllers\admin\AmenityController::class, 'destroy'])->where('id','[0-9]+')->name('amenities.destroy');
            Route::post('amenities/bulk-delete', [App\Http\Controllers\admin\AmenityController::class, 'bulkDestroy'])->name('amenities.bulkDestroy');

            Route::get('services', [App\Http\Controllers\admin\ExtraServiceController::class, 'index'])->name('services.index');
            Route::get('services/create', [App\Http\Controllers\admin\ExtraServiceController::class, 'create'])->name('services.create');
            Route::post('services', [App\Http\Controllers\admin\ExtraServiceController::class, 'store'])->name('services.store');
            Route::get('services/{id}/edit', [App\Http\Controllers\admin\ExtraServiceController::class, 'edit'])->where('id','[0-9]+')->name('services.edit');
            Route::put('services/{id}', [App\Http\Controllers\admin\ExtraServiceController::class, 'update'])->where('id','[0-9]+')->name('services.update');
            Route::delete('services/{id}', [App\Http\Controllers\admin\ExtraServiceController::class, 'destroy'])->where('id','[0-9]+')->name('services.destroy');
            Route::post('services/bulk-delete', [App\Http\Controllers\admin\ExtraServiceController::class, 'bulkDestroy'])->name('services.bulkDestroy');
            
            // Floors (floor management)
            Route::get('floors', [App\Http\Controllers\admin\FloorController::class, 'index'])->name('floors.index');
            Route::get('floors/create', [App\Http\Controllers\admin\FloorController::class, 'create'])->name('floors.create');
            Route::post('floors', [App\Http\Controllers\admin\FloorController::class, 'store'])->name('floors.store');
            Route::post('floors/bulk-delete', [App\Http\Controllers\admin\FloorController::class, 'bulkDestroy'])->name('floors.bulkDestroy');
            Route::get('floors/{id}/edit', [App\Http\Controllers\admin\FloorController::class, 'edit'])->where('id','[0-9]+')->name('floors.edit');
            Route::put('floors/{id}', [App\Http\Controllers\admin\FloorController::class, 'update'])->where('id','[0-9]+')->name('floors.update');
            Route::delete('floors/{id}', [App\Http\Controllers\admin\FloorController::class, 'destroy'])->where('id','[0-9]+')->name('floors.destroy');
        });

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
