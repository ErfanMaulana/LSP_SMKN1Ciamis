<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BuktiPersyaratanDasarPemohon;
use App\Models\Asesor;

class Skema extends Model
{
    protected $fillable = [
        'nama_skema',
        'nomor_skema',
        'jenis_skema',
        'jurusan_id',
        'kkm',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id', 'ID_jurusan');
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function buktiPersyaratanDasarPemohon()
    {
        return $this->hasOne(BuktiPersyaratanDasarPemohon::class, 'skema_id');
    }

    public function asesis()
    {
        return $this->belongsToMany(Asesi::class, 'asesi_skema', 'skema_id', 'asesi_nik')
                    ->withPivot('status', 'tanggal_mulai', 'tanggal_selesai')
                    ->withTimestamps();
    }
    public function asesors()
    {
        return $this->belongsToMany(Asesor::class, 'asesor_skema', 'skema_id', 'asesor_id');
    }
}
