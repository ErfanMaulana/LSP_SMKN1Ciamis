<?php

namespace Database\Seeders;

use App\Models\SocialMedia;
use Illuminate\Database\Seeder;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialMedia::create([
            'platform' => 'facebook',
            'name' => 'LSP SMKN 1 Ciamis',
            'url' => 'https://facebook.com/lspsmkn1ciamis',
            'is_active' => true,
            'urutan' => 1,
        ]);

        SocialMedia::create([
            'platform' => 'instagram',
            'name' => 'lsp_smkn1_ciamis',
            'url' => 'https://instagram.com/lsp_smkn1_ciamis',
            'is_active' => true,
            'urutan' => 2,
        ]);

        SocialMedia::create([
            'platform' => 'twitter',
            'name' => 'LSP SMKN 1 Ciamis',
            'url' => 'https://twitter.com/lspsmkn1ciamis',
            'is_active' => true,
            'urutan' => 3,
        ]);

        SocialMedia::create([
            'platform' => 'youtube',
            'name' => 'LSP SMKN 1 Ciamis',
            'url' => 'https://youtube.com/@lspsmkn1ciamis',
            'is_active' => true,
            'urutan' => 4,
        ]);

        SocialMedia::create([
            'platform' => 'whatsapp',
            'name' => 'LSP SMKN 1 Ciamis',
            'url' => 'https://wa.me/628123456789',
            'is_active' => true,
            'urutan' => 5,
        ]);
    }
}
