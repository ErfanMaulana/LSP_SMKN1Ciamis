<?php

namespace Database\Seeders\Catalog;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkemaJuniorContentMarketingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Skema: Junior Content Marketing
     * Berdasarkan: FR.APL.02 Asesmen Mandiri
     * 8 Unit Kompetensi | 21 Elemen | 67 Kriteria Unjuk Kerja
     */
    public function run(): void
    {
        $nomorSkema = 'SKM/BNSP/JCM/2023/001';

        if (DB::table('skemas')->where('nomor_skema', $nomorSkema)->exists()) {
            $this->command->info('Skema Junior Content Marketing sudah ada. Seeder dilewati.');
            return;
        }

        // Ambil ID jurusan BDP (Bisnis Daring dan Pemasaran)
        $jurusanId = DB::table('jurusan')
            ->where('kode_jurusan', 'BDP')
            ->value('ID_jurusan');

        if (!$jurusanId) {
            $jurusanId = DB::table('jurusan')
                ->where('nama_jurusan', 'like', '%Bisnis Daring%')
                ->orWhere('nama_jurusan', 'like', '%Pemasaran%')
                ->value('ID_jurusan');
        }

        if (!$jurusanId) {
            $this->command->warn('Jurusan BDP tidak ditemukan! Skema akan dibuat tanpa jurusan.');
        }

        // =====================================================================
        // 1. BUAT SKEMA
        // =====================================================================
        $skemaId = DB::table('skemas')->insertGetId([
            'nomor_skema'  => $nomorSkema,
            'nama_skema'   => 'Junior Content Marketing',
            'jenis_skema'  => 'Okupasi',
            'jurusan_id'   => $jurusanId,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        // =====================================================================
        // UNIT 1: Melakukan Aspek K3 Gerai
        // Kode: G.46RKU00.019.1
        // =====================================================================
        $unit1 = DB::table('units')->insertGetId([
            'skema_id'        => $skemaId,
            'kode_unit'       => 'G.46RKU00.019.1',
            'judul_unit'      => 'Melakukan Aspek Keselamatan dan Kesehatan Kerja (K3) Gerai',
            'pertanyaan_unit' => 'Dapatkah Saya Melakukan Aspek Keselamatan dan Kesehatan Kerja (K3) Gerai?',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Elemen 1.1
        $elemen1_1 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit1,
            'nama_elemen' => 'Menginventarisir potensi hazard di gerai',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen1_1, 'deskripsi_kriteria' => 'Potensi sumber hazard dan risiko serta tingkatan bahaya terhadap manusia dan produk diidentifikasi.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen1_1, 'deskripsi_kriteria' => 'Potensi sumber hazard dan risiko serta tingkatan bahaya terhadap manusia dan produk diidentifikasi sesuai prosedur pengendalian resiko bahaya pada Standar Operasional Prosedur (SOP) Pengendalian Keselamatan dan Kesehatan Kerja (K3).', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 1.2
        $elemen1_2 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit1,
            'nama_elemen' => 'Melaksanakan program pengendalian hazard di gerai',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen1_2, 'deskripsi_kriteria' => 'Ruang lingkup pengendalian risiko Keselamatan dan Kesehatan Kerja (K3) disusun sesuai prosedur.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen1_2, 'deskripsi_kriteria' => 'Cara pengendalian hazard bagi manusia dan produk dibuat sesuai standar Pengendalian K3.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen1_2, 'deskripsi_kriteria' => 'Standar Operasional Prosedur (SOP) pengendalian hazard terkait risiko keselamatan dan kesehatan manusia dan produk dibuat sesuai SOP Pengendalian K3.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen1_2, 'deskripsi_kriteria' => 'Pengendalian hazard dilaksanakan sesuai Standar Operasional Prosedur (SOP).', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 1.3
        $elemen1_3 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit1,
            'nama_elemen' => 'Mengevaluasi pelaksanaan pengendalian keselamatan dan kesehatan kerja (K3)',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen1_3, 'deskripsi_kriteria' => 'Pemantauan pelaksanaan pengendalian hazard dilakukan sesuai Standar Operasional Prosedur (SOP).', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen1_3, 'deskripsi_kriteria' => 'Pelaksanaan program pengendalian hazard terkait Keselamatan dan Kesehatan Kerja (K3) dievaluasi.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // UNIT 2: Merapihkan Area Kerja
        // Kode: G.46RIT00.001.1
        // =====================================================================
        $unit2 = DB::table('units')->insertGetId([
            'skema_id'        => $skemaId,
            'kode_unit'       => 'G.46RIT00.001.1',
            'judul_unit'      => 'Merapihkan Area Kerja',
            'pertanyaan_unit' => 'Dapatkah Saya Merapikan Area Kerja?',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Elemen 2.1
        $elemen2_1 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit2,
            'nama_elemen' => 'Mengatur area kerja',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen2_1, 'deskripsi_kriteria' => 'Area kerja ditata sesuai dengan kebijakan dan prosedur gerai.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen2_1, 'deskripsi_kriteria' => 'Tugas rutin dijalankan sesuai dengan kebijakan dan prosedur gerai.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen2_1, 'deskripsi_kriteria' => 'Barang-barang diletakkan di area/tempat yang ditunjuk sesuai dengan kebijakan dan prosedur gerai.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 2.2
        $elemen2_2 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit2,
            'nama_elemen' => 'Membersihkan area kerja',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen2_2, 'deskripsi_kriteria' => 'Kebijakan dan prosedur gerai mengenai kebersihan pribadi diterapkan pada area kerja.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen2_2, 'deskripsi_kriteria' => 'Pelaksanaan membersihkan area kerja dilaksanakan sesuai dengan kebijakan dan prosedur gerai.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen2_2, 'deskripsi_kriteria' => 'Prosedur penanganan sampah gerai dilaksanakan sesuai dengan kebijakan dan peraturan yang relevan.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen2_2, 'deskripsi_kriteria' => 'Tumpahan, tetesan dari makanan, limbah atau zat bahaya lainnya ditangani sesuai dengan persyaratan Keselamatan dan Kesehatan Kerja (K3) dan prosedur gerai.', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen2_2, 'deskripsi_kriteria' => 'Peralatan dan perlengkapan kerja digunakan sesuai dengan instruksi dan aturan penggunaan dari pabrik.', 'urutan' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen2_2, 'deskripsi_kriteria' => 'Peralatan dan perlengkapan kerja dibersihkan sesuai dengan instruksi dan aturan penggunaan dari pabrik.', 'urutan' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen2_2, 'deskripsi_kriteria' => 'Peralatan dan perlengkapan kerja disimpan sesuai dengan kebijakan dan prosedur gerai.', 'urutan' => 7, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 2.3
        $elemen2_3 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit2,
            'nama_elemen' => 'Menangani potensi bahaya sederhana',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen2_3, 'deskripsi_kriteria' => 'Tumpahan makanan, limbah atau potensi bahaya lain dilaporkan ke personil yang relevan sesuai dengan kebijakan dan prosedur gerai.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen2_3, 'deskripsi_kriteria' => 'Rambu peringatan pada area yang tidak aman dipasang segera.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen2_3, 'deskripsi_kriteria' => 'Alat Pelindung Diri (APD) yang tepat digunakan saat membersihkan area kerja.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // UNIT 3: Merencanakan Riset Terhadap Sebuah Produk dan/atau Merek
        // Kode: M.70MKT00.009.2
        // =====================================================================
        $unit3 = DB::table('units')->insertGetId([
            'skema_id'        => $skemaId,
            'kode_unit'       => 'M.70MKT00.009.2',
            'judul_unit'      => 'Merencanakan Riset Terhadap Sebuah Produk dan/atau Merek',
            'pertanyaan_unit' => 'Dapatkah Saya Merencanakan Riset Terhadap Sebuah Produk dan/atau Merek?',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Elemen 3.1
        $elemen3_1 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit3,
            'nama_elemen' => 'Menentukan kebutuhan dan tujuan riset',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen3_1, 'deskripsi_kriteria' => 'Masalah diidentifikasi berdasarkan ketidaksesuaian terhadap strategi organisasi.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen3_1, 'deskripsi_kriteria' => 'Tujuan riset ditentukan sesuai dengan identifikasi masalah.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 3.2
        $elemen3_2 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit3,
            'nama_elemen' => 'Menentukan metode riset',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen3_2, 'deskripsi_kriteria' => 'Jenis data yang diperlukan diidentifikasi berdasarkan tujuan riset.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen3_2, 'deskripsi_kriteria' => 'Kombinasi jenis data ditentukan berdasarkan tujuan riset.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen3_2, 'deskripsi_kriteria' => 'Metode pengumpulan data diidentifikasi berdasarkan tujuan riset.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen3_2, 'deskripsi_kriteria' => 'Sumber data diidentifikasi berdasarkan tujuan riset.', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen3_2, 'deskripsi_kriteria' => 'Metode pengolahan data diidentifikasi berdasarkan tujuan riset.', 'urutan' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 3.3
        $elemen3_3 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit3,
            'nama_elemen' => 'Mempersiapkan instrumen riset',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen3_3, 'deskripsi_kriteria' => 'Jenis data, kombinasi, metode pengumpulan, sumber, jumlah dan metode pengolahan data ditentukan sesuai dengan tujuan riset.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen3_3, 'deskripsi_kriteria' => 'Sumber daya dan jadwal yang diperlukan untuk riset diidentifikasi sesuai dengan proses penyelenggaraan riset.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen3_3, 'deskripsi_kriteria' => 'Kelayakan riset ditentukan berdasarkan pemenuhan tujuan.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // UNIT 4: Menyusun Informasi Product Knowledge Produk Makanan dan Minuman
        // Kode: G.46RKU00.004.1
        // =====================================================================
        $unit4 = DB::table('units')->insertGetId([
            'skema_id'        => $skemaId,
            'kode_unit'       => 'G.46RKU00.004.1',
            'judul_unit'      => 'Menyusun Informasi Product Knowledge Produk Makanan dan Minuman',
            'pertanyaan_unit' => 'Dapatkah Saya Menyusun Informasi Product Knowledge Produk Makanan dan Minuman?',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Elemen 4.1
        $elemen4_1 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit4,
            'nama_elemen' => 'Mengidentifikasi fitur produk makanan dan minuman',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen4_1, 'deskripsi_kriteria' => 'Indikator spesifikasi kelompok produk makanan dan minuman diidentifikasi sesuai prosedur yang berlaku.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen4_1, 'deskripsi_kriteria' => 'Data dan informasi tentang fitur produk makanan dan minuman dikumpulkan sesuai prosedur yang berlaku.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 4.2
        $elemen4_2 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit4,
            'nama_elemen' => 'Menyiapkan informasi tentang fitur produk makanan dan minuman',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen4_2, 'deskripsi_kriteria' => 'Informasi mengenai rentang (range) produk makanan dan minuman disusun sesuai prosedur.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen4_2, 'deskripsi_kriteria' => 'Fitur produk makanan dan minuman menurut karakteristik produk (bahan baku, umur produk, kandungan produk, penggunaan produk, penangangan produk dan risiko) disiapkan sesuai prosedur.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen4_2, 'deskripsi_kriteria' => 'Fitur produk makanan dan minuman menurut legalitas produk (standar produk dan izin edar) disiapkan sesuai prosedur.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen4_2, 'deskripsi_kriteria' => 'Fitur produk makanan dan minuman menurut benefit produk (fungsi produk, promosi, garansi, layanan purna jual, layanan pengiriman dan merk) disiapkan sesuai prosedur.', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen4_2, 'deskripsi_kriteria' => 'Dokumen tentang informasi product knowledge produk makanan dan minuman disusun sesuai prosedur.', 'urutan' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // UNIT 5: Menyusun Informasi Product Knowledge Produk Fashion
        // Kode: G.46RKU00.007.1
        // =====================================================================
        $unit5 = DB::table('units')->insertGetId([
            'skema_id'        => $skemaId,
            'kode_unit'       => 'G.46RKU00.007.1',
            'judul_unit'      => 'Menyusun Informasi Product Knowledge Produk Fashion',
            'pertanyaan_unit' => 'Dapatkah Saya Menyusun Informasi Product Knowledge Produk Fashion?',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Elemen 5.1
        $elemen5_1 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit5,
            'nama_elemen' => 'Mengidentifikasi fitur produk fashion',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen5_1, 'deskripsi_kriteria' => 'Indikator spesifikasi kelompok produk fashion diidentifikasi sesuai prosedur yang berlaku.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen5_1, 'deskripsi_kriteria' => 'Data dan informasi tentang fitur produk fashion dikumpulkan sesuai prosedur.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 5.2
        $elemen5_2 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit5,
            'nama_elemen' => 'Menyiapkan informasi tentang fitur produk fashion',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen5_2, 'deskripsi_kriteria' => 'Informasi mengenai rentang (range) produk fashion disusun sesuai prosedur.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen5_2, 'deskripsi_kriteria' => 'Fitur produk fashion menurut karakteristik produk (bahan baku, umur produk, kandungan produk, penggunaan produk, penangangan produk dan risiko) disiapkan sesuai prosedur.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen5_2, 'deskripsi_kriteria' => 'Fitur produk fashion menurut legalitas produk (standar produk dan izin edar) disiapkan sesuai prosedur.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen5_2, 'deskripsi_kriteria' => 'Fitur produk fashion menurut benefit produk (fungsi produk, promosi, garansi, layanan purna jual, layanan pengiriman dan merk) disiapkan sesuai prosedur.', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen5_2, 'deskripsi_kriteria' => 'Dokumen tentang informasi product knowledge produk fashion disusun sesuai prosedur.', 'urutan' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // UNIT 6: Mempersiapkan Konten Digital
        // Kode: M.70MKT00.014.1
        // =====================================================================
        $unit6 = DB::table('units')->insertGetId([
            'skema_id'        => $skemaId,
            'kode_unit'       => 'M.70MKT00.014.1',
            'judul_unit'      => 'Mempersiapkan Konten Digital',
            'pertanyaan_unit' => 'Dapatkah Saya Mempersiapkan Konten Digital?',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Elemen 6.1
        $elemen6_1 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit6,
            'nama_elemen' => 'Menentukan kebutuhan konten digital',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen6_1, 'deskripsi_kriteria' => 'Kebutuhan konten tertulis dan visual secara digital ditentukan sesuai tujuan pemasaran.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_1, 'deskripsi_kriteria' => 'Format konten ditinjau untuk menjaga konsistensi.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_1, 'deskripsi_kriteria' => 'Fungsi dan batasan platform diidentifikasi untuk pengembangan konten yang sesuai.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_1, 'deskripsi_kriteria' => 'Platform untuk konten internal dan eksternal ditentukan agar batas penggunaan setiap platform jelas.', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_1, 'deskripsi_kriteria' => 'Informasi produk untuk pengembangan konten disesuaikan dengan tujuan pemasaran.', 'urutan' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_1, 'deskripsi_kriteria' => 'Pengembangan konten direncanakan agar sejalan dengan citra dan aktivitas pemasaran.', 'urutan' => 6, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 6.2
        $elemen6_2 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit6,
            'nama_elemen' => 'Mengembangkan konten tertulis',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen6_2, 'deskripsi_kriteria' => 'Konten tertulis dikembangkan sesuai dengan citra dan aktivitas pemasaran.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_2, 'deskripsi_kriteria' => 'Konten tertulis dihasilkan secara akurat dan rinci.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_2, 'deskripsi_kriteria' => 'Gaya penulisan digunakan dengan jelas sesuai target pasar.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_2, 'deskripsi_kriteria' => 'Teknik penulisan wara (copywriting) digunakan berdasarkan target pasar.', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_2, 'deskripsi_kriteria' => 'Kata kunci digunakan dalam penelusuran daring yang sesuai secara optimal.', 'urutan' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_2, 'deskripsi_kriteria' => 'Susunan kalimat dan ejaan yang benar digunakan sesuai dengan isi pesan.', 'urutan' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_2, 'deskripsi_kriteria' => 'Masukan dari pihak lain yang terlibat dipertimbangkan relevansinya dan perubahan yang diperlukan pada konten tertulis disusun berdasarkan tujuan pemasaran.', 'urutan' => 7, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 6.3
        $elemen6_3 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit6,
            'nama_elemen' => 'Mengembangkan konten visual',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen6_3, 'deskripsi_kriteria' => 'Konten visual yang sesuai digunakan dengan memperhatikan citra dan gaya pemasaran.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_3, 'deskripsi_kriteria' => 'Konten visual diedit guna meningkatkan kualitas dan daya tarik visual.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_3, 'deskripsi_kriteria' => 'Konten visual yang mempromosikan produk dan jasa ditentukan sesuai dengan tujuan.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_3, 'deskripsi_kriteria' => 'Komentar diminta dari pihak terlibat dan perubahan yang perlu dilakukan pada konten visual dibuat mengikuti komentar yang diberikan.', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 6.4
        $elemen6_4 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit6,
            'nama_elemen' => 'Mengunggah konten digital',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen6_4, 'deskripsi_kriteria' => 'Persetujuan terhadap konten tertulis dan visual didapatkan dari atasan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_4, 'deskripsi_kriteria' => 'Konten diunggah ke platform digital dengan syarat file yang sesuai.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_4, 'deskripsi_kriteria' => 'Konten diatur untuk pengalaman pengguna (user experience) yang lebih baik.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_4, 'deskripsi_kriteria' => 'Konten disajikan dalam visual yang sesuai.', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_4, 'deskripsi_kriteria' => 'Masukan dari pihak lain yang terlibat didapatkan untuk mendapatkan penilaian terkait konten tertulis dan visual yang dibuat.', 'urutan' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_4, 'deskripsi_kriteria' => 'Konten dilihat dan diuji pada beberapa perangkat dan perubahan dilakukan jika perlu.', 'urutan' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_4, 'deskripsi_kriteria' => 'Konten diarsipkan dan penelusuran riwayat konten dikontrol secara berkala.', 'urutan' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen6_4, 'deskripsi_kriteria' => 'Penyimpanan konten dan cadangannya dipastikan aman dari pencurian data.', 'urutan' => 8, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // UNIT 7: Melakukan Aktivitas Pemasaran Digital
        // Kode: G.46RKU00.025.1
        // =====================================================================
        $unit7 = DB::table('units')->insertGetId([
            'skema_id'        => $skemaId,
            'kode_unit'       => 'G.46RKU00.025.1',
            'judul_unit'      => 'Melakukan Aktivitas Pemasaran Digital',
            'pertanyaan_unit' => 'Dapatkah Saya Melakukan Aktivitas Pemasaran Digital?',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Elemen 7.1
        $elemen7_1 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit7,
            'nama_elemen' => 'Menyiapkan aktivitas pemasaran digital bisnis ritel',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen7_1, 'deskripsi_kriteria' => 'Spesifikasi kebutuhan pelanggan sesuai dengan segmen pelanggan diidentifikasi.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen7_1, 'deskripsi_kriteria' => 'Tren perkembangan pemasaran digital dan manfaat penggunaannya bagi bisnis ritel diidentifikasi.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen7_1, 'deskripsi_kriteria' => 'Rencana aktivitas pemasaran digital disusun sesuai prosedur.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 7.2
        $elemen7_2 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit7,
            'nama_elemen' => 'Melaksanakan aktivitas pemasaran digital bisnis ritel',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen7_2, 'deskripsi_kriteria' => 'Aktivitas pemasaran digital bisnis ritel dilakukan sesuai dengan rencana dan prosedur perusahaan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen7_2, 'deskripsi_kriteria' => 'Aktivitas pemasaran digital dimonitor dan dievaluasi sesuai dengan prosedur Perusahaan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen7_2, 'deskripsi_kriteria' => 'Laporan pelaksanaan dan hasil aktivitas pemasaran digital disusun untuk dilaporkan kepada pimpinan.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // =====================================================================
        // UNIT 8: Menciptakan Pengalaman Bagi Pengguna Media Digital
        // Kode: M.70MKT00.016.1
        // =====================================================================
        $unit8 = DB::table('units')->insertGetId([
            'skema_id'        => $skemaId,
            'kode_unit'       => 'M.70MKT00.016.1',
            'judul_unit'      => 'Menciptakan Pengalaman Bagi Pengguna Media Digital',
            'pertanyaan_unit' => 'Dapatkah Saya Menciptakan Pengalaman Bagi Pengguna Media Digital?',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        // Elemen 8.1
        $elemen8_1 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit8,
            'nama_elemen' => 'Menentukan Tujuan',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen8_1, 'deskripsi_kriteria' => 'Tujuan ditentukan untuk menjalin keterikatan di media digital.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen8_1, 'deskripsi_kriteria' => 'Karakteristik produk, brand, atau organisasi ditentukan berdasarkan strategi organisasi.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen8_1, 'deskripsi_kriteria' => 'Kerangka undang-undang, peraturan, dan kebijakan yang relevan diidentifikasi sesuai strategi organisasi.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 8.2
        $elemen8_2 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit8,
            'nama_elemen' => 'Mengklasifikasi pengguna dan tipe percakapan',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen8_2, 'deskripsi_kriteria' => 'Pengguna diklasifikasi menurut pola keterikatan (engagement pattern), keadaan sosial-ekonomi, dan karakteristik media digital yang digunakan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen8_2, 'deskripsi_kriteria' => 'Tipe percakapan diidentifikasi melalui analisis data penggunaan media digital.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen8_2, 'deskripsi_kriteria' => 'Tipe percakapan berdasarkan perangkat, platform, dan maksud penggunaan didapatkan secara sistematis.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Elemen 8.3
        $elemen8_3 = DB::table('elemens')->insertGetId([
            'unit_id'     => $unit8,
            'nama_elemen' => 'Menggambarkan pengalaman pelanggan dan perjalanan pelanggan di media digital',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $elemen8_3, 'deskripsi_kriteria' => 'Komunitas digital (digital enclaves) dan pola keterikatan (engagement) pengguna diidentifikasi kesesuaiannya dengan tujuan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen8_3, 'deskripsi_kriteria' => 'Titik kontak digital diklasifikasikan dengan produk, merek, atau organisasi.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen8_3, 'deskripsi_kriteria' => 'Model perjalanan pengguna (user journey) yang tidak umum dibuat untuk dijadikan peluang penambahan pengguna.', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $elemen8_3, 'deskripsi_kriteria' => 'Lokasi, gaya, dan pemicu intervensi dalam perjalanan pengguna (user journey) dipilih untuk menciptakan pengalaman penggunaan terbaik.', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->command->info('Skema Junior Content Marketing berhasil di-seed!');
        $this->command->info('   - 8 Unit Kompetensi');
        $this->command->info('   - 21 Elemen Kompetensi');
        $this->command->info('   - 67 Kriteria Unjuk Kerja');
    }
}
