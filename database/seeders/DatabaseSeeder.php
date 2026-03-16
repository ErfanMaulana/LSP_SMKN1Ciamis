<?php

namespace Database\Seeders;

use Database\Seeders\Profiles\DemoSeeder;
use Database\Seeders\Profiles\DeploySeeder;
use Database\Seeders\Profiles\TestingSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $profile = env('SEED_PROFILE');

        if (!$profile) {
            $profile = app()->environment('production') ? 'deploy' : 'testing';
        }

        match ($profile) {
            'deploy' => $this->call([DeploySeeder::class]),
            'demo' => $this->call([DemoSeeder::class]),
            default => $this->call([TestingSeeder::class]),
        };
    }
}
