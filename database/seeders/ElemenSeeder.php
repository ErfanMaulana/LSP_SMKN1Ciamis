<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ElemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua unit berdasarkan kode_unit
        $units = DB::table('units')->get()->keyBy('kode_unit');

        if ($units->isEmpty()) {
            $this->command->error('Units tidak ditemukan! Jalankan UnitSeeder terlebih dahulu.');
            return;
        }

        $elemens = [];

        // Unit 1: J.620100.004.01 - Menggunakan Struktur Data (2 elemen)
        if ($units->has('J.620100.004.01')) {
            $elemens[] = [
                'unit_id' => $units['J.620100.004.01']->id,
                'nama_elemen' => 'Mengidentifikasi konsep data dan struktur data',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.004.01']->id,
                'nama_elemen' => 'Menerapkan struktur data dan akses terhadap struktur data tersebut',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Unit 2: J.620100.009.02 - Menggunakan Spesifikasi Program (3 elemen)
        if ($units->has('J.620100.009.02')) {
            $elemens[] = [
                'unit_id' => $units['J.620100.009.02']->id,
                'nama_elemen' => 'Menggunakan metode pengembangan program',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.009.02']->id,
                'nama_elemen' => 'Menggunakan diagram program dan deskripsi program',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.009.02']->id,
                'nama_elemen' => 'Menerapkan hasil pemodelan ke dalam pengembangan program',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Unit 3: J.620100.010.01 - Menerapkan Perintah Eksekusi (3 elemen)
        if ($units->has('J.620100.010.01')) {
            $elemens[] = [
                'unit_id' => $units['J.620100.010.01']->id,
                'nama_elemen' => 'Mengidentifikasi mekanisme running atau eksekusi source code',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.010.01']->id,
                'nama_elemen' => 'Mengeksekusi source code',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.010.01']->id,
                'nama_elemen' => 'Mengidentifikasi hasil eksekusi',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Unit 4: J.620100.016.01 - Menulis Kode (2 elemen)
        if ($units->has('J.620100.016.01')) {
            $elemens[] = [
                'unit_id' => $units['J.620100.016.01']->id,
                'nama_elemen' => 'Menerapkan coding-guidelines dan best practices dalam penulisan program (kode sumber)',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.016.01']->id,
                'nama_elemen' => 'Menggunakan ukuran performansi dalam menulisan kode sumber',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Unit 5: J.620100.017.02 - Pemrograman Terstruktur (6 elemen)
        if ($units->has('J.620100.017.02')) {
            $elemens[] = [
                'unit_id' => $units['J.620100.017.02']->id,
                'nama_elemen' => 'Menggunakan tipe data dan kontrol program',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.017.02']->id,
                'nama_elemen' => 'Membuat program sederhana',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.017.02']->id,
                'nama_elemen' => 'Membuat program menggunakan prosedur dan fungsi',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.017.02']->id,
                'nama_elemen' => 'Membuat program menggunakan array',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.017.02']->id,
                'nama_elemen' => 'Membuat program untuk akses file',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.017.02']->id,
                'nama_elemen' => 'Mengkompilasi Program',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Unit 6: J.620100.023.02 - Membuat Dokumen Kode Program (4 elemen)
        if ($units->has('J.620100.023.02')) {
            $elemens[] = [
                'unit_id' => $units['J.620100.023.02']->id,
                'nama_elemen' => 'Melakukan identifikasi kode program',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.023.02']->id,
                'nama_elemen' => 'Membuat dokumentasi modul program',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.023.02']->id,
                'nama_elemen' => 'Membuat dokumentasi fungsi, prosedur atau method program',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.023.02']->id,
                'nama_elemen' => 'Men-generate dokumentasi',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Unit 7: J.620100.025.02 - Melakukan Debugging (3 elemen)
        if ($units->has('J.620100.025.02')) {
            $elemens[] = [
                'unit_id' => $units['J.620100.025.02']->id,
                'nama_elemen' => 'Mempersiapkan kode program',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.025.02']->id,
                'nama_elemen' => 'Melakukan debugging',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620100.025.02']->id,
                'nama_elemen' => 'Memperbaiki program',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Unit 8: J.620900.033.02 - Pengujian unit Program (5 elemen)
        if ($units->has('J.620900.033.02')) {
            $elemens[] = [
                'unit_id' => $units['J.620900.033.02']->id,
                'nama_elemen' => 'Menentukan kebutuhan uji coba dalam pengembangan',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620900.033.02']->id,
                'nama_elemen' => 'Mempersiapkan dokumentasi uji coba',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620900.033.02']->id,
                'nama_elemen' => 'Mempersiapkan data uji',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620900.033.02']->id,
                'nama_elemen' => 'Melaksanakan prosedur uji coba',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $elemens[] = [
                'unit_id' => $units['J.620900.033.02']->id,
                'nama_elemen' => 'Mengevaluasi hasil uji coba',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('elemens')->insert($elemens);
        
        $this->command->info(count($elemens) . ' Elemen berhasil ditambahkan!');
    }
}
