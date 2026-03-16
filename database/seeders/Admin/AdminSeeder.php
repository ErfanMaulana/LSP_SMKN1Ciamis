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
        if (!Admin::where('username', 'admin')->exists()) {
            Admin::create([
                'name' => 'Administrator',
                'email' => 'admin@smkn1ciamis.sch.id',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
            ]);
        }

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
    }
}