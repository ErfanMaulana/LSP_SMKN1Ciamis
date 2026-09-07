<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StandarIndustriUnit extends Model
{
    protected $table = 'standar_industri_unit';

    protected $fillable = [
        'unit_id',
        'nama_standar',
        'deskripsi_standar',
        'urutan',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
