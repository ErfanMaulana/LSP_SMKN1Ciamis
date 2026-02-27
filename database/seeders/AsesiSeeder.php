<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AsesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusanNames = ['PPLG', 'DKV', 'AKL', 'KLN', 'MPLB', 'PM', 'HTL'];
        $tempat_lahir = ['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Semarang', 'Makassar', 'Yogyakarta', 'Palembang', 'Bogor', 'Bekasi'];
        $kelas = ['X A', 'X B', 'XI A', 'XI B', 'XII A', 'XII B'];

        $firstNames = ['Andi', 'Siti', 'Rizky', 'Budi', 'Dewi', 'Achmad', 'Nisa', 'Rudi', 'Siana', 'Toni', 'Udin', 'Vina', 'Wayan', 'Xenia', 'Yani', 'Zuri', 'Alfian', 'Bella', 'Choirul', 'Desy'];
        $lastNames = ['Saputra', 'Aminah', 'Ramadhan', 'Wijaya', 'Kusuma', 'Rahman', 'Putri', 'Setiawan', 'Handoko', 'Wulandari', 'Santoso', 'Pratama', 'Gunawan', 'Hermawan', 'Irawan', 'Jakarta', 'Kusuma', 'Lestari', 'Mansur', 'Nurdin'];

        $asesiCount = 0;

        // Mapping jurusan names to ID (from database)
        $jurusanIdMap = [
            'PPLG' => 1,
            'DKV' => 2,
            'AKL' => 3,
            'KLN' => 4,
            'MPLB' => 5,
            'PM' => 6,
            'HTL' => 7,
        ];

        // Distribution: PPLG=6, DKV=5, AKL=5, KLN=5, MPLB=5, PM=5, HTL=5
        $distribution = [
            'PPLG' => 6,
            'DKV' => 5,
            'AKL' => 5,
            'KLN' => 5,
            'MPLB' => 5,
            'PM' => 5,
            'HTL' => 5,
        ];

        foreach ($jurusanNames as $jurusanName) {
            $idJurusan = $jurusanIdMap[$jurusanName];
            $count = $distribution[$jurusanName];

            for ($i = 1; $i <= $count; $i++) {
                $asesiCount++;
                $nik = '3207' . str_pad($asesiCount, 12, '0', STR_PAD_LEFT);
                
                // Skip if NIK already exists
                if (DB::table('asesi')->where('NIK', $nik)->exists()) {
                    continue;
                }

                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $nama = "$firstName $lastName";

                DB::table('asesi')->insert([
                    'NIK' => $nik,
                    'nama' => $nama,
                    'email' => strtolower(str_replace(' ', '.', $nama)) . "@student.smkn1ciamis.sch.id",
                    'ID_jurusan' => $idJurusan,
                    'kelas' => $kelas[array_rand($kelas)],
                    'tempat_lahir' => $tempat_lahir[array_rand($tempat_lahir)],
                    'tanggal_lahir' => Carbon::now()->subYears(rand(16, 18))->subDays(rand(0, 365))->toDateString(),
                    'alamat' => 'Jl. ' . $tempat_lahir[array_rand($tempat_lahir)] . ' No. ' . rand(1, 999),
                    'kebangsaan' => 'Indonesia',
                    'kode_kota' => '3207',
                    'kode_provinsi' => '32',
                    'telepon_hp' => '08' . rand(10000000000, 99999999999),
                    'kode_pos' => '40' . rand(100, 999),
                    'pendidikan_terakhir' => 'SMP',
                    'kode_kementrian' => 'KEMENDIKBUD',
                    'kode_anggaran' => 'APBN',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $this->command->info("Created asesi #{$asesiCount}: {$nama} ({$jurusanName})");
            }
        }

        $this->command->info("Total {$asesiCount} asesi data created!");
    }
}
