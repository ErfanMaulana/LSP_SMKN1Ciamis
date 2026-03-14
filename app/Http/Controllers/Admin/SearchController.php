<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Kelompok;
use App\Models\Skema;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = [];
        $user = auth()->guard('admin')->user();

        // Search Asesi
        if ($user->hasPermission('asesi.view')) {
            $asesis = Asesi::where('nama', 'like', "%{$query}%")
                ->orWhere('NIK', 'like', "%{$query}%")
                ->orWhere('no_reg', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            foreach ($asesis as $asesi) {
                $results[] = [
                    'title'    => $asesi->nama,
                    'subtitle' => 'NIK: ' . $asesi->NIK . ($asesi->no_reg ? ' · No. Reg: ' . $asesi->no_reg : ''),
                    'url'      => route('admin.asesi.index') . '?search=' . urlencode($asesi->nama),
                    'category' => 'Asesi',
                    'icon'     => 'bi-people',
                    'color'    => '#0061A5',
                ];
            }
        }

        // Search Asesor
        if ($user->hasPermission('asesor.view')) {
            $asesors = Asesor::where('nama', 'like', "%{$query}%")
                ->orWhere('no_met', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            foreach ($asesors as $asesor) {
                $results[] = [
                    'title'    => $asesor->nama,
                    'subtitle' => 'No. MET: ' . ($asesor->no_met ?? '-'),
                    'url'      => route('admin.asesor.index') . '?search=' . urlencode($asesor->nama),
                    'category' => 'Asesor',
                    'icon'     => 'bi-person-badge',
                    'color'    => '#7c3aed',
                ];
            }
        }

        // Search Kelompok
        if ($user->hasPermission('kelompok.view')) {
            $kelompoks = Kelompok::where('nama_kelompok', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            foreach ($kelompoks as $kelompok) {
                $results[] = [
                    'title'    => $kelompok->nama_kelompok,
                    'subtitle' => 'Kelompok' . ($kelompok->skema ? ' · ' . $kelompok->skema->nama_skema : ''),
                    'url'      => route('admin.kelompok.index') . '?search=' . urlencode($kelompok->nama_kelompok),
                    'category' => 'Kelompok',
                    'icon'     => 'bi-diagram-3-fill',
                    'color'    => '#059669',
                ];
            }
        }

        // Search Skema
        if ($user->hasPermission('skema.view')) {
            $skemas = Skema::where('nama_skema', 'like', "%{$query}%")
                ->orWhere('nomor_skema', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            foreach ($skemas as $skema) {
                $results[] = [
                    'title'    => $skema->nama_skema,
                    'subtitle' => $skema->nomor_skema ?? '-',
                    'url'      => route('admin.skema.index') . '?search=' . urlencode($skema->nama_skema),
                    'category' => 'Skema',
                    'icon'     => 'bi-patch-check',
                    'color'    => '#d97706',
                ];
            }
        }

        // Search Jurusan
        if ($user->hasPermission('jurusan.view')) {
            $jurusans = Jurusan::where('nama_jurusan', 'like', "%{$query}%")
                ->orWhere('kode_jurusan', 'like', "%{$query}%")
                ->limit(5)
                ->get();

            foreach ($jurusans as $jurusan) {
                $results[] = [
                    'title'    => $jurusan->nama_jurusan,
                    'subtitle' => 'Kode: ' . ($jurusan->kode_jurusan ?? '-'),
                    'url'      => route('admin.jurusan.show', $jurusan->ID_jurusan),
                    'category' => 'Jurusan',
                    'icon'     => 'bi-mortarboard',
                    'color'    => '#dc2626',
                ];
            }
        }

        return response()->json(['results' => $results]);
    }
}
