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

    public function downloadPdf(int $id)
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
        $rekomendasiAsesmen = DB::table('asesi_skema')
            ->where('asesi_nik', $banding->asesi_nik)
            ->where('skema_id', $banding->skema_id)
            ->value('rekomendasi');

        $pdf = Pdf::loadView('admin.banding-asesmen.pdf', compact('banding', 'komponen', 'jawabanMap', 'rekomendasiAsesmen'))
            ->setPaper('a4', 'portrait');

        $asesiNik = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $banding->asesi_nik) ?: 'asesi';
        $tanggal = now()->format('Ymd');

        return $pdf->download("FR_AK_04_Banding_Asesmen_{$asesiNik}_{$tanggal}.pdf");
    }

    public function review(Request $request, int $id)
    {
        $banding = BandingAsesmen::findOrFail($id);

        if ($banding->status === 'tidak_banding') {
            return redirect()->route('admin.banding-asesmen.show', $banding->id)
                ->with('error', 'Data ini ditetapkan asesi sebagai Tidak Banding dan tidak perlu proses verifikasi admin.');
        }

        $validated = $request->validate([
            'status' => 'required|in:ditinjau,diterima,ditolak',
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
