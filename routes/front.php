<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\RegisterController;
use App\Http\Controllers\Front\KompetensiController;
use App\Http\Controllers\Front\ProfilController;

/*
|--------------------------------------------------------------------------
| Front Routes
|--------------------------------------------------------------------------
|
| Here is where you can register front-end routes for your application.
|
*/

Route::name('front.')->group(function () {
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::redirect('/daftar-lsp', '/#daftar-lsp')->name('daftar');
    Route::redirect('/kontak', '/#kontak')->name('kontak');

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
