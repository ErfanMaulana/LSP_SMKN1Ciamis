<?php

namespace Database\Seeders\Reference;

use App\Models\PanduanItem;
use Illuminate\Database\Seeder;

class PanduanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'alur-keseluruhan-sistem' => [
                [
                    'title' => 'Pembuatan Akun oleh Admin',
                    'description' => 'Admin membuat dan mengelola akun untuk Asesi dan Asesor agar setiap pengguna memiliki akses sesuai dengan perannya masing-masing.'
                ],
                [
                    'title' => 'Login dan Identifikasi Peran',
                    'description' => 'Asesi dan Asesor melakukan login melalui halaman yang sama. Sistem secara otomatis mengidentifikasi peran pengguna dan mengarahkan ke dashboard sesuai hak akses.'
                ],
                [
                    'title' => 'Persiapan Data Master',
                    'description' => 'Admin menyiapkan data master seperti skema sertifikasi, unit kompetensi, elemen, kriteria unjuk kerja, TUK, serta jadwal uji kompetensi.'
                ],
                [
                    'title' => 'Pendaftaran Asesi (APL 01)',
                    'description' => 'Asesi melakukan pendaftaran dengan mengisi formulir APL 01 serta melengkapi dokumen persyaratan yang dibutuhkan.'
                ],
                [
                    'title' => 'Verifikasi dan Persetujuan Admin',
                    'description' => 'Admin melakukan verifikasi data pendaftaran Asesi. Jika data dinyatakan valid, maka Asesi akan disetujui (approved) untuk mengikuti tahap selanjutnya.'
                ],
                [
                    'title' => 'Penjadwalan dan Pengelompokan',
                    'description' => 'Admin menyusun kelompok Asesi serta menentukan jadwal dan lokasi uji kompetensi bagi Asesi yang telah disetujui.'
                ],
                [
                    'title' => 'Asesmen Mandiri (APL 02)',
                    'description' => 'Asesi mengisi asesmen mandiri (APL 02) sebagai bentuk evaluasi awal terhadap kompetensi yang dimiliki.'
                ],
                [
                    'title' => 'Rekomendasi Asesor',
                    'description' => 'Asesor meninjau hasil asesmen mandiri dan memberikan rekomendasi. Asesi yang direkomendasikan dapat melanjutkan ke tahap uji kompetensi.'
                ],
                [
                    'title' => 'Pelaksanaan Uji Kompetensi',
                    'description' => 'Asesi mengikuti uji kompetensi sesuai jadwal yang telah ditentukan oleh Admin.'
                ],
                [
                    'title' => 'Penilaian dan Keputusan',
                    'description' => 'Asesor melakukan penilaian hasil uji kompetensi dan menentukan apakah Asesi dinyatakan Kompeten (K) atau Belum Kompeten (BK).'
                ],
                [
                    'title' => 'Penerbitan Sertifikat',
                    'description' => 'Asesi yang dinyatakan Kompeten akan diproses untuk penerbitan sertifikat sebagai bukti kelulusan.'
                ],
                [
                    'title' => 'Monitoring dan Laporan',
                    'description' => 'Admin memantau seluruh proses dan hasil asesmen, sementara Asesi dapat melihat status akhir serta ringkasan hasil uji kompetensi.'
                ],
            ],
            'peran-asesi' => [
                ['title' => 'Login ke Panel Asesi', 'description' => 'Pastikan akun Asesi sudah dibuat oleh Admin. Setelah itu, login melalui halaman utama dan sistem akan mengarahkan ke area Asesi.'],
                ['title' => 'Isi Form Pendaftaran', 'description' => 'Lengkapi data pribadi dan data pendukung sesuai form pendaftaran pada panel Asesi.'],
                ['title' => 'Upload Dokumen', 'description' => 'Unggah berkas seperti pas foto, identitas, bukti kompetensi, dan transkrip nilai.'],
                ['title' => 'Menunggu Persetujuan Admin', 'description' => 'Sebelum disetujui Admin, akses fitur inti masih dibatasi. Jika ditolak, perbaiki data lalu kirim ulang.'],
                ['title' => 'Kerjakan Asesmen Mandiri', 'description' => 'Setelah approved, Asesi dapat mengisi FR.APL.02 pada menu asesmen mandiri berdasarkan skema yang tersedia.'],
                ['title' => 'Cek Jadwal dan Hasil', 'description' => 'Pantau jadwal ujikom, hasil asesmen, serta update profil dan password secara mandiri.'],
            ],
            'peran-asesor' => [
                ['title' => 'Login ke Panel Asesor', 'description' => 'Akun Asesor dibuat oleh Admin. Asesor login dari halaman umum lalu diarahkan ke area Asesor.'],
                ['title' => 'Tinjau Jadwal dan Kelompok', 'description' => 'Asesor melihat jadwal ujikom serta kelompok Asesi yang menjadi tanggung jawabnya.'],
                ['title' => 'Entry Penilaian', 'description' => 'Input nilai per Asesi melalui fitur entry penilaian sesuai instrumen yang berlaku.'],
                ['title' => 'Review Berkas dan Hasil Asesi', 'description' => 'Lakukan peninjauan hasil asesmen mandiri maupun data pendukung untuk memastikan objektivitas.'],
                ['title' => 'Beri Rekomendasi', 'description' => 'Asesor memberikan rekomendasi akhir kompetensi untuk setiap Asesi.'],
                ['title' => 'Sinkron dengan Monitoring Admin', 'description' => 'Semua penilaian akan termonitor di panel Admin sebagai dasar pengambilan keputusan dan pelaporan.'],
            ],
            'peran-admin' => [
                ['title' => 'Login dan Akses Dashboard', 'description' => 'Admin masuk melalui login admin untuk mengakses dashboard manajemen.'],
                ['title' => 'Buat Akun Asesi dan Asesor', 'description' => 'Admin mendaftarkan akun pengguna agar Asesi dan Asesor bisa login dan menjalankan tugas masing-masing.'],
                ['title' => 'Kelola Data Master', 'description' => 'Admin menyiapkan jurusan, skema, unit, elemen, kriteria, TUK, jadwal ujikom, mitra, dan konten pendukung front.'],
                ['title' => 'Kelola Hak Akses', 'description' => 'Admin mengatur role dan permission sesuai kebutuhan operasional agar tiap menu hanya diakses pihak berwenang.'],
                ['title' => 'Verifikasi Asesi', 'description' => 'Admin memeriksa kelengkapan data dan dokumen, kemudian melakukan approve/reject beserta catatan.'],
                ['title' => 'Pantau Penilaian Asesor', 'description' => 'Admin memonitor input nilai, progres kelompok, dan hasil rekomendasi dari Asesor.'],
                ['title' => 'Kontrol Kualitas Sistem', 'description' => 'Admin memastikan alur berjalan baik melalui pencarian data, evaluasi hasil, dan pemeliharaan konfigurasi sistem.'],
            ],
        ];

        foreach ($data as $section => $steps) {
            foreach ($steps as $index => $step) {
                PanduanItem::updateOrCreate(
                    [
                        'section' => $section,
                        'title' => $step['title'],
                    ],
                    [
                        'description' => $step['description'],
                        'sort_order' => $index,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
