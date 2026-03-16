<?php

namespace Database\Seeders\Testing;

use App\Models\Account;
use App\Models\Asesor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AsesorSeeder extends Seeder
{
    public function run(): void
    {
        $asesors = [
            ['nama' => 'Andi Saputra', 'no_met' => 'MET-001', 'nik' => '1234567890123456', 'password' => 'MET-001'],
            ['nama' => 'Siti Aminah', 'no_met' => 'MET-002', 'nik' => '1234567890123457', 'password' => 'MET-002'],
            ['nama' => 'Rizky Ramadhan', 'no_met' => 'MET-003', 'nik' => '1234567890123458', 'password' => 'MET-003'],
            ['nama' => 'Dewi Lestari', 'no_met' => 'MET-004', 'nik' => '1234567890123459', 'password' => 'MET-004'],
        ];

        foreach ($asesors as $data) {
            Asesor::firstOrCreate(
                ['nama' => $data['nama']],
                [
                    'nama' => $data['nama'],
                    'no_met' => $data['no_met'],
                ]
            );

            Account::updateOrCreate(
                ['id' => $data['no_met']],
                [
                    'id' => $data['no_met'],
                    'nama' => $data['nama'],
                    'NIK' => $data['nik'],
                    'password' => Hash::make($data['password']),
                    'role' => 'asesor',
                ]
            );
        }

        $this->command->info('AsesorSeeder completed.');
        $this->command->info('Asesor & Account data created successfully!');
    }
}