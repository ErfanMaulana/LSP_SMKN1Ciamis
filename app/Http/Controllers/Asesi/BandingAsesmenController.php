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

    public function index(Request $request)
    {
        $account = Auth::guard('account')->user();
        $asesi = $this->getAsesi();

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        $query = DB::table('asesi_skema as aks')
            ->join('skemas as s', 's.id', '=', 'aks.skema_id')
            ->leftJoin('banding_asesmen as b', function ($join) {
                $join->on('b.asesi_nik', '=', 'aks.asesi_nik')
                    ->on('b.skema_id', '=', 'aks.skema_id');
            })
            ->where('aks.asesi_nik', $asesi->NIK)
            ->whereNotNull('aks.rekomendasi')
            ->select([
                'aks.skema_id',
                'aks.rekomendasi',
                'aks.tanggal_selesai',
                's.nama_skema',
                's.nomor_skema',
                'b.id as banding_id',
                'b.status as banding_status',
                'b.tanggal_pengajuan',
                'b.checked_at',
            ]);

        $search = trim((string) $request->get('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('s.nama_skema', 'like', "%{$search}%")
                    ->orWhere('s.nomor_skema', 'like', "%{$search}%");
            });
        }

        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            if ($status === 'belum') {
                $query->whereNull('b.id');
            } else {
                $query->where('b.status', $status);
            }
        }

        $rows = $query->orderByDesc('aks.updated_at')->paginate(12)->withQueryString();

        $statsBase = DB::table('asesi_skema as aks')
            ->leftJoin('banding_asesmen as b', function ($join) {
                $join->on('b.asesi_nik', '=', 'aks.asesi_nik')
                    ->on('b.skema_id', '=', 'aks.skema_id');
            })
            ->where('aks.asesi_nik', $asesi->NIK)
            ->whereNotNull('aks.rekomendasi');

        $stats = [
            'total' => (clone $statsBase)->count(),
            'belum_memilih' => (clone $statsBase)->whereNull('b.id')->count(),
            'diajukan' => (clone $statsBase)->where('b.status', 'diajukan')->count(),
            'ditinjau' => (clone $statsBase)->where('b.status', 'ditinjau')->count(),
            'diterima' => (clone $statsBase)->where('b.status', 'diterima')->count(),
            'ditolak' => (clone $statsBase)->where('b.status', 'ditolak')->count(),
            'tidak_banding' => (clone $statsBase)->where('b.status', 'tidak_banding')->count(),
        ];

        return view('asesi.banding.index', compact('account', 'asesi', 'rows', 'stats', 'search', 'status'));
    }

    public function show(int $skemaId)
    {
        $account = Auth::guard('account')->user();
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
                ->with('error', 'Keputusan asesmen belum tersedia, sehingga banding belum bisa diajukan.');
        }

        $skema = Skema::findOrFail($skemaId);

        $komponen = BandingAsesmenKomponen::where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        if ($komponen->isEmpty()) {
            return redirect()->route('asesi.banding.index')
                ->with('error', 'Komponen ceklis banding belum tersedia. Hubungi admin.');
        }

        $banding = BandingAsesmen::with('jawaban')
            ->where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();

        $existingJawaban = $banding
            ? $banding->jawaban->keyBy('komponen_id')
            : collect();

        return view('asesi.banding.form', compact(
            'account',
            'asesi',
            'skema',
            'pivot',
            'komponen',
            'banding',
            'existingJawaban'
        ));
    }

    public function store(Request $request, int $skemaId)
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
                ->with('error', 'Keputusan asesmen belum tersedia, sehingga banding belum bisa diajukan.');
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
            'alasan_banding' => ['required', 'string', 'max:3000', 'regex:/\\S/'],
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

        if ($existing && in_array($existing->status, ['diterima', 'ditolak'], true)) {
            return back()->with('error', 'Banding sudah diproses dan tidak dapat diubah lagi.');
        }

        $asesorId = $asesi->ID_asesor;
        if (!$asesorId && !empty($pivot->reviewed_by)) {
            $asesorId = Asesor::where('no_met', (string) $pivot->reviewed_by)->value('ID_asesor');
        }

        DB::transaction(function () use ($asesi, $skemaId, $asesorId, $pivot, $validated, $komponenIds) {
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

        $existing = BandingAsesmen::where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();

        if ($existing && in_array($existing->status, ['diterima', 'ditolak'], true)) {
            return back()->with('error', 'Banding sudah diproses admin dan keputusan tidak dapat diubah lagi.');
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
                ]
            );

            BandingAsesmenJawaban::where('banding_id', $banding->id)->delete();
        });

        return redirect()->route('asesi.banding.index')
            ->with('success', 'Keputusan Tidak Banding berhasil disimpan. Anda dapat mengubahnya selama belum diproses final oleh admin.');
    }
}
