<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asesor;

class AsesorSeeder extends Seeder
{
    public function run(): void
    {
        $asesors = [
            ['nama' => 'Andi Saputra',    'no_met' => 'MET-001'],
            ['nama' => 'Siti Aminah',     'no_met' => 'MET-002'],
            ['nama' => 'Rizky Ramadhan',  'no_met' => 'MET-003'],
            ['nama' => 'Dewi Lestari',    'no_met' => 'MET-004'],
        ];

        foreach ($asesors as $data) {
            Asesor::firstOrCreate(['nama' => $data['nama']], $data);
        }

        $this->command->info('AsesorSeeder completed.');
    }
}
