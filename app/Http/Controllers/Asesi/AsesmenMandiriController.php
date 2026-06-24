<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Skema;
use App\Models\JawabanElemen;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AsesmenMandiriController extends Controller
{
    private function isValidSignatureDataUri(?string $signature): bool
    {
        if (!$signature) {
            return false;
        }

        return (bool) preg_match('/^data:image\/png;base64,[A-Za-z0-9+\/=]+$/', $signature);
    }

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

        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();

        if (!$pivot) {
            return redirect()->route('asesi.asesmen-mandiri.index')
                ->with('error', 'Skema ini tidak terdaftar untuk akun Anda.');
        }

        // Make sure this schema belongs to asesi's jurusan
        if ($skema->jurusan_id !== null && $skema->jurusan_id != $asesi->ID_jurusan) {
            return redirect()->route('asesi.asesmen-mandiri.index')
                ->with('error', 'Skema ini tidak sesuai dengan jurusan Anda.');
        }

        // Build dynamic rules so every elemen in this skema must be answered completely.
        $elemenIds = $skema->units
            ->flatMap(function ($unit) {
                return $unit->elemens->pluck('id');
            })
            ->values();

        $isFinalSubmission = $request->has('submit_final');

        // Validate input
        $rules = [
            'jawaban' => 'required|array',
            'jawaban.*.status' => 'required|in:K,BK',
            'jawaban.*.bukti' => 'nullable|string|max:1000',
        ];

        foreach ($elemenIds as $elemenId) {
            $rules["jawaban.$elemenId.status"] = 'required|in:K,BK';

            if ($isFinalSubmission) {
                $rules["jawaban.$elemenId.bukti"] = ['required', 'string', 'max:1000', 'regex:/\\S/'];
            } else {
                $rules["jawaban.$elemenId.bukti"] = ['nullable', 'string', 'max:1000'];
            }
        }

        if ($isFinalSubmission && empty($pivot->tanda_tangan)) {
            $rules['tanda_tangan'] = 'required|string';
        }

        $request->validate($rules, [
            'tanda_tangan.required' => 'Tanda tangan wajib diisi sebelum menyelesaikan asesmen.',
            'jawaban.*.status.required' => 'Status K/BK pada setiap elemen wajib dipilih.',
            'jawaban.*.status.in' => 'Status elemen harus K atau BK.',
            'jawaban.*.bukti.required' => 'Bukti relevan pada setiap elemen wajib diisi.',
            'jawaban.*.bukti.regex' => 'Bukti relevan tidak boleh hanya berisi spasi.',
        ]);

        $providedTandaTangan = $request->input('tanda_tangan');

        if ($providedTandaTangan && !$this->isValidSignatureDataUri($providedTandaTangan)) {
            return redirect()->route('asesi.asesmen-mandiri.show', $skemaId)
                ->with('error', 'Format tanda tangan tidak valid.');
        }

        if (!$isFinalSubmission && $providedTandaTangan && $providedTandaTangan !== ($pivot->tanda_tangan ?? null)) {
            DB::table('asesi_skema')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skemaId)
                ->update([
                    'tanda_tangan' => $providedTandaTangan,
                    'tanggal_tanda_tangan' => now(),
                    'updated_at' => now(),
                ]);

            $pivot->tanda_tangan = $providedTandaTangan;
        }
        
        // Save answers
        foreach ($request->jawaban as $elemenId => $data) {
            JawabanElemen::updateOrCreate(
                [
                    'asesi_nik' => $asesi->NIK,
                    'elemen_id' => $elemenId,
                ],
                [
                    'status' => $data['status'],
                    'bukti' => isset($data['bukti']) ? trim($data['bukti']) : null,
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
        if ($isFinalSubmission && $totalElements === $answeredElements) {
            $tandaTangan = $providedTandaTangan ?: ($pivot->tanda_tangan ?? null);

            if (!$this->isValidSignatureDataUri($tandaTangan)) {
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
            
            return redirect()->route('asesi.asesmen-mandiri.index')
                ->with('success', 'Asesmen Mandiri untuk skema "' . $skema->nama_skema . '" berhasil diselesaikan!');
        }
        
        return redirect()->route('asesi.asesmen-mandiri.show', $skemaId)
            ->with('success', 'Jawaban berhasil disimpan!');
    }

    /**
     * Show ujikom result from asesor scoring (status only, no numeric score shown).
     */
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

        // Fetch all schemas registered for this candidate (via pivot asesi_skema)
        $asesiSkemas = DB::table('asesi_skema')
            ->join('skemas', 'asesi_skema.skema_id', '=', 'skemas.id')
            ->where('asesi_skema.asesi_nik', $asesi->NIK)
            ->select('skemas.*', 'asesi_skema.status as status_mandiri', 'asesi_skema.tanggal_selesai as tgl_selesai_mandiri')
            ->get();

        $hasilUjikom = [];

        foreach ($asesiSkemas as $skema) {
            // 1. Pendaftaran: completed since they are approved asesi
            $stepPendaftaran = [
                'name' => 'Pendaftaran & Verifikasi',
                'status' => 'completed',
                'label' => 'Terverifikasi',
                'description' => 'Berkas pendaftaran Anda telah diverifikasi oleh admin.'
            ];

            // 2. Asesmen Mandiri
            $isMandiriSelesai = ($skema->status_mandiri === 'selesai');
            $stepMandiri = [
                'name' => 'Asesmen Mandiri (FR.APL.02)',
                'status' => $isMandiriSelesai ? 'completed' : 'pending',
                'label' => $isMandiriSelesai ? 'Selesai' : 'Belum Selesai',
                'description' => $isMandiriSelesai 
                    ? 'Anda telah menyelesaikan pengisian form asesmen mandiri.'
                    : 'Silakan isi form asesmen mandiri terlebih dahulu.'
            ];

            // 3. Persetujuan Asesmen
            $useNik = \Illuminate\Support\Facades\Schema::hasColumn('persetujuan_asesmen', 'asesi_nik');
            $persetujuan = DB::table('persetujuan_asesmen')
                ->where('nomor_skema', $skema->nomor_skema)
                ->where(function($q) use ($asesi, $useNik) {
                    $q->where('nama_asesi', $asesi->nama);
                    if ($useNik) {
                        $q->orWhere('asesi_nik', $asesi->NIK);
                    }
                })
                ->first();

            $isPersetujuanSelesai = $persetujuan && !empty($persetujuan->ttd_asesi_nama) && !empty($persetujuan->ttd_asesor_nama);
            $stepPersetujuan = [
                'name' => 'Persetujuan Asesmen (FR.APL.03)',
                'status' => $isPersetujuanSelesai ? 'completed' : 'pending',
                'label' => $isPersetujuanSelesai ? 'Selesai & Ditandatangani' : 'Belum Ditandatangani',
                'description' => $isPersetujuanSelesai 
                    ? 'Persetujuan asesmen telah disepakati dan ditandatangani oleh Anda dan Asesor.'
                    : 'Harap periksa dan tandatangani dokumen persetujuan asesmen.'
            ];

            // 4. Jadwal Ujikom
            $jadwal = DB::table('jadwal_peserta')
                ->join('jadwal_ujikom', 'jadwal_ujikom.id', '=', 'jadwal_peserta.jadwal_id')
                ->where('jadwal_peserta.asesi_nik', $asesi->NIK)
                ->where('jadwal_ujikom.skema_id', $skema->id)
                ->select('jadwal_ujikom.*')
                ->first();

            $isJadwalSelesai = (bool)$jadwal;
            
            $jadwalDesc = 'Menunggu penjadwalan uji kompetensi dari admin/asesor.';
            if ($isJadwalSelesai) {
                $tukName = DB::table('tuk')->where('id', $jadwal->tuk_id)->value('nama_tuk') ?? 'TUK';
                $tglMulai = $jadwal->tanggal_mulai ? \Carbon\Carbon::parse($jadwal->tanggal_mulai)->locale('id')->isoFormat('D MMMM YYYY') : '-';
                $jadwalDesc = 'Jadwal Anda: ' . $tglMulai . ' di ' . $tukName;
            }

            $stepJadwal = [
                'name' => 'Jadwal Uji Kompetensi',
                'status' => $isJadwalSelesai ? 'completed' : 'pending',
                'label' => $isJadwalSelesai ? 'Sudah Dijadwalkan' : 'Belum Dijadwalkan',
                'description' => $jadwalDesc
            ];

            // 5. Penilaian / Ceklis Observasi
            $ceklis = DB::table('ceklis_observasi_aktivitas_praktiks')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skema->id)
                ->first();

            $isPenilaianSelesai = $ceklis && !empty($ceklis->ttd_asesi_file) && !empty($ceklis->ttd_asesor_file);
            
            $penilaianLabel = 'Belum Dinilai';
            $penilaianDesc = 'Menunggu penilaian observasi praktik dari Asesor.';
            if ($ceklis) {
                if ($isPenilaianSelesai) {
                    $penilaianLabel = 'Selesai & Ditandatangani';
                    $penilaianDesc = 'Proses observasi praktik telah dinilai dan disetujui kedua belah pihak.';
                } elseif (!empty($ceklis->ttd_asesor_file)) {
                    $penilaianLabel = 'Menunggu Tanda Tangan Anda';
                    $penilaianDesc = 'Asesor telah menilai. Harap masuk ke menu Ceklis Observasi untuk menandatanganinya.';
                }
            }

            $stepPenilaian = [
                'name' => 'Penilaian & Ceklis Observasi (FR.IA.01)',
                'status' => $isPenilaianSelesai ? 'completed' : 'pending',
                'label' => $penilaianLabel,
                'description' => $penilaianDesc
            ];

            // 6. Rekaman Asesmen (FR.AK.02)
            $rekaman = DB::table('rekaman_asesmen_kompetensi')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skema->id)
                ->first();

            $isRekamanSelesai = $rekaman && !empty($rekaman->ttd_asesi_file) && !empty($rekaman->ttd_asesor_file);

            $rekamanLabel = 'Belum Diisi';
            $rekamanDesc = 'Menunggu pengisian rekaman asesmen dari Asesor.';
            if ($rekaman) {
                if ($isRekamanSelesai) {
                    $rekamanLabel = 'Selesai & Ditandatangani';
                    $rekamanDesc = 'Rekaman asesmen kompetensi telah ditandatangani oleh kedua belah pihak.';
                } elseif (!empty($rekaman->ttd_asesor_file)) {
                    $rekamanLabel = 'Menunggu Tanda Tangan Anda';
                    $rekamanDesc = 'Asesor telah mengisi rekaman asesmen. Harap periksa dan tandatangan.';
                }
            }

            $stepRekaman = [
                'name' => 'Rekaman Asesmen (FR.AK.02)',
                'status' => $isRekamanSelesai ? 'completed' : 'pending',
                'label' => $rekamanLabel,
                'description' => $rekamanDesc
            ];

            // Check if all are completed
            $allCompleted = $isMandiriSelesai && $isPersetujuanSelesai && $isJadwalSelesai && $isPenilaianSelesai && $isRekamanSelesai;

            $hasilUjikom[] = (object)[
                'skema_id' => $skema->id,
                'nama_skema' => $skema->nama_skema,
                'nomor_skema' => $skema->nomor_skema,
                'steps' => [$stepPendaftaran, $stepMandiri, $stepPersetujuan, $stepJadwal, $stepPenilaian, $stepRekaman],
                'all_completed' => $allCompleted,
                'ceklis' => $ceklis,
                'rekaman' => $rekaman,
                'rekomendasi' => $ceklis ? $ceklis->rekomendasi : null,
                'tanggal_ceklis' => $ceklis && $ceklis->ttd_asesi_tanggal ? \Carbon\Carbon::parse($ceklis->ttd_asesi_tanggal)->locale('id')->isoFormat('D MMMM YYYY') : null,
                'asesor_nama' => $ceklis && $ceklis->ttd_asesor_nama ? $ceklis->ttd_asesor_nama : null,
            ];
        }

        $hasilUjikom = collect($hasilUjikom);

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

            /**
             * Generate PDF for completed asesmen mandiri result.
             */
            public function pdf($skemaId)
            {
                $account = Auth::guard('account')->user();
                $asesi = $this->getAsesi();

                if (!$asesi) {
                    return redirect()->route('asesi.asesmen-mandiri.index')
                        ->with('error', 'Data asesi tidak ditemukan.');
                }

                $skema = Skema::with(['units.elemens.kriteria'])->findOrFail($skemaId);

                $pivot = DB::table('asesi_skema')
                    ->where('asesi_nik', $asesi->NIK)
                    ->where('skema_id', $skemaId)
                    ->first();

                if (!$pivot || $pivot->status !== 'selesai') {
                    return redirect()->route('asesi.asesmen-mandiri.result', $skemaId)
                        ->with('error', 'PDF hanya dapat dibuat setelah asesmen mandiri selesai.');
                }

                $answers = JawabanElemen::where('asesi_nik', $asesi->NIK)
                    ->whereHas('elemen.unit', function ($q) use ($skemaId) {
                        $q->where('skema_id', $skemaId);
                    })
                    ->with('elemen.unit')
                    ->get()
                    ->keyBy('elemen_id');

                $logoPath = public_path('images/lsp.png');
                $logoUrl = file_exists($logoPath)
                    ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
                    : null;

                $pdf = Pdf::loadView('asesi.asesmen-mandiri.pdf-result', compact(
                    'account',
                    'asesi',
                    'skema',
                    'answers',
                    'pivot',
                    'logoUrl'
                ))->setPaper('a4', 'portrait');

                $fileName = 'FR_APL_03_' . ($asesi->NIK ?? 'asesi') . '_' . ($skema->nama_skema ?? 'hasil') . '.pdf';

                return $pdf->stream($fileName);
            }
}
