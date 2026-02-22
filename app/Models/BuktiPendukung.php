<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuktiPendukung extends Model
{
    use HasFactory;

    protected $table = 'bukti_pendukung';

    protected $fillable = [
        'NIK',
        'jenis_dokumen',
        'file_path',
        'nama_file',
    ];

    public function asesi()
    {
        return $this->belongsTo(Asesi::class, 'NIK', 'NIK');
    }
}
