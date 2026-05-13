<?php

namespace Database\Seeders\Catalog;

use Illuminate\Database\Seeder;
use App\Models\Skema;

class CombinedSkemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skemas = [
            // Core skema list (subset copied from existing SkemaSeeder)
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
            // (You can extend this list by copying remaining entries from SkemaSeeder.php)
        ];

        // Include specialized skema from dedicated seeders (e.g. AKL)
        $skemas[] = [
            'nomor_skema' => 'SKM/BNSP/00013/1/2020/32',
            'nama_skema' => 'KKNI Level II Akuntansi dan Keuangan Lembaga',
            'jenis_skema' => 'KKNI',
        ];

        foreach ($skemas as $data) {
            Skema::updateOrCreate(
                ['nomor_skema' => $data['nomor_skema']],
                array_merge($data, ['updated_at' => now()])
            );
            $this->command->info(sprintf("Skema '%s' processed.", $data['nomor_skema']));
        }
    }
}
