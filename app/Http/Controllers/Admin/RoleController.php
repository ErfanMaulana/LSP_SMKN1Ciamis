<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::withCount(['admins', 'permissions']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('display_name', 'like', "%{$search}%");
            });
        }

        $roles = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:50|unique:roles,name|regex:/^[a-z0-9\-]+$/',
            'display_name' => 'required|string|max:100',
            'description'  => 'nullable|string|max:255',
            'permissions'  => 'array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'name.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung.',
        ]);

        $role = Role::create([
            'name'           => $request->name,
            'display_name'   => $request->display_name,
            'description'    => $request->description,
            'is_super_admin' => false,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil ditambahkan.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('group')->orderBy('display_name')->get()->groupBy('group');
        $rolePermissionIds = $role->permissions()->pluck('permissions.id')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissionIds'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name'         => 'required|string|max:50|unique:roles,name,' . $role->id . '|regex:/^[a-z0-9\-]+$/',
            'display_name' => 'required|string|max:100',
            'description'  => 'nullable|string|max:255',
            'permissions'  => 'array',
            'permissions.*' => 'exists:permissions,id',
        ], [
            'name.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda hubung.',
        ]);

        $role->update([
            'name'         => $request->name,
            'display_name' => $request->display_name,
            'description'  => $request->description,
        ]);

        if (!$role->is_super_admin) {
            $role->permissions()->sync($request->permissions ?? []);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if ($role->is_super_admin) {
            return back()->with('error', 'Role Super Admin tidak bisa dihapus.');
        }

        if ($role->admins()->count() > 0) {
            return back()->with('error', 'Role masih digunakan oleh ' . $role->admins()->count() . ' admin.');
        }

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role berhasil dihapus.');
    }
}
