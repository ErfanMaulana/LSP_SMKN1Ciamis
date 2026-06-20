<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RekamanAsesmenKompetensiDetail;

class RekamanAsesmenKompetensi extends Model
{
    protected $table = 'rekaman_asesmen_kompetensi';

    protected $fillable = [
        'kode_form',
        'judul_form',
        'kategori_skema',
        'skema_id',
        'tuk',
        'asesor_id',
        'asesi_nik',
        'tanggal_mulai',
        'tanggal_selesai',
        'rekomendasi',
        'tindak_lanjut',
        'komentar_observasi',
        'ttd_asesor_nama',
        'ttd_asesor_no_reg',
        'ttd_asesor_tanggal',
        'ttd_asesor_file',
        'ttd_asesi_nama',
        'ttd_asesi_tanggal',
        'ttd_asesi_file',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'ttd_asesor_tanggal' => 'date',
        'ttd_asesi_tanggal' => 'date',
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
        return $this->hasMany(RekamanAsesmenKompetensiDetail::class, 'rekaman_id');
    }
}
