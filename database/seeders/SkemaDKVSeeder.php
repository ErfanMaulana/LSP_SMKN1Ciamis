<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkemaDKVSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID jurusan DKV
        $jurusanDKV = DB::table('jurusan')
            ->where('kode_jurusan', 'DKV')
            ->value('ID_jurusan');

        if (!$jurusanDKV) {
            $this->command->warn('Jurusan DKV tidak ditemukan! Skema akan dibuat tanpa jurusan.');
        }

        // 1. Buat skema Content Creator Junior untuk DKV
        $skemaId = DB::table('skemas')->insertGetId([
            'nomor_skema' => 'SKM/BNSP/00010/2/2023/999',
            'nama_skema' => 'Content Creator Junior',
            'jenis_skema' => 'Okupasi',
            'jurusan_id' => $jurusanDKV,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Unit Kompetensi 1: Melakukan Riset Kreatif Multimedia
        $unitId1 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'J.59MTM00.002.1',
            'judul_unit' => 'Melakukan Riset Kreatif Multimedia',
            'pertanyaan_unit' => 'Dapatkah Saya Melakukan riset kreatif multimedia?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 1.1: Melakukan riset konten
        $elemenId1_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Melakukan riset konten',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Data riset konten multimedia dikumpulkan',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Data riset konten multimedia dianalisa',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Rekomendasi konten multimedia dihasilkan',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 1.2: Melakukan riset teknologi
        $elemenId1_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Melakukan riset teknologi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Data riset teknologi multimedia dikumpulkan',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Data riset teknologi multimedia dianalisa',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Rekomendasi teknologi multimedia dihasilkan',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 1.3: Melakukan riset kebutuhan user
        $elemenId1_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Melakukan riset kebutuhan user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_3,
                'deskripsi_kriteria' => 'Data riset kebutuhan user multimedia dikumpulkan',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_3,
                'deskripsi_kriteria' => 'Data riset kebutuhan user multimedia dianalisa',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_3,
                'deskripsi_kriteria' => 'Rekomendasi kebutuhan user multimedia dihasilkan',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. Unit Kompetensi 2: Menyusun Creative Brief
        $unitId2 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'J.59MTM00.004.1',
            'judul_unit' => 'Menyusun Creative Brief',
            'pertanyaan_unit' => 'Dapatkah Saya Menyusun Creative Brief?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 2.1: Mengidentifikasi kebutuhan kreatif stakeholder
        $elemenId2_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Mengidentifikasi kebutuhan kreatif stakeholder',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Detail kebutuhan stakeholder terkait audio dan visual diidentifikasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Hasil penyampaian kreatif konsep dikembangkan sesuai dengan kebutuhan stakeholder',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 2.2: Menyusun arahan kreatif
        $elemenId2_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Menyusun arahan kreatif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_2,
                'deskripsi_kriteria' => 'Hasil pengembangan konsep kreatif dirumuskan menjadi bahan standart output',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_2,
                'deskripsi_kriteria' => 'Standart pekerjaan kreatif (creative brief) dibuat untuk menjadi acuan produksi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 4. Unit Kompetensi 3: Membuat Aset Visual
        $unitId3 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'J.59MTM00.011.1',
            'judul_unit' => 'Membuat Aset Visual Berdasarkan Langkah Kerja Yang Telah Ditetapkan',
            'pertanyaan_unit' => 'Dapatkah Saya Membuat Aset Visual Berdasarkan Langkah Kerja Yang Telah Ditetapkan?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 3.1: Memproduksi aset visual Multimedia
        $elemenId3_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Memproduksi aset visual Multimedia',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Prosedur pengerjaan asset visual teridentifikasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Aset visual multimedia di-produksi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Penyimpanan asset secara berkala dilakukan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 3.2: Mereview hasil desain
        $elemenId3_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Mereview hasil desain',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Hasil kerja dievaluasi secara berkala.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Tuntutan perbaikan visual dipersiapkan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Proses perbaikan dilakukan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 5. Unit Kompetensi 4: Membuat Aset Audio
        $unitId4 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'J.59MTM00.015.1',
            'judul_unit' => 'Membuat Aset Audio Berdasarkan Langkah Kerja Yang Telah Ditetapkan',
            'pertanyaan_unit' => 'Dapatkah Saya Membuat Aset Audio Berdasarkan Langkah Kerja Yang Telah Ditetapkan?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 4.1: Memproduksi aset audio Multimedia
        $elemenId4_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Memproduksi aset audio Multimedia',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Prosedur pengerjaan teridentifikasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Aset audio multimedia diproduksi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Penyimpanan secara berkala dilakukan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 4.2: Mereview hasil produksi audio
        $elemenId4_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Mereview hasil produksi audio',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Hasil kerja direview secara berkala.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Tuntutan perbaikan visual dipersiapkan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Proses perbaikan dilakukan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 6. Unit Kompetensi 5: Mengintegrasikan Komponen Multimedia
        $unitId5 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'J.59MTM00.018.1',
            'judul_unit' => 'Mengintegrasikan Seluruh Komponen Multimedia Terkait Audio Dan Visual',
            'pertanyaan_unit' => 'Dapatkah Saya Mengintegrasikan Seluruh Komponen Multimedia Terkait Audio Dan Visual?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 5.1: Mengidentifikasi metode integrasi komponen multimedia
        $elemenId5_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Mengidentifikasi metode integrasi komponen multimedia',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Metode integrasi teknis multimedia diuraikan sesuai kebutuhan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Metode integrasi dipilih sesuai dengan kebutuhan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 5.2: Menentukan sistem integrasi komponen multimedia dengan kecerdasan buatan
        $elemenId5_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Menentukan sistem integrasi komponen multimedia dengan kecerdasan buatan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Sistem integrasi direncanakan sesuai metode yang dipilih.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Sistem integrasi dipilih sesuai dengan metode intergrasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✓ Skema Content Creator Junior untuk DKV berhasil dibuat!');
        $this->command->info('✓ 5 Unit Kompetensi berhasil ditambahkan!');
        $this->command->info('✓ 11 Elemen berhasil ditambahkan!');
        $this->command->info('✓ 29 Kriteria Unjuk Kerja berhasil ditambahkan!');
    }
}
