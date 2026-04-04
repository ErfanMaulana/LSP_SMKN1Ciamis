<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    protected $table = 'kelompok';

    protected $fillable = ['nama_kelompok', 'skema_id'];

    public function skema()
    {
        return $this->belongsTo(Skema::class);
    }

    public function asesors()
    {
        return $this->belongsToMany(Asesor::class, 'kelompok_asesor', 'kelompok_id', 'asesor_id');
    }

    public function asesis()
    {
        return $this->hasMany(Asesi::class, 'kelompok_id');
    }

    public function jadwals()
    {
        return $this->belongsToMany(
            JadwalUjikom::class,
            'jadwal_kelompok',
            'kelompok_id',
            'jadwal_id'
        );
    }
}
