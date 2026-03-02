<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ProfileContent;
use App\Models\Jurusan;

class ProfilController extends Controller
{
    public function index()
    {
        $sejarah = ProfileContent::byType('sejarah')->active()->get();
        $milestones = ProfileContent::byType('milestone')->active()->get();
        $visions = \App\Models\ProfileVisionMission::byType('visi')->active()->get();
        $missions = \App\Models\ProfileVisionMission::byType('misi')->active()->get();
        
        // Ambil jurusan dengan skema count
        $jurusanList = Jurusan::withCount('skemas')
            ->having('skemas_count', '>', 0)
            ->orderBy('skemas_count', 'desc')
            ->get()
            ->map(function ($jurusan) {
                // Icon mapping berdasarkan kode jurusan
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

        return view('asesi.profil', compact('sejarah', 'milestones', 'visions', 'missions', 'jurusanList'));
    }
}
