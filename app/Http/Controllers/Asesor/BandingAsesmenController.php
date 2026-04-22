<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Banding;
use App\Models\Skema;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BandingAsesmenController extends Controller
{
    /**
     * Get authenticated asesor
     */
    private function getAsesor(): ?Asesor
    {
        $account = Auth::guard('account')->user();
        return Asesor::where('no_reg', $account->no_reg)->first();
    }

    /**
     * Display list of pending bandings for this asesor
     */
    public function index()
    {
        $asesor = $this->getAsesor();

        if (!$asesor) {
            return redirect()->route('asesor.dashboard')
                ->with('error', 'Data asesor tidak ditemukan.');
        }

        // Get bandings assigned to this asesor, grouped by status
        $bandings = Banding::with(['asesi', 'skema'])
            ->where('asesor_id', $asesor->ID_asesor)
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected', 'revised')")
            ->orderByDesc('diajukan_at')
            ->get()
            ->groupBy('status');

        return view('asesor.banding.index', compact('asesor', 'bandings'));
    }

    /**
     * Show banding form for asesor to review and process
     */
    public function form($asesiNik, $skemaId)
    {
        $asesor = $this->getAsesor();

        if (!$asesor) {
            return redirect()->route('asesor.dashboard')
                ->with('error', 'Data asesor tidak ditemukan.');
        }

        $asesi = Asesi::where('NIK', $asesiNik)->firstOrFail();
        $skema = Skema::findOrFail($skemaId);

        // Get banding record
        $banding = Banding::where('asesi_nik', $asesiNik)
            ->where('skema_id', $skemaId)
            ->firstOrFail();

        // Verify this banding is assigned to this asesor
        if ($banding->asesor_id !== $asesor->ID_asesor && $banding->status !== 'pending') {
            abort(403, 'Anda tidak berhak mengakses banding ini.');
        }

        // Get nilai details untuk konteks diskusi
        $nilaiDetails = DB::table('asesor_nilai_elemens')
            ->join('elemens as e', 'asesor_nilai_elemens.elemen_id', '=', 'e.id')
            ->join('units as u', 'e.unit_id', '=', 'u.id')
            ->where('asesor_nilai_elemens.asesi_nik', $asesiNik)
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

        return view('asesor.banding.form', compact('asesor', 'asesi', 'skema', 'banding', 'nilaiDetails'));
    }

    /**
     * Process banding (approve/reject/revise)
     */
    public function store(Request $request, $asesiNik, $skemaId)
    {
        $asesor = $this->getAsesor();

        if (!$asesor) {
            return redirect()->route('asesor.dashboard')
                ->with('error', 'Data asesor tidak ditemukan.');
        }

        $asesi = Asesi::where('NIK', $asesiNik)->firstOrFail();
        $skema = Skema::findOrFail($skemaId);

        // Get banding
        $banding = Banding::where('asesi_nik', $asesiNik)
            ->where('skema_id', $skemaId)
            ->firstOrFail();

        // Verify authorization
        if ($banding->asesor_id !== $asesor->ID_asesor) {
            abort(403, 'Anda tidak berhak memproses banding ini.');
        }

        // Validate
        $request->validate([
            'action' => 'required|in:approve,reject,revise',
            'catatan_asesor' => 'required|string|min:5|max:1000',
            'total_k_sesudah' => 'nullable|integer|min:0',
            'total_bk_sesudah' => 'nullable|integer|min:0',
        ]);

        $action = $request->action;
        $totalK = $request->total_k_sesudah;
        $totalBK = $request->total_bk_sesudah;

        // Process berdasarkan action
        $newStatus = match($action) {
            'approve' => 'approved',
            'reject' => 'rejected',
            'revise' => 'revised',
        };

        $banding->update([
            'status' => $newStatus,
            'catatan_asesor' => $request->catatan_asesor,
            'total_k_sesudah' => $totalK,
            'total_bk_sesudah' => $totalBK,
            'direview_at' => now(),
            'direview_oleh' => $asesor->no_reg,
        ]);

        // Jika approve/revise, update nilai asesi di database
        if ($action === 'approve' || $action === 'revise') {
            // Bisa tambah logic untuk update asesor_nilai_elemens jika diperlukan
            // Atau tandai bahwa nilai sudah di-review
        }

        ActivityLogger::logAsesor(
            $asesor->no_reg,
            $asesor->nama,
            'Proses Banding Asesmen',
            "Asesor {$action} banding untuk asesi {$asesi->nama} ({$asesiNik}) pada skema {$skema->nama_skema}",
            $request
        );

        $message = match($action) {
            'approve' => 'Banding disetujui. Nilai asesi telah diperbarui.',
            'reject' => 'Banding ditolak. Nilai asesi tetap sesuai penilaian awal.',
            'revise' => 'Nilai diperbaharui berdasarkan review banding.',
        };

        return redirect()->route('asesor.banding.index')
            ->with('success', $message);
    }
}
