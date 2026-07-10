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
            'status' => 'required|in:ditinjau,diterima,ditolak,asesmen_ulang',
            'catatan_admin' => 'nullable|string|max:2000',
        ]);

        $admin = auth()->guard('admin')->user();

        $banding->update([
            'status' => $validated['status'],
            'catatan_admin' => trim((string) ($validated['catatan_admin'] ?? '')) ?: null,
            'checked_by' => $admin?->id,
            'checked_at' => now(),
        ]);

        return redirect()->route('admin.banding-asesmen.show', $banding->id)
            ->with('success', 'Status banding asesmen berhasil diperbarui.');
    }
}
