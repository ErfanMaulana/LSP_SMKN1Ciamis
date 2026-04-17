<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BandingAsesmenJawaban extends Model
{
    protected $table = 'banding_asesmen_jawaban';

    protected $fillable = [
        'banding_id',
        'komponen_id',
        'jawaban',
    ];

    public function banding()
    {
        return $this->belongsTo(BandingAsesmen::class, 'banding_id');
    }

    public function komponen()
    {
        return $this->belongsTo(BandingAsesmenKomponen::class, 'komponen_id');
    }
}
