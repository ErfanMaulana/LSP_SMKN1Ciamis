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
        'no_reg',
        'nama',
        'email',
        'ID_jurusan',
        'kelas',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'kebangsaan',
        'kewarganegaraan',
        'kode_kota',
        'kode_provinsi',
        'telepon_rumah',
        'telepon_hp',
        'kode_pos',
        'pendidikan_terakhir',
        'pekerjaan',
        'nama_lembaga',
        'alamat_lembaga',
        'jabatan',
        'no_fax_lembaga',
        'email_lembaga',
        'unit_lembaga',
        'pas_foto',
        'identitas_pribadi',
        'bukti_kompetensi',
        'transkrip_nilai',
        'kode_kementrian',
        'kode_anggaran',
        'status',
        'catatan_admin',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'datetime',
        'verified_at'   => 'datetime',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'ID_jurusan', 'ID_jurusan');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    public function buktiPendukung()
    {
        return $this->hasMany(BuktiPendukung::class, 'NIK', 'NIK');
    }

    public function transkripNilai()
    {
        return $this->hasMany(BuktiPendukung::class, 'NIK', 'NIK')
                    ->where('jenis_dokumen', 'transkrip_nilai');
    }

    public function identitasPribadi()
    {
        return $this->hasMany(BuktiPendukung::class, 'NIK', 'NIK')
                    ->where('jenis_dokumen', 'identitas_pribadi');
    }

    public function buktiKompetensi()
    {
        return $this->hasMany(BuktiPendukung::class, 'NIK', 'NIK')
                    ->where('jenis_dokumen', 'bukti_kompetensi');
    }

    public function skemas()
    {
        return $this->belongsToMany(Skema::class, 'asesi_skema', 'asesi_nik', 'skema_id')
                    ->withPivot('status', 'tanggal_mulai', 'tanggal_selesai')
                    ->withTimestamps();
    }

    public function jawabanElemen()
    {
        return $this->hasMany(JawabanElemen::class, 'asesi_nik', 'NIK');
    }
}
