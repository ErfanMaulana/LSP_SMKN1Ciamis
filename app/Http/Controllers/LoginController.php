<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Tampilkan form login terpadu
     */
    public function showLoginForm()
    {
        // Jika sudah login, redirect ke dashboard masing-masing
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::guard('account')->check()) {
            $account = Auth::guard('account')->user();
            if ($account->isAsesor()) {
                return redirect()->route('asesor.dashboard');
            }
            return redirect()->route('asesi.dashboard');
        }

        return view('login');
    }

    /**
     * Proses login berdasarkan role yang dipilih
     */
    public function login(Request $request)
    {
        $request->validate([
            'role'       => 'required|in:admin,asesi,asesor',
            'identifier' => 'required|string',
            'password'   => 'required|string',
        ]);

        $role = $request->role;

        // ── Login Admin ──────────────────────────────────────────────
        if ($role === 'admin') {
            $admin = Admin::where('username', $request->identifier)->first();

            if ($admin && Hash::check($request->password, $admin->password)) {
                Auth::guard('admin')->login($admin, $request->filled('remember'));
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Login berhasil!');
            }

            return back()->withErrors(['identifier' => 'Username atau password salah.'])
                         ->withInput($request->only('identifier', 'role'));
        }


        // ── Login Asesi / Asesor ─────────────────────────────────────
        $account = Account::where('no_reg', $request->identifier)
                          ->where('role', $role)
                          ->first();

        if ($account && Hash::check($request->password, $account->password)) {
            Auth::guard('account')->login($account, $request->filled('remember'));

            if ($account->isAsesor()) {
                return redirect()->intended(route('asesor.dashboard'))->with('success', 'Login berhasil!');
            }
            return redirect()->intended(route('asesi.dashboard'))->with('success', 'Login berhasil!');
        }

        return back()->withErrors(['identifier' => 'Nomor registrasi atau password salah.'])
                     ->withInput($request->only('identifier', 'role'));
    }

    /**
     * Logout semua guard sekaligus
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Auth::guard('account')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}
