<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'skema_id',
        'kode_unit',
        'judul_unit',
        'pertanyaan_unit',
    ];

    public function skema()
    {
        return $this->belongsTo(Skema::class);
    }

    public function elemens()
    {
        return $this->hasMany(Elemen::class);
    }
}
