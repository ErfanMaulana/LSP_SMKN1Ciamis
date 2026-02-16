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
                'nama_jurusan' => 'Teknik Komputer dan Jaringan',
                'kode_jurusan' => 'TKJ',
            ],
            [
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'kode_jurusan' => 'RPL',
            ],
            [
                'nama_jurusan' => 'Multimedia',
                'kode_jurusan' => 'MM',
            ],
            [
                'nama_jurusan' => 'Teknik Kendaraan Ringan',
                'kode_jurusan' => 'TKR',
            ],
            [
                'nama_jurusan' => 'Teknik Sepeda Motor',
                'kode_jurusan' => 'TSM',
            ],
        ];

        foreach ($jurusan as $data) {
            Jurusan::create($data);
        }
    }
}
