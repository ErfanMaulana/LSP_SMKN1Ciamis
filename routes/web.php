<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Data dummy untuk carousels
    $carousels = collect([
        (object)[
            'title' => 'LSP SMKN 1 Ciamis',
            'subtitle' => 'Lembaga Sertifikasi Profesi Pihak Pertama',
            'description' => 'Menghasilkan lulusan yang kompeten dan bersertifikat sesuai standar industri nasional dan internasional.',
            'image' => 'banners/default1.jpg',
            'button_text' => 'Pelajari Lebih Lanjut',
            'button_link' => '#about'
        ],
        (object)[
            'title' => 'Sertifikasi Kompetensi',
            'subtitle' => 'Meningkatkan Kualitas dan Daya Saing Lulusan',
            'description' => 'Program sertifikasi kompetensi yang diakui oleh Badan Nasional Sertifikasi Profesi (BNSP).',
            'image' => 'banners/default2.jpg',
            'button_text' => 'Lihat Skema',
            'button_link' => '#skema'
        ],
        (object)[
            'title' => 'Standar Kompetensi Kerja',
            'subtitle' => 'Sesuai dengan Kebutuhan Industri',
            'description' => 'Mempersiapkan siswa dengan kompetensi yang relevan dan dibutuhkan oleh dunia kerja modern.',
            'image' => 'banners/default3.jpg',
            'button_text' => 'Hubungi Kami',
            'button_link' => '#contact'
        ],
    ]);
    
    // Data statistik dummy
    $totalMurid = 1200;
    $asesor = 15;
    $skema = 8;
    $tuk = 3;
    
    return view('front.home', compact('carousels', 'totalMurid', 'asesor', 'skema', 'tuk'));
})->name('front.home');

// Default login route (redirect to admin login)
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');
