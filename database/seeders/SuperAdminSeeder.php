<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing superadmin if exists
        Admin::where('username', 'superadmin')->delete();

        // Create new Super Admin account
        $superAdmin = Admin::create([
            'name'     => 'Super Administrator',
            'email'    => 'superadmin@lsp.local',
            'username' => 'superadmin',
            'password' => Hash::make('superadmin123'),
        ]);

        // Attach super admin role
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
