<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    public function index()
    {
        $account = Auth::guard('account')->user();
        $asesi   = Asesi::where('NIK', $account->NIK)->first();

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        // Jadwal yang asesi sudah terdaftar (via jadwal_peserta)
        // Only show jadwal whose attempt matches the asesi's current attempt for that skema
        $jadwalTerdaftar = DB::table('jadwal_peserta')
            ->join('jadwal_ujikom', 'jadwal_ujikom.id', '=', 'jadwal_peserta.jadwal_id')
            ->leftJoin('tuk', 'tuk.id', '=', 'jadwal_ujikom.tuk_id')
            ->leftJoin('skemas', 'skemas.id', '=', 'jadwal_ujikom.skema_id')
            ->where('jadwal_peserta.asesi_nik', $asesi->NIK)
            ->whereRaw('
                jadwal_peserta.attempt = COALESCE(
                    (SELECT MAX(ase_sk.attempt)
                     FROM asesi_skema ase_sk
                     WHERE ase_sk.asesi_nik = jadwal_peserta.asesi_nik
                       AND ase_sk.skema_id  = jadwal_ujikom.skema_id),
                    1
                )
            ')
            ->select(
                'jadwal_ujikom.id',
                'jadwal_ujikom.judul_jadwal',
                'jadwal_ujikom.tanggal_mulai',
                'jadwal_ujikom.tanggal_selesai',
                'jadwal_ujikom.waktu_mulai',
                'jadwal_ujikom.waktu_selesai',
                'jadwal_ujikom.kuota',
                'jadwal_ujikom.peserta_terdaftar',
                'jadwal_ujikom.status',
                'jadwal_ujikom.keterangan',
                'tuk.nama_tuk',
                'tuk.kota',
                'tuk.alamat as tuk_alamat',
                'tuk.tipe_tuk',
                'skemas.nama_skema'
            )
            ->orderByRaw("CASE jadwal_ujikom.status WHEN 'berlangsung' THEN 1 WHEN 'dijadwalkan' THEN 2 WHEN 'selesai' THEN 3 WHEN 'dibatalkan' THEN 4 ELSE 5 END")
            ->orderBy('jadwal_ujikom.tanggal_mulai')
            ->get();

        // Ambil daftar peserta untuk setiap jadwal
        $jadwalWithPeserta = $jadwalTerdaftar->map(function ($jadwal) {
            $peserta = DB::table('jadwal_peserta')
                ->join('asesi', 'asesi.NIK', '=', 'jadwal_peserta.asesi_nik')
                ->leftJoin('jurusan', 'jurusan.ID_jurusan', '=', 'asesi.ID_jurusan')
                ->where('jadwal_peserta.jadwal_id', $jadwal->id)
                ->select(
                    'asesi.NIK',
                    'asesi.nama',
                    'asesi.email',
                    'asesi.kelas',
                    'jurusan.nama_jurusan',
                    'jurusan.kode_jurusan'
                )
                ->orderBy('asesi.nama')
                ->get();
            
            $jadwal->peserta = $peserta;
            return $jadwal;
        });

        return view('asesi.jadwal', compact('account', 'asesi', 'jadwalWithPeserta'));
    }
}
