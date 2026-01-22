<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\loginController;
use App\Http\Controllers\admin\dashboardController;
use Illuminate\Http\Request;


Route::prefix('admin')->name('admin.')->group(function () {
    // Login routes (only for guests)
    // Route::middleware('admin.guest')->group(function () {
        Route::get('login', [loginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [loginController::class, 'login'])->name('login.post');
    // });

    // Protected admin routes
    // Route::middleware('admin.auth')->group(function () {
        Route::get('/', function () {
            return view('admin.index');
        })->name('index');

        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    // });
});
