<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mitra;
use Carbon\Carbon;

class MitraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mitra = [
            [
                'no_mou' => 'MOU-001/2025',
                'nama_mitra' => 'PT Telkom Indonesia',
                'jenis_usaha' => 'Telekomunikasi',
                'tanggal_mou' => Carbon::parse('2025-01-15'),
                'tanggal_berakhir' => Carbon::parse('2027-01-15'),
            ],
            [
                'no_mou' => 'MOU-002/2025',
                'nama_mitra' => 'PT Astra International',
                'jenis_usaha' => 'Otomotif',
                'tanggal_mou' => Carbon::parse('2025-02-01'),
                'tanggal_berakhir' => Carbon::parse('2027-02-01'),
            ],
            [
                'no_mou' => 'MOU-003/2025',
                'nama_mitra' => 'CV Digital Media Solutions',
                'jenis_usaha' => 'IT & Software Development',
                'tanggal_mou' => Carbon::parse('2025-03-10'),
                'tanggal_berakhir' => Carbon::parse('2027-03-10'),
            ],
        ];

        foreach ($mitra as $data) {
            Mitra::firstOrCreate(
                ['no_mou' => $data['no_mou']],
                $data
            );
        }
    }
}
