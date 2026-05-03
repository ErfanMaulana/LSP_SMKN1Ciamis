<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesi\AuthController;
use App\Http\Controllers\Asesor\BandingAsesmenController;
use App\Http\Controllers\Asesor\DashboardController;
use App\Http\Controllers\Asesor\RekamanAsesmenKompetensiController;

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
        Route::get('/asesi-terkait', [DashboardController::class, 'asesiIndex'])->name('asesi.terkait');
        Route::get('/asesi',     [DashboardController::class, 'asesiIndex'])->name('asesi.index');
        Route::get('/asesi/{asesiNik}/review',  [DashboardController::class, 'asesiReview'])->name('asesi.review');
        Route::post('/asesi/{asesiNik}/review', [DashboardController::class, 'recommend'])->name('asesi.recommend');
        Route::prefix('rekaman-asesmen-kompetensi')->name('rekaman-asesmen-kompetensi.')->group(function () {
            Route::get('/', [RekamanAsesmenKompetensiController::class, 'index'])->name('index');
            Route::get('/skema-participants', [RekamanAsesmenKompetensiController::class, 'participantsBySkema'])->name('skema-participants');
            Route::get('/skema-units', [RekamanAsesmenKompetensiController::class, 'skemaUnits'])->name('skema-units');
            Route::get('/create', [RekamanAsesmenKompetensiController::class, 'create'])->name('create');
            Route::post('/', [RekamanAsesmenKompetensiController::class, 'store'])->name('store');
            Route::get('/{id}', [RekamanAsesmenKompetensiController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [RekamanAsesmenKompetensiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [RekamanAsesmenKompetensiController::class, 'update'])->name('update');
            Route::delete('/{id}', [RekamanAsesmenKompetensiController::class, 'destroy'])->name('destroy');
        });
        Route::get('/banding-asesmen', [BandingAsesmenController::class, 'index'])->name('banding.index');
        Route::get('/banding-asesmen/{asesiNik}/{skemaId}', [BandingAsesmenController::class, 'form'])->name('banding.form');
        Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');
    });
});
