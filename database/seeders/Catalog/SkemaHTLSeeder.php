<?php

namespace Database\Seeders\Catalog;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkemaHTLSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('skemas')->where('nomor_skema', 'SKM/BNSP/00009/2/2023/885')->exists()) {
            $this->command->info('Skema HTL sudah ada. Seeder dilewati.');
            return;
        }

        $jurusanHTL = DB::table('jurusan')->where('kode_jurusan', 'HTL')->value('ID_jurusan');
        
        if (!$jurusanHTL) {
            $this->command->warn('Jurusan HTL tidak ditemukan!');
        }

        $skemaId = DB::table('skemas')->insertGetId([
            'nomor_skema' => 'SKM/BNSP/00009/2/2023/885',
            'nama_skema' => 'Guest Service Agent',
            'jenis_skema' => 'Okupasi',
            'jurusan_id' => $jurusanHTL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // UNIT 1
        $u1 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.002.2', 'judul_unit' => 'Menyediakan Layanan Akomodasi Reception', 'pertanyaan_unit' => 'Dapatkah Saya Menyediakan Layanan Akomodasi Reception?', 'created_at' => now(), 'updated_at' => now()]);
        $e1_1 = DB::table('elemens')->insertGetId(['unit_id' => $u1, 'nama_elemen' => 'Menyiapkan kedatangan tamu', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e1_1, 'deskripsi_kriteria' => 'Peralatan dan perlengkapan kedatangan tamu di area reception disiapkan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e1_1, 'deskripsi_kriteria' => 'Daftar kedatangan tamu diperiksa sebelum tamu tiba.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e1_2 = DB::table('elemens')->insertGetId(['unit_id' => $u1, 'nama_elemen' => 'Menyambut dan mendaftarkan tamu', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e1_2, 'deskripsi_kriteria' => 'Tamu disambut dengan hangat dan sopan sesuai peraturan perusahaan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e1_2, 'deskripsi_kriteria' => 'Rincian reservasi dikonfirmasikan kepada tamu dengan baik dan benar.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e1_3 = DB::table('elemens')->insertGetId(['unit_id' => $u1, 'nama_elemen' => 'Mengorganisir keberangkatan tamu', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e1_3, 'deskripsi_kriteria' => 'Daftar keberangkatan ditinjau kembali dan diperiksa keakurasiannya.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e1_3, 'deskripsi_kriteria' => 'Tagihan tamu dibuat dan diperiksa dengan baik sesuai peraturan perusahaan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e1_4 = DB::table('elemens')->insertGetId(['unit_id' => $u1, 'nama_elemen' => 'Menyiapkan catatan dan laporan front office', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e1_4, 'deskripsi_kriteria' => 'Catatan front office diperbaharui secara baik dan benar.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e1_4, 'deskripsi_kriteria' => 'Laporan didistribusikan pada departemen yang bersangkutan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 2
        $u2 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.003.2', 'judul_unit' => 'Memelihara Catatan Keuangan', 'pertanyaan_unit' => 'Dapatkah Saya Memelihara Catatan Keuangan?', 'created_at' => now(), 'updated_at' => now()]);
        $e2_1 = DB::table('elemens')->insertGetId(['unit_id' => $u2, 'nama_elemen' => 'Membuat ayat-ayat jurnal', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e2_1, 'deskripsi_kriteria' => 'Pemeriksaan transaksi dilaksanakan secara teliti sesuai prosedur perusahaan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e2_1, 'deskripsi_kriteria' => 'Catatan keuangan yang disiapkan oleh setiap outlet diperiksa.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e2_2 = DB::table('elemens')->insertGetId(['unit_id' => $u2, 'nama_elemen' => 'Menyesuaikan rekening', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e2_2, 'deskripsi_kriteria' => 'Laporan keuangan diselesaikan sesuai jangka waktu yang ditetapkan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e2_2, 'deskripsi_kriteria' => 'Penyelesaian laporan diteruskan kepada orang/departemen yang bersangkutan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 3
        $u3 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.004.2', 'judul_unit' => 'Memproses Transaksi Keuangan', 'pertanyaan_unit' => 'Dapatkah Saya Memproses Transaksi Keuangan?', 'created_at' => now(), 'updated_at' => now()]);
        $e3_1 = DB::table('elemens')->insertGetId(['unit_id' => $u3, 'nama_elemen' => 'Memproses tanda terima dan pembayaran', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e3_1, 'deskripsi_kriteria' => 'Pemeriksaan transaksi dilaksanakan secara teliti.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e3_1, 'deskripsi_kriteria' => 'Neraca yang disiapkan oleh setiap outlet diperiksa.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e3_2 = DB::table('elemens')->insertGetId(['unit_id' => $u3, 'nama_elemen' => 'Memindahkan keuntungan dari register/terminal', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e3_2, 'deskripsi_kriteria' => 'Laporan keuangan diselesaikan sesuai jangka waktu yang ditetapkan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e3_2, 'deskripsi_kriteria' => 'Dokumen kas dan non-kas dipindahkan sesuai prosedur keamanan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e3_3 = DB::table('elemens')->insertGetId(['unit_id' => $u3, 'nama_elemen' => 'Mencocokkan keuntungan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e3_3, 'deskripsi_kriteria' => 'Kas dihitung secara teliti.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e3_3, 'deskripsi_kriteria' => 'Dokumen non-kas dihitung secara akurat.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 4
        $u4 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.006.2', 'judul_unit' => 'Melakukan Komunikasi Melalui Telepon', 'pertanyaan_unit' => 'Dapatkah Saya Melakukan Komunikasi Melalui Telepon?', 'created_at' => now(), 'updated_at' => now()]);
        $e4_1 = DB::table('elemens')->insertGetId(['unit_id' => $u4, 'nama_elemen' => 'Menjawab panggilan telepon masuk', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e4_1, 'deskripsi_kriteria' => 'Telepon dijawab secara cepat, jelas dan sesuai standar perusahaan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e4_1, 'deskripsi_kriteria' => 'Bantuan yang bersahabat ditawarkan kepada penelepon.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e4_2 = DB::table('elemens')->insertGetId(['unit_id' => $u4, 'nama_elemen' => 'Membuat panggilan telepon', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e4_2, 'deskripsi_kriteria' => 'Nomor telepon diperoleh secara akurat.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e4_2, 'deskripsi_kriteria' => 'Tujuan pemanggilan dibuat secara jelas.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 5
        $u5 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.007.2', 'judul_unit' => 'Melaksanakan Audit Malam', 'pertanyaan_unit' => 'Dapatkah Saya Melaksanakan Audit Malam?', 'created_at' => now(), 'updated_at' => now()]);
        $e5_1 = DB::table('elemens')->insertGetId(['unit_id' => $u5, 'nama_elemen' => 'Memonitor prosedur keuangan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e5_1, 'deskripsi_kriteria' => 'Pemeriksaan transaksi dilaksanakan secara teliti.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e5_1, 'deskripsi_kriteria' => 'Prosedur keuangan dimonitor dan diinformasikan kepada manajemen.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e5_2 = DB::table('elemens')->insertGetId(['unit_id' => $u5, 'nama_elemen' => 'Menyelesaikan laporan keuangan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e5_2, 'deskripsi_kriteria' => 'Laporan keuangan diselesaikan sesuai jangka waktu.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e5_2, 'deskripsi_kriteria' => 'Penyelesaian laporan diteruskan kepada departemen yang bersangkutan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 6
        $u6 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.149.2', 'judul_unit' => 'Melakukan Kerjasama Dengan Kolega dan Pelanggan', 'pertanyaan_unit' => 'Dapatkah Saya Melakukan Kerjasama Dengan Kolega dan Pelanggan?', 'created_at' => now(), 'updated_at' => now()]);
        $e6_1 = DB::table('elemens')->insertGetId(['unit_id' => $u6, 'nama_elemen' => 'Melakukan komunikasi di tempat kerja', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e6_1, 'deskripsi_kriteria' => 'Komunikasi dengan tamu dan kolega dilaksanakan secara terbuka, profesional, ramah dan sopan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e6_1, 'deskripsi_kriteria' => 'Bahasa dan nada yang cocok digunakan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e6_2 = DB::table('elemens')->insertGetId(['unit_id' => $u6, 'nama_elemen' => 'Memberikan bantuan untuk tamu internal dan eksternal', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e6_2, 'deskripsi_kriteria' => 'Kebutuhan dan harapan tamu diidentifikasi secara benar.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e6_2, 'deskripsi_kriteria' => 'Karyawan berkomunikasi dengan tamu dan dilayani dengan ramah dan sopan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e6_3 = DB::table('elemens')->insertGetId(['unit_id' => $u6, 'nama_elemen' => 'Menjaga standar kinerja presentasi personal', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e6_3, 'deskripsi_kriteria' => 'Standar kinerja yang tinggi digunakan untuk melakukan pekerjaan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e6_3, 'deskripsi_kriteria' => 'Persyaratan presentasi khusus untuk fungsi kerja khusus dipenuhi.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e6_4 = DB::table('elemens')->insertGetId(['unit_id' => $u6, 'nama_elemen' => 'Melakukan kerja dalam tim', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e6_4, 'deskripsi_kriteria' => 'Kepercayaan, dukungan dan hormat diperlihatkan kepada anggota tim.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e6_4, 'deskripsi_kriteria' => 'Tujuan kerja tim secara bersama diidentifikasi.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 7
        $u7 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.150.2', 'judul_unit' => 'Melakukan Kerja Dalam Lingkungan Sosial yang Beragam', 'pertanyaan_unit' => 'Dapatkah Saya Melakukan Kerja Dalam Lingkungan Sosial yang Beragam?', 'created_at' => now(), 'updated_at' => now()]);
        $e7_1 = DB::table('elemens')->insertGetId(['unit_id' => $u7, 'nama_elemen' => 'Melakukan komunikasi dengan pelanggan dan kolega dari latar belakang yang beragam', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e7_1, 'deskripsi_kriteria' => 'Pelanggan dan kolega dari seluruh kelompok budaya dinilai dan diperlakukan dengan hormat.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e7_1, 'deskripsi_kriteria' => 'Komunikasi lisan dan non-lisan dipertimbangkan dengan perbedaan budaya.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e7_2 = DB::table('elemens')->insertGetId(['unit_id' => $u7, 'nama_elemen' => 'Menangani kesalahpahaman antar budaya', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e7_2, 'deskripsi_kriteria' => 'Hal-hal yang dapat menimbulkan kesalahpahaman di tempat kerja harus diidentifikasi.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e7_2, 'deskripsi_kriteria' => 'Usaha dilakukan untuk memecahkan masalah kesalahpahaman dengan pertimbangan budaya.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 8
        $u8 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.151.2', 'judul_unit' => 'Mengikuti Prosedur Kesehatan, Keselamatan, dan Keamanan di Tempat Kerja', 'pertanyaan_unit' => 'Dapatkah Saya Mengikuti Prosedur Kesehatan, Keselamatan, dan Keamanan di Tempat Kerja?', 'created_at' => now(), 'updated_at' => now()]);
        $e8_1 = DB::table('elemens')->insertGetId(['unit_id' => $u8, 'nama_elemen' => 'Mengikuti prosedur tempat kerja dan memberikan umpan balik tentang kesehatan, keselamatan dan keamanan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e8_1, 'deskripsi_kriteria' => 'Prosedur kesehatan, keselamatan dan keamanan diikuti secara benar.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e8_1, 'deskripsi_kriteria' => 'Pelanggaran prosedur diproses dengan segera.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e8_2 = DB::table('elemens')->insertGetId(['unit_id' => $u8, 'nama_elemen' => 'Menangani situasi darurat', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e8_2, 'deskripsi_kriteria' => 'Situasi darurat yang potensial segera ditindaklanjuti.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e8_2, 'deskripsi_kriteria' => 'Prosedur keadaan darurat dipatuhi secara benar.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e8_3 = DB::table('elemens')->insertGetId(['unit_id' => $u8, 'nama_elemen' => 'Menjaga standar presentasi perorangan yang aman', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e8_3, 'deskripsi_kriteria' => 'Penampilan personil/grooming yang pantas dijaga.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e8_3, 'deskripsi_kriteria' => 'Sikap yang baik dipresentasikan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 9
        $u9 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.152.2', 'judul_unit' => 'Mengembangkan Pengetahuan Tentang Industri Perhotelan', 'pertanyaan_unit' => 'Dapatkah Saya Mengembangkan Pengetahuan Tentang Industri Perhotelan?', 'created_at' => now(), 'updated_at' => now()]);
        $e9_1 = DB::table('elemens')->insertGetId(['unit_id' => $u9, 'nama_elemen' => 'Mencari informasi tentang industri perhotelan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e9_1, 'deskripsi_kriteria' => 'Sumber-sumber informasi dalam industri perhotelan diakses secara benar.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e9_1, 'deskripsi_kriteria' => 'Informasi tentang industri diterapkan dengan benar pada aktifitas kerja harian.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e9_2 = DB::table('elemens')->insertGetId(['unit_id' => $u9, 'nama_elemen' => 'Meningkatkan pengetahuan bidang industri perhotelan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e9_2, 'deskripsi_kriteria' => 'Pengetahuan umum dalam industri perhotelan diperbaharui dengan riset.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e9_2, 'deskripsi_kriteria' => 'Pengetahuan yang terbaru diberikan kepada pelanggan dan kolega.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 10
        $u10 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.154.2', 'judul_unit' => 'Mempromosikan Produk dan Jasa Kepada Pelanggan', 'pertanyaan_unit' => 'Dapatkah Saya Mempromosikan Produk dan Jasa Kepada Pelanggan?', 'created_at' => now(), 'updated_at' => now()]);
        $e10_1 = DB::table('elemens')->insertGetId(['unit_id' => $u10, 'nama_elemen' => 'Mengembangkan pengetahuan produk/jasa dan pasar', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e10_1, 'deskripsi_kriteria' => 'Kesempatan yang ada diambil untuk mengembangkan pengetahuan tentang produk/jasa.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e10_1, 'deskripsi_kriteria' => 'Riset formal dan informal digunakan untuk memperbaharui pengetahuan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e10_2 = DB::table('elemens')->insertGetId(['unit_id' => $u10, 'nama_elemen' => 'Mendorong pelanggan menggunakan dan membeli produk dan jasa', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e10_2, 'deskripsi_kriteria' => 'Informasi akurat tentang produk dan jasa ditawarkan kepada pelanggan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e10_2, 'deskripsi_kriteria' => 'Teknik penjualan digunakan untuk mendorong penggunaan dan pembelian produk jasa.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 11
        $u11 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.155.2', 'judul_unit' => 'Menangani Situasi Konflik', 'pertanyaan_unit' => 'Dapatkah Saya Menangani Situasi Konflik?', 'created_at' => now(), 'updated_at' => now()]);
        $e11_1 = DB::table('elemens')->insertGetId(['unit_id' => $u11, 'nama_elemen' => 'Mengidentifikasi situasi konflik', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e11_1, 'deskripsi_kriteria' => 'Potensi konflik harus cepat diidentifikasi dan tindakan cepat diambil.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e11_1, 'deskripsi_kriteria' => 'Situasi dimana keselamatan personal terancam harus diidentifikasi secara cepat.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e11_2 = DB::table('elemens')->insertGetId(['unit_id' => $u11, 'nama_elemen' => 'Memecahkan situasi konflik', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e11_2, 'deskripsi_kriteria' => 'Tanggung jawab diambil untuk mencari solusi konflik.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e11_2, 'deskripsi_kriteria' => 'Keterampilan komunikasi efektif digunakan untuk menangani konflik.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e11_3 = DB::table('elemens')->insertGetId(['unit_id' => $u11, 'nama_elemen' => 'Memberi tanggapan terhadap keluhan pelanggan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e11_3, 'deskripsi_kriteria' => 'Keluhan ditangani secara bijaksana, sopan dan ramah.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e11_3, 'deskripsi_kriteria' => 'Tindakan yang tepat diambil untuk memecahkan masalah keluhan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 12
        $u12 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.163.2', 'judul_unit' => 'Menyediakan Pertolongan Pertama', 'pertanyaan_unit' => 'Dapatkah Saya Menyediakan Pertolongan Pertama?', 'created_at' => now(), 'updated_at' => now()]);
        $e12_1 = DB::table('elemens')->insertGetId(['unit_id' => $u12, 'nama_elemen' => 'Memilih dan menata peralatan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e12_1, 'deskripsi_kriteria' => 'Situasi darurat dikenali secara cepat dan benar.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e12_1, 'deskripsi_kriteria' => 'Bantuan dari layanan darurat/kolega diorganisir bila diperlukan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e12_2 = DB::table('elemens')->insertGetId(['unit_id' => $u12, 'nama_elemen' => 'Memberikan perawatan yang tepat', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e12_2, 'deskripsi_kriteria' => 'Kondisi fisik pasien dinilai dari tanda-tanda vital yang tampak.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e12_2, 'deskripsi_kriteria' => 'Pertolongan pertama diberikan untuk memulihkan kondisi pasien.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e12_3 = DB::table('elemens')->insertGetId(['unit_id' => $u12, 'nama_elemen' => 'Memonitor situasi', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e12_3, 'deskripsi_kriteria' => 'Peralatan dibersihkan setelah digunakan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e12_3, 'deskripsi_kriteria' => 'Informasi tentang kondisi korban disampaikan kepada personil layanan darurat.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e12_4 = DB::table('elemens')->insertGetId(['unit_id' => $u12, 'nama_elemen' => 'Menyiapkan laporan insiden', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e12_4, 'deskripsi_kriteria' => 'Situasi darurat didokumentasikan sesuai dengan prosedur perusahaan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e12_4, 'deskripsi_kriteria' => 'Laporan diberikan secara jelas, akurat dan tepat waktu.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 13
        $u13 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.164.2', 'judul_unit' => 'Melakukan Prosedur Administrasi', 'pertanyaan_unit' => 'Dapatkah Saya Melakukan Prosedur Administrasi?', 'created_at' => now(), 'updated_at' => now()]);
        $e13_1 = DB::table('elemens')->insertGetId(['unit_id' => $u13, 'nama_elemen' => 'Memproses dokumen-dokumen kantor', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e13_1, 'deskripsi_kriteria' => 'Dokumen diproses sesuai dengan prosedur perusahaan dalam jangka waktu yang ditentukan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e13_1, 'deskripsi_kriteria' => 'Perlengkapan kantor digunakan secara benar untuk memproses dokumen.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e13_2 = DB::table('elemens')->insertGetId(['unit_id' => $u13, 'nama_elemen' => 'Membuat draft korespondensi yang sederhana', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e13_2, 'deskripsi_kriteria' => 'Teks ditulis dengan menggunakan bahasa yang tepat dan jelas.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e13_2, 'deskripsi_kriteria' => 'Ejaan, tanda baca dan tata bahasa harus benar.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e13_3 = DB::table('elemens')->insertGetId(['unit_id' => $u13, 'nama_elemen' => 'Menjaga sistem dokumen', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e13_3, 'deskripsi_kriteria' => 'Dokumen diarsipkan/disimpan sesuai dengan prosedur keamanan kantor.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e13_3, 'deskripsi_kriteria' => 'Referensi dan sistem indeks diubah dan ditingkatkan sesuai prosedur.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 14
        $u14 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.166.2', 'judul_unit' => 'Menyiapkan Dokumen Bisnis', 'pertanyaan_unit' => 'Dapatkah Saya Menyiapkan Dokumen Bisnis?', 'created_at' => now(), 'updated_at' => now()]);
        $e14_1 = DB::table('elemens')->insertGetId(['unit_id' => $u14, 'nama_elemen' => 'Menentukan persyaratan dokumen', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e14_1, 'deskripsi_kriteria' => 'Persyaratan dan tujuan didefinisikan secara jelas dengan berkonsultasi dengan kolega yang tepat.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e14_1, 'deskripsi_kriteria' => 'Bantuan para ahli diperoleh bila perlu dalam parameter anggaran.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e14_2 = DB::table('elemens')->insertGetId(['unit_id' => $u14, 'nama_elemen' => 'Melaksanakan riset', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e14_2, 'deskripsi_kriteria' => 'Riset dilaksanakan sesuai dengan ruang lingkup proyek.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e14_2, 'deskripsi_kriteria' => 'Metode pengumpulan data formal dan informal digunakan dengan tepat.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e14_3 = DB::table('elemens')->insertGetId(['unit_id' => $u14, 'nama_elemen' => 'Menyusun dokumen', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e14_3, 'deskripsi_kriteria' => 'Struktur dan isi dokumen dikembangkan agar mencerminkan tujuan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e14_3, 'deskripsi_kriteria' => 'Jenis presentasi tertulis dan teknik grafis digunakan untuk meningkatkan dampak.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e14_4 = DB::table('elemens')->insertGetId(['unit_id' => $u14, 'nama_elemen' => 'Menindaklanjuti dokumen', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e14_4, 'deskripsi_kriteria' => 'Dokumen disajikan/diedarkan bila perlu.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e14_4, 'deskripsi_kriteria' => 'Rekomendasi ditinjau kembali dan ditindaklanjuti sesuai prioritas.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 15
        $u15 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.206.2', 'judul_unit' => 'Memulai Percakapan dan Mengembangkan Hubungan Baik Dengan Tamu', 'pertanyaan_unit' => 'Dapatkah Saya Memulai Percakapan dan Mengembangkan Hubungan Baik Dengan Tamu?', 'created_at' => now(), 'updated_at' => now()]);
        $e15_1 = DB::table('elemens')->insertGetId(['unit_id' => $u15, 'nama_elemen' => 'Menyediakan dan meminta informasi tentang topik yang sudah familiar', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e15_1, 'deskripsi_kriteria' => 'Teknik percakapan ditangani untuk memeriksa pemahaman.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e15_1, 'deskripsi_kriteria' => 'Konstruksi gramatika digunakan untuk menyampaikan maksud dengan jelas.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e15_2 = DB::table('elemens')->insertGetId(['unit_id' => $u15, 'nama_elemen' => 'Menunjukan pemahaman atas struktur percakapan tidak resmi', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e15_2, 'deskripsi_kriteria' => 'Percakapan tidak resmi dibuka dan ditutup secara tepat.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e15_2, 'deskripsi_kriteria' => 'Teknik klarifikasi dan timbal balik digunakan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e15_3 = DB::table('elemens')->insertGetId(['unit_id' => $u15, 'nama_elemen' => 'Menanggapi secara tepat atas keluhan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e15_3, 'deskripsi_kriteria' => 'Pemahaman tentang sifat keluhan didemonstrasikan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e15_3, 'deskripsi_kriteria' => 'Kemungkinan solusi ditawarkan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 16
        $u16 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.207.2', 'judul_unit' => 'Melakukan Percakapan Singkat di Telepon', 'pertanyaan_unit' => 'Dapatkah Saya Melakukan Percakapan Singkat di Telepon?', 'created_at' => now(), 'updated_at' => now()]);
        $e16_1 = DB::table('elemens')->insertGetId(['unit_id' => $u16, 'nama_elemen' => 'Memberi tanggapan terhadap telepon masuk', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e16_1, 'deskripsi_kriteria' => 'Salam yang tepat diberikan yang mencakup nama perusahaan dan orang.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e16_1, 'deskripsi_kriteria' => 'Bantuan ditawarkan pada penelepon dengan menggunakan pernyataan patokan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e16_2 = DB::table('elemens')->insertGetId(['unit_id' => $u16, 'nama_elemen' => 'Menerima pesan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e16_2, 'deskripsi_kriteria' => 'Pesan telepon dicatat secara akurat.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e16_2, 'deskripsi_kriteria' => 'Pesan harus dikonfirmasikan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e16_3 = DB::table('elemens')->insertGetId(['unit_id' => $u16, 'nama_elemen' => 'Mengakhiri hubungan telepon', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e16_3, 'deskripsi_kriteria' => 'Tutup percakapan secara tepat.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e16_3, 'deskripsi_kriteria' => 'Meletakkan gagang telepon pada posisi yang tepat.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e16_4 = DB::table('elemens')->insertGetId(['unit_id' => $u16, 'nama_elemen' => 'Membuat hubungan telepon', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e16_4, 'deskripsi_kriteria' => 'Membuat tujuan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e16_4, 'deskripsi_kriteria' => 'Nyatakan tujuan secara jelas.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 17
        $u17 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.213.2', 'judul_unit' => 'Menulis Pesan Singkat', 'pertanyaan_unit' => 'Dapatkah Saya Menulis Pesan Singkat?', 'created_at' => now(), 'updated_at' => now()]);
        $e17_1 = DB::table('elemens')->insertGetId(['unit_id' => $u17, 'nama_elemen' => 'Memahami tujuan pesan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e17_1, 'deskripsi_kriteria' => 'Maksud dari percakapan singkat dikutip.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e17_1, 'deskripsi_kriteria' => 'Penentuan maksud dari kalimat utama ditangkap.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e17_2 = DB::table('elemens')->insertGetId(['unit_id' => $u17, 'nama_elemen' => 'Menulis secara jelas, kalimat yang mudah dipahami', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e17_2, 'deskripsi_kriteria' => 'Struktur yang tepat digunakan di awal, tengah dan akhir.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e17_2, 'deskripsi_kriteria' => 'Kosa kata utama yang berkaitan dengan subject digunakan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 18
        $u18 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HRD00.217.2', 'judul_unit' => 'Berkomunikasi Secara Lisan dalam Bahasa Inggris pada Tingkat Operasional Dasar', 'pertanyaan_unit' => 'Dapatkah Saya Berkomunikasi Secara Lisan dalam Bahasa Inggris pada Tingkat Operasional Dasar?', 'created_at' => now(), 'updated_at' => now()]);
        $e18_1 = DB::table('elemens')->insertGetId(['unit_id' => $u18, 'nama_elemen' => 'Berkomunikasi dengan pelanggan dan kolega mengenai kegiatan rutin dan tidak rutin', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e18_1, 'deskripsi_kriteria' => 'Keluhan pelanggan ditanggapi.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e18_1, 'deskripsi_kriteria' => 'Informasi dan saran secara rinci diberikan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e18_2 = DB::table('elemens')->insertGetId(['unit_id' => $u18, 'nama_elemen' => 'Berperan serta dalam diskusi kelompok', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e18_2, 'deskripsi_kriteria' => 'Penelitian yang sesuai dengan topik dibuat.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e18_2, 'deskripsi_kriteria' => 'Pendapat diberikan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e18_3 = DB::table('elemens')->insertGetId(['unit_id' => $u18, 'nama_elemen' => 'Membuat presentasi lisan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e18_3, 'deskripsi_kriteria' => 'Presentasi dibuat yang sesuai dengan pertemuan atau diskusi.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e18_3, 'deskripsi_kriteria' => 'Presentasi disiapkan berdasarkan prinsip pembelajaran orang dewasa.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 19
        $u19 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.218.2', 'judul_unit' => 'Melaksanakan Tugas Perlindungan Anak yang Relevan dengan Industri Pariwisata', 'pertanyaan_unit' => 'Dapatkah Saya Melaksanakan Tugas Perlindungan Anak yang Relevan dengan Industri Pariwisata?', 'created_at' => now(), 'updated_at' => now()]);
        $e19_1 = DB::table('elemens')->insertGetId(['unit_id' => $u19, 'nama_elemen' => 'Mengidentifikasi masalah eksploitasi seksual anak oleh wisatawan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e19_1, 'deskripsi_kriteria' => 'Masalah eksploitasi anak terhadap anak oleh wisatawan ditentukan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e19_1, 'deskripsi_kriteria' => 'Dampak eksploitasi seksual anak dijelaskan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e19_2 = DB::table('elemens')->insertGetId(['unit_id' => $u19, 'nama_elemen' => 'Menjelaskan tindakan nasional regional dan international untuk mencegah eksploitasi seksual anak oleh wisatawan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e19_2, 'deskripsi_kriteria' => 'Konvensi PBB tentang hak azasi manusia dicari dan diketahui.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e19_2, 'deskripsi_kriteria' => 'Inisiatif nasional, regional dan international diperiksa.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e19_3 = DB::table('elemens')->insertGetId(['unit_id' => $u19, 'nama_elemen' => 'Menjelaskan tindakan yang bisa dilakukan di tempat kerja untuk melindungi anak', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e19_3, 'deskripsi_kriteria' => 'Daftar tindakan yang dapat diambil disiapkan oleh staf.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e19_3, 'deskripsi_kriteria' => 'Tindakan pencegahan eksploitasi seksual anak dijelaskan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 20
        $u20 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.220.2', 'judul_unit' => 'Mengembangkan Lingkungan Yang Aman Bagi Anak-Anak di Tujuan Pariwisata', 'pertanyaan_unit' => 'Dapatkah Saya Mengembangkan Lingkungan Yang Aman Bagi Anak-Anak di Tujuan Pariwisata?', 'created_at' => now(), 'updated_at' => now()]);
        $e20_1 = DB::table('elemens')->insertGetId(['unit_id' => $u20, 'nama_elemen' => 'Mengidentifikasi kebutuhan hotel dan perjalanan industri untuk berkomitmen untuk praktek-praktek yang mencegah eksploitasi seksual anak oleh wisatawan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e20_1, 'deskripsi_kriteria' => 'Masalah eksploitasi seksual anak oleh wisatawan didefinisikan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e20_1, 'deskripsi_kriteria' => 'Masalah perlindungan anak di pariwisata ditemukan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e20_2 = DB::table('elemens')->insertGetId(['unit_id' => $u20, 'nama_elemen' => 'Mengevaluasi operasi kerja di hotel dan perjalanan industri untuk menerapkan langkah-langkah pencegahan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e20_2, 'deskripsi_kriteria' => 'Peran dan fungsi dalam hotel dan biro perjalanan dijelaskan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e20_2, 'deskripsi_kriteria' => 'Peluang dan metode untuk memperkenalkan langkah perlindungan anak dinilai.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 21
        $u21 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.226.2', 'judul_unit' => 'Bekerja Secara Kooperatif dalam Lingkungan Administrasi Umum', 'pertanyaan_unit' => 'Dapatkah Saya Bekerja Secara Kooperatif dalam Lingkungan Administrasi Umum?', 'created_at' => now(), 'updated_at' => now()]);
        $e21_1 = DB::table('elemens')->insertGetId(['unit_id' => $u21, 'nama_elemen' => 'Berkomunikasi di tempat kerja', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e21_1, 'deskripsi_kriteria' => 'Komunikasi dengan kolega dan pelanggan dilakukan secara terbuka, profesional dan santun.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e21_1, 'deskripsi_kriteria' => 'Menggunakan bahasa dan nada suara yang tepat.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e21_2 = DB::table('elemens')->insertGetId(['unit_id' => $u21, 'nama_elemen' => 'Menyediakan bantuan kepada pelanggan di dalam dan di luar perusahaan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e21_2, 'deskripsi_kriteria' => 'Kebutuhan dan harapan pelanggan diidentifikasi secara benar.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e21_2, 'deskripsi_kriteria' => 'Semua komunikasi dengan pelanggan dilakukan secara ramah dan sopan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e21_3 = DB::table('elemens')->insertGetId(['unit_id' => $u21, 'nama_elemen' => 'Memelihara standar presentasi pribadi', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e21_3, 'deskripsi_kriteria' => 'Standar unggul dari presentasi pribadi dilatih.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e21_3, 'deskripsi_kriteria' => 'Kebutuhan presentasi khusus untuk fungsi pekerjaan khusus dilatih.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e21_4 = DB::table('elemens')->insertGetId(['unit_id' => $u21, 'nama_elemen' => 'Bekerja dalam administrasi umum', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e21_4, 'deskripsi_kriteria' => 'Kepercayaan, dorongan dan rasa hormat ditunjukkan kepada anggota tim.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e21_4, 'deskripsi_kriteria' => 'Tujuan kerja tim diidentifikasi bersama-sama.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 22
        $u22 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.229.2', 'judul_unit' => 'Membangun dan Memelihara Tempat Kerja yang Aman', 'pertanyaan_unit' => 'Dapatkah Saya Membangun dan Memelihara Tempat Kerja yang Aman?', 'created_at' => now(), 'updated_at' => now()]);
        $e22_1 = DB::table('elemens')->insertGetId(['unit_id' => $u22, 'nama_elemen' => 'Membina dan menjaga rangka kerja untuk kesehatan, keselamatan dan keamanan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e22_1, 'deskripsi_kriteria' => 'Mengembangkan kebijakan kesehatan, keselamatan dan keamanan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e22_1, 'deskripsi_kriteria' => 'Tanggung jawab serta tugas kesehatan didefinisikan secara jelas.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e22_2 = DB::table('elemens')->insertGetId(['unit_id' => $u22, 'nama_elemen' => 'Membina dan menjaga pengaturan partisipasi untuk manajemen kesehatan, keselamatan dan keamanan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e22_2, 'deskripsi_kriteria' => 'Membuat dan mempertahankan proses konsultasi yang tepat.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e22_2, 'deskripsi_kriteria' => 'Menangani masalah yang timbul melalui partisipasi dan konsultasi.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e22_3 = DB::table('elemens')->insertGetId(['unit_id' => $u22, 'nama_elemen' => 'Membina dan menjaga prosedur untuk identifikasi dan menilai bahaya dan resiko', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e22_3, 'deskripsi_kriteria' => 'Mengidentifikasi bahaya dan resiko potensial dan menilai secara benar.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e22_3, 'deskripsi_kriteria' => 'Prosedur identifikasi secara terus-menerus dikembangkan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e22_4 = DB::table('elemens')->insertGetId(['unit_id' => $u22, 'nama_elemen' => 'Membina dan menjaga prosedur untuk kontrol bahaya dan resiko', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e22_4, 'deskripsi_kriteria' => 'Mengembangkan dan melaksanakan ukuran untuk mengontrol resiko yang dinilai.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e22_4, 'deskripsi_kriteria' => 'Prosedur kontrol resiko dikembangkan dan dipadukan dalam sistem kerja.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e22_5 = DB::table('elemens')->insertGetId(['unit_id' => $u22, 'nama_elemen' => 'Membina dan menjaga prosedur organisasional untuk menangani perayaan yang berbahaya', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e22_5, 'deskripsi_kriteria' => 'Mengidentifikasi secara benar perayaan yang berbahaya.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e22_5, 'deskripsi_kriteria' => 'Prosedur yang dapat mengontrol resiko dikembangkan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e22_6 = DB::table('elemens')->insertGetId(['unit_id' => $u22, 'nama_elemen' => 'Membina dan menjaga program pelatihan kesehatan dan keselamatan yang berhubungan dengan pekerjaan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e22_6, 'deskripsi_kriteria' => 'Program pelatihan kesehatan yang berhubungan dengan pekerjaan dikembangkan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e22_6, 'deskripsi_kriteria' => 'Program keselamatan yang berhubungan dengan pekerjaan dilaksanakan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e22_7 = DB::table('elemens')->insertGetId(['unit_id' => $u22, 'nama_elemen' => 'Membina dan menjaga sistem untuk catatan kesehatan dan keselamatan yang berhubungan dengan pekerjaan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e22_7, 'deskripsi_kriteria' => 'Membina dan memonitor sistem untuk menyimpan catatan kesehatan.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e22_7, 'deskripsi_kriteria' => 'Membina dan memonitor sistem untuk menyimpan catatan keselamatan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e22_8 = DB::table('elemens')->insertGetId(['unit_id' => $u22, 'nama_elemen' => 'Mengevaluasi sistem kesehatan dan keamanan yang berhubungan dengan pekerjaan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e22_8, 'deskripsi_kriteria' => 'Keefektifan sistem kesehatan, keselamatan dan keamanan dinilai.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e22_8, 'deskripsi_kriteria' => 'Peningkatan sistem kesehatan dan keselamatan dikembangkan.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 23
        $u23 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.230.2', 'judul_unit' => 'Membaca Bahasa Inggris pada Tingkat Lanjut', 'pertanyaan_unit' => 'Dapatkah Saya Membaca Bahasa Inggris pada Tingkat Lanjut?', 'created_at' => now(), 'updated_at' => now()]);
        $e23_1 = DB::table('elemens')->insertGetId(['unit_id' => $u23, 'nama_elemen' => 'Membaca dan menginterpretasikan dokumen kompleks di tempat kerja', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e23_1, 'deskripsi_kriteria' => 'Rincian bacaan dibaca dan dimengerti baik secara tersurat maupun tersirat.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e23_1, 'deskripsi_kriteria' => 'Grafik dianalisis dan diinterpretasikan dengan benar.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 24
        $u24 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.250.2', 'judul_unit' => 'Mencari dan Mendapatkan Data Komputer', 'pertanyaan_unit' => 'Dapatkah Saya Mencari dan Mendapatkan Data Komputer?', 'created_at' => now(), 'updated_at' => now()]);
        $e24_1 = DB::table('elemens')->insertGetId(['unit_id' => $u24, 'nama_elemen' => 'Membuka file', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e24_1, 'deskripsi_kriteria' => 'Komputer dihidupkan/diakses secara benar.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e24_1, 'deskripsi_kriteria' => 'Aplikasi dari perangkat lunak yang memadai dipilih dari menu yang tersedia.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e24_2 = DB::table('elemens')->insertGetId(['unit_id' => $u24, 'nama_elemen' => 'Memanggil dan mengubah data', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e24_2, 'deskripsi_kriteria' => 'Data yang dipanggil ditempatkan di dalam file.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e24_2, 'deskripsi_kriteria' => 'Alat input yang tepat digunakan untuk memasukkan, mengubah atau menghapus informasi.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e24_3 = DB::table('elemens')->insertGetId(['unit_id' => $u24, 'nama_elemen' => 'Menutup dan mengeluarkan file', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e24_3, 'deskripsi_kriteria' => 'File ditutup sesuai dengan prosedur perangkat lunak.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e24_3, 'deskripsi_kriteria' => 'Program dikeluarkan sesuai dengan prosedur perangkat lunak.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 25
        $u25 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.255.2', 'judul_unit' => 'Mengoperasikan Sistem Reservasi Komputer', 'pertanyaan_unit' => 'Dapatkah Saya Mengoperasikan Sistem Reservasi Komputer?', 'created_at' => now(), 'updated_at' => now()]);
        $e25_1 = DB::table('elemens')->insertGetId(['unit_id' => $u25, 'nama_elemen' => 'Mengakses dan menggunakan informasi dari sistem pemesanan komputer', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e25_1, 'deskripsi_kriteria' => 'Tampilan sistem pemesanan komputer diakses dan diinterpretasikan dengan teliti.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e25_1, 'deskripsi_kriteria' => 'Keistimewaan sistem digunakan untuk mengakses informasi.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e25_2 = DB::table('elemens')->insertGetId(['unit_id' => $u25, 'nama_elemen' => 'Membuat dan memproses pemesanan melalui sistem pemesanan komputer', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e25_2, 'deskripsi_kriteria' => 'Pemesanan baru dibuat dengan teliti sesuai dengan prosedur sistem.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e25_2, 'deskripsi_kriteria' => 'Semua data yang diperlukan dicatat dengan teliti.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e25_3 = DB::table('elemens')->insertGetId(['unit_id' => $u25, 'nama_elemen' => 'Mengirim dan menerima komunikasi melalui sistem pemesanan komputer', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e25_3, 'deskripsi_kriteria' => 'Komunikasi dengan kolega dibuat dan diproses melalui sistem.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e25_3, 'deskripsi_kriteria' => 'Komunikasi dengan kolega industri diakses pada waktu yang tepat.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // UNIT 26
        $u26 = DB::table('units')->insertGetId(['skema_id' => $skemaId, 'kode_unit' => 'I.55HDR00.256.2', 'judul_unit' => 'Mengawasi dan Memonitor Orang', 'pertanyaan_unit' => 'Dapatkah Saya Mengawasi dan Memonitor Orang?', 'created_at' => now(), 'updated_at' => now()]);
        $e26_1 = DB::table('elemens')->insertGetId(['unit_id' => $u26, 'nama_elemen' => 'Menyiapkan untuk memonitor/mengamati', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e26_1, 'deskripsi_kriteria' => 'Instruksi tugas dikonfirmasikan dengan klien.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e26_1, 'deskripsi_kriteria' => 'Perlengkapan yang tepat untuk menjalankan tugas diseleksi dan diuji.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e26_2 = DB::table('elemens')->insertGetId(['unit_id' => $u26, 'nama_elemen' => 'Memeriksa (ID card) KTP', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e26_2, 'deskripsi_kriteria' => 'Memeriksa KTP pada saat memasuki lokasi dan pastikan tampil di monitor.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e26_2, 'deskripsi_kriteria' => 'Pemeriksaan secara cepat dilaksanakan secara teratur.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e26_3 = DB::table('elemens')->insertGetId(['unit_id' => $u26, 'nama_elemen' => 'Memonitor area yang dapat dimasuki', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e26_3, 'deskripsi_kriteria' => 'Area yang hanya dapat dimasuki dengan akses dimonitor dengan kamera.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e26_3, 'deskripsi_kriteria' => 'Area yang hanya dapat dimasuki dengan akses dimonitor oleh personil.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e26_4 = DB::table('elemens')->insertGetId(['unit_id' => $u26, 'nama_elemen' => 'Mengamati/memonitor barang-barang yang tidak terjaga', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e26_4, 'deskripsi_kriteria' => 'Barang-barang yang tidak dijaga dimonitor dan diperiksa.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e26_4, 'deskripsi_kriteria' => 'Barang yang tidak terjaga dan mencurigakan ditutup dan polisi diberitahu.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e26_5 = DB::table('elemens')->insertGetId(['unit_id' => $u26, 'nama_elemen' => 'Merespon orang-orang yang bersikap mencurigakan', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e26_5, 'deskripsi_kriteria' => 'Perorangan atau kelompok yang bersikap mencurigakan diidentifikasi dan diawasi.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e26_5, 'deskripsi_kriteria' => 'Insiden yang mencurigakan dicatat dengan menggunakan tape video pengawas.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $e26_6 = DB::table('elemens')->insertGetId(['unit_id' => $u26, 'nama_elemen' => 'Menanggapi sikap yang mencurigakan atau yang melanggar hukum', 'created_at' => now(), 'updated_at' => now()]);
        DB::table('kriteria')->insert([
            ['elemen_id' => $e26_6, 'deskripsi_kriteria' => 'Komitmen pelanggar hukum atau sikap mencurigakan diidentifikasi.', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['elemen_id' => $e26_6, 'deskripsi_kriteria' => 'Tingkat tanggapan yang tepat diidentifikasi sesuai dengan hukum yang berlaku.', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->command->info('✓ Skema Guest Service Agent untuk HTL berhasil dibuat!');
        $this->command->info('✓ 26 Unit Kompetensi berhasil ditambahkan!');
        $this->command->info('✓ 75 Elemen berhasil ditambahkan!');
        $this->command->info('✓ Total ~150 Kriteria berhasil ditambahkan!');
    }
}
