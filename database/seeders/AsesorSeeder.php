<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asesor;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class AsesorSeeder extends Seeder
    {
        public function run(): void
        {
            $asesors = [
                ['nama' => 'Andi Saputra',    'no_met' => 'MET-001', 'nik' => '1234567890123456', 'password' => 'MET-001'],
                ['nama' => 'Siti Aminah',     'no_met' => 'MET-002', 'nik' => '1234567890123457', 'password' => 'MET-002'],
                ['nama' => 'Rizky Ramadhan',  'no_met' => 'MET-003', 'nik' => '1234567890123458', 'password' => 'MET-003'],
                ['nama' => 'Dewi Lestari',    'no_met' => 'MET-004', 'nik' => '1234567890123459', 'password' => 'MET-004 '],
            ];

            foreach ($asesors as $data) {
                // Create Asesor
                Asesor::firstOrCreate(
                    ['nama' => $data['nama']],
                    [
                        'nama' => $data['nama'],
                        'no_met' => $data['no_met'],
                    ]
                );

                // Create Account for login
                Account::firstOrCreate(
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
