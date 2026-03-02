<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkemaKLNSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Skema: Helper Cookery untuk Jurusan Kuliner (KLN)
     * 22 Unit Kompetensi
     */
    public function run(): void
    {
        // Ambil ID jurusan Kuliner (KLN)
        $jurusanKLN = DB::table('jurusan')
            ->where('kode_jurusan', 'KLN')
            ->value('ID_jurusan');

        if (!$jurusanKLN) {
            $this->command->warn('Jurusan KLN tidak ditemukan! Skema akan dibuat tanpa jurusan.');
        }

        // 1. Buat skema Helper Cookery untuk KLN
        $skemaId = DB::table('skemas')->insertGetId([
            'nomor_skema' => 'SKM/BNSP/00009/2/2023/877',
            'nama_skema' => 'Helper Cookery',
            'jenis_skema' => 'Okupasi',
            'jurusan_id' => $jurusanKLN,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ========== UNIT 1: Menggunakan Metode Dasar Memasak ==========
        $unitId1 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'I.55HDR00.041.3',
            'judul_unit' => 'Menggunakan Metode Dasar Memasak',
            'pertanyaan_unit' => 'Dapatkah Saya menggunakan metode dasar memasak?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Elemen 1.1
        $elemenId1_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Memilih dan menggunakan perlengkapan memasak',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Perlengkapan yang tepat ditentukan secara benar untuk metode memasak tertentu.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_1,
                'deskripsi_kriteria' => 'Perlengkapan masak dipilih dan digunakan sesuai keperluan masakan tertentu.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 1.2
        $elemenId1_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Menerapkan metode dasar memasak',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Istilah dasar dalam memasak diperbaharui dan dikembangkan sesuai dengan kebutuhan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Metode dasar memasak disiapkan dan dipilih sesuai standar resep perusahaan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_2,
                'deskripsi_kriteria' => 'Metode dasar memasak yang telah dipilih didemonstrasikan dan digunakan sesuai standar resep perusahaan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Elemen 1.3
        $elemenId1_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId1,
            'nama_elemen' => 'Mengevaluasi dan melaporkan hasil pelaksanaan kegiatan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId1_3,
                'deskripsi_kriteria' => 'Hasil pelaksanaan kegiatan dasar memasak dievaluasi.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId1_3,
                'deskripsi_kriteria' => 'Hasil evaluasi kegiatan dasar memasak dilaporkan kepada manajemen atau pimpinan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 2: Menyiapkan Sandwich ==========
        $unitId2 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'I.55HDR00.046.3',
            'judul_unit' => 'Menyiapkan Sandwich',
            'pertanyaan_unit' => 'Dapatkah Saya menyiapkan sandwich?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $elemenId2_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Menyiapkan dan menyajikan berbagai macam sandwich',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Bahan-bahan dasar dipilih dari berbagai jenis roti.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Bahan isian dipilih dan dikombinasikan sehingga sesuai dan serasi dengan jenis sandwich.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Sandwich disajikan dengan menggunakan teknik pemolesan, pelapisan, penentuan porsi, pembentukan, dan pemotongan sesuai dengan jenis sandwich.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Perlengkapan untuk pembakaran dan pemanasan dipilih secara tepat dan digunakan dengan benar.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_1,
                'deskripsi_kriteria' => 'Sandwich dihias dan disajikan dengan saus dan hidangan pengiring untuk menarik perhatian tamu.',
                'urutan' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $elemenId2_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Menerapkan perencanaan dan persiapan alur kerja',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_2,
                'deskripsi_kriteria' => 'Sandwich disiapkan dan disajikan sesuai dengan prosedur.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_2,
                'deskripsi_kriteria' => 'Ukuran, warna dan bentuk peralatan makan dipilih sesuai dengan persyaratan perusahaan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $elemenId2_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId2,
            'nama_elemen' => 'Menyimpan sandwich',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId2_3,
                'deskripsi_kriteria' => 'Sandwich disimpan secara benar untuk menjaga kesegaran dan mutu.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_3,
                'deskripsi_kriteria' => 'Sisa saus disimpan dibawah temperature dan kondisi yang tepat untuk menjaga kualitas dan meminimalkan pemborosan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId2_3,
                'deskripsi_kriteria' => 'Kebersihan dasar dan persyaratan dan keamanan makanan diikuti dan diterapkan pada semua pekerjaan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 3: Menyiapkan Berbagai Macam Kaldu Dan Saus ==========
        $unitId3 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'I.55HDR00.047.3',
            'judul_unit' => 'Menyiapkan Berbagai Macam Kaldu Dan Saus',
            'pertanyaan_unit' => 'Dapatkah Saya menyiapkan berbagai macam kaldu dan saus?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $elemenId3_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Menyiapkan dan membuat kaldu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Kombinasi rempah-rempah dan bumbu dipilih dan disiapkan sesuai standar resep.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_1,
                'deskripsi_kriteria' => 'Kaldu dibuat dengan menggunakan rempah-rempah dan bumbu yang telah dipilih.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $elemenId3_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Menyiapkan dan membuat saus',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Bahan saus panas dan dingin dipilih dan disiapkan sesuai standar resep.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Prosedur dan teknik pembuatan saus yang tepat ditentukan untuk pencapaian konsistensi mutu dan rasa.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Saus dasar dan turunan saus dibuat sesuai dengan standar resep dan kesukaan tamu.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_2,
                'deskripsi_kriteria' => 'Beragam pengental saus digunakan secara tepat.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $elemenId3_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId3,
            'nama_elemen' => 'Menyimpan kaldu dan saus pelaksanaan kegiatan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId3_3,
                'deskripsi_kriteria' => 'Kaldu disimpan dengan benar sesuai prosedur perusahaan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId3_3,
                'deskripsi_kriteria' => 'Saus disimpan dengan benar untuk menjaga standar konsistensi.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 4: Menyiapkan Berbagai Macam Sup ==========
        $unitId4 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'I.55HDR00.048.3',
            'judul_unit' => 'Menyiapkan Berbagai Macam Sup',
            'pertanyaan_unit' => 'Dapatkah Saya menyiapkan berbagai macam sup?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $elemenId4_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Mengidentifikasi jenis dan bahan sup',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Berbagai jenis sup diidentifikasi dan digolongkan menurut penampilannya.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Bahan sup dan kaldu diidentifikasi dan dipilih sesuai jenis sup.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Pengental sup diidentifikasi dan dipilih sesuai jenis sup.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_1,
                'deskripsi_kriteria' => 'Teknik dan metode pembuatan sup diidentifikasi dan dipilih sesuai jenis sup.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $elemenId4_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Membuat dan menyajikan sup dalam hidangan menu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Berbagai jenis sup dibuat sesuai dengan standar resep dan keinginan tamu.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Isian sup dan kaldu dibuat dari aneka daging, ikan, atau sayuran.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Penghias sup dibuat dari bahan yang bisa dimakan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_2,
                'deskripsi_kriteria' => 'Sup disajikan dalam keadaan panas kecuali gazpacho sup dingin di Eropa.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $elemenId4_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId4,
            'nama_elemen' => 'Menyimpan sup dan persiapannya',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId4_3,
                'deskripsi_kriteria' => 'Sup disimpan secara benar tanpa merusak mutu.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_3,
                'deskripsi_kriteria' => 'Sup disimpan dalam chiller atau freezer setelah melalui proses pendinginan sesuai standar Perusahaan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId4_3,
                'deskripsi_kriteria' => 'Sup dipanaskan kembali tanpa mengurangi mutu untuk disajikan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ========== UNIT 5: Melaksanakan Prosedur Keamanan Makanan ==========
        $unitId5 = DB::table('units')->insertGetId([
            'skema_id' => $skemaId,
            'kode_unit' => 'I.55HDR00.068.3',
            'judul_unit' => 'Melaksanakan Prosedur Keamanan Makanan',
            'pertanyaan_unit' => 'Dapatkah Saya melaksanakan prosedur keamanan makanan?',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $elemenId5_1 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Mengidentifikasi bahaya dan risiko keamanan makanan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Bahaya dan risiko keamanan makanan yang ditimbulkan oleh zat biologi diidentifikasi dan didokumentasikan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Bahaya dan risiko keamanan makanan yang ditimbulkan oleh bahan fisika diidentifikasi dan didokumentasikan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Bahaya dan risiko keamanan makanan yang ditimbulkan oleh zat kimia diidentifikasi dan didokumentasikan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_1,
                'deskripsi_kriteria' => 'Keamanan makanan dilakukan sesuai standar perusahaan.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $elemenId5_2 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Mengidentifikasi titik kontrol penting dalam sistem produksi makanan dengan menggunakan metode HACCP',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Titik rawan makanan diidentifikasi dan diawasi dengan seksama sesuai metode HACCP.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_2,
                'deskripsi_kriteria' => 'Proses alur kerja produksi makanan diidentifikasi dan disiapkan sesuai metode HACCP.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $elemenId5_3 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Melaksanakan rencana HACCP perusahaan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_3,
                'deskripsi_kriteria' => 'Produksi makanan yang sesuai dengan spesifikasi keamanan makanan berdasarkan metode HACCP ditentukan dan dilaksanakan.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_3,
                'deskripsi_kriteria' => 'Proses alur produksi makanan diikuti sesuai rencana HACCP.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_3,
                'deskripsi_kriteria' => 'Ketepatan pencatatan data dimonitor dan dikoreksi sesuai standar perusahaan.',
                'urutan' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_3,
                'deskripsi_kriteria' => 'Validasi audit internal dan eksternal metode HACCP perusahaan dilaksanakan sesuai dengan prosedur.',
                'urutan' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $elemenId5_4 = DB::table('elemens')->insertGetId([
            'unit_id' => $unitId5,
            'nama_elemen' => 'Mengevaluasi dan melaporkan hasil kegiatan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('kriteria')->insert([
            [
                'elemen_id' => $elemenId5_4,
                'deskripsi_kriteria' => 'Hasil pelaksanaan kegiatan keamanan makanan dievaluasi dan didokumentasikan untuk perbaikan pada kegiatan selanjutnya.',
                'urutan' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'elemen_id' => $elemenId5_4,
                'deskripsi_kriteria' => 'Hasil evaluasi kegiatan Keamanan makanan dilaporkan kepada manajemen atau pimpinan.',
                'urutan' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->command->info('✓ Skema Helper Cookery untuk Kuliner berhasil dibuat!');
        $this->command->info('✓ 5 Unit Kompetensi berhasil ditambahkan!');
        $this->command->info('  (Catatan: Masih ada 17 unit lagi yang bisa ditambahkan)');
    }
}
