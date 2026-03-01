<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\BuktiPendukung;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Show the registration form (Step 1 - Personal Data)
     */
    public function showForm()
    {
        $account = Auth::guard('account')->user();
        $asesi   = Asesi::where('NIK', $account->NIK)->first();

        // If asesi already registered, redirect to dashboard
        if ($asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('info', 'Anda sudah terdaftar sebagai asesi.');
        }

        $jurusanList = Jurusan::all();

        return view('asesi.pendaftaran.formulir', compact('account', 'jurusanList'));
    }

    /**
     * Store registration data (Step 1)
     */
    public function storeForm(Request $request)
    {
        $account = Auth::guard('account')->user();

        // Check if already registered
        if (Asesi::where('NIK', $account->NIK)->exists()) {
            return redirect()->route('asesi.dashboard')
                ->with('info', 'Anda sudah terdaftar sebagai asesi.');
        }

        $validator = Validator::make($request->all(), [
            'nama'                  => 'required|string|max:255',
            'tempat_lahir'          => 'required|string|max:255',
            'tanggal_lahir'         => 'required|date',
            'jenis_kelamin'         => 'required|in:Laki-laki,Perempuan',
            'kewarganegaraan'       => 'required|string|max:255',
            'alamat'                => 'required|string',
            'kode_pos'              => 'required|string|max:10',
            'telepon_hp'            => 'required|string|max:20',
            'email'                 => 'required|email|max:255',
            'pekerjaan'             => 'required|string|max:255',
            'pendidikan_terakhir'   => 'required|string|max:255',
            'ID_jurusan'            => 'required|exists:jurusan,ID_jurusan',
            'nama_lembaga'          => 'required|string|max:255',
            'alamat_lembaga'        => 'required|string',
            'jabatan'               => 'required|string|max:255',
            'no_fax_lembaga'        => 'nullable|string|max:20',
            'telepon_rumah'         => 'nullable|string|max:20',
            'email_lembaga'         => 'required|email|max:255',
            'unit_lembaga'          => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->all();
        $data['NIK'] = $account->NIK;

        Asesi::create($data);

        // Store NIK in session for step 2
        session(['pendaftaran_nik' => $account->NIK]);

        return redirect()->route('asesi.pendaftaran.dokumen');
    }

    /**
     * Show the document upload form (Step 2)
     */
    public function showDokumen()
    {
        $account = Auth::guard('account')->user();
        $nik     = session('pendaftaran_nik', $account->NIK);
        $asesi   = Asesi::where('NIK', $nik)->first();

        if (!$asesi) {
            return redirect()->route('asesi.pendaftaran.formulir')
                ->with('error', 'Silakan isi formulir data diri terlebih dahulu.');
        }

        // If already has documents and is pending/approved, go to dashboard
        if (in_array($asesi->status, ['pending', 'approved'])) {
            return redirect()->route('asesi.dashboard')
                ->with('info', 'Pendaftaran Anda sudah diproses.');
        }

        return view('asesi.pendaftaran.dokumen', compact('account', 'asesi'));
    }

    /**
     * Store uploaded documents (Step 2)
     */
    public function storeDokumen(Request $request)
    {
        $account = Auth::guard('account')->user();
        $nik     = session('pendaftaran_nik', $account->NIK);
        $asesi   = Asesi::where('NIK', $nik)->first();

        if (!$asesi) {
            return redirect()->route('asesi.pendaftaran.formulir')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'pas_foto'              => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'transkrip_nilai'       => 'required|array|min:1',
            'transkrip_nilai.*'     => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'identitas_pribadi'     => 'required|array|min:1',
            'identitas_pribadi.*'   => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'bukti_kompetensi'      => 'required|array|min:1',
            'bukti_kompetensi.*'    => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ], [
            'pas_foto.required'             => 'Pas foto wajib diupload.',
            'transkrip_nilai.required'      => 'Minimal 1 file transkrip nilai wajib diupload.',
            'identitas_pribadi.required'    => 'Minimal 1 file identitas pribadi wajib diupload.',
            'bukti_kompetensi.required'     => 'Minimal 1 file bukti kompetensi wajib diupload.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $folder = 'dokumen_asesi/' . $nik;

        // Upload pas foto
        if ($request->hasFile('pas_foto')) {
            $pasFotoPath = $request->file('pas_foto')->store($folder, 'public');
            $asesi->pas_foto = $pasFotoPath;
            $asesi->save();
        }

        // Upload transkrip nilai
        if ($request->hasFile('transkrip_nilai')) {
            foreach ($request->file('transkrip_nilai') as $file) {
                $path = $file->store($folder . '/transkrip', 'public');
                BuktiPendukung::create([
                    'NIK'            => $nik,
                    'jenis_dokumen'  => 'transkrip_nilai',
                    'file_path'      => $path,
                    'nama_file'      => $file->getClientOriginalName(),
                ]);
            }
        }

        // Upload identitas pribadi
        if ($request->hasFile('identitas_pribadi')) {
            foreach ($request->file('identitas_pribadi') as $file) {
                $path = $file->store($folder . '/identitas', 'public');
                BuktiPendukung::create([
                    'NIK'            => $nik,
                    'jenis_dokumen'  => 'identitas_pribadi',
                    'file_path'      => $path,
                    'nama_file'      => $file->getClientOriginalName(),
                ]);
            }
        }

        // Upload bukti kompetensi
        if ($request->hasFile('bukti_kompetensi')) {
            foreach ($request->file('bukti_kompetensi') as $file) {
                $path = $file->store($folder . '/kompetensi', 'public');
                BuktiPendukung::create([
                    'NIK'            => $nik,
                    'jenis_dokumen'  => 'bukti_kompetensi',
                    'file_path'      => $path,
                    'nama_file'      => $file->getClientOriginalName(),
                ]);
            }
        }

        // Set status to pending
        $asesi->status = 'pending';
        $asesi->save();

        // Clear session
        session()->forget('pendaftaran_nik');

        return redirect()->route('asesi.dashboard')
            ->with('success', 'Pendaftaran berhasil! Silakan tunggu konfirmasi dari admin.');
    }
}
