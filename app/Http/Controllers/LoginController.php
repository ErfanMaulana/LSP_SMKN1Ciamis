<?php

namespace App\Http\Controllers;

use App\Models\Account;
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
            'role'       => 'required|in:asesi,asesor',
            'identifier' => 'required|string',
            'password'   => 'required|string',
        ]);

        $role = $request->role;

        // ── Login Asesi (via NIK) / Asesor (via No Reg) ──────────────
        if ($role === 'asesi') {
            $account = Account::where('NIK', $request->identifier)
                              ->where('role', 'asesi')
                              ->first();
        } else {
            $account = Account::where('id', $request->identifier)
                              ->where('role', 'asesor')
                              ->first();
        }

        if ($account && Hash::check($request->password, $account->password)) {
            Auth::guard('account')->login($account, $request->filled('remember'));

            if ($account->isAsesor()) {
                return redirect()->intended(route('asesor.dashboard'))->with('success', 'Login berhasil!');
            }
            return redirect()->intended(route('asesi.dashboard'))->with('success', 'Login berhasil!');
        }

        $errorMsg = $role === 'asesi' 
            ? 'NIK atau password salah.' 
            : 'Nomor registrasi atau password salah.';

        return back()->withErrors(['identifier' => $errorMsg])
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
