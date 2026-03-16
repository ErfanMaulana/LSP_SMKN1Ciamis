<?php

namespace Database\Seeders\Admin;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::where('username', 'superadmin')->delete();

        $superAdmin = Admin::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@lsp.local',
            'username' => 'superadmin',
            'password' => Hash::make('superadmin123'),
        ]);

        $superAdminRole = Role::where('is_super_admin', true)->first();
        if ($superAdminRole) {
            $superAdmin->roles()->attach($superAdminRole);
            $this->command->info('✓ Super Admin role attached successfully');
        } else {
            $this->command->warn('⚠ Super Admin role not found! Creating with basic access only.');
        }

        $this->command->info('✓ Super Admin account created!');
        $this->command->info('Username: superadmin');
        $this->command->info('Password: superadmin123');
    }
}