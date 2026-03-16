<?php

namespace Database\Seeders\Profiles;

use Database\Seeders\Admin\AdminSeeder;
use Database\Seeders\Admin\RolePermissionSeeder;
use Database\Seeders\Reference\CoreReferenceSeeder;
use Database\Seeders\Testing\AsesiSeeder;
use Database\Seeders\Testing\AsesorSeeder;
use Database\Seeders\Testing\MitraSeeder;
use Illuminate\Database\Seeder;

class TestingSeeder extends Seeder
{
    /**
     * Seed data untuk pengembangan dan testing fitur inti.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminSeeder::class,
            CoreReferenceSeeder::class,
            MitraSeeder::class,
            AsesorSeeder::class,
            AsesiSeeder::class,
        ]);
    }
}