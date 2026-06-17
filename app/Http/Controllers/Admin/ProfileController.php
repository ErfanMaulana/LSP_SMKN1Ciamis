<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $admin = Auth::guard('admin')->user()->load('roles');

        return view('admin.profile.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'username' => 'required|string|max:100|regex:/^[a-zA-Z0-9_]+$/|unique:admins,username,' . $admin->id,
            'foto_profil' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'remove_foto_profil' => 'nullable|boolean',
        ], [
            'username.regex' => 'Username hanya boleh huruf, angka, dan underscore.',
        ]);

        if ($request->boolean('remove_foto_profil') && $admin->foto_profil && Storage::disk('public')->exists($admin->foto_profil)) {
            Storage::disk('public')->delete($admin->foto_profil);
            $validated['foto_profil'] = null;
        }

        if ($request->hasFile('foto_profil')) {
            if ($admin->foto_profil && Storage::disk('public')->exists($admin->foto_profil)) {
                Storage::disk('public')->delete($admin->foto_profil);
            }

            $validated['foto_profil'] = $request->file('foto_profil')->store('admin-profile', 'public');
        }

        unset($validated['remove_foto_profil']);

        $admin->update($validated);

        return back()->with('success', 'Profil admin berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal 8 karakter.',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini salah.',
            ]);
        }

        $admin->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Simpan tanda tangan admin (base64 data URL) ke profil.
     */
    public function saveSignature(Request $request)
    {
        $request->validate([
            'tanda_tangan' => ['required', 'string'],
        ]);

        $data = $request->input('tanda_tangan');

        // Pastikan format base64 image yang valid
        if (!preg_match('/^data:image\/(png|jpeg|jpg|gif|webp);base64,/', $data)) {
            return response()->json(['success' => false, 'message' => 'Format tanda tangan tidak valid.'], 422);
        }

        $admin = Auth::guard('admin')->user();
        $admin->update(['tanda_tangan' => $data]);

        return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil disimpan.']);
    }

    /**
     * Hapus tanda tangan tersimpan dari profil admin.
     */
    public function deleteSignature(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        $admin->update(['tanda_tangan' => null]);

        return response()->json(['success' => true, 'message' => 'Tanda tangan berhasil dihapus.']);
    }
}
