<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesi\AuthController;
use App\Http\Controllers\Asesi\AsesmenMandiriController;
use App\Http\Controllers\Asesi\BandingAsesmenController;
use App\Http\Controllers\Asesi\ProfileController;
use App\Http\Controllers\Asesi\JadwalController;
use App\Http\Controllers\Asesi\RegisterController;
use App\Http\Controllers\Asesi\UmpanBalikController;

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
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // Pendaftaran (Registration within panel) - accessible before approval
        Route::get('/pendaftaran', [RegisterController::class, 'showForm'])->name('pendaftaran.formulir');
        Route::post('/pendaftaran', [RegisterController::class, 'storeForm'])->name('pendaftaran.formulir.store');
        Route::get('/pendaftaran/dokumen', [RegisterController::class, 'showDokumen'])->name('pendaftaran.dokumen');
        Route::post('/pendaftaran/dokumen', [RegisterController::class, 'storeDokumen'])->name('pendaftaran.dokumen.store');

        // Routes that require admin approval
        Route::middleware('asesi.approved')->group(function () {
            Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

            // Asesmen Mandiri (FR.APL.02)
            Route::get('/asesmen-mandiri', [AsesmenMandiriController::class, 'index'])->name('asesmen-mandiri.index');
            Route::get('/asesmen-mandiri/{skemaId}', [AsesmenMandiriController::class, 'show'])->name('asesmen-mandiri.show');
            Route::post('/asesmen-mandiri/{skemaId}', [AsesmenMandiriController::class, 'store'])->name('asesmen-mandiri.store');
            Route::get('/asesmen-mandiri/{skemaId}/result', [AsesmenMandiriController::class, 'result'])->name('asesmen-mandiri.result');
            Route::get('/hasil-ujikom', [AsesmenMandiriController::class, 'hasilUjikom'])->name('hasil-ujikom.index');

            // Umpan Balik Kinerja Asesor
            Route::get('/umpan-balik', [UmpanBalikController::class, 'index'])->name('umpan-balik.index');
            Route::get('/umpan-balik/{skemaId}', [UmpanBalikController::class, 'show'])->name('umpan-balik.show');
            Route::post('/umpan-balik/{skemaId}', [UmpanBalikController::class, 'store'])->name('umpan-balik.store');

            // Profil
            Route::get('/profil', [ProfileController::class, 'edit'])->name('profil.edit');
            Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');
            Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profil.password');

            // Jadwal Ujikom
            Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
        });
    });
});
