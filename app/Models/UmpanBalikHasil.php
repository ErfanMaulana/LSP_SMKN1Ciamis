<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmpanBalikHasil extends Model
{
    protected $table = 'umpan_balik_hasil';

    protected $fillable = [
        'asesi_nik',
        'skema_id',
        'komponen_id',
        'jawaban',
        'catatan',
    ];

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'skema_id');
    }

    public function komponen()
    {
        return $this->belongsTo(UmpanBalikKomponen::class, 'komponen_id');
    }

    public function asesi()
    {
        return $this->belongsTo(Asesi::class, 'asesi_nik', 'NIK');
    }
}
