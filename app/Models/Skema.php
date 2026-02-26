<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    protected $fillable = [
        'nama_skema',
        'nomor_skema',
        'jenis_skema',
        'jurusan_id',
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

    public function asesis()
    {
        return $this->belongsToMany(Asesi::class, 'asesi_skema', 'skema_id', 'asesi_nik')
                    ->withPivot('status', 'tanggal_mulai', 'tanggal_selesai')
                    ->withTimestamps();
    }
}
