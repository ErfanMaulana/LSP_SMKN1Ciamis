<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AsesiController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\SkemaController;
use App\Http\Controllers\Admin\SocialMediaController;
use App\Http\Controllers\Admin\ProfileContentController;
use App\Http\Controllers\Admin\TukController;
use App\Http\Controllers\Admin\JadwalUjikomController;
use App\Http\Controllers\Admin\AkunAsesiController;
use App\Http\Controllers\Admin\PenugasanAsesorController;
use App\Http\Controllers\Admin\KelompokController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\AsesmenMandiriController;
use App\Http\Controllers\Admin\NilaiAsesorController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\KontakController;
use App\Http\Controllers\Admin\MitraController;
use App\Http\Controllers\Admin\PanduanController;
use App\Http\Controllers\Admin\LogActivityController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UmpanBalikKomponenController;
use App\Http\Controllers\Admin\UmpanBalikHasilController;
use App\Http\Controllers\Admin\PersetujuanAsesmenController;
use App\Http\Controllers\Admin\CeklisObservasiAktivitasPraktikController;
use App\Http\Controllers\Admin\RekamanAsesmenKompetensiController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
|
*/

Route::prefix('admin')->group(function () {
    // Admin login routes (not protected)
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');

    // Protected admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('permission:dashboard.view');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

        // Admin profile
        Route::get('/profil', [ProfileController::class, 'edit'])->name('admin.profile.edit');
        Route::put('/profil', [ProfileController::class, 'update'])->name('admin.profile.update');
        Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('admin.profile.password');

        // Global Search
        Route::get('/search', [SearchController::class, 'search'])->name('admin.search');
        
        // Asesi CRUD
        Route::middleware('permission:asesi.view')->group(function () {
            Route::get('/asesi', [AsesiController::class, 'index'])->name('admin.asesi.index');
            Route::get('/asesi/create', [AsesiController::class, 'create'])->name('admin.asesi.create')->middleware('permission:asesi.create');
            Route::post('/asesi', [AsesiController::class, 'store'])->name('admin.asesi.store')->middleware('permission:asesi.create');
            Route::post('/asesi/import-activated', [AsesiController::class, 'importActivated'])->name('admin.asesi.import-activated')->middleware('permission:asesi.create');
            Route::get('/asesi/template-activated', [AsesiController::class, 'downloadActivatedTemplate'])->name('admin.asesi.template-activated')->middleware('permission:asesi.view');
            Route::get('/asesi/export-activated', [AsesiController::class, 'exportActivated'])->name('admin.asesi.export-activated')->middleware('permission:asesi.view');
            Route::get('/asesi/{nik}/edit', [AsesiController::class, 'edit'])->name('admin.asesi.edit')->middleware('permission:asesi.edit');
            Route::put('/asesi/{nik}', [AsesiController::class, 'update'])->name('admin.asesi.update')->middleware('permission:asesi.edit');
            Route::delete('/asesi/{nik}', [AsesiController::class, 'destroy'])->name('admin.asesi.destroy')->middleware('permission:asesi.delete');
            Route::post('/asesi/bulk-delete', [AsesiController::class, 'bulkDelete'])->name('admin.asesi.bulk-delete')->middleware('permission:asesi.delete');
        });

        // Verifikasi Asesi
        Route::middleware('permission:verifikasi-asesi.view')->group(function () {
            Route::get('/asesi-verifikasi', [AsesiController::class, 'verifikasi'])->name('admin.asesi.verifikasi');
            Route::post('/asesi-verifikasi/bulk-approve', [AsesiController::class, 'bulkApprove'])->name('admin.asesi.bulk-approve')->middleware('permission:verifikasi-asesi.approve');
            Route::post('/asesi-verifikasi/bulk-reject', [AsesiController::class, 'bulkReject'])->name('admin.asesi.bulk-reject')->middleware('permission:verifikasi-asesi.reject');
            Route::get('/asesi-verifikasi/{nik}', [AsesiController::class, 'showVerifikasi'])->name('admin.asesi.verifikasi.show');
            Route::post('/asesi-verifikasi/{nik}/approve', [AsesiController::class, 'approve'])->name('admin.asesi.approve')->middleware('permission:verifikasi-asesi.approve');
            Route::post('/asesi-verifikasi/{nik}/reject', [AsesiController::class, 'reject'])->name('admin.asesi.reject')->middleware('permission:verifikasi-asesi.reject');
        });
        
        // Asesor CRUD
        Route::middleware('permission:asesor.view')->group(function () {
            Route::get('/asesor', [AsesorController::class, 'index'])->name('admin.asesor.index');
            Route::get('/asesor/create', [AsesorController::class, 'create'])->name('admin.asesor.create')->middleware('permission:asesor.create');
            Route::post('/asesor', [AsesorController::class, 'store'])->name('admin.asesor.store')->middleware('permission:asesor.create');
            Route::get('/asesor/{ID_asesor}', [AsesorController::class, 'show'])->name('admin.asesor.show');
            Route::get('/asesor/{ID_asesor}/edit', [AsesorController::class, 'edit'])->name('admin.asesor.edit')->middleware('permission:asesor.edit');
            Route::put('/asesor/{ID_asesor}', [AsesorController::class, 'update'])->name('admin.asesor.update')->middleware('permission:asesor.edit');
            Route::delete('/asesor/{ID_asesor}', [AsesorController::class, 'destroy'])->name('admin.asesor.destroy')->middleware('permission:asesor.delete');
        });
        
        // Mitra CRUD
        Route::middleware('permission:mitra.view')->group(function () {
            Route::get('/mitra', [MitraController::class, 'index'])->name('admin.mitra.index');
            Route::get('/mitra/create', [MitraController::class, 'create'])->name('admin.mitra.create')->middleware('permission:mitra.create');
            Route::post('/mitra', [MitraController::class, 'store'])->name('admin.mitra.store')->middleware('permission:mitra.create');
            Route::get('/mitra/{no_mou}/edit', [MitraController::class, 'edit'])->name('admin.mitra.edit')->middleware('permission:mitra.edit');
            Route::put('/mitra/{no_mou}', [MitraController::class, 'update'])->name('admin.mitra.update')->middleware('permission:mitra.edit');
            Route::delete('/mitra/{no_mou}', [MitraController::class, 'destroy'])->name('admin.mitra.destroy')->middleware('permission:mitra.delete');
        });

        // Jurusan CRUD
        Route::middleware('permission:jurusan.view')->group(function () {
            Route::get('/jurusan', [JurusanController::class, 'index'])->name('admin.jurusan.index');
            Route::get('/jurusan/create', [JurusanController::class, 'create'])->name('admin.jurusan.create')->middleware('permission:jurusan.create');
            Route::post('/jurusan', [JurusanController::class, 'store'])->name('admin.jurusan.store')->middleware('permission:jurusan.create');
            Route::get('/jurusan/{ID_jurusan}', [JurusanController::class, 'show'])->name('admin.jurusan.show');
            Route::get('/jurusan/{ID_jurusan}/edit', [JurusanController::class, 'edit'])->name('admin.jurusan.edit')->middleware('permission:jurusan.edit');
            Route::put('/jurusan/{ID_jurusan}', [JurusanController::class, 'update'])->name('admin.jurusan.update')->middleware('permission:jurusan.edit');
            Route::delete('/jurusan/{ID_jurusan}', [JurusanController::class, 'destroy'])->name('admin.jurusan.destroy')->middleware('permission:jurusan.delete');
        });

        // Skema CRUD
        Route::middleware('permission:skema.view')->group(function () {
            Route::get('/skema', [SkemaController::class, 'index'])->name('admin.skema.index');
            Route::get('/skema/create', [SkemaController::class, 'create'])->name('admin.skema.create')->middleware('permission:skema.create');
            Route::post('/skema', [SkemaController::class, 'store'])->name('admin.skema.store')->middleware('permission:skema.create');
            Route::get('/skema/{id}', [SkemaController::class, 'show'])->name('admin.skema.show');
            Route::get('/skema/{id}/edit', [SkemaController::class, 'edit'])->name('admin.skema.edit')->middleware('permission:skema.edit');
            Route::put('/skema/{id}', [SkemaController::class, 'update'])->name('admin.skema.update')->middleware('permission:skema.edit');
            Route::delete('/skema/{id}', [SkemaController::class, 'destroy'])->name('admin.skema.destroy')->middleware('permission:skema.delete');
        });

        // Carousel CRUD
        Route::middleware('permission:carousel.view')->group(function () {
            Route::get('/carousel', [CarouselController::class, 'index'])->name('admin.carousel.index');
            Route::get('/carousel/create', [CarouselController::class, 'create'])->name('admin.carousel.create')->middleware('permission:carousel.create');
            Route::post('/carousel', [CarouselController::class, 'store'])->name('admin.carousel.store')->middleware('permission:carousel.create');
            Route::get('/carousel/{id}/edit', [CarouselController::class, 'edit'])->name('admin.carousel.edit')->middleware('permission:carousel.edit');
            Route::put('/carousel/{id}', [CarouselController::class, 'update'])->name('admin.carousel.update')->middleware('permission:carousel.edit');
            Route::delete('/carousel/{id}', [CarouselController::class, 'destroy'])->name('admin.carousel.destroy')->middleware('permission:carousel.delete');
            Route::patch('/carousel/{id}/toggle', [CarouselController::class, 'toggleStatus'])->name('admin.carousel.toggle')->middleware('permission:carousel.edit');
        });

        // Berita CRUD
        Route::middleware('permission:berita.view')->group(function () {
            Route::get('/berita', [BeritaController::class, 'index'])->name('admin.berita.index');
            Route::get('/berita/create', [BeritaController::class, 'create'])->name('admin.berita.create')->middleware('permission:berita.create');
            Route::post('/berita', [BeritaController::class, 'store'])->name('admin.berita.store')->middleware('permission:berita.create');
            Route::get('/berita/{id}', [BeritaController::class, 'show'])->name('admin.berita.show');
            Route::get('/berita/{id}/edit', [BeritaController::class, 'edit'])->name('admin.berita.edit')->middleware('permission:berita.edit');
            Route::put('/berita/{id}', [BeritaController::class, 'update'])->name('admin.berita.update')->middleware('permission:berita.edit');
            Route::delete('/berita/{id}', [BeritaController::class, 'destroy'])->name('admin.berita.destroy')->middleware('permission:berita.delete');
        });

        // Kontak (single page CRUD)
        Route::middleware('permission:kontak.view')->group(function () {
            Route::get('/kontak', [KontakController::class, 'index'])->name('admin.kontak.index');
            Route::get('/kontak/edit', [KontakController::class, 'edit'])->name('admin.kontak.edit')->middleware('permission:kontak.edit');
            Route::put('/kontak', [KontakController::class, 'update'])->name('admin.kontak.update')->middleware('permission:kontak.edit');
        });

        // Social Media CRUD
        Route::middleware('permission:socialmedia.view')->group(function () {
            Route::get('/social-media', [SocialMediaController::class, 'index'])->name('admin.socialmedia.index');
            Route::get('/social-media/create', [SocialMediaController::class, 'create'])->name('admin.socialmedia.create')->middleware('permission:socialmedia.create');
            Route::post('/social-media', [SocialMediaController::class, 'store'])->name('admin.socialmedia.store')->middleware('permission:socialmedia.create');
            Route::get('/social-media/{id}/edit', [SocialMediaController::class, 'edit'])->name('admin.socialmedia.edit')->middleware('permission:socialmedia.edit');
            Route::put('/social-media/{id}', [SocialMediaController::class, 'update'])->name('admin.socialmedia.update')->middleware('permission:socialmedia.edit');
            Route::delete('/social-media/{id}', [SocialMediaController::class, 'destroy'])->name('admin.socialmedia.destroy')->middleware('permission:socialmedia.delete');
            Route::patch('/social-media/{id}/toggle', [SocialMediaController::class, 'toggleStatus'])->name('admin.socialmedia.toggle')->middleware('permission:socialmedia.edit');
        });

        // Panduan Website CRUD (per section)
        Route::middleware('permission:panduan.view')->group(function () {
            Route::get('/panduan/{section}', [PanduanController::class, 'index'])
                ->name('admin.panduan.index')
                ->where('section', 'alur-keseluruhan-sistem|peran-asesi|peran-asesor|peran-admin');

            Route::get('/panduan/{section}/create', [PanduanController::class, 'create'])
                ->name('admin.panduan.create')
                ->where('section', 'alur-keseluruhan-sistem|peran-asesi|peran-asesor|peran-admin')
                ->middleware('permission:panduan.create');

            Route::post('/panduan/{section}', [PanduanController::class, 'store'])
                ->name('admin.panduan.store')
                ->where('section', 'alur-keseluruhan-sistem|peran-asesi|peran-asesor|peran-admin')
                ->middleware('permission:panduan.create');

            Route::get('/panduan/{section}/{id}/edit', [PanduanController::class, 'edit'])
                ->name('admin.panduan.edit')
                ->where('section', 'alur-keseluruhan-sistem|peran-asesi|peran-asesor|peran-admin')
                ->middleware('permission:panduan.edit');

            Route::put('/panduan/{section}/{id}', [PanduanController::class, 'update'])
                ->name('admin.panduan.update')
                ->where('section', 'alur-keseluruhan-sistem|peran-asesi|peran-asesor|peran-admin')
                ->middleware('permission:panduan.edit');

            Route::delete('/panduan/{section}/{id}', [PanduanController::class, 'destroy'])
                ->name('admin.panduan.destroy')
                ->where('section', 'alur-keseluruhan-sistem|peran-asesi|peran-asesor|peran-admin')
                ->middleware('permission:panduan.delete');

            Route::post('/panduan/{section}/bulk-delete', [PanduanController::class, 'bulkDestroy'])
                ->name('admin.panduan.bulk-destroy')
                ->where('section', 'alur-keseluruhan-sistem|peran-asesi|peran-asesor|peran-admin')
                ->middleware('permission:panduan.delete');

            Route::patch('/panduan/{section}/{id}/toggle', [PanduanController::class, 'toggleStatus'])
                ->name('admin.panduan.toggle')
                ->where('section', 'alur-keseluruhan-sistem|peran-asesi|peran-asesor|peran-admin')
                ->middleware('permission:panduan.edit');
        });

        // Profile Content CRUD
        Route::middleware('permission:profile-content.view')->group(function () {
            Route::get('/profile-content', [ProfileContentController::class, 'index'])->name('admin.profile-content.index');
            Route::get('/profile-content/create', [ProfileContentController::class, 'create'])->name('admin.profile-content.create')->middleware('permission:profile-content.create');
            Route::post('/profile-content', [ProfileContentController::class, 'store'])->name('admin.profile-content.store')->middleware('permission:profile-content.create');
            
            // Vision & Mission CRUD (must be before {id}/edit to avoid conflict)
            Route::get('/profile-content/vision-mission/create/{type}', [ProfileContentController::class, 'createVisionMission'])->name('admin.profile-content.vision-mission.create')->where('type', 'visi|misi')->middleware('permission:profile-content.create');
            Route::post('/profile-content/vision-mission', [ProfileContentController::class, 'storeVisionMission'])->name('admin.profile-content.vision-mission.store')->middleware('permission:profile-content.create');
            Route::get('/profile-content/vision-mission/{id}/edit', [ProfileContentController::class, 'editVisionMission'])->name('admin.profile-content.vision-mission.edit')->middleware('permission:profile-content.edit');
            Route::put('/profile-content/vision-mission/{id}', [ProfileContentController::class, 'updateVisionMission'])->name('admin.profile-content.vision-mission.update')->middleware('permission:profile-content.edit');
            Route::delete('/profile-content/vision-mission/{id}', [ProfileContentController::class, 'destroyVisionMission'])->name('admin.profile-content.vision-mission.destroy')->middleware('permission:profile-content.delete');
            Route::patch('/profile-content/vision-mission/{id}/toggle', [ProfileContentController::class, 'toggleVisionMissionStatus'])->name('admin.profile-content.vision-mission.toggle')->middleware('permission:profile-content.edit');
            
            Route::get('/profile-content/{id}/edit', [ProfileContentController::class, 'edit'])->name('admin.profile-content.edit')->middleware('permission:profile-content.edit');
            Route::put('/profile-content/{id}', [ProfileContentController::class, 'update'])->name('admin.profile-content.update')->middleware('permission:profile-content.edit');
            Route::delete('/profile-content/{id}', [ProfileContentController::class, 'destroy'])->name('admin.profile-content.destroy')->middleware('permission:profile-content.delete');
            Route::patch('/profile-content/{id}/toggle', [ProfileContentController::class, 'toggleStatus'])->name('admin.profile-content.toggle')->middleware('permission:profile-content.edit');
        });

        // TUK CRUD
        Route::middleware('permission:tuk.view')->group(function () {
            Route::get('/tuk', [TukController::class, 'index'])->name('admin.tuk.index');
            Route::get('/tuk/create', [TukController::class, 'create'])->name('admin.tuk.create')->middleware('permission:tuk.create');
            Route::post('/tuk', [TukController::class, 'store'])->name('admin.tuk.store')->middleware('permission:tuk.create');
            Route::get('/tuk/{id}/edit', [TukController::class, 'edit'])->name('admin.tuk.edit')->middleware('permission:tuk.edit');
            Route::put('/tuk/{id}', [TukController::class, 'update'])->name('admin.tuk.update')->middleware('permission:tuk.edit');
            Route::delete('/tuk/{id}', [TukController::class, 'destroy'])->name('admin.tuk.destroy')->middleware('permission:tuk.delete');
            Route::patch('/tuk/{id}/toggle', [TukController::class, 'toggleStatus'])->name('admin.tuk.toggle')->middleware('permission:tuk.edit');
        });

        // Jadwal Ujikom CRUD
        Route::middleware('permission:jadwal-ujikom.view')->group(function () {
            Route::get('/jadwal-ujikom', [JadwalUjikomController::class, 'index'])->name('admin.jadwal-ujikom.index');
            Route::get('/jadwal-ujikom/create', [JadwalUjikomController::class, 'create'])->name('admin.jadwal-ujikom.create')->middleware('permission:jadwal-ujikom.create');
            Route::get('/jadwal-ujikom/asesi-rekomendasi', [JadwalUjikomController::class, 'getAsesiBySkema'])->name('admin.jadwal-ujikom.asesi-rekomendasi');
            Route::post('/jadwal-ujikom', [JadwalUjikomController::class, 'store'])->name('admin.jadwal-ujikom.store')->middleware('permission:jadwal-ujikom.create');
            Route::get('/jadwal-ujikom/{id}/edit', [JadwalUjikomController::class, 'edit'])->name('admin.jadwal-ujikom.edit')->middleware('permission:jadwal-ujikom.edit');
            Route::put('/jadwal-ujikom/{id}', [JadwalUjikomController::class, 'update'])->name('admin.jadwal-ujikom.update')->middleware('permission:jadwal-ujikom.edit');
            Route::delete('/jadwal-ujikom/{id}', [JadwalUjikomController::class, 'destroy'])->name('admin.jadwal-ujikom.destroy')->middleware('permission:jadwal-ujikom.delete');
            Route::patch('/jadwal-ujikom/{id}/status', [JadwalUjikomController::class, 'updateStatus'])->name('admin.jadwal-ujikom.status')->middleware('permission:jadwal-ujikom.status');
        });

        // Umpan Balik Komponen CRUD
        Route::middleware('permission:jadwal-ujikom.view')->group(function () {
            Route::get('/umpan-balik-komponen', [UmpanBalikKomponenController::class, 'index'])->name('admin.umpan-balik-komponen.index');
            Route::get('/umpan-balik-komponen/create', [UmpanBalikKomponenController::class, 'create'])->name('admin.umpan-balik-komponen.create')->middleware('permission:jadwal-ujikom.create');
            Route::post('/umpan-balik-komponen', [UmpanBalikKomponenController::class, 'store'])->name('admin.umpan-balik-komponen.store')->middleware('permission:jadwal-ujikom.create');
            Route::get('/umpan-balik-komponen/skema/{skemaId}', [UmpanBalikKomponenController::class, 'show'])->name('admin.umpan-balik-komponen.show');
            Route::get('/umpan-balik-komponen/skema/{skemaId}/edit', [UmpanBalikKomponenController::class, 'editSkema'])->name('admin.umpan-balik-komponen.edit-skema')->middleware('permission:jadwal-ujikom.edit');
            Route::delete('/umpan-balik-komponen/skema/{skemaId}', [UmpanBalikKomponenController::class, 'destroyBySkema'])->name('admin.umpan-balik-komponen.destroy-skema')->middleware('permission:jadwal-ujikom.delete');
            Route::get('/umpan-balik-komponen/{id}/edit', [UmpanBalikKomponenController::class, 'edit'])->name('admin.umpan-balik-komponen.edit')->middleware('permission:jadwal-ujikom.edit');
            Route::put('/umpan-balik-komponen/{id}', [UmpanBalikKomponenController::class, 'update'])->name('admin.umpan-balik-komponen.update')->middleware('permission:jadwal-ujikom.edit');
            Route::delete('/umpan-balik-komponen/{id}', [UmpanBalikKomponenController::class, 'destroy'])->name('admin.umpan-balik-komponen.destroy')->middleware('permission:jadwal-ujikom.delete');
        });

        // Hasil Umpan Balik Asesi
        Route::middleware('permission:jadwal-ujikom.view')->group(function () {
            Route::get('/umpan-balik-hasil', [UmpanBalikHasilController::class, 'index'])->name('admin.umpan-balik-hasil.index');
        });

        // Persetujuan Asesmen dan Kerahasiaan CRUD
        Route::middleware('permission:persetujuan-asesmen.view')->group(function () {
            Route::get('/persetujuan-asesmen', [PersetujuanAsesmenController::class, 'index'])->name('admin.persetujuan-asesmen.index');
            Route::get('/persetujuan-asesmen/skema-participants', [PersetujuanAsesmenController::class, 'participantsBySkema'])->name('admin.persetujuan-asesmen.skema-participants');
            Route::get('/persetujuan-asesmen/create', [PersetujuanAsesmenController::class, 'create'])->name('admin.persetujuan-asesmen.create')->middleware('permission:persetujuan-asesmen.create');
            Route::post('/persetujuan-asesmen', [PersetujuanAsesmenController::class, 'store'])->name('admin.persetujuan-asesmen.store')->middleware('permission:persetujuan-asesmen.create');
            Route::get('/persetujuan-asesmen/{id}', [PersetujuanAsesmenController::class, 'show'])->name('admin.persetujuan-asesmen.show');
            Route::get('/persetujuan-asesmen/{id}/edit', [PersetujuanAsesmenController::class, 'edit'])->name('admin.persetujuan-asesmen.edit')->middleware('permission:persetujuan-asesmen.edit');
            Route::put('/persetujuan-asesmen/{id}', [PersetujuanAsesmenController::class, 'update'])->name('admin.persetujuan-asesmen.update')->middleware('permission:persetujuan-asesmen.edit');
            Route::delete('/persetujuan-asesmen/{id}', [PersetujuanAsesmenController::class, 'destroy'])->name('admin.persetujuan-asesmen.destroy')->middleware('permission:persetujuan-asesmen.delete');
        });

        // Ceklis Observasi Aktivitas Praktik CRUD
        Route::middleware('permission:ceklis-observasi-aktivitas-praktik.view')->group(function () {
            Route::get('/ceklis-observasi-aktivitas-praktik', [CeklisObservasiAktivitasPraktikController::class, 'index'])->name('admin.ceklis-observasi-aktivitas-praktik.index');
            Route::get('/ceklis-observasi-aktivitas-praktik/skema-participants', [CeklisObservasiAktivitasPraktikController::class, 'participantsBySkema'])->name('admin.ceklis-observasi-aktivitas-praktik.skema-participants');
            Route::get('/ceklis-observasi-aktivitas-praktik/skema-structure', [CeklisObservasiAktivitasPraktikController::class, 'skemaStructure'])->name('admin.ceklis-observasi-aktivitas-praktik.skema-structure');
            Route::get('/ceklis-observasi-aktivitas-praktik/create', [CeklisObservasiAktivitasPraktikController::class, 'create'])->name('admin.ceklis-observasi-aktivitas-praktik.create')->middleware('permission:ceklis-observasi-aktivitas-praktik.create');
            Route::post('/ceklis-observasi-aktivitas-praktik', [CeklisObservasiAktivitasPraktikController::class, 'store'])->name('admin.ceklis-observasi-aktivitas-praktik.store')->middleware('permission:ceklis-observasi-aktivitas-praktik.create');
            Route::get('/ceklis-observasi-aktivitas-praktik/{id}', [CeklisObservasiAktivitasPraktikController::class, 'show'])->name('admin.ceklis-observasi-aktivitas-praktik.show');
            Route::get('/ceklis-observasi-aktivitas-praktik/{id}/edit', [CeklisObservasiAktivitasPraktikController::class, 'edit'])->name('admin.ceklis-observasi-aktivitas-praktik.edit')->middleware('permission:ceklis-observasi-aktivitas-praktik.edit');
            Route::put('/ceklis-observasi-aktivitas-praktik/{id}', [CeklisObservasiAktivitasPraktikController::class, 'update'])->name('admin.ceklis-observasi-aktivitas-praktik.update')->middleware('permission:ceklis-observasi-aktivitas-praktik.edit');
            Route::delete('/ceklis-observasi-aktivitas-praktik/{id}', [CeklisObservasiAktivitasPraktikController::class, 'destroy'])->name('admin.ceklis-observasi-aktivitas-praktik.destroy')->middleware('permission:ceklis-observasi-aktivitas-praktik.delete');
        });

        // Rekaman Asesmen Kompetensi CRUD
        Route::middleware('permission:rekaman-asesmen-kompetensi.view')->group(function () {
            Route::get('/rekaman-asesmen-kompetensi', [RekamanAsesmenKompetensiController::class, 'index'])->name('admin.rekaman-asesmen-kompetensi.index');
            Route::get('/rekaman-asesmen-kompetensi/skema-participants', [RekamanAsesmenKompetensiController::class, 'participantsBySkema'])->name('admin.rekaman-asesmen-kompetensi.skema-participants');
            Route::get('/rekaman-asesmen-kompetensi/skema-units', [RekamanAsesmenKompetensiController::class, 'skemaUnits'])->name('admin.rekaman-asesmen-kompetensi.skema-units');
            Route::get('/rekaman-asesmen-kompetensi/create', [RekamanAsesmenKompetensiController::class, 'create'])->name('admin.rekaman-asesmen-kompetensi.create')->middleware('permission:rekaman-asesmen-kompetensi.create');
            Route::post('/rekaman-asesmen-kompetensi', [RekamanAsesmenKompetensiController::class, 'store'])->name('admin.rekaman-asesmen-kompetensi.store')->middleware('permission:rekaman-asesmen-kompetensi.create');
            Route::get('/rekaman-asesmen-kompetensi/{id}', [RekamanAsesmenKompetensiController::class, 'show'])->name('admin.rekaman-asesmen-kompetensi.show');
            Route::get('/rekaman-asesmen-kompetensi/{id}/edit', [RekamanAsesmenKompetensiController::class, 'edit'])->name('admin.rekaman-asesmen-kompetensi.edit')->middleware('permission:rekaman-asesmen-kompetensi.edit');
            Route::put('/rekaman-asesmen-kompetensi/{id}', [RekamanAsesmenKompetensiController::class, 'update'])->name('admin.rekaman-asesmen-kompetensi.update')->middleware('permission:rekaman-asesmen-kompetensi.edit');
            Route::delete('/rekaman-asesmen-kompetensi/{id}', [RekamanAsesmenKompetensiController::class, 'destroy'])->name('admin.rekaman-asesmen-kompetensi.destroy')->middleware('permission:rekaman-asesmen-kompetensi.delete');
        });

        // Penugasan Asesor ke Asesi
        Route::middleware('permission:penugasan-asesor.view')->group(function () {
            Route::get('/penugasan-asesor', [PenugasanAsesorController::class, 'index'])->name('admin.penugasan-asesor.index');
            Route::get('/penugasan-asesor/{ID_asesor}', [PenugasanAsesorController::class, 'show'])->name('admin.penugasan-asesor.show');
            Route::post('/penugasan-asesor/{ID_asesor}/assign', [PenugasanAsesorController::class, 'assign'])->name('admin.penugasan-asesor.assign')->middleware('permission:penugasan-asesor.assign');
            Route::post('/penugasan-asesor/{ID_asesor}/assign-bulk', [PenugasanAsesorController::class, 'assignBulk'])->name('admin.penugasan-asesor.assign-bulk')->middleware('permission:penugasan-asesor.assign');
            Route::delete('/penugasan-asesor/{ID_asesor}/unassign/{NIK}', [PenugasanAsesorController::class, 'unassign'])->name('admin.penugasan-asesor.unassign')->middleware('permission:penugasan-asesor.assign');
        });

        // Kelompok CRUD + Manage Asesi
        Route::middleware('permission:kelompok.view')->group(function () {
            Route::get('/kelompok', [KelompokController::class, 'index'])->name('admin.kelompok.index');
            Route::get('/kelompok/create', [KelompokController::class, 'create'])->name('admin.kelompok.create')->middleware('permission:kelompok.create');
            Route::post('/kelompok', [KelompokController::class, 'store'])->name('admin.kelompok.store')->middleware('permission:kelompok.create');
            Route::get('/kelompok/{id}', [KelompokController::class, 'show'])->name('admin.kelompok.show');
            Route::get('/kelompok/{id}/edit', [KelompokController::class, 'edit'])->name('admin.kelompok.edit')->middleware('permission:kelompok.edit');
            Route::put('/kelompok/{id}', [KelompokController::class, 'update'])->name('admin.kelompok.update')->middleware('permission:kelompok.edit');
            Route::delete('/kelompok/{id}', [KelompokController::class, 'destroy'])->name('admin.kelompok.destroy')->middleware('permission:kelompok.delete');
            Route::post('/kelompok/{id}/assign', [KelompokController::class, 'assignAsesi'])->name('admin.kelompok.assign')->middleware('permission:kelompok.manage');
            Route::post('/kelompok/{id}/assign-bulk', [KelompokController::class, 'assignBulk'])->name('admin.kelompok.assign-bulk')->middleware('permission:kelompok.manage');
            Route::delete('/kelompok/{id}/unassign/{NIK}', [KelompokController::class, 'unassignAsesi'])->name('admin.kelompok.unassign')->middleware('permission:kelompok.manage');
        });

        // Akun Asesi (NIK-based account management)
        Route::middleware('permission:akun-asesi.view')->group(function () {
            Route::get('/akun-asesi', [AkunAsesiController::class, 'index'])->name('admin.akun-asesi.index');
            Route::post('/akun-asesi', [AkunAsesiController::class, 'store'])->name('admin.akun-asesi.store')->middleware('permission:akun-asesi.create');
            Route::post('/akun-asesi/import', [AkunAsesiController::class, 'import'])->name('admin.akun-asesi.import')->middleware('permission:akun-asesi.import');
            Route::get('/akun-asesi/template', [AkunAsesiController::class, 'downloadTemplate'])->name('admin.akun-asesi.template');
            Route::patch('/akun-asesi/{id}/reset-password', [AkunAsesiController::class, 'resetPassword'])->name('admin.akun-asesi.reset-password')->middleware('permission:akun-asesi.reset');
            Route::delete('/akun-asesi/{id}', [AkunAsesiController::class, 'destroy'])->name('admin.akun-asesi.destroy')->middleware('permission:akun-asesi.delete');
        });

        // ─── Role Management (Super Admin / permission:role.*) ───
        Route::middleware('permission:role.view')->group(function () {
            Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
            Route::get('/roles/create', [RoleController::class, 'create'])->name('admin.roles.create')->middleware('permission:role.create');
            Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store')->middleware('permission:role.create');
            Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit')->middleware('permission:role.edit');
            Route::put('/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update')->middleware('permission:role.edit');
            Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy')->middleware('permission:role.delete');
        });

        // ─── Admin Management (Super Admin / permission:admin.*) ───
        Route::middleware('permission:admin.view')->group(function () {
            Route::get('/admin-management', [AdminManagementController::class, 'index'])->name('admin.admin-management.index');
            Route::get('/admin-management/create', [AdminManagementController::class, 'create'])->name('admin.admin-management.create')->middleware('permission:admin.create');
            Route::post('/admin-management', [AdminManagementController::class, 'store'])->name('admin.admin-management.store')->middleware('permission:admin.create');
            Route::get('/admin-management/{admin}/edit', [AdminManagementController::class, 'edit'])->name('admin.admin-management.edit')->middleware('permission:admin.edit');
            Route::put('/admin-management/{admin}', [AdminManagementController::class, 'update'])->name('admin.admin-management.update')->middleware('permission:admin.edit');
            Route::delete('/admin-management/{admin}', [AdminManagementController::class, 'destroy'])->name('admin.admin-management.destroy')->middleware('permission:admin.delete');
        });

        // ─── Asesmen Mandiri (Admin monitoring) ───
        Route::middleware('permission:asesmen-mandiri.view')->group(function () {
            Route::get('/asesmen-mandiri', [AsesmenMandiriController::class, 'index'])->name('admin.asesmen-mandiri.index');
            Route::get('/asesmen-mandiri/{asesiNik}/{skemaId}', [AsesmenMandiriController::class, 'show'])->name('admin.asesmen-mandiri.show');
        });

        // ─── Nilai (Admin monitoring) ───
        Route::middleware('permission:nilai-asesor.view')->group(function () {
            Route::get('/nilai-asesor', [NilaiAsesorController::class, 'index'])->name('admin.nilai-asesor.index');
            Route::get('/nilai-asesor/{asesiNik}/{skemaId}', [NilaiAsesorController::class, 'show'])->name('admin.nilai-asesor.show');
            Route::post('/nilai-asesor/{skemaId}/update-kkm', [NilaiAsesorController::class, 'updateKkm'])->name('admin.nilai-asesor.update-kkm');
        });

        // Log Activity (Super Admin only, without permission middleware)
        Route::middleware('super-admin')->group(function () {
            Route::get('/log-activity/user', [LogActivityController::class, 'userIndex'])->name('admin.log-activity.user');
            Route::get('/log-activity/admin', [LogActivityController::class, 'adminIndex'])->name('admin.log-activity.admin');
            Route::get('/log-activity/user/export', [LogActivityController::class, 'userExport'])->name('admin.log-activity.user.export');
            Route::get('/log-activity/admin/export', [LogActivityController::class, 'adminExport'])->name('admin.log-activity.admin.export');
        });
    });
});
