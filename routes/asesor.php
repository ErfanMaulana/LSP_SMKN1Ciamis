<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesi\AuthController;
use App\Http\Controllers\Asesor\BandingAsesmenController;
use App\Http\Controllers\Asesor\DashboardController;
use App\Http\Controllers\Asesor\CeklisObservasiController;

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
        Route::get('/ceklis-observasi', [CeklisObservasiController::class, 'index'])->name('ceklis-observasi.index');
        Route::get('/ceklis-observasi/skema-participants', [CeklisObservasiController::class, 'participantsBySkema'])->name('ceklis-observasi.skema-participants');
        Route::get('/ceklis-observasi/skema-structure', [CeklisObservasiController::class, 'skemaStructure'])->name('ceklis-observasi.skema-structure');
        Route::get('/ceklis-observasi/create', [CeklisObservasiController::class, 'create'])->name('ceklis-observasi.create');
        Route::post('/ceklis-observasi', [CeklisObservasiController::class, 'store'])->name('ceklis-observasi.store');
        Route::get('/ceklis-observasi/{id}/edit', [CeklisObservasiController::class, 'edit'])->name('ceklis-observasi.edit');
        Route::put('/ceklis-observasi/{id}', [CeklisObservasiController::class, 'update'])->name('ceklis-observasi.update');
        Route::delete('/ceklis-observasi/{id}', [CeklisObservasiController::class, 'destroy'])->name('ceklis-observasi.destroy');
        Route::get('/asesi',     [DashboardController::class, 'asesiIndex'])->name('asesi.index');
        Route::get('/asesi/{asesiNik}/review',  [DashboardController::class, 'asesiReview'])->name('asesi.review');
        Route::post('/asesi/{asesiNik}/review', [DashboardController::class, 'recommend'])->name('asesi.recommend');
        Route::get('/banding-asesmen', [BandingAsesmenController::class, 'index'])->name('banding.index');
        Route::get('/banding-asesmen/{asesiNik}/{skemaId}', [BandingAsesmenController::class, 'form'])->name('banding.form');
        Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');
    });
});
