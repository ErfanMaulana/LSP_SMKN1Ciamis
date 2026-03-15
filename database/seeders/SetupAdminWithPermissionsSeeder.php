<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class SetupAdminWithPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create all permissions if they don't exist
        $permissions = [
            'dashboard.view',
            'asesi.view', 'asesi.create', 'asesi.edit', 'asesi.delete',
            'verifikasi-asesi.view', 'verifikasi-asesi.approve', 'verifikasi-asesi.reject',
            'asesor.view', 'asesor.create', 'asesor.edit', 'asesor.delete',
            'jurusan.view', 'jurusan.create', 'jurusan.edit', 'jurusan.delete',
            'skema.view', 'skema.create', 'skema.edit', 'skema.delete',
            'tuk.view', 'tuk.create', 'tuk.edit', 'tuk.delete',
            'carousel.view', 'carousel.create', 'carousel.edit', 'carousel.delete',
            'admin-management.view', 'admin-management.create', 'admin-management.edit', 'admin-management.delete',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm],
                ['display_name' => ucfirst(str_replace('.', ' ', $perm))]
            );
        }
        echo "✓ Permissions created" . PHP_EOL;

        // Create Super Admin Role with all permissions
        $superAdminRole = Role::updateOrCreate(
            ['name' => 'super_admin'],
            [
                'display_name' => 'Super Administrator',
                'description' => 'Full access to all features',
                'is_super_admin' => true
            ]
        );

        // Assign all permissions to super admin role
        $allPermissions = Permission::all();
        $superAdminRole->permissions()->sync($allPermissions->pluck('id'));
        echo "✓ Permissions assigned to super admin role" . PHP_EOL;

        // Create or update admin
        $admin = Admin::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'name'     => 'Super Administrator',
                'email'    => 'superadmin@lsp.local',
                'password' => Hash::make('superadmin123'),
            ]
        );
        echo "✓ Admin created/updated" . PHP_EOL;

        // Assign role to admin
        $admin->roles()->sync([$superAdminRole->id]);
        echo "✓ Role assigned to admin" . PHP_EOL;

        echo "\n✅ Setup complete!" . PHP_EOL;
        echo "Username: superadmin" . PHP_EOL;
        echo "Password: superadmin123" . PHP_EOL;
    }
}
