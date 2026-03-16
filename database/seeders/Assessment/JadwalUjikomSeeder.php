<?php

namespace Database\Seeders\Assessment;

use Illuminate\Database\Seeder;
use App\Models\JadwalUjikom;
use App\Models\Tuk;
use App\Models\Skema;

class JadwalUjikomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tuk = Tuk::first();
        $skemas = Skema::all();

        if (!$tuk || $skemas->isEmpty()) {
            $this->command->warn('No TUK or Skema found. Please seed those first.');
            return;
        }

        $jadwalData = [
            [
                'judul_jadwal' => 'Ujikom Web Development Maret 2026',
                'tuk_id' => $tuk->id,
                'skema_id' => $skemas->random()->id,
                'tanggal_mulai' => '2026-03-10',
                'tanggal_selesai' => '2026-03-11',
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '16:00:00',
                'kuota' => 30,
                'peserta_terdaftar' => 0,
                'status' => 'dijadwalkan',
                'keterangan' => 'Jadwal ujikom untuk skema Web Development.',
            ],
            [
                'judul_jadwal' => 'Ujikom Junior Programmer Batch 1',
                'tuk_id' => $tuk->id,
                'skema_id' => $skemas->random()->id,
                'tanggal_mulai' => '2026-03-15',
                'tanggal_selesai' => '2026-03-16',
                'waktu_mulai' => '07:30:00',
                'waktu_selesai' => '15:30:00',
                'kuota' => 25,
                'peserta_terdaftar' => 0,
                'status' => 'dijadwalkan',
                'keterangan' => 'Ujikom untuk sertifikasi Junior Programmer.',
            ],
            [
                'judul_jadwal' => 'Ujikom Database Administrator',
                'tuk_id' => $tuk->id,
                'skema_id' => $skemas->random()->id,
                'tanggal_mulai' => '2026-03-20',
                'tanggal_selesai' => '2026-03-21',
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '17:00:00',
                'kuota' => 20,
                'peserta_terdaftar' => 0,
                'status' => 'dijadwalkan',
                'keterangan' => 'Ujikom untuk sertifikasi Database Administrator.',
            ],
            [
                'judul_jadwal' => 'Ujikom Digital Marketing Gelombang 1',
                'tuk_id' => $tuk->id,
                'skema_id' => $skemas->random()->id,
                'tanggal_mulai' => '2026-03-25',
                'tanggal_selesai' => '2026-03-26',
                'waktu_mulai' => '09:00:00',
                'waktu_selesai' => '16:00:00',
                'kuota' => 35,
                'peserta_terdaftar' => 0,
                'status' => 'dijadwalkan',
                'keterangan' => 'Ujikom untuk sertifikasi Digital Marketing.',
            ],
            [
                'judul_jadwal' => 'Ujikom UI/UX Designer',
                'tuk_id' => $tuk->id,
                'skema_id' => $skemas->random()->id,
                'tanggal_mulai' => '2026-04-05',
                'tanggal_selesai' => '2026-04-06',
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '16:00:00',
                'kuota' => 28,
                'peserta_terdaftar' => 0,
                'status' => 'dijadwalkan',
                'keterangan' => 'Ujikom untuk sertifikasi UI/UX Designer.',
            ],
            [
                'judul_jadwal' => 'Ujikom Mobile App Developer',
                'tuk_id' => $tuk->id,
                'skema_id' => $skemas->random()->id,
                'tanggal_mulai' => '2026-04-12',
                'tanggal_selesai' => '2026-04-13',
                'waktu_mulai' => '08:00:00',
                'waktu_selesai' => '17:00:00',
                'kuota' => 22,
                'peserta_terdaftar' => 0,
                'status' => 'dijadwalkan',
                'keterangan' => 'Ujikom untuk sertifikasi Mobile App Developer.',
            ],
            [
                'judul_jadwal' => 'Ujikom Data Analyst Batch 2',
                'tuk_id' => $tuk->id,
                'skema_id' => $skemas->random()->id,
                'tanggal_mulai' => '2026-04-20',
                'tanggal_selesai' => '2026-04-21',
                'waktu_mulai' => '07:00:00',
                'waktu_selesai' => '16:00:00',
                'kuota' => 30,
                'peserta_terdaftar' => 0,
                'status' => 'dijadwalkan',
                'keterangan' => 'Ujikom untuk sertifikasi Data Analyst.',
            ],
        ];

        foreach ($jadwalData as $data) {
            if (!JadwalUjikom::where('judul_jadwal', $data['judul_jadwal'])->exists()) {
                JadwalUjikom::create($data);
            }
        }

        $this->command->info('Jadwal Ujikom seeder completed successfully! Added multiple jadwal for March and April 2026.');
    }
}
