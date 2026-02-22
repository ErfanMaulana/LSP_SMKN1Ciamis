<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\BuktiPendukung;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function showAsesiRegistrationForm()
    {
        $jurusanList = Jurusan::all();
        return view('front.register.asesi', compact('jurusanList'));
    }

    public function registerAsesi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NIK' => 'required|string|max:255|unique:asesi,NIK',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kewarganegaraan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kode_pos' => 'required|string|max:10',
            'telepon_hp' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'pekerjaan' => 'required|string|max:255',
            'pendidikan_terakhir' => 'required|string|max:255',
            'ID_jurusan' => 'required|exists:jurusan,ID_jurusan',
            'nama_lembaga' => 'required|string|max:255',
            'alamat_lembaga' => 'required|string',
            'jabatan' => 'required|string|max:255',
            'no_fax_lembaga' => 'nullable|string|max:20',
            'telepon_rumah' => 'nullable|string|max:20',
            'email_lembaga' => 'required|email|max:255',
            'unit_lembaga' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Asesi::create($request->all());

        // Store NIK in session for step 2
        session(['asesi_nik' => $request->NIK]);

        return redirect()->route('front.register.asesi.dokumen');
    }

    public function showDokumenForm()
    {
        $nik = session('asesi_nik');

        if (!$nik || !Asesi::where('NIK', $nik)->exists()) {
            return redirect()->route('front.register.asesi')
                ->with('error', 'Silakan isi formulir data diri terlebih dahulu.');
        }

        return view('front.register.dokumen');
    }

    public function storeDokumen(Request $request)
    {
        $nik = session('asesi_nik');

        if (!$nik) {
            return redirect()->route('front.register.asesi')
                ->with('error', 'Sesi pendaftaran telah berakhir. Silakan mulai ulang.');
        }

        $asesi = Asesi::where('NIK', $nik)->first();

        if (!$asesi) {
            return redirect()->route('front.register.asesi')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        $validator = Validator::make($request->all(), [
            'pas_foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'transkrip_nilai' => 'required|array|min:1',
            'transkrip_nilai.*' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'identitas_pribadi' => 'required|array|min:1',
            'identitas_pribadi.*' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'bukti_kompetensi' => 'required|array|min:1',
            'bukti_kompetensi.*' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ], [
            'pas_foto.required' => 'Pas foto wajib diupload.',
            'transkrip_nilai.required' => 'Minimal 1 file transkrip nilai wajib diupload.',
            'identitas_pribadi.required' => 'Minimal 1 file identitas pribadi wajib diupload.',
            'bukti_kompetensi.required' => 'Minimal 1 file bukti kompetensi wajib diupload.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $folder = 'dokumen_asesi/' . $nik;

        // Upload pas foto (tetap di tabel asesi, single file)
        if ($request->hasFile('pas_foto')) {
            $pasFotoPath = $request->file('pas_foto')->store($folder, 'public');
            $asesi->pas_foto = $pasFotoPath;
            $asesi->save();
        }

        // Upload transkrip nilai (multiple) ke tabel bukti_pendukung
        if ($request->hasFile('transkrip_nilai')) {
            foreach ($request->file('transkrip_nilai') as $file) {
                $path = $file->store($folder . '/transkrip', 'public');
                BuktiPendukung::create([
                    'NIK' => $nik,
                    'jenis_dokumen' => 'transkrip_nilai',
                    'file_path' => $path,
                    'nama_file' => $file->getClientOriginalName(),
                ]);
            }
        }

        // Upload identitas pribadi (multiple) ke tabel bukti_pendukung
        if ($request->hasFile('identitas_pribadi')) {
            foreach ($request->file('identitas_pribadi') as $file) {
                $path = $file->store($folder . '/identitas', 'public');
                BuktiPendukung::create([
                    'NIK' => $nik,
                    'jenis_dokumen' => 'identitas_pribadi',
                    'file_path' => $path,
                    'nama_file' => $file->getClientOriginalName(),
                ]);
            }
        }

        // Upload bukti kompetensi (multiple) ke tabel bukti_pendukung
        if ($request->hasFile('bukti_kompetensi')) {
            foreach ($request->file('bukti_kompetensi') as $file) {
                $path = $file->store($folder . '/kompetensi', 'public');
                BuktiPendukung::create([
                    'NIK' => $nik,
                    'jenis_dokumen' => 'bukti_kompetensi',
                    'file_path' => $path,
                    'nama_file' => $file->getClientOriginalName(),
                ]);
            }
        }

        // Set status to pending (waiting for admin approval)
        $asesi->status = 'pending';
        $asesi->save();

        // Clear session
        session()->forget('asesi_nik');

        return redirect()->route('front.register.asesi.success')
            ->with('success', 'Pendaftaran berhasil! Silakan tunggu konfirmasi dari admin.');
    }

    public function registrationSuccess()
    {
        return view('front.register.success');
    }
}
