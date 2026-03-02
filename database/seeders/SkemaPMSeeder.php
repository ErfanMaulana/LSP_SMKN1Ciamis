<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkemaPMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Skema: Trainee Kasir untuk Jurusan Pemasaran (PM)
     * 10 Unit Kompetensi
     */
    public function run(): void
    {
        // Ambil ID jurusan Pemasaran (PM)
        $jurusanPM = DB::table('jurusan')
            ->where('kode_jurusan', 'PM')
            ->value('ID_jurusan');

        if (!$jurusanPM) {
            $this->command->warn('Jurusan PM tidak ditemukan! Skema akan dibuat tanpa jurusan.');
        }

        // 1. Buat skema Trainee Kasir untuk PM
        $skemaId = DB::table('skemas')->insertGetId([
            'nomor_skema' => 'SKM/BNSP/00007/2/2023/761',
            'nama_skema' => 'Trainee Kasir',
            'jenis_skema' => 'Okupasi',
            'jurusan_id' => $jurusanPM,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ========== UNIT 1: Merapihkan Area Kerja ==========
        $unitId1 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'G.46RIT00.001.1',
            'judul_unit' => 'Merapihkan Area Kerja',
            'pertanyaan_unit' => 'Dapatkah Saya Merapikan Area Kerja?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 1.1
        $elemenId1_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Mengatur area kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Area kerja ditata sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Tugas rutin dijalankan sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Barang-barang diletakkan di area/tempat yang ditunjuk sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 1.2
        $elemenId1_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Membersihkan area kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Kebijakan dan prosedur gerai mengenai kebersihan pribadi diterapkan pada area kerja.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Pelaksanaan membersihkan area kerja dilaksanakan sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Prosedur penanganan sampah gerai dilaksanakan sesuai dengan kebijakan dan peraturan yang relevan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Tumpahan, tetesan dari makanan, limbah atau zat bahaya lainnya ditangani sesuai dengan persyaratan Keselamatan dan Kesehatan Kerja (K3) dan prosedur gerai.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Peralatan dan perlengkapan kerja digunakan sesuai dengan instruksi dan aturan penggunaan dari pabrik.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Peralatan dan perlengkapan kerja dibersihkan sesuai dengan instruksi dan aturan penggunaan dari pabrik.',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Peralatan dan perlengkapan kerja disimpan sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 1.3
        $elemenId1_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Menangani potensi bahaya sederhana',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_3,
                'deskripsi_kriteria' => 'Tumpahan makanan, limbah atau potensi bahaya lain dilaporkan ke personil yang relevan sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_3,
                'deskripsi_kriteria' => 'Rambu peringatan pada area yang tidak aman dipasang segera.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_3,
                'deskripsi_kriteria' => 'Alat Pelindung Diri (APD) yang tepat digunakan saat membersihkan area kerja.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 2: Menjalankan Tugas dan Tanggung Jawab Pribadi ==========
        $unitId2 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'G.46RIT00.002.1',
            'judul_unit' => 'Menjalankan Tugas dan Tanggung Jawab Pribadi',
            'pertanyaan_unit' => 'Dapatkah Saya Menjalankan Tugas dan Tanggung Jawab Pribadi?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 2.1
        $elemenId2_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Mengidentifikasi tugas dan tanggung jawab kerja pribadi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Tugas-tugas yang harus diselesaikan diidentifikasi sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Instruksi kerja yang diperlukan untuk menyelesaikan tugas-tugas dicatat sesuai.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Kegiatan kerja harian dalam lingkup tanggung jawab direncanakan sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Tugas-tugas disusun menjadi bagian yang lebih kecil agar dapat dikerjakan dengan lebih mudah sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 2.2
        $elemenId2_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Menyusun urutan tugas sesuai dengan jadwal kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_2,
                'deskripsi_kriteria' => 'Tugas-tugas diurutkan sesuai skala prioritas berdasarkan kebijakan dan prosedur gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_2,
                'deskripsi_kriteria' => 'Tugas-tugas dilengkapi dengan jadwal yang spesifik dan tuntutan standar dan kualitasnya.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 2.3
        $elemenId2_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Mendiskusikan perubahan tugas dan tanggung jawab kepada atasan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_3,
                'deskripsi_kriteria' => 'Perubahan-perubahan pada tuntutan kerja diidentifikasi sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_3,
                'deskripsi_kriteria' => 'Perubahan yang mempengaruhi penyelesaian tugas saat ini dikomunikasikan kepada personil yang relevan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 3: Membangun Perilaku Kerja Ritel yang Efektif ==========
        $unitId3 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'G.46RIT00.004.1',
            'judul_unit' => 'Membangun Perilaku Kerja Ritel yang Efektif',
            'pertanyaan_unit' => 'Dapatkah Saya Membangun Perilaku Kerja Ritel yang Efektif?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 3.1
        $elemenId3_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Melakukan pekerjaan sesuai persyaratan perusahaan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Persyaratan dan tanggung jawab pekerjaan dilakukan sesuai kebijakan dan prosedur gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Saran diminta dari orang yang tepat ketika terjadi ketidaksesuaian pekerjaan dengan kebijakan dan prosedur gerai.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Ketentuan waktu kerja dilaksanakan sesuai dengan kebijakan dan prosedur perusahaan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Pengetahuan dan ketrampilan dikembangkan untuk menerapkan hak dan tanggung jawab karyawan maupun perusahaan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Tugas dan tanggung jawab yang relevan dipatuhi untuk menjaga budaya perusahaan.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Standar dan nilai-nilai yang dapat merugikan perusahaan dikomunikasikan melalui jalur yang telah ditetapkan.',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Perilaku yang berkontribusi atas lingkungan kerja yang nyaman, diidentifikasi untuk diterapkan.',
                'urutan' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 3.2
        $elemenId3_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Mendukung tim kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Sikap sopan dan suka membantu dalam lingkungan kerja ditunjukkan untuk meningkatkan kinerja tim.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Bantuan kepada rekan kerja diberikan setiap saat sepanjang tidak mengganggu pekerjaan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Perintah dan tanggung jawab dilaksanakan sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Hubungan dengan pelanggan dan rekan kerja dijaga agar tidak menimbulkan perselisihan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 3.3
        $elemenId3_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Menjaga penampilan pribadi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_3,
                'deskripsi_kriteria' => 'Penampilan dan cara berpakaian disesuaikan dengan persyaratan di tempat kerja.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_3,
                'deskripsi_kriteria' => 'Kebersihan pribadi dijaga sesuai kebijakan dan prosedur gerai.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 3.4
        $elemenId3_4 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Mengembangkan kebiasaan kerja yang efektif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_4,
                'deskripsi_kriteria' => 'Perintah dan prosedur kerja dilaksanakan sesuai ketentuan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_4,
                'deskripsi_kriteria' => 'Cara berfikir dan bertindak terkait dengan anti-diskriminasi, pelecehan seksual dan intimidasi dilakukan sesuai dengan ketentuan hukum yang berlaku.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_4,
                'deskripsi_kriteria' => 'Pertanyaan diajukan untuk mencari dan mengklarifikasi informasi di tempat kerja.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_4,
                'deskripsi_kriteria' => 'Tugas dan pekerjaan rutin sehari-hari diatur sesuai peran kerja.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_4,
                'deskripsi_kriteria' => 'Tugas-tugas diselesaikan sesuai skala prioritas waktu yang dibuat.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_4,
                'deskripsi_kriteria' => 'Kepentingan pekerjaan dan kepentingan pribadi diidentifikasi untuk mencapai keseimbangan prioritas.',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 4: Berkomunikasi Aktif dalam Lingkungan Kerja Ritel ==========
        $unitId4 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'G.46RIT00.008.1',
            'judul_unit' => 'Berkomunikasi Aktif dalam Lingkungan Kerja Ritel',
            'pertanyaan_unit' => 'Dapatkah Saya Berkomunikasi Aktif dalam Lingkungan Kerja Ritel?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 4.1
        $elemenId4_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Melakukan komunikasi tatap muka dengan pelanggan dan rekan kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Komunikasi dengan pelanggan yang mencerminkan citra gerai dijaga sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Pelanggan disapa sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Teknik berkomunikasi langsung diterapkan untuk menentukan kebutuhan pelanggan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Kepercayaan diri dan kecekatan dalam bekerja ditunjukkan sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 4.2
        $elemenId4_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Menggunakan teknologi untuk berkomunikasi dengan pelanggan dan rekan kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Teknik berkomunikasi dengan bantuan teknologi diterapkan sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Email, situs jejaring sosial dan teknologi lainnya digunakan untuk mengolah informasi dan permintaan pelanggan sesuai kebijakan dan prosedur gerai.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Pesan dan informasi disampaikan segera kepada pihak yang terkait.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Masalah dan tindakan relevan yang sedang diambil, diinformasikan kepada pelanggan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 4.3
        $elemenId4_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Melakukan komunikasi dengan pelanggan dan rekan kerja dari latar belakang yang berbeda-beda',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_3,
                'deskripsi_kriteria' => 'Pelanggan dan rekan kerja dari latar belakang yang berbeda-beda diperlakukan sama.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_3,
                'deskripsi_kriteria' => 'Perbedaan budaya dipertimbangkan dalam komunikasi lisan dan tulisan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_3,
                'deskripsi_kriteria' => 'Bantuan dimintakan kepada rekan kerja atau atasan, bila diperlukan untuk memfasilitasi komunikasi.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 4.4
        $elemenId4_4 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Bekerja dalam tim',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_4,
                'deskripsi_kriteria' => 'Bantuan dimintakan kepada anggota tim lainnya saat dibutuhkan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_4,
                'deskripsi_kriteria' => 'Jalur komunikasi dengan atasan dan rekan kerja dijalankan sesuai kebijakan gerai.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_4,
                'deskripsi_kriteria' => 'Masukan yang konstruktif dari anggota tim lainnya ditindaklanjuti untuk perbaikan kerja tim.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_4,
                'deskripsi_kriteria' => 'Ketrampilan berkomunikasi ditingkatkan untuk meminimalkan kesalahpahaman.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_4,
                'deskripsi_kriteria' => 'Komunikasi secara terbuka dan saling menghormati diterapkan untuk menghindari konflik di tempat kerja.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 4.5
        $elemenId4_5 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Menafsirkan dokumen ritel',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_5,
                'deskripsi_kriteria' => 'Berbagai macam dokumen ritel dibuatkan daftarnya.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_5,
                'deskripsi_kriteria' => 'Informasi dari berbagai dokumen ritel ditafsirkan untuk diaplikasikan sesuai kebijakan dan prosedur gerai.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 5: Menerapkan Perilaku Kerja Aman di Gerai Ritel ==========
        $unitId5 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'G.46RIT00.018.1',
            'judul_unit' => 'Menerapkan Perilaku Kerja Aman di Gerai Ritel',
            'pertanyaan_unit' => 'Dapatkah Saya Menerapkan Perilaku Kerja Aman di Gerai Ritel?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 5.1
        $elemenId5_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Menerapkan prosedur keselamatan dasar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Prosedur keselamatan dilakukan untuk mencapai lingkungan kerja yang aman, sesuai dengan peraturan Keselamatan dan Kesehatan Kerja (K3) yang relevan, termasuk kode praktek mengenai bahaya tertentu di industri atau tempat kerja.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Praktek kerja yang tidak aman dilaporkan, termasuk instalasi dan peralatan yang rusak sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Barang dan zat berbahaya ditempatkan sesuai kebijakan gerai dan peraturan yang relevan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Potensi resiko penanganan secara manual (manual handling) diaplikasikan sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Insiden dan kecelakaan kerja dilaporkan kepada personil yang relevan.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 5.2
        $elemenId5_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Menerapkan prosedur darurat dasar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Prosedur darurat, evakuasi dan kebakaran dipatuhi sesuai dengan kebijakan gerai dan peraturan Keselamatan dan Kesehatan Kerja (K3).',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Personil yang bertanggung jawab untuk pertolongan pertama dan prosedur evakuasi ditetapkan untuk memudahkan koordinasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Alarm keamanan dipasang pada lokasi yang tepat sesuai dengan fungsinya.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 6: Melakukan Interaksi Aktif dalam Membantu Pelanggan Berbelanja ==========
        $unitId6 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'G.46RIT00.009.1',
            'judul_unit' => 'Melakukan Interaksi Aktif dalam Membantu Pelanggan Berbelanja',
            'pertanyaan_unit' => 'Dapatkah Saya Melakukan Interaksi Aktif dalam Membantu Pelanggan Berbelanja?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 6.1
        $elemenId6_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId6,
            'nama_elemen' => 'Memberikan pelayanan prima kepada pelanggan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId6_1,
                'deskripsi_kriteria' => 'Pelayanan secara komunikatif, sopan dan profesional diberikan kepada pelanggan sesuai prosedur dan kebijakan gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_1,
                'deskripsi_kriteria' => 'Informasi kebutuhan pelanggan dicatat secara detail untuk ditindaklanjuti.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_1,
                'deskripsi_kriteria' => 'Potensi masalah diidentifikasi untuk segera diantisipasi demi meminimalkan ketidakpuasan pelanggan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_1,
                'deskripsi_kriteria' => 'Pelayanan kepada pelanggan diutamakan dari kegiatan lainnya.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_1,
                'deskripsi_kriteria' => 'Interaksi dengan pelanggan dijaga sampai pelanggan menyelesaikan pembelian.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_1,
                'deskripsi_kriteria' => 'Komunikasi lisan dan tulisan dilakukan untuk mengembangkan hubungan baik dengan pelanggan.',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_1,
                'deskripsi_kriteria' => 'Pelayanan prima diterapkan agar pelanggan tertarik datang kembali.',
                'urutan' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_1,
                'deskripsi_kriteria' => 'Ucapan terimakasih disampaikan saat pelanggan meninggalkan gerai sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 6.2
        $elemenId6_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId6,
            'nama_elemen' => 'Menangani keluhan dan complain pelanggan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId6_2,
                'deskripsi_kriteria' => 'Keluhan dan komplain pelanggan didengarkan secara positif, terbuka dan sopan untuk ditindaklanjuti oleh pihak-pihak yang relevan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_2,
                'deskripsi_kriteria' => 'Keluhan dan komplain pelanggan ditindaklanjuti sampai selesai sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_2,
                'deskripsi_kriteria' => 'Keluhan dan komplain pelanggan diubah menjadi kesempatan dengan memberikan pelayanan yang lebih baik sesuai prosedur gerai.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_2,
                'deskripsi_kriteria' => 'Penanganan keluhan dan komplain pelanggan didokumentasikan sebagai pembelajaran untuk perbaikan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 6.3
        $elemenId6_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId6,
            'nama_elemen' => 'Memproses pembelian pelanggan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Data dan informasi pelanggan dicatat secara akurat sesuai kebijakan dan prosedur gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Informasi yang tepat diberikan kepada pelanggan saat melakukan transaksi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Pembelian yang dilakukan pelanggan diproses secara rinci dan akurat.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Proses pengembalian uang dilakukan dengan teliti dan cermat.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 6.4
        $elemenId6_4 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId6,
            'nama_elemen' => 'Mengidentifikasi permintaan khusus pelanggan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId6_4,
                'deskripsi_kriteria' => 'Permintaan khusus diidentifikasi melalui pertanyaan dan pengamatan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_4,
                'deskripsi_kriteria' => 'Ketulusan tutur kata dan Bahasa tubuh ditunjukkan ketika membantu pelanggan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_4,
                'deskripsi_kriteria' => 'Kebutuhan khusus pelanggan dipenuhi dengan sigap.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 7: Melakukan Penerimaan Barang Dagangan (Receiving) ==========
        $unitId7 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'G.46RIT00.006.1',
            'judul_unit' => 'Melakukan Penerimaan Barang Dagangan (Receiving)',
            'pertanyaan_unit' => 'Dapatkah Saya Melakukan Penerimaan Barang Dagangan (Receiving)?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 7.1
        $elemenId7_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId7,
            'nama_elemen' => 'Memeriksa penerimaan barang dan kelengkapan dokumen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Prosedur penerimaan barang di tempat kerja diterapkan untuk menghindari kesalahan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Dokumen penerimaan barang dan pelaporan kerusakan diperiksa kesesuaiannya.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Perbedaan atau kerusakan pada penerimaan barang dilaporkan kepada pihak yang relevan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Barang yang tidak sesuai dicatat untuk diproses lebih lanjut sesuai dengan prosedur perusahaan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 7.2
        $elemenId7_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId7,
            'nama_elemen' => 'Melakukan penyimpanan barang',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId7_2,
                'deskripsi_kriteria' => 'Teknik dan sumber daya untuk penanganan barang secara manual diidentifikasi sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_2,
                'deskripsi_kriteria' => 'Prosedur keselamatan kerja diterapkan saat membongkar, membuka kemasan, dan menyimpan barang.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_2,
                'deskripsi_kriteria' => 'Barang dikeluarkan dari kemasan sesuai dengan prosedur dan kebijakan gerai.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_2,
                'deskripsi_kriteria' => 'Barang disimpan sesuai dengan prosedur dan kebijakan gerai.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 8: Melakukan Pengemasan Produk ==========
        $unitId8 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'G.46RIT00.005.1',
            'judul_unit' => 'Melakukan Pengemasan Produk',
            'pertanyaan_unit' => 'Dapatkah Saya Melakukan Pengemasan Produk?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 8.1
        $elemenId8_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId8,
            'nama_elemen' => 'Mengemas produk',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId8_1,
                'deskripsi_kriteria' => 'Spesifikasi kemasan dan dokumentasi kemasan diidentifikasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_1,
                'deskripsi_kriteria' => 'Jenis-jenis kemasan dipilih sesuai dengan barang-barang yang akan dikemas.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_1,
                'deskripsi_kriteria' => 'Bahan-bahan kemasan disesuaikan dengan spesifikasi dan kebutuhan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_1,
                'deskripsi_kriteria' => 'Pengemasan dilakukan sesuai dengan prosedur dan kebijakan gerai.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_1,
                'deskripsi_kriteria' => 'Bahan-bahan kemasan digunakan secara ekonomis dan tepat, untuk meminimalkan kerusakan dan kerugian pada saat transit atau penyimpanan.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_1,
                'deskripsi_kriteria' => 'Barang-barang yang telah dikemas disusun, untuk meminimalkan kerusakan dari dalam dan luar.',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 8.2
        $elemenId8_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId8,
            'nama_elemen' => 'Memberi label pada produk yang telah dikemas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId8_2,
                'deskripsi_kriteria' => 'Standar pemberian label di tempat kerja diidentifikasi sesuai jenis produk.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_2,
                'deskripsi_kriteria' => 'Pemberian label dan simbol dilakukan sesuai dengan standar dan kebijakan perusahaan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_2,
                'deskripsi_kriteria' => 'Penanganan produk diterapkan sesuai prosedur dan kebijakan gerai.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_2,
                'deskripsi_kriteria' => 'Faktur dan slip produk dilampirkan sesuai dengan prosedur gerai.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_2,
                'deskripsi_kriteria' => 'Dokumentasi dilengkapi sesuai prosedur dan kebijakan gerai.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 9: Mengoperasikan Peralatan Dasar Ritel ==========
        $unitId9 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'G.46RIT00.003.1',
            'judul_unit' => 'Mengoperasikan Peralatan Dasar Ritel',
            'pertanyaan_unit' => 'Dapatkah Saya Mengoperasikan Peralatan Dasar Ritel?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 9.1
        $elemenId9_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId9,
            'nama_elemen' => 'Mengidentifikasi kegunaan peralatan ritel',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId9_1,
                'deskripsi_kriteria' => 'Peralatan di gerai diidentifikasi sesuai kegunaannya.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId9_1,
                'deskripsi_kriteria' => 'Peralatan dipilih berdasarkan jenis pekerjaan sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 9.2
        $elemenId9_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId9,
            'nama_elemen' => 'Memakai peralatan ritel sesuai dengan anjuran produsen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId9_2,
                'deskripsi_kriteria' => 'Peralatan dioperasikan sesuai dengan spesifikasi desain dan persyaratan Keselamatan dan Kesehatan Kerja (K3).',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId9_2,
                'deskripsi_kriteria' => 'Kesalahan pada peralatan dilaporkan kepada personil yang relevan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 9.3
        $elemenId9_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId9,
            'nama_elemen' => 'Memelihara peralatan sesuai dengan anjuran produsen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId9_3,
                'deskripsi_kriteria' => 'Program pemeliharaan peralatan diterapkan sesuai kebijakan dan prosedur gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId9_3,
                'deskripsi_kriteria' => 'Peralatan disimpan sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 10: Menjual Produk dan Pelayanan Ritel ==========
        $unitId10 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'G.46RIT00.014.1',
            'judul_unit' => 'Menjual Produk dan Pelayanan Ritel',
            'pertanyaan_unit' => 'Dapatkah Saya Menjual Produk dan Layanan Ritel?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 10.1
        $elemenId10_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId10,
            'nama_elemen' => 'Mengaplikasikan pengetahuan produk',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId10_1,
                'deskripsi_kriteria' => 'Pengetahuan produk ditingkatkan dengan mengakses sumber yang relevan dan masukan dari staf penjualan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_1,
                'deskripsi_kriteria' => 'Pengetahuan mengenai penggunaan produk dan pelayanan diaplikasikan sesuai dengan kebijakan gerai dan peraturan yang berlaku.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_1,
                'deskripsi_kriteria' => 'Kesenjangan dalam pengetahuan produk diatasi dengan menggali sumber informasi yang relevan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 10.2
        $elemenId10_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId10,
            'nama_elemen' => 'Menyapa pelanggan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId10_2,
                'deskripsi_kriteria' => 'Pelanggan disapa dengan salam yang sudah ditetapkan oleh perusahaan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_2,
                'deskripsi_kriteria' => 'Perilaku pelanggan dipelajari dengan menggali sumber informasi yang relevan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_2,
                'deskripsi_kriteria' => 'Pendekatan kepada pelanggan dilakukan sesuai dengan prosedur gerai dan perilaku pelanggan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_2,
                'deskripsi_kriteria' => 'Kesan positif ditunjukkan untuk mendorong minat belanja pelanggan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 10.3
        $elemenId10_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId10,
            'nama_elemen' => 'Mengumpulkan informasi pelanggan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId10_3,
                'deskripsi_kriteria' => 'Teknik berkomunikasi digunakan untuk menggali kebutuhan spesifik pelanggan dan alasan membeli.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_3,
                'deskripsi_kriteria' => 'Tanda dan isyarat beli non-verbal dari pelanggan diidentifikasi untuk memenuhi kebutuhanya.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_3,
                'deskripsi_kriteria' => 'Pelanggan diarahkan kepada produk tertentu sesuai dengan kebijakan gerai.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 10.4
        $elemenId10_4 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId10,
            'nama_elemen' => 'Menjual manfaat produk dan pelayanan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId10_4,
                'deskripsi_kriteria' => 'Kebutuhan pelanggan dipadankan dengan produk dan pelayanan tertentu.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_4,
                'deskripsi_kriteria' => 'Fitur, karakteristik dan keunggulan produk dipromosikan ke pelanggan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_4,
                'deskripsi_kriteria' => 'Cara pemakaian produk dan prasyarat keselamatan dijelaskan sesuai buku panduan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_4,
                'deskripsi_kriteria' => 'Pelanggan dirujuk ke spesialis produk jika diperlukan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_4,
                'deskripsi_kriteria' => 'Pertanyaan rutin pelanggan dijawab dengan tepat jika perlu dirujuk ke rekan kerja yang lebih senior.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 10.5
        $elemenId10_5 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId10,
            'nama_elemen' => 'Mengatasi keberatan pelanggan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId10_5,
                'deskripsi_kriteria' => 'Keberatan pelanggan diterima untuk identifikasi sesuai dengan prosedur gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_5,
                'deskripsi_kriteria' => 'Keberatan pelanggan dikelompokkan ke kategori harga, waktu dan karakteristik untuk memberikan solusi terbaik.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_5,
                'deskripsi_kriteria' => 'Solusi atas keberatan pelanggan diberikan sesuai dengan kebijakan gerai.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_5,
                'deskripsi_kriteria' => 'Teknik pemecahan masalah digunakan dalam lingkup tanggung jawabnya, jika perlu dirujuk ke staf senior.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 10.6
        $elemenId10_6 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId10,
            'nama_elemen' => 'Menutup penjualan (Close sale)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId10_6,
                'deskripsi_kriteria' => 'Isyarat pembelian dari pelanggan ditangani dengan tepat.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_6,
                'deskripsi_kriteria' => 'Pelanggan diarahkan untuk melakukan pembelian.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_6,
                'deskripsi_kriteria' => 'Teknik dan metoda dalam menutup penjualan digunakan sesuai dengan kebijakan dan prosedur gerai.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 10.7
        $elemenId10_7 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId10,
            'nama_elemen' => 'Memaksimalkan peluang penjualan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId10_7,
                'deskripsi_kriteria' => 'Peluang untuk melakukan penjualan tambahan diidentifikasi sesuai dengan kebijakan gerai.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_7,
                'deskripsi_kriteria' => 'Saran untuk produk dan pelayanan tambahan diberikan kepada pelanggan sesuai kebutuhannya.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_7,
                'deskripsi_kriteria' => 'Hasil penjualan pribadi didiskusikan dengan pihak yang relevan guna memaksimalkan penjualan dimasa depan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✓ Skema Trainee Kasir untuk Pemasaran berhasil dibuat!');
        $this->command->info('✓ 10 Unit Kompetensi berhasil ditambahkan!');
        $this->command->info('✓ 37 Elemen berhasil ditambahkan!');
    }
}
