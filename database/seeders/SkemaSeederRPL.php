<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skema;
use App\Models\Unit;
use App\Models\Elemen;
use App\Models\Kriteria;

class SkemaSeederRPL extends Seeder
{
    public function run(): void
    {
        // ── Skema ──────────────────────────────────────────────
        $skema = Skema::updateOrCreate(
            ['nomor_skema' => 'SKM/BNSP/00010/2/2023/1324'],
            [
                'nama_skema'  => 'Okupasi Pemrogram Junior (Junior Coder)',
                'jenis_skema' => 'Okupasi',
            ]
        );

        // ── Data Unit Kompetensi ───────────────────────────────
        $units = [
            // ── Unit 1 ────────────────────────────────────────
            [
                'kode_unit'      => 'J.620100.004.01',
                'judul_unit'     => 'Menggunakan Struktur Data',
                'pertanyaan_unit' => 'Dapatkah Saya menggunakan Struktur Data?',
                'elemens' => [
                    [
                        'nama_elemen' => 'Mengidentifikasi konsep data dan struktur data',
                        'kriteria' => [
                            'Konsep data dan struktur data diidentifikasi sesuai dengan konteks permasalahan.',
                            'Alternatif struktur data, kelebihan dan kekurangannya dibandingkan untuk konteks permasalahan yang diselesaikan.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Menerapkan struktur data dan akses terhadap struktur data tersebut',
                        'kriteria' => [
                            'Struktur data diimplementasikan sesuai dengan bahasa pemrograman yang akan dipergunakan.',
                            'Akses terhadap data dalam algoritma yang efisiensi dinyatakan sesuai bahasa pemrograman yang akan dipakai.',
                        ],
                    ],
                ],
            ],

            // ── Unit 2 ────────────────────────────────────────
            [
                'kode_unit'      => 'J.620100.009.02',
                'judul_unit'     => 'Menggunakan Spesifikasi Program',
                'pertanyaan_unit' => 'Dapatkah Saya menggunakan Spesifikasi Program?',
                'elemens' => [
                    [
                        'nama_elemen' => 'Menggunakan metode pengembangan program',
                        'kriteria' => [
                            'Metode pengembangan aplikasi (software development) didefinisikan.',
                            'Metode pengembangan aplikasi (software development) dipilih sesuai kebutuhan.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Menggunakan diagram program dan deskripsi program',
                        'kriteria' => [
                            'Diagram program didefinisikan dengan metodologi pengembangan sistem.',
                            'Metode pemodelan, diagram objek dan diagram komponen digunakan pada implementasi program sesuai dengan spesifikasi.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Menerapkan hasil pemodelan ke dalam pengembangan program',
                        'kriteria' => [
                            'Hasil pemodelan yang mendukung kemampuan metodologi dipilih sesuai spesifikasi.',
                            'Hasil pemrograman (Integrated Development Environment-IDE) yang mendukung kemampuan metodologi bahasa pemrograman dipilih sesuai spesifikasi.',
                        ],
                    ],
                ],
            ],

            // ── Unit 3 ────────────────────────────────────────
            [
                'kode_unit'      => 'J.620100.010.01',
                'judul_unit'     => 'Menerapkan Perintah Eksekusi Bahasa Pemrograman Berbasis Teks, Grafik, dan Multimedia',
                'pertanyaan_unit' => 'Dapatkah Saya menerapkan Perintah Eksekusi Bahasa Pemrograman Berbasis Teks, Grafik, dan Multimedia?',
                'elemens' => [
                    [
                        'nama_elemen' => 'Mengidentifikasi mekanisme running atau eksekusi source code',
                        'kriteria' => [
                            'Cara dan tools diidentifikasi untuk mengeksekusi source code.',
                            'Parameter diidentifikasi untuk mengeksekusi source code.',
                            'Peletakan source code diidentifikasi sehingga bisa dieksekusi dengan benar.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Mengeksekusi source code',
                        'kriteria' => [
                            'Source code dieksekusi sesuai dengan mekanisme eksekusi source code dari tools pemrograman yang digunakan.',
                            'Perbedaan diidentifikasi antara running, debugging, atau membuat executable file.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Mengidentifikasi hasil eksekusi',
                        'kriteria' => [
                            'Source code berhasil diidentifikasi sesuai skenario yang direncanakan.',
                            'Jika eksekusi source code gagal/tidak berhasil, diidentifikasi sumber permasalahan.',
                        ],
                    ],
                ],
            ],

            // ── Unit 4 ────────────────────────────────────────
            [
                'kode_unit'      => 'J.620100.016.01',
                'judul_unit'     => 'Menulis Kode Dengan Prinsip Sesuai Guidelines dan Best Practices',
                'pertanyaan_unit' => 'Dapatkah Saya menulis Kode Dengan Prinsip Sesuai Guidelines dan Best Practices?',
                'elemens' => [
                    [
                        'nama_elemen' => 'Menerapkan coding-guidelines dan best practices dalam penulisan program (kode sumber)',
                        'kriteria' => [
                            'Kode sumber dituliskan mengikuti coding-guidelines dan best practices.',
                            'Struktur program dibuat yang sesuai dengan konsep paradigmanya.',
                            'Menangani Galat/error.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Menggunakan ukuran performansi dalam penulisan kode sumber',
                        'kriteria' => [
                            'Efisiensi penggunaan resources dihitung oleh kode.',
                            'Kemudahan interaksi selalu diimplementasikan sesuai standar yang berlaku.',
                        ],
                    ],
                ],
            ],

            // ── Unit 5 ────────────────────────────────────────
            [
                'kode_unit'      => 'J.620100.017.02',
                'judul_unit'     => 'Mengimplementasikan Pemrograman Terstruktur',
                'pertanyaan_unit' => 'Dapatkah Saya mengimplementasikan Pemrograman Terstruktur?',
                'elemens' => [
                    [
                        'nama_elemen' => 'Menggunakan tipe data dan kontrol program',
                        'kriteria' => [
                            'Tipe data yang ditentukan sesuai standar.',
                            'Syntax program yang digunakan sesuai standar.',
                            'Struktur kontrol program yang digunakan sesuai standar.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Membuat program sederhana',
                        'kriteria' => [
                            'Program baca tulis untuk memasukkan data dari keyboard dan menampilkan ke layar monitor termasuk variasinya dibuat sesuai standar masukan/keluaran.',
                            'Struktur kontrol percabangan dan pengulangan digunakan dalam membuat program.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Membuat program menggunakan prosedur dan fungsi',
                        'kriteria' => [
                            'Program dengan menggunakan prosedur dibuat sesuai aturan penulisan program.',
                            'Program dengan menggunakan fungsi dibuat sesuai aturan penulisan program.',
                            'Program dengan menggunakan prosedur dan fungsi secara bersamaan dibuat sesuai aturan penulisan program.',
                            'Keterangan diberikan untuk setiap prosedur dan fungsi.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Membuat program menggunakan array',
                        'kriteria' => [
                            'Dimensi array ditentukan.',
                            'Tipe data array ditentukan.',
                            'Panjang array ditentukan.',
                            'Pengurutan array digunakan.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Membuat program untuk akses file',
                        'kriteria' => [
                            'Program dibuat untuk menulis data dalam media penyimpan.',
                            'Program dibuat untuk membaca data dari media penyimpan.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Mengkompilasi Program',
                        'kriteria' => [
                            'Kesalahan program dikoreksi.',
                            'Kesalahan syntax dalam program dibebaskan.',
                        ],
                    ],
                ],
            ],

            // ── Unit 6 ────────────────────────────────────────
            [
                'kode_unit'      => 'J.620100.023.02',
                'judul_unit'     => 'Membuat Dokumen Kode Program',
                'pertanyaan_unit' => 'Dapatkah Saya melakukan pembuatan dokumen kode program?',
                'elemens' => [
                    [
                        'nama_elemen' => 'Melakukan identifikasi kode program',
                        'kriteria' => [
                            'Modul program diidentifikasi.',
                            'Parameter yang dipergunakan diidentifikasi.',
                            'Algoritma dijelaskan cara kerjanya.',
                            'Komentar setiap baris kode termasuk data, eksepsi, fungsi, prosedur dan class (bila ada) diberikan.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Membuat dokumentasi modul program',
                        'kriteria' => [
                            'Dokumentasi modul dibuat sesuai dengan identitas untuk memudahkan pelacakan.',
                            'Identifikasi dokumentasi diterapkan.',
                            'Kegunaan modul dijelaskan.',
                            'Dokumen direvisi sesuai perubahan kode program.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Membuat dokumentasi fungsi, prosedur atau method program',
                        'kriteria' => [
                            'Dokumentasi fungsi, prosedur atau metod dibuat.',
                            'Kemungkinan eksepsi dijelaskan.',
                            'Dokumen direvisi sesuai perubahan kode program.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Men-generate dokumentasi',
                        'kriteria' => [
                            'Tools untuk generate dokumentasi diidentifikasi.',
                            'Generate dokumentasi dilakukan.',
                        ],
                    ],
                ],
            ],

            // ── Unit 7 ────────────────────────────────────────
            [
                'kode_unit'      => 'J.620100.025.02',
                'judul_unit'     => 'Melakukan Debugging',
                'pertanyaan_unit' => 'Dapatkah Saya melakukan Debugging?',
                'elemens' => [
                    [
                        'nama_elemen' => 'Mempersiapkan kode program',
                        'kriteria' => [
                            'Kode program sesuai spesifikasi disiapkan.',
                            'Debugging tools untuk melihat proses suatu modul dipersiapkan.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Melakukan debugging',
                        'kriteria' => [
                            'Kode program dikompilasi sesuai bahasa pemrograman yang digunakan.',
                            'Kriteria lulus build dianalisis.',
                            'Kriteria eksekusi aplikasi dianalisis.',
                            'Kode kesalahan dicatat.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Memperbaiki program',
                        'kriteria' => [
                            'Perbaikan terhadap kesalahan kompilasi maupun build dirumuskan.',
                            'Perbaikan dilakukan.',
                        ],
                    ],
                ],
            ],

            // ── Unit 8 ────────────────────────────────────────
            [
                'kode_unit'      => 'J.620900.033.02',
                'judul_unit'     => 'Melaksanakan Pengujian Unit Program',
                'pertanyaan_unit' => 'Dapatkah Saya melakukan Pengujian unit Program?',
                'elemens' => [
                    [
                        'nama_elemen' => 'Menentukan kebutuhan uji coba dalam pengembangan',
                        'kriteria' => [
                            'Prosedur uji coba aplikasi diidentifikasikan sesuai dengan software development life cycle.',
                            'Tools uji coba ditentukan.',
                            'Standar dan kondisi uji coba diidentifikasi.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Mempersiapkan dokumentasi uji coba',
                        'kriteria' => [
                            'Mempersiapkan dokumentasi uji coba.',
                            'Uji coba dengan variasi kondisi dapat dilaksanakan.',
                            'Skenario uji coba dibuat.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Mempersiapkan data uji',
                        'kriteria' => [
                            'Data uji unit tes diidentifikasi.',
                            'Data uji unit tes dibangkitkan.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Melaksanakan prosedur uji coba',
                        'kriteria' => [
                            'Skenario uji coba didesain.',
                            'Prosedur uji coba dalam algoritma didesain.',
                            'Uji coba dilaksanakan.',
                        ],
                    ],
                    [
                        'nama_elemen' => 'Mengevaluasi hasil uji coba',
                        'kriteria' => [
                            'Hasil uji coba dicatat.',
                            'Hasil uji coba dianalisis.',
                            'Prosedur uji coba dilaporkan.',
                            'Kesalahan/error diselesaikan.',
                        ],
                    ],
                ],
            ],
        ];

        // ── Seed ke database ───────────────────────────────────
        foreach ($units as $unitData) {
            $unit = Unit::updateOrCreate(
                [
                    'skema_id'  => $skema->id,
                    'kode_unit' => $unitData['kode_unit'],
                ],
                [
                    'judul_unit'      => $unitData['judul_unit'],
                    'pertanyaan_unit' => $unitData['pertanyaan_unit'],
                ]
            );

            foreach ($unitData['elemens'] as $elemenData) {
                $elemen = Elemen::updateOrCreate(
                    [
                        'unit_id'     => $unit->id,
                        'nama_elemen' => $elemenData['nama_elemen'],
                    ]
                );

                foreach ($elemenData['kriteria'] as $urutan => $deskripsi) {
                    Kriteria::updateOrCreate(
                        [
                            'elemen_id'          => $elemen->id,
                            'deskripsi_kriteria' => $deskripsi,
                        ],
                        [
                            'urutan' => $urutan + 1,
                        ]
                    );
                }
            }
        }
    }
}
