<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asesi;
use App\Models\Jurusan;
use Faker\Factory as Faker;

class AsesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $jurusan = Jurusan::all();

        if ($jurusan->isEmpty()) {
            $this->command->warn('No jurusan found. Please run JurusanSeeder first.');
            return;
        }

        $asesiData = [
            ['nama' => 'Andi Saputra', 'email' => 'andi.saputra@student.smkn1ciamis.sch.id'],
            ['nama' => 'Siti Aminah', 'email' => 'siti.aminah@student.smkn1ciamis.sch.id'],
            ['nama' => 'Rizky Ramadhan', 'email' => 'rizky.ramadhan@student.smkn1ciamis.sch.id'],
            ['nama' => 'Budi Wijaya', 'email' => 'budi.wijaya@student.smkn1ciamis.sch.id'],
            ['nama' => 'Dewi Kusuma', 'email' => 'dewi.kusuma@student.smkn1ciamis.sch.id'],
        ];

        foreach ($asesiData as $index => $data) {
            $nik = '3207' . str_pad($index + 1, 12, '0', STR_PAD_LEFT);
            
            Asesi::create([
                'NIK' => $nik,
                'nama' => $data['nama'],
                'email' => $data['email'],
                'ID_jurusan' => $jurusan->random()->ID_jurusan,
                'kelas' => 'XII ' . ['RPL', 'TKJ', 'MM'][array_rand(['RPL', 'TKJ', 'MM'])],
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->dateTimeBetween('-18 years', '-16 years')->format('Y-m-d'),
                'alamat' => $faker->address,
                'kebangsaan' => 'Indonesia',
                'kode_kota' => '3207',
                'kode_provinsi' => '32',
                'telepon_rumah' => $faker->phoneNumber,
                'telepon_hp' => '08' . $faker->numerify('##########'),
                'kode_pos' => $faker->postcode,
                'pendidikan_terakhir' => 'SMK',
                'kode_kementrian' => 'KEMENDIKBUD',
                'kode_anggaran' => 'APBN',
            ]);
        }

        $this->command->info('Asesi seeder completed successfully!');
    }
}
