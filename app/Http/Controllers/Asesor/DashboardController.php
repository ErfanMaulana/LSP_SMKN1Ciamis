<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Asesor;
use App\Models\Asesi;
use App\Models\Kelas;
use App\Models\Skema;
use App\Models\Kelompok;
use App\Models\JadwalUjikom;
use App\Models\JawabanElemen;
use App\Models\AsesorNilaiElemen;
use App\Models\PersetujuanAsesmen;
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

        $selesai = 0;
        $sedang = 0;
        $belum = 0;

        if (count($skemaIds)) {
            $rows = DB::table('asesi_skema')->whereIn('skema_id', $skemaIds)->get();
            $niks = $rows->pluck('asesi_nik')->unique()->values();

            $rekamanByAsesiSkema = DB::table('rekaman_asesmen_kompetensi')
                ->where('asesor_id', $asesor->ID_asesor)
                ->select(DB::raw("CONCAT(asesi_nik, '|', skema_id) as `asset_key`"), 'id')
                ->get()
                ->pluck('id', 'asset_key');
            
            $asesmenByAsesiSkema = DB::table('jawaban_elemens as je')
                ->join('elemens as e', 'je.elemen_id', '=', 'e.id')
                ->join('units as u', 'e.unit_id', '=', 'u.id')
                ->whereIn('je.asesi_nik', $niks)
                ->whereIn('u.skema_id', $skemaIds)
                ->select(DB::raw("CONCAT(je.asesi_nik, '|', u.skema_id) as `asset_key`"), 'je.id')
                ->get()
                ->pluck('id', 'asset_key');
            
            $penilaianByAsesiSkema = DB::table('asesor_nilai_elemens')
                ->where('asesor_id', $asesor->ID_asesor)
                ->whereIn('asesi_nik', $niks)
                ->select(DB::raw("CONCAT(asesi_nik, '|', skema_id) as `asset_key`"), 'id')
                ->get()
                ->pluck('id', 'asset_key');

            $ceklisByAsesiSkema = DB::table('ceklis_observasi_aktivitas_praktiks')
                ->where('asesor_id', $asesor->ID_asesor)
                ->whereIn('asesi_nik', $niks)
                ->whereIn('skema_id', $skemaIds)
                ->select(
                    DB::raw("MAX(id) as id"),
                    DB::raw("CONCAT(asesi_nik, '|', skema_id) as `asset_key`")
                )
                ->groupBy('asesi_nik', 'skema_id')
                ->get()
                ->pluck('id', 'asset_key');

            $skemas = Skema::whereIn('id', $skemaIds)->get(['id', 'nomor_skema'])->keyBy('id');
            $nomorSkemas = $skemas->pluck('nomor_skema')->filter()->unique()->values();
            
            $persetujuans = DB::table('persetujuan_asesmen')
                ->whereIn('nomor_skema', $nomorSkemas)
                ->get()
                ->groupBy(function($item) {
                    return $item->nomor_skema . '|' . ($item->asesi_nik ?: $item->nama_asesi);
                });

            foreach ($rows as $row) {
                $row->asesi = Asesi::where('NIK', $row->asesi_nik)->first();
                $row->skema = $skemas->get($row->skema_id);
                
                $key = "{$row->asesi_nik}|{$row->skema_id}";
                $has_rekaman = isset($rekamanByAsesiSkema[$key]);
                $has_asesmen_mandiri = isset($asesmenByAsesiSkema[$key]);
                $has_penilaian = isset($penilaianByAsesiSkema[$key]);
                $has_ceklis = isset($ceklisByAsesiSkema[$key]);

                $persetujuanSignedByAsesor = false;
                $persetujuanSignedByAsesi = false;
                if ($row->skema) {
                    $pList = $persetujuans->get($row->skema->nomor_skema . '|' . $row->asesi_nik) 
                        ?? ($row->asesi ? $persetujuans->get($row->skema->nomor_skema . '|' . $row->asesi->nama) : null);
                    
                    $p = $pList ? $pList->sortByDesc('id')->first() : null;
                    if ($p) {
                        $persetujuanSignedByAsesor = !empty($p->ttd_asesor_nama);
                        $persetujuanSignedByAsesi = !empty($p->ttd_asesi_nama);
                    }
                }
                
                $persetujuanFullySigned = $persetujuanSignedByAsesor && $persetujuanSignedByAsesi;
                $rekomendasi = $row->rekomendasi ?? '';

                if (!$has_asesmen_mandiri) {
                    $belum++;
                } elseif (empty($rekomendasi)) {
                    $sedang++;
                } elseif ($rekomendasi === 'tidak_lanjut') {
                    $belum++;
                } elseif (!$persetujuanFullySigned) {
                    $sedang++;
                } elseif (!$has_ceklis) {
                    $sedang++;
                } elseif (!$has_rekaman) {
                    $sedang++;
                } elseif (!$has_penilaian) {
                    $sedang++;
                } else {
                    $selesai++;
                }
            }
        }

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
        return redirect()->to(route('asesor.profil.index') . '#password-form');
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

        return redirect()->to(route('asesor.profil.index') . '#password-form')
            ->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Simpan tanda tangan asesor (base64 data URL) ke profil.
     */
    public function saveSignature(Request $request)
    {
        $request->validate(['tanda_tangan' => ['required', 'string']]);

        $data = $request->input('tanda_tangan');

        if (!preg_match('/^data:image\/(png|jpeg|jpg|gif|webp);base64,/', $data)) {
            return response()->json(['success' => false, 'message' => 'Format tanda tangan tidak valid.'], 422);
        }

        $asesor = $this->getAsesor();

        if (!$asesor) {
            return response()->json(['success' => false, 'message' => 'Profil asesor tidak ditemukan.'], 404);
        }

        $asesor->update(['saved_tanda_tangan' => $data]);

        return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil disimpan.']);
    }

    /**
     * Hapus tanda tangan tersimpan dari profil asesor.
     */
    public function deleteSignature(Request $request)
    {
        $asesor = $this->getAsesor();

        if (!$asesor) {
            return response()->json(['success' => false, 'message' => 'Profil asesor tidak ditemukan.'], 404);
        }

        $asesor->update(['saved_tanda_tangan' => null]);

        return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil dihapus.']);
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
    public function kelompokIndex(Request $request)
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();

        if (!$asesor) {
            $kelompoks = collect();
            return view('asesor.kelompok.index', compact('account', 'asesor', 'kelompoks'));
        }

        $kelompoks = Kelompok::with(['skema', 'asesis.jurusan', 'jadwals'])
            ->whereHas('asesors', function ($q) use ($asesor) {
                $q->where('asesor.ID_asesor', $asesor->ID_asesor);
            })
            ->when($request->filled('status') && $request->status !== 'all', function ($query) use ($request) {
                $query->filterStatus($request->status);
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

        $kelompok = Kelompok::with(['skema', 'asesis.jurusan', 'asesors', 'jadwals'])
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
        $skemaNames = $asesor ? $asesor->skemas->pluck('nama_skema')->filter()->values() : collect();

        if (!count($skemaIds)) {
            $data  = collect();
            $skema = null;
            $summary = [
                'total'   => 0,
                'selesai' => 0,
                'sedang'  => 0,
                'belum'   => 0,
            ];

            return view('asesor.asesi.index', compact('account', 'asesor', 'data', 'skema', 'summary', 'skemaNames'));
        }

        // Build base query with asesi join for filtering
        $query = DB::table('asesi_skema')
            ->join('asesi as a', 'asesi_skema.asesi_nik', '=', 'a.NIK')
            ->whereIn('asesi_skema.skema_id', $skemaIds);

        // Filter by search (nama and NIK only)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('a.nama', 'like', "%{$search}%")
                  ->orWhere('a.NIK', 'like', "%{$search}%");
            });
        }

        // Filter by jurusan
        if ($request->filled('jurusan')) {
            $query->where('a.ID_jurusan', $request->jurusan);
        }

        // Filter by skema (in addition to asesor's skema filter)
        if ($request->filled('skema')) {
            $query->where('asesi_skema.skema_id', $request->skema);
        }

        // Filter by kelas
        if ($request->filled('kelas')) {
            $query->where('a.kelas', 'like', "%{$request->kelas}%");
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('asesi_skema.status', $request->status);
        }

        $rows = $query->select('asesi_skema.*')->orderByDesc('asesi_skema.updated_at')->get();

        // Get rekaman, asesmen mandiri, and penilaian counts
        $rekamanByAsesiSkema = DB::table('rekaman_asesmen_kompetensi')
            ->where('asesor_id', $asesor->ID_asesor)
            ->select(DB::raw("CONCAT(asesi_nik, '|', skema_id) as `asset_key`"), 'id')
            ->get()
            ->pluck('id', 'asset_key');
        
        $asesmenByAsesiSkema = DB::table('jawaban_elemens as je')
            ->join('elemens as e', 'je.elemen_id', '=', 'e.id')
            ->join('units as u', 'e.unit_id', '=', 'u.id')
            ->whereIn('je.asesi_nik', $rows->pluck('asesi_nik')->unique()->values())
            ->whereIn('u.skema_id', $skemaIds)
            ->select(DB::raw("CONCAT(je.asesi_nik, '|', u.skema_id) as `asset_key`"), 'je.id')
            ->get()
            ->pluck('id', 'asset_key');
        
        $penilaianByAsesiSkema = DB::table('asesor_nilai_elemens')
            ->where('asesor_id', $asesor->ID_asesor)
            ->whereIn('asesi_nik', $rows->pluck('asesi_nik')->unique()->values())
            ->select(DB::raw("CONCAT(asesi_nik, '|', skema_id) as `asset_key`"), 'id')
            ->get()
            ->pluck('id', 'asset_key');

        $ceklisByAsesiSkema = DB::table('ceklis_observasi_aktivitas_praktiks')
            ->where('asesor_id', $asesor->ID_asesor)
            ->whereIn('asesi_nik', $rows->pluck('asesi_nik')->unique()->values())
            ->whereIn('skema_id', $skemaIds)
            ->select(
                DB::raw("MAX(id) as id"),
                DB::raw("CONCAT(asesi_nik, '|', skema_id) as `asset_key`")
            )
            ->groupBy('asesi_nik', 'skema_id')
            ->get()
            ->pluck('id', 'asset_key');

        // Check scheduling for asesi (both direct and via kelompok)
        $scheduledKelompokIds = DB::table('jadwal_kelompok')->pluck('kelompok_id')->unique()->toArray();
        $directScheduledKelompokIds = DB::table('jadwal_ujikom')->whereNotNull('kelompok_id')->pluck('kelompok_id')->unique()->toArray();
        $allScheduledKelompokIds = array_unique(array_merge($scheduledKelompokIds, $directScheduledKelompokIds));
        $scheduledAsesiNiks = DB::table('jadwal_peserta')->pluck('asesi_nik')->unique()->toArray();

        // Attach asesi data
        $data = $rows->map(function ($row) use ($rekamanByAsesiSkema, $asesmenByAsesiSkema, $penilaianByAsesiSkema, $ceklisByAsesiSkema, $allScheduledKelompokIds, $scheduledAsesiNiks) {
            $row->asesi = Asesi::where('NIK', $row->asesi_nik)->first();
            $row->skema = Skema::find($row->skema_id);
            
            $key = "{$row->asesi_nik}|{$row->skema_id}";
            $row->has_rekaman = isset($rekamanByAsesiSkema[$key]);
            $row->has_asesmen_mandiri = isset($asesmenByAsesiSkema[$key]);
            $row->has_penilaian = isset($penilaianByAsesiSkema[$key]);
            $row->has_ceklis_observasi = isset($ceklisByAsesiSkema[$key]);
            $row->ceklis_observasi_id = $ceklisByAsesiSkema[$key] ?? null;

            // Attach has_jadwal flag
            $row->has_jadwal = in_array($row->asesi_nik, $scheduledAsesiNiks) 
                || ($row->asesi && $row->asesi->kelompok_id && in_array($row->asesi->kelompok_id, $allScheduledKelompokIds));

            // Persetujuan Asesmen status: check latest persetujuan record for this asesi+skema
            $row->persetujuan_exists = false;
            $row->persetujuan_signed_by_asesor = false;
            $row->persetujuan_signed_by_asesi = false;
            if ($row->skema) {
                $p = PersetujuanAsesmen::where('nomor_skema', $row->skema->nomor_skema)
                    ->where(function ($q) use ($row) {
                        $q->where('nama_asesi', $row->asesi?->nama ?? '');
                        if (method_exists($row->asesi, 'NIK') || !empty($row->asesi?->NIK)) {
                            $q->orWhere('asesi_nik', $row->asesi_nik);
                        }
                    })
                    ->latest()
                    ->first();

                if ($p) {
                    $row->persetujuan_exists = true;
                    $row->persetujuan_signed_by_asesor = !empty($p->ttd_asesor_nama);
                    $row->persetujuan_signed_by_asesi = !empty($p->ttd_asesi_nama);
                    $row->persetujuan_id = $p->id;
                }
            }
            
            return $row;
        });

        // Sort data according to assessment workflow priority
        $data = $data->sort(function ($a, $b) {
            $rank = function ($row) {
                $hasAsesmenMandiri = (bool) ($row->has_asesmen_mandiri ?? false);
                $rekomendasi = $row->rekomendasi ?? '';
                $persetujuanSignedByAsesor = (bool) ($row->persetujuan_signed_by_asesor ?? false);
                $persetujuanSignedByAsesi = (bool) ($row->persetujuan_signed_by_asesi ?? false);
                $persetujuanFullySigned = $persetujuanSignedByAsesor && $persetujuanSignedByAsesi;
                $hasRekaman = (bool) ($row->has_rekaman ?? false);
                $hasCeklis = (bool) ($row->has_ceklis_observasi ?? false);
                $hasPenilaian = (bool) ($row->has_penilaian ?? false);

                if (($rekomendasi) === 'tidak_lanjut') return 99;
                if (!$hasAsesmenMandiri) return 0;
                if (empty($rekomendasi)) return 1;
                if (!$persetujuanFullySigned) return 2;
                if (!$hasCeklis) return 3;
                if (!$hasRekaman) return 4;
                if (!$hasPenilaian) return 5;
                return 6;
            };

            $ra = $rank($a);
            $rb = $rank($b);
            if ($ra !== $rb) return $ra <=> $rb;

            // tie-breaker: most recently updated first
            $ta = isset($a->updated_at) ? strtotime($a->updated_at) : 0;
            $tb = isset($b->updated_at) ? strtotime($b->updated_at) : 0;
            return $tb <=> $ta;
        })->values();

        $skema = count($skemaIds) === 1 ? Skema::find($skemaIds[0]) : null;

        // Calculate counts based on actual workflow status
        $selesaiCount = 0;
        $sedangCount = 0;
        $belumCount = 0;

        foreach ($data as $row) {
            $hasAsesmenMandiri = (bool) $row->has_asesmen_mandiri;
            $rekomendasi = $row->rekomendasi ?? '';
            $persetujuanSignedByAsesor = (bool) ($row->persetujuan_signed_by_asesor ?? false);
            $persetujuanSignedByAsesi = (bool) ($row->persetujuan_signed_by_asesi ?? false);
            $persetujuanFullySigned = $persetujuanSignedByAsesor && $persetujuanSignedByAsesi;
            $hasRekaman = (bool) $row->has_rekaman;
            $hasCeklis = (bool) $row->has_ceklis_observasi;
            $hasPenilaian = (bool) $row->has_penilaian;

            if (!$hasAsesmenMandiri) {
                $belumCount++;
            } elseif (empty($rekomendasi)) {
                $sedangCount++;
            } elseif ($rekomendasi === 'tidak_lanjut') {
                $belumCount++;
            } elseif (!$persetujuanFullySigned) {
                $sedangCount++;
            } elseif (!$hasCeklis) {
                $sedangCount++;
            } elseif (!$hasRekaman) {
                $sedangCount++;
            } elseif (!$hasPenilaian) {
                $sedangCount++;
            } else {
                $selesaiCount++;
            }
        }

        $summary = [
            'total'   => $data->count(),
            'selesai' => $selesaiCount,
            'sedang'  => $sedangCount,
            'belum'   => $belumCount,
        ];

        $selectedJurusan = $request->filled('jurusan') ? (string) $request->jurusan : '';

        // Jurusan is the parent filter, so only show jurusan that are reachable
        // from the asesor's assigned skemas.
        $jurusans = DB::table('jurusan')
            ->whereIn('ID_jurusan', function ($q) use ($skemaIds) {
                $q->select('jurusan_id')
                    ->from('skemas')
                    ->whereIn('id', $skemaIds)
                    ->whereNotNull('jurusan_id');
            })
            ->orderBy('nama_jurusan')
            ->get();

        $skemaQuery = Skema::whereIn('id', $skemaIds)
            ->orderBy('nama_skema');

        $kelasQuery = Kelas::query()
            ->orderBy('nama_kelas');

        if ($selectedJurusan !== '') {
            $skemaQuery->where('jurusan_id', $selectedJurusan);
            $kelasQuery->where('ID_jurusan', $selectedJurusan);
        } else {
            $kelasQuery->whereRaw('1 = 0');
        }

        $skemaList = $skemaQuery->get();
        $kelasList = $kelasQuery->get();

        return view('asesor.asesi.index', compact(
            'account', 'asesor', 'data', 'skema', 'summary', 'skemaNames',
            'jurusans', 'skemaList', 'kelasList', 'selectedJurusan'
        ));
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

        return redirect()->route('asesor.entry-penilaian')
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

        return redirect()->route('asesor.asesmen-mandiri.show', [
            'asesiNik' => $asesiNik,
            'skemaId' => $pivot->skema_id,
        ]);
    }

    /**
     * Daftar asesmen mandiri untuk asesor
     */
    public function asesmenMandiriIndex(Request $request)
    {
        $account = Auth::guard('account')->user();
        $asesor  = $this->getAsesor();
        $skemaIds = $asesor ? $asesor->skemas->pluck('id')->toArray() : [];
        $search = trim((string) $request->get('search'));
        
        $status = $request->input('status') ?? '';
        $rekomendasi = $request->input('rekomendasi') ?? '';

        // If status is not specified and it's not an AJAX request, default to 'menunggu_review' to show the priority tab first
        if ($status === '' && !$request->ajax() && !$request->has('rekomendasi')) {
            $status = 'menunggu_review';
        }

        if (!$asesor || !count($skemaIds)) {
            $data = collect();
            $summary = [
                'total' => 0,
                'pending_review' => 0,
                'belum_dikerjakan' => 0,
                'sudah_direkomendasikan' => 0,
                'tidak_direkomendasikan' => 0,
            ];

            if ($request->ajax()) {
                return view('asesor.asesmen-mandiri.partials.table-rows', compact('data'))->render();
            }

            return view('asesor.asesmen-mandiri.index', compact('account', 'asesor', 'data', 'summary', 'search', 'status', 'rekomendasi'));
        }

        $query = DB::table('asesi_skema')->whereIn('skema_id', $skemaIds);

        if ($search !== '') {
            $query
                ->join('asesi as a', 'asesi_skema.asesi_nik', '=', 'a.NIK')
                ->join('skemas as s', 'asesi_skema.skema_id', '=', 's.id')
                ->where(function ($q) use ($search) {
                    $q->where('a.nama', 'like', "%{$search}%")
                        ->orWhere('a.NIK', 'like', "%{$search}%")
                        ->orWhere('s.nama_skema', 'like', "%{$search}%")
                        ->orWhere('s.nomor_skema', 'like', "%{$search}%");
                })
                ->select('asesi_skema.*');
        }

        $rows = $query->orderByDesc('updated_at')->get();

        $asesiMap = Asesi::query()
            ->whereIn('NIK', $rows->pluck('asesi_nik')->unique()->values())
            ->get()
            ->keyBy('NIK');

        $skemaMap = Skema::query()
            ->whereIn('id', $rows->pluck('skema_id')->unique()->values())
            ->get()
            ->keyBy('id');

        $asesmenByAsesiSkema = DB::table('jawaban_elemens as je')
            ->join('elemens as e', 'je.elemen_id', '=', 'e.id')
            ->join('units as u', 'e.unit_id', '=', 'u.id')
            ->whereIn('je.asesi_nik', $rows->pluck('asesi_nik')->unique()->values())
            ->whereIn('u.skema_id', $skemaIds)
            ->select(
                DB::raw("COUNT(je.id) as total"),
                DB::raw("CONCAT(je.asesi_nik, '|', u.skema_id) as `asset_key`")
            )
            ->groupBy('je.asesi_nik', 'u.skema_id')
            ->get()
            ->pluck('total', 'asset_key');

        $allData = $rows->map(function ($row) use ($asesiMap, $skemaMap, $asesmenByAsesiSkema) {
            $row->asesi = $asesiMap[$row->asesi_nik] ?? null;
            $row->skema = $skemaMap[$row->skema_id] ?? null;

            $key = "{$row->asesi_nik}|{$row->skema_id}";
            $row->has_asesmen_mandiri = isset($asesmenByAsesiSkema[$key]);
            $row->jawaban_count = (int) ($asesmenByAsesiSkema[$key] ?? 0);

            return $row;
        });

        $summary = [
            'total' => $allData->count(),
            'pending_review' => $allData->filter(fn($row) => $row->status === 'selesai' && empty($row->rekomendasi))->count(),
            'belum_dikerjakan' => $allData->filter(fn($row) => $row->status !== 'selesai')->count(),
            'sudah_direkomendasikan' => $allData->filter(fn($row) => $row->status === 'selesai' && ($row->rekomendasi ?? '') === 'lanjut')->count(),
            'tidak_direkomendasikan' => $allData->filter(fn($row) => $row->status === 'selesai' && ($row->rekomendasi ?? '') === 'tidak_lanjut')->count(),
        ];

        // Filter based on status (tabs or dropdown)
        $data = $allData;
        
        if ($status !== '') {
            if ($status === 'menunggu_review') {
                $data = $data->filter(fn($row) => $row->status === 'selesai' && empty($row->rekomendasi));
            } elseif ($status === 'belum_dikerjakan') {
                $data = $data->filter(fn($row) => $row->status !== 'selesai');
            } elseif ($status === 'sudah_direkomendasikan') {
                $data = $data->filter(fn($row) => $row->status === 'selesai' && ($row->rekomendasi ?? '') === 'lanjut');
            } elseif ($status === 'tidak_direkomendasikan') {
                $data = $data->filter(fn($row) => $row->status === 'selesai' && ($row->rekomendasi ?? '') === 'tidak_lanjut');
            } elseif ($status === 'selesai') {
                $data = $data->filter(fn($row) => $row->status === 'selesai');
            } elseif ($status === 'sedang_mengerjakan') {
                $data = $data->filter(fn($row) => $row->status === 'sedang_mengerjakan');
            } elseif ($status === 'belum_mulai') {
                $data = $data->filter(fn($row) => $row->status === 'belum_mulai');
            }
        }

        if ($rekomendasi !== '') {
            if ($rekomendasi === 'belum') {
                $data = $data->filter(fn($row) => empty($row->rekomendasi));
            } else {
                $data = $data->filter(fn($row) => ($row->rekomendasi ?? '') === $rekomendasi);
            }
        }

        if ($request->ajax()) {
            return view('asesor.asesmen-mandiri.partials.table-rows', compact('data'))->render();
        }

        return view('asesor.asesmen-mandiri.index', compact('account', 'asesor', 'data', 'summary', 'search', 'status', 'rekomendasi'));
    }

    /**
     * Detail asesmen mandiri untuk asesor
     */
    public function asesmenMandiriShow($asesiNik, $skemaId)
    {
        $account = Auth::guard('account')->user();
        $asesor  = $this->getAsesor();
        $skemaIds = $asesor ? $asesor->skemas->pluck('id')->toArray() : [];

        $asesi = Asesi::where('NIK', $asesiNik)->firstOrFail();

        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->where('skema_id', $skemaId)
            ->when(count($skemaIds), fn($q) => $q->whereIn('skema_id', $skemaIds))
            ->first();

        abort_unless((bool) $pivot, 403, 'Asesi ini tidak terdaftar di skema Anda.');

        $skema = Skema::with(['units.elemens.kriteria'])->findOrFail($skemaId);

        $answers = JawabanElemen::where('asesi_nik', $asesiNik)
            ->whereHas('elemen.unit', fn ($q) => $q->where('skema_id', $skemaId))
            ->get()
            ->keyBy('elemen_id');

        $kCount  = $answers->where('status', 'K')->count();
        $bkCount = $answers->where('status', 'BK')->count();
        $totalElemen = $skema->units->reduce(function ($carry, $unit) {
            return $carry + $unit->elemens->count();
        }, 0);
        $totalBelum = max(0, $totalElemen - $kCount - $bkCount);

        $savedSignature = $asesor ? $asesor->saved_tanda_tangan : null;

        return view('asesor.asesmen-mandiri.show', compact(
            'account', 'asesor', 'asesi', 'skema', 'answers', 'pivot', 'kCount', 'bkCount', 'totalElemen', 'totalBelum', 'savedSignature'
        ));
    }

    /**
     * Export asesmen mandiri (FR.APL.02) untuk asesor.
     */
    public function asesmenMandiriExport($asesiNik, $skemaId)
    {
        $asesor = $this->getAsesor();
        $skemaIds = $asesor ? $asesor->skemas->pluck('id')->toArray() : [];

        $asesi = Asesi::where('NIK', $asesiNik)->firstOrFail();

        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->where('skema_id', $skemaId)
            ->when(count($skemaIds), fn($q) => $q->whereIn('skema_id', $skemaIds))
            ->first();

        abort_unless((bool) $pivot, 403, 'Asesi ini tidak terdaftar di skema Anda.');

        $skema = Skema::with([
            'units' => fn($query) => $query->orderBy('id'),
            'units.elemens' => fn($query) => $query->orderBy('id'),
            'units.elemens.kriteria' => fn($query) => $query->orderBy('urutan')->orderBy('id'),
        ])->findOrFail($skemaId);

        $answers = JawabanElemen::where('asesi_nik', $asesiNik)
            ->whereHas('elemen.unit', fn ($q) => $q->where('skema_id', $skemaId))
            ->get()
            ->keyBy('elemen_id');

        $logoPath = public_path('images/lsp.png');
        $logoDataUri = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $html = view('asesor.asesmen-mandiri.export-fr-apl-02', [
            'asesi' => $asesi,
            'asesor' => $asesor,
            'skema' => $skema,
            'answers' => $answers,
            'pivot' => $pivot,
            'logoPath' => $logoPath,
            'logoDataUri' => $logoDataUri,
        ])->render();

        $fileSkema = preg_replace('/[^A-Za-z0-9\-]+/', '-', (string) ($skema->nomor_skema ?? $skema->id));
        $fileName = 'FR.APL.02-' . $asesi->NIK . '-' . trim($fileSkema, '-') . '.doc';

        return response($html, 200, [
            'Content-Type' => 'application/msword; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Simpan rekomendasi asesor untuk asesmen mandiri asesi
     */
    public function recommend(Request $request, $asesiNik, $skemaId = null)
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
        $pivotQuery = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->when(count($skemaIds), fn($q) => $q->whereIn('skema_id', $skemaIds));

        if ($skemaId) {
            $pivotQuery->where('skema_id', $skemaId);
        }

        $pivot = $pivotQuery->first();

        abort_unless((bool) $pivot, 403, 'Asesi ini tidak terdaftar di skema Anda.');

        $targetSkemaId = $skemaId ?: $pivot->skema_id;

        $updated = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->where('skema_id', $targetSkemaId)
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

        if ($skemaId) {
            return redirect()->route('asesor.asesmen-mandiri.show', [
                'asesiNik' => $asesiNik,
                'skemaId' => $targetSkemaId,
            ])->with('success', 'Rekomendasi berhasil disimpan: ' . $label . '.');
        }

        return redirect()->route('asesor.asesi.review', $asesiNik)
            ->with('success', 'Rekomendasi berhasil disimpan: ' . $label . '.');
    }
}
