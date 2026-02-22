<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanElemen extends Model
{
    protected $table = 'jawaban_elemens';

    protected $fillable = [
        'asesi_nik',
        'elemen_id',
        'status',
        'bukti',
    ];

    public function asesi()
    {
        return $this->belongsTo(Asesi::class, 'asesi_nik', 'NIK');
    }

    public function elemen()
    {
        return $this->belongsTo(Elemen::class);
    }
}
