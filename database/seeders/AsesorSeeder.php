<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asesor;

class AsesorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skemas = \App\Models\Skema::all();

        if ($skemas->isEmpty()) {
            $this->command->warn('No skema found. Please run SkemaSeeder first.');
            return;
        }

        $asesorData = [
            ['nama' => 'Andi Saputra', 'no_reg' => 'ASRO01126'],
            ['nama' => 'Siti Aminah', 'no_reg' => 'ASRO01124'],
            ['nama' => 'Rizky Ramadhan', 'no_reg' => 'ASRO01122'],
            ['nama' => 'Dewi Lestari', 'no_reg' => 'ASRO01121'],
            ['nama' => 'Fairuz Azmi', 'no_reg' => 'ASRO01123'],
            ['nama' => 'Erfan Eka Maulana', 'no_reg' => 'ASRO01125'],
            ['nama' => 'Budi Santoso', 'no_reg' => 'ASRO01127'],
            ['nama' => 'Nur Hidayah', 'no_reg' => 'ASRO01128'],
            ['nama' => 'Ahmad Fauzi', 'no_reg' => 'ASRO01129'],
            ['nama' => 'Linda Wijaya', 'no_reg' => 'ASRO01130'],
            ['nama' => 'Hendra Gunawan', 'no_reg' => 'ASRO01131'],
            ['nama' => 'Maya Sari', 'no_reg' => 'ASRO01132'],
            ['nama' => 'Arif Rahman', 'no_reg' => 'ASRO01133'],
            ['nama' => 'Putri Wulandari', 'no_reg' => 'ASRO01134'],
            ['nama' => 'Dimas Prasetyo', 'no_reg' => 'ASRO01135'],
            ['nama' => 'Rina Marlina', 'no_reg' => 'ASRO01136'],
            ['nama' => 'Yanto Hermawan', 'no_reg' => 'ASRO01137'],
            ['nama' => 'Sari Indah', 'no_reg' => 'ASRO01138'],
            ['nama' => 'Rudi Hartono', 'no_reg' => 'ASRO01139'],
            ['nama' => 'Devi Anggraini', 'no_reg' => 'ASRO01140'],
        ];

        foreach ($asesorData as $index => $data) {
            if (!Asesor::where('no_reg', $data['no_reg'])->exists()) {
                // Assign different skema to each asesor
                $skema = $skemas[$index % $skemas->count()];
                
                Asesor::create([
                    'nama' => $data['nama'],
                    'no_reg' => $data['no_reg'],
                    'ID_skema' => $skema->id,
                    'no_mou' => null,
                ]);

                // Create account for asesor
                \App\Models\Account::create([
                    'id' => $data['no_reg'],
                    'password' => \Illuminate\Support\Facades\Hash::make($data['no_reg']),
                    'role' => 'asesor',
                ]);
            }
        }

        $this->command->info('Asesor seeder completed successfully! 20 asesor with different expertise added.');
    }
}
