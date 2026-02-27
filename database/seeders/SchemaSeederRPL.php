<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchemaSeederRPL extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Run all seeders
        $this->call([
            SkemaSeeder::class,
            UnitSeeder::class,
            ElemenSeeder::class,
            KriteriaSeeder::class,
        ]);
    }
}
