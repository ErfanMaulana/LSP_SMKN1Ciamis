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
                ['title' => 'Admin Membuat Akun Asesi dan Asesor', 'description' => 'Alur dimulai dari Admin dengan menyiapkan akun untuk Asesi dan Asesor agar setiap pengguna memiliki akses sesuai peran.'],
                ['title' => 'Login dan Identifikasi Peran', 'description' => 'Setelah akun tersedia, pengguna login dari halaman yang sama. Sistem membaca role akun lalu mengarahkan ke dashboard Admin, Asesi, atau Asesor.'],
                ['title' => 'Persiapan Data Master oleh Admin', 'description' => 'Admin menyiapkan jurusan, skema, unit, elemen, kriteria, jadwal, TUK, serta pembagian kelompok dan penugasan Asesor.'],
                ['title' => 'Pendaftaran dan Kelengkapan Berkas Asesi', 'description' => 'Asesi mengisi formulir pendaftaran, melengkapi dokumen, lalu menunggu proses verifikasi dari Admin.'],
                ['title' => 'Verifikasi Admin', 'description' => 'Admin melakukan approve atau reject data Asesi. Asesi yang approved bisa lanjut ke asesmen mandiri dan jadwal ujikom.'],
                ['title' => 'Asesmen dan Penilaian', 'description' => 'Asesi mengerjakan asesmen mandiri (FR.APL.02), lalu Asesor melakukan entry nilai dan memberi rekomendasi kompetensi.'],
                ['title' => 'Monitoring Hasil', 'description' => 'Admin memantau progres dan hasil, sementara Asesi melihat status akhir dan ringkasan hasil ujikom.'],
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
