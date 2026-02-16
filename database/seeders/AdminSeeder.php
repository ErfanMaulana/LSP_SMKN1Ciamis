<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@smkn1ciamis.sch.id',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
    }
}
