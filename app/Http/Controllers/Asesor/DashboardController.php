<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Asesor;
use App\Models\Asesi;
use App\Models\Skema;
use App\Models\JawabanElemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Resolve asesor profile from the logged-in account
     */
    private function getAsesor()
    {
        $account = Auth::guard('account')->user();
        return Asesor::with('skema')->where('no_reg', $account->no_reg)->first();
    }

    /**
     * Dashboard asesor
     */
    public function dashboard()
    {
        $account = Auth::guard('account')->user();
        $asesor  = $this->getAsesor();

        $skemaId = $asesor?->ID_skema;

        // Asesi yang terdaftar di skema asesor ini
        $totalAsesi = $skemaId
            ? DB::table('asesi_skema')->where('skema_id', $skemaId)->count()
            : 0;

        $selesai = $skemaId
            ? DB::table('asesi_skema')->where('skema_id', $skemaId)->where('status', 'selesai')->count()
            : 0;

        $sedang = $skemaId
            ? DB::table('asesi_skema')->where('skema_id', $skemaId)->where('status', 'sedang_mengerjakan')->count()
            : 0;

        $belum = $totalAsesi - $selesai - $sedang;

        $stats = compact('totalAsesi', 'selesai', 'sedang', 'belum');

        // 5 asesi terakhir yang selesai
        $recentCompleted = [];
        if ($skemaId) {
            $recentCompleted = DB::table('asesi_skema')
                ->where('skema_id', $skemaId)
                ->where('status', 'selesai')
                ->orderByDesc('tanggal_selesai')
                ->limit(5)
                ->get()
                ->map(function ($row) {
                    $row->asesi = Asesi::where('NIK', $row->asesi_nik)->first();
                    return $row;
                });
        }

        return view('asesor.dashboard', compact('account', 'asesor', 'stats', 'recentCompleted'));
    }

    /**
     * Daftar asesi yang terdaftar di skema asesor
     */
    public function asesiIndex(Request $request)
    {
        $account = Auth::guard('account')->user();
        $asesor  = $this->getAsesor();
        $skemaId = $asesor?->ID_skema;

        $query = DB::table('asesi_skema')
            ->where('skema_id', $skemaId);

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rows = $query->orderByDesc('updated_at')->get();

        // Attach asesi data
        $data = $rows->map(function ($row) {
            $row->asesi = Asesi::where('NIK', $row->asesi_nik)->first();
            return $row;
        });

        $skema = $skemaId ? Skema::find($skemaId) : null;

        return view('asesor.asesi.index', compact('account', 'asesor', 'data', 'skema'));
    }

    /**
     * Review hasil asesmen mandiri seorang asesi
     */
    public function asesiReview($asesiNik)
    {
        $account = Auth::guard('account')->user();
        $asesor  = $this->getAsesor();
        $skemaId = $asesor?->ID_skema;

        $asesi = Asesi::where('NIK', $asesiNik)->firstOrFail();

        // Pastikan asesi ini terdaftar di skema asesor
        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->where('skema_id', $skemaId)
            ->first();

        abort_unless($pivot, 403, 'Asesi ini tidak terdaftar di skema Anda.');

        $skema = Skema::with(['units.elemens.kriteria'])->findOrFail($skemaId);

        $answers = JawabanElemen::where('asesi_nik', $asesiNik)
            ->whereHas('elemen.unit', fn ($q) => $q->where('skema_id', $skemaId))
            ->get()
            ->keyBy('elemen_id');

        $kCount  = $answers->where('status', 'K')->count();
        $bkCount = $answers->where('status', 'BK')->count();

        return view('asesor.asesi.review', compact(
            'account', 'asesor', 'asesi', 'skema', 'answers', 'pivot', 'kCount', 'bkCount'
        ));
    }

    /**
     * Simpan rekomendasi asesor untuk asesmen mandiri asesi
     */
    public function recommend(Request $request, $asesiNik)
    {
        $account = Auth::guard('account')->user();
        $asesor  = $this->getAsesor();
        $skemaId = $asesor?->ID_skema;

        $request->validate([
            'rekomendasi'    => 'required|in:lanjut,tidak_lanjut',
            'catatan_asesor' => 'nullable|string|max:1000',
        ]);

        // Pastikan asesi ini memang di skema asesor
        $updated = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->where('skema_id', $skemaId)
            ->update([
                'rekomendasi'    => $request->rekomendasi,
                'catatan_asesor' => $request->catatan_asesor,
                'reviewed_at'    => now(),
                'reviewed_by'    => $account->no_reg,
                'updated_at'     => now(),
            ]);

        abort_unless($updated, 403, 'Asesi ini tidak terdaftar di skema Anda.');

        $label = $request->rekomendasi === 'lanjut'
            ? 'Asesmen dapat dilanjutkan'
            : 'Asesmen tidak dapat dilanjutkan';

        return redirect()->route('asesor.asesi.review', $asesiNik)
            ->with('success', 'Rekomendasi berhasil disimpan: ' . $label . '.');
    }
}
