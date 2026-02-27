<?php

namespace Database\Seeders;

use App\Models\Carousel;
use Illuminate\Database\Seeder;

class CarouselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Carousel::create([
            'title' => 'Selamat Datang di LSP SMKN 1 Ciamis',
            'subtitle' => 'Lembaga Sertifikasi Profesional',
            'description' => 'Berkomitmen pada standar kompetensi nasional dan industri',
            'image' => 'carousel-1.jpg',
            'button_text' => 'Lihat Skema',
            'button_link' => '#skema',
            'is_active' => true,
            'urutan' => 1,
        ]);

        Carousel::create([
            'title' => 'Program Sertifikasi Terpercaya',
            'subtitle' => 'Standar BNSP',
            'description' => 'Kami menyediakan berbagai program sertifikasi sesuai standar BNSP',
            'image' => 'carousel-2.jpg',
            'button_text' => 'Daftar Sekarang',
            'button_link' => '/asesi/daftar',
            'is_active' => true,
            'urutan' => 2,
        ]);

        Carousel::create([
            'title' => 'Raih Kesempatan Emas',
            'subtitle' => 'Sertifikat Kompetensi',
            'description' => 'Daftarkan diri Anda sekarang dan raih sertifikat kompetensi',
            'image' => 'carousel-3.jpg',
            'button_text' => 'Pelajari Lebih Lanjut',
            'button_link' => '/profil',
            'is_active' => true,
            'urutan' => 3,
        ]);
    }
}
