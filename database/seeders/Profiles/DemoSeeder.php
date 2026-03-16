<?php

namespace Database\Seeders\Profiles;

use Database\Seeders\Assessment\JadwalUjikomSeeder;
use Database\Seeders\Catalog\ElemenSeeder;
use Database\Seeders\Catalog\KriteriaSeeder;
use Database\Seeders\Catalog\UnitSeeder;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Seed data untuk demo alur aplikasi di luar kebutuhan deploy.
     */
    public function run(): void
    {
        $this->call([
            TestingSeeder::class,
            UnitSeeder::class,
            ElemenSeeder::class,
            KriteriaSeeder::class,
            JadwalUjikomSeeder::class,
        ]);
    }
}