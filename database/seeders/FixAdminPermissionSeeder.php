<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class FixAdminPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create or get Super Admin role
        $superAdminRole = Role::firstOrCreate(
            ['is_super_admin' => true],
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full access to all features'
            ]
        );

        // Get or create admin
        $admin = Admin::where('username', 'superadmin')->first();
        
        if (!$admin) {
            $admin = Admin::create([
                'name'     => 'Super Administrator',
                'email'    => 'superadmin@lsp.local',
                'username' => 'superadmin',
                'password' => Hash::make('superadmin123'),
            ]);
            echo "✓ Admin created" . PHP_EOL;
        } else {
            echo "✓ Admin already exists" . PHP_EOL;
        }

        // Assign super admin role
        if (!$admin->roles()->where('role_id', $superAdminRole->id)->exists()) {
            $admin->roles()->attach($superAdminRole->id);
            echo "✓ Role assigned to admin" . PHP_EOL;
        }

        echo "✓ Setup complete!" . PHP_EOL;
        echo "Username: superadmin" . PHP_EOL;
        echo "Password: superadmin123" . PHP_EOL;
    }
}
