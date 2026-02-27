<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalUjikom extends Model
{
    protected $table = 'jadwal_ujikom';

    protected $fillable = [
        'tuk_id',
        'skema_id',
        'judul_jadwal',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'kuota',
        'peserta_terdaftar',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai'=> 'date',
        'waktu_mulai'  => 'string',
        'waktu_selesai'=> 'string',
    ];

    public function tuk()
    {
        return $this->belongsTo(Tuk::class, 'tuk_id');
    }

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'skema_id');
    }

    public function peserta()
    {
        return $this->belongsToMany(
            \App\Models\Asesi::class,
            'jadwal_peserta',
            'jadwal_id',
            'asesi_nik',
            'id',
            'NIK'
        );
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'dijadwalkan' => 'Dijadwalkan',
            'berlangsung' => 'Berlangsung',
            'selesai'     => 'Selesai',
            'dibatalkan'  => 'Dibatalkan',
            default       => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'dijadwalkan' => '#2563eb',
            'berlangsung' => '#d97706',
            'selesai'     => '#16a34a',
            'dibatalkan'  => '#dc2626',
            default       => '#64748b',
        };
    }

    public function getSisaKuotaAttribute(): int
    {
        return max(0, $this->kuota - $this->peserta_terdaftar);
    }
}
