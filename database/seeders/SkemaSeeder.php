<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skema;

class SkemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skemas = [
            [
                'nomor_skema' => 'SKM/BNSP/00010/2/2023/1324',
                'nama_skema' => 'Okupasi Pemrogram Junior (Junior Coder)',
                'jenis_skema' => 'Okupasi',
            ],
            [
                'nomor_skema' => 'KKNI/2023/001',
                'nama_skema' => 'Teknisi Jaringan Komputer Level II',
                'jenis_skema' => 'KKNI',
            ],
            [
                'nomor_skema' => 'KLASTER/RPL/2023/01',
                'nama_skema' => 'Klaster Pengembangan Web',
                'jenis_skema' => 'Klaster',
            ],
            [
                'nomor_skema' => 'SKM/0072/00025/1/2023/2',
                'nama_skema' => 'Okupasi Desainer Grafis',
                'jenis_skema' => 'Okupasi',
            ],
            [
                'nomor_skema' => 'KKNI/2023/002',
                'nama_skema' => 'Teknisi Komputer Level III',
                'jenis_skema' => 'KKNI',
            ],
        ];

        foreach ($skemas as $skema) {
            Skema::create($skema);
        }
    }
}
