<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use App\Models\User;
use App\Models\Skema;
use App\Models\Tuk;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Jurusan;
use App\Models\ProfileContent;
use App\Models\ProfileVisionMission;

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

        // Dynamic profile content from admin-managed data
        $sejarah = ProfileContent::byType('sejarah')->active()->get();
        $milestones = ProfileContent::byType('milestone')->active()->get();
        $visions = ProfileVisionMission::byType('visi')->active()->get();
        $missions = ProfileVisionMission::byType('misi')->active()->get();

        $jurusanList = Jurusan::withCount('skemas')
            ->having('skemas_count', '>', 0)
            ->orderBy('skemas_count', 'desc')
            ->get()
            ->map(function ($jurusan) {
                $iconMap = [
                    'PPLG' => ['icon' => 'bi-pc-display-horizontal', 'color' => 'ic-blue'],
                    'AKL'  => ['icon' => 'bi-calculator-fill', 'color' => 'ic-blue'],
                    'PM'   => ['icon' => 'bi-graph-up-arrow', 'color' => 'ic-blue'],
                    'MPLB' => ['icon' => 'bi-briefcase-fill', 'color' => 'ic-blue'],
                    'DKV'  => ['icon' => 'bi-palette-fill', 'color' => 'ic-blue'],
                    'KLN'  => ['icon' => 'bi-egg-fried', 'color' => 'ic-blue'],
                    'HTL'  => ['icon' => 'bi-building', 'color' => 'ic-blue'],
                ];

                $iconData = $iconMap[$jurusan->kode_jurusan] ?? ['icon' => 'bi-mortarboard-fill', 'color' => 'ic-blue'];

                return [
                    'nama' => $jurusan->nama_jurusan,
                    'kode' => $jurusan->kode_jurusan,
                    'icon' => $iconData['icon'],
                    'color' => $iconData['color'],
                    'skema_count' => $jurusan->skemas_count,
                    'visi' => $jurusan->visi,
                ];
            });

        return view('front.home', compact(
            'carousels',
            'totalAsesi',
            'totalAsesor',
            'totalSkema',
            'totalTuk',
            'sejarah',
            'milestones',
            'visions',
            'missions',
            'jurusanList'
        ));
    }
    
}
