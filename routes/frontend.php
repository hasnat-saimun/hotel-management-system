<?php
use App\Http\Controllers\frontend\DashboardController;
use App\Http\Controllers\frontend\GalleryController;
use App\Http\Controllers\frontend\ElemestsController;
use Illuminate\Support\Facades\Route;

    

Route::get('/', [DashboardController::class, 'index'])->name('frontend.index');

Route::get('/about', function () {
    return view('frontend.about');
});

Route::get('/gallery', [GalleryController::class, 'index'])->name('frontend.gallery');

Route::get('/elements', [ElemestsController::class, 'index'])->name('frontend.elemests');

Route::get('/accomodation', function () {
    return view('frontend.accomodation');
});
