<?php

namespace Database\Seeders\Admin;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::where('username', 'superadmin')->delete();

        Admin::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@lsp.local',
            'username' => 'superadmin',
            'password' => Hash::make('superadmin123'),
        ]);

        echo "✓ Admin created!" . PHP_EOL;
        echo "Username: superadmin" . PHP_EOL;
        echo "Password: superadmin123" . PHP_EOL;
    }
}