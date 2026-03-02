<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkemaAKLSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Skema: KKNI Level II Akuntansi dan Keuangan Lembaga untuk Jurusan AKL
     * 7 Unit Kompetensi
     */
    public function run(): void
    {
        // Ambil ID jurusan AKL
        $jurusanAKL = DB::table('jurusan')
            ->where('kode_jurusan', 'AKL')
            ->value('ID_jurusan');

        if (!$jurusanAKL) {
            $this->command->warn('Jurusan AKL tidak ditemukan! Skema akan dibuat tanpa jurusan.');
        }

        // 1. Buat skema KKNI Level II Akuntansi dan Keuangan Lembaga untuk AKL
        $skemaId = DB::table('skemas')->insertGetId([
            'nomor_skema' => 'SKM/BNSP/00013/1/2020/32',
            'nama_skema' => 'KKNI Level II Akuntansi dan Keuangan Lembaga',
            'jenis_skema' => 'KKNI',
            'jurusan_id' => $jurusanAKL,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ========== UNIT 1: Menerapkan Prinsip Praktik Profesional dalam Bekerja ==========
        $unitId1 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'M.692000.001.02',
            'judul_unit' => 'Menerapkan Prinsip Praktik Profesional dalam Bekerja',
            'pertanyaan_unit' => 'Dapatkah Saya Menerapkan Prinsip Praktik Profesional dalam Bekerja?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 1.1
        $elemenId1_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Mengidentifikasi luas, sektor dan tanggung jawab industri',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Aspek-aspek eksternal yang mempengaruhi profesi teknisi akuntansi diidentifikasi dalam menjalankan pekerjaan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Peran dan tanggung jawab berbagai pihak yang terlibat dalam profesi teknisi akuntansi diidentifikasi dalam menjalankan pekerjaan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 1.2
        $elemenId1_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Menerapkan pedoman, prosedur dan aturan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Informasi yang berhubungan dengan hukum, peraturan dan kode etik dikumpulkan dan dianalisa.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Informasi yang berhubungan dengan hukum, peraturan dan kode etik dalam kaitannya dengan pihak yang bersangkutan di tempat kerja ditentukan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Ketentuan tentang praktik yang relevan dipergunakan sebagai dasar untuk menjalankan pekerjaan dan pengambilan keputusan secara beretika.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 1.3
        $elemenId1_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Mengelola informasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_3,
                'deskripsi_kriteria' => 'Dokumen, laporan, data dan kalkulasi dianalisis dan diorganisir sesuai kebutuhan konsumen dan/atau organisasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_3,
                'deskripsi_kriteria' => 'Informasi disajikan dalam format yang sesuai dengan kebutuhan pengguna informasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 1.4
        $elemenId1_4 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Merencanakan penyelesaian pekerjaan dengan mempertimbangkan keterbatasan waktu dan sumber daya',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_4,
                'deskripsi_kriteria' => 'Tugas yang harus diselesaikan dan kondisi yang relevan ditentukan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_4,
                'deskripsi_kriteria' => 'Pekerjaan direncanakan secara mandiri maupun secara tim untuk periode tertentu dengan mempertimbangkan sumber daya, waktu dan skala prioritas.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_4,
                'deskripsi_kriteria' => 'Perubahan teknologi dan organisasi kerja dapat diadaptasi.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 1.5
        $elemenId1_5 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Merancang dan mengelola kompetensi personal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_5,
                'deskripsi_kriteria' => 'Kebutuhan pengembangan kompetensi dan sasaran pengembangan diidentifikasi dan dikaji ulang secara periodik.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_5,
                'deskripsi_kriteria' => 'Kebutuhan kompetensi, otorisasi dan lisensi diidentifikasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_5,
                'deskripsi_kriteria' => 'Kesempatan pengembangan profesional yang menggambarkan kebutuhan dan sasaran diselesaikan dalam jangka waktu tertentu.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 2: Menerapkan Praktik-Praktik Kesehatan dan Keselamatan di Tempat Kerja ==========
        $unitId2 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'M.692000.002.02',
            'judul_unit' => 'Menerapkan Praktik-Praktik Kesehatan dan Keselamatan di Tempat Kerja',
            'pertanyaan_unit' => 'Dapatkah Saya Menerapkan Praktik-Praktik Kesehatan dan Keselamatan di Tempat Kerja?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 2.1
        $elemenId2_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Mengikuti prosedur kerja untuk mengidentifikasi bahaya dan pengendalian resiko',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Bahaya di tempat kerja dikenali dan dilaporkan kepada yang berwenang sesuai dengan prosedur tempat kerja.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Prosedur tempat kerja dan instruksi kerja untuk mengendalikan resiko diikuti secara akurat.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Prosedur tempat kerja yang berkaitan dengan kecelakaan, api dan darurat diikuti dimana diperlukan dalam lingkup penyebab dan kompetensi karyawan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Seluruh area kerja dijaga tetap bersih dan bebas dari gangguan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Seluruh pintu darurat dikenali dan bebas setiap waktu.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 2.2
        $elemenId2_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Berkontribusi untuk berpartisipasi dalam pengaturan manajemen kesehatan dan keselamatan kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_2,
                'deskripsi_kriteria' => 'Isu-isu kesehatan dan keselamatan kerja diinformasikan kepada aparat yang berwenang sesuai dengan prosedur tempat kerja yang relevan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_2,
                'deskripsi_kriteria' => 'Kontribusi kepada manajemen kesehatan dan keselamatan kerja di tempat kerja dibuat sesuai dengan kebijakan dan prosedur organisasi dan dalam lingkup tanggung jawab dan kompetensi karyawan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_2,
                'deskripsi_kriteria' => 'Dokumen kesehatan dan keselamatan kerja yang relevan diidentifikasi, secara periodik diperiksa dan rekomendasinya ditindaklanjuti.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_2,
                'deskripsi_kriteria' => 'Klarifikasi kewajiban, prosedur dan praktik-praktik kesehatan dan keselamatan kerja ditinjau kembali bila diperlukan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 2.3
        $elemenId2_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Menerapkan praktik-praktik kesehatan dan keselamatan kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_3,
                'deskripsi_kriteria' => 'Prosedur kesehatan dan keselamatan kerja diterapkan setiap waktu dalam pekerjaan sehari-hari.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_3,
                'deskripsi_kriteria' => 'Peringatan bahaya dan tanda-tanda keselamatan dikenali dan diobservasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_3,
                'deskripsi_kriteria' => 'Teknik-teknik penanganan keselamatan secara manual dan teknik keselamatan operasi peralatan diterapkan setiap waktu.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_3,
                'deskripsi_kriteria' => 'Prosedur pertolongan pertama secara darurat diikuti.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_3,
                'deskripsi_kriteria' => 'Situasi yang secara potensial berbahaya diidentifikasi, meliputi kegagalan dan peralatan berbahaya, secara langsung dilaporkan.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 3: Memproses Entry Jurnal ==========
        $unitId3 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'M.692000.007.02',
            'judul_unit' => 'Memproses Entry Jurnal',
            'pertanyaan_unit' => 'Dapatkah Saya Memproses Entry Jurnal?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 3.1
        $elemenId3_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Memeriksa dokumen sumber dan dokumen pendukung',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Dokumen sumber dan dokumen pendukung diperiksa.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Otorisasi oleh pihak yang berwenang dalam dokumen sumber diperiksa.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 3.2
        $elemenId3_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Mencatat dokumen sumber ke dalam jurnal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Jurnal diotorisasi sesuai dengan kebijakan dan prosedur perusahaan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Dokumen sumber dicatat ke dalam jurnal secara akurat dan sesuai dengan standar yang ditetapkan perusahaan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Transaksi secara tepat dialokasikan ke dalam sistem dan akun.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 3.3
        $elemenId3_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Mengarsipkan dokumen sumber dan dokumen pendukung',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_3,
                'deskripsi_kriteria' => 'Dokumen sumber dan pendukung disimpan secara tepat waktu sesuai dengan prosedur dan kebijakan perusahaan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_3,
                'deskripsi_kriteria' => 'Arsip dokumen diakses dan ditelusuri sesuai kebijakan perusahaan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 4: Memproses Buku Besar ==========
        $unitId4 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'M.692000.008.02',
            'judul_unit' => 'Memproses Buku Besar',
            'pertanyaan_unit' => 'Dapatkah Saya Memproses Buku Besar?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 4.1
        $elemenId4_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Mempersiapkan pengelolaan buku besar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Peralatan dan perlengkapan yang dibutuhkan untuk pengelolaan buku besar disediakan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Buku besar yang diperlukan disediakan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Rekapitulasi jurnal disajikan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 4.2
        $elemenId4_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Membukukan jumlah angka dari jurnal ke buku besar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Akun-akun dalam buku besar yang diperlukan diidentifikasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Jumlah yang ada dalam rekapitulasi jurnal dibukukan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 4.3
        $elemenId4_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Menyusun daftar saldo akun dalam buku besar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_3,
                'deskripsi_kriteria' => 'Daftar saldo akun dalam buku besar disajikan sesuai dengan format yang telah ditetapkan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_3,
                'deskripsi_kriteria' => 'Saldo akun dalam buku besar dipastikan kebenarannya.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 5: Menyusun Laporan Keuangan ==========
        $unitId5 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'M.692000.013.02',
            'judul_unit' => 'Menyusun Laporan Keuangan',
            'pertanyaan_unit' => 'Dapatkah Saya Menyusun Laporan Keuangan?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 5.1
        $elemenId5_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Mencatat jurnal penyesuaian',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Dokumen sumber penyesuaian disediakan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Akun-akun yang memerlukan jurnal penyesuaian diidentifikasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Jurnal penyesuaian yang diperlukan dicatat.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 5.2
        $elemenId5_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Menyajikan laporan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Neraca lajur disiapkan sesuai ketentuan SOP.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Laporan laba rugi disajikan sesuai ketentuan SOP/SAK/SAK ETAP.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Laporan neraca/laporan posisi keuangan disajikan sesuai ketentuan SOP/SAK/SAK ETAP.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Laporan Perubahan ekuitas disajikan sesuai ketentuan SOP/SAK/SAK ETAP.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Laporan arus kas disajikan sesuai ketentuan SOP/SAK/SAK ETAP.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 5.3
        $elemenId5_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Mencatat jurnal penutup',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_3,
                'deskripsi_kriteria' => 'Akun yang didebit dan dikredit diidentifikasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_3,
                'deskripsi_kriteria' => 'Jurnal penutup dicatat.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 5.4
        $elemenId5_4 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Memposting jurnal penyesuaian dan jurnal penutup ke buku besar',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_4,
                'deskripsi_kriteria' => 'Jurnal penyesuaian dan jurnal penutup diposting.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_4,
                'deskripsi_kriteria' => 'Saldo dalam akun buku besar setelah tutup buku disajikan sesuai ketentuan SOP.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 6: Mengoperasikan Paket Program Pengolah Angka/Spreadsheet ==========
        $unitId6 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'M.692000.022.02',
            'judul_unit' => 'Mengoperasikan Paket Program Pengolah Angka/Spreadsheet',
            'pertanyaan_unit' => 'Dapatkah Saya Mengoperasikan Paket Program Pengolah Angka/Spreadsheet?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 6.1
        $elemenId6_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId6,
            'nama_elemen' => 'Mempersiapkan komputer dan paket program pengolah angka',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId6_1,
                'deskripsi_kriteria' => 'Komputer yang dibutuhkan untuk mengoperasikan paket program pengolah angka disediakan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_1,
                'deskripsi_kriteria' => 'Paket program pengolah angka siap dioperasikan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_1,
                'deskripsi_kriteria' => 'Sumber data yang akan diolah dengan program pengolah angka disiapkan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 6.2
        $elemenId6_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId6,
            'nama_elemen' => 'Mengentry data',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId6_2,
                'deskripsi_kriteria' => 'Karakter sel diidentifikasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_2,
                'deskripsi_kriteria' => 'Karakter data diidentifikasi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_2,
                'deskripsi_kriteria' => 'Data dientry sesuai dengan karakter sel.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_2,
                'deskripsi_kriteria' => 'Hasil entry disesuaikan dengan sumber data.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 6.3
        $elemenId6_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId6,
            'nama_elemen' => 'Mengolah data dengan menggunakan fungsi-fungsi program pengolah angka',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Data diolah dengan rumus matematika.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Data diolah dengan rumus statistik.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Data diolah dengan menggunakan rumus semi absolut, absolut dan fungsi logika.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Data diolah dengan menggunakan fungsi financial.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Data diolah dengan menggunakan fungsi date-time.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_3,
                'deskripsi_kriteria' => 'Data diolah dengan menggunakan fungsi grafik.',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 6.4
        $elemenId6_4 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId6,
            'nama_elemen' => 'Membuat laporan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId6_4,
                'deskripsi_kriteria' => 'Laporan dibuat dalam bentuk tabel.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId6_4,
                'deskripsi_kriteria' => 'Laporan dibuat dalam bentuk grafik.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 7: Mengoperasikan Aplikasi Komputer Akuntansi ==========
        $unitId7 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'M.692000.023.02',
            'judul_unit' => 'Mengoperasikan Aplikasi Komputer Akuntansi',
            'pertanyaan_unit' => 'Dapatkah Saya Mengoperasikan Aplikasi Komputer Akuntansi?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 7.1
        $elemenId7_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId7,
            'nama_elemen' => 'Menyiapkan data awal perusahaan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Peralatan dan perlengkapan yang dibutuhkan disiapkan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_1,
                'deskripsi_kriteria' => 'Data perusahaan dibuat.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 7.2
        $elemenId7_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId7,
            'nama_elemen' => 'Menyusun data Setup awal dan saldo awal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId7_2,
                'deskripsi_kriteria' => 'Daftar akun disusun dan saldo awal akun dientry.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_2,
                'deskripsi_kriteria' => 'Kode pajak disiapkan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_2,
                'deskripsi_kriteria' => 'Kartu piutang dan pelanggan dibuat dan saldo awal piutang dientry.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_2,
                'deskripsi_kriteria' => 'Kartu utang dan pemasok dibuat dan saldo awal utang dientry.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_2,
                'deskripsi_kriteria' => 'Kartu persediaan dibuat dan saldo awal persediaan dientry.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 7.3
        $elemenId7_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId7,
            'nama_elemen' => 'Melakukan entry transaksi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId7_3,
                'deskripsi_kriteria' => 'Transaksi yang akan dientry dianalisis.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_3,
                'deskripsi_kriteria' => 'Transaksi dientry dengan menggunakan menu yang tepat.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_3,
                'deskripsi_kriteria' => 'Penyesuaian dientry dengan tepat.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_3,
                'deskripsi_kriteria' => 'Proses tutup buku dilakukan secara tepat.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 7.4
        $elemenId7_4 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId7,
            'nama_elemen' => 'Mencetak laporan keuangan dan lainnya',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId7_4,
                'deskripsi_kriteria' => 'Laporan laba rugi dibuat sesuai dengan ketentuan SOP.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_4,
                'deskripsi_kriteria' => 'Laporan neraca dibuat sesuai dengan ketentuan SOP.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_4,
                'deskripsi_kriteria' => 'Laporan ekuitas dibuat sesuai dengan ketentuan SOP.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_4,
                'deskripsi_kriteria' => 'Laporan arus kas dibuat sesuai dengan ketentuan SOP.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_4,
                'deskripsi_kriteria' => 'Laporan piutang dibuat sesuai dengan ketentuan SOP.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_4,
                'deskripsi_kriteria' => 'Laporan utang dibuat sesuai dengan ketentuan SOP.',
                'urutan' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_4,
                'deskripsi_kriteria' => 'Laporan persediaan dibuat sesuai dengan ketentuan SOP.',
                'urutan' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 7.5
        $elemenId7_5 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId7,
            'nama_elemen' => 'Membuat backup file',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId7_5,
                'deskripsi_kriteria' => 'Backup file data dibuat sesuai dengan ketentuan SOP.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId7_5,
                'deskripsi_kriteria' => 'Backup file data disimpan dalam media penyimpanan data.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✓ Skema KKNI Level II Akuntansi dan Keuangan Lembaga untuk AKL berhasil dibuat!');
        $this->command->info('✓ 7 Unit Kompetensi berhasil ditambahkan!');
        $this->command->info('✓ 27 Elemen berhasil ditambahkan!');
    }
}
