<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriteria';

    protected $fillable = [
        'elemen_id',
        'deskripsi_kriteria',
        'urutan',
    ];

    public function elemen()
    {
        return $this->belongsTo(Elemen::class);
    }
}
