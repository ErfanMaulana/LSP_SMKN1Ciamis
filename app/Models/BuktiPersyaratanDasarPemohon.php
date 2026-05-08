<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiPersyaratanDasarPemohon extends Model
{
    use HasFactory;

    protected $table = 'bukti_persyaratan_dasar_pemohon';

    protected $fillable = [
        'skema_id',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'skema_id');
    }
}