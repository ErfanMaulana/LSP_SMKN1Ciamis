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
        return Asesor::with('skemas')->where('no_met', $account->id)->first();
    }

    /**
     * Dashboard asesor
     */
    public function dashboard()
    {
        $account = Auth::guard('account')->user();
        $asesor  = $this->getAsesor();

        $skemaIds = $asesor ? $asesor->skemas->pluck('id')->toArray() : [];

        // Asesi yang terdaftar di skema asesor ini
        $totalAsesi = count($skemaIds)
            ? DB::table('asesi_skema')->whereIn('skema_id', $skemaIds)->count()
            : 0;

        $selesai = count($skemaIds)
            ? DB::table('asesi_skema')->whereIn('skema_id', $skemaIds)->where('status', 'selesai')->count()
            : 0;

        $sedang = count($skemaIds)
            ? DB::table('asesi_skema')->whereIn('skema_id', $skemaIds)->where('status', 'sedang_mengerjakan')->count()
            : 0;

        $belum = $totalAsesi - $selesai - $sedang;

        $stats = compact('totalAsesi', 'selesai', 'sedang', 'belum');

        // 5 asesi terakhir yang selesai
        $recentCompleted = [];
        if (count($skemaIds)) {
            $recentCompleted = DB::table('asesi_skema')
                ->whereIn('skema_id', $skemaIds)
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
        $skemaIds = $asesor ? $asesor->skemas->pluck('id')->toArray() : [];

        $query = DB::table('asesi_skema')
            ->when(count($skemaIds), fn($q) => $q->whereIn('skema_id', $skemaIds));

        if (!count($skemaIds)) {
            $data  = collect();
            $skema = null;
            return view('asesor.asesi.index', compact('account', 'asesor', 'data', 'skema'));
        }

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

        $skema = count($skemaIds) === 1 ? Skema::find($skemaIds[0]) : null;

        return view('asesor.asesi.index', compact('account', 'asesor', 'data', 'skema'));
    }

    /**
     * Review hasil asesmen mandiri seorang asesi
     */
    public function asesiReview($asesiNik)
    {
        $account = Auth::guard('account')->user();
        $asesor  = $this->getAsesor();
        $skemaIds = $asesor ? $asesor->skemas->pluck('id')->toArray() : [];

        $asesi = Asesi::where('NIK', $asesiNik)->firstOrFail();

        // Cari pivot di semua skema asesor
        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->when(count($skemaIds), fn($q) => $q->whereIn('skema_id', $skemaIds))
            ->first();

        abort_unless($pivot, 403, 'Asesi ini tidak terdaftar di skema Anda.');

        $skemaId = $pivot->skema_id;

        $skema = Skema::with(['units.elemens.kriteria'])->findOrFail($skemaId);

        $answers = JawabanElemen::where('asesi_nik', $asesiNik)
            ->whereHas('elemen.unit', fn ($q) => $q->where('skema_id', $skemaId))
            ->get()
            ->keyBy('elemen_id');

        $kCount  = $answers->where('status', 'K')->count();
        $bkCount = $answers->where('status', 'BK')->count();

        $savedSignature = $asesor->saved_tanda_tangan;

        return view('asesor.asesi.review', compact(
            'account', 'asesor', 'asesi', 'skema', 'answers', 'pivot', 'kCount', 'bkCount', 'savedSignature'
        ));
    }

    /**
     * Simpan rekomendasi asesor untuk asesmen mandiri asesi
     */
    public function recommend(Request $request, $asesiNik)
    {
        $account = Auth::guard('account')->user();
        $asesor  = $this->getAsesor();
        $skemaIds = $asesor ? $asesor->skemas->pluck('id')->toArray() : [];

        $request->validate([
            'rekomendasi'          => 'required|in:lanjut,tidak_lanjut',
            'catatan_asesor'       => 'nullable|string|max:1000',
            'tanda_tangan_asesor'  => 'required|string',
            'simpan_tanda_tangan'  => 'nullable|in:0,1',
        ], [
            'tanda_tangan_asesor.required' => 'Tanda tangan asesor wajib diisi sebelum menyimpan rekomendasi.',
        ]);

        // Validasi format base64 PNG
        if (!preg_match('/^data:image\/png;base64,[A-Za-z0-9+\/=]+$/', $request->tanda_tangan_asesor)) {
            return back()->withErrors(['tanda_tangan_asesor' => 'Format tanda tangan tidak valid.'])->withInput();
        }

        // Pastikan asesi ini memang di skema asesor
        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->when(count($skemaIds), fn($q) => $q->whereIn('skema_id', $skemaIds))
            ->first();

        abort_unless($pivot, 403, 'Asesi ini tidak terdaftar di skema Anda.');

        $updated = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->where('skema_id', $pivot->skema_id)
            ->update([
                'rekomendasi'                => $request->rekomendasi,
                'catatan_asesor'             => $request->catatan_asesor,
                'tanda_tangan_asesor'        => $request->tanda_tangan_asesor,
                'tanggal_tanda_tangan_asesor' => now(),
                'reviewed_at'                => now(),
                'reviewed_by'                => $account->id,
                'updated_at'                 => now(),
            ]);

        abort_unless($updated, 403, 'Asesi ini tidak terdaftar di skema Anda.');

        // Simpan tanda tangan ke profil asesor jika diminta
        if ($request->simpan_tanda_tangan === '1') {
            $asesor->update(['saved_tanda_tangan' => $request->tanda_tangan_asesor]);
        }

        $label = $request->rekomendasi === 'lanjut'
            ? 'Asesmen dapat dilanjutkan'
            : 'Asesmen tidak dapat dilanjutkan';

        return redirect()->route('asesor.asesi.review', $asesiNik)
            ->with('success', 'Rekomendasi berhasil disimpan: ' . $label . '.');
    }
}
