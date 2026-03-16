<?php

namespace Database\Seeders\Catalog;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkemaMPLBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Skema: Office Administrative untuk Jurusan MPLB
     * 21 Unit Kompetensi
     */
    public function run(): void
    {
        if (DB::table('skemas')->where('nomor_skema', 'SKM/BNSP/00014/2/2023/845')->exists()) {
            $this->command->info('Skema MPLB sudah ada. Seeder dilewati.');
            return;
        }

        // Ambil ID jurusan MPLB
        $jurusanMPLB = DB::table('jurusan')
            ->where('kode_jurusan', 'MPLB')
            ->value('ID_jurusan');

        if (!$jurusanMPLB) {
            $this->command->warn('Jurusan MPLB tidak ditemukan! Skema akan dibuat tanpa jurusan.');
        }

        // 1. Buat skema Office Administrative untuk MPLB
        $skemaId = DB::table('skemas')->insertGetId([
            'nomor_skema' => 'SKM/BNSP/00014/2/2023/845',
            'nama_skema' => 'Okupasi Office Administrative',
            'jenis_skema' => 'Okupasi',
            'jurusan_id' => $jurusanMPLB,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ========== UNIT 1: Menangani Penerimaan dan Pengiriman Dokumen/Surat ==========
        $unitId1 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821100.001.02',
            'judul_unit' => 'Menangani Penerimaan dan Pengiriman Dokumen/Surat',
            'pertanyaan_unit' => 'Dapatkah Saya Menangani Penerimaan dan Pengiriman Dokumen/Surat?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 1.1
        $elemenId1_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Memproses pencatatan surat/dokumen termasuk surat elektronik yang masuk dan keluar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Mengidentifikasi surat/dokumen yang diterima sesuai prosedur organisasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Menyortir Surat/dokumen sesuai dengan tujuannya.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Memeriksa Berita dalam surat/dokumen termasuk surat elektronik keakuratannya.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Mengidentifikasi Lampiran surat/dokumen termasuk surat elektronik sesuai prosedur organisasi.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Mencatat Surat/dokumen sesuai dengan sistem yang ada pada organisasi.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 1.2
        $elemenId1_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Mendistribusikan surat/dokumen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Menyiapkan Daftar distribusi sesuai prosedur organisasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Melakukan Pendistribusian surat/dokumen dengan batas waktu.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Membuat Salinan surat/dokumen sesuai prosedur organisasi.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Menetapkan Pilihan cara pengiriman dengan benar dan yang terbaik sesuai kebutuhan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Mendokumentasi tanda terima surat/dokumen sesuai prosedur organisasi.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 2: Memproduksi Dokumen ==========
        $unitId2 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821100.004.02',
            'judul_unit' => 'Memproduksi Dokumen',
            'pertanyaan_unit' => 'Dapatkah Saya Memproduksi Dokumen?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 2.1
        $elemenId2_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Menyiapkan dokumen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Memilih teknologi dan perangkat lunak untuk menghasilkan dokumen yang dibutuhkan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Mengidentifikasi Persyaratan Organisasi untuk menciptakan informasi sesuai rencana.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 2.2
        $elemenId2_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Mendesain dokumen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_2,
                'deskripsi_kriteria' => 'Membuat pointers untuk mengisi dokumen sesuai kebutuhan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_2,
                'deskripsi_kriteria' => 'Menentukan format sesuai dengan ketentuan organisasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_2,
                'deskripsi_kriteria' => 'Menentukan peralatan untuk menciptakan dokumen sesuai kebutuhan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 2.3
        $elemenId2_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Memproduksi dokumen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_3,
                'deskripsi_kriteria' => 'Menentukan waktu untuk memproduksi dokumen sesuai dengan persyaratan organisasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_3,
                'deskripsi_kriteria' => 'Membuat dokumen sesuai format.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_3,
                'deskripsi_kriteria' => 'Memeriksa redaksional dokumen yang dihasilkan sesuai persyaratan organisasi.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 3: Mengelola Jadwal Kegiatan Pimpinan ==========
        $unitId3 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821100.012.01',
            'judul_unit' => 'Mengelola Jadwal Kegiatan Pimpinan',
            'pertanyaan_unit' => 'Dapatkah Saya Mengelola Jadwal Kegiatan Pimpinan?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 3.1
        $elemenId3_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Mengidentifikasi kegiatan pimpinan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Memperoleh Informasi jadwal kegiatan pimpinan melalui departemen internal atau pihak luar yang terkait.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Mengidentifikasi Informasi pada dokumen pendukung sesuai dengan kegiatan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Mengidentifikasi Jadwal hari libur nasional dan hari cuti pimpinan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 3.2
        $elemenId3_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Mengatur jadwal kegiatan pimpinan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Menyeleksi Kegiatan sesuai kebutuhan organisasi dan arahan pimpinan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Mengkonfirmasi Kehadiran pimpinan pada kegiatan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Menyusun Jadwal kegiatan/perjalanan pimpinan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 3.3
        $elemenId3_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Mencatat jadwal kegiatan pimpinan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_3,
                'deskripsi_kriteria' => 'Mengidetifikasi Metode pencatatan kegiatan yang digunakan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_3,
                'deskripsi_kriteria' => 'Membuat Jadwal kegiatan sesuai format pada organisasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_3,
                'deskripsi_kriteria' => 'Memperbaharui Jadwal kegiatan pimpinan secara berkala.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 4: Mengatur Rapat/Pertemuan ==========
        $unitId4 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821100.013.01',
            'judul_unit' => 'Mengatur Rapat/Pertemuan',
            'pertanyaan_unit' => 'Dapatkah Saya Mengatur Rapat/Pertemuan?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 4.1
        $elemenId4_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Memproses undangan rapat/pertemuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Memastikan daftar nama peserta rapat sesuai tujuan rapat/pertemuan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Menyusun tujuan dan rangkaian acara pertemuan sesuai arahan pimpinan dan protokoler yang berlaku di organisasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Menetapkan waktu, tempat dan ketentuan pertemuan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Membuat undangan rapat sesuai kegiatan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Mengirim undangan melalui media yang dipilih.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 4.2
        $elemenId4_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Mempersiapkan peralatan, perlengkapan dan tenaga ahli',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Memesan ruangan pertemuan dan konsumsi sesuai kebutuhan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Menentukan layout ruangan sesuai kebutuhan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Mengidentifikasi peralatan dan perlengkapan pendukung sesuai kebutuhan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Menyiapkan tenaga profesional sesuai kebutuhan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 4.3
        $elemenId4_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Mengatur biaya pertemuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_3,
                'deskripsi_kriteria' => 'Membuat rincian anggaran untuk mendapatkan persetujuan pimpinan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_3,
                'deskripsi_kriteria' => 'Menindaklanjuti rincian anggaran kepada bagian terkait.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_3,
                'deskripsi_kriteria' => 'Mengelola anggaran sesuai rincian yang telah ditetapkan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_3,
                'deskripsi_kriteria' => 'Mencatat bukti kwitansi/tanda terima sebagai lampiran laporan penggunaan biaya.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 4.4
        $elemenId4_4 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Menyelenggarakan pertemuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_4,
                'deskripsi_kriteria' => 'Mengonfirmasi kehadiran peserta.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_4,
                'deskripsi_kriteria' => 'Memeriksa pengaturan ruangan, konsumsi, sesuai dengan pesanan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_4,
                'deskripsi_kriteria' => 'Menyiapkan peralatan dan perlengkapan sesuai kebutuhan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_4,
                'deskripsi_kriteria' => 'Melaksanakan pertemuan sesuai rangkaian agenda yang telah ditetapkan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 4.5
        $elemenId4_5 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Membuat laporan pertemuan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_5,
                'deskripsi_kriteria' => 'Melaporkan jumlah peserta yang hadir.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_5,
                'deskripsi_kriteria' => 'Membuat laporan penggunaan biaya sesuai dengan aturan organisasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 5: Melakukan komunikasi melalui telepon ==========
        $unitId5 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.029.02',
            'judul_unit' => 'Melakukan komunikasi melalui telepon',
            'pertanyaan_unit' => 'Dapatkah Saya Melakukan komunikasi melalui telepon?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 5.1
        $elemenId5_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Menjawab Telepon',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Menjawab panggilan telepon sesuai dengan SOP.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Menawarkan bantuan kepada penelepon sesuai SOP.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Mengulangi inti pembicaraan untuk menghindari salah pengertian.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Menjawab pertanyaan penelepon atau meneruskan kepada orang yang tepat.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 5.2
        $elemenId5_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Melakukan Panggilan Telepon',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Mendapatkan nomor telepon dari sumber yang benar.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Melakukan input data secara teliti, cepat dan tepat waktu.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Menyampaikan nama organisasi dan alasan menelepon sesuai SOP.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Menerapkan etika bertelepon sesuai SOP saat berbicara melalui telepon.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 5.3
        $elemenId5_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Menangani Pesan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_3,
                'deskripsi_kriteria' => 'Mencatat pesan yang diterima secara akurat untuk disampaikan kepada departemen/orang yang dimaksud.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_3,
                'deskripsi_kriteria' => 'Menyampaikan pesan segera kepada orang yang berhak menerimanya.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_3,
                'deskripsi_kriteria' => 'Melaporkan panggilan telepon yang mengancam dan mencurigakan dengan cepat kepada yang berwenang sesuai dengan SOP.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 6: Melakukan Komunikasi Lisan dengan Kolega/Pelanggan ==========
        $unitId6 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.030.02',
            'judul_unit' => 'Melakukan Komunikasi Lisan dengan Kolega/Pelanggan',
            'pertanyaan_unit' => 'Dapatkah Saya Melakukan Komunikasi Lisan dengan Kolega/Pelanggan?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 6.1
        $elemenId6_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId6,
            'nama_elemen' => 'Menyiapkan komunikasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId6_1,
                'deskripsi_kriteria' => 'Mengidentifikasi karakteristik komunikan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_1,
                'deskripsi_kriteria' => 'Menentukan metode komunikasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 6.2
        $elemenId6_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId6,
            'nama_elemen' => 'Melakukan kontak dengan komunikan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId6_2,
                'deskripsi_kriteria' => 'Menciptakan lingkungan pelayanan yang efektif.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_2,
                'deskripsi_kriteria' => 'Menerima permintaan informasi komunikan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_2,
                'deskripsi_kriteria' => 'Mengeksplorasi kebutuhan komunikan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 6.3
        $elemenId6_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId6,
            'nama_elemen' => 'Menangani Pesan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Mengolah pesan atau informasi yang diterima dicatat untuk.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Memberikan jawaban sesuai kebutuhan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Melakukan tindakan lebih lanjut.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Mengevaluasi pelaksanaan komunikasi.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 7: Melakukan Komunikasi Lisan dalam Bahasa Inggris pada Tingkat Operasional Dasar ==========
        $unitId7 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.032.02',
            'judul_unit' => 'Melakukan Komunikasi Lisan dalam Bahasa Inggris pada Tingkat Operasional Dasar',
            'pertanyaan_unit' => 'Dapatkah Saya Melakukan Komunikasi Lisan dalam Bahasa Inggris pada Tingkat Operasional Dasar?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 7.1
        $elemenId7_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId7,
            'nama_elemen' => 'Melakukan komunikasi sehari-hari ditempat kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Melakukan komunikasi timbal balik dengan komunikan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Memberikan Informasi faktual dan terkini sesuai SOP organisasi dan kebutuhan pelanggan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Menjawab permintaan atau pertanyaan-pertanyaan yang bersifat sederhana.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Memberikan Bantuan dalam lingkup tanggung jawabnya.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Memberikan Saran/usul untuk hal-hal tertentu sesuai dengan SOP.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Mengidentifikasi Kebutuhan bantuan yang diperlukan dari orang lain.',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Melakukan penjelasan yang mudah dengan perlahan-lahan dan berurutan.',
                'urutan' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Memberikan Informasi tambahan sesuai dengan kebutuhan pelanggan dan kolega.',
                'urutan' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 7.2
        $elemenId7_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId7,
            'nama_elemen' => 'Menggunakan kalimat sesuai SOP organisasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId7_2,
                'deskripsi_kriteria' => 'Mengidentifikasi Kalimat resmi dan tidak resmi untuk digunakan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_2,
                'deskripsi_kriteria' => 'Memberikan Sapaan dengan santun sesuai etika berkomunikasi perpisahan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_2,
                'deskripsi_kriteria' => 'Memberikan Salam perpisahan sesuai SOP organisasi.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_2,
                'deskripsi_kriteria' => 'Mengajukan permintaan maaf sesuai SOP organisasi.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 7.3
        $elemenId7_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId7,
            'nama_elemen' => 'Melakukan komunikasi melalui telepon',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId7_3,
                'deskripsi_kriteria' => 'Memberikan salam termasuk menyebutkan nama organisasi sesuai SOP organisasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_3,
                'deskripsi_kriteria' => 'Menawarkan Bantuan kepada penelepon.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_3,
                'deskripsi_kriteria' => 'Meminta penelepon untuk menunggu ketika sedang mencari orang yang dikehendaki penelepon.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_3,
                'deskripsi_kriteria' => 'Menyampaikan permintaan maaf kepada penelepon ketika orang yang dikehendaki tidak berada di tempat ataupun tidak mampu memenuhi permintaan penelepon.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_3,
                'deskripsi_kriteria' => 'Mencatat Data dan pesan penelepon secara lengkap.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 8: Membaca dalam Bahasa Inggris pada Tingkat Operasional Dasar ==========
        $unitId8 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.033.01',
            'judul_unit' => 'Membaca dalam Bahasa Inggris pada Tingkat Operasional Dasar',
            'pertanyaan_unit' => 'Dapatkah Saya Membaca dalam Bahasa Inggris pada Tingkat Operasional Dasar?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 8.1
        $elemenId8_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId8,
            'nama_elemen' => 'Mengenali tanda-tanda umum yang biasa digunakan pada Industri',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId8_1,
                'deskripsi_kriteria' => 'Menafsirkan makna tanda-tanda baca bahasa Inggris.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_1,
                'deskripsi_kriteria' => 'Mengetahui pengertian tanda-tanda umum yang biasa digunakan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 8.2
        $elemenId8_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId8,
            'nama_elemen' => 'Membaca dokumen kerja sederhana',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId8_2,
                'deskripsi_kriteria' => 'Mengidentifikasi maksud dan isi dokumen.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_2,
                'deskripsi_kriteria' => 'Menanggapi maksud dari dokumen bila perlu.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_2,
                'deskripsi_kriteria' => 'Mencari bantuan bila perlu.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 8.3
        $elemenId8_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId8,
            'nama_elemen' => 'Membaca teks instruksional sederhana',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId8_3,
                'deskripsi_kriteria' => 'Mengidentifikasi tujuan dan inti teks.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_3,
                'deskripsi_kriteria' => 'Memberikan tanggapan terhadap maksud dari teks yang dibutuhkan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId8_3,
                'deskripsi_kriteria' => 'Mencari bantuan bila perlu.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 9: Menulis Dalam Bahasa Inggris Pada Tingkat Operasional Dasar ==========
        $unitId9 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.034.02',
            'judul_unit' => 'Menulis Dalam Bahasa Inggris Pada Tingkat Operasional Dasar',
            'pertanyaan_unit' => 'Dapatkah Saya Menulis Dalam Bahasa Inggris Pada Tingkat Operasional Dasar?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 9.1
        $elemenId9_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId9,
            'nama_elemen' => 'Mengidentifikasi tujuan penulisan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId9_1,
                'deskripsi_kriteria' => 'Mengidentifikasi tujuan penulisan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId9_1,
                'deskripsi_kriteria' => 'Memilih Jenis dokumen yang tepat sebagai pendukung untuk dipersiapkan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId9_1,
                'deskripsi_kriteria' => 'Menggunakan bahasa yang sesuai dengan kaidah tata bahasa untuk memenuhi kebutuhan konteks.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId9_1,
                'deskripsi_kriteria' => 'Memperhatikan kaidah tata bahasa yang umum dalam bisnis dan diikuti.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId9_1,
                'deskripsi_kriteria' => 'Menyampaikan informasi secara objektif.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 9.2
        $elemenId9_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId9,
            'nama_elemen' => 'Membuat dokumen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId9_2,
                'deskripsi_kriteria' => 'Menggunakan kalimat sederhana dalam menyampaikan suatu pengertian.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId9_2,
                'deskripsi_kriteria' => 'Menjabarkan instruksi dan/atau petunjuk dengan benar.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId9_2,
                'deskripsi_kriteria' => 'Mengikuti kaidah tata bahasa yang umum dalam bisnis.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId9_2,
                'deskripsi_kriteria' => 'Menggunakan bahasa yang tepat untuk memenuhi kebutuhan konteks sesuai kebutuhan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId9_2,
                'deskripsi_kriteria' => 'Meruntut kalimat sesuai dengan SOP.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId9_2,
                'deskripsi_kriteria' => 'Menggunakan Bahasa resmi dan tidak resmi dengan tepat.',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 10: Memberi Layanan Kepada Pelanggan ==========
        $unitId10 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.045.02',
            'judul_unit' => 'Memberi Layanan Kepada Pelanggan',
            'pertanyaan_unit' => 'Dapatkah Saya Memberi Layanan Kepada Pelanggan?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 10.1
        $elemenId10_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId10,
            'nama_elemen' => 'Menerapkan konsep pelayanan prima dan prinsip-prinsip pelayanan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId10_1,
                'deskripsi_kriteria' => 'Mengidentifikasi konsep pelayanan sesuai kebutuhan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_1,
                'deskripsi_kriteria' => 'Menerapkan prinsip-prinsip pelayanan prima sesuai dengan SOP organisasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 10.2
        $elemenId10_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId10,
            'nama_elemen' => 'Menerapkan unsur-unsur kualitas pelayanan prima',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId10_2,
                'deskripsi_kriteria' => 'Menerapkan unsur-unsur kualitas pelayanan prima.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_2,
                'deskripsi_kriteria' => 'Meningkatkan kualitas pelayanan secara efektif dan efisien.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 10.3
        $elemenId10_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId10,
            'nama_elemen' => 'Memberikan pelayanan kepada pelanggan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId10_3,
                'deskripsi_kriteria' => 'Mengidentifikasi kebutuhan Pelayanan sesuai SOP.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_3,
                'deskripsi_kriteria' => 'Menangani keluhan pelanggan sesuai dengan prosedur organisasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_3,
                'deskripsi_kriteria' => 'Membangun hubungan dengan pelanggan untuk memberikan pelayanan yang maksimal sesuai dengan kebutuhan pelanggan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId10_3,
                'deskripsi_kriteria' => 'Menggunakan bahasa dan sikap yang baik sesuai SOP.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 11: Memproduksi Dokumen di Komputer ==========
        $unitId11 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.053.02',
            'judul_unit' => 'Memproduksi Dokumen di Komputer',
            'pertanyaan_unit' => 'Dapatkah Saya Memproduksi Dokumen di Komputer?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 11.1
        $elemenId11_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId11,
            'nama_elemen' => 'Mempersiapkan peralatan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId11_1,
                'deskripsi_kriteria' => 'Menyiapkan Peralatan computer sesuai dengan SOP.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId11_1,
                'deskripsi_kriteria' => 'Memilih Piranti lunak melalui menu sesuai dengan kebutuhan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId11_1,
                'deskripsi_kriteria' => 'Menyiapkan Media penyimpanan sesuai kebutuhan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 11.2
        $elemenId11_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId11,
            'nama_elemen' => 'Membuat dokumen dari konsep atau teks langsung',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId11_2,
                'deskripsi_kriteria' => 'Mengoperasikan Keyboard/mouse sesuai SOP.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId11_2,
                'deskripsi_kriteria' => 'Mengedit Konsep dokumen sesuai dengan format standar tempat kerja.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId11_2,
                'deskripsi_kriteria' => 'Membuat Dokumen pendukung bila diperlukan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 11.3
        $elemenId11_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId11,
            'nama_elemen' => 'Menyimpan dokumen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId11_3,
                'deskripsi_kriteria' => 'Memastikan nama file/folder penyimpanan dokumen yang dibuat, sesuai SOP.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId11_3,
                'deskripsi_kriteria' => 'Membuat Salinan file/dokumen bila diperlukan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 11.4
        $elemenId11_4 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId11,
            'nama_elemen' => 'Mencetak Dokumen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId11_4,
                'deskripsi_kriteria' => 'Mengidentifikasi File/dokumen/naskah yang akan dicetak sesuai SOP.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId11_4,
                'deskripsi_kriteria' => 'Mengidentifikasi Fitur-fitur pencetakan sesuai kebutuhan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId11_4,
                'deskripsi_kriteria' => 'Mencetak File/dokumen/naskah dengan menggunakan parameter dan urutan pencetakan sesuai prosedur pencetakan file/dokumen bila diperlukan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 12: Menggunakan Peralatan Komunikasi ==========
        $unitId12 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.054.01',
            'judul_unit' => 'Menggunakan Peralatan Komunikasi',
            'pertanyaan_unit' => 'Dapatkah Saya Menggunakan Peralatan Komunikasi?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 12.1
        $elemenId12_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId12,
            'nama_elemen' => 'Mengidentifikasi jenis peralatan komunikasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId12_1,
                'deskripsi_kriteria' => 'Memastikan Jenis komunikasi yang akan dilakukan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId12_1,
                'deskripsi_kriteria' => 'Mengidentifikasi Peralatan komunikasi sesuai dengan jenis komunikasi yang akan dilakukan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId12_1,
                'deskripsi_kriteria' => 'Memilih Peralatan komunikasi yang tepat sesuai kebutuhan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 12.2
        $elemenId12_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId12,
            'nama_elemen' => 'Menggunakan peralatan komunikasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId12_2,
                'deskripsi_kriteria' => 'Menggunakan Peralatan komunikasi sesuai SOP.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId12_2,
                'deskripsi_kriteria' => 'Melaporkan Peralatan yang tidak berfungsi kepada pihak terkait.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId12_2,
                'deskripsi_kriteria' => 'Melakukan Tindakan alternatif segera bila alat komunikasi tidak berfungsi.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId12_2,
                'deskripsi_kriteria' => 'Menggunakan Peralatan komunikasi sesuai dengan panduan penggunaan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 13: Mengoperasikan Aplikasi Perangkat Lunak ==========
        $unitId13 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.057.02',
            'judul_unit' => 'Mengoperasikan Aplikasi Perangkat Lunak',
            'pertanyaan_unit' => 'Dapatkah Saya Mengoperasikan Aplikasi Perangkat Lunak?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 13.1
        $elemenId13_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId13,
            'nama_elemen' => 'Mengakses aplikasi piranti lunak',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId13_1,
                'deskripsi_kriteria' => 'Mengidentifikasi pilihan aplikasi yang dibutuhkan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId13_1,
                'deskripsi_kriteria' => 'Memilih metode pencarian secara tepat untuk penggunaan tipe informasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 13.2
        $elemenId13_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId13,
            'nama_elemen' => 'Melakukan pengoperasian aplikasi piranti lunak',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId13_2,
                'deskripsi_kriteria' => 'Memilih aplikasi piranti lunak sesuai kebutuhan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId13_2,
                'deskripsi_kriteria' => 'Mengidentifikasi peranan yang berbeda dan bagian-bagian dari aplikasi untuk fungsi tertentu.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId13_2,
                'deskripsi_kriteria' => 'Mengoperasikan aplikasi sesuai dengan prosedur.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 14: Mengakses Data di Komputer ==========
        $unitId14 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.058.02',
            'judul_unit' => 'Mengakses Data di Komputer',
            'pertanyaan_unit' => 'Dapatkah Saya Mengakses Data di Komputer?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 14.1
        $elemenId14_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId14,
            'nama_elemen' => 'Membuka file',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId14_1,
                'deskripsi_kriteria' => 'Mengidentifikasi file yang akan di akses sesuai dengan perangkat lunak yang digunakan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId14_1,
                'deskripsi_kriteria' => 'Memilih piranti lunak yang tepat dari menu yang tersedia.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId14_1,
                'deskripsi_kriteria' => 'Mengenali file dapat untuk dibuka.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 14.2
        $elemenId14_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId14,
            'nama_elemen' => 'Mengunduh data',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId14_2,
                'deskripsi_kriteria' => 'Memastikan ketepatan data/informasi yang di akses.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId14_2,
                'deskripsi_kriteria' => 'Menyimpan data/informasi yang di unduh ke dalam file.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId14_2,
                'deskripsi_kriteria' => 'Mengcopy data sesuai dengan kebutuhan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 15: Menggunakan Peralatan dan Sumber Daya Kerja ==========
        $unitId15 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.059.01',
            'judul_unit' => 'Menggunakan Peralatan dan Sumber Daya Kerja',
            'pertanyaan_unit' => 'Dapatkah Saya Menggunakan Peralatan dan Sumber Daya Kerja?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 15.1
        $elemenId15_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId15,
            'nama_elemen' => 'Memilih peralatan dan sumber daya kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId15_1,
                'deskripsi_kriteria' => 'Mengidentifikasi akses peralatan dan sumber daya kerja untuk menyelesaikan tugas.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId15_1,
                'deskripsi_kriteria' => 'Melengkapi perkiraan jumlah dan sumber daya untuk menyelesaikan tugas.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId15_1,
                'deskripsi_kriteria' => 'Memeriksa peralatan untuk pemeliharaan sesuai dengan SOP.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 15.2
        $elemenId15_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId15,
            'nama_elemen' => 'Mengoperasikan Peralatan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId15_2,
                'deskripsi_kriteria' => 'Mengoperasikan peralatan sesuai instruksi pada buku petunjuk.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId15_2,
                'deskripsi_kriteria' => 'Mengidentifikasi kerusakan peralatan secara akurat untuk memastikan perbaikan yang harus diambil sesuai spesifikasi produk.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId15_2,
                'deskripsi_kriteria' => 'Melaporkan perbaikan peralatan yang dilakukan diluar organisasi kepada orang yang tepat sesuai tanggung jawabnya.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 15.3
        $elemenId15_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId15,
            'nama_elemen' => 'Memelihara peralatan atau sumber daya kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId15_3,
                'deskripsi_kriteria' => 'Memelihara peralatan dan sumber daya untuk mendukung pelaksanaan tugas sesuai dengan petunjuk.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId15_3,
                'deskripsi_kriteria' => 'Melakukan pemeliharaan peralatan untuk memastikan bahwa standar pabrik telah terpenuhi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId15_3,
                'deskripsi_kriteria' => 'Mencatat pemeliharaan terhadap peralatan dan sumberdaya sesuai petunjuk.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId15_3,
                'deskripsi_kriteria' => 'Menyimpan peralatan dan sumber daya sesuai SOP.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 16: Membuat Surat/Dokumen Elektronik ==========
        $unitId16 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.060.01',
            'judul_unit' => 'Membuat Surat/Dokumen Elektronik',
            'pertanyaan_unit' => 'Dapatkah Saya Membuat Surat/Dokumen Elektronik?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 16.1
        $elemenId16_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId16,
            'nama_elemen' => 'Mempersiapkan piranti lunak',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId16_1,
                'deskripsi_kriteria' => 'Mengaktifkan perangkat komputer sesuai dengan sistem operasi dan persyaratan manual instalasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId16_1,
                'deskripsi_kriteria' => 'Memastikan piranti lunak telah terinstalasi dan siap digunakan untuk membuat surat elektronik.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId16_1,
                'deskripsi_kriteria' => 'Memiliki akun surat elektronik (email).',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 16.2
        $elemenId16_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId16,
            'nama_elemen' => 'Mengenali menu, format alamat email dan konfigurasi sederhana',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId16_2,
                'deskripsi_kriteria' => 'Mengenali menu-menu yang disediakan beserta shortcut nya berdasarkan user manual sesuai standar tempat kerja.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId16_2,
                'deskripsi_kriteria' => 'Mengenali dan memahami format alamat surat elektronik secara tepat dan cepat.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId16_2,
                'deskripsi_kriteria' => 'Melakukan konfigurasi sederhana sesuai dengan manual yang tersedia.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 16.3
        $elemenId16_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId16,
            'nama_elemen' => 'Membuka, mengambil, membaca, membuat dan mengirim surat/dokumen elektronik',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId16_3,
                'deskripsi_kriteria' => 'Membuka surat elektronik baru dari server menggunakan menu/tombol yang tersedia sesuai SOP organisasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId16_3,
                'deskripsi_kriteria' => 'Mengenali asal surat elektronik dan dapat membaca isinya dengan baik.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId16_3,
                'deskripsi_kriteria' => 'Mengekstrak berkas lampiran (file attachment) dari email, secara benar sesuai prosedur.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId16_3,
                'deskripsi_kriteria' => 'Meneruskan surat elektronik (forward), dan membalas (reply), ke alamat tertentu.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId16_3,
                'deskripsi_kriteria' => 'Membuat/mengkomposisi surat elektronik baru dan mengirim dengan menggunakan fitur yang disediakan.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId16_3,
                'deskripsi_kriteria' => 'Melakukan pengiriman Surat elektronik ke suatu alamat atau beberapa dan dapat disertai dengan file attachment.',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId16_3,
                'deskripsi_kriteria' => 'Menulis surat elektronik sesuai dengan memperhatikan netiquette.',
                'urutan' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 16.4
        $elemenId16_4 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId16,
            'nama_elemen' => 'Menyimpan surat/dokumen elektronik',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId16_4,
                'deskripsi_kriteria' => 'Mengidentifikasi surat elektronik sehingga secara otomatis dapat masuk ke folder yang telah ditetapkan, secara tepat dan benar.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId16_4,
                'deskripsi_kriteria' => 'Membuat atau menghapus folder sesuai kebutuhan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId16_4,
                'deskripsi_kriteria' => 'Menyimpan surat/dokumen elektronik sesuai media yang tersedia secara tepat.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 17: Mengakses Informasi Melalui Homepage ==========
        $unitId17 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.061.01',
            'judul_unit' => 'Mengakses Informasi Melalui Homepage',
            'pertanyaan_unit' => 'Dapatkah Saya Mengakses Informasi Melalui Homepage?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 17.1
        $elemenId17_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId17,
            'nama_elemen' => 'Menyiapkan Perangkat komputer dan informasi yang dibutuhkan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId17_1,
                'deskripsi_kriteria' => 'Memastikan Peralatan komputer telah terhubung dengan internet sesuai SOP organisasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId17_1,
                'deskripsi_kriteria' => 'Menelusuri Universal resources location (URL) dan homepage secara benar.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId17_1,
                'deskripsi_kriteria' => 'Mengidentifikasi Halaman dan situs homepage sesuai kebutuhan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 17.2
        $elemenId17_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId17,
            'nama_elemen' => 'Membuka homepage',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId17_2,
                'deskripsi_kriteria' => 'Mengakses Informasi terkini sesuai dengan kebutuhan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId17_2,
                'deskripsi_kriteria' => 'Mengakses Informasi dari sumber yang dapat dipercaya.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 17.3
        $elemenId17_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId17,
            'nama_elemen' => 'Mengakses data/informasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId17_3,
                'deskripsi_kriteria' => 'Mengidentifikasi Informasi yang diakses pada homepage secara tepat.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId17_3,
                'deskripsi_kriteria' => 'Menyimpan Informasi yang diakses pada media penyimpanan sesuai kebutuhan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId17_3,
                'deskripsi_kriteria' => 'Memastikan informasi dapat diakses dengan benar sesuai prosedur.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 18: Melakukan Transaksi Perbankan Sederhana ==========
        $unitId18 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.067.01',
            'judul_unit' => 'Melakukan Transaksi Perbankan Sederhana',
            'pertanyaan_unit' => 'Dapatkah Saya Melakukan Transaksi Perbankan Sederhana?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 18.1
        $elemenId18_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId18,
            'nama_elemen' => 'Mengenali produk perbankan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId18_1,
                'deskripsi_kriteria' => 'Mengenali tujuan dan fungsi semua jenis produk perbankan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId18_1,
                'deskripsi_kriteria' => 'Memilih produk perbankan sesuai dengan kebutuhan perusahaan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId18_1,
                'deskripsi_kriteria' => 'Mengidentifikasi produk perbankan sesuai dengan kebutuhan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 18.2
        $elemenId18_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId18,
            'nama_elemen' => 'Melakukan transaksi perbankan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId18_2,
                'deskripsi_kriteria' => 'Menulis transaksi yang akan dilakukan dengan jelas pada formulir sesuai kebutuhan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId18_2,
                'deskripsi_kriteria' => 'Memeriksa ulang ketepatan penulisan pengisian formulir.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId18_2,
                'deskripsi_kriteria' => 'Membuat salinan apabila dibutuhkan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 18.3
        $elemenId18_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId18,
            'nama_elemen' => 'Mencatat dan menyimpan bukti-bukti',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId18_3,
                'deskripsi_kriteria' => 'Mencatat transaksi yang sudah dilakukan dalam pembukuan sesuai dengan SOP.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId18_3,
                'deskripsi_kriteria' => 'Menggandakan bukti-bukti transaksi untuk disimpan dengan aman.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 19: Mengelola Arsip ==========
        $unitId19 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.073.02',
            'judul_unit' => 'Mengelola Arsip',
            'pertanyaan_unit' => 'Dapatkah Saya Mengelola Arsip?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 19.1
        $elemenId19_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId19,
            'nama_elemen' => 'Melakukan penyimpanan dokumen/surat',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId19_1,
                'deskripsi_kriteria' => 'Memastikan dokumen yang akan diarsip sudah selesai diproses dan siap untuk disimpan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId19_1,
                'deskripsi_kriteria' => 'Mengidentifikasi sistem kearsipan sesuai kebutuhan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId19_1,
                'deskripsi_kriteria' => 'Mengindeks dokumen dan ditempatkan sesuai sistem kearsipan yang berlaku dimasing-masing organisasi untuk memudahkan pencarian.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 19.2
        $elemenId19_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId19,
            'nama_elemen' => 'Menjaga sistem kearsipan manual maupun elektronik',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId19_2,
                'deskripsi_kriteria' => 'Menyimpan dokumen di tempat penyimpanan arsip manual maupun elektronik sesuai sistem yang telah ditentukan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId19_2,
                'deskripsi_kriteria' => 'Memonitor pemindahan dokumen agar mudah dalam pencarian kembali.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId19_2,
                'deskripsi_kriteria' => 'Melakukan pemantauan pemeliharaan berkala terhadap perlengkapan dan peralatan sesuai dengan buku panduan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId19_2,
                'deskripsi_kriteria' => 'Melakukan permohonan perbaikan terhadap perlengkapan/peralatan yang tidak berfungsi segera.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 19.3
        $elemenId19_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId19,
            'nama_elemen' => 'Melakukan pengendalian dokumen',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId19_3,
                'deskripsi_kriteria' => 'Mengidentifikasi seluruh dokumen yang telah disahkan oleh pejabat yang berwenang, sebelum diterbitkan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId19_3,
                'deskripsi_kriteria' => 'Mencantumkan perubahan dokumen revisi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId19_3,
                'deskripsi_kriteria' => 'Mengendalikan dokumen antara dokumen yang dipakai sebagai acuan kerja dengan stempel terkendali atau hanya bahan bacaan dengan stempel tidak terkendali.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId19_3,
                'deskripsi_kriteria' => 'Mendistribusikan unit kerja yang menerima dokumen ditentukan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId19_3,
                'deskripsi_kriteria' => 'Membuat tanda terima dokumen terkendali dalam bentuk log penerimaan dan dikaji secara berkala sesuai ketentuan dan disetujui oleh personil yang berwenang.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId19_3,
                'deskripsi_kriteria' => 'Menarik/mengambil atau memusnahkan dokumen yang telah melewati masa simpan sesuai ketentuan yang berlaku.',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 20: Menerapkan Prosedur K3 Perkantoran ==========
        $unitId20 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821.100.075.02',
            'judul_unit' => 'Menerapkan Prosedur K3 Perkantoran',
            'pertanyaan_unit' => 'Dapatkah Saya Menerapkan Prosedur K3 Perkantoran?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 20.1
        $elemenId20_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId20,
            'nama_elemen' => 'Menjaga keamanan peralatan dan tempat kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId20_1,
                'deskripsi_kriteria' => 'Mengidentifikasi Peralatan dan tempat kerja.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_1,
                'deskripsi_kriteria' => 'Memeriksa Seluruh peralatan operasional, keamanan dan masa kadaluarsanya sebelum digunakan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_1,
                'deskripsi_kriteria' => 'Memastikan Peralatan kerja yang berhubungan dengan listrik berdasarkan keamanannya sesuai SOP.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_1,
                'deskripsi_kriteria' => 'Mempergunakan Peralatan sesuai petunjuk pemakaian.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_1,
                'deskripsi_kriteria' => 'Melakukan Pemeliharaan rutin sesuai dengan buku petunjuk penggunaan peralatan.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_1,
                'deskripsi_kriteria' => 'Menyimpan Peralatan ditempat yang sesuai dengan peraturan penyimpanan dengan kondisi siap pakai.',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_1,
                'deskripsi_kriteria' => 'Melaporkan kerusakan berdasarkan ketentuan perusahaan.',
                'urutan' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_1,
                'deskripsi_kriteria' => 'Mengenakan Perlengkapan keselamatan kerja yang sesuai untuk menghindari kecelakaan kerja.',
                'urutan' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 20.2
        $elemenId20_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId20,
            'nama_elemen' => 'Menjaga kebersihan peralatan dan tempat kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId20_2,
                'deskripsi_kriteria' => 'Memeriksa Seluruh peralatan operasional, kebersihannya sebelum digunakan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_2,
                'deskripsi_kriteria' => 'Membersihkan Peralatan setelah dipakai sesuai dengan buku petunjuk penggunaan peralatan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_2,
                'deskripsi_kriteria' => 'Menyiapkan Bahan-bahan untuk membersihkan sesuai dengan petunjuk, persyaratan kesehatan dan keselamatan kerja.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_2,
                'deskripsi_kriteria' => 'Menyimpan Bahan-bahan kimia sesuai dengan persyaratan kesehatan dan keselamatan kerja.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_2,
                'deskripsi_kriteria' => 'Membuang Sampah dan kelebihan bahan kimia sesuai higienitas, keamanan serta peraturan mengenai lingkungan.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 20.3
        $elemenId20_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId20,
            'nama_elemen' => 'Mengatasi situasi-situasi darurat',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId20_3,
                'deskripsi_kriteria' => 'Mengidentifikasi Potensi keadaan darurat.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_3,
                'deskripsi_kriteria' => 'Menangani Keadaan darurat sesuai keterampilan yang dimiliki dan kewenangan yang diberikan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_3,
                'deskripsi_kriteria' => 'Mencari segera Bantuan dari rekan sejawat atau orang yang berwenang, bilamana diperlukan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_3,
                'deskripsi_kriteria' => 'Melaporkan Rincian keadaan darurat sesuai dengan aturan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 20.4
        $elemenId20_4 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId20,
            'nama_elemen' => 'Menangani tindakan pertolongan pertama pada kecelakaan di kantor',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId20_4,
                'deskripsi_kriteria' => 'Tanggap serta pro-aktif terhadap keadaan darurat.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_4,
                'deskripsi_kriteria' => 'Melakukan Tindakan sesuai keterampilan dan kewenangan yang telah ditentukan dalam menghadapi keadaan darurat, sehingga dapat menentukan langkah langkah selanjutnya.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_4,
                'deskripsi_kriteria' => 'Mengidentifikasi Fisik korban dan tanda-tanda kehidupan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_4,
                'deskripsi_kriteria' => 'Informasi dan sarana serta prasana layanan pendukung terkini tersedia.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_4,
                'deskripsi_kriteria' => 'Menyampaikan Informasi mengenai kondisi korban kepada petugas unit gawat darurat (UGD).',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_4,
                'deskripsi_kriteria' => 'Membakukan Situasi darurat yang dialami dalam bentuk laporan sesuai dengan peraturan.',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId20_4,
                'deskripsi_kriteria' => 'Membuat Laporan dengan jelas, akurat serta tepat waktu.',
                'urutan' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 21: Meminimalisir Pencurian ==========
        $unitId21 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'N.821100.076.02',
            'judul_unit' => 'Meminimalisir Pencurian',
            'pertanyaan_unit' => 'Dapatkah Saya Meminimalisir Pencurian?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 21.1
        $elemenId21_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId21,
            'nama_elemen' => 'Menggunakan sistem keamanan penyimpanan secara rutin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId21_1,
                'deskripsi_kriteria' => 'Sistem dan prosedur keamanan perusahaan digunakan sesuai dengan SOP Organisasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId21_1,
                'deskripsi_kriteria' => 'Uang kas/tunai disimpan sesuai dengan SOP Organisasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId21_1,
                'deskripsi_kriteria' => 'Perilaku pelanggan yang mencurigakan ditangani sesuai dengan SOP Organisasi.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId21_1,
                'deskripsi_kriteria' => 'Tindakan pencurian ditangani sesuai dengan SOP Organisasi.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId21_1,
                'deskripsi_kriteria' => 'Aset fisik disimpan sesuai SOP Organisasi.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 21.2
        $elemenId21_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId21,
            'nama_elemen' => 'Meminimalisir pencurian',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId21_2,
                'deskripsi_kriteria' => 'Tindakan tepat yang sesuai peraturan dilakukan untuk mengurangi pencurian.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId21_2,
                'deskripsi_kriteria' => 'Kode inventaris dicocokkan dengan ketersediaan inventaris secara berkala sesuai peraturan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId21_2,
                'deskripsi_kriteria' => 'Barang yang mudah dicuri diberi pengamanan tambahan sesuai peraturan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId21_2,
                'deskripsi_kriteria' => 'Keamanan staf dan pihak luar dijaga sesuai SOP Organisasi.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✓ Skema Office Administrative untuk MPLB berhasil dibuat!');
        $this->command->info('✓ 21 Unit Kompetensi berhasil ditambahkan!');
        $this->command->info('✓ 63 Elemen berhasil ditambahkan!');
    }
}
