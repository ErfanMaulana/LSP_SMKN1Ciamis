<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;

class KompetensiController extends Controller
{
    /**
     * Menampilkan halaman list kompetensi
     */
    public function index()
    {
        $kompetensi = Jurusan::with(['asesi', 'skemas.units'])->get()->map(function ($jurusan) {
            // Hitung total unit kompetensi dari semua skema di jurusan ini
            $totalUnits = $jurusan->skemas->sum(function ($skema) {
                return $skema->units->count();
            });

            return [
                'id' => $jurusan->id_jurusan,
                'kode' => $jurusan->kode_jurusan,
                'slug' => strtolower($jurusan->kode_jurusan),
                'nama' => $jurusan->nama_jurusan,
                'unit_kompetensi' => $totalUnits,
                'jumlah_asesi' => $jurusan->asesi()->count(),
                'visi' => $jurusan->visi,
                'misi' => $jurusan->misi,
                'standar_kompetensi' => 'SKKNI Industri 4.0',
                'deskripsi' => 'Program kompetensi profesional SMKN 1 Ciamis'
            ];
        });

        return view('front.kompetensi-dan-data-skema.index', compact('kompetensi'));
    }

    /**
     * Menampilkan halaman detail kompetensi
     */
    public function detail($slug)
    {
        $jurusan = Jurusan::with(['skemas.units'])->where('kode_jurusan', strtoupper($slug))->first();

        if (!$jurusan) {
            abort(404, 'Kompetensi tidak ditemukan');
        }

        $jumlah_asesi = $jurusan->asesi()->count();
        
        // Hitung total unit kompetensi dari semua skema di jurusan ini
        $totalUnits = $jurusan->skemas->sum(function ($skema) {
            return $skema->units->count();
        });

        $data = [
            'id' => $jurusan->id_jurusan,
            'kode' => $jurusan->kode_jurusan,
            'slug' => strtolower($jurusan->kode_jurusan),
            'nama' => $jurusan->nama_jurusan,
            'unit_kompetensi' => $totalUnits,
            'jumlah_asesi' => $jumlah_asesi,
            'visi' => $jurusan->visi ?? '',
            'misi' => $this->parseMisi($jurusan->misi),
            'standar_kompetensi' => 'SKKNI Industri 4.0',
            'deskripsi' => 'Program kompetensi profesional SMKN 1 Ciamis'
        ];

        return view('front.kompetensi-dan-data-skema.detail', ['jurusan' => $data]);
    }

    /**
     * Parse misi dari string ke array
     */
    private function parseMisi($misiText)
    {
        if (!$misiText) {
            return [];
        }

        // Split by newline
        $misi = explode("\n", $misiText);
        $misi = array_map('trim', $misi);
        $misi = array_filter($misi, fn($m) => !empty($m));
        
        return array_values($misi);
    }
}
