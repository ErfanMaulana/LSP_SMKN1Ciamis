<?php

namespace Database\Seeders;

use App\Models\ProfileContent;
use Illuminate\Database\Seeder;

class ProfileContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sejarah Singkat
        ProfileContent::create([
            'type' => 'sejarah',
            'title' => 'Sejarah Singkat',
            'content' => 'LSP P1 SMKN 1 Ciamis didirikan dengan semangat untuk memberikan pengakuan kompetensi formal bagi lulusan pendidikan vokasi. Sebagai salah satu SMK Pusat Keunggulan, kami meyakinkan bahwa setiap jebolan siap di pasar kerja global yang kompeten!',
            'icon' => null,
            'order' => 0,
            'is_active' => true,
        ]);

        ProfileContent::create([
            'type' => 'sejarah',
            'title' => 'Visi Misi',
            'content' => 'Melalui lisensi resmi dari Badan Nasional Sertifikasi Profesi (BNSP), kami terus berinovasi dalam mengembangkan skema sertifikasi yang relevan dengan kebutuhan industri, Dunia Usaha, dan Dunia Kerja (DUDI/KA).',
            'icon' => null,
            'order' => 1,
            'is_active' => true,
        ]);

        // Milestone Perjalanan
        ProfileContent::create([
            'type' => 'milestone',
            'title' => 'Inisiasi & Persiapan',
            'content' => 'Pembentukan tim pengembang dan penyusunan dokumen sistem manajemen sertifikasi sesuai standar BNSP.',
            'icon' => 'bi bi-star-fill',
            'order' => 0,
            'is_active' => true,
        ]);

        ProfileContent::create([
            'type' => 'milestone',
            'title' => 'Lisensi BNSP',
            'content' => 'Resmi memperoleh lisensi dari BNSP sebagai Lembaga Sertifikasi Profesi untuk menyelenggarakan uji kompetensi 5 skema.',
            'icon' => 'bi bi-patch-check-fill',
            'order' => 1,
            'is_active' => true,
        ]);

        ProfileContent::create([
            'type' => 'milestone',
            'title' => 'Re-Akreditasi',
            'content' => 'Keberhasilan melewati proses re-akreditasi BNSP dengan penambahan ruang lingkup skema baru.',
            'icon' => 'bi bi-arrow-repeat',
            'order' => 2,
            'is_active' => true,
        ]);
    }
}
