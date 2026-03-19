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
        Route::get('/profil', [DashboardController::class, 'profil'])->name('profil.index');
        Route::put('/profil', [DashboardController::class, 'updateProfil'])->name('profil.update');
        Route::get('/ubah-password', [DashboardController::class, 'passwordForm'])->name('password.edit');
        Route::put('/ubah-password', [DashboardController::class, 'updatePassword'])->name('password.update');
        Route::get('/jadwal', [DashboardController::class, 'jadwalIndex'])->name('jadwal.index');
        Route::get('/kelompok', [DashboardController::class, 'kelompokIndex'])->name('kelompok.index');
        Route::get('/kelompok/{id}', [DashboardController::class, 'kelompokShow'])->name('kelompok.show');
        Route::get('/entry-penilaian', [DashboardController::class, 'entryPenilaianIndex'])->name('entry-penilaian');
        Route::get('/entry-penilaian/create', [DashboardController::class, 'entryPenilaianCreate'])->name('entry-penilaian.create');
        Route::get('/entry-penilaian/{asesiNik}/input', [DashboardController::class, 'entryPenilaianForm'])->name('entry-penilaian.form');
        Route::post('/entry-penilaian/{asesiNik}/input', [DashboardController::class, 'entryPenilaianStore'])->name('entry-penilaian.store');
        Route::get('/asesi',     [DashboardController::class, 'asesiIndex'])->name('asesi.index');
        Route::get('/asesi/{asesiNik}/review',  [DashboardController::class, 'asesiReview'])->name('asesi.review');
        Route::post('/asesi/{asesiNik}/review', [DashboardController::class, 'recommend'])->name('asesi.recommend');
        Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');
    });
});
