<?php

namespace App\Http\Controllers\Asesor;

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
    private function getAsesor(): ?Asesor
    {
        $account = Auth::guard('account')->user();

        return Asesor::with('skemas')->where('no_met', $account->id)->first();
    }

    public function index(Request $request)
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();

        if (!$asesor) {
            return view('asesor.banding.index', [
                'account' => $account,
                'asesor' => null,
                'rows' => collect(),
                'stats' => [
                    'total' => 0,
                    'diajukan' => 0,
                    'ditinjau' => 0,
                    'diterima' => 0,
                    'ditolak' => 0,
                    'tidak_banding' => 0,
                ],
            ]);
        }

        $skemaIds = $asesor->skemas->pluck('id')->all();

        $query = DB::table('asesi_skema as aks')
            ->join('asesi as a', 'a.NIK', '=', 'aks.asesi_nik')
            ->join('skemas as s', 's.id', '=', 'aks.skema_id')
            ->leftJoin('banding_asesmen as b', function ($join) {
                $join->on('b.asesi_nik', '=', 'aks.asesi_nik')
                    ->on('b.skema_id', '=', 'aks.skema_id');
            })
            ->whereIn('aks.skema_id', $skemaIds)
            ->whereNotNull('aks.rekomendasi')
            ->select([
                'aks.asesi_nik',
                'aks.skema_id',
                'aks.rekomendasi',
                'aks.tanggal_selesai',
                'a.nama as asesi_nama',
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
                $q->where('a.nama', 'like', "%{$search}%")
                    ->orWhere('a.NIK', 'like', "%{$search}%")
                    ->orWhere('s.nama_skema', 'like', "%{$search}%")
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
            ->whereIn('aks.skema_id', $skemaIds)
            ->whereNotNull('aks.rekomendasi');

        $stats = [
            'total' => (clone $statsBase)->count(),
            'diajukan' => (clone $statsBase)->where('b.status', 'diajukan')->count(),
            'ditinjau' => (clone $statsBase)->where('b.status', 'ditinjau')->count(),
            'diterima' => (clone $statsBase)->where('b.status', 'diterima')->count(),
            'ditolak' => (clone $statsBase)->where('b.status', 'ditolak')->count(),
            'tidak_banding' => (clone $statsBase)->where('b.status', 'tidak_banding')->count(),
        ];

        return view('asesor.banding.index', compact('account', 'asesor', 'rows', 'stats', 'search', 'status'));
    }

    public function form(string $asesiNik, int $skemaId)
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();

        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $isAssigned = DB::table('asesor_skema')
            ->where('asesor_id', $asesor->ID_asesor)
            ->where('skema_id', $skemaId)
            ->exists();

        abort_unless($isAssigned, 403, 'Skema ini tidak berada dalam penugasan Anda.');

        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->where('skema_id', $skemaId)
            ->first();

        abort_unless($pivot && !empty($pivot->rekomendasi), 404, 'Data asesi atau keputusan asesmen tidak ditemukan.');

        $asesi = Asesi::where('NIK', $asesiNik)->firstOrFail();
        $skema = Skema::findOrFail($skemaId);

        $komponen = BandingAsesmenKomponen::where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        if ($komponen->isEmpty()) {
            return redirect()->route('asesor.banding.index')
                ->with('error', 'Komponen ceklis banding belum tersedia. Hubungi admin.');
        }

        $banding = BandingAsesmen::with('jawaban')
            ->where('asesi_nik', $asesiNik)
            ->where('skema_id', $skemaId)
            ->first();

        if (!$banding) {
            return redirect()->route('asesor.banding.index')
                ->with('error', 'Banding belum diajukan oleh asesi.');
        }

        $existingJawaban = $banding
            ? $banding->jawaban->keyBy('komponen_id')
            : collect();

        return view('asesor.banding.form', compact(
            'account',
            'asesor',
            'asesi',
            'skema',
            'pivot',
            'komponen',
            'banding',
            'existingJawaban'
        ));
    }

    public function store(Request $request, string $asesiNik, int $skemaId)
    {
        return redirect()->route('asesor.banding.index')
            ->with('error', 'Pengajuan banding hanya dapat dilakukan oleh asesi.');

        $asesor = $this->getAsesor();

        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $isAssigned = DB::table('asesor_skema')
            ->where('asesor_id', $asesor->ID_asesor)
            ->where('skema_id', $skemaId)
            ->exists();

        abort_unless($isAssigned, 403, 'Skema ini tidak berada dalam penugasan Anda.');

        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->where('skema_id', $skemaId)
            ->first();

        abort_unless($pivot && !empty($pivot->rekomendasi), 404, 'Data asesi atau keputusan asesmen tidak ditemukan.');

        $komponenIds = BandingAsesmenKomponen::where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->pluck('id');

        if ($komponenIds->isEmpty()) {
            return redirect()->route('asesor.banding.index')
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

        $existing = BandingAsesmen::where('asesi_nik', $asesiNik)
            ->where('skema_id', $skemaId)
            ->first();

        if ($existing && in_array($existing->status, ['diterima', 'ditolak'], true)) {
            return back()->with('error', 'Banding sudah diperiksa admin dan tidak dapat diubah lagi.');
        }

        DB::transaction(function () use ($asesiNik, $skemaId, $asesor, $pivot, $validated, $komponenIds, $existing) {
            $banding = BandingAsesmen::updateOrCreate(
                [
                    'asesi_nik' => $asesiNik,
                    'skema_id' => $skemaId,
                ],
                [
                    'asesor_id' => $asesor->ID_asesor,
                    'tanggal_asesmen' => $pivot->tanggal_selesai ? Carbon::parse($pivot->tanggal_selesai)->toDateString() : now()->toDateString(),
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

            // Hapus jawaban dari komponen yang sudah tidak aktif/terhapus.
            if ($existing) {
                BandingAsesmenJawaban::where('banding_id', $banding->id)
                    ->whereNotIn('komponen_id', $komponenIds)
                    ->delete();
            }
        });

        return redirect()->route('asesor.banding.index')
            ->with('success', 'Banding asesmen berhasil diajukan dan menunggu pengecekan admin.');
    }
}
