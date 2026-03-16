<?php

namespace Database\Seeders\Admin;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdminRole = Role::where('is_super_admin', true)->first();
        
        $superAdmin = Admin::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@smkn1ciamis.sch.id',
                'password' => Hash::make('superadmin'),
            ]
        );

        if ($superAdminRole && !$superAdmin->roles()->where('role_id', $superAdminRole->id)->exists()) {
            $superAdmin->roles()->attach($superAdminRole->id);
        }

        // Create Admin Web
        $adminWebRole = Role::where('name', 'admin-web')->first();
        
        $adminWeb = Admin::firstOrCreate(
            ['username' => 'adminweb'],
            [
                'name' => 'Admin Web',
                'email' => 'adminweb@smkn1ciamis.sch.id',
                'password' => Hash::make('adminweb'),
            ]
        );

        if ($adminWebRole && !$adminWeb->roles()->where('role_id', $adminWebRole->id)->exists()) {
            $adminWeb->roles()->attach($adminWebRole->id);
        }

        // Create Admin LSP
        $adminLspRole = Role::where('name', 'admin-lsp')->first();
        
        $adminLsp = Admin::firstOrCreate(
            ['username' => 'adminlsp'],
            [
                'name' => 'Admin LSP',
                'email' => 'adminlsp@smkn1ciamis.sch.id',
                'password' => Hash::make('adminlsp'),
            ]
        );

        if ($adminLspRole && !$adminLsp->roles()->where('role_id', $adminLspRole->id)->exists()) {
            $adminLsp->roles()->attach($adminLspRole->id);
        }
    }
}
