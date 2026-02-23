<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Create test user only if not exists
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }

        // Run all seeders
        $this->call([
            AdminSeeder::class,
            JurusanSeeder::class,
            MitraSeeder::class,
            AsesorSeeder::class,
            AsesiSeeder::class,
            SkemaSeederRPL::class,
        ]);
    }
}
