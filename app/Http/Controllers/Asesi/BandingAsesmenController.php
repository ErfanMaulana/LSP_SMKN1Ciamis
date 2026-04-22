<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Banding;
use App\Models\Skema;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BandingAsesmenController extends Controller
{
    /**
     * Get authenticated asesi
     */
    private function getAsesi(): ?Asesi
    {
        $account = Auth::guard('account')->user();
        return Asesi::where('NIK', $account->NIK)->first();
    }

    /**
     * Display list of banding asesmen for asesi
     */
    public function index()
    {
        $asesi = $this->getAsesi();

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        // Get all banding records for this asesi
        $bandings = Banding::with(['skema', 'asesor'])
            ->where('asesi_nik', $asesi->NIK)
            ->orderByDesc('diajukan_at')
            ->get();

        // Get available skemas that are completed but don't have banding yet
        $asesiSkemas = DB::table('asesi_skema as aks')
            ->join('skemas as s', 'aks.skema_id', '=', 's.id')
            ->where('aks.asesi_nik', $asesi->NIK)
            ->where('aks.status', 'selesai')
            ->whereNotExists(function ($query) use ($asesi) {
                $query->select(DB::raw(1))
                    ->from('bandings')
                    ->whereColumn('bandings.skema_id', 'aks.skema_id')
                    ->where('bandings.asesi_nik', $asesi->NIK)
                    ->where('bandings.status', '!=', 'rejected');
            })
            ->select('s.id', 's.nama_skema', 's.nomor_skema')
            ->get();

        return view('asesi.banding.index', compact('asesi', 'bandings', 'asesiSkemas'));
    }

    /**
     * Show form to create banding for specific schema
     */
    public function show($skemaId)
    {
        $asesi = $this->getAsesi();
        $skema = Skema::findOrFail($skemaId);

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        // Check if asesmen selesai untuk skema ini
        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();
        
        if (!$pivot || $pivot->status !== 'selesai') {
            return redirect()->route('asesi.banding.index')
                ->with('error', 'Asesmen untuk skema ini belum selesai.');
        }
        
        // Get nilai details untuk ditampilkan di form
        $nilaiDetails = DB::table('asesor_nilai_elemens')
            ->join('elemens as e', 'asesor_nilai_elemens.elemen_id', '=', 'e.id')
            ->join('units as u', 'e.unit_id', '=', 'u.id')
            ->where('asesor_nilai_elemens.asesi_nik', $asesi->NIK)
            ->where('u.skema_id', $skemaId)
            ->select([
                'e.id as elemen_id',
                'e.kode_elemen',
                'u.nama_unit',
                'e.nama_elemen',
                'asesor_nilai_elemens.status'
            ])
            ->orderBy('u.urutan')
            ->orderBy('e.id')
            ->get();
        
        // Get existing banding if any
        $banding = Banding::where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();
        
        // Calculate totals
        $totalElemen = $nilaiDetails->count();
        $totalK = $nilaiDetails->where('status', 'K')->count();
        $totalBK = $totalElemen - $totalK;

        return view('asesi.banding.show', compact('asesi', 'skema', 'banding', 'nilaiDetails', 'totalElemen', 'totalK', 'totalBK'));
    }

    /**
     * Store banding asesmen
     */
    public function store(Request $request, $skemaId)
    {
        $asesi = $this->getAsesi();
        $skema = Skema::findOrFail($skemaId);

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        // Validasi
        $request->validate([
            'alasan_banding' => 'required|string|min:10|max:1000',
        ], [
            'alasan_banding.required' => 'Alasan banding wajib diisi.',
            'alasan_banding.min' => 'Alasan banding minimal 10 karakter.',
            'alasan_banding.max' => 'Alasan banding maksimal 1000 karakter.',
        ]);
        
        // Check asesmen sudah selesai
        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();
        
        if (!$pivot || $pivot->status !== 'selesai') {
            return redirect()->route('asesi.banding.index')
                ->with('error', 'Asesmen tidak dalam status selesai.');
        }
        
        // Get nilai details
        $nilaiDetails = DB::table('asesor_nilai_elemens')
            ->join('elemens as e', 'asesor_nilai_elemens.elemen_id', '=', 'e.id')
            ->join('units as u', 'e.unit_id', '=', 'u.id')
            ->where('asesor_nilai_elemens.asesi_nik', $asesi->NIK)
            ->where('u.skema_id', $skemaId)
            ->select('asesor_nilai_elemens.status')
            ->get();
        
        $totalElemen = $nilaiDetails->count();
        $totalK = $nilaiDetails->where('status', 'K')->count();
        $totalBK = $totalElemen - $totalK;
        
        // Get asesor untuk skema ini
        $asesorId = DB::table('jadwal_peserta as jp')
            ->join('jadwal_ujikom as ju', 'jp.jadwal_id', '=', 'ju.id')
            ->leftJoin('jadwal_kelompok as jk', 'ju.id', '=', 'jk.jadwal_id')
            ->where('jp.asesi_nik', $asesi->NIK)
            ->where('ju.skema_id', $skemaId)
            ->value('ju.asesor_id');
        
        // Create or update banding
        $banding = Banding::updateOrCreate(
            ['asesi_nik' => $asesi->NIK, 'skema_id' => $skemaId],
            [
                'asesor_id' => $asesorId,
                'status' => 'pending',
                'alasan_banding' => $request->alasan_banding,
                'total_elemen' => $totalElemen,
                'total_k_sebelum' => $totalK,
                'total_bk_sebelum' => $totalBK,
                'diajukan_at' => now(),
            ]
        );
        
        ActivityLogger::logAsesi(
            $asesi->NIK,
            $asesi->nama,
            'Ajukan Banding Asesmen',
            "Asesi mengajukan banding untuk skema {$skema->nama_skema}. Alasan: " . substr($request->alasan_banding, 0, 50) . '...',
            $request
        );

        return redirect()->route('asesi.banding.index')
            ->with('success', 'Banding asesmen berhasil diajukan. Tunggu review dari asesor.');
    }

    /**
     * Decline/withdraw banding
     */
    public function decline(Request $request, $skemaId)
    {
        $asesi = $this->getAsesi();
        $skema = Skema::findOrFail($skemaId);

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        $banding = Banding::where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->firstOrFail();
        
        if ($banding->status !== 'pending') {
            return redirect()->route('asesi.banding.index')
                ->with('error', 'Hanya banding yang pending dapat dibatalkan.');
        }
        
        $banding->update([
            'status' => 'rejected',
            'catatan_asesor' => 'Dibatalkan oleh asesi pada ' . now()->format('d/m/Y H:i'),
            'direview_at' => now(),
        ]);
        
        ActivityLogger::logAsesi(
            $asesi->NIK,
            $asesi->nama,
            'Batalkan Banding Asesmen',
            "Asesi membatalkan banding untuk skema {$skema->nama_skema}",
            $request
        );

        return redirect()->route('asesi.banding.index')
            ->with('success', 'Banding asesmen berhasil dibatalkan.');
    }
}
