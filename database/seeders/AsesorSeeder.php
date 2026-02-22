<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asesor;
use App\Models\Mitra;

class AsesorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mitra = Mitra::all();

        $asesorData = [
            ['nama' => 'Andi Saputra', 'expertise' => 'Software Engineering - Level II'],
            ['nama' => 'Siti Aminah', 'expertise' => 'Cloud Infrastructure Specialist'],
            ['nama' => 'Rizky Ramadhan', 'expertise' => 'Automotive Engine Maintenance'],
            ['nama' => 'Dewi Lestari', 'expertise' => 'Network Systems Admin'],
        ];

        foreach ($asesorData as $data) {
            if (!Asesor::where('nama', $data['nama'])->exists()) {
                Asesor::create([
                    'nama' => $data['nama'],
                    'ID_skema' => rand(1000, 9999),
                    'no_mou' => $mitra->isNotEmpty() ? $mitra->random()->no_mou : null,
                ]);
            }
        }

        $this->command->info('Asesor seeder completed successfully!');
    }
}
