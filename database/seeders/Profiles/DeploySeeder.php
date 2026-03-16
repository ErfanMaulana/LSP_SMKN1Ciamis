<?php

namespace Database\Seeders\Profiles;

use Database\Seeders\Admin\AdminSeeder;
use Database\Seeders\Admin\RolePermissionSeeder;
use Database\Seeders\Reference\CoreReferenceSeeder;
use Illuminate\Database\Seeder;

class DeploySeeder extends Seeder
{
    /**
     * Seed minimum untuk kebutuhan deploy/production.
     * 
     * Includes:
     * - Role & Permission setup (admin-web, admin-lsp, super-admin)
     * - Admin accounts (superadmin, adminweb, adminlsp)
     * - Core reference data (Jurusan, Skema, etc.)
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            AdminSeeder::class,
            CoreReferenceSeeder::class,
        ]);
    }
}