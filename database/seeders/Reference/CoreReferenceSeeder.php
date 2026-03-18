<?php

namespace Database\Seeders\Reference;

use Database\Seeders\Reference\CarouselSeeder;
use Database\Seeders\Reference\JurusanSeeder;
use Database\Seeders\Reference\ProfileContentSeeder;
use Database\Seeders\Reference\ProfileVisionMissionSeeder;
use Database\Seeders\Catalog\SkemaAKLSeeder;
use Database\Seeders\Catalog\SkemaDKVSeeder;
use Database\Seeders\Catalog\SkemaHTLSeeder;
use Database\Seeders\Catalog\SkemaKLNSeeder;
use Database\Seeders\Catalog\SkemaMPLBSeeder;
use Database\Seeders\Catalog\SkemaPMSeeder;
use Database\Seeders\Catalog\SkemaSeederRPL;
use Database\Seeders\Reference\SocialMediaSeeder;
use Illuminate\Database\Seeder;

class CoreReferenceSeeder extends Seeder
{
    /**
     * Seed data referensi yang aman dipakai untuk deploy.
     * 
     * Note: Admin dan Role seeders dipindahkan ke DeploySeeder
     * sebagai kebutuhan utama deployment.
     */
    public function run(): void
    {
        $this->call([
            JurusanSeeder::class,
            SkemaSeederRPL::class,
            SkemaDKVSeeder::class,
            SkemaKLNSeeder::class,
            SkemaMPLBSeeder::class,
            SkemaPMSeeder::class,
            SkemaAKLSeeder::class,
            SkemaHTLSeeder::class,
            ProfileContentSeeder::class,
            ProfileVisionMissionSeeder::class,
            SocialMediaSeeder::class,
            CarouselSeeder::class,
            PanduanSeeder::class,
        ]);
    }
}