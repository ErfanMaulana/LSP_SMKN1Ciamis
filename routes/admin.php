<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AsesiController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\SkemaController;
use App\Http\Controllers\Admin\MitraController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\ProfileContentController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
|
*/

// Redirect old /admin/login → /login for backward compatibility
Route::get('/admin/login', fn () => redirect()->route('login'))->name('admin.login');

Route::prefix('admin')->group(function () {
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

        // Verifikasi Asesi
        Route::get('/asesi-verifikasi', [AsesiController::class, 'verifikasi'])->name('admin.asesi.verifikasi');
        Route::get('/asesi-verifikasi/{nik}', [AsesiController::class, 'showVerifikasi'])->name('admin.asesi.verifikasi.show');
        Route::post('/asesi-verifikasi/{nik}/approve', [AsesiController::class, 'approve'])->name('admin.asesi.approve');
        Route::post('/asesi-verifikasi/{nik}/reject', [AsesiController::class, 'reject'])->name('admin.asesi.reject');
        
        // Asesor CRUD
        Route::get('/asesor', [AsesorController::class, 'index'])->name('admin.asesor.index');
        Route::get('/asesor/create', [AsesorController::class, 'create'])->name('admin.asesor.create');
        Route::post('/asesor', [AsesorController::class, 'store'])->name('admin.asesor.store');
        Route::get('/asesor/{ID_asesor}/edit', [AsesorController::class, 'edit'])->name('admin.asesor.edit');
        Route::put('/asesor/{ID_asesor}', [AsesorController::class, 'update'])->name('admin.asesor.update');
        Route::delete('/asesor/{ID_asesor}', [AsesorController::class, 'destroy'])->name('admin.asesor.destroy');
        
        // Jurusan CRUD
        Route::get('/jurusan', [JurusanController::class, 'index'])->name('admin.jurusan.index');
        Route::get('/jurusan/create', [JurusanController::class, 'create'])->name('admin.jurusan.create');
        Route::post('/jurusan', [JurusanController::class, 'store'])->name('admin.jurusan.store');
        Route::get('/jurusan/{ID_jurusan}/edit', [JurusanController::class, 'edit'])->name('admin.jurusan.edit');
        Route::put('/jurusan/{ID_jurusan}', [JurusanController::class, 'update'])->name('admin.jurusan.update');
        Route::delete('/jurusan/{ID_jurusan}', [JurusanController::class, 'destroy'])->name('admin.jurusan.destroy');

        // Skema CRUD
        Route::get('/skema', [SkemaController::class, 'index'])->name('admin.skema.index');
        Route::get('/skema/create', [SkemaController::class, 'create'])->name('admin.skema.create');
        Route::post('/skema', [SkemaController::class, 'store'])->name('admin.skema.store');
        Route::get('/skema/{id}/edit', [SkemaController::class, 'edit'])->name('admin.skema.edit');
        Route::put('/skema/{id}', [SkemaController::class, 'update'])->name('admin.skema.update');
        Route::delete('/skema/{id}', [SkemaController::class, 'destroy'])->name('admin.skema.destroy');

        // Mitra CRUD
        Route::get('/mitra', [MitraController::class, 'index'])->name('admin.mitra.index');
        Route::get('/mitra/create', [MitraController::class, 'create'])->name('admin.mitra.create');
        Route::post('/mitra', [MitraController::class, 'store'])->name('admin.mitra.store');
        Route::get('/mitra/{no_mou}/edit', [MitraController::class, 'edit'])->name('admin.mitra.edit');
        Route::put('/mitra/{no_mou}', [MitraController::class, 'update'])->name('admin.mitra.update');
        Route::delete('/mitra/{no_mou}', [MitraController::class, 'destroy'])->name('admin.mitra.destroy');

        // Carousel CRUD
        Route::get('/carousel', [CarouselController::class, 'index'])->name('admin.carousel.index');
        Route::get('/carousel/create', [CarouselController::class, 'create'])->name('admin.carousel.create');
        Route::post('/carousel', [CarouselController::class, 'store'])->name('admin.carousel.store');
        Route::get('/carousel/{id}/edit', [CarouselController::class, 'edit'])->name('admin.carousel.edit');
        Route::put('/carousel/{id}', [CarouselController::class, 'update'])->name('admin.carousel.update');
        Route::delete('/carousel/{id}', [CarouselController::class, 'destroy'])->name('admin.carousel.destroy');
        Route::patch('/carousel/{id}/toggle', [CarouselController::class, 'toggleStatus'])->name('admin.carousel.toggle');

        // Social Media CRUD
        Route::get('/social-media', [SocialMediaController::class, 'index'])->name('admin.socialmedia.index');
        Route::get('/social-media/create', [SocialMediaController::class, 'create'])->name('admin.socialmedia.create');
        Route::post('/social-media', [SocialMediaController::class, 'store'])->name('admin.socialmedia.store');
        Route::get('/social-media/{id}/edit', [SocialMediaController::class, 'edit'])->name('admin.socialmedia.edit');
        Route::put('/social-media/{id}', [SocialMediaController::class, 'update'])->name('admin.socialmedia.update');
        Route::delete('/social-media/{id}', [SocialMediaController::class, 'destroy'])->name('admin.socialmedia.destroy');
        Route::patch('/social-media/{id}/toggle', [SocialMediaController::class, 'toggleStatus'])->name('admin.socialmedia.toggle');

        // Profile Content CRUD
        Route::get('/profile-content', [ProfileContentController::class, 'index'])->name('admin.profile-content.index');
        Route::get('/profile-content/create', [ProfileContentController::class, 'create'])->name('admin.profile-content.create');
        Route::post('/profile-content', [ProfileContentController::class, 'store'])->name('admin.profile-content.store');
        
        // Vision & Mission CRUD (must be before {id}/edit to avoid conflict)
        Route::get('/profile-content/vision-mission/create/{type}', [ProfileContentController::class, 'createVisionMission'])->name('admin.profile-content.vision-mission.create')->where('type', 'visi|misi');
        Route::post('/profile-content/vision-mission', [ProfileContentController::class, 'storeVisionMission'])->name('admin.profile-content.vision-mission.store');
        Route::get('/profile-content/vision-mission/{id}/edit', [ProfileContentController::class, 'editVisionMission'])->name('admin.profile-content.vision-mission.edit');
        Route::put('/profile-content/vision-mission/{id}', [ProfileContentController::class, 'updateVisionMission'])->name('admin.profile-content.vision-mission.update');
        Route::delete('/profile-content/vision-mission/{id}', [ProfileContentController::class, 'destroyVisionMission'])->name('admin.profile-content.vision-mission.destroy');
        Route::patch('/profile-content/vision-mission/{id}/toggle', [ProfileContentController::class, 'toggleVisionMissionStatus'])->name('admin.profile-content.vision-mission.toggle');
        
        Route::get('/profile-content/{id}/edit', [ProfileContentController::class, 'edit'])->name('admin.profile-content.edit');
        Route::put('/profile-content/{id}', [ProfileContentController::class, 'update'])->name('admin.profile-content.update');
        Route::delete('/profile-content/{id}', [ProfileContentController::class, 'destroy'])->name('admin.profile-content.destroy');
        Route::patch('/profile-content/{id}/toggle', [ProfileContentController::class, 'toggleStatus'])->name('admin.profile-content.toggle');
    });
});
