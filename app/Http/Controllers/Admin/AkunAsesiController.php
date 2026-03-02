<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\AkunAsesiImport;
use App\Models\Account;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
     * Show import form
     */
    public function showImport()
    {
        return view('admin.akun-asesi.import');
    }

    /**
     * Handle Excel import
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File Excel wajib diunggah.',
            'file.mimes' => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $import = new AkunAsesiImport();
        Excel::import($import, $request->file('file'));

        $msg = "Import selesai: {$import->imported} akun dibuat.";
        if ($import->skipped > 0) {
            $msg .= " {$import->skipped} NIK sudah ada (dilewati).";
        }
        if ($import->invalid > 0) {
            $msg .= " {$import->invalid} baris tidak valid.";
        }

        $type = $import->imported > 0 ? 'success' : 'error';

        return redirect()->route('admin.akun-asesi.index')
            ->with($type, $msg)
            ->with('import_errors', $import->errors);
    }

    /**
     * Download Excel template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="template_import_akun_asesi.csv"',
        ];

        $content = "NIK,Nama\n";
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
            ->with('success', 'Akun asesi berhasil dihapus.');
    }
}
