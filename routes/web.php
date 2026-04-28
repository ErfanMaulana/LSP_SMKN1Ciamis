<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Asesi\BandingAsesmenController;

Route::get('/', [HomeController::class, 'index'])->name('front.home');

// Unified login route
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('asesi')->name('asesi.')->middleware(['auth:account', 'asesi.approved', 'ujikom.completed'])->group(function () {
	Route::get('/banding-asesmen', [BandingAsesmenController::class, 'index'])->name('banding.index');
	Route::get('/banding-asesmen/{skemaId}', [BandingAsesmenController::class, 'show'])->name('banding.show');
	Route::post('/banding-asesmen/{skemaId}', [BandingAsesmenController::class, 'store'])->name('banding.store');
	Route::post('/banding-asesmen/{skemaId}/decline', [BandingAsesmenController::class, 'decline'])->name('banding.decline');
});
