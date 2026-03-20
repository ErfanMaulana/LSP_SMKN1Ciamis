<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\RegisterController;
use App\Http\Controllers\Front\KompetensiController;
use App\Http\Controllers\Front\BeritaController;
use App\Http\Controllers\Front\KontakController;
use App\Http\Controllers\Front\PanduanController;

/*
|--------------------------------------------------------------------------
| Front Routes
|--------------------------------------------------------------------------
|
| Here is where you can register front-end routes for your application.
|
*/

Route::name('front.')->group(function () {
    Route::redirect('/profil', '/')->name('profil');
    Route::redirect('/daftar-lsp', '/berita')->name('daftar');
    Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');

    // Panduan Sistem Routes
    Route::prefix('panduan')->name('panduan.')->group(function () {
        Route::get('/', [PanduanController::class, 'overview'])->name('overview');
        Route::get('/alur-keseluruhan-sistem', [PanduanController::class, 'overview'])->name('overview.alur');
        Route::get('/peran-asesi', [PanduanController::class, 'asesi'])->name('asesi');
        Route::get('/peran-asesor', [PanduanController::class, 'asesor'])->name('asesor');
        Route::get('/peran-admin', [PanduanController::class, 'admin'])->name('admin');
    });

    // Berita Routes
    Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
    Route::get('/berita/{slug}', [BeritaController::class, 'show'])->name('berita.show');

    // Kompetensi Routes
    Route::get('/kompetensi-dan-data-skema', [KompetensiController::class, 'index'])->name('kompetensi.index');
    Route::get('/kompetensi-dan-data-skema/{slug}', [KompetensiController::class, 'detail'])->name('kompetensi.detail');

    // Registration Routes
    Route::prefix('register')->name('register.')->group(function () {
        // Asesi Registration - Step 1 (Formulir)
        Route::get('/asesi', [RegisterController::class, 'showAsesiRegistrationForm'])->name('asesi');
        Route::post('/asesi', [RegisterController::class, 'registerAsesi'])->name('asesi.store');

        // Asesi Registration - Step 2 (Dokumen/Berkas)
        Route::get('/asesi/dokumen', [RegisterController::class, 'showDokumenForm'])->name('asesi.dokumen');
        Route::post('/asesi/dokumen', [RegisterController::class, 'storeDokumen'])->name('asesi.dokumen.store');

        // Asesi Registration - Success
        Route::get('/asesi/success', [RegisterController::class, 'registrationSuccess'])->name('asesi.success');
    });
});
