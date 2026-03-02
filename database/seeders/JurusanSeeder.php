<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jurusan;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jurusan = [
            
            [
                'nama_jurusan' => 'Akuntansi dan Keuangan Lembaga',
                'kode_jurusan' => 'AKL',
            ],
            [
                'nama_jurusan' => 'Desain Komunikasi Visual',
                'kode_jurusan' => 'DKV',
            ],
            [
                'nama_jurusan' => 'Manajemen Perkantoran dan Layanan Bisnis',
                'kode_jurusan' => 'MPLB',
            ],
            [
                'nama_jurusan' => 'Pemasaran',
                'kode_jurusan' => 'PM',
            ],
            [
                'nama_jurusan' => 'Pengembangan Perangkat Lunak dan Gim',
                'kode_jurusan' => 'PPLG',
            ],
            [
                'nama_jurusan' => 'Perhotelan',
                'kode_jurusan' => 'HTL',
            ],
        ];

        foreach ($jurusan as $data) {
            Jurusan::firstOrCreate(
                ['kode_jurusan' => $data['kode_jurusan']], 
                $data
            );
        }
    }
}
