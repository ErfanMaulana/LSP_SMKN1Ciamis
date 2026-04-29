<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BandingAsesmen extends Model
{
    protected $table = 'banding_asesmen';

    protected $fillable = [
        'asesi_nik',
        'skema_id',
        'asesor_id',
        'tanggal_asesmen',
        'tanggal_pengajuan',
        'alasan_banding',
        'status',
        'catatan_admin',
        'checked_by',
        'checked_at',
    ];

    protected $casts = [
        'tanggal_asesmen' => 'date',
        'tanggal_pengajuan' => 'date',
        'checked_at' => 'datetime',
    ];

    public function asesi()
    {
        return $this->belongsTo(Asesi::class, 'asesi_nik', 'NIK');
    }

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'skema_id');
    }

    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'asesor_id', 'ID_asesor');
    }

    public function checker()
    {
        return $this->belongsTo(Admin::class, 'checked_by');
    }

    public function jawaban()
    {
        return $this->hasMany(BandingAsesmenJawaban::class, 'banding_id');
    }
}
