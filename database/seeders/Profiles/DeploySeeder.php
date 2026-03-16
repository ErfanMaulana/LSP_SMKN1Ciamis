<?php

namespace Database\Seeders\Profiles;

use Database\Seeders\Reference\CoreReferenceSeeder;
use Illuminate\Database\Seeder;

class DeploySeeder extends Seeder
{
    /**
     * Seed minimum untuk kebutuhan deploy/production.
     */
    public function run(): void
    {
        $this->call([
            CoreReferenceSeeder::class,
        ]);
    }
}