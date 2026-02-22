<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    protected $fillable = [
        'nama_skema',
        'nomor_skema',
        'jenis_skema',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }
}
