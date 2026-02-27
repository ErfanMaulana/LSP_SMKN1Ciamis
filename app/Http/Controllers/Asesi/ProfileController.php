<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil
     */
    public function edit(Request $request)
    {
        $account = Auth::guard('account')->user();
        $asesi   = Asesi::where('no_reg', $account->no_reg)->first();
        $tab     = $request->get('tab', session('tab', 'profil'));

        return view('asesi.profil-edit', compact('account', 'asesi', 'tab'));
    }

    /**
     * Simpan perubahan data profil
     */
    public function update(Request $request)
    {
        $account = Auth::guard('account')->user();
        $asesi   = Asesi::where('no_reg', $account->no_reg)->first();

        if (!$asesi) {
            return back()->with('error', 'Data asesi tidak ditemukan.');
        }

        $request->validate([
            'nama'              => 'required|string|max:255',
            'email'             => 'nullable|email|max:255',
            'telepon_hp'        => 'nullable|string|max:20',
            'telepon_rumah'     => 'nullable|string|max:20',
            'tempat_lahir'      => 'nullable|string|max:255',
            'tanggal_lahir'     => 'nullable|date',
            'jenis_kelamin'     => 'nullable|in:L,P',
            'alamat'            => 'nullable|string|max:500',
            'kode_pos'          => 'nullable|string|max:10',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'pekerjaan'         => 'nullable|string|max:255',
            'nama_lembaga'      => 'nullable|string|max:255',
            'alamat_lembaga'    => 'nullable|string|max:500',
            'jabatan'           => 'nullable|string|max:255',
        ]);

        $asesi->update($request->only([
            'nama','email','telepon_hp','telepon_rumah','tempat_lahir',
            'tanggal_lahir','jenis_kelamin','alamat','kode_pos',
            'pendidikan_terakhir','pekerjaan','nama_lembaga','alamat_lembaga','jabatan',
        ]));

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Simpan perubahan password
     */
    public function updatePassword(Request $request)
    {
        $account = Auth::guard('account')->user();

        $request->validate([
            'current_password'  => 'required|string',
            'password'          => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min'       => 'Password baru minimal 8 karakter.',
        ]);

        if (!Hash::check($request->current_password, $account->password)) {
            return back()
                ->withErrors(['current_password' => 'Password saat ini salah.'])
                ->with('tab', 'password');
        }

        $account->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil diperbarui!')->with('tab', 'password');
    }
}
