<?php
use App\Http\Controllers\frontend\DashboardController;
use App\Http\Controllers\frontend\GalleryController;
use App\Http\Controllers\frontend\ElemestsController;
use App\Http\Controllers\frontend\ContractController;
use App\Http\Controllers\frontend\PackageController;
use Illuminate\Support\Facades\Route;

    

Route::get('/', [DashboardController::class, 'index'])->name('frontend.index');

Route::get('/about', function () {
    return view('frontend.about');
});

Route::get('/gallery', [GalleryController::class, 'index'])->name('frontend.gallery');

Route::get('/elements', [ElemestsController::class, 'index'])->name('frontend.elemests');

Route::get('/contract', [ContractController::class, 'contract'])->name('frontend.contract');

Route::get('/room-details', [DashboardController::class, 'roomDetails'])->name('frontend.room_details');

Route::get('/honeymoon-package', [PackageController::class, 'index'])->name('frontend.package.honeymoon_package');

Route::get('/accomodation', function () {
    return view('frontend.accomodation');

});
Route::get('/cancle-booking', function () {
    return view('frontend.exitBooking');

});