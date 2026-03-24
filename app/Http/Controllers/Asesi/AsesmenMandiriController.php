<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Skema;
use App\Models\JawabanElemen;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsesmenMandiriController extends Controller
{
    /**
     * Get the current authenticated asesi
     */
    private function getAsesi()
    {
        $account = Auth::guard('account')->user();
        return Asesi::where('NIK', $account->NIK)->first();
    }

    /**
     * Display list of available schemas filtered by asesi's jurusan
     */
    public function index()
    {
        $account = Auth::guard('account')->user();
        $asesi = $this->getAsesi();

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        // Only load schemas that the asesi registered for (from pivot asesi_skema)
        $asesiSkemas = $asesi->skemas()->withCount('units')->get();
        $skemas      = $asesiSkemas;

        // If there is exactly one schema, go straight to the form
        if ($skemas->count() === 1) {
            return redirect()->route('asesi.asesmen-mandiri.show', $skemas->first()->id);
        }

        $asesiSkemas = $asesiSkemas->keyBy('id');

        return view('asesi.asesmen-mandiri.index', compact('account', 'asesi', 'skemas', 'asesiSkemas'));
    }

    /**
     * Start or continue asesmen mandiri for a schema
     */
    public function show($skemaId)
    {
        $account = Auth::guard('account')->user();
        $asesi = $this->getAsesi();
        
        if (!$asesi) {
            return redirect()->route('asesi.asesmen-mandiri.index')
                ->with('error', 'Data asesi tidak ditemukan.');
        }
        
        $skema = Skema::with(['units.elemens.kriteria'])->findOrFail($skemaId);

        // Make sure asesi is registered for this schema (via asesi_skema pivot)
        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();

        if (!$pivot) {
            return redirect()->route('asesi.asesmen-mandiri.index')
                ->with('error', 'Skema ini tidak terdaftar untuk akun Anda.');
        }

        // Update status to sedang_mengerjakan if still belum_mulai
        if ($pivot->status === 'belum_mulai') {
            DB::table('asesi_skema')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skemaId)
                ->update([
                    'status' => 'sedang_mengerjakan',
                    'tanggal_mulai' => now(),
                    'updated_at' => now(),
                ]);
        }
        
        // Get existing answers for this asesi and schema
        $existingAnswers = JawabanElemen::where('asesi_nik', $asesi->NIK)
            ->whereHas('elemen.unit', function($q) use ($skemaId) {
                $q->where('skema_id', $skemaId);
            })
            ->get()
            ->keyBy('elemen_id');

        // Re-fetch updated pivot to get rekomendasi data
        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();

        // Load asesor data if recommendation exists
        $asesorReviewer = null;
        if ($pivot && $pivot->reviewed_by) {
            $asesorReviewer = Asesor::where('no_met', $pivot->reviewed_by)->first();
        }
        
        return view('asesi.asesmen-mandiri.form', compact('account', 'asesi', 'skema', 'existingAnswers', 'pivot', 'asesorReviewer'));
    }

    /**
     * Store or update asesmen mandiri answers
     */
    public function store(Request $request, $skemaId)
    {
        $account = Auth::guard('account')->user();
        $asesi = $this->getAsesi();
        
        if (!$asesi) {
            return redirect()->route('asesi.asesmen-mandiri.index')
                ->with('error', 'Data asesi tidak ditemukan.');
        }
        
        $skema = Skema::with(['units.elemens'])->findOrFail($skemaId);

        // Make sure this schema belongs to asesi's jurusan
        if ($skema->jurusan_id !== null && $skema->jurusan_id != $asesi->ID_jurusan) {
            return redirect()->route('asesi.asesmen-mandiri.index')
                ->with('error', 'Skema ini tidak sesuai dengan jurusan Anda.');
        }

        // Validate input
        $rules = [
            'jawaban' => 'required|array',
            'jawaban.*.status' => 'required|in:K,BK',
            'jawaban.*.bukti' => 'nullable|string|max:1000',
        ];

        if ($request->has('submit_final')) {
            $rules['tanda_tangan'] = 'required|string';
        }

        $request->validate($rules, [
            'tanda_tangan.required' => 'Tanda tangan wajib diisi sebelum menyelesaikan asesmen.',
        ]);
        
        // Save answers
        foreach ($request->jawaban as $elemenId => $data) {
            JawabanElemen::updateOrCreate(
                [
                    'asesi_nik' => $asesi->NIK,
                    'elemen_id' => $elemenId,
                ],
                [
                    'status' => $data['status'],
                    'bukti' => $data['bukti'] ?? null,
                ]
            );
        }
        
        // Check if all elements are answered
        $totalElements = 0;
        foreach ($skema->units as $unit) {
            $totalElements += $unit->elemens->count();
        }
        
        $answeredElements = JawabanElemen::where('asesi_nik', $asesi->NIK)
            ->whereHas('elemen.unit', function($q) use ($skemaId) {
                $q->where('skema_id', $skemaId);
            })
            ->count();
        
        // If this is final submission
        if ($request->has('submit_final') && $totalElements === $answeredElements) {
            // Validate signature format (must be base64 PNG data URI)
            $tandaTangan = $request->input('tanda_tangan');
            if (!preg_match('/^data:image\/png;base64,[A-Za-z0-9+\/=]+$/', $tandaTangan)) {
                return redirect()->route('asesi.asesmen-mandiri.show', $skemaId)
                    ->with('error', 'Format tanda tangan tidak valid.');
            }

            DB::table('asesi_skema')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skemaId)
                ->update([
                    'status' => 'selesai',
                    'tanggal_selesai' => now(),
                    'tanda_tangan' => $tandaTangan,
                    'tanggal_tanda_tangan' => now(),
                    'updated_at' => now(),
                ]);

            ActivityLogger::logUser(
                (string) $asesi->NIK,
                $asesi->nama ?? (string) $asesi->NIK,
                'Mengisi APL 2',
                'User menyelesaikan dan submit final APL 2 untuk skema "' . $skema->nama_skema . '".',
                $request,
                ['skema_id' => (int) $skemaId, 'submit_type' => 'final']
            );
            
            return redirect()->route('asesi.asesmen-mandiri.index')
                ->with('success', 'Asesmen Mandiri untuk skema "' . $skema->nama_skema . '" berhasil diselesaikan!');
        }

        ActivityLogger::logUser(
            (string) $asesi->NIK,
            $asesi->nama ?? (string) $asesi->NIK,
            'Mengisi APL 2',
            'User menyimpan jawaban APL 2 untuk skema "' . $skema->nama_skema . '".',
            $request,
            ['skema_id' => (int) $skemaId, 'submit_type' => 'draft']
        );
        
        return redirect()->route('asesi.asesmen-mandiri.show', $skemaId)
            ->with('success', 'Jawaban berhasil disimpan!');
    }

    /**
     * Show ujikom result from asesor scoring (status only, no numeric score shown).
     */
    public function hasilUjikom()
    {
        $account = Auth::guard('account')->user();
        $asesi = $this->getAsesi();

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        $nilaiAgg = DB::table('asesor_nilai_elemens')
            ->selectRaw('asesi_nik, skema_id, COUNT(*) as total_elemen, SUM(CASE WHEN status = "K" THEN 1 ELSE 0 END) as total_k, MAX(updated_at) as terakhir_dinilai, MAX(asesor_id) as asesor_id')
            ->where('asesi_nik', $asesi->NIK)
            ->groupBy('asesi_nik', 'skema_id');

        $hasilUjikom = DB::table('asesi_skema as aks')
            ->join('skemas as s', 'aks.skema_id', '=', 's.id')
            ->leftJoinSub($nilaiAgg, 'nilai', function ($join) {
                $join->on('aks.asesi_nik', '=', 'nilai.asesi_nik')
                    ->on('aks.skema_id', '=', 'nilai.skema_id');
            })
            ->leftJoin('asesor as a', 'a.ID_asesor', '=', 'nilai.asesor_id')
            ->where('aks.asesi_nik', $asesi->NIK)
            ->where('aks.status', 'selesai')
            ->select([
                'aks.skema_id',
                'aks.status',
                'aks.tanggal_selesai',
                's.nama_skema',
                's.nomor_skema',
                'nilai.total_elemen',
                'nilai.total_k',
                'nilai.terakhir_dinilai',
                'a.nama as asesor_nama',
            ])
            ->orderByDesc('aks.tanggal_selesai')
            ->get()
            ->map(function ($row) {
                $hasPenilaian = $row->total_elemen !== null && (int) $row->total_elemen > 0;
                $row->status_penilaian = $hasPenilaian ? 'sudah_dinilai' : 'belum_dinilai';
                $row->hasil_ujikom = $hasPenilaian && (int) $row->total_k === (int) $row->total_elemen
                    ? 'kompeten'
                    : 'belum_kompeten';
                return $row;
            });

        return view('asesi.hasil-ujikom.index', compact('account', 'asesi', 'hasilUjikom'));
    }

    /**
     * View completed asesmen mandiri result
     */
    public function result($skemaId)
    {
        $account = Auth::guard('account')->user();
        $asesi = $this->getAsesi();
        
        if (!$asesi) {
            return redirect()->route('asesi.asesmen-mandiri.index')
                ->with('error', 'Data asesi tidak ditemukan.');
        }
        
        $skema = Skema::with(['units.elemens.kriteria'])->findOrFail($skemaId);
        
        // Get answers
        $answers = JawabanElemen::where('asesi_nik', $asesi->NIK)
            ->whereHas('elemen.unit', function($q) use ($skemaId) {
                $q->where('skema_id', $skemaId);
            })
            ->get()
            ->keyBy('elemen_id');
        
        // Get pivot data
        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();
        
        return view('asesi.asesmen-mandiri.result', compact('account', 'asesi', 'skema', 'answers', 'pivot'));
    }
}
