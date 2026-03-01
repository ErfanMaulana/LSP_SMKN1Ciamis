<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AsesiRPLSeeder extends Seeder
{
    /**
     * 36 asesi jurusan RPL (ID_jurusan = 2) beserta akun login.
     * No Reg format : 2526-02-XXXX  (TA 2025/2026, kode jurusan 02, urut 4 digit)
     * NIK format    : 3207XXXXXXXXXX (kode kota Ciamis 3207)
     * Password      : password (bcrypt)
     */
    public function run(): void
    {
        $namaList = [
            ['nama' => 'Aldi Firmansyah',      'jk' => 'Laki-laki',  'tl' => 'Ciamis',     'tgl' => '2007-03-12'],
            ['nama' => 'Anisa Rahayu',          'jk' => 'Perempuan',  'tl' => 'Tasikmalaya','tgl' => '2007-06-20'],
            ['nama' => 'Bagas Prasetyo',        'jk' => 'Laki-laki',  'tl' => 'Banjar',     'tgl' => '2007-09-05'],
            ['nama' => 'Citra Dewi Lestari',    'jk' => 'Perempuan',  'tl' => 'Ciamis',     'tgl' => '2008-01-18'],
            ['nama' => 'Dandi Nugraha',         'jk' => 'Laki-laki',  'tl' => 'Pangandaran','tgl' => '2007-11-30'],
            ['nama' => 'Elsa Putri Kusuma',     'jk' => 'Perempuan',  'tl' => 'Garut',      'tgl' => '2007-04-07'],
            ['nama' => 'Fajar Maulana',         'jk' => 'Laki-laki',  'tl' => 'Ciamis',     'tgl' => '2008-02-14'],
            ['nama' => 'Gita Apriani',          'jk' => 'Perempuan',  'tl' => 'Tasikmalaya','tgl' => '2007-07-22'],
            ['nama' => 'Hendra Setiawan',       'jk' => 'Laki-laki',  'tl' => 'Banjar',     'tgl' => '2007-05-10'],
            ['nama' => 'Indah Permatasari',     'jk' => 'Perempuan',  'tl' => 'Ciamis',     'tgl' => '2008-03-28'],
            ['nama' => 'Jovan Rizaldi',         'jk' => 'Laki-laki',  'tl' => 'Pangandaran','tgl' => '2007-08-15'],
            ['nama' => 'Kartika Sari',          'jk' => 'Perempuan',  'tl' => 'Garut',      'tgl' => '2007-12-03'],
            ['nama' => 'Lukman Hakim',          'jk' => 'Laki-laki',  'tl' => 'Ciamis',     'tgl' => '2008-04-19'],
            ['nama' => 'Maya Nurfadilah',       'jk' => 'Perempuan',  'tl' => 'Tasikmalaya','tgl' => '2007-02-26'],
            ['nama' => 'Naufal Ardiansyah',     'jk' => 'Laki-laki',  'tl' => 'Banjar',     'tgl' => '2007-10-08'],
            ['nama' => 'Olivia Listiani',       'jk' => 'Perempuan',  'tl' => 'Ciamis',     'tgl' => '2008-05-14'],
            ['nama' => 'Putra Ramadhan',        'jk' => 'Laki-laki',  'tl' => 'Pangandaran','tgl' => '2007-01-23'],
            ['nama' => 'Qonita Azzahra',        'jk' => 'Perempuan',  'tl' => 'Garut',      'tgl' => '2007-09-11'],
            ['nama' => 'Rafi Hidayat',          'jk' => 'Laki-laki',  'tl' => 'Ciamis',     'tgl' => '2008-06-30'],
            ['nama' => 'Salsabila Nuraini',     'jk' => 'Perempuan',  'tl' => 'Tasikmalaya','tgl' => '2007-03-17'],
            ['nama' => 'Taufik Hidayat',        'jk' => 'Laki-laki',  'tl' => 'Banjar',     'tgl' => '2007-07-04'],
            ['nama' => 'Umi Kalsum',            'jk' => 'Perempuan',  'tl' => 'Ciamis',     'tgl' => '2008-01-09'],
            ['nama' => 'Vicky Kurniawan',       'jk' => 'Laki-laki',  'tl' => 'Pangandaran','tgl' => '2007-11-16'],
            ['nama' => 'Windi Ramadhani',       'jk' => 'Perempuan',  'tl' => 'Garut',      'tgl' => '2007-04-25'],
            ['nama' => 'Xena Pahlevi',          'jk' => 'Perempuan',  'tl' => 'Ciamis',     'tgl' => '2008-07-03'],
            ['nama' => 'Yogi Pratama',          'jk' => 'Laki-laki',  'tl' => 'Tasikmalaya','tgl' => '2007-06-12'],
            ['nama' => 'Zahra Nur Azizah',      'jk' => 'Perempuan',  'tl' => 'Banjar',     'tgl' => '2007-10-29'],
            ['nama' => 'Arif Budianto',         'jk' => 'Laki-laki',  'tl' => 'Ciamis',     'tgl' => '2008-02-06'],
            ['nama' => 'Bella Safitri',         'jk' => 'Perempuan',  'tl' => 'Pangandaran','tgl' => '2007-08-21'],
            ['nama' => 'Chandra Wijaya',        'jk' => 'Laki-laki',  'tl' => 'Garut',      'tgl' => '2007-05-17'],
            ['nama' => 'Dina Anggraeni',        'jk' => 'Perempuan',  'tl' => 'Ciamis',     'tgl' => '2008-03-11'],
            ['nama' => 'Eko Saputra',           'jk' => 'Laki-laki',  'tl' => 'Tasikmalaya','tgl' => '2007-12-24'],
            ['nama' => 'Fitria Handayani',      'jk' => 'Perempuan',  'tl' => 'Banjar',     'tgl' => '2007-01-15'],
            ['nama' => 'Gilang Ramadhan',       'jk' => 'Laki-laki',  'tl' => 'Ciamis',     'tgl' => '2008-04-08'],
            ['nama' => 'Hana Maulida',          'jk' => 'Perempuan',  'tl' => 'Pangandaran','tgl' => '2007-09-27'],
            ['nama' => 'Irfan Maulana',         'jk' => 'Laki-laki',  'tl' => 'Garut',      'tgl' => '2007-07-19'],
        ];

        $kelas = ['XII RPL 1', 'XII RPL 2'];
        $hashedPw = Hash::make('password');
        $now = now();

        foreach ($namaList as $i => $data) {
            $urut   = str_pad($i + 1, 4, '0', STR_PAD_LEFT);
            $noReg  = '252602' . $urut;                               // 2526 = TA, 02 = RPL, urut

            // NIK = 320712 + DDMMYY (female: DD+40) + 4-digit-seq
            [$year, $month, $day] = explode('-', $data['tgl']);
            $dd  = (int) $day + ($data['jk'] === 'Perempuan' ? 40 : 0);
            $nik = '320712' . str_pad($dd, 2, '0', STR_PAD_LEFT) . substr($month, 0, 2) . substr($year, 2, 2) . $urut;

            // Skip if already exists
            if (DB::table('asesi')->where('NIK', $nik)->exists()) continue;
            if (DB::table('accounts')->where('id', $noReg)->exists()) continue;

            DB::table('asesi')->insert([
                'NIK'                 => $nik,
                'no_reg'              => $noReg,
                'nama'                => $data['nama'],
                'email'               => strtolower(str_replace(' ', '.', $data['nama'])) . '@student.smkn1ciamis.sch.id',
                'ID_jurusan'          => 2,
                'kelas'               => $kelas[$i % 2],
                'tempat_lahir'        => $data['tl'],
                'tanggal_lahir'       => $data['tgl'],
                'jenis_kelamin'       => $data['jk'],
                'alamat'              => 'Jl. ' . ['Sudirman', 'Diponegoro', 'Veteran', 'Merdeka', 'Pemuda', 'Pahlawan'][$i % 6]
                                          . ' No. ' . ($i + 1) . ', Ciamis',
                'kebangsaan'          => 'Indonesia',
                'kewarganegaraan'     => 'WNI',
                'kode_kota'           => '3207',
                'kode_provinsi'       => '32',
                'kode_pos'            => '46200',
                'telepon_hp'          => '0812' . str_pad($i + 10000000, 8, '0', STR_PAD_LEFT),
                'pendidikan_terakhir' => 'SMA/SMK',
                'pekerjaan'           => 'Pelajar',
                'nama_lembaga'        => 'SMKN 1 Ciamis',
                'alamat_lembaga'      => 'Jl. Jend. Sudirman No. 1, Ciamis',
                'jabatan'             => 'Siswa',
                'kode_kementrian'     => 'KEMENDIKBUD',
                'kode_anggaran'       => 'APBN',
                'status'              => 'approved',
                'verified_at'         => $now,
                'created_at'          => $now,
                'updated_at'          => $now,
            ]);

            DB::table('accounts')->insert([
                'id'           => $noReg,
                'password'     => $hashedPw,
                'role'         => 'asesi',
                'created_at'   => $now,
                'updated_at'   => $now,
            ]);
        }

        $this->command->info('✅ 36 asesi RPL berhasil dibuat. Login: no_reg = 252602XXXX | password = password');
    }
}
