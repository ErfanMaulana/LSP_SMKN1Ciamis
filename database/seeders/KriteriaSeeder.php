<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua elemen dengan unit dan kode_unit
        $elemens = DB::table('elemens')
            ->join('units', 'elemens.unit_id', '=', 'units.id')
            ->select('elemens.*', 'units.kode_unit')
            ->get();

        if ($elemens->isEmpty()) {
            $this->command->error('Elemens tidak ditemukan! Jalankan ElemenSeeder terlebih dahulu.');
            return;
        }

        $kriteria = [];

        // Helper function untuk menambahkan kriteria
        $addKriteria = function ($kodeUnit, $namaElemen, $deskripsiArray) use ($elemens, &$kriteria) {
            $elemen = $elemens->first(function ($item) use ($kodeUnit, $namaElemen) {
                return $item->kode_unit === $kodeUnit && 
                       $item->nama_elemen === $namaElemen;
            });

            if ($elemen) {
                foreach ($deskripsiArray as $index => $deskripsi) {
                    $kriteria[] = [
                        'elemen_id' => $elemen->id,
                        'deskripsi_kriteria' => $deskripsi,
                        'urutan' => $index + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        };

        // ========== UNIT 1: J.620100.004.01 - Menggunakan Struktur Data ==========
        
        // Elemen 1: Mengidentifikasi konsep data dan struktur data
        $addKriteria('J.620100.004.01', 'Mengidentifikasi konsep data dan struktur data', [
            'Konsep data dan struktur data diidentifikasi sesuai dengan konteks permasalahan.',
            'Alternatif struktur data, kelebihan dan kekurangannya dibandingkan untuk konteks permasalahan yang diselesaikan',
        ]);

        // Elemen 2: Menerapkan struktur data dan akses terhadap struktur data tersebut
        $addKriteria('J.620100.004.01', 'Menerapkan struktur data dan akses terhadap struktur data tersebut', [
            'Struktur data diimplementasikan sesuai dengan bahasa pemrograman yang akan dipergunakan.',
            'Akses terhadap data dalam algoritma yang efisiensi diyatakan sesuai bahasa pemrograman yang akan dipakai',
        ]);

        // ========== UNIT 2: J.620100.009.02 - Menggunakan Spesifikasi Program ==========
        
        // Elemen 1: Menggunakan metode pengembangan program
        $addKriteria('J.620100.009.02', 'Menggunakan metode pengembangan program', [
            'Metode pengembangan aplikasi (software development) didefinisikan.',
            'Metode pengembangan aplikasi (software development) dimilih sesuai kebutuhan.',
        ]);

        // Elemen 2: Menggunakan diagram program dan deskripsi program
        $addKriteria('J.620100.009.02', 'Menggunakan diagram program dan deskripsi program', [
            'Diagram program didefinisikan dengan metodologi pengembangan sistem.',
            'Metode pemodelan, diagram objek dan diagram komponen digunakan pada implementasi program sesuai dengan spesifikasi.',
        ]);

        // Elemen 3: Menerapkan hasil pemodelan ke dalam pengembangan program
        $addKriteria('J.620100.009.02', 'Menerapkan hasil pemodelan ke dalam pengembangan program', [
            'Hasil pemodelan yang mendukung kemampuan metodologi dimilih sesuai spesifikasi.',
            'Hasil pemrograman (Integrated Development Environment-IDE) yang mendukung kemampuan metodologi bahasa pemrograman dipilih sesuai spesifikasi.',
        ]);

        // ========== UNIT 3: J.620100.010.01 - Menerapkan Perintah Eksekusi ==========
        
        // Elemen 1: Mengidentifikasi mekanisme running atau eksekusi source code
        $addKriteria('J.620100.010.01', 'Mengidentifikasi mekanisme running atau eksekusi source code', [
            'Cara dan tools diidentifikasi untuk mengeksekusi source code.',
            'Parameter diidentifikasi untuk mengeksekusi source code.',
            'Peletakan source code diidentifikasi sehingga bisa dieksekusi dengan benar.',
        ]);

        // Elemen 2: Mengeksekusi source code
        $addKriteria('J.620100.010.01', 'Mengeksekusi source code', [
            'Source code dieksekusi sesuai dengan mekanisme eksekusi source code dari tools. pemrograman yang digunakan.',
            'Perbedaan diidentifikasi antara running, debugging, atau membuat executable file.',
        ]);

        // Elemen 3: Mengidentifikasi hasil eksekusi
        $addKriteria('J.620100.010.01', 'Mengidentifikasi hasil eksekusi', [
            'Source code berhasil diidentifikasi sesuai skenario yang direncanakan.',
            'Jika eksekusi source code gagal/tidak berhasil, diidentifikasi sumber permasalahan.',
        ]);

        // ========== UNIT 4: J.620100.016.01 - Menulis Kode ==========
        
        // Elemen 1: Menerapkan coding-guidelines dan best practices
        $addKriteria('J.620100.016.01', 'Menerapkan coding-guidelines dan best practices dalam penulisan program (kode sumber)', [
            'Kode sumber dinuliskan mengikuti coding-guidelines dan best practices.',
            'Struktur program dibuat yang sesuai dengan konsep paradigmanya.',
            'Menangani Galat/error.',
        ]);

        // Elemen 2: Menggunakan ukuran performansi dalam menulisan kode sumber
        $addKriteria('J.620100.016.01', 'Menggunakan ukuran performansi dalam menulisan kode sumber', [
            'Efisiensi penggunaan resources dihitung oleh kode.',
            'Kemudahan interaksi selalu dimplementasikan sesuai standar yang berlaku.',
        ]);

        // ========== UNIT 5: J.620100.017.02 - Pemrograman Terstruktur ==========
        
        // Elemen 1: Menggunakan tipe data dan kontrol program
        $addKriteria('J.620100.017.02', 'Menggunakan tipe data dan kontrol program', [
            'Tipe data yang ditentukan sesuai standar.',
            'Syntax program yang digunakan sesuai standar.',
            'Struktur kontrol program yang  digunakan esuai standar.',
        ]);

        // Elemen 2: Membuat program sederhana
        $addKriteria('J.620100.017.02', 'Membuat program sederhana', [
            'Program baca tulis untuk memasukkan data dari keyboard dan menampilkan ke layar monitor termasuk variasinya dibuat sesuai standar masukan/keluaran.',
            'Struktur kontrol percabangan dan pengulangan digunakan dalam membuat program.',
        ]);

        // Elemen 3: Membuat program menggunakan prosedur dan fungsi
        $addKriteria('J.620100.017.02', 'Membuat program menggunakan prosedur dan fungsi', [
            'Program dengan menggunakan prosedur dibuat sesuai aturan penulisan program.',
            'Program dengan menggunakan fungsi dibuat sesuai aturan penulisan program.',
            'Program dengan menggunakan prosedur dan fungsi secara bersamaan dibuat sesuai aturan penulisan program.',
            'Keterangan diberikan untuk setiap prosedur dan fungsi.',
        ]);

        // Elemen 4: Membuat program menggunakan array
        $addKriteria('J.620100.017.02', 'Membuat program menggunakan array', [
            'Dimensi array dinentukan.',
            'Tipe data array dinentukan.',
            'Panjang array dinentukan.',
            'Pengurutan array digunakan.',
        ]);

        // Elemen 5: Membuat program untuk akses file
        $addKriteria('J.620100.017.02', 'Membuat program untuk akses file', [
            'Program dibuat untuk menulis data dalam media penyimpan.',
            'Program dibuat untuk membaca data dari media penyimpan.',
        ]);

        // Elemen 6: Mengkompilasi Program
        $addKriteria('J.620100.017.02', 'Mengkompilasi Program', [
            'Kesalahan program dikoreksi.',
            'Kesalahan syntax dalam program dibebaskan.',
        ]);

        // ========== UNIT 6: J.620100.023.02 - Membuat Dokumen Kode Program ==========
        
        // Elemen 1: Melakukan identifikasi kode program
        $addKriteria('J.620100.023.02', 'Melakukan identifikasi kode program', [
            'Modul program diidentifikasi',
            'Parameter yang dipergunakan diidentifikasi',
            'Algoritma dijelaskan cara kerjanya',
            'Komentar setiap baris kode termasuk data, eksepsi, fungsi, prosedur dan class (bila ada) diberikan',
        ]);

        // Elemen 2: Membuat dokumentasi modul program
        $addKriteria('J.620100.023.02', 'Membuat dokumentasi modul program', [
            'Dokumentasi modul dibuat sesuai dengan identitas untuk memudahkan pelacakan',
            'Identifikasi dokumentasi diterapkan',
            'Kegunaan modul dijelaskan',
            'Dokumen direvisi sesuai perubahan kode program',
        ]);

        // Elemen 3: Membuat dokumentasi fungsi, prosedur atau method program
        $addKriteria('J.620100.023.02', 'Membuat dokumentasi fungsi, prosedur atau method program', [
            'Dokumentasi fungsi, prosedur atau metod dibuat',
            'Kemungkinan eksepsi dijelaskan',
            'Dokumen direvisi sesuai perubahan kode program',
        ]);

        // Elemen 4: Men-generate dokumentasi
        $addKriteria('J.620100.023.02', 'Men-generate dokumentasi', [
            'Tools untuk generate dokumentasi diidentifikasi',
            'Generate dokumentasi dilakukan',
        ]);

        // ========== UNIT 7: J.620100.025.02 - Melakukan Debugging ==========
        
        // Elemen 1: Mempersiapkan kode program
        $addKriteria('J.620100.025.02', 'Mempersiapkan kode program', [
            'Kode program sesuai spesifikasi disiapkan.',
            'Debugging tools untuk melihat proses suatu modul dipersiapkan.',
        ]);

        // Elemen 2: Melakukan debugging
        $addKriteria('J.620100.025.02', 'Melakukan debugging', [
            'Kode program dikompilasi sesuai bahasa pemrograman yang digunakan.',
            'Kriteria lulus build dianalisis.',
            'Kriteria eksekusi aplikasi dianalisis.',
            'Kode kesalahan dicatat',
        ]);

        // Elemen 3: Memperbaiki program
        $addKriteria('J.620100.025.02', 'Memperbaiki program', [
            'Perbaikan terhadap kesalahan kompilasi maupun build dirumuskan.',
            'Perbaikan dilakukan.',
        ]);

        // ========== UNIT 8: J.620900.033.02 - Pengujian unit Program ==========
        
        // Elemen 1: Menentukan kebutuhan uji coba dalam pengembangan
        $addKriteria('J.620900.033.02', 'Menentukan kebutuhan uji coba dalam pengembangan', [
            'Prosedur uji coba aplikasi diidentifikasikan sesuai dengan software development life cycle.',
            'Tools uji coba ditentukan.',
            'Standar dan kondisi uji coba diidentifikasi',
        ]);

        // Elemen 2: Mempersiapkan dokumentasi uji coba
        $addKriteria('J.620900.033.02', 'Mempersiapkan dokumentasi uji coba', [
            'Mempersiapkan dokumentasi uji coba.',
            'Uji coba dengan variasi kondisi dapat dilaksanakan.',
            'Skenario uji coba dibuat.',
        ]);

        // Elemen 3: Mempersiapkan data uji
        $addKriteria('J.620900.033.02', 'Mempersiapkan data uji', [
            'Data uji unit tes diidentifikasi.',
            'Data uji unit tes dibangkitkan.',
        ]);

        // Elemen 4: Melaksanakan prosedur uji coba
        $addKriteria('J.620900.033.02', 'Melaksanakan prosedur uji coba', [
            'Skenario uji coba didesain',
            'Prosedur uji coba dalam algoritma didesain',
            'Uji coba dilaksanakan',
        ]);

        // Elemen 5: Mengevaluasi hasil uji coba
        $addKriteria('J.620900.033.02', 'Mengevaluasi hasil uji coba', [
            'Hasil uji coba dicatat',
            'Hasil uji coba dianalisis',
            'Prosedur uji coba dilaporkan',
            'Kesalahan/error diselesaikan',
        ]);

        DB::table('kriteria')->insert($kriteria);
        
        $this->command->info(count($kriteria) . ' Kriteria Unjuk Kerja berhasil ditambahkan!');
    }
}
