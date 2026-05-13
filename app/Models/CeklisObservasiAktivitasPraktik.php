<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CeklisObservasiAktivitasPraktikDetail;

class CeklisObservasiAktivitasPraktik extends Model
{
    protected $table = 'ceklis_observasi_aktivitas_praktiks';

    protected $fillable = [
        'kode_form',
        'judul_form',
        'skema_id',
        'asesi_nik',
        'asesor_id',
        'tuk',
        'tanggal',
        'rekomendasi',
        'belum_kompeten_kelompok_pekerjaan',
        'belum_kompeten_unit',
        'belum_kompeten_elemen',
        'belum_kompeten_kuk',
        'ttd_asesi_nama',
        'ttd_asesi_tanggal',
        'ttd_asesor_nama',
        'ttd_asesor_no_reg',
        'ttd_asesor_tanggal',
        'catatan_footer',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'ttd_asesi_tanggal' => 'date',
        'ttd_asesor_tanggal' => 'date',
    ];

    public function skema()
    {
        return $this->belongsTo(Skema::class);
    }

    public function asesi()
    {
        return $this->belongsTo(Asesi::class, 'asesi_nik', 'NIK');
    }

    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'asesor_id', 'ID_asesor');
    }

    public function details()
    {
        return $this->hasMany(CeklisObservasiAktivitasPraktikDetail::class, 'ceklis_observasi_id');
    }
}
