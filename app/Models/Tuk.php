<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tuk extends Model
{
    protected $table = 'tuk';

    protected $fillable = [
        'nama_tuk',
        'kode_tuk',
        'tipe_tuk',
        'alamat',
        'provinsi',
        'kota',
        'no_telepon',
        'email',
        'kapasitas',
        'status',
        'keterangan',
    ];

    public function jadwalUjikom()
    {
        return $this->hasMany(JadwalUjikom::class, 'tuk_id');
    }

    public function getTipeLabelAttribute(): string
    {
        return match ($this->tipe_tuk) {
            'sewaktu'      => 'TUK Sewaktu',
            'tempat_kerja' => 'TUK Tempat Kerja',
            'mandiri'      => 'TUK Mandiri',
            default        => $this->tipe_tuk,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status === 'aktif' ? 'Aktif' : 'Non-Aktif';
    }
}
