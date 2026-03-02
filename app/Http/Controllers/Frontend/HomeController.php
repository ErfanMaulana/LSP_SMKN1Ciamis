<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use App\Models\User;
use App\Models\Skema;
use App\Models\Tuk;
use App\Models\Asesi;
use App\Models\Asesor;

class HomeController extends Controller
{
    public function index()
    {
        $carousels = Carousel::active()->get();
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

        // Dynamic stats from database
        $totalAsesi  = Asesi::count();
        $totalAsesor = Asesor::count();
        $totalSkema  = Skema::count();
        $totalTuk    = Tuk::count();

        return view('front.home', compact(
            'carousels',
            'totalAsesi',
            'totalAsesor',
            'totalSkema',
            'totalTuk'
        ));
    }
    
}
