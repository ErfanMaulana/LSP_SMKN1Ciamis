<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BandingAsesmenKomponen extends Model
{
    protected $table = 'banding_asesmen_komponen';

    protected $fillable = [
        'pernyataan',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
