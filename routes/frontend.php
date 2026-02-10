<?php
use App\Http\Controllers\frontend\DashboardController;
use Illuminate\Support\Facades\Route;

    

Route::get('/', [DashboardController::class, 'index'])->name('frontend.index');

Route::get('/about', function () {
    return view('frontend.about');
});

Route::get('/accomodation', function () {
    return view('frontend.accomodation');
});
