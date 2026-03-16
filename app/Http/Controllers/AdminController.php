<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
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
            
            // Redirect ke halaman pertama yang admin punya akses berdasarkan sidebar menu
            return redirect($admin->getFirstAccessibleRoute())->with('success', 'Login berhasil!');
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
        // ── Statistik utama ────────────────────────────────────────────
        $stats = [
            'totalAsesi'   => Asesi::count(),
            'totalAsesor'  => Asesor::count(),
            'totalJurusan' => Jurusan::count(),
            'totalSkema'   => Skema::count(),
            'totalMitra'   => Mitra::count(),
        ];

        // ── Status verifikasi asesi ────────────────────────────────────
        $verifikasi = [
            'pending'  => Asesi::where('status', 'pending')->count(),
            'approved' => Asesi::where('status', 'approved')->count(),
            'rejected' => Asesi::where('status', 'rejected')->count(),
        ];

        // ── Progress asesmen mandiri (dari pivot asesi_skema) ──────────
        $asesmen = [
            'belum_mulai'         => DB::table('asesi_skema')->where('status', 'belum_mulai')->count(),
            'sedang_mengerjakan'  => DB::table('asesi_skema')->where('status', 'sedang_mengerjakan')->count(),
            'selesai'             => DB::table('asesi_skema')->where('status', 'selesai')->count(),
            'rekomendasi_lanjut'  => DB::table('asesi_skema')->where('rekomendasi', 'lanjut')->count(),
            'rekomendasi_tidak'   => DB::table('asesi_skema')->where('rekomendasi', 'tidak_lanjut')->count(),
        ];

        // ── 6 asesi terbaru ────────────────────────────────────────────
        $recentAsesi = Asesi::with('jurusan')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        // ── 5 rekomendasi terbaru ──────────────────────────────────────
        $recentRekomendasi = DB::table('asesi_skema')
            ->whereNotNull('rekomendasi')
            ->orderByDesc('reviewed_at')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $row->asesi = Asesi::where('NIK', $row->asesi_nik)->first();
                $row->skema = Skema::find($row->skema_id);
                return $row;
            });

        return view('admin.dashboard', compact(
            'stats', 'verifikasi', 'asesmen', 'recentAsesi', 'recentRekomendasi'
        ));
    }
}
