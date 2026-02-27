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
        $asesi   = Asesi::where('no_reg', $account->no_reg)->first();

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        // Jadwal yang asesi sudah terdaftar (via jadwal_peserta)
        $jadwalTerdaftar = DB::table('jadwal_peserta')
            ->join('jadwal_ujikom', 'jadwal_ujikom.id', '=', 'jadwal_peserta.jadwal_id')
            ->leftJoin('tuk', 'tuk.id', '=', 'jadwal_ujikom.tuk_id')
            ->leftJoin('skemas', 'skemas.id', '=', 'jadwal_ujikom.skema_id')
            ->where('jadwal_peserta.asesi_nik', $asesi->NIK)
            ->select(
                'jadwal_ujikom.id',
                'jadwal_ujikom.judul_jadwal',
                'jadwal_ujikom.tanggal',
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
            ->orderByRaw("FIELD(jadwal_ujikom.status, 'berlangsung', 'dijadwalkan', 'selesai', 'dibatalkan')")
            ->orderBy('jadwal_ujikom.tanggal')
            ->get();

        return view('asesi.jadwal', compact('account', 'asesi', 'jadwalTerdaftar'));
    }
}
