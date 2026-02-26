<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesi\AuthController;
use App\Http\Controllers\Asesor\DashboardController;

/*
|--------------------------------------------------------------------------
| Asesor Routes
|--------------------------------------------------------------------------
*/

Route::prefix('asesor')->name('asesor.')->group(function () {
    // Protected asesor routes (uses account guard, role = asesor)
    Route::middleware('auth:account')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/asesi',     [DashboardController::class, 'asesiIndex'])->name('asesi.index');
        Route::get('/asesi/{asesiNik}/review',  [DashboardController::class, 'asesiReview'])->name('asesi.review');
        Route::post('/asesi/{asesiNik}/review', [DashboardController::class, 'recommend'])->name('asesi.recommend');
        Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');
    });
});
