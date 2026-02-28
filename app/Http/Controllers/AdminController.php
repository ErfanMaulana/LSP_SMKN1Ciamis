<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Jurusan;
use App\Models\Skema;
use App\Models\Mitra;

class AdminController extends Controller
{
    /**
     * Tampilkan form login admin
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Proses login admin
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admin')->login($admin, $request->filled('remember'));
            
            return redirect()->intended('/admin/dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logout berhasil!');
    }

    /**
     * Tampilkan dashboard admin
     */
    public function dashboard()
    {
        // Ambil statistik dari database
        $stats = [
            'totalAsesi' => Asesi::count(),
            'totalAsesor' => Asesor::count(),
            'totalJurusan' => Jurusan::count(),
            'totalSkema' => Skema::count(),
            'totalMitra' => Mitra::count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
}
