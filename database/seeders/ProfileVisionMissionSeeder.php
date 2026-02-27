<?php

namespace Database\Seeders;

use App\Models\ProfileVisionMission;
use Illuminate\Database\Seeder;

class ProfileVisionMissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Visi
        ProfileVisionMission::create([
            'type' => 'visi',
            'content' => '"Menjadi platform digital resmi LSP SMKN 1 Ciamis yang terintegrasi, modern, dan profesional dalam mendukung seluruh proses sertifikasi kompetensi secara transparan, akurat, dan efisien, serta mampu meningkatkan kualitas layanan, kredibilitas lembaga, dan daya saing lulusan di dunia usaha dan dunia industri melalui pemanfaatan teknologi informasi."',
            'order' => 0,
            'is_active' => true,
        ]);

        // Misi items
        ProfileVisionMission::create([
            'type' => 'misi',
            'content' => 'Menyelenggarakan sertifikasi kompetensi yang transparan, objektif, dan terpercaya.',
            'order' => 0,
            'is_active' => true,
        ]);

        ProfileVisionMission::create([
            'type' => 'misi',
            'content' => 'Menyediakan asesor yang profesional dan kompeten di bidangnya.',
            'order' => 1,
            'is_active' => true,
        ]);

        ProfileVisionMission::create([
            'type' => 'misi',
            'content' => 'Mengembangkan skema sertifikasi sesuai dinamika kebutuhan pasar kerja.',
            'order' => 2,
            'is_active' => true,
        ]);

        ProfileVisionMission::create([
            'type' => 'misi',
            'content' => 'Meningkatkan kerjasama strategis dengan dunia usaha dan industri.',
            'order' => 3,
            'is_active' => true,
        ]);
    }
}
