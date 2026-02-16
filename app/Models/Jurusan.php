<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';
    protected $primaryKey = 'ID_jurusan';

    protected $fillable = [
        'nama_jurusan',
        'kode_jurusan',
    ];

    public function asesi()
    {
        return $this->hasMany(Asesi::class, 'ID_jurusan', 'ID_jurusan');
    }
}
