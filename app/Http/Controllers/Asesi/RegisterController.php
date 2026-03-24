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

        $jurusanList = Jurusan::with('kelasItems')->get();
        $skemaList   = Skema::orderBy('jurusan_id')->orderBy('nama_skema')->get();

        // Parse NIK to auto-fill tanggal_lahir and jenis_kelamin
        // NIK Format: PP KK CC DD MM YY SSSS
        // Digit 7-8  : Tanggal (perempuan = tanggal + 40)
        // Digit 9-10 : Bulan
        // Digit 11-12: Tahun (2 digit)
        $nikData = null;
        $nik = $account->NIK ?? '';
        if (strlen($nik) === 16 && ctype_digit($nik)) {
            $dd = (int) substr($nik, 6, 2);
            $mm = (int) substr($nik, 8, 2);
            $yy = (int) substr($nik, 10, 2);

            $isFemale = $dd > 40;
            $day      = $isFemale ? $dd - 40 : $dd;

            // 2-digit year heuristic: <= current 2-digit year → 2000s, else → 1900s
            $currentYY = (int) date('y');
            $year = ($yy <= $currentYY) ? (2000 + $yy) : (1900 + $yy);

            if ($mm >= 1 && $mm <= 12 && $day >= 1 && $day <= 31) {
                $nikData = [
                    'tanggal_lahir' => sprintf('%04d-%02d-%02d', $year, $mm, $day),
                    'jenis_kelamin' => $isFemale ? 'Perempuan' : 'Laki-laki',
                ];
            }
        }

        return view('asesi.pendaftaran.formulir', compact('account', 'asesi', 'jurusanList', 'skemaList', 'nikData'));
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
            'kelas'                 => 'nullable|string|max:50',
            'skema_id'              => 'required|exists:skemas,id',
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

        $data = $request->except('skema_id');
        $data['NIK']    = $account->NIK;
        $data['status'] = 'pending';   // reset to pending on new/resubmit
        $data['verified_at'] = null;
        $data['verified_by'] = null;
        $data['catatan_admin'] = null;

        $asesiRecord = Asesi::updateOrCreate(['NIK' => $account->NIK], $data);

        // Sync selected skema to pivot table
        $asesiRecord->skemas()->sync([$request->skema_id => ['status' => 'belum_mulai']]);

        // Store NIK in session for step 2
        session(['pendaftaran_nik' => $account->NIK]);

        ActivityLogger::logUser(
            (string) $account->NIK,
            $request->input('nama') ?: ($account->nama ?? (string) $account->NIK),
            'Mengisi APL 1',
            'User menyimpan formulir APL 1 (data diri).',
            $request,
            ['skema_id' => (int) $request->skema_id]
        );

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

        // If already has documents and is approved, go to dashboard
        if ($asesi->status === 'approved') {
            return redirect()->route('asesi.dashboard')
                ->with('info', 'Pendaftaran Anda sudah disetujui.');
        }

        // If pending (submitted step 1 but not yet approved), show step 2 still
        // or redirect back to formulir with info
        if ($asesi->status === 'pending' && $asesi->pas_foto) {
            return redirect()->route('asesi.pendaftaran.formulir')
                ->with('info', 'Dokumen sudah dikirim. Menunggu verifikasi admin.');
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

        ActivityLogger::logUser(
            (string) $account->NIK,
            $asesi->nama ?? ($account->nama ?? (string) $account->NIK),
            'Mengisi APL 1',
            'User mengirim dokumen pendukung APL 1.',
            $request
        );

        // Clear session
        session()->forget('pendaftaran_nik');

        return redirect()->route('asesi.dashboard')
            ->with('success', 'Pendaftaran berhasil! Silakan tunggu konfirmasi dari admin.');
    }
}
