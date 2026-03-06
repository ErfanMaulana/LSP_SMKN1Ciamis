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
        'nama',
        'no_met',
    ];

    public function skemas()
    {
        return $this->belongsToMany(Skema::class, 'asesor_skema', 'asesor_id', 'skema_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'no_met', 'id');
    }

    public function asesis()
    {
        return $this->hasMany(Asesi::class, 'ID_asesor', 'ID_asesor');
    }

    public function kelompoks()
    {
        return $this->belongsToMany(Kelompok::class, 'kelompok_asesor', 'asesor_id', 'kelompok_id');
    }
}
