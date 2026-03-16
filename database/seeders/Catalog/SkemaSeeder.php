<?php

namespace Database\Seeders\Catalog;

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
            [
                'nomor_skema' => 'SKM/BNSP/00011/2/2023/1325',
                'nama_skema' => 'Content Creator Junior',
                'jenis_skema' => 'Okupasi',
            ],
            [
                'nomor_skema' => 'KLASTER/COOK/2023/01',
                'nama_skema' => 'Helper Cookery',
                'jenis_skema' => 'Klaster',
            ],
            [
                'nomor_skema' => 'SKM/BNSP/00012/2/2023/1326',
                'nama_skema' => 'Okupasi Office Administrative',
                'jenis_skema' => 'Okupasi',
            ],
            [
                'nomor_skema' => 'KLASTER/KAS/2023/01',
                'nama_skema' => 'Trainee Kasir',
                'jenis_skema' => 'Klaster',
            ],
            [
                'nomor_skema' => 'KKNI/2023/003',
                'nama_skema' => 'KKNI Level II Akuntansi dan Keuangan Lembaga',
                'jenis_skema' => 'KKNI',
            ],
            [
                'nomor_skema' => 'SKM/BNSP/00013/2/2023/1327',
                'nama_skema' => 'Junior Database Administrator',
                'jenis_skema' => 'Okupasi',
            ],
            [
                'nomor_skema' => 'KKNI/2023/004',
                'nama_skema' => 'Digital Marketing Specialist Level II',
                'jenis_skema' => 'KKNI',
            ],
            [
                'nomor_skema' => 'KLASTER/MEC/2023/01',
                'nama_skema' => 'Teknisi Mekanik Otomotif',
                'jenis_skema' => 'Klaster',
            ],
            [
                'nomor_skema' => 'SKM/BNSP/00014/2/2023/1328',
                'nama_skema' => 'Okupasi UI/UX Designer',
                'jenis_skema' => 'Okupasi',
            ],
            [
                'nomor_skema' => 'KKNI/2023/005',
                'nama_skema' => 'Cyber Security Analyst Level II',
                'jenis_skema' => 'KKNI',
            ],
            [
                'nomor_skema' => 'KLASTER/ELC/2023/01',
                'nama_skema' => 'Teknisi Elektronika',
                'jenis_skema' => 'Klaster',
            ],
            [
                'nomor_skema' => 'SKM/BNSP/00015/2/2023/1329',
                'nama_skema' => 'Mobile App Developer',
                'jenis_skema' => 'Okupasi',
            ],
            [
                'nomor_skema' => 'KKNI/2023/006',
                'nama_skema' => 'Data Analyst Level II',
                'jenis_skema' => 'KKNI',
            ],
            [
                'nomor_skema' => 'KLASTER/HOSP/2023/01',
                'nama_skema' => 'Front Office Hospitality',
                'jenis_skema' => 'Klaster',
            ],
            [
                'nomor_skema' => 'SKM/BNSP/00016/2/2023/1330',
                'nama_skema' => 'Video Editor Professional',
                'jenis_skema' => 'Okupasi',
            ],
            [
                'nomor_skema' => 'KKNI/2023/007',
                'nama_skema' => 'Cloud Infrastructure Engineer',
                'jenis_skema' => 'KKNI',
            ],
            [
                'nomor_skema' => 'KLASTER/SALE/2023/01',
                'nama_skema' => 'Sales Marketing Executive',
                'jenis_skema' => 'Klaster',
            ],
        ];

        foreach ($skemas as $skema) {
            if (!Skema::where('nomor_skema', $skema['nomor_skema'])->exists()) {
                Skema::create($skema);
            }
        }
    }
}
