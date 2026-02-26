<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesi\AuthController;
use App\Http\Controllers\Asesi\AsesmenMandiriController;

/*
|--------------------------------------------------------------------------
| Asesi Routes
|--------------------------------------------------------------------------
|
| Here is where you can register asesi routes for your application.
|
*/

Route::prefix('asesi')->name('asesi.')->group(function () {
    // Redirect old /asesi/login to unified /login
    Route::get('/login', fn () => redirect()->route('login'))->name('login');

    // Protected asesi routes
    Route::middleware('auth:account')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Asesmen Mandiri (FR.APL.02)
        Route::get('/asesmen-mandiri', [AsesmenMandiriController::class, 'index'])->name('asesmen-mandiri.index');
        Route::get('/asesmen-mandiri/{skemaId}', [AsesmenMandiriController::class, 'show'])->name('asesmen-mandiri.show');
        Route::post('/asesmen-mandiri/{skemaId}', [AsesmenMandiriController::class, 'store'])->name('asesmen-mandiri.store');
        Route::get('/asesmen-mandiri/{skemaId}/result', [AsesmenMandiriController::class, 'result'])->name('asesmen-mandiri.result');
    });
});
