<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use HasFactory;

    protected $table = 'mitra';
    protected $primaryKey = 'no_mou';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_mou',
        'nama_mitra',
        'alamat',
        'telepon',
        'email',
    ];

    public function asesor()
    {
        return $this->hasMany(Asesor::class, 'no_mou', 'no_mou');
    }
}
