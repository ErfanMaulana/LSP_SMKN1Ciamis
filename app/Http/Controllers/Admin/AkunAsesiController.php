<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunAsesiController extends Controller
{
    /**
     * Display list of asesi accounts
     */
    public function index()
    {
        $accounts = Account::where('role', 'asesi')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.akun-asesi.index', compact('accounts'));
    }

    /**
     * Show form to create a new asesi account
     */
    public function create()
    {
        return view('admin.akun-asesi.create');
    }

    /**
     * Store a new asesi account with NIK
     */
    public function store(Request $request)
    {
        $request->validate([
            'NIK' => 'required|string|size:16|unique:accounts,NIK',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'NIK.required' => 'NIK wajib diisi.',
            'NIK.size' => 'NIK harus terdiri dari 16 digit.',
            'NIK.unique' => 'NIK sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        Account::create([
            'id' => $request->NIK,
            'NIK' => $request->NIK,
            'password' => $request->password,
            'role' => 'asesi',
        ]);

        return redirect()->route('admin.akun-asesi.index')
            ->with('success', 'Akun asesi dengan NIK ' . $request->NIK . ' berhasil dibuat!');
    }

    /**
     * Delete an asesi account
     */
    public function destroy($id)
    {
        $account = Account::findOrFail($id);

        if ($account->role !== 'asesi') {
            return back()->with('error', 'Akun ini bukan akun asesi.');
        }

        $account->delete();

        return redirect()->route('admin.akun-asesi.index')
            ->with('success', 'Akun asesi berhasil dihapus.');
    }
}
