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
        'Nama_Jurusan',
        'nama_jurusan',
        'kode_jurusan',
        'visi',
        'misi',
    ];

    /**
     * Accessor so $model->nama_jurusan works regardless of DB column case.
     */
    public function getNamaJurusanAttribute($value)
    {
        return $value ?? ($this->attributes['Nama_Jurusan'] ?? null);
    }

    public function asesi()
    {
        return $this->hasMany(Asesi::class, 'ID_jurusan', 'ID_jurusan');
    }
}
