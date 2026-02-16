<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesi extends Model
{
    use HasFactory;

    protected $table = 'asesi';
    protected $primaryKey = 'NIK';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'NIK',
        'nama',
        'email',
        'ID_jurusan',
        'kelas',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'kebangsaan',
        'kode_kota',
        'kode_provinsi',
        'telepon_rumah',
        'telepon_hp',
        'kode_pos',
        'pendidikan_terakhir',
        'kode_kementrian',
        'kode_anggaran',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'ID_jurusan', 'ID_jurusan');
    }
}
