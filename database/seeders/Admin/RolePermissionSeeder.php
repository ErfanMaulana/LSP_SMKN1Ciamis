<?php

namespace Database\Seeders\Admin;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissionGroups = [
            'Dashboard' => [
                'dashboard.view' => 'Lihat Dashboard',
            ],
            'Asesor' => [
                'asesor.view'   => 'Lihat Asesor',
                'asesor.create' => 'Tambah Asesor',
                'asesor.edit'   => 'Edit Asesor',
                'asesor.delete' => 'Hapus Asesor',
            ],
            'Asesi' => [
                'asesi.view'   => 'Lihat Asesi',
                'asesi.create' => 'Tambah Asesi',
                'asesi.edit'   => 'Edit Asesi',
                'asesi.delete' => 'Hapus Asesi',
            ],
            'Verifikasi Asesi' => [
                'verifikasi-asesi.view'    => 'Lihat Verifikasi Asesi',
                'verifikasi-asesi.approve' => 'Approve Asesi',
                'verifikasi-asesi.reject'  => 'Reject Asesi',
            ],
            'Akun Asesi' => [
                'akun-asesi.view'   => 'Lihat Akun Asesi',
                'akun-asesi.create' => 'Tambah Akun Asesi',
                'akun-asesi.delete' => 'Hapus Akun Asesi',
                'akun-asesi.reset'  => 'Reset Password Akun Asesi',
                'akun-asesi.import' => 'Import Akun Asesi',
            ],
            'Kelompok' => [
                'kelompok.view'   => 'Lihat Kelompok',
                'kelompok.create' => 'Tambah Kelompok',
                'kelompok.edit'   => 'Edit Kelompok',
                'kelompok.delete' => 'Hapus Kelompok',
                'kelompok.manage' => 'Kelola Anggota Kelompok',
            ],
            'Mitra' => [
                'mitra.view'   => 'Lihat Mitra',
                'mitra.create' => 'Tambah Mitra',
                'mitra.edit'   => 'Edit Mitra',
                'mitra.delete' => 'Hapus Mitra',
            ],
            'Jurusan' => [
                'jurusan.view'   => 'Lihat Jurusan',
                'jurusan.create' => 'Tambah Jurusan',
                'jurusan.edit'   => 'Edit Jurusan',
                'jurusan.delete' => 'Hapus Jurusan',
            ],
            'Skema' => [
                'skema.view'   => 'Lihat Skema',
                'skema.create' => 'Tambah Skema',
                'skema.edit'   => 'Edit Skema',
                'skema.delete' => 'Hapus Skema',
            ],
            'TUK' => [
                'tuk.view'   => 'Lihat TUK',
                'tuk.create' => 'Tambah TUK',
                'tuk.edit'   => 'Edit TUK',
                'tuk.delete' => 'Hapus TUK',
            ],
            'Jadwal Ujikom' => [
                'jadwal-ujikom.view'   => 'Lihat Jadwal Ujikom',
                'jadwal-ujikom.create' => 'Tambah Jadwal Ujikom',
                'jadwal-ujikom.edit'   => 'Edit Jadwal Ujikom',
                'jadwal-ujikom.delete' => 'Hapus Jadwal Ujikom',
                'jadwal-ujikom.status' => 'Ubah Status Jadwal',
            ],
            'Penugasan Asesor' => [
                'penugasan-asesor.view'   => 'Lihat Penugasan Asesor',
                'penugasan-asesor.assign' => 'Assign Asesor',
            ],
            'Asesmen Mandiri' => [
                'asesmen-mandiri.view' => 'Lihat Asesmen Mandiri',
                'nilai-asesor.view'    => 'Lihat Nilai',
            ],
            'Banding Asesmen' => [
                'banding-asesmen.view' => 'Lihat Banding Asesmen',
                'banding-asesmen.check' => 'Cek Banding Asesmen',
                'banding-asesmen-komponen.view' => 'Lihat Komponen Ceklis Banding',
                'banding-asesmen-komponen.create' => 'Tambah Komponen Ceklis Banding',
                'banding-asesmen-komponen.edit' => 'Edit Komponen Ceklis Banding',
                'banding-asesmen-komponen.delete' => 'Hapus Komponen Ceklis Banding',
            ],
            'Carousel' => [
                'carousel.view'   => 'Lihat Carousel',
                'carousel.create' => 'Tambah Carousel',
                'carousel.edit'   => 'Edit Carousel',
                'carousel.delete' => 'Hapus Carousel',
            ],
            'Berita' => [
                'berita.view'   => 'Lihat Berita',
                'berita.create' => 'Tambah Berita',
                'berita.edit'   => 'Edit Berita',
                'berita.delete' => 'Hapus Berita',
            ],
            'Kontak' => [
                'kontak.view' => 'Lihat Kontak',
                'kontak.edit' => 'Edit Kontak',
            ],
            'Sosial Media' => [
                'socialmedia.view'   => 'Lihat Sosial Media',
                'socialmedia.create' => 'Tambah Sosial Media',
                'socialmedia.edit'   => 'Edit Sosial Media',
                'socialmedia.delete' => 'Hapus Sosial Media',
            ],
            'Konten Profil' => [
                'profile-content.view'   => 'Lihat Konten Profil',
                'profile-content.create' => 'Tambah Konten Profil',
                'profile-content.edit'   => 'Edit Konten Profil',
                'profile-content.delete' => 'Hapus Konten Profil',
            ],
            'Panduan' => [
                'panduan.view'   => 'Lihat Panduan',
                'panduan.create' => 'Tambah Poin Panduan',
                'panduan.edit'   => 'Edit Poin Panduan',
                'panduan.delete' => 'Hapus Poin Panduan',
            ],
            'Manajemen Admin' => [
                'admin.view'   => 'Lihat Admin',
                'admin.create' => 'Tambah Admin',
                'admin.edit'   => 'Edit Admin',
                'admin.delete' => 'Hapus Admin',
            ],
            'Role & Permission' => [
                'role.view'   => 'Lihat Role',
                'role.create' => 'Tambah Role',
                'role.edit'   => 'Edit Role',
                'role.delete' => 'Hapus Role',
            ],
        ];

        $allPermissionIds = [];
        foreach ($permissionGroups as $group => $permissions) {
            foreach ($permissions as $name => $displayName) {
                $perm = Permission::updateOrCreate(
                    ['name' => $name],
                    ['display_name' => $displayName, 'group' => $group]
                );
                $allPermissionIds[] = $perm->id;
            }
        }

        $superAdmin = Role::updateOrCreate(
            ['name' => 'super-admin'],
            [
                'display_name' => 'Super Admin',
                'description'  => 'Full access to all features',
                'is_super_admin' => true,
            ]
        );
        $superAdmin->permissions()->sync($allPermissionIds);

        // $operator = Role::firstOrCreate(
        //     ['name' => 'operator'],
        //     [
        //         'display_name' => 'Operator',
        //         'description'  => 'Manages day-to-day operations',
        //         'is_super_admin' => false,
        //     ]
        // );
        // $operatorPerms = Permission::whereIn('group', [
        //     'Dashboard', 'Asesi', 'Verifikasi Asesi', 'Akun Asesi', 'Kelompok',
        // ])->pluck('id');
        // $operator->permissions()->sync($operatorPerms);

        // Admin Web Role - Manages frontend website
        $adminWeb = Role::updateOrCreate(
            ['name' => 'admin-web'],
            [
                'display_name' => 'Admin Web',
                'description'  => 'Manages website content and settings',
                'is_super_admin' => false,
            ]
        );
        $adminWebPerms = Permission::whereIn('group', [
            'Dashboard', 'Carousel', 'Berita', 'Kontak', 'Sosial Media', 'Konten Profil', 'Panduan',
        ])->pluck('id');
        $adminWeb->permissions()->sync($adminWebPerms);

        // Admin LSP Role - Manages assessment and operational features
        $adminLsp = Role::updateOrCreate(
            ['name' => 'admin-lsp'],
            [
                'display_name' => 'Admin LSP',
                'description'  => 'Manages assessment operations and data',
                'is_super_admin' => false,
            ]
        );
        $adminLspPerms = Permission::whereIn('group', [
            'Asesor', 'Asesi', 'Verifikasi Asesi', 'Akun Asesi', 'Kelompok',
            'Mitra', 'Jurusan', 'Skema', 'TUK', 'Jadwal Ujikom', 
            'Penugasan Asesor', 'Asesmen Mandiri', 'Banding Asesmen', 'Manajemen Admin', 'Role & Permission',
        ])->pluck('id');
        $adminLsp->permissions()->sync($adminLspPerms);

        $admin = Admin::first();
        if ($admin) {
            $admin->roles()->syncWithoutDetaching([$superAdmin->id]);
        }
    }
}