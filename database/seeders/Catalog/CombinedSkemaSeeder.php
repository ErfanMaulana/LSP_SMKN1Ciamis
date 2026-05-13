<?php

namespace Database\Seeders\Catalog;

use Illuminate\Database\Seeder;

class CombinedSkemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Memanggil semua seeder skema untuk menghindari duplikasi dan menjaga konsistensi data.
     */
    public function run(): void
    {
        $this->call([
            SkemaSeeder::class,
            SkemaAKLSeeder::class,
            SkemaDKVSeeder::class,
            SkemaHTLSeeder::class,
            SkemaKLNSeeder::class,
            SkemaMPLBSeeder::class,
            SkemaPMSeeder::class,
            SkemaSeederRPL::class,
        ]);
    }
}
