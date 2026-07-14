<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan form login asesi
     */
    public function showLoginForm()
    {
        if (Auth::guard('account')->check()) {
            $account = Auth::guard('account')->user();

            if ($account->isAsesi()) {
                return redirect()->route('asesi.dashboard');
            }
            if ($account->isAsesor()) {
                return redirect()->route('asesor.dashboard');
            }
        }

        return view('asesi.login');
    }

    /**
     * Proses login asesi / asesor
     */
    public function login(Request $request)
    {
        $request->validate([
            'no_reg'   => 'required|string',
            'password' => 'required|string',
        ]);

        $account = Account::where('id', $request->no_reg)->first();

        if ($account && Hash::check($request->password, $account->password)) {
            Auth::guard('account')->login($account, $request->filled('remember'));

            ActivityLogger::logUser(
                (string) ($account->NIK ?? $account->id),
                $account->nama ?? (string) ($account->NIK ?? $account->id),
                'Login User',
                'User berhasil login ke sistem.',
                $request,
                ['role' => $account->role]
            );

            if ($account->isAsesi()) {
                return redirect()->route('asesi.dashboard')->with('success', 'Login berhasil!');
            }
            if ($account->isAsesor()) {
                return redirect()->route('asesor.dashboard')->with('success', 'Login berhasil!');
            }

            // Fallback
            return redirect()->route('asesi.dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'no_reg' => 'Nomor registrasi atau password salah.',
        ])->withInput($request->only('no_reg'));
    }

    /**
     * Logout asesi / asesor
     */
    public function logout(Request $request)
    {
        $account = Auth::guard('account')->user();

        if ($account) {
            ActivityLogger::logUser(
                (string) ($account->NIK ?? $account->id),
                $account->nama ?? (string) ($account->NIK ?? $account->id),
                'Logout User',
                'User logout dari sistem.',
                $request,
                ['role' => $account->role]
            );
        }

        Auth::guard('account')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }

    /**
     * Dashboard asesi
     */
    public function dashboard()
    {
        $account = Auth::guard('account')->user();

        if ($account->isAsesor()) {
            // Asesor gets their own dashboard
            $asesor = \App\Models\Asesor::with('skemas')->where('no_met', $account->id)->first();
            return redirect()->route('asesor.dashboard');
        }

        $asesi = \App\Models\Asesi::with(['jurusan', 'asesor.skemas', 'kelompok.asesors.skemas'])->where('NIK', $account->NIK)->first();

        // If asesi has no registration yet, redirect to registration form
        if (!$asesi) {
            return redirect()->route('asesi.pendaftaran.formulir');
        }

        if ($asesi->status !== 'approved') {
            return view('asesi.dashboard-pending', compact('account', 'asesi'));
        }

        // Fetch all schemas registered for this candidate (via pivot asesi_skema) - only the latest attempt
        $asesiSkemas = \DB::table('asesi_skema')
            ->join('skemas', 'asesi_skema.skema_id', '=', 'skemas.id')
            ->where('asesi_skema.asesi_nik', $asesi->NIK)
            ->whereRaw('asesi_skema.attempt = (SELECT MAX(b.attempt) FROM asesi_skema b WHERE b.asesi_nik = asesi_skema.asesi_nik AND b.skema_id = asesi_skema.skema_id)')
            ->select('skemas.*', 'asesi_skema.status as status_mandiri', 'asesi_skema.tanggal_selesai as tgl_selesai_mandiri', 'asesi_skema.attempt')
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
            $isStep1Completed = true;

            // 2. Asesmen Mandiri
            $isMandiriSelesai = ($skema->status_mandiri === 'selesai');
            $isStep2Completed = $isMandiriSelesai;
            $stepMandiri = [
                'name' => 'Asesmen Mandiri (FR.APL.02)',
                'status' => $isStep2Completed ? 'completed' : 'pending',
                'label' => $isMandiriSelesai ? 'Selesai' : 'Belum Selesai',
                'description' => $isMandiriSelesai 
                    ? 'Anda telah menyelesaikan pengisian form asesmen mandiri.'
                    : 'Silakan isi form asesmen mandiri terlebih dahulu.'
            ];

            // 3. Jadwal Ujikom (Evaluated before Persetujuan for sequential calculation)
            $jadwal = \DB::table('jadwal_peserta')
                ->join('jadwal_ujikom', 'jadwal_ujikom.id', '=', 'jadwal_peserta.jadwal_id')
                ->where('jadwal_peserta.asesi_nik', $asesi->NIK)
                ->where('jadwal_ujikom.skema_id', $skema->id)
                ->where('jadwal_peserta.attempt', $skema->attempt)
                ->select('jadwal_ujikom.*')
                ->first();

            $isJadwalSelesai = (bool)$jadwal;
            $isStep3Completed = $isJadwalSelesai && $isStep2Completed;
            
            $jadwalDesc = 'Menunggu penjadwalan uji kompetensi dari admin/asesor.';
            if ($isJadwalSelesai) {
                $tukName = \DB::table('tuk')->where('id', $jadwal->tuk_id)->value('nama_tuk') ?? 'TUK';
                $tglMulai = $jadwal->tanggal_mulai ? \Carbon\Carbon::parse($jadwal->tanggal_mulai)->locale('id')->isoFormat('D MMMM YYYY') : '-';
                $jadwalDesc = 'Jadwal Anda: ' . $tglMulai . ' di ' . $tukName;
            }

            $stepJadwal = [
                'name' => 'Jadwal Uji Kompetensi',
                'status' => $isStep3Completed ? 'completed' : 'pending',
                'label' => $isJadwalSelesai ? 'Sudah Dijadwalkan' : 'Belum Dijadwalkan',
                'description' => $jadwalDesc
            ];

            // 4. Persetujuan Asesmen (Evaluated after Jadwal)
            $useNik = \Illuminate\Support\Facades\Schema::hasColumn('persetujuan_asesmen', 'asesi_nik');
            $persetujuan = \DB::table('persetujuan_asesmen')
                ->where('nomor_skema', $skema->nomor_skema)
                ->where('attempt', $skema->attempt)
                ->where(function($q) use ($asesi, $useNik) {
                    $q->where('nama_asesi', $asesi->nama);
                    if ($useNik) {
                        $q->orWhere('asesi_nik', $asesi->NIK);
                    }
                })
                ->first();

            $isPersetujuanSelesai = $persetujuan && !empty($persetujuan->ttd_asesi_nama) && !empty($persetujuan->ttd_asesor_nama);
            $isStep4Completed = $isPersetujuanSelesai && $isStep3Completed;
            $isPersetujuanReady = (bool)(
                $persetujuan 
                && !empty($persetujuan->ttd_asesor_nama) 
                && !empty($persetujuan->ttd_asesor_tanggal)
                && (
                    $persetujuan->bukti_verifikasi_portofolio ||
                    $persetujuan->bukti_reviu_produk ||
                    $persetujuan->bukti_observasi_langsung ||
                    $persetujuan->bukti_kegiatan_terstruktur ||
                    $persetujuan->bukti_pertanyaan_lisan ||
                    $persetujuan->bukti_pertanyaan_tertulis ||
                    $persetujuan->bukti_pertanyaan_wawancara ||
                    $persetujuan->bukti_lainnya
                )
            );
            $stepPersetujuan = [
                'name' => 'Persetujuan Asesmen (FR.APL.03)',
                'status' => $isStep4Completed ? 'completed' : 'pending',
                'label' => $isPersetujuanSelesai ? 'Selesai & Ditandatangani' : 'Belum Ditandatangani',
                'description' => $isPersetujuanSelesai 
                    ? 'Persetujuan asesmen telah disepakati dan ditandatangani oleh Anda dan Asesor.'
                    : 'Harap periksa dan tandatangani dokumen persetujuan asesmen.',
                'is_ready' => $isPersetujuanReady && $isStep3Completed
            ];

            // 5. Penilaian / Ceklis Observasi
            $ceklis = \DB::table('ceklis_observasi_aktivitas_praktiks')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skema->id)
                ->where('attempt', $skema->attempt)
                ->first();

            $isPenilaianSelesai = $ceklis && !empty($ceklis->ttd_asesi_file) && !empty($ceklis->ttd_asesor_file);
            $isStep5Completed = $isPenilaianSelesai && $isStep4Completed;
            
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
                'status' => $isStep5Completed ? 'completed' : 'pending',
                'label' => $penilaianLabel,
                'description' => $penilaianDesc
            ];

            // 6. Rekaman Asesmen (FR.AK.02)
            $rekaman = \DB::table('rekaman_asesmen_kompetensi')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skema->id)
                ->where('attempt', $skema->attempt)
                ->first();

            $isRekamanSelesai = $rekaman && !empty($rekaman->ttd_asesi_file) && !empty($rekaman->ttd_asesor_file);
            $isStep6Completed = $isRekamanSelesai && $isStep5Completed;

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
                'status' => $isStep6Completed ? 'completed' : 'pending',
                'label' => $rekamanLabel,
                'description' => $rekamanDesc
            ];

            // Check if all are completed
            $allCompleted = $isStep6Completed;

            $hasilUjikom[] = (object)[
                'skema_id' => $skema->id,
                'nama_skema' => $skema->nama_skema,
                'nomor_skema' => $skema->nomor_skema,
                'steps' => [$stepPendaftaran, $stepMandiri, $stepJadwal, $stepPersetujuan, $stepPenilaian, $stepRekaman],
                'all_completed' => $allCompleted,
                'ceklis' => $ceklis,
                'rekaman' => $rekaman,
                'rekomendasi' => $ceklis ? $ceklis->rekomendasi : null,
                'tanggal_ceklis' => $ceklis && $ceklis->ttd_asesi_tanggal ? \Carbon\Carbon::parse($ceklis->ttd_asesi_tanggal)->locale('id')->isoFormat('D MMMM YYYY') : null,
                'asesor_nama' => $ceklis && $ceklis->ttd_asesor_nama ? $ceklis->ttd_asesor_nama : null,
            ];
        }

        $hasilUjikom = collect($hasilUjikom);

        return view('asesi.dashboard', compact('account', 'asesi', 'hasilUjikom'));
    }
}
