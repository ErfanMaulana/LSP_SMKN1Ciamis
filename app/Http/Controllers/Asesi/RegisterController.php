<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\BuktiPendukung;
use App\Models\Jurusan;
use App\Models\Skema;
use App\Support\ActivityLogger;
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
        $asesi   = Asesi::with('skemas')->where('NIK', $account->NIK)->first();

        // If already approved, redirect to dashboard
        if ($asesi && $asesi->status === 'approved') {
            return redirect()->route('asesi.dashboard')
                ->with('info', 'Pendaftaran Anda sudah disetujui.');
        }

        // If pending verification, redirect to dashboard to avoid confusion
        if ($asesi && $asesi->status === 'pending') {
            return redirect()->route('asesi.dashboard')
                ->with('info', 'Formulir Anda sedang menunggu verifikasi admin.');
        }

        $jurusanList = Jurusan::with('kelasItems')->get();
        $skemaList   = Skema::orderBy('jurusan_id')->orderBy('nama_skema')->get();

        // Parse NIK to auto-fill tanggal_lahir and jenis_kelamin
        $nikData = null;
        $nikAutofill = false;
        $nikAutofillMessage = null;
        $nik = $account->NIK ?? '';
        if (strlen($nik) !== 16 || !ctype_digit($nik)) {
            $nikAutofillMessage = 'NIK akun tidak valid (harus 16 digit angka), isi tanggal lahir dan jenis kelamin secara manual.';
        } else {
            $dd = (int) substr($nik, 6, 2);
            $mm = (int) substr($nik, 8, 2);
            $yy = (int) substr($nik, 10, 2);

            $isFemale = $dd > 40;
            $day      = $isFemale ? $dd - 40 : $dd;

            $currentYY = (int) date('y');
            $year = ($yy <= $currentYY) ? (2000 + $yy) : (1900 + $yy);

            if ($mm >= 1 && $mm <= 12 && $day >= 1 && $day <= 31) {
                $nikData = [
                    'tanggal_lahir' => sprintf('%04d-%02d-%02d', $year, $mm, $day),
                    'jenis_kelamin' => $isFemale ? 'Perempuan' : 'Laki-laki',
                ];
                $nikAutofill = true;
            } else {
                $nikAutofillMessage = 'Tanggal/bulan pada NIK tidak valid, isi tanggal lahir dan jenis kelamin secara manual.';
            }
        }

        return view('asesi.pendaftaran.formulir', compact(
            'account',
            'asesi',
            'jurusanList',
            'skemaList',
            'nikData',
            'nikAutofill',
            'nikAutofillMessage'
        ));
    }

    /**
     * Store registration data (Step 1)
     */
    public function storeForm(Request $request)
    {
        $account = Auth::guard('account')->user();
        $existing = Asesi::where('NIK', $account->NIK)->first();

        // If already approved
        if ($existing && $existing->status === 'approved') {
            return redirect()->route('asesi.dashboard')
                ->with('info', 'Pendaftaran Anda sudah disetujui.');
        }

        // If pending, cannot resubmit
        if ($existing && $existing->status === 'pending') {
            return redirect()->route('asesi.pendaftaran.formulir')
                ->with('warning', 'Formulir Anda sedang menunggu verifikasi admin. Tidak dapat mengubah data saat ini.');
        }

        // If banned, cannot register at all
        if ($existing && $existing->status === 'banned') {
            return redirect()->route('asesi.pendaftaran.formulir')
                ->with('error', 'Akun Anda telah diblokir secara permanen dan tidak dapat mendaftar.');
        }

        // Both 'draft' and 'next' use lenient validation — Form 1 can always proceed.
        // Required-field completeness is checked in storeDokumen() at submission time.
        $isDraft = $request->input('action') === 'draft';

        $validator = Validator::make($request->all(), [
            'nama'                  => 'nullable|string|max:255',
            'tempat_lahir'          => 'nullable|string|max:255',
            'tanggal_lahir'         => 'nullable|date',
            'jenis_kelamin'         => 'nullable|in:Laki-laki,Perempuan',
            'kewarganegaraan'       => 'nullable|string|max:255',
            'alamat'                => 'nullable|string',
            'kode_pos'              => 'nullable|string|max:10',
            'telepon_hp'            => 'nullable|string|max:20',
            'email'                 => 'nullable|email|max:255',
            'pekerjaan'             => 'nullable|string|max:255',
            'pendidikan_terakhir'   => 'nullable|string|max:255',
            'ID_jurusan'            => 'nullable|exists:jurusan,ID_jurusan',
            'kelas'                 => 'nullable|string|max:50',
            'skema_id'              => 'nullable|exists:skemas,id',
            'nama_lembaga'          => 'nullable|string|max:255',
            'alamat_lembaga'        => 'nullable|string',
            'jabatan'               => 'nullable|string|max:255',
            'no_fax_lembaga'        => 'nullable|string|max:20',
            'telepon_rumah'         => 'nullable|string|max:20',
            'email_lembaga'         => 'nullable|email|max:255',
            'unit_lembaga'          => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // For draft: include all fields (even null = cleared) so erased values are saved.
        // For 'next': validation already passed so nulls won't appear on required fields.
        $data = $request->except('skema_id', 'action');
        $data['NIK']    = $account->NIK;
        $data['status'] = 'draft';
        $data['verified_at'] = null;
        $data['verified_by'] = null;
        $data['catatan_admin'] = null;

        $asesiRecord = Asesi::updateOrCreate(['NIK' => $account->NIK], $data);

        if ($request->filled('skema_id')) {
            $asesiRecord->skemas()->sync([$request->skema_id => ['status' => 'belum_mulai']]);
        }

        session([
            'pendaftaran_nik' => $account->NIK,
            'pendaftaran_step1_completed' => true,
        ]);

        ActivityLogger::logUser(
            (string) $account->NIK,
            $request->input('nama') ?: ($account->nama ?? (string) $account->NIK),
            'Mengisi APL 1',
            $isDraft ? 'User menyimpan draft formulir APL 1.' : 'User menyimpan formulir APL 1 (data diri).',
            $request,
            ['skema_id' => (int) $request->skema_id]
        );

        if ($isDraft) {
            return redirect()->route('asesi.pendaftaran.formulir')
                ->with('success', 'Draft formulir berhasil disimpan.');
        }

        return redirect()->route('asesi.pendaftaran.dokumen');
    }

    /**
     * Show the document upload form (Step 2)
     */
    public function showDokumen()
    {
        $account = Auth::guard('account')->user();
        $nik     = session('pendaftaran_nik', $account->NIK);
        $asesi   = Asesi::with('buktiPendukung')->where('NIK', $nik)->first();

        if (!$asesi) {
            return redirect()->route('asesi.pendaftaran.formulir')
                ->with('error', 'Silakan isi formulir data diri terlebih dahulu.');
        }

        // If already approved
        if ($asesi->status === 'approved') {
            return redirect()->route('asesi.dashboard')
                ->with('info', 'Pendaftaran Anda sudah disetujui.');
        }

        // If pending (submitted Step 2), redirect to dashboard
        if ($asesi->status === 'pending') {
            return redirect()->route('asesi.dashboard')
                ->with('info', 'Formulir Anda sedang menunggu verifikasi admin.');
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
        $asesi   = Asesi::with('buktiPendukung')->where('NIK', $nik)->first();

        if (!$asesi) {
            return redirect()->route('asesi.pendaftaran.formulir')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        $isDraft = $request->input('action') === 'draft';

        // On final submit, validate that Form 1 required fields are all filled in the DB
        if (!$isDraft) {
            $step1Errors = $this->validateStep1Completeness($asesi);
            if (!empty($step1Errors)) {
                return redirect()->route('asesi.pendaftaran.formulir')
                    ->withErrors($step1Errors)
                    ->with('step1_incomplete', true);
            }
        }

        $hasPasFoto = !empty($asesi->pas_foto);
        $hasTranskrip = $asesi->buktiPendukung->where('jenis_dokumen', 'transkrip_nilai')->count() > 0;
        $hasIdentitas = $asesi->buktiPendukung->where('jenis_dokumen', 'identitas_pribadi')->count() > 0;

        if ($isDraft) {
            $rules = [
                'pas_foto'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'transkrip_nilai'       => 'nullable|array',
                'transkrip_nilai.*'     => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
                'identitas_pribadi'     => 'nullable|array',
                'identitas_pribadi.*'   => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
                'bukti_kompetensi'      => 'nullable|array',
                'bukti_kompetensi.*'    => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
                'tanda_tangan_pendaftar' => 'nullable|string',
            ];
        } else {
            // Submit to admin: Pas Foto, Transkrip, and Identitas are required (unless draft files exist), Bukti Kompetensi is optional.
            $rules = [
                'pas_foto'              => $hasPasFoto ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048' : 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
                'transkrip_nilai'       => $hasTranskrip ? 'nullable|array' : 'required|array|min:1',
                'transkrip_nilai.*'     => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
                'identitas_pribadi'     => $hasIdentitas ? 'nullable|array' : 'required|array|min:1',
                'identitas_pribadi.*'   => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
                'bukti_kompetensi'      => 'nullable|array',
                'bukti_kompetensi.*'    => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
                'tanda_tangan_pendaftar' => ['required', 'string', 'regex:/^data:image\/png;base64,[A-Za-z0-9+\/=]+$/'],
            ];
        }

        $validator = Validator::make($request->all(), $rules, [
            'pas_foto.required'             => 'Pas foto wajib diupload.',
            'pas_foto.image'                => 'Pas foto harus berupa file gambar.',
            'pas_foto.mimes'                => 'Format pas foto harus JPG, JPEG, PNG, atau WEBP.',
            'pas_foto.max'                  => 'Ukuran pas foto maksimal 2MB.',
            'transkrip_nilai.required'      => 'Minimal 1 file transkrip nilai wajib diupload.',
            'transkrip_nilai.*.file'        => 'File transkrip nilai tidak valid.',
            'transkrip_nilai.*.mimes'       => 'Format file transkrip nilai harus JPG, JPEG, PNG, WEBP, atau PDF.',
            'transkrip_nilai.*.max'         => 'Ukuran file transkrip nilai maksimal 2MB per file.',
            'identitas_pribadi.required'    => 'Minimal 1 file identitas pribadi wajib diupload.',
            'identitas_pribadi.*.file'      => 'File identitas pribadi tidak valid.',
            'identitas_pribadi.*.mimes'     => 'Format file identitas pribadi harus JPG, JPEG, PNG, WEBP, atau PDF.',
            'identitas_pribadi.*.max'       => 'Ukuran file identitas pribadi maksimal 2MB per file.',
            'bukti_kompetensi.*.file'       => 'File bukti kompetensi tidak valid.',
            'bukti_kompetensi.*.mimes'      => 'Format file bukti kompetensi harus JPG, JPEG, PNG, WEBP, atau PDF.',
            'bukti_kompetensi.*.max'        => 'Ukuran file bukti kompetensi maksimal 2MB per file.',
            'tanda_tangan_pendaftar.required' => 'Tanda tangan wajib diisi sebelum pendaftaran dikirim.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $folder = 'dokumen_asesi/' . $nik;

        // Upload pas foto
        if ($request->hasFile('pas_foto')) {
            if ($asesi->pas_foto) {
                Storage::disk('public')->delete($asesi->pas_foto);
            }
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

        if ($isDraft) {
            if ($request->filled('tanda_tangan_pendaftar')) {
                $asesi->tanda_tangan_pendaftar = $request->input('tanda_tangan_pendaftar');
                $asesi->tanggal_tanda_tangan_pendaftar = now();
            }
            $asesi->status = 'draft';
            $asesi->save();

            ActivityLogger::logUser(
                (string) $account->NIK,
                $asesi->nama ?? ($account->nama ?? (string) $account->NIK),
                'Mengisi APL 1',
                'User menyimpan draft dokumen pendukung APL 1.',
                $request
            );

            return redirect()->route('asesi.pendaftaran.dokumen')
                ->with('success', 'Draft dokumen berhasil disimpan.');
        }

        $asesi->status = 'pending';
        $asesi->tanda_tangan_pendaftar = $request->input('tanda_tangan_pendaftar');
        $asesi->tanggal_tanda_tangan_pendaftar = now();
        $asesi->save();

        ActivityLogger::logUser(
            (string) $account->NIK,
            $asesi->nama ?? ($account->nama ?? (string) $account->NIK),
            'Mengisi APL 1',
            'User mengirim dokumen pendukung APL 1.',
            $request
        );

        // Clear flow markers
        session()->forget(['pendaftaran_nik', 'pendaftaran_step1_completed']);

        return redirect()->route('asesi.dashboard')
            ->with('success', 'Pendaftaran berhasil! Silakan tunggu konfirmasi dari admin.');
    }

    /**
     * Delete a draft supporting document file
     */
    public function deleteDokumen($id)
    {
        $account = Auth::guard('account')->user();
        $dokumen = BuktiPendukung::where('id', $id)->where('NIK', $account->NIK)->firstOrFail();

        if ($dokumen->file_path) {
            Storage::disk('public')->delete($dokumen->file_path);
        }

        $dokumen->delete();

        return redirect()->back()->with('success', 'File dokumen berhasil dihapus.');
    }

    /**
     * Validate that all required Form 1 fields are filled in the Asesi record.
     * Returns an array of validation errors keyed by field name, or empty array if complete.
     */
    protected function validateStep1Completeness(Asesi $asesi): array
    {
        $errors = [];

        $required = [
            'nama'                => 'Nama Lengkap',
            'tempat_lahir'        => 'Tempat Lahir',
            'tanggal_lahir'       => 'Tanggal Lahir',
            'jenis_kelamin'       => 'Jenis Kelamin',
            'kewarganegaraan'     => 'Kewarganegaraan',
            'alamat'              => 'Alamat Lengkap',
            'kode_pos'            => 'Kode POS',
            'telepon_hp'          => 'Telepon / HP',
            'email'               => 'Email',
            'pekerjaan'           => 'Pekerjaan',
            'pendidikan_terakhir' => 'Pendidikan Terakhir',
            'ID_jurusan'          => 'Jurusan',
            'nama_lembaga'        => 'Nama Lembaga / Perusahaan',
            'alamat_lembaga'      => 'Alamat Lembaga',
            'jabatan'             => 'Jabatan',
            'email_lembaga'       => 'Email Lembaga',
        ];

        foreach ($required as $field => $label) {
            if (empty($asesi->$field)) {
                $errors[$field] = "Field \"{$label}\" pada Formulir Data Diri wajib diisi.";
            }
        }

        // Check skema selection via pivot
        if ($asesi->skemas()->count() === 0) {
            $errors['skema_id'] = 'Skema Sertifikasi pada Formulir Data Diri wajib dipilih.';
        }

        return $errors;
    }
}
