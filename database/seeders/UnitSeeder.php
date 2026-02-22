<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID skema "Okupasi Pemrogram Junior (Junior Coder)"
        $skemaId = DB::table('skemas')
            ->where('nomor_skema', 'SKM/BNSP/00010/2/2023/1324')
            ->value('id');

        if (!$skemaId) {
            $this->command->error('Skema tidak ditemukan! Jalankan SkemaSeeder terlebih dahulu.');
            return;
        }

        $units = [
            [
                'skema_id' => $skemaId,
                'kode_unit' => 'J.620100.004.01',
                'judul_unit' => 'Menggunakan Struktur Data',
                'pertanyaan_unit' => 'Dapatkah Saya menggunakan Struktur Data?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skema_id' => $skemaId,
                'kode_unit' => 'J.620100.009.02',
                'judul_unit' => 'Menggunakan Spesifikasi Program',
                'pertanyaan_unit' => 'Dapatkah Saya menggunakan Spesifikasi Program?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skema_id' => $skemaId,
                'kode_unit' => 'J.620100.010.01',
                'judul_unit' => 'Menerapkan Perintah Eksekusi Bahasa Pemrograman Berbasis Teks, Grafik, dan Multimedia',
                'pertanyaan_unit' => 'Dapatkah Saya menerapkan Perintah Eksekusi Bahasa Pemrograman Berbasis Teks, Grafik, dan Multimedia?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skema_id' => $skemaId,
                'kode_unit' => 'J.620100.016.01',
                'judul_unit' => 'Menulis Kode Dengan Prinsip Sesuai Guidelines dan Best Practices',
                'pertanyaan_unit' => 'Dapatkah Saya menulis Kode Dengan Prinsip Sesuai Guidelines dan Best Practices?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skema_id' => $skemaId,
                'kode_unit' => 'J.620100.017.02',
                'judul_unit' => 'Mengimplementasikan Pemrograman Terstruktur',
                'pertanyaan_unit' => 'Dapatkah Saya mengimplementasikan Pemrograman Terstruktur?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skema_id' => $skemaId,
                'kode_unit' => 'J.620100.023.02',
                'judul_unit' => 'Membuat Dokumen Kode Program',
                'pertanyaan_unit' => 'Dapatkah Saya melakukan pembuatan dokumen kode program?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skema_id' => $skemaId,
                'kode_unit' => 'J.620100.025.02',
                'judul_unit' => 'Melakukan Debugging',
                'pertanyaan_unit' => 'Dapatkah Saya melakukan Debugging?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'skema_id' => $skemaId,
                'kode_unit' => 'J.620900.033.02',
                'judul_unit' => 'Melaksanakan Pengujian unit Program',
                'pertanyaan_unit' => 'Dapatkah Saya melakukan Pengujian unit Program?',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('units')->insert($units);
        
        $this->command->info('8 Unit Kompetensi berhasil ditambahkan!');
    }
}
