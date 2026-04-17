<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RekamanAsesmenKompetensi;

class RekamanAsesmenKompetensiDetail extends Model
{
    protected $table = 'rekaman_asesmen_kompetensi_details';

    protected $fillable = [
        'rekaman_id',
        'unit_id',
        'observasi_demonstrasi',
        'portofolio',
        'pernyataan_pihak_ketiga',
        'pertanyaan_lisan',
        'pertanyaan_tertulis',
        'proyek_kerja',
        'lainnya',
    ];

    protected $casts = [
        'observasi_demonstrasi' => 'boolean',
        'portofolio' => 'boolean',
        'pernyataan_pihak_ketiga' => 'boolean',
        'pertanyaan_lisan' => 'boolean',
        'pertanyaan_tertulis' => 'boolean',
        'proyek_kerja' => 'boolean',
        'lainnya' => 'boolean',
    ];

    public function rekaman()
    {
        return $this->belongsTo(RekamanAsesmenKompetensi::class, 'rekaman_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
