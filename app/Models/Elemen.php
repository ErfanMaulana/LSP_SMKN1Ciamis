<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Elemen extends Model
{
    protected $fillable = [
        'unit_id',
        'nama_elemen',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function kriteria()
    {
        return $this->hasMany(Kriteria::class, 'elemen_id');
    }

    public function jawabanElemens()
    {
        return $this->hasMany(JawabanElemen::class, 'elemen_id');
    }
}
