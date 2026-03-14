<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsesorNilaiElemen extends Model
{
    protected $table = 'asesor_nilai_elemens';

    protected $fillable = [
        'asesi_nik',
        'skema_id',
        'elemen_id',
        'asesor_id',
        'nilai',
        'status',
    ];

    protected $casts = [
        'nilai' => 'float',
    ];

    public function asesi()
    {
        return $this->belongsTo(Asesi::class, 'asesi_nik', 'NIK');
    }

    public function skema()
    {
        return $this->belongsTo(Skema::class);
    }

    public function elemen()
    {
        return $this->belongsTo(Elemen::class);
    }

    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'asesor_id', 'ID_asesor');
    }
}
