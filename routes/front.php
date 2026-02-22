<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Front Routes
|--------------------------------------------------------------------------
|
| Here is where you can register front-end routes for your application.
|
*/

Route::name('front.')->group(function () {
    Route::view('/profil', 'asesi.profil')->name('profil');
    Route::redirect('/kompetensi-skema', '/#kompetensi')->name('kompetensi');
    Route::redirect('/daftar-lsp', '/#daftar-lsp')->name('daftar');
    Route::redirect('/kontak', '/#kontak')->name('kontak');
});
