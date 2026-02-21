<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use App\Models\User;
use App\Models\Skema;
use App\Models\Tuk;

class HomeController extends Controller
{
    public function index()
    {
        $carousels = Carousel::where('is_active', 1)->latest()->get();
        if ($carousels->isEmpty()) {
        $carousels = collect([
            (object)[
                'title' => 'Sertifikasi Kompetensi Siswa Kejuruan',
                'subtitle' => 'Lembaga Sertifikasi Profesi (LSP P1)',
                'description' => 'Pengujian kompetensi berbasis standar industri dan BNSP untuk memastikan lulusan siap kerja dan profesional.',
                'image' => 'images/jellyfish.jpg',
                'button_text' => 'Lihat Skema',
                'button_link' => '#skema'
            ]
        ]);
    }

        // sementara dummy dulu biar ga error
        $totalMurid  = 1200;
        $totalAsesor = 45;
        $totalSkema  = 12;
        $totalTuk    = 8;

        return view('front.home', compact(
            'carousels',
            'totalMurid',
            'totalAsesor',
            'totalSkema',
            'totalTuk'
        ));
    }
    
}
