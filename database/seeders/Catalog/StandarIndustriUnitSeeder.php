<?php

namespace Database\Seeders\Catalog;

use App\Models\StandarIndustriUnit;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StandarIndustriUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $standardsMap = $this->getStandardsMap();

        $totalInserted = 0;
        $unitsProcessed = 0;

        foreach ($standardsMap as $skemaPattern => $units) {
            foreach ($units as $unitCodePattern => $standards) {
                // Normalize unit code for search (e.g. remove spaces or handle slight code differences)
                $cleanCode = trim($unitCodePattern);
                
                // Find units matching this kode_unit (exact or without dot variations)
                $matchedUnits = Unit::where('kode_unit', $cleanCode)
                    ->orWhere('kode_unit', str_replace('..', '.', $cleanCode))
                    ->orWhere('kode_unit', 'like', '%' . str_replace([' ', '.'], '', $cleanCode) . '%')
                    ->get();

                if ($matchedUnits->isEmpty()) {
                    // Try partial match by code prefix/suffix
                    $parts = explode('.', $cleanCode);
                    if (count($parts) >= 3) {
                        $coreCode = $parts[0] . '.' . $parts[1];
                        $matchedUnits = Unit::where('kode_unit', 'like', $coreCode . '%')->get();
                    }
                }

                foreach ($matchedUnits as $unit) {
                    $unitsProcessed++;
                    
                    // Clear existing standards to avoid duplication on re-seeding
                    StandarIndustriUnit::where('unit_id', $unit->id)->delete();

                    foreach ($standards as $idx => $standarItem) {
                        $namaStandar = is_array($standarItem) ? ($standarItem['nama'] ?? '') : (string) $standarItem;
                        $deskripsiStandar = is_array($standarItem) ? ($standarItem['deskripsi'] ?? null) : null;

                        if (trim($namaStandar) !== '') {
                            StandarIndustriUnit::create([
                                'unit_id' => $unit->id,
                                'nama_standar' => trim($namaStandar),
                                'deskripsi_standar' => $deskripsiStandar ? trim($deskripsiStandar) : null,
                                'urutan' => $idx + 1,
                            ]);
                            $totalInserted++;
                        }
                    }
                }
            }
        }

        $this->command->info("Standar Industri / Tempat Kerja berhasil di-seed: {$totalInserted} data pada {$unitsProcessed} unit kompetensi.");
    }

    /**
     * Complete mapping of unit codes to Standar Industri / SOP Tempat Kerja
     * based on ceklis_observasi docx templates.
     */
    private function getStandardsMap(): array
    {
        return [
            // ==========================================
            // 1. RPL - Pemrogram Junior (rpl.docx)
            // ==========================================
            'RPL' => [
                'J.620100.004.01' => [
                    'Instrumen Penilaian Persiapan Tempat Kerja',
                    'SOP Menerapkan Prinsip-Prinsip Keselamatan dan Kesehatan Kerja di Lingkungan Kerja',
                    'SOP Menggunakan Struktur Data',
                ],
                'J.620100.009.02' => [
                    'Instrumen Penilaian Produk Pembuatan Aplikasi',
                    'SOP Menggunakan Spesifikasi Program',
                ],
                'J.620100.010.01' => [
                    'Instrumen Penilaian Produk Pembuatan Aplikasi',
                    'SOP Menerapkan Perintah Eksekusi Bahasa Pemrograman Berbasis Teks, Grafik, dan Multimedia',
                ],
                'J.620100.016.01' => [
                    'Instrumen Penilaian Produk Pembuatan Aplikasi',
                    'SOP Menulis Kode Dengan Prinsip Sesuai Guidelines dan Best Practices',
                ],
                'J.620100.017.02' => [
                    'Instrumen Penilaian Produk Pembuatan Aplikasi',
                    'SOP Mengimplementasikan Pemrograman Terstruktur',
                ],
                'J.620100.023.02' => [
                    'Instrumen Penilaian Produk Pembuatan Aplikasi',
                    'SOP Membuat Dokumen Kode Program',
                ],
                'J.620100.025.02' => [
                    'Instrumen Pengujian dengan Debugging',
                    'SOP Melakukan Debugging',
                ],
                'J.620900.033.02' => [
                    'Instrumen Pengujian dengan Debugging',
                    'SOP Melaksanakan Pengujian Unit Program',
                ],
            ],

            // ==========================================
            // 2. DKV - Content Creator Junior (dkv.docx)
            // ==========================================
            'DKV' => [
                'J.59MTM00.002.1' => [
                    'Instrumen Penilaian Persiapan Tempat Kerja',
                    'SOP Riset Konten dan Teknologi Multimedia',
                ],
                'J.59MTM00.004.1' => [
                    'Instrumen Penilaian Proses Tempat Kerja',
                    'SOP menyusun creative brief',
                    'SOP Produksi Multimedia',
                ],
                'J.59MTM00.011.1' => [
                    'Instrumen Penilaian Proses Tempat Kerja',
                    'SOP Produksi Multimedia',
                ],
                'J.59MTM00.015.1' => [
                    'Instrumen Penilaian Proses Tempat Kerja',
                    'SOP Produksi Multimedia',
                ],
                'J.59MTM00.018.1' => [
                    'Instrumen Penilaian Proses dan Hasil Tempat Kerja',
                    'SOP Produksi Multimedia',
                ],
            ],

            // ==========================================
            // 3. Kuliner - Helper Cookery (kuliner.docx)
            // ==========================================
            'KLN' => [
                'I.55HDR00.041.3' => [
                    'SOP Pembuatan Hidangan Table D’Hote',
                    'Standar Resep dan Metode Memasak Perusahaan',
                ],
                'I.55HDR00.046.3' => [
                    'SOP Pembuatan Hidangan Table D’Hote',
                    'Standar Penyajian Sandwich dan Pengolahan Makanan Dingin',
                ],
                'I.55HDR00.047.3' => [
                    'SOP Pembuatan Hidangan Table D’Hote',
                    'Standar Pengolahan dan Penyimpanan Kaldu/Saus',
                ],
                'I.55HDR00.048.3' => [
                    'Instrumen Penilaian Persiapan Tempat Kerja',
                    'SOP Pembuatan Hidangan Table D’Hote',
                    'Standar Pengolahan dan Penyimpanan Sup',
                ],
                'I.55HDR00.068.3' => [
                    'SOP Pembuatan Hidangan Table D’Hote',
                    'Standar Keamanan Pangan dan HACCP',
                ],
            ],

            // ==========================================
            // 4. MPLB - Okupasi Office Administrative (mplb.docx)
            // ==========================================
            'MPLB' => [
                'N.821100.001.02' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Menangani Penerimaan dan Pengiriman Dokumen/Surat',
                ],
                'N.821100.004.02' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Memproduksi Dokumen',
                ],
                'N.821100.012.01' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Mengelola Jadwal Kegiatan Pimpinan',
                ],
                'N.821100.013.01' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Mengatur Rapat/Pertemuan',
                ],
                'N.821.100.029.02' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Melakukan Komunikasi Melalui Telepon',
                ],
                'N.821.100.030.02' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Melakukan Komunikasi Lisan dengan Kolega/Pelanggan',
                ],
                'N.821.100.032.02' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Melakukan Komunikasi Lisan dalam Bahasa Inggris pada Tingkat Operasional Dasar',
                ],
                'N.821.100.033.01' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Membaca dalam Bahasa Inggris pada Tingkat Operasional Dasar',
                ],
                'N.821.100.034.02' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Menulis Dalam Bahasa Inggris Pada Tingkat Operasional Dasar',
                ],
                'N.821.100.045.02' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Memberikan Layanan kepada Pelanggan',
                ],
                'N.821.100.053.02' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Memproduksi Dokumen di Komputer',
                ],
                'N.821.100.054.01' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Menggunakan Peralatan Komunikasi',
                ],
                'N.821.100.057.02' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Mengoperasikan Aplikasi Perangkat Lunak',
                ],
                'N.821.100.058.02' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Mengakses Data di Komputer',
                ],
                'N.821.100.059.01' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Menggunakan Peralatan dan Sumber Daya Kerja',
                ],
                'N.821.100.060.01' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Membuat Surat/Dokumen Elektronik',
                ],
                'N.821.100.061.01' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Mengakses Informasi melalui Homepage',
                ],
                'N.821.100.067.01' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Melakukan Transaksi Perbankan Sederhana',
                ],
                'N.821.100.073.02' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Mengelola Arsip',
                ],
                'N.821.100.075.02' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Menerapkan Prosedur K3 Perkantoran',
                ],
                'N.821100.076.02' => [
                    'SKKNI No. 183 Tahun 2016',
                    'SOP Meminimalisir Pencurian',
                ],
            ],

            // ==========================================
            // 5. Pemasaran / Kasir - Trainee Kasir (pm.docx)
            // ==========================================
            'PM' => [
                'G.46RIT00.001.1' => [
                    'SOP Trainee Kasir',
                    'SOP Kebersihan dan Kerapihan Area Kasir',
                ],
                'G.46RIT00.002.1' => [
                    'SOP Trainee Kasir',
                    'Instruksi Kerja Tugas dan Tanggung Jawab Kasir',
                ],
                'G.46RIT00.003.1' => [
                    'SOP Trainee Kasir',
                    'SOP Pengoperasian Mesin POS / Kasir',
                ],
                'G.46RIT00.004.1' => [
                    'SOP Trainee Kasir',
                    'Standar Nilai dan Budaya Kerja Ritel',
                ],
                'G.46RIT00.005.1' => [
                    'SOP Trainee Kasir',
                    'SOP Pengemasan dan Packing Barang Dagangan',
                ],
                'G.46RIT00.006.1' => [
                    'SOP Trainee Kasir',
                    'SOP Penerimaan dan Pengecekan Barang Dagangan',
                ],
                'G.46RIT00.008.1' => [
                    'SOP Trainee Kasir',
                    'Standar Komunikasi Kerja Toko Ritel',
                ],
                'G.46RIT00.009.1' => [
                    'SOP Trainee Kasir',
                    'Standar Pelayanan Pelanggan Gerai Ritel',
                ],
                'G.46RIT00.014.1' => [
                    'SOP Trainee Kasir',
                    'SOP Transaksi Penjualan dan Pelayanan Ritel',
                ],
                'G.46RIT00.018.1' => [
                    'SOP Trainee Kasir',
                    'SOP Keselamatan dan Keamanan Kerja Toko Ritel',
                ],
            ],

            // ==========================================
            // 6. AKL - Akuntansi dan Keuangan Lembaga (akl.docx)
            // ==========================================
            'AKL' => [
                'M.692000.001.02' => [
                    'SOP Penyusunan Laporan Keuangan berbasis SAK ETAP',
                    'Pedoman dan Prosedur Praktik Profesional Akuntansi',
                ],
                'M.692000.002.02' => [
                    'SOP K3 Tempat Kerja Akuntansi',
                    'SOP Penyusunan Laporan Keuangan berbasis SAK ETAP',
                ],
                'M.692000.007.02' => [
                    'SOP Penyusunan Laporan Keuangan berbasis SAK ETAP',
                    'SOP Memproses Entry Jurnal',
                ],
                'M.692000.008.02' => [
                    'SOP Penyusunan Laporan Keuangan berbasis SAK ETAP',
                    'SOP Memproses Buku Besar',
                ],
                'M.692000.013.02' => [
                    'SOP Penyusunan Laporan Keuangan berbasis SAK ETAP',
                    'Standar Akuntansi Keuangan (SAK ETAP)',
                ],
                'M.692000.022.02' => [
                    'SOP Mengelola informasi untuk Operasional Pengoperasian Paket Spreadsheet dan Komputer Akuntansi',
                ],
                'M.692000.023.02' => [
                    'SOP Mengelola informasi untuk Operasional Pengoperasian Paket Spreadsheet dan Komputer Akuntansi',
                ],
            ],

            // ==========================================
            // 7. Perhotelan - Guest Service Agent (perhotelan.docx)
            // ==========================================
            'HTL' => [
                'I.55HDR00.002.2' => [
                    'SOP Guest Service Agent',
                    'SOP Layanan Penerimaan Tamu (Reception)',
                ],
                'I.55HDR00.003.2' => [
                    'SOP Guest Service Agent',
                    'SOP Pemeliharaan Catatan Keuangan Front Office',
                ],
                'I.55HDR00.004.2' => [
                    'SOP Guest Service Agent',
                    'SOP Pemrosesan Transaksi Keuangan Front Office',
                ],
                'I.55HDR00.006.2' => [
                    'SOP Guest Service Agent',
                    'SOP Komunikasi Telepon Standar Hotel',
                ],
                'I.55HDR00.007.2' => [
                    'SOP Guest Service Agent',
                    'SOP Pelaksanaan Audit Malam (Night Audit)',
                ],
                'I.55HDR00.149.2' => [
                    'SOP Guest Service Agent',
                    'Standar Pelayanan Prima dan Hubungan Tamu',
                ],
                'I.55HDR00.150.2' => [
                    'SOP Guest Service Agent',
                    'Standar Etika Kerja Lingkungan Sosial Beragam',
                ],
                'I.55HDR00.151.2' => [
                    'SOP Guest Service Agent',
                    'SOP K3 dan Keamanan Hotel',
                ],
                'I.55HDR00.152.2' => [
                    'SOP Guest Service Agent',
                    'Standar Pengetahuan Industri Perhotelan',
                ],
                'I.55HDR00.154.2' => [
                    'SOP Guest Service Agent',
                    'SOP Promosi Produk dan Layanan Hotel',
                ],
                'I.55HDR00.155.2' => [
                    'SOP Guest Service Agent',
                    'SOP Penanganan Keluhan dan Situasi Konflik',
                ],
                'I.55HDR00.163.2' => [
                    'SOP Guest Service Agent',
                    'SOP Pertolongan Pertama Pada Kecelakaan (P3K)',
                ],
                'I.55HDR00.164.2' => [
                    'SOP Guest Service Agent',
                    'SOP Prosedur Administrasi Front Office',
                ],
                'I.55HDR00.166.2' => [
                    'SOP Guest Service Agent',
                    'SOP Penyusunan Dokumen Bisnis Perhotelan',
                ],
                'I.55HDR00.206.2' => [
                    'SOP Guest Service Agent',
                    'Standar Komunikasi dan Hospitality Pelayanan Tamu',
                ],
                'I.55HDR00.207.2' => [
                    'SOP Guest Service Agent',
                    'SOP Percakapan Singkat Telepon Hotel',
                ],
                'I.55HDR00.213.2' => [
                    'SOP Guest Service Agent',
                    'SOP Penulisan Pesan Singkat (Guest Message)',
                ],
                'I.55HRD00.217.2' => [
                    'SOP Guest Service Agent',
                    'Standar Komunikasi Bahasa Inggris Front Office',
                ],
                'I.55HDR00.218.2' => [
                    'SOP Guest Service Agent',
                    'Pedoman Perlindungan Anak di Sektor Pariwisata',
                ],
                'I.55HDR00.220.2' => [
                    'SOP Guest Service Agent',
                    'SOP Keamanan Lingkungan Ramah Anak di Hotel',
                ],
                'I.55HDR00.226.2' => [
                    'SOP Guest Service Agent',
                    'Standar Kerja Kooperatif Administrasi Hotel',
                ],
                'I.55HDR00.229.2' => [
                    'SOP Guest Service Agent',
                    'Standar Pemeliharaan Lingkungan Kerja Aman',
                ],
                'I.55HDR00.230.2' => [
                    'SOP Guest Service Agent',
                    'Standar Pemahaman Dokumen Bahasa Inggris Lanjutan',
                ],
                'I.55HDR00.250.2' => [
                    'SOP Guest Service Agent',
                    'SOP Pengoperasian Sistem Data Komputer Hotel',
                ],
                'I.55HDR00.255.2' => [
                    'SOP Guest Service Agent',
                    'SOP Pengoperasian Sistem Reservasi Komputer (Property Management System)',
                ],
                'I.55HDR00.256.2' => [
                    'SOP Guest Service Agent',
                    'SOP Pengawasan dan Pemantauan Area Publik Hotel',
                ],
            ],
        ];
    }
}
