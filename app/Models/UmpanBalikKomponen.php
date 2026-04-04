<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmpanBalikKomponen extends Model
{
    protected $table = 'umpan_balik_komponen';

    protected $fillable = [
        'skema_id',
        'pernyataan',
        'urutan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'skema_id');
    }
}
