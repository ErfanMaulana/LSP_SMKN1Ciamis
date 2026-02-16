<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AsesiController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\Admin\JurusanController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
|
*/

Route::prefix('admin')->group(function () {
    // Login routes (guest only)
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');
    
    // Protected admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        
        // Asesi CRUD
        Route::get('/asesi', [AsesiController::class, 'index'])->name('admin.asesi.index');
        Route::get('/asesi/create', [AsesiController::class, 'create'])->name('admin.asesi.create');
        Route::post('/asesi', [AsesiController::class, 'store'])->name('admin.asesi.store');
        Route::get('/asesi/{nik}/edit', [AsesiController::class, 'edit'])->name('admin.asesi.edit');
        Route::put('/asesi/{nik}', [AsesiController::class, 'update'])->name('admin.asesi.update');
        Route::delete('/asesi/{nik}', [AsesiController::class, 'destroy'])->name('admin.asesi.destroy');
        
        // Asesor CRUD
        Route::get('/asesor', [AsesorController::class, 'index'])->name('admin.asesor.index');
        Route::get('/asesor/create', [AsesorController::class, 'create'])->name('admin.asesor.create');
        Route::post('/asesor', [AsesorController::class, 'store'])->name('admin.asesor.store');
        Route::get('/asesor/{id}/edit', [AsesorController::class, 'edit'])->name('admin.asesor.edit');
        Route::put('/asesor/{id}', [AsesorController::class, 'update'])->name('admin.asesor.update');
        Route::delete('/asesor/{id}', [AsesorController::class, 'destroy'])->name('admin.asesor.destroy');
        
        // Jurusan CRUD
        Route::get('/jurusan', [JurusanController::class, 'index'])->name('admin.jurusan.index');
        Route::get('/jurusan/create', [JurusanController::class, 'create'])->name('admin.jurusan.create');
        Route::post('/jurusan', [JurusanController::class, 'store'])->name('admin.jurusan.store');
        Route::get('/jurusan/{id}/edit', [JurusanController::class, 'edit'])->name('admin.jurusan.edit');
        Route::put('/jurusan/{id}', [JurusanController::class, 'update'])->name('admin.jurusan.update');
        Route::delete('/jurusan/{id}', [JurusanController::class, 'destroy'])->name('admin.jurusan.destroy');
    });
});
