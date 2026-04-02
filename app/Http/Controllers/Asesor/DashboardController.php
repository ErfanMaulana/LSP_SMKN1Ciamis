<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Asesor;
use App\Models\Asesi;
use App\Models\Skema;
use App\Models\Kelompok;
use App\Models\JadwalUjikom;
use App\Models\JawabanElemen;
use App\Models\AsesorNilaiElemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
     * Halaman profil asesor.
     */
    public function profil()
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();

        if (!$asesor) {
            return redirect()->route('asesor.dashboard')
                ->with('error', 'Profil asesor tidak ditemukan. Hubungi admin.');
        }

        return view('asesor.profil.index', compact('account', 'asesor'));
    }

    /**
     * Update foto profil asesor.
     */
    public function updateProfil(Request $request)
    {
        $asesor = $this->getAsesor();

        if (!$asesor) {
            return redirect()->route('asesor.dashboard')
                ->with('error', 'Profil asesor tidak ditemukan. Hubungi admin.');
        }

        $validated = $request->validate([
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'foto_profil_cropped' => 'nullable|string',
            'hapus_foto' => 'nullable|boolean',
        ]);

        if (($validated['hapus_foto'] ?? false) && $asesor->foto_profil) {
            Storage::disk('public')->delete($asesor->foto_profil);
            $asesor->update(['foto_profil' => null]);

            return redirect()->route('asesor.profil.index')
                ->with('success', 'Foto profil berhasil dihapus.');
        }

        if (!empty($validated['foto_profil_cropped'])) {
            if (!preg_match('/^data:image\/(jpeg|jpg|png|webp);base64,/', $validated['foto_profil_cropped'], $matches)) {
                return redirect()->route('asesor.profil.index')
                    ->withErrors(['foto_profil' => 'Format hasil crop tidak valid.'])->withInput();
            }

            $rawBase64 = substr($validated['foto_profil_cropped'], strpos($validated['foto_profil_cropped'], ',') + 1);
            $binaryImage = base64_decode($rawBase64, true);

            if ($binaryImage === false) {
                return redirect()->route('asesor.profil.index')
                    ->withErrors(['foto_profil' => 'Data gambar hasil crop tidak valid.'])->withInput();
            }

            $extension = strtolower($matches[1]);
            if ($extension === 'jpeg') {
                $extension = 'jpg';
            }

            if ($asesor->foto_profil) {
                Storage::disk('public')->delete($asesor->foto_profil);
            }

            $path = 'asesor/profile/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->put($path, $binaryImage);

            $asesor->update(['foto_profil' => $path]);

            return redirect()->route('asesor.profil.index')
                ->with('success', 'Foto profil berhasil diperbarui.');
        }

        if ($request->hasFile('foto_profil')) {
            if ($asesor->foto_profil) {
                Storage::disk('public')->delete($asesor->foto_profil);
            }

            $path = $request->file('foto_profil')->store('asesor/profile', 'public');
            $asesor->update(['foto_profil' => $path]);

            return redirect()->route('asesor.profil.index')
                ->with('success', 'Foto profil berhasil diperbarui.');
        }

        return redirect()->route('asesor.profil.index')
            ->with('error', 'Pilih foto profil terlebih dahulu atau gunakan opsi hapus foto.');
    }

    /**
     * Form ubah password asesor.
     */
    public function passwordForm()
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();

        return view('asesor.profil.password', compact('account', 'asesor'));
    }

    /**
     * Simpan password baru asesor.
     */
    public function updatePassword(Request $request)
    {
        $account = Auth::guard('account')->user();

        $validated = $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:8|confirmed|different:password_lama',
        ], [
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password_baru.different' => 'Password baru harus berbeda dari password lama.',
        ]);

        if (!Hash::check($validated['password_lama'], $account->password)) {
            return back()->withErrors(['password_lama' => 'Password lama tidak sesuai.'])->withInput();
        }

        DB::table('accounts')
            ->where('id', $account->id)
            ->update([
                'password' => Hash::make($validated['password_baru']),
                'updated_at' => now(),
            ]);

        return redirect()->route('asesor.password.edit')
            ->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Jadwal ujikom yang ditugaskan ke asesor login.
     */
    public function jadwalIndex(Request $request)
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();

        if (!$asesor) {
            $jadwals = collect();
            return view('asesor.jadwal.index', compact('account', 'asesor', 'jadwals'));
        }

        $jadwals = JadwalUjikom::with(['tuk', 'skema', 'kelompok', 'kelompoks'])
            ->where('asesor_id', $asesor->ID_asesor)
            ->when($request->filled('status') && $request->status !== 'all', function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->orderByDesc('tanggal_mulai')
            ->orderBy('waktu_mulai')
            ->get();

        return view('asesor.jadwal.index', compact('account', 'asesor', 'jadwals'));
    }

    /**
     * Kelompok yang diampu asesor login.
     */
    public function kelompokIndex()
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();

        if (!$asesor) {
            $kelompoks = collect();
            return view('asesor.kelompok.index', compact('account', 'asesor', 'kelompoks'));
        }

        $kelompoks = Kelompok::with(['skema', 'asesis.jurusan'])
            ->whereHas('asesors', function ($q) use ($asesor) {
                $q->where('asesor.ID_asesor', $asesor->ID_asesor);
            })
            ->orderBy('nama_kelompok')
            ->get();

        return view('asesor.kelompok.index', compact('account', 'asesor', 'kelompoks'));
    }

    /**
     * Detail kelompok yang diampu asesor login.
     */
    public function kelompokShow($id)
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();

        if (!$asesor) {
            abort(403, 'Profil asesor tidak ditemukan.');
        }

        $kelompok = Kelompok::with(['skema', 'asesis.jurusan', 'asesors'])
            ->where('id', $id)
            ->whereHas('asesors', function ($q) use ($asesor) {
                $q->where('asesor.ID_asesor', $asesor->ID_asesor);
            })
            ->firstOrFail();

        return view('asesor.kelompok.show', compact('account', 'asesor', 'kelompok'));
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
     * Halaman utama Entry Penilaian: menampilkan asesi yang sudah dinilai asesor.
     */
    public function entryPenilaianIndex()
    {
        $account = Auth::guard('account')->user();
        $asesor  = $this->getAsesor();
        $skemaIds = $asesor ? $asesor->skemas->pluck('id')->toArray() : [];

        if (!count($skemaIds) || !$asesor) {
            $asesiData = collect();
            $sudahDinilai = collect();
            $belumDinilai = collect();
            return view('asesor.penilaian.index', compact('account', 'asesor', 'asesiData', 'sudahDinilai', 'belumDinilai'));
        }

        // Ambil semua asesi dengan status selesai di skema asesor
        $asesiData = DB::table('asesi_skema as aks')
            ->leftJoinSub(
                DB::table('asesor_nilai_elemens')
                    ->where('asesor_id', $asesor->ID_asesor)
                    ->selectRaw('asesi_nik, skema_id, COUNT(*) as total_elemen, AVG(nilai) as rata_rata, SUM(CASE WHEN status = "K" THEN 1 ELSE 0 END) as total_k, MAX(updated_at) as terakhir_dinilai')
                    ->groupBy('asesi_nik', 'skema_id'),
                'nilai',
                function ($join) {
                    $join->on('aks.asesi_nik', '=', 'nilai.asesi_nik')
                         ->on('aks.skema_id', '=', 'nilai.skema_id');
                }
            )
            ->select([
                'aks.asesi_nik',
                'aks.skema_id',
                'aks.status',
                'aks.rekomendasi',
                'aks.tanggal_selesai',
                'aks.updated_at',
                'nilai.total_elemen',
                'nilai.rata_rata',
                'nilai.total_k',
                'nilai.terakhir_dinilai',
            ])
            ->whereIn('aks.skema_id', $skemaIds)
            ->where('aks.status', 'selesai')
            ->orderByDesc('aks.tanggal_selesai')
            ->get()
            ->map(function ($row) use ($asesor) {
                $row->asesi = Asesi::where('NIK', $row->asesi_nik)->first();
                $row->skema = Skema::find($row->skema_id);
                $row->sudah_dinilai = $row->total_elemen !== null;
                return $row;
            });

        // Pisahkan data yang sudah dinilai dan belum dinilai
        $sudahDinilai = $asesiData->filter(fn($item) => $item->sudah_dinilai);
        $belumDinilai = $asesiData->filter(fn($item) => !$item->sudah_dinilai);

        return view('asesor.penilaian.index', compact('account', 'asesor', 'asesiData', 'sudahDinilai', 'belumDinilai'));
    }

    /**
     * Tombol "Isi Nilai Asesi": cari kandidat lalu redirect ke form input nilai per elemen.
     */
    public function entryPenilaianCreate()
    {
        $asesor = $this->getAsesor();
        $skemaIds = $asesor ? $asesor->skemas->pluck('id')->toArray() : [];

        if (!count($skemaIds) || !$asesor) {
            return redirect()->route('asesor.entry-penilaian')
                ->with('error', 'Skema asesor belum ditetapkan.');
        }

        $target = DB::table('asesi_skema as aks')
            ->whereIn('aks.skema_id', $skemaIds)
            ->where('aks.status', 'selesai')
            ->orderByRaw("CASE WHEN aks.rekomendasi IS NULL OR aks.rekomendasi = '' THEN 0 ELSE 1 END")
            ->orderByDesc('aks.tanggal_selesai')
            ->first();

        if (!$target) {
            return redirect()->route('asesor.entry-penilaian')
                ->with('error', 'Belum ada asesi dengan status selesai untuk diisi nilainya.');
        }

        return redirect()->route('asesor.entry-penilaian.form', $target->asesi_nik);
    }

    /**
     * Form input nilai asesor per elemen.
     */
    public function entryPenilaianForm($asesiNik)
    {
        $account = Auth::guard('account')->user();
        $asesor  = $this->getAsesor();
        $skemaIds = $asesor ? $asesor->skemas->pluck('id')->toArray() : [];

        $asesi = Asesi::where('NIK', $asesiNik)->firstOrFail();

        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->when(count($skemaIds), fn($q) => $q->whereIn('skema_id', $skemaIds))
            ->first();

        abort_unless((bool) $pivot, 403, 'Asesi ini tidak terdaftar di skema Anda.');

        $skema = Skema::with(['units.elemens'])->findOrFail($pivot->skema_id);
        $elemenIds = $skema->units->flatMap(fn($unit) => $unit->elemens->pluck('id'))->values()->all();

        $existingNilai = AsesorNilaiElemen::query()
            ->where('asesor_id', $asesor?->ID_asesor)
            ->where('asesi_nik', $asesiNik)
            ->where('skema_id', $pivot->skema_id)
            ->whereIn('elemen_id', $elemenIds)
            ->get()
            ->keyBy('elemen_id');

        return view('asesor.penilaian.form', compact('account', 'asesor', 'asesi', 'skema', 'pivot', 'existingNilai'));
    }

    /**
     * Simpan nilai asesor per elemen (angka) dan status K/BK.
     */
    public function entryPenilaianStore(Request $request, $asesiNik)
    {
        $asesor  = $this->getAsesor();
        $skemaIds = $asesor ? $asesor->skemas->pluck('id')->toArray() : [];

        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->when(count($skemaIds), fn($q) => $q->whereIn('skema_id', $skemaIds))
            ->first();

        abort_unless((bool) $pivot, 403, 'Asesi ini tidak terdaftar di skema Anda.');

        $skema = Skema::with(['units.elemens'])->findOrFail($pivot->skema_id);
        $elemens = $skema->units->flatMap(fn($unit) => $unit->elemens)->values();

        $request->validate([
            'nilai' => 'required|array',
            'nilai.*' => 'required|numeric|min:0|max:100',
        ], [
            'nilai.required' => 'Nilai per elemen wajib diisi.',
            'nilai.*.required' => 'Semua elemen harus memiliki nilai.',
            'nilai.*.numeric' => 'Nilai harus berupa angka.',
            'nilai.*.min' => 'Nilai minimal 0.',
            'nilai.*.max' => 'Nilai maksimal 100.',
        ]);

        foreach ($elemens as $elemen) {
            $nilai = (float) $request->input('nilai.' . $elemen->id, 0);
            $status = $nilai >= 75 ? 'K' : 'BK';

            AsesorNilaiElemen::updateOrCreate(
                [
                    'asesi_nik' => $asesiNik,
                    'skema_id'  => $pivot->skema_id,
                    'elemen_id' => $elemen->id,
                    'asesor_id' => $asesor?->ID_asesor,
                ],
                [
                    'nilai'  => (int) round($nilai),
                    'status' => $status,
                ]
            );
        }

        return redirect()->route('asesor.entry-penilaian.form', $asesiNik)
            ->with('success', 'Nilai per elemen berhasil disimpan.');
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

        abort_unless((bool) $pivot, 403, 'Asesi ini tidak terdaftar di skema Anda.');

        $skemaId = $pivot->skema_id;

        $skema = Skema::with(['units.elemens.kriteria'])->findOrFail($skemaId);

        $answers = JawabanElemen::where('asesi_nik', $asesiNik)
            ->whereHas('elemen.unit', fn ($q) => $q->where('skema_id', $skemaId))
            ->get()
            ->keyBy('elemen_id');

        $kCount  = $answers->where('status', 'K')->count();
        $bkCount = $answers->where('status', 'BK')->count();

        $savedSignature = $asesor ? $asesor->saved_tanda_tangan : null;

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

        abort_unless((bool) $pivot, 403, 'Asesi ini tidak terdaftar di skema Anda.');

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
