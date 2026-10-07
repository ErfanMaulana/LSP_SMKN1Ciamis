<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RekamanAsesmenKompetensi;
use App\Models\PersetujuanAsesmen;

class Asesi extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->isDirty('tanda_tangan_pendaftar') && $model->tanda_tangan_pendaftar) {
                $model->tanda_tangan = $model->tanda_tangan_pendaftar;
            }
        });
    }

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
        'ID_asesor',
        'kelompok_id',
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
        'tanda_tangan_pendaftar',
        'tanggal_tanda_tangan_pendaftar',
        'tanda_tangan_admin',
        'tanggal_tanda_tangan_admin',
        'verifikasi_bukti_persyaratan_dasar',
        'verifikasi_bukti_administratif',
        'tanda_tangan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'datetime',
        'verified_at'   => 'datetime',
        'tanggal_tanda_tangan_pendaftar' => 'datetime',
        'tanggal_tanda_tangan_admin'     => 'datetime',
        'verifikasi_bukti_persyaratan_dasar' => 'array',
        'verifikasi_bukti_administratif' => 'array',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'ID_jurusan', 'ID_jurusan');
    }

    public function asesor()
    {
        return $this->belongsTo(Asesor::class, 'ID_asesor', 'ID_asesor');
    }

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
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
                    ->withPivot('status', 'tanggal_mulai', 'tanggal_selesai', 'rekomendasi', 'catatan_asesor', 'reviewed_at', 'reviewed_by', 'tanda_tangan', 'tanggal_tanda_tangan')
                    ->withTimestamps();
    }

    public function currentAttempt(?int $skemaId = null): int
    {
        $query = \Illuminate\Support\Facades\DB::table('asesi_skema')
            ->where('asesi_nik', $this->NIK);
        if ($skemaId) {
            $query->where('skema_id', $skemaId);
        }
        return (int) ($query->max('attempt') ?? 1);
    }

    public function hasCompletedUjikom(): bool
    {
        return RekamanAsesmenKompetensi::where('asesi_nik', $this->NIK)
            ->where('attempt', $this->currentAttempt())
            ->whereNotNull('tanggal_selesai')
            ->where(function($q) {
                $q->where('tanggal_selesai', '<=', now())
                  ->orWhere(function($sub) {
                      $sub->whereNotNull('ttd_asesi_file')
                          ->where('ttd_asesi_file', '!=', '');
                  });
            })
            ->exists();
    }

    public function hasCompletedUjikomForSkema(int|string $skemaId): bool
    {
        return RekamanAsesmenKompetensi::where('asesi_nik', $this->NIK)
            ->where('skema_id', $skemaId)
            ->where('attempt', $this->currentAttempt($skemaId))
            ->whereNotNull('tanggal_selesai')
            ->where(function($q) {
                $q->where('tanggal_selesai', '<=', now())
                  ->orWhere(function($sub) {
                      $sub->whereNotNull('ttd_asesi_file')
                          ->where('ttd_asesi_file', '!=', '');
                  });
            })
            ->exists();
    }

    public function hasRekomendasiLanjut(): bool
    {
        return $this->skemas()
            ->wherePivot('attempt', $this->currentAttempt())
            ->wherePivot('rekomendasi', 'lanjut')
            ->exists();
    }

    public function hasRekomendasiLanjutForSkema(int|string $skemaId): bool
    {
        return $this->skemas()
            ->where('skemas.id', $skemaId)
            ->wherePivot('attempt', $this->currentAttempt($skemaId))
            ->wherePivot('rekomendasi', 'lanjut')
            ->exists();
    }

    public function persetujuanAsesmens()
    {
        return $this->hasMany(PersetujuanAsesmen::class, 'asesi_nik', 'NIK');
    }

    public function hasSignedPersetujuanAsesmen(?int $skemaId = null): bool
    {
        $query = $this->persetujuanAsesmens()
            ->where('attempt', $this->currentAttempt($skemaId))
            ->whereNotNull('ttd_asesi_nama')
            ->where('ttd_asesi_nama', '!=', '')
            ->whereNotNull('ttd_asesi_tanggal');

        if ($skemaId) {
            $skema = Skema::find($skemaId);
            if ($skema) {
                $query->where('nomor_skema', $skema->nomor_skema);
            }
        }

        return $query->exists();
    }

    /**
     * Check if Persetujuan Asesmen (FR.AK.01) is ready to be used / accessed by asesi.
     * Ready means:
     * - Asesor has already filled evidence checklist AND signed,
     * OR asesi has already signed it.
     */
    public function isPersetujuanAsesmenReady(?int $skemaId = null): bool
    {
        $useNik = \Illuminate\Support\Facades\Schema::hasColumn('persetujuan_asesmen', 'asesi_nik');

        $isReadyQuery = function ($nomorSkema, $attempt) use ($useNik) {
            return PersetujuanAsesmen::where('nomor_skema', $nomorSkema)
                ->where('attempt', $attempt)
                ->where(function ($q) use ($useNik) {
                    if ($useNik && !empty($this->NIK)) {
                        $q->where('asesi_nik', $this->NIK);
                    } else {
                        $q->where('nama_asesi', $this->nama);
                    }
                })
                ->where(function ($q) {
                    // Already signed by asesi
                    $q->where(function ($sub) {
                        $sub->whereNotNull('ttd_asesi_nama')
                            ->where('ttd_asesi_nama', '!=', '')
                            ->whereNotNull('ttd_asesi_tanggal');
                    })
                    // Or asesor has completed checklist and signed
                    ->orWhere(function ($sub) {
                        $sub->whereNotNull('ttd_asesor_nama')
                            ->where('ttd_asesor_nama', '!=', '')
                            ->whereNotNull('ttd_asesor_tanggal')
                            ->where(function ($bukti) {
                                $bukti->where('bukti_verifikasi_portofolio', 1)
                                      ->orWhere('bukti_reviu_produk', 1)
                                      ->orWhere('bukti_observasi_langsung', 1)
                                      ->orWhere('bukti_kegiatan_terstruktur', 1)
                                      ->orWhere('bukti_pertanyaan_lisan', 1)
                                      ->orWhere('bukti_pertanyaan_tertulis', 1)
                                      ->orWhere('bukti_pertanyaan_wawancara', 1)
                                      ->orWhere('bukti_lainnya', 1);
                            });
                    });
                })
                ->exists();
        };

        if ($skemaId) {
            $skema = Skema::find($skemaId);
            if (!$skema) {
                return false;
            }
            return $isReadyQuery($skema->nomor_skema, $this->currentAttempt($skemaId));
        }

        // Check registered skemas for this candidate
        $registeredSkemas = \Illuminate\Support\Facades\DB::table('asesi_skema')
            ->join('skemas', 'asesi_skema.skema_id', '=', 'skemas.id')
            ->where('asesi_skema.asesi_nik', $this->NIK)
            ->whereRaw('asesi_skema.attempt = (SELECT MAX(b.attempt) FROM asesi_skema b WHERE b.asesi_nik = asesi_skema.asesi_nik AND b.skema_id = asesi_skema.skema_id)')
            ->select('skemas.id', 'skemas.nomor_skema', 'asesi_skema.attempt')
            ->get();

        if ($registeredSkemas->isNotEmpty()) {
            foreach ($registeredSkemas as $rs) {
                if ($isReadyQuery($rs->nomor_skema, $rs->attempt)) {
                    return true;
                }
            }
            return false;
        }

        // Fallback if no pivot records yet
        return PersetujuanAsesmen::where('attempt', $this->currentAttempt())
            ->where(function ($q) use ($useNik) {
                if ($useNik && !empty($this->NIK)) {
                    $q->where('asesi_nik', $this->NIK);
                } else {
                    $q->where('nama_asesi', $this->nama);
                }
            })
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNotNull('ttd_asesi_nama')
                        ->where('ttd_asesi_nama', '!=', '')
                        ->whereNotNull('ttd_asesi_tanggal');
                })
                ->orWhere(function ($sub) {
                    $sub->whereNotNull('ttd_asesor_nama')
                        ->where('ttd_asesor_nama', '!=', '')
                        ->whereNotNull('ttd_asesor_tanggal')
                        ->where(function ($bukti) {
                            $bukti->where('bukti_verifikasi_portofolio', 1)
                                  ->orWhere('bukti_reviu_produk', 1)
                                  ->orWhere('bukti_observasi_langsung', 1)
                                  ->orWhere('bukti_kegiatan_terstruktur', 1)
                                  ->orWhere('bukti_pertanyaan_lisan', 1)
                                  ->orWhere('bukti_pertanyaan_tertulis', 1)
                                  ->orWhere('bukti_pertanyaan_wawancara', 1)
                                  ->orWhere('bukti_lainnya', 1);
                        });
                });
            })
            ->exists();
    }

    public function jawabanElemen()
    {
        return $this->hasMany(JawabanElemen::class, 'asesi_nik', 'NIK');
    }

    public function account()
    {
        return $this->hasOne(Account::class, 'NIK', 'NIK');
    }
}
