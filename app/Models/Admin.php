<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // ─── Role & Permission ───────────────────────────

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admin_role');
    }

    /**
     * Check if admin is super admin (bypass all permissions).
     */
    public function isSuperAdmin(): bool
    {
        return $this->roles()->where('is_super_admin', true)->exists();
    }

    /**
     * Check if admin has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Check if admin has a specific permission (via any of their roles).
     */
    public function hasPermission(string $permissionName): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->roles()
            ->whereHas('permissions', fn ($q) => $q->where('name', $permissionName))
            ->exists();
    }

    /**
     * Check if admin has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->roles()
            ->whereHas('permissions', fn ($q) => $q->whereIn('name', $permissions))
            ->exists();
    }

    /**
     * Get all permission names (flattened from all roles).
     */
    public function getAllPermissions(): \Illuminate\Support\Collection
    {
        if ($this->isSuperAdmin()) {
            return Permission::pluck('name');
        }

        return Permission::whereHas('roles', function ($q) {
            $q->whereIn('roles.id', $this->roles()->pluck('roles.id'));
        })->pluck('name');
    }

    /**
     * Get the first accessible menu route based on admin's permissions.
     * Follows sidebar menu structure order.
     */
    public function getFirstAccessibleRoute(): string
    {
        // Menu items in sidebar order: [route => permission_required]
        $menuItems = [
            'admin.dashboard' => 'dashboard.view',
            'admin.admin-management.index' => 'admin.view',
            'admin.roles.index' => 'role.view',
            'admin.asesi.index' => 'asesi.view',
            'admin.asesor.index' => 'asesor.view',
            'admin.jurusan.index' => 'jurusan.view',
            'admin.tuk.index' => 'tuk.view',
            'admin.skema.index' => 'skema.view',
            'admin.asesi.verifikasi' => 'verifikasi-asesi.view',
            'admin.asesmen-mandiri.index' => 'asesmen-mandiri.view',
            'admin.nilai-asesor.index' => 'asesmen-mandiri.view',
            'admin.kelompok.index' => 'kelompok.view',
            'admin.jadwal-ujikom.index' => 'jadwal-ujikom.view',
            'admin.carousel.index' => 'carousel.view',
            'admin.berita.index' => 'berita.view',
            'admin.kontak.index' => 'kontak.view',
            'admin.socialmedia.index' => 'socialmedia.view',
            'admin.profile-content.index' => 'profile-content.view',
        ];

        // Find first accessible route
        foreach ($menuItems as $route => $permission) {
            if ($this->hasPermission($permission)) {
                return route($route);
            }
        }

        // Fallback to dashboard (should not happen for valid admins)
        return route('admin.dashboard');
    }
}
