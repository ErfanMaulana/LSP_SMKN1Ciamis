<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\BandingAsesmen;
use App\Models\BandingAsesmenJawaban;
use App\Models\BandingAsesmenKomponen;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BandingAsesmenController extends Controller
{
    private function getAsesi(): ?Asesi
    {
        $account = Auth::guard('account')->user();

        return Asesi::where('NIK', $account->NIK)->first();
    }

    private function formatSignatureUrl(?string $sig): ?string
    {
        if (empty($sig)) return null;
        $sig = trim($sig);
        if (str_contains($sig, '/storage/')) {
            return asset('storage/' . ltrim(explode('/storage/', $sig)[1], '/'));
        }
        if (str_starts_with($sig, 'http://') || str_starts_with($sig, 'https://')) {
            return $sig;
        }
        if (str_starts_with($sig, 'ceklis-observasi/') || str_starts_with($sig, 'persetujuan-asesmen/') || str_starts_with($sig, 'signatures/') || str_starts_with($sig, 'pendaftar/') || str_starts_with($sig, 'banding/')) {
            return asset('storage/' . ltrim($sig, '/'));
        }
        if (str_starts_with($sig, 'data:image')) {
            return preg_replace('/\s+/', '', $sig);
        }
        return 'data:image/png;base64,' . preg_replace('/\s+/', '', $sig);
    }

    public function index(Request $request)
    {
        $account = Auth::guard('account')->user();
        $asesi = $this->getAsesi();

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesi->NIK)
            ->whereNotNull('rekomendasi')
            ->orderByDesc('updated_at')
            ->first();

        if (!$pivot) {
            return redirect()->route('asesi.dashboard')
                ->with('warning', 'Keputusan asesmen belum tersedia, sehingga banding belum bisa diajukan.');
        }

        $skema = Skema::findOrFail($pivot->skema_id);

        // Fetch final assessment result
        $rekaman = \App\Models\RekamanAsesmenKompetensi::where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skema->id)
            ->first();

        if (!$rekaman || empty($rekaman->rekomendasi)) {
            return redirect()->route('asesi.dashboard')
                ->with('warning', 'Keputusan asesmen belum tersedia, sehingga banding belum bisa diajukan.');
        }

        $komponen = BandingAsesmenKomponen::where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        if ($komponen->isEmpty()) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Komponen ceklis banding belum tersedia. Hubungi admin.');
        }

        $banding = BandingAsesmen::with('jawaban')
            ->where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skema->id)
            ->first();

        $existingJawaban = $banding
            ? $banding->jawaban->keyBy('komponen_id')
            : collect();

        $isKompeten = ($rekaman->rekomendasi === 'kompeten');

        $savedSignature = null;
        if ($asesi) {
            $raw = $asesi->tanda_tangan_pendaftar ?? $asesi->tanda_tangan ?? null;
            $savedSignature = $this->formatSignatureUrl($raw);
        }

        return view('asesi.banding.form', compact(
            'account',
            'asesi',
            'skema',
            'pivot',
            'komponen',
            'banding',
            'existingJawaban',
            'isKompeten',
            'savedSignature'
        ));
    }

    public function show(int $skemaId)
    {
        return redirect()->route('asesi.banding.index');
    }

    public function store(Request $request, int $skemaId)
    {
        $asesi = $this->getAsesi();

        // === DEBUG LOGGING (hapus setelah masalah teridentifikasi) ===
        \Illuminate\Support\Facades\Log::info('BandingAsesmen store called', [
            'skema_id' => $skemaId,
            'has_ttd_asesi_file' => !empty($request->ttd_asesi_file),
            'ttd_asesi_file_length' => strlen((string) $request->ttd_asesi_file),
            'ttd_asesi_file_prefix' => substr((string) $request->ttd_asesi_file, 0, 30),
            'alasan_length' => strlen((string) $request->alasan_banding),
            'jawaban_keys' => array_keys((array) $request->jawaban),
        ]);
        // === END DEBUG ===

        if (!$asesi) {
            return redirect()->route('asesi.banding.index')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();

        if (!$pivot || empty($pivot->rekomendasi)) {
            return redirect()->route('asesi.banding.index')
                ->with('error', 'Keputusan asesmen belum tersedia, sehingga banding belum bisa diajukan.');
        }

        // Fetch final assessment result and ensure they are NOT kompeten
        $rekaman = \App\Models\RekamanAsesmenKompetensi::where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();

        if (!$rekaman || $rekaman->rekomendasi === 'kompeten') {
            return redirect()->route('asesi.banding.index')
                ->with('error', 'Banding hanya dapat diajukan oleh asesi yang dinyatakan belum kompeten.');
        }

        $komponenIds = BandingAsesmenKomponen::where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->pluck('id');

        if ($komponenIds->isEmpty()) {
            return redirect()->route('asesi.banding.index')
                ->with('error', 'Komponen ceklis banding belum tersedia. Hubungi admin.');
        }

        $rules = [
            'alasan_banding' => ['required', 'string', 'max:3000', 'regex:/\S/'],
            'ttd_asesi_file' => 'nullable|string',
            'ttd_asesi_nama' => 'nullable|string|max:255',
            'ttd_asesi_tanggal' => 'nullable|date',
        ];

        foreach ($komponenIds as $komponenId) {
            $rules["jawaban.$komponenId"] = 'required|in:ya,tidak';
        }

        $validated = $request->validate($rules, [
            'alasan_banding.required' => 'Alasan banding wajib diisi.',
            'alasan_banding.regex' => 'Alasan banding tidak boleh hanya berisi spasi.',
            'jawaban.*.required' => 'Semua ceklis wajib diisi.',
            'jawaban.*.in' => 'Nilai ceklis harus Ya atau Tidak.',
        ]);

        $existing = BandingAsesmen::where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();

        // Cek apakah ada tanda tangan: dari form baru ATAU dari data yang sudah tersimpan
        $incomingTtd = trim((string) $request->input('ttd_asesi_file', ''));
        $hasExistingTtd = $existing && !empty($existing->ttd_asesi_file);
        $hasNewTtd = !empty($incomingTtd);

        if (!$hasNewTtd && !$hasExistingTtd) {
            return back()
                ->withInput()
                ->withErrors(['ttd_asesi_file' => 'Tanda tangan asesi wajib diisi.']);
        }


        if ($existing && in_array($existing->status, ['diajukan', 'ditinjau', 'diterima', 'ditolak', 'asesmen_ulang'], true)) {
            return back()->with('error', 'Banding sudah diajukan/diproses dan tidak dapat diubah lagi.');
        }

        $ttdAsesiFile = $existing ? $existing->ttd_asesi_file : null;
        if (!empty($incomingTtd)) {
            if (strpos($incomingTtd, 'data:image') === 0) {
                try {
                    list($type, $signatureData) = explode(';', $incomingTtd);
                    list(, $signatureData) = explode(',', $signatureData);
                    $signatureData = base64_decode($signatureData);

                    $filename = 'signature_asesi_' . uniqid() . '_' . time() . '.png';
                    $path = 'banding/signatures';

                    \Illuminate\Support\Facades\Storage::disk('public')->put($path . '/' . $filename, $signatureData);
                    $ttdAsesiFile = $path . '/' . $filename;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to save banding ttd_asesi_file: ' . $e->getMessage());
                }
            } elseif (strpos($incomingTtd, '/storage/') !== false) {
                $ttdAsesiFile = ltrim(explode('/storage/', $incomingTtd)[1], '/');
            } elseif (strpos($incomingTtd, 'ceklis-observasi/') === 0 || strpos($incomingTtd, 'persetujuan-asesmen/') === 0 || strpos($incomingTtd, 'signatures/') === 0 || strpos($incomingTtd, 'pendaftar/') === 0 || strpos($incomingTtd, 'banding/') === 0) {
                $ttdAsesiFile = $incomingTtd;
            }
        }

        $buktiPendukung = $existing ? $existing->bukti_pendukung : null;

        $asesorId = $asesi->ID_asesor;
        if (!$asesorId && !empty($pivot->reviewed_by)) {
            $asesorId = Asesor::where('no_met', (string) $pivot->reviewed_by)->value('ID_asesor');
        }

        DB::transaction(function () use ($asesi, $skemaId, $asesorId, $pivot, $validated, $komponenIds, $request, $ttdAsesiFile, $buktiPendukung) {
            $banding = BandingAsesmen::updateOrCreate(
                [
                    'asesi_nik' => $asesi->NIK,
                    'skema_id' => $skemaId,
                ],
                [
                    'asesor_id' => $asesorId,
                    'tanggal_asesmen' => $pivot->tanggal_selesai
                        ? Carbon::parse($pivot->tanggal_selesai)->toDateString()
                        : now()->toDateString(),
                    'tanggal_pengajuan' => now()->toDateString(),
                    'alasan_banding' => trim((string) $validated['alasan_banding']),
                    'status' => 'diajukan',
                    'catatan_admin' => null,
                    'checked_by' => null,
                    'checked_at' => null,
                    'ttd_asesi_nama' => $request->input('ttd_asesi_nama') ?: $asesi->nama,
                    'ttd_asesi_tanggal' => $request->input('ttd_asesi_tanggal') ?: now()->toDateString(),
                    'ttd_asesi_file' => $ttdAsesiFile,
                    'bukti_pendukung' => $buktiPendukung,
                ]
            );

            foreach ($komponenIds as $komponenId) {
                BandingAsesmenJawaban::updateOrCreate(
                    [
                        'banding_id' => $banding->id,
                        'komponen_id' => $komponenId,
                    ],
                    [
                        'jawaban' => $validated['jawaban'][$komponenId],
                    ]
                );
            }

            BandingAsesmenJawaban::where('banding_id', $banding->id)
                ->whereNotIn('komponen_id', $komponenIds)
                ->delete();
        });

        return redirect()->route('asesi.banding.index')
            ->with('success', 'Pengajuan banding asesmen berhasil dikirim dan menunggu proses asesor/admin.');
    }

    public function decline(Request $request, int $skemaId)
    {
        $asesi = $this->getAsesi();

        if (!$asesi) {
            return redirect()->route('asesi.banding.index')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();

        if (!$pivot || empty($pivot->rekomendasi)) {
            return redirect()->route('asesi.banding.index')
                ->with('error', 'Keputusan asesmen belum tersedia, sehingga keputusan banding belum bisa disimpan.');
        }

        // Fetch final assessment result and ensure they are NOT kompeten
        $rekaman = \App\Models\RekamanAsesmenKompetensi::where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();

        if (!$rekaman || $rekaman->rekomendasi === 'kompeten') {
            return redirect()->route('asesi.banding.index')
                ->with('error', 'Keputusan tidak banding hanya dapat diajukan oleh asesi yang dinyatakan belum kompeten.');
        }

        $existing = BandingAsesmen::where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();

        if ($existing && in_array($existing->status, ['diajukan', 'ditinjau', 'diterima', 'ditolak', 'asesmen_ulang'], true)) {
            return back()->with('error', 'Banding sudah diajukan/diproses dan tidak dapat diubah lagi.');
        }

        $asesorId = $asesi->ID_asesor;
        if (!$asesorId && !empty($pivot->reviewed_by)) {
            $asesorId = Asesor::where('no_met', (string) $pivot->reviewed_by)->value('ID_asesor');
        }

        DB::transaction(function () use ($asesi, $skemaId, $asesorId, $pivot) {
            $banding = BandingAsesmen::updateOrCreate(
                [
                    'asesi_nik' => $asesi->NIK,
                    'skema_id' => $skemaId,
                ],
                [
                    'asesor_id' => $asesorId,
                    'tanggal_asesmen' => $pivot->tanggal_selesai
                        ? Carbon::parse($pivot->tanggal_selesai)->toDateString()
                        : now()->toDateString(),
                    'tanggal_pengajuan' => now()->toDateString(),
                    'alasan_banding' => 'Asesi memilih tidak mengajukan banding.',
                    'status' => 'tidak_banding',
                    'catatan_admin' => null,
                    'checked_by' => null,
                    'checked_at' => null,
                    'ttd_asesi_nama' => null,
                    'ttd_asesi_tanggal' => null,
                    'ttd_asesi_file' => null,
                ]
            );

            BandingAsesmenJawaban::where('banding_id', $banding->id)->delete();
        });

        return redirect()->route('asesi.banding.index')
            ->with('success', 'Keputusan Tidak Banding berhasil disimpan. Anda dapat mengubahnya selama belum diproses final oleh admin.');
    }
}
