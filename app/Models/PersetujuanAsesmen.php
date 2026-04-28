<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersetujuanAsesmen extends Model
{
    protected $table = 'persetujuan_asesmen';

    protected $fillable = [
        'kode_form',
        'judul_form',
        'pengantar',
        'kategori_skema',
        'judul_skema',
        'nomor_skema',
        'tuk',
        'nama_asesor',
        'nama_asesi',
        'bukti_verifikasi_portofolio',
        'bukti_reviu_produk',
        'bukti_observasi_langsung',
        'bukti_kegiatan_terstruktur',
        'bukti_pertanyaan_lisan',
        'bukti_pertanyaan_tertulis',
        'bukti_pertanyaan_wawancara',
        'bukti_lainnya',
        'bukti_lainnya_keterangan',
        'hari_tanggal',
        'waktu',
        'tuk_pelaksanaan',
        'pernyataan_asesi_1',
        'pernyataan_asesor',
        'pernyataan_asesi_2',
        'ttd_asesor_nama',
        'ttd_asesor_tanggal',
        'ttd_asesi_nama',
        'ttd_asesi_tanggal',
        'catatan_footer',
    ];

    protected $casts = [
        'bukti_verifikasi_portofolio' => 'boolean',
        'bukti_reviu_produk' => 'boolean',
        'bukti_observasi_langsung' => 'boolean',
        'bukti_kegiatan_terstruktur' => 'boolean',
        'bukti_pertanyaan_lisan' => 'boolean',
        'bukti_pertanyaan_tertulis' => 'boolean',
        'bukti_pertanyaan_wawancara' => 'boolean',
        'bukti_lainnya' => 'boolean',
        'ttd_asesor_tanggal' => 'date',
        'ttd_asesi_tanggal' => 'date',
    ];
}
