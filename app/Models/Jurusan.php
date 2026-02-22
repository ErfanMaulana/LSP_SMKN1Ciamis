<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';
    protected $primaryKey = 'id_jurusan'; // lowercase

    protected $fillable = [
        'Nama_Jurusan', // sesuai dengan database
        'nama_jurusan',
        'kode_jurusan',
        'visi',
        'misi',
    ];

    public function asesi()
    {
        return $this->hasMany(Asesi::class, 'ID_jurusan', 'id_jurusan');
    }
}
