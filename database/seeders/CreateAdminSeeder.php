<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class CreateAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing admin
        Admin::where('username', 'superadmin')->delete();

        // Create superadmin
        Admin::create([
            'name'     => 'Super Administrator',
            'email'    => 'superadmin@lsp.local',
            'username' => 'superadmin',
            'password' => Hash::make('superadmin123'),
        ]);

        echo "✓ Admin created!" . PHP_EOL;
        echo "Username: superadmin" . PHP_EOL;
        echo "Password: superadmin123" . PHP_EOL;
    }
}
