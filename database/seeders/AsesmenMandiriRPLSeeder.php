<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsesmenMandiriRPLSeeder extends Seeder
{
    /**
     * Seed asesmen mandiri untuk 36 asesi RPL:
     *  - asesi_skema : status=selesai, rekomendasi=lanjut, reviewed oleh asesor RPL
     *  - jawaban_elemens : semua 29 elemen dijawab 'K' (Kompeten)
     *
     * Skema : id=1 (Okupasi Pemrogram Junior / Junior Coder)
     * Asesor : no_reg='123' (Andi Saputra, ID_skema=1)
     */
    public function run(): void
    {
        $skemaId    = 1;
        $asesorNoReg = '123';

        // Ambil semua elemen milik skema RPL
        $elemenIds = DB::table('elemens')
            ->join('units', 'units.id', '=', 'elemens.unit_id')
            ->where('units.skema_id', $skemaId)
            ->pluck('elemens.id')
            ->toArray();

        if (empty($elemenIds)) {
            $this->command->error('Tidak ada elemen untuk skema id=' . $skemaId . '. Jalankan ElemenSeeder terlebih dahulu.');
            return;
        }

        // Ambil semua asesi RPL
        $asesiList = DB::table('asesi')
            ->where('ID_jurusan', 2)
            ->whereNotNull('no_reg')
            ->select('NIK', 'nama', 'no_reg')
            ->orderBy('no_reg')
            ->get();

        if ($asesiList->isEmpty()) {
            $this->command->error('Tidak ada asesi RPL. Jalankan AsesiRPLSeeder terlebih dahulu.');
            return;
        }

        $count        = 0;
        $now          = now();
        $catatanList  = [
            'Asesi menunjukkan kompetensi yang baik di seluruh elemen penilaian.',
            'Berdasarkan asesmen mandiri, asesi dinyatakan kompeten dan dapat melanjutkan.',
            'Semua bukti kompetensi relevan dan memenuhi standar yang ditetapkan.',
            'Asesi telah memenuhi seluruh kriteria unjuk kerja dengan baik.',
            'Kemampuan teknis asesi sesuai dengan standar kompetensi yang dipersyaratkan.',
            'Asesi memiliki pemahaman yang memadai dan dapat dilanjutkan ke tahap berikutnya.',
        ];

        $buktiBySklil = [
            'Ijazah / transkrip nilai SMK',
            'Sertifikat pelatihan pemrograman',
            'Portofolio proyek aplikasi',
            'Laporan praktik kerja industri',
            'Sertifikat lomba / kompetisi pemrograman',
        ];

        foreach ($asesiList as $idx => $asesi) {
            // ── 1. asesi_skema ──────────────────────────────────────
            $existingSkema = DB::table('asesi_skema')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skemaId)
                ->first();

            if ($existingSkema) {
                // Update jika sudah ada tapi belum direkomendasikan
                if ($existingSkema->rekomendasi !== 'lanjut') {
                    DB::table('asesi_skema')
                        ->where('id', $existingSkema->id)
                        ->update([
                            'status'         => 'selesai',
                            'tanggal_selesai'=> $now->copy()->subDays(rand(1, 14)),
                            'rekomendasi'    => 'lanjut',
                            'catatan_asesor' => $catatanList[$idx % count($catatanList)],
                            'reviewed_at'    => $now->copy()->subDays(rand(0, 3)),
                            'reviewed_by'    => $asesorNoReg,
                            'updated_at'     => $now,
                        ]);
                }
            } else {
                $mulai   = $now->copy()->subDays(rand(20, 30));
                $selesai = $mulai->copy()->addDays(rand(3, 10));

                DB::table('asesi_skema')->insert([
                    'asesi_nik'      => $asesi->NIK,
                    'skema_id'       => $skemaId,
                    'status'         => 'selesai',
                    'tanggal_mulai'  => $mulai,
                    'tanggal_selesai'=> $selesai,
                    'rekomendasi'    => 'lanjut',
                    'catatan_asesor' => $catatanList[$idx % count($catatanList)],
                    'reviewed_at'    => $selesai->copy()->addDay(),
                    'reviewed_by'    => $asesorNoReg,
                    'created_at'     => $mulai,
                    'updated_at'     => $now,
                ]);
            }

            // ── 2. jawaban_elemens ──────────────────────────────────
            $jawabanRows = [];
            foreach ($elemenIds as $elemenId) {
                // Cek sudah ada
                $exists = DB::table('jawaban_elemens')
                    ->where('asesi_nik', $asesi->NIK)
                    ->where('elemen_id', $elemenId)
                    ->exists();

                if (!$exists) {
                    $jawabanRows[] = [
                        'asesi_nik'  => $asesi->NIK,
                        'elemen_id'  => $elemenId,
                        'status'     => 'K',  // Kompeten
                        'bukti'      => $buktiBySklil[($idx + $elemenId) % count($buktiBySklil)],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($jawabanRows)) {
                // Insert dalam batch untuk efisiensi
                foreach (array_chunk($jawabanRows, 50) as $chunk) {
                    DB::table('jawaban_elemens')->insert($chunk);
                }
            }

            $count++;
        }

        $totalJawaban = $count * count($elemenIds);
        $this->command->info("✅ Asesmen mandiri selesai untuk {$count} asesi RPL");
        $this->command->info("   Skema    : Okupasi Pemrogram Junior (id={$skemaId})");
        $this->command->info("   Elemen   : " . count($elemenIds) . " elemen × {$count} asesi = {$totalJawaban} jawaban");
        $this->command->info("   Status   : selesai | Rekomendasi: lanjut | Asesor: {$asesorNoReg}");
    }
}
