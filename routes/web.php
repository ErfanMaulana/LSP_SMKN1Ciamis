<?php

    // use Illuminate\Support\Facades\Route;

    // Route::get('/', function () {
    // return view('welcome');
    // });
    // use App\Http\Controllers\Frontend\HomeController;
    // Route::get('/', [HomeController::class, 'index']);
use App\Http\Controllers\Frontend\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\DashboardController;

Route::prefix('admin')->name('admin.')->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


