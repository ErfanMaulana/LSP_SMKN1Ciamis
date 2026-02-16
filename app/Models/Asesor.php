<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesor extends Model
{
    use HasFactory;

    protected $table = 'asesor';
    protected $primaryKey = 'ID_asesor';

    protected $fillable = [
        'ID_skema',
        'no_mou',
        'nama',
    ];

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'no_mou', 'no_mou');
    }
}
