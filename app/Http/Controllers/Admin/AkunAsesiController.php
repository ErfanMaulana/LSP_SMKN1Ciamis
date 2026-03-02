<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Asesi;
use Illuminate\Http\Request;

class AkunAsesiController extends Controller
{
    /**
     * Display list of asesi accounts
     */
    public function index(Request $request)
    {
        $query = Account::where('role', 'asesi');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('NIK', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($status = $request->input('status')) {
            if ($status === 'verified') {
                $query->whereIn('NIK', Asesi::whereNotNull('status')->pluck('NIK'));
            } elseif ($status === 'unverified') {
                $query->whereNotIn('NIK', Asesi::whereNotNull('status')->pluck('NIK'));
            }
        }

        $accounts = $query->orderBy('created_at', 'desc')->paginate(15);

        // Stats
        $totalAkun     = Account::where('role', 'asesi')->count();
        $verified      = Account::where('role', 'asesi')
            ->whereIn('NIK', Asesi::whereNotNull('status')->pluck('NIK'))->count();
        $unverified    = $totalAkun - $verified;

        return view('admin.akun-asesi.index', compact(
            'accounts', 'totalAkun', 'verified', 'unverified'
        ));
    }

    /**
     * Store a new asesi account
     */
    public function store(Request $request)
    {
        $request->validate([
            'NIK'  => 'required|string|size:16|unique:accounts,NIK',
            'nama' => 'required|string|max:255',
        ], [
            'NIK.required' => 'NIK wajib diisi.',
            'NIK.size'     => 'NIK harus terdiri dari 16 digit.',
            'NIK.unique'   => 'NIK sudah terdaftar.',
            'nama.required'=> 'Nama wajib diisi.',
        ]);

        Account::create([
            'id'       => $request->NIK,
            'NIK'      => $request->NIK,
            'nama'     => $request->nama,
            'password' => $request->NIK, // default password = NIK
            'role'     => 'asesi',
        ]);

        return redirect()->route('admin.akun-asesi.index')
            ->with('success', 'Akun asesi ' . $request->nama . ' (NIK: ' . $request->NIK . ') berhasil dibuat!');
    }

    /**
     * Handle CSV import (native PHP fgetcsv — no ext-zip needed)
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'file.required' => 'File CSV wajib diunggah.',
            'file.mimes'    => 'Format file harus .csv (gunakan template yang disediakan).',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $path    = $request->file('file')->getRealPath();
        $handle  = fopen($path, 'r');

        if ($handle === false) {
            return redirect()->route('admin.akun-asesi.index')
                ->with('error', 'Gagal membaca file.');
        }

        $imported = 0;
        $skipped  = 0;
        $invalid  = 0;
        $errors   = [];
        $rowNum   = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;

            // Skip empty rows
            if (count(array_filter($row, fn($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $nik  = trim((string) ($row[0] ?? ''));
            $nama = trim((string) ($row[1] ?? ''));

            // Skip header
            if (strtolower($nik) === 'nik') {
                continue;
            }

            // Validate NIK (16 digits)
            if (!preg_match('/^\d{16}$/', $nik)) {
                $invalid++;
                $errors[] = "Baris {$rowNum}: NIK \"{$nik}\" tidak valid (harus 16 digit angka).";
                continue;
            }

            if (empty($nama)) {
                $invalid++;
                $errors[] = "Baris {$rowNum}: Nama kosong untuk NIK {$nik}.";
                continue;
            }

            // Skip duplicate NIK
            if (Account::where('NIK', $nik)->exists()) {
                $skipped++;
                $errors[] = "Baris {$rowNum}: NIK {$nik} sudah terdaftar (dilewati).";
                continue;
            }

            Account::create([
                'id'       => $nik,
                'NIK'      => $nik,
                'nama'     => $nama,
                'password' => $nik,
                'role'     => 'asesi',
            ]);

            $imported++;
        }

        fclose($handle);

        $msg = "Import selesai: {$imported} akun dibuat.";
        if ($skipped > 0) $msg .= " {$skipped} NIK sudah ada (dilewati).";
        if ($invalid > 0) $msg .= " {$invalid} baris tidak valid.";

        $type = $imported > 0 ? 'success' : 'error';

        return redirect()->route('admin.akun-asesi.index')
            ->with($type, $msg)
            ->with('import_errors', $errors);
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_import_akun_asesi.csv"',
        ];

        $content  = "NIK,Nama\n";
        $content .= "3204010101010001,Budi Santoso\n";
        $content .= "3204010101010002,Siti Rahayu\n";

        return response($content, 200, $headers);
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
            ->with('success', 'Akun NIK ' . $account->NIK . ' berhasil dihapus.');
    }

    /**
     * Reset password to NIK
     */
    public function resetPassword($id)
    {
        $account = Account::findOrFail($id);
        $account->password = $account->NIK;
        $account->save();

        return redirect()->route('admin.akun-asesi.index')
            ->with('success', 'Password akun NIK ' . $account->NIK . ' berhasil direset ke NIK.');
    }
}
