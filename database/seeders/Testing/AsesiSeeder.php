<?php

namespace Database\Seeders\Testing;

use App\Models\Account;
use App\Models\Asesi;
use App\Models\Jurusan;
use App\Models\Skema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AsesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusanCodes = ['PPLG', 'DKV', 'AKL', 'KLN', 'MPLB', 'PM', 'HTL'];
        $distribution = [
            'PPLG' => 8,
            'DKV' => 6,
            'AKL' => 6,
            'KLN' => 6,
            'MPLB' => 6,
            'PM' => 6,
            'HTL' => 6,
        ];
        $tempatLahir = ['Ciamis', 'Banjar', 'Tasikmalaya', 'Garut', 'Bandung', 'Pangandaran'];
        $kelas = ['XII A', 'XII B', 'XII 1', 'XII 2'];
        $firstNames = ['Aldi', 'Bella', 'Cahya', 'Dinda', 'Eko', 'Fajar', 'Gita', 'Hana', 'Ilham', 'Jihan', 'Kiki', 'Lukman', 'Mira', 'Nanda', 'Oki', 'Putri', 'Qori', 'Raka', 'Salsa', 'Tegar'];
        $lastNames = ['Saputra', 'Lestari', 'Pratama', 'Azzahra', 'Firmansyah', 'Ramadhan', 'Kusuma', 'Pertiwi', 'Wijaya', 'Maulana'];
        $statusPattern = ['belum_mulai', 'sedang_mengerjakan', 'selesai'];
        $now = now();
        $createdCount = 0;

        $jurusans = Jurusan::query()
            ->whereIn('kode_jurusan', $jurusanCodes)
            ->get(['ID_jurusan', 'kode_jurusan'])
            ->keyBy('kode_jurusan');

        $skemaByJurusan = Skema::query()
            ->orderBy('nama_skema')
            ->get(['id', 'jurusan_id'])
            ->groupBy('jurusan_id');

        foreach ($jurusanCodes as $jurusanIndex => $jurusanCode) {
            $jurusan = $jurusans->get($jurusanCode);
            if (!$jurusan) {
                $this->command->warn("Jurusan {$jurusanCode} belum tersedia. Seeder asesi untuk jurusan ini dilewati.");
                continue;
            }

            $skemas = $skemaByJurusan->get($jurusan->ID_jurusan, collect())->values();
            $totalPerJurusan = $distribution[$jurusanCode] ?? 5;

            for ($i = 1; $i <= $totalPerJurusan; $i++) {
                $seedNumber = ($jurusanIndex + 1) * 100 + $i;
                $nik = '3207' . str_pad((string) $seedNumber, 12, '0', STR_PAD_LEFT);
                $nama = $firstNames[($seedNumber - 1) % count($firstNames)] . ' ' . $lastNames[($seedNumber + $jurusanIndex) % count($lastNames)];
                $tanggalLahir = Carbon::create(2007 + ($i % 2), (($i + $jurusanIndex) % 12) + 1, min(28, 10 + $i))->toDateString();
                $telepon = '08' . str_pad((string) (8120000000 + $seedNumber), 10, '0', STR_PAD_LEFT);
                $noReg = 'ASESI-' . $jurusanCode . '-' . str_pad((string) $i, 3, '0', STR_PAD_LEFT);

                Asesi::updateOrCreate(
                    ['NIK' => $nik],
                    [
                        'no_reg' => $noReg,
                        'nama' => $nama,
                        'email' => strtolower(str_replace(' ', '.', $nama)) . '@student.smkn1ciamis.sch.id',
                        'ID_jurusan' => $jurusan->ID_jurusan,
                        'kelas' => $kelas[($i - 1) % count($kelas)],
                        'tempat_lahir' => $tempatLahir[($i + $jurusanIndex) % count($tempatLahir)],
                        'tanggal_lahir' => $tanggalLahir,
                        'jenis_kelamin' => $i % 2 === 0 ? 'Perempuan' : 'Laki-laki',
                        'alamat' => 'Jl. Sekolah No. ' . (10 + $seedNumber) . ', Ciamis',
                        'kebangsaan' => 'Indonesia',
                        'kewarganegaraan' => 'WNI',
                        'kode_kota' => '3207',
                        'kode_provinsi' => '32',
                        'telepon_hp' => $telepon,
                        'kode_pos' => '46211',
                        'pendidikan_terakhir' => 'SMP/MTs',
                        'pekerjaan' => 'Pelajar',
                        'nama_lembaga' => 'SMKN 1 Ciamis',
                        'alamat_lembaga' => 'Ciamis, Jawa Barat',
                        'jabatan' => 'Siswa',
                        'kode_kementrian' => 'KEMENDIKBUD',
                        'kode_anggaran' => 'APBN',
                        'status' => 'approved',
                        'verified_at' => $now,
                    ]
                );

                Account::updateOrCreate(
                    ['NIK' => $nik],
                    [
                        'id' => $nik,
                        'NIK' => $nik,
                        'nama' => $nama,
                        'password' => Hash::make($nik),
                        'role' => 'asesi',
                    ]
                );

                if ($skemas->isNotEmpty()) {
                    $skema = $skemas[($i - 1) % $skemas->count()];
                    $statusAsesmen = $statusPattern[($i - 1) % count($statusPattern)];
                    $tanggalMulai = $statusAsesmen === 'belum_mulai' ? null : $now->copy()->subDays(10 + $i);
                    $tanggalSelesai = $statusAsesmen === 'selesai' ? $now->copy()->subDays(($i % 4) + 1) : null;

                    DB::table('asesi_skema')->updateOrInsert(
                        [
                            'asesi_nik' => $nik,
                            'skema_id' => $skema->id,
                        ],
                        [
                            'status' => $statusAsesmen,
                            'tanggal_mulai' => $tanggalMulai,
                            'tanggal_selesai' => $tanggalSelesai,
                            'rekomendasi' => $statusAsesmen === 'selesai'
                                ? (($i + $jurusanIndex) % 2 === 0 ? 'lanjut' : 'tidak_lanjut')
                                : null,
                            'catatan_asesor' => $statusAsesmen === 'selesai'
                                ? 'Hasil seeded untuk kebutuhan testing alur asesmen.'
                                : null,
                            'reviewed_at' => $statusAsesmen === 'selesai' ? $now->copy()->subDay() : null,
                            'reviewed_by' => null,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]
                    );
                }

                $createdCount++;
            }
        }

        $this->command->info("AsesiSeeder selesai. {$createdCount} data asesi beserta akun login berhasil disiapkan.");
        $this->command->info('Login asesi sample: identifier = NIK, password awal = NIK.');
    }
}