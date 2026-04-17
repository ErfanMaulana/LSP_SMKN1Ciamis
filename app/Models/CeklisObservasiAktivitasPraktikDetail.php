<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CeklisObservasiAktivitasPraktik;

class CeklisObservasiAktivitasPraktikDetail extends Model
{
    protected $table = 'ceklis_observasi_aktivitas_praktik_details';

    protected $fillable = [
        'ceklis_observasi_id',
        'unit_id',
        'elemen_id',
        'kriteria_id',
        'pencapaian',
        'penilaian_lanjut',
    ];

    public function ceklisObservasi()
    {
        return $this->belongsTo(CeklisObservasiAktivitasPraktik::class, 'ceklis_observasi_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function elemen()
    {
        return $this->belongsTo(Elemen::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
