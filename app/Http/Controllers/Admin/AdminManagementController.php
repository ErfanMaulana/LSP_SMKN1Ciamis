<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Admin::with('roles');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $admins = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.admin-management.index', compact('admins'));
    }

    public function create()
    {
        $roles = Role::orderBy('display_name')->get();
        return view('admin.admin-management.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:admins,email',
            'username' => 'required|string|max:100|unique:admins,username|regex:/^[a-zA-Z0-9_]+$/',
            'password' => ['required', 'confirmed', Password::min(8)],
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,id',
        ], [
            'username.regex' => 'Username hanya boleh huruf, angka, dan underscore.',
            'roles.required' => 'Pilih minimal satu role.',
        ]);

        $admin = Admin::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $admin->roles()->sync($request->roles);

        return redirect()->route('admin.admin-management.index')
            ->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(Admin $admin)
    {
        $roles = Role::orderBy('display_name')->get();
        $adminRoleIds = $admin->roles()->pluck('roles.id')->toArray();
        return view('admin.admin-management.edit', compact('admin', 'roles', 'adminRoleIds'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'username' => 'required|string|max:100|unique:admins,username,' . $admin->id . '|regex:/^[a-zA-Z0-9_]+$/',
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,id',
        ], [
            'username.regex' => 'Username hanya boleh huruf, angka, dan underscore.',
            'roles.required' => 'Pilih minimal satu role.',
        ]);

        $admin->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'username' => $request->username,
        ]);

        if ($request->filled('password')) {
            $admin->update(['password' => Hash::make($request->password)]);
        }

        $admin->roles()->sync($request->roles);

        return redirect()->route('admin.admin-management.index')
            ->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(Admin $admin)
    {
        // Prevent deleting yourself
        if ($admin->id === Auth::guard('admin')->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $admin->roles()->detach();
        $admin->delete();

        return redirect()->route('admin.admin-management.index')
            ->with('success', 'Admin berhasil dihapus.');
    }
}
