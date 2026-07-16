<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BandingAsesmen;
use App\Models\BandingAsesmenKomponen;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BandingAsesmenController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status', 'all');

        $query = BandingAsesmen::with(['asesi', 'skema', 'asesor', 'checker']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('asesi', function ($sq) use ($search) {
                    $sq->where('nama', 'like', "%{$search}%")
                        ->orWhere('NIK', 'like', "%{$search}%");
                })->orWhereHas('skema', function ($sq) use ($search) {
                    $sq->where('nama_skema', 'like', "%{$search}%")
                        ->orWhere('nomor_skema', 'like', "%{$search}%");
                });
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $data = $query->orderByDesc('updated_at')->paginate(12)->withQueryString();

        $stats = [
            'total' => BandingAsesmen::count(),
            'diajukan' => BandingAsesmen::where('status', 'diajukan')->count(),
            'ditinjau' => BandingAsesmen::where('status', 'ditinjau')->count(),
            'diterima' => BandingAsesmen::where('status', 'diterima')->count(),
            'ditolak' => BandingAsesmen::where('status', 'ditolak')->count(),
            'asesmen_ulang' => BandingAsesmen::where('status', 'asesmen_ulang')->count(),
            'tidak_banding' => BandingAsesmen::where('status', 'tidak_banding')->count(),
        ];

        return view('admin.banding-asesmen.index', compact('data', 'stats', 'search', 'status'));
    }

    public function show(int $id)
    {
        $banding = BandingAsesmen::with([
            'asesi.jurusan',
            'skema',
            'asesor',
            'checker',
            'jawaban.komponen',
        ])->findOrFail($id);

        $komponen = BandingAsesmenKomponen::orderBy('urutan')->orderBy('id')->get();
        $jawabanMap = $banding->jawaban->keyBy('komponen_id');

        return view('admin.banding-asesmen.show', compact('banding', 'komponen', 'jawabanMap'));
    }

    public function export(int $id)
    {
        $banding = BandingAsesmen::with([
            'asesi.jurusan',
            'skema',
            'asesor',
            'checker',
            'jawaban.komponen',
        ])->findOrFail($id);

        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $banding->asesi_nik)
            ->where('skema_id', $banding->skema_id)
            ->first();

        if (empty($banding->ttd_asesi_file)) {
            return redirect()->back()->with('error', 'Form FR.AK.04 belum dapat diexport karena asesi belum menandatangani pengajuan banding.');
        }

        $komponen = BandingAsesmenKomponen::where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $existingJawaban = $banding->jawaban->keyBy('komponen_id');

        $logoPath = public_path('images/lsp.png');
        $logoDataUri = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $ttdAsesiDataUri = null;
        if (!empty($banding->ttd_asesi_file)) {
            if (str_starts_with($banding->ttd_asesi_file, 'data:image')) {
                $ttdAsesiDataUri = $banding->ttd_asesi_file;
            } else {
                $filePath = storage_path('app/public/' . ltrim($banding->ttd_asesi_file, '/'));
                if (file_exists($filePath)) {
                    $mime = mime_content_type($filePath) ?: 'image/png';
                    $ttdAsesiDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($filePath));
                }
            }
        }

        $html = view('asesor.banding.export-docx', [
            'asesor' => $banding->asesor,
            'asesi' => $banding->asesi,
            'skema' => $banding->skema,
            'pivot' => $pivot,
            'komponen' => $komponen,
            'banding' => $banding,
            'existingJawaban' => $existingJawaban,
            'logoPath' => $logoPath,
            'logoDataUri' => $logoDataUri,
            'ttdAsesiDataUri' => $ttdAsesiDataUri,
        ])->render();

        $fileSkema = preg_replace('/[^A-Za-z0-9\-]+/', '-', (string) ($banding->skema?->nomor_skema ?? $banding->skema_id));
        $fileName = 'FR.AK.04-' . ($banding->asesi_nik ?? 'asesi') . '-' . trim($fileSkema, '-') . '.doc';

        return response($html, 200, [
            'Content-Type' => 'application/msword; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function review(Request $request, int $id)
    {
        $banding = BandingAsesmen::findOrFail($id);

        if ($banding->status === 'tidak_banding') {
            return redirect()->route('admin.banding-asesmen.show', $banding->id)
                ->with('error', 'Data ini ditetapkan asesi sebagai Tidak Banding dan tidak perlu proses verifikasi admin.');
        }

        $validated = $request->validate([
            'status' => 'required|in:diterima,ditolak,asesmen_ulang',
            'catatan_admin' => 'nullable|string|max:2000',
        ]);

        $admin = auth()->guard('admin')->user();

        DB::transaction(function () use ($banding, $validated, $admin) {
            $banding->update([
                'status' => $validated['status'],
                'catatan_admin' => trim((string) ($validated['catatan_admin'] ?? '')) ?: null,
                'checked_by' => $admin?->id,
                'checked_at' => now(),
            ]);

            if ($validated['status'] === 'asesmen_ulang') {
                // Determine new attempt
                $currentMaxAttempt = (int) DB::table('asesi_skema')
                    ->where('asesi_nik', $banding->asesi_nik)
                    ->where('skema_id', $banding->skema_id)
                    ->max('attempt');

                $newAttempt = $currentMaxAttempt + 1;

                // 0. Reset kelompok_id on asesi table so admin has to re-assign a kelompok
                DB::table('asesi')
                    ->where('NIK', $banding->asesi_nik)
                    ->update(['kelompok_id' => null]);

                // 1. Duplicate asesi_skema
                DB::table('asesi_skema')->insert([
                    'asesi_nik' => $banding->asesi_nik,
                    'skema_id' => $banding->skema_id,
                    'attempt' => $newAttempt,
                    'status' => 'belum_mulai',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 2. Duplicate persetujuan_asesmen
                $prevPersetujuan = \App\Models\PersetujuanAsesmen::where('asesi_nik', $banding->asesi_nik)
                    ->latest('id')
                    ->first();
                if ($prevPersetujuan) {
                    $newPersetujuan = $prevPersetujuan->replicate();
                    $newPersetujuan->attempt = $newAttempt;
                    $newPersetujuan->ttd_asesor_nama = null;
                    $newPersetujuan->ttd_asesor_tanggal = null;
                    $newPersetujuan->ttd_asesor_file = null;
                    $newPersetujuan->ttd_asesi_nama = null;
                    $newPersetujuan->ttd_asesi_tanggal = null;
                    $newPersetujuan->ttd_asesi_file = null;
                    
                    // Reset reviu checklist
                    $newPersetujuan->bukti_verifikasi_portofolio = false;
                    $newPersetujuan->bukti_reviu_produk = false;
                    $newPersetujuan->bukti_observasi_langsung = false;
                    $newPersetujuan->bukti_kegiatan_terstruktur = false;
                    $newPersetujuan->bukti_pertanyaan_lisan = false;
                    $newPersetujuan->bukti_pertanyaan_tertulis = false;
                    $newPersetujuan->bukti_pertanyaan_wawancara = false;
                    $newPersetujuan->bukti_lainnya = false;
                    $newPersetujuan->bukti_lainnya_keterangan = null;
                    
                    $newPersetujuan->save();
                }

                // 3. Duplicate ceklis_observasi_aktivitas_praktiks & details
                $prevCeklis = \App\Models\CeklisObservasiAktivitasPraktik::where('asesi_nik', $banding->asesi_nik)
                    ->where('skema_id', $banding->skema_id)
                    ->latest('id')
                    ->first();
                if ($prevCeklis) {
                    $newCeklis = $prevCeklis->replicate();
                    $newCeklis->attempt = $newAttempt;
                    $newCeklis->rekomendasi = 'belum_kompeten';
                    $newCeklis->belum_kompeten_kelompok_pekerjaan = null;
                    $newCeklis->belum_kompeten_unit = null;
                    $newCeklis->belum_kompeten_elemen = null;
                    $newCeklis->belum_kompeten_kuk = null;
                    $newCeklis->ttd_asesi_nama = null;
                    $newCeklis->ttd_asesi_tanggal = null;
                    $newCeklis->ttd_asesi_file = null;
                    $newCeklis->ttd_asesor_nama = null;
                    $newCeklis->ttd_asesor_no_reg = null;
                    $newCeklis->ttd_asesor_tanggal = null;
                    $newCeklis->ttd_asesor_file = null;
                    $newCeklis->save();

                    $prevDetails = \App\Models\CeklisObservasiAktivitasPraktikDetail::where('ceklis_observasi_id', $prevCeklis->id)->get();
                    foreach ($prevDetails as $detail) {
                        $newDetail = $detail->replicate();
                        $newDetail->ceklis_observasi_id = $newCeklis->id;
                        $newDetail->pencapaian = null;
                        $newDetail->penilaian_lanjut = null;
                        $newDetail->save();
                    }
                }

                // 4. Duplicate rekaman_asesmen_kompetensi & details
                $prevRekaman = \App\Models\RekamanAsesmenKompetensi::where('asesi_nik', $banding->asesi_nik)
                    ->where('skema_id', $banding->skema_id)
                    ->latest('id')
                    ->first();
                if ($prevRekaman) {
                    $newRekaman = $prevRekaman->replicate();
                    $newRekaman->attempt = $newAttempt;
                    $newRekaman->tanggal_mulai = null;
                    $newRekaman->tanggal_selesai = null;
                    $newRekaman->rekomendasi = 'belum_kompeten';
                    $newRekaman->tindak_lanjut = null;
                    $newRekaman->komentar_observasi = null;
                    $newRekaman->ttd_asesor_nama = null;
                    $newRekaman->ttd_asesor_no_reg = null;
                    $newRekaman->ttd_asesor_tanggal = null;
                    $newRekaman->ttd_asesor_file = null;
                    $newRekaman->ttd_asesi_nama = null;
                    $newRekaman->ttd_asesi_tanggal = null;
                    $newRekaman->ttd_asesi_file = null;
                    $newRekaman->save();

                    $prevRakDetails = \App\Models\RekamanAsesmenKompetensiDetail::where('rekaman_id', $prevRekaman->id)->get();
                    foreach ($prevRakDetails as $rakDetail) {
                        $newRakDetail = $rakDetail->replicate();
                        $newRakDetail->rekaman_id = $newRekaman->id;
                        $newRakDetail->observasi_demonstrasi = false;
                        $newRakDetail->portofolio = false;
                        $newRakDetail->pernyataan_pihak_ketiga = false;
                        $newRakDetail->pertanyaan_lisan = false;
                        $newRakDetail->pertanyaan_tertulis = false;
                        $newRakDetail->proyek_kerja = false;
                        $newRakDetail->lainnya = false;
                        $newRakDetail->save();
                    }
                }
            }
        });

        return redirect()->route('admin.banding-asesmen.show', $banding->id)
            ->with('success', 'Status banding asesmen berhasil diperbarui.');
    }
}
