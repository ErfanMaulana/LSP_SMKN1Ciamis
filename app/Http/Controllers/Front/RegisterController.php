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

        // Filter out empty/null entries from file arrays before validation
        $sanitizeFiles = function ($key) use ($request) {
            $files = $request->file($key);
            if (!is_array($files)) {
                return ($files instanceof \Illuminate\Http\UploadedFile && $files->isValid()) ? [$files] : [];
            }
            return array_values(array_filter($files, function ($f) {
                return $f instanceof \Illuminate\Http\UploadedFile && $f->isValid();
            }));
        };

        $cleanTranskrip = $sanitizeFiles('transkrip_nilai');
        $cleanIdentitas = $sanitizeFiles('identitas_pribadi');
        $cleanKompetensi = $sanitizeFiles('bukti_kompetensi');

        $request->files->set('transkrip_nilai', $cleanTranskrip);
        $request->files->set('identitas_pribadi', $cleanIdentitas);
        $request->files->set('bukti_kompetensi', $cleanKompetensi);

        $hasPasFotoInput = $request->hasFile('pas_foto') || ($request->filled('pas_foto_base64') && str_starts_with($request->input('pas_foto_base64'), 'data:image'));

        $validator = Validator::make($request->all(), [
            'pas_foto' => $hasPasFotoInput ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120' : 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'transkrip_nilai' => 'required|array|min:1',
            'transkrip_nilai.*' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'identitas_pribadi' => 'required|array|min:1',
            'identitas_pribadi.*' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'bukti_kompetensi' => 'nullable|array',
            'bukti_kompetensi.*' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            'tanda_tangan_pendaftar' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $isBase64 = (bool) preg_match('/^data:image\/(png|jpeg|jpg);base64,[A-Za-z0-9+\/=\s]+$/', $value);
                    $isUrlOrPath = str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, 'storage/') || str_starts_with($value, '/storage/');
                    if (!$isBase64 && !$isUrlOrPath) {
                        $fail('Format tanda tangan tidak valid.');
                    }
                }
            ],
        ], [
            'pas_foto.required' => 'Pas foto wajib diupload.',
            'pas_foto.image' => 'Pas foto harus berupa file gambar.',
            'pas_foto.mimes' => 'Format pas foto harus JPG, JPEG, PNG, atau WEBP.',
            'pas_foto.max' => 'Ukuran pas foto maksimal 5MB.',
            'transkrip_nilai.required' => 'Minimal 1 file transkrip nilai wajib diupload.',
            'transkrip_nilai.*.file' => 'File transkrip nilai tidak valid.',
            'transkrip_nilai.*.mimes' => 'Format file transkrip nilai harus JPG, JPEG, PNG, WEBP, atau PDF.',
            'transkrip_nilai.*.max' => 'Ukuran file transkrip nilai maksimal 5MB per file.',
            'identitas_pribadi.required' => 'Minimal 1 file identitas pribadi wajib diupload.',
            'identitas_pribadi.*.file' => 'File identitas pribadi tidak valid.',
            'identitas_pribadi.*.mimes' => 'Format file identitas pribadi harus JPG, JPEG, PNG, WEBP, atau PDF.',
            'identitas_pribadi.*.max' => 'Ukuran file identitas pribadi maksimal 5MB per file.',
            'bukti_kompetensi.*.file' => 'File bukti kompetensi tidak valid.',
            'bukti_kompetensi.*.mimes' => 'Format file bukti kompetensi harus JPG, JPEG, PNG, WEBP, atau PDF.',
            'bukti_kompetensi.*.max' => 'Ukuran file bukti kompetensi maksimal 5MB per file.',
            'tanda_tangan_pendaftar.required' => 'Tanda tangan wajib diisi sebelum pendaftaran dikirim.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $folder = 'dokumen_asesi/' . $nik;

        // Upload pas foto (tetap di tabel asesi, single file)
        if ($request->hasFile('pas_foto')) {
            if ($asesi->pas_foto) {
                Storage::disk('public')->delete($asesi->pas_foto);
            }
            $pasFotoPath = $request->file('pas_foto')->store($folder, 'public');
            $asesi->pas_foto = $pasFotoPath;
            $asesi->save();
        } elseif ($request->filled('pas_foto_base64') && str_starts_with($request->input('pas_foto_base64'), 'data:image')) {
            if ($asesi->pas_foto) {
                Storage::disk('public')->delete($asesi->pas_foto);
            }
            $dataUri = $request->input('pas_foto_base64');
            $imageParts = explode(';base64,', $dataUri);
            $imageBase64 = end($imageParts);
            $imageContent = base64_decode($imageBase64);
            $filename = $folder . '/pas_foto_' . time() . '.jpg';
            Storage::disk('public')->put($filename, $imageContent);
            $asesi->pas_foto = $filename;
            $asesi->save();
        }

        // Upload transkrip nilai (multiple) ke tabel bukti_pendukung
        if (!empty($cleanTranskrip)) {
            foreach ($cleanTranskrip as $file) {
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
        if (!empty($cleanIdentitas)) {
            foreach ($cleanIdentitas as $file) {
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
        if (!empty($cleanKompetensi)) {
            foreach ($cleanKompetensi as $file) {
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
        $asesi->tanda_tangan_pendaftar = $request->input('tanda_tangan_pendaftar');
        $asesi->tanggal_tanda_tangan_pendaftar = now();
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
