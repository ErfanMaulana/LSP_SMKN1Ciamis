<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Skema;
use App\Models\JawabanElemen;
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
        return Asesi::where('no_reg', $account->no_reg)->first();
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

        // Only load schemas that belong to asesi's jurusan
        $skemas = Skema::withCount('units')
            ->where('jurusan_id', $asesi->ID_jurusan)
            ->get();

        // If there is exactly one schema, go straight to the form
        if ($skemas->count() === 1) {
            return redirect()->route('asesi.asesmen-mandiri.show', $skemas->first()->id);
        }

        // Get asesi's selected schemas with pivot data
        $asesiSkemas = $asesi->skemas()->get()->keyBy('id');

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

        // Make sure this schema belongs to asesi's jurusan
        if ($skema->jurusan_id !== null && $skema->jurusan_id != $asesi->ID_jurusan) {
            return redirect()->route('asesi.asesmen-mandiri.index')
                ->with('error', 'Skema ini tidak sesuai dengan jurusan Anda.');
        }

        // Check if asesi has selected this schema, if not, create the relationship
        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();
        
        if (!$pivot) {
            // Create new relationship
            DB::table('asesi_skema')->insert([
                'asesi_nik' => $asesi->NIK,
                'skema_id' => $skemaId,
                'status' => 'sedang_mengerjakan',
                'tanggal_mulai' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($pivot->status === 'belum_mulai') {
            // Update status to sedang_mengerjakan
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
        
        return view('asesi.asesmen-mandiri.form', compact('account', 'asesi', 'skema', 'existingAnswers', 'pivot'));
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
        $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*.status' => 'required|in:K,BK',
            'jawaban.*.bukti' => 'nullable|string|max:1000',
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
            DB::table('asesi_skema')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skemaId)
                ->update([
                    'status' => 'selesai',
                    'tanggal_selesai' => now(),
                    'updated_at' => now(),
                ]);
            
            return redirect()->route('asesi.asesmen-mandiri.index')
                ->with('success', 'Asesmen Mandiri untuk skema "' . $skema->nama_skema . '" berhasil diselesaikan!');
        }
        
        return redirect()->route('asesi.asesmen-mandiri.show', $skemaId)
            ->with('success', 'Jawaban berhasil disimpan!');
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
