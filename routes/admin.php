<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\loginController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ReservationController;
use App\Http\Controllers\admin\FrontDeskController;
use App\Http\Controllers\admin\RoomBlockController;
use App\Http\Controllers\admin\GuestController;
use App\Http\Controllers\admin\CompanyController;
use App\Http\Controllers\admin\TravelAgentController;
use App\Http\Controllers\admin\LoyaltyController;
use App\Http\Controllers\admin\BlacklistController;
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
            Route::get('{id}/edit', [App\Http\Controllers\admin\RoomController::class, 'edit'])->name('edit');
            Route::put('{id}', [App\Http\Controllers\admin\RoomController::class, 'update'])->name('update');
            Route::get('/deleteSingleRoomImage/{id}/{index}', [App\Http\Controllers\admin\RoomController::class, 'deleteSingleRoomImage'])->name('deleteSingleRoomImage');
            Route::put('updateRoomImage/{id}', [App\Http\Controllers\admin\RoomController::class, 'updateRoomImage'])->name('updateRoomImage');
            Route::delete('{id}', [App\Http\Controllers\admin\RoomController::class, 'destroy'])->name('destroy');
            Route::post('bulk-delete', [App\Http\Controllers\admin\RoomController::class, 'bulkDestroy'])->name('bulkDestroy');
            // Room Types, Amenities, Extra Services
            Route::get('types', [App\Http\Controllers\admin\RoomTypeController::class, 'index'])->name('types.index');
            Route::get('types/create', [App\Http\Controllers\admin\RoomTypeController::class, 'create'])->name('types.create');
            Route::post('types', [App\Http\Controllers\admin\RoomTypeController::class, 'store'])->name('types.store');
            Route::get('types/{id}/edit', [App\Http\Controllers\admin\RoomTypeController::class, 'edit'])->name('types.edit');
            Route::put('types/{id}', [App\Http\Controllers\admin\RoomTypeController::class, 'update'])->name('types.update');
            Route::delete('types/{id}', [App\Http\Controllers\admin\RoomTypeController::class, 'destroy'])->name('types.destroy');
            Route::post('types/bulk-delete', [App\Http\Controllers\admin\RoomTypeController::class, 'bulkDestroy'])->name('types.bulkDestroy');

            Route::get('amenities', [App\Http\Controllers\admin\AmenityController::class, 'index'])->name('amenities.index');
            Route::get('amenities/create', [App\Http\Controllers\admin\AmenityController::class, 'create'])->name('amenities.create');
            Route::post('amenities', [App\Http\Controllers\admin\AmenityController::class, 'store'])->name('amenities.store');
            Route::get('amenities/{id}/edit', [App\Http\Controllers\admin\AmenityController::class, 'edit'])->name('amenities.edit');
            Route::put('amenities/{id}', [App\Http\Controllers\admin\AmenityController::class, 'update'])->name('amenities.update');
            Route::delete('amenities/{id}', [App\Http\Controllers\admin\AmenityController::class, 'destroy'])->name('amenities.destroy');
            Route::post('amenities/bulk-delete', [App\Http\Controllers\admin\AmenityController::class, 'bulkDestroy'])->name('amenities.bulkDestroy');

            Route::get('services', [App\Http\Controllers\admin\ExtraServiceController::class, 'index'])->name('services.index');
            Route::get('services/create', [App\Http\Controllers\admin\ExtraServiceController::class, 'create'])->name('services.create');
            Route::post('services', [App\Http\Controllers\admin\ExtraServiceController::class, 'store'])->name('services.store');
            Route::get('services/{id}/edit', [App\Http\Controllers\admin\ExtraServiceController::class, 'edit'])->name('services.edit');
            Route::put('services/{id}', [App\Http\Controllers\admin\ExtraServiceController::class, 'update'])->name('services.update');
            Route::delete('services/{id}', [App\Http\Controllers\admin\ExtraServiceController::class, 'destroy'])->name('services.destroy');
            Route::post('services/bulk-delete', [App\Http\Controllers\admin\ExtraServiceController::class, 'bulkDestroy'])->name('services.bulkDestroy');
            
            // Floors (floor management)
            Route::get('floors', [App\Http\Controllers\admin\FloorController::class, 'index'])->name('floors.index');
            Route::get('floors/create', [App\Http\Controllers\admin\FloorController::class, 'create'])->name('floors.create');
            Route::post('floors', [App\Http\Controllers\admin\FloorController::class, 'store'])->name('floors.store');
            Route::post('floors/bulk-delete', [App\Http\Controllers\admin\FloorController::class, 'bulkDestroy'])->name('floors.bulkDestroy');
            Route::get('floors/{id}/edit', [App\Http\Controllers\admin\FloorController::class, 'edit'])->name('floors.edit');
            Route::put('floors/{id}', [App\Http\Controllers\admin\FloorController::class, 'update'])->name('floors.update');
            Route::delete('floors/{id}', [App\Http\Controllers\admin\FloorController::class, 'destroy'])->name('floors.destroy');
        });

        // Reservations & Front Desk
        Route::prefix('reservations')->name('reservations.')->group(function () {
            Route::get('calendar', [ReservationController::class, 'calendar'])->name('calendar');
            Route::get('calendar-by-room', [ReservationController::class, 'calendarByRoom'])->name('calendar-by-room');
            Route::get('{id}/calendar-modal', [ReservationController::class, 'calendarModal'])->name('calendar-modal');
			Route::get('create', [ReservationController::class, 'create'])->name('create');
			Route::post('create', [ReservationController::class, 'store'])->name('store');
            Route::get('create-reservation', [ReservationController::class, 'reservation'])->name('reservation');
            Route::post('create-reservation', [ReservationController::class, 'storeReservation'])->name('reservation.store');
            Route::get('/', [ReservationController::class, 'index'])->name('index');
            Route::get('{id}/checkin', [ReservationController::class, 'checkin'])->name('checkin');
            Route::get('{id}/checkout', [ReservationController::class, 'checkout'])->name('checkout');
            Route::post('{id}/cancel', [ReservationController::class, 'cancel'])->name('cancel');
            Route::get('{id}/edit', [ReservationController::class, 'edit'])->name('edit');
            Route::put('{id}', [ReservationController::class, 'update'])->name('update');
            Route::delete('{id}', [ReservationController::class, 'destroy'])->name('destroy');
            Route::get('{id}', [ReservationController::class, 'show'])->name('show');
        });

        Route::prefix('front-desk')->name('front-desk.')->group(function () {
            Route::get('arrivals', [FrontDeskController::class, 'arrivals'])->name('arrivals');
            Route::get('arrivals/{reservation}/check-in', [FrontDeskController::class, 'showCheckIn'])->name('arrivals.check-in');
            Route::post('arrivals/{reservation}/check-in', [FrontDeskController::class, 'storeCheckIn'])->name('arrivals.check-in.store');
            Route::get('departures', [FrontDeskController::class, 'departures'])->name('departures');
            Route::get('departures/{stay}/check-out', [FrontDeskController::class, 'showCheckOut'])->name('departures.check-out');
            Route::post('departures/{stay}/check-out', [FrontDeskController::class, 'storeCheckOut'])->name('departures.check-out.store');
            Route::get('in-house', [FrontDeskController::class, 'inHouse'])->name('in-house');
            Route::get('in-house/{stay}', [FrontDeskController::class, 'showInHouse'])->name('in-house.show');
            Route::post('in-house/{stay}/check-out', [FrontDeskController::class, 'checkOutInHouse'])->name('in-house.check-out');
            Route::post('in-house/{stay}/extend', [FrontDeskController::class, 'extendInHouse'])->name('in-house.extend');
            Route::post('in-house/{stay}/change-room', [FrontDeskController::class, 'changeRoomInHouse'])->name('in-house.change-room');
            Route::post('in-house/{stay}/note', [FrontDeskController::class, 'addNoteInHouse'])->name('in-house.note');
            Route::get('room-rack', [FrontDeskController::class, 'roomRack'])->name('room-rack');
            Route::get('walk-in', [FrontDeskController::class, 'walkIn'])->name('walk-in');
            Route::get('guest-requests', [FrontDeskController::class, 'guestRequests'])->name('guest-requests');
        });

        // Room Blocks (Group bookings)
        Route::prefix('room-blocks')->name('room-blocks.')->group(function () {
            Route::get('/', [RoomBlockController::class, 'index'])->name('index');
            Route::get('create', [RoomBlockController::class, 'create'])->name('create');
            Route::get('availability', [RoomBlockController::class, 'availability'])->name('availability');
            Route::post('/', [RoomBlockController::class, 'createBlock'])->name('store');
            Route::get('{id}', [RoomBlockController::class, 'show'])->name('show');
            Route::put('{id}', [RoomBlockController::class, 'update'])->name('update');

            Route::post('{id}/assign-rooms', [RoomBlockController::class, 'assignRooms'])->name('assign-rooms');
            Route::post('{id}/unassign-rooms', [RoomBlockController::class, 'unassignRooms'])->name('unassign-rooms');

            Route::post('{id}/release', [RoomBlockController::class, 'releaseBlock'])->name('release');

            Route::post('{id}/checkin-all-confirmed', [RoomBlockController::class, 'checkinAllConfirmed'])->name('checkin-all-confirmed');
            Route::post('{id}/checkin-selected', [RoomBlockController::class, 'checkinSelected'])->name('checkin-selected');

            Route::get('{id}/convert', [RoomBlockController::class, 'convert'])->name('convert');
            Route::post('{id}/convert', [RoomBlockController::class, 'convertToReservation'])->name('convert.store');
        });

        // Guests & CRM
        Route::prefix('guests')->name('guests.')->group(function () {
            Route::get('/', [GuestController::class, 'index'])->name('index');
            Route::get('create', [GuestController::class, 'create'])->name('create');
            Route::post('/', [GuestController::class, 'store'])->name('store');
            Route::get('{id}/edit', [GuestController::class, 'edit'])->name('edit');
            Route::put('{id}', [GuestController::class, 'update'])->name('update');
            Route::delete('{id}', [GuestController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('companies')->name('companies.')->group(function () {
            Route::get('/', [CompanyController::class, 'index'])->name('index');
            Route::get('create', [CompanyController::class, 'create'])->name('create');
            Route::post('/', [CompanyController::class, 'store'])->name('store');
            Route::get('{id}/edit', [CompanyController::class, 'edit'])->name('edit');
            Route::put('{id}', [CompanyController::class, 'update'])->name('update');
            Route::delete('{id}', [CompanyController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('travel-agents')->name('travel-agents.')->group(function () {
            Route::get('/', [TravelAgentController::class, 'index'])->name('index');
            Route::get('create', [TravelAgentController::class, 'create'])->name('create');
            Route::post('/', [TravelAgentController::class, 'store'])->name('store');
            Route::get('{id}/edit', [TravelAgentController::class, 'edit'])->name('edit');
            Route::put('{id}', [TravelAgentController::class, 'update'])->name('update');
            Route::delete('{id}', [TravelAgentController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('loyalties')->name('loyalties.')->group(function () {
            Route::get('/', [LoyaltyController::class, 'index'])->name('index');
            Route::get('create', [LoyaltyController::class, 'create'])->name('create');
            Route::post('/', [LoyaltyController::class, 'store'])->name('store');
            Route::get('{id}/edit', [LoyaltyController::class, 'edit'])->name('edit');
            Route::put('{id}', [LoyaltyController::class, 'update'])->name('update');
            Route::delete('{id}', [LoyaltyController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('blacklists')->name('blacklists.')->group(function () {
            Route::get('/', [BlacklistController::class, 'index'])->name('index');
            Route::get('create', [BlacklistController::class, 'create'])->name('create');
            Route::post('/', [BlacklistController::class, 'store'])->name('store');
            Route::get('{id}/edit', [BlacklistController::class, 'edit'])->name('edit');
            Route::put('{id}', [BlacklistController::class, 'update'])->name('update');
            Route::delete('{id}', [BlacklistController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('api')->name('api.')->group(function () {
            Route::get('guests/search', [GuestController::class, 'apiSearch'])->name('guests.search');
            Route::post('guests', [GuestController::class, 'storeAjax'])->name('guests.store');
        });

    // });
    //calendar view
    
Route::get('/calendar', function () {
    return view('admin.calendar');
});
});
