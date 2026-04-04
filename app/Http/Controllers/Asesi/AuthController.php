<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan form login asesi
     */
    public function showLoginForm()
    {
        if (Auth::guard('account')->check()) {
            $account = Auth::guard('account')->user();

            if ($account->isAsesi()) {
                return redirect()->route('asesi.dashboard');
            }
            if ($account->isAsesor()) {
                return redirect()->route('asesor.dashboard');
            }
        }

        return view('asesi.login');
    }

    /**
     * Proses login asesi / asesor
     */
    public function login(Request $request)
    {
        $request->validate([
            'no_reg'   => 'required|string',
            'password' => 'required|string',
        ]);

        $account = Account::where('id', $request->no_reg)->first();

        if ($account && Hash::check($request->password, $account->password)) {
            Auth::guard('account')->login($account, $request->filled('remember'));

            ActivityLogger::logUser(
                (string) ($account->NIK ?? $account->id),
                $account->nama ?? (string) ($account->NIK ?? $account->id),
                'Login User',
                'User berhasil login ke sistem.',
                $request,
                ['role' => $account->role]
            );

            if ($account->isAsesi()) {
                return redirect()->intended(route('asesi.dashboard'))->with('success', 'Login berhasil!');
            }
            if ($account->isAsesor()) {
                return redirect()->intended(route('asesor.dashboard'))->with('success', 'Login berhasil!');
            }

            // Fallback
            return redirect()->route('asesi.dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'no_reg' => 'Nomor registrasi atau password salah.',
        ])->withInput($request->only('no_reg'));
    }

    /**
     * Logout asesi / asesor
     */
    public function logout(Request $request)
    {
        $account = Auth::guard('account')->user();

        if ($account) {
            ActivityLogger::logUser(
                (string) ($account->NIK ?? $account->id),
                $account->nama ?? (string) ($account->NIK ?? $account->id),
                'Logout User',
                'User logout dari sistem.',
                $request,
                ['role' => $account->role]
            );
        }

        Auth::guard('account')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }

    /**
     * Dashboard asesi
     */
    public function dashboard()
    {
        $account = Auth::guard('account')->user();

        if ($account->isAsesor()) {
            // Asesor gets their own dashboard
            $asesor = \App\Models\Asesor::with('skemas')->where('no_met', $account->id)->first();
            return redirect()->route('asesor.dashboard');
        }

        $asesi = \App\Models\Asesi::with(['jurusan', 'asesor.skemas', 'kelompok.asesors.skemas'])->where('NIK', $account->NIK)->first();

        // If asesi has no registration yet, redirect to registration form
        if (!$asesi) {
            return redirect()->route('asesi.pendaftaran.formulir');
        }

        return view('asesi.dashboard', compact('account', 'asesi'));
    }
}
