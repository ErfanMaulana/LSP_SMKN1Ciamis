<?php

namespace App\Http\Controllers;

use App\Imports\AsesiActivatedImport;
use App\Models\Asesi;
use App\Models\Account;
use App\Models\Jurusan;
use App\Models\Skema;
use App\Models\Asesor;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html as WordHtml;
use PhpOffice\PhpWord\IOFactory as WordIO;

class AsesiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asesi::with('jurusan', 'skemas');
        
        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('NIK', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('telepon_hp', 'LIKE', "%{$search}%");
            });
        }
        
        // Jurusan filter
        if ($request->has('jurusan') && $request->jurusan != '') {
            $query->where('ID_jurusan', $request->jurusan);
        }
        
        // Skema filter
        if ($request->has('skema') && $request->skema != '') {
            $query->whereHas('skemas', function($q) use ($request) {
                $q->where('skemas.id', $request->skema);
            });
        }
        
        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Card quick-filter
        $cardFilter = $request->get('card_filter', '');
        if ($cardFilter === 'this_year') {
            $query->whereYear('created_at', date('Y'));
        } elseif ($cardFilter === 'in_assessment') {
            $query->where('status', 'approved');
        } elseif ($cardFilter === 'certified') {
            $query->whereHas('skemas', function($q) {
                $q->where('asesi_skema.status', 'selesai')
                  ->where('asesi_skema.rekomendasi', 'lanjut');
            });
        }
        
        // Sort filter (A-Z or Z-A)
        $sortOrder = 'asc'; // default
        if ($request->has('sort') && $request->sort != '') {
            $sortOrder = $request->sort;
        }
        $query->orderBy('nama', $sortOrder);
        
        $perPage = (int) $request->query('per_page', 10);
        $perPage = max(5, min(100, $perPage));

        $asesi = $query->paginate($perPage)->appends($request->except('page'));
        
        // Statistik dinamis
        $totalAsesi = Asesi::count();
        
        // Asesi yang terdaftar tahun ini
        $registeredThisYear = Asesi::whereYear('created_at', date('Y'))->count();
        
        // Asesi dalam penilaian (status approved)
        $inAssessment = Asesi::where('status', 'approved')->count();
        
        // Asesi yang sudah tersertifikasi (memiliki skema dengan status selesai)
        $certified = Asesi::whereHas('skemas', function($query) {
            $query->where('asesi_skema.status', 'selesai')
                  ->where('asesi_skema.rekomendasi', 'lanjut');
        })->count();
        
        // Get all jurusan for filter dropdown
        $jurusanList = Jurusan::all();
        $skemaList = Skema::orderBy('nama_skema')->get();
        
        // If AJAX request, return only table rows
        if ($request->ajax()) {
            return view('admin.asesi.partials.table-rows', compact('asesi'))->render();
        }

        $perPageAkun = (int) $request->query('per_page_akun', 10);
        $perPageAkun = max(5, min(100, $perPageAkun));

        // Akun role=asesi yang tidak punya data di tabel asesi
        $akunTanpaAsesi = Account::where('role', 'asesi')
            ->whereNotIn('NIK', Asesi::pluck('NIK')->filter())
            ->orderBy('nama')
            ->paginate($perPageAkun, ['*'], 'page_akun')
            ->appends($request->except('page_akun'));
        
        return view('admin.asesi.index', compact(
            'asesi', 
            'totalAsesi',
            'registeredThisYear',
            'inAssessment',
            'certified',
            'jurusanList',
            'skemaList',
            'akunTanpaAsesi',
            'cardFilter'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $jurusan = Jurusan::with('kelasItems')->orderBy('nama_jurusan')->get();
        $skemaList = Skema::orderBy('jurusan_id')->orderBy('nama_skema')->get();

        $prefillNIK = preg_replace('/\D+/', '', (string) $request->query('nik', ''));
        $prefillNIK = substr($prefillNIK, 0, 16);
        $prefillNama = trim((string) $request->query('nama', ''));
        $nikData = $this->parseNikData($prefillNIK);

        return view('admin.asesi.create', compact('jurusan', 'skemaList', 'prefillNIK', 'prefillNama', 'nikData'));
    }

    /**
     * Parse NIK to derive birth date and gender.
     * Format: PPKKCCDDMMYYSSSS, female day = DD + 40.
     */
    private function parseNikData(?string $nik): ?array
    {
        if (!$nik || strlen($nik) !== 16 || !ctype_digit($nik)) {
            return null;
        }

        $ddRaw = (int) substr($nik, 6, 2);
        $mm = (int) substr($nik, 8, 2);
        $yy = (int) substr($nik, 10, 2);

        $isFemale = $ddRaw > 40;
        $dd = $isFemale ? ($ddRaw - 40) : $ddRaw;

        if ($dd < 1 || $dd > 31 || $mm < 1 || $mm > 12) {
            return null;
        }

        $currentYY = (int) date('y');
        $year = $yy <= $currentYY ? (2000 + $yy) : (1900 + $yy);

        $tanggal = sprintf('%04d-%02d-%02d', $year, $mm, $dd);
        return [
            'tanggal_lahir' => $tanggal,
            'jenis_kelamin' => $isFemale ? 'Perempuan' : 'Laki-laki',
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'NIK' => 'required|digits:16|regex:/^[0-9]{16}$/|unique:asesi,NIK',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P,Laki-laki,Perempuan',
            'kebangsaan' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kode_pos' => 'required|string|max:10',
            'telepon_hp' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'pekerjaan' => 'required|string|max:255',
            'pendidikan_terakhir' => 'required|string|max:100',
            'ID_jurusan' => 'required|exists:jurusan,ID_jurusan',
            'skema_id' => 'required|exists:skemas,id',
            'nama_lembaga' => 'required|string|max:255',
            'alamat_lembaga' => 'required|string',
            'jabatan' => 'required|string|max:255',
            'no_fax_lembaga' => 'nullable|string|max:50',
            'telepon_rumah' => 'nullable|string|max:20',
            'email_lembaga' => 'required|email|max:255',
            'unit_lembaga' => 'nullable|string|max:255',
            'kelas' => 'nullable|string|max:50',
            'kode_kota' => 'nullable|string|max:50',
            'kode_provinsi' => 'nullable|string|max:50',
            'kode_kementrian' => 'nullable|string|max:50',
            'kode_anggaran' => 'nullable|string|max:50',
            'tanda_tangan_pendaftar' => ['required', 'string', 'regex:/^data:image\/png;base64,[A-Za-z0-9+\/=]+$/'],
        ]);

        $skemaId = $validated['skema_id'];
        unset($validated['skema_id']);

        // Normalize gender to DB enum values.
        if (($validated['jenis_kelamin'] ?? null) === 'L') {
            $validated['jenis_kelamin'] = 'Laki-laki';
        } elseif (($validated['jenis_kelamin'] ?? null) === 'P') {
            $validated['jenis_kelamin'] = 'Perempuan';
        }

        $validated['tanggal_tanda_tangan_pendaftar'] = now();

        $asesi = Asesi::create($validated);

        // Sync selected skema similar to pendaftaran flow
        $asesi->skemas()->sync([$skemaId => ['status' => 'belum_mulai']]);

        // Auto-create account with NIK as login ID and default password
        if (!Account::where('NIK', $validated['NIK'])->exists()) {
            Account::create([
                'id'       => $validated['NIK'],
                'NIK'      => $validated['NIK'],
                'nama'     => $validated['nama'],
                'password' => Hash::make($validated['NIK']),
                'role'     => 'asesi',
            ]);
        }

        return redirect()->route('admin.asesi.index')->with('success', 'Data Asesi berhasil ditambahkan! Akun login (NIK: ' . $validated['NIK'] . ', password awal: NIK) otomatis dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show($nik)
    {
        $asesi = Asesi::with(['jurusan', 'buktiPendukung', 'verifiedBy', 'skemas.buktiPersyaratanDasarPemohon'])
            ->findOrFail($nik);
        
        $asesiSkemas = \DB::table('asesi_skema')
            ->join('skemas', 'asesi_skema.skema_id', '=', 'skemas.id')
            ->where('asesi_skema.asesi_nik', $asesi->NIK)
            ->whereRaw('asesi_skema.attempt = (SELECT MAX(b.attempt) FROM asesi_skema b WHERE b.asesi_nik = asesi_skema.asesi_nik AND b.skema_id = asesi_skema.skema_id)')
            ->select('skemas.*', 'asesi_skema.status as status_mandiri', 'asesi_skema.tanggal_selesai as tgl_selesai_mandiri', 'asesi_skema.attempt')
            ->get();

        $hasilUjikom = [];

        foreach ($asesiSkemas as $skema) {
            // 1. Pendaftaran & Verifikasi
            $isStep1Completed = ($asesi->status === 'approved');
            $stepPendaftaran = [
                'name' => 'Pendaftaran & Verifikasi',
                'status' => $isStep1Completed ? 'completed' : 'pending',
                'label' => $isStep1Completed ? 'Terverifikasi' : 'Menunggu Verifikasi',
                'description' => $isStep1Completed
                    ? 'Berkas pendaftaran telah diverifikasi oleh admin.'
                    : 'Berkas pendaftaran sedang dalam proses verifikasi oleh admin.'
            ];

            // 2. Asesmen Mandiri
            $isMandiriSelesai = ($skema->status_mandiri === 'selesai');
            $isStep2Completed = $isMandiriSelesai;
            $stepMandiri = [
                'name' => 'Asesmen Mandiri (FR.APL.02)',
                'status' => $isStep2Completed ? 'completed' : 'pending',
                'label' => $isMandiriSelesai ? 'Selesai' : 'Belum Selesai',
                'description' => $isMandiriSelesai
                    ? 'Asesi telah menyelesaikan pengisian form asesmen mandiri.'
                    : 'Asesi belum mengisi form asesmen mandiri.'
            ];

            // 3. Jadwal Ujikom
            $jadwal = \DB::table('jadwal_peserta')
                ->join('jadwal_ujikom', 'jadwal_ujikom.id', '=', 'jadwal_peserta.jadwal_id')
                ->where('jadwal_peserta.asesi_nik', $asesi->NIK)
                ->where('jadwal_ujikom.skema_id', $skema->id)
                ->where('jadwal_peserta.attempt', $skema->attempt)
                ->select('jadwal_ujikom.*')
                ->first();

            $isJadwalSelesai = (bool)$jadwal;
            $isStep3Completed = $isJadwalSelesai && $isStep2Completed;
            
            $jadwalDesc = 'Menunggu penjadwalan uji kompetensi dari admin/asesor.';
            if ($isJadwalSelesai) {
                $tukName = \DB::table('tuk')->where('id', $jadwal->tuk_id)->value('nama_tuk') ?? 'TUK';
                $tglMulai = $jadwal->tanggal_mulai ? \Carbon\Carbon::parse($jadwal->tanggal_mulai)->locale('id')->isoFormat('D MMMM YYYY') : '-';
                $jadwalDesc = 'Jadwal: ' . $tglMulai . ' di ' . $tukName;
            }

            $stepJadwal = [
                'name' => 'Jadwal Uji Kompetensi',
                'status' => $isStep3Completed ? 'completed' : 'pending',
                'label' => $isJadwalSelesai ? 'Sudah Dijadwalkan' : 'Belum Dijadwalkan',
                'description' => $jadwalDesc
            ];

            // 4. Persetujuan Asesmen
            $useNik = \Illuminate\Support\Facades\Schema::hasColumn('persetujuan_asesmen', 'asesi_nik');
            $persetujuan = \DB::table('persetujuan_asesmen')
                ->where('nomor_skema', $skema->nomor_skema)
                ->where('attempt', $skema->attempt)
                ->where(function($q) use ($asesi, $useNik) {
                    $q->where('nama_asesi', $asesi->nama);
                    if ($useNik) {
                        $q->orWhere('asesi_nik', $asesi->NIK);
                    }
                })
                ->first();

            $isPersetujuanSelesai = $persetujuan && !empty($persetujuan->ttd_asesi_nama) && !empty($persetujuan->ttd_asesor_nama);
            $isStep4Completed = $isPersetujuanSelesai && $isStep3Completed;
            
            $stepPersetujuan = [
                'name' => 'Persetujuan Asesmen (FR.APL.03)',
                'status' => $isStep4Completed ? 'completed' : 'pending',
                'label' => $isPersetujuanSelesai ? 'Selesai & Ditandatangani' : 'Belum Ditandatangani',
                'description' => $isPersetujuanSelesai
                    ? 'Persetujuan asesmen telah disepakati dan ditandatangani oleh Asesi dan Asesor.'
                    : 'Dokumen persetujuan asesmen belum selesai ditandatangani.'
            ];

            // 5. Penilaian / Ceklis Observasi
            $ceklis = \DB::table('ceklis_observasi_aktivitas_praktiks')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skema->id)
                ->where('attempt', $skema->attempt)
                ->first();

            $isPenilaianSelesai = $ceklis && !empty($ceklis->ttd_asesi_file) && !empty($ceklis->ttd_asesor_file);
            $isStep5Completed = $isPenilaianSelesai && $isStep4Completed;
            
            $penilaianLabel = 'Belum Dinilai';
            $penilaianDesc = 'Menunggu penilaian observasi praktik dari Asesor.';
            if ($ceklis) {
                if ($isPenilaianSelesai) {
                    $penilaianLabel = 'Selesai & Ditandatangani';
                    $penilaianDesc = 'Proses observasi praktik telah dinilai dan disetujui kedua belah pihak.';
                } elseif (!empty($ceklis->ttd_asesor_file)) {
                    $penilaianLabel = 'Menunggu Tanda Tangan Asesi';
                    $penilaianDesc = 'Asesor telah menilai. Menunggu tanda tangan Asesi.';
                }
            }

            $stepPenilaian = [
                'name' => 'Penilaian & Ceklis Observasi (FR.IA.01)',
                'status' => $isStep5Completed ? 'completed' : 'pending',
                'label' => $penilaianLabel,
                'description' => $penilaianDesc
            ];

            // 6. Rekaman Asesmen
            $rekaman = \DB::table('rekaman_asesmen_kompetensi')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skema->id)
                ->where('attempt', $skema->attempt)
                ->first();

            $isRekamanSelesai = $rekaman && !empty($rekaman->ttd_asesi_file) && !empty($rekaman->ttd_asesor_file);
            $isStep6Completed = $isRekamanSelesai && $isStep5Completed;

            $rekamanLabel = 'Belum Diisi';
            $rekamanDesc = 'Menunggu pengisian rekaman asesmen dari Asesor.';
            if ($rekaman) {
                if ($isRekamanSelesai) {
                    $rekamanLabel = 'Selesai & Ditandatangani';
                    $rekamanDesc = 'Rekaman asesmen kompetensi telah ditandatangani oleh kedua belah pihak.';
                } elseif (!empty($rekaman->ttd_asesor_file)) {
                    $rekamanLabel = 'Menunggu Tanda Tangan Asesi';
                    $rekamanDesc = 'Asesor telah mengisi rekaman asesmen. Menunggu tanda tangan Asesi.';
                }
            }

            $stepRekaman = [
                'name' => 'Rekaman Asesmen (FR.AK.02)',
                'status' => $isStep6Completed ? 'completed' : 'pending',
                'label' => $rekamanLabel,
                'description' => $rekamanDesc
            ];

            // 7. Nilai Asesor
            $nilaiExists = \DB::table('asesor_nilai_elemens')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skema->id)
                ->exists();
            $isStep7Completed = $nilaiExists && $isStep6Completed;
            
            $stepNilai = [
                'name' => 'Nilai Asesor',
                'status' => $isStep7Completed ? 'completed' : 'pending',
                'label' => $nilaiExists ? 'Selesai' : 'Belum Dinilai',
                'description' => $nilaiExists
                    ? 'Asesor telah menginput nilai kompetensi.'
                    : 'Asesor belum menginput nilai kompetensi.'
            ];

            $allCompleted = $isStep7Completed;

            $hasilUjikom[] = (object)[
                'skema_id' => $skema->id,
                'nama_skema' => $skema->nama_skema,
                'nomor_skema' => $skema->nomor_skema,
                'steps' => [$stepPendaftaran, $stepMandiri, $stepJadwal, $stepPersetujuan, $stepPenilaian, $stepRekaman, $stepNilai],
                'all_completed' => $allCompleted,
                'ceklis' => $ceklis,
                'rekaman' => $rekaman,
                'jadwal' => $jadwal,
                'persetujuan' => $persetujuan,
                'is_jadwal_selesai' => $isJadwalSelesai,
                'is_persetujuan_selesai' => $isPersetujuanSelesai,
                'is_nilai_selesai' => $nilaiExists,
                'rekomendasi' => $ceklis ? $ceklis->rekomendasi : null,
                'tanggal_ceklis' => $ceklis && $ceklis->ttd_asesi_tanggal ? \Carbon\Carbon::parse($ceklis->ttd_asesi_tanggal)->locale('id')->isoFormat('D MMMM YYYY') : null,
                'asesor_nama' => $ceklis && $ceklis->ttd_asesor_nama ? $ceklis->ttd_asesor_nama : null,
            ];
        }

        $hasilUjikom = collect($hasilUjikom);
        
        return view('admin.asesi.show', compact('asesi', 'hasilUjikom'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($nik)
    {
        $asesi    = Asesi::with(['skemas'])->findOrFail($nik);
        $jurusan  = Jurusan::with('kelasItems')->orderBy('nama_jurusan')->get();
        $skemas   = Skema::with('jurusan')->orderBy('nama_skema')->get();
        return view('admin.asesi.edit', compact('asesi', 'jurusan', 'skemas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $nik)
    {
        $asesi = Asesi::findOrFail($nik);

        $validated = $request->validate([
            'NIK'                 => 'required|digits:16|regex:/^[0-9]{16}$/|unique:asesi,NIK,' . $nik . ',NIK',
            'no_reg'              => 'nullable|string|max:50',
            'nama'                => 'required|string|max:255',
            'email'               => 'nullable|email|max:255',
            'ID_jurusan'          => 'required|exists:jurusan,ID_jurusan',
            'kelompok_id'         => 'nullable|exists:kelompok,id',
            'kelas'               => 'nullable|string|max:50',
            'jenis_kelamin'       => 'nullable|in:L,P,Laki-laki,Perempuan',
            'tempat_lahir'        => 'nullable|string|max:255',
            'tanggal_lahir'       => 'nullable|date',
            'alamat'              => 'nullable|string',
            'kebangsaan'          => 'nullable|string|max:100',
            'kewarganegaraan'     => 'nullable|string|max:100',
            'kode_kota'           => 'nullable|string|max:50',
            'kode_provinsi'       => 'nullable|string|max:50',
            'telepon_rumah'       => 'nullable|string|max:20',
            'telepon_hp'          => 'nullable|string|max:20',
            'kode_pos'            => 'nullable|string|max:10',
            'pendidikan_terakhir' => 'nullable|string|max:100',
            'pekerjaan'           => 'nullable|string|max:255',
            'jabatan'             => 'nullable|string|max:255',
            'nama_lembaga'        => 'nullable|string|max:255',
            'alamat_lembaga'      => 'nullable|string',
            'no_fax_lembaga'      => 'nullable|string|max:50',
            'email_lembaga'       => 'nullable|email|max:255',
            'unit_lembaga'        => 'nullable|string|max:255',
            'kode_kementrian'     => 'nullable|string|max:50',
            'kode_anggaran'       => 'nullable|string|max:50',
            'skema_ids'           => 'nullable|array',
            'skema_ids.*'         => 'exists:skemas,id',
        ]);

        // Remove skema_ids before updating asesi model fields
        $skemaIds = $request->input('skema_ids', []);
        unset($validated['skema_ids']);

        // Normalize gender to DB enum values.
        if (($validated['jenis_kelamin'] ?? null) === 'L') {
            $validated['jenis_kelamin'] = 'Laki-laki';
        } elseif (($validated['jenis_kelamin'] ?? null) === 'P') {
            $validated['jenis_kelamin'] = 'Perempuan';
        }

        $asesi->update($validated);

        // Sync skemas: preserve pivot data for existing ones, only attach/detach diff
        $currentSkemaIds = $asesi->skemas()->pluck('skemas.id')->toArray();
        $toDetach = array_diff($currentSkemaIds, $skemaIds);
        $toAttach = array_diff($skemaIds, $currentSkemaIds);

        if (!empty($toDetach)) {
            $asesi->skemas()->detach($toDetach);
        }
        foreach ($toAttach as $skemaId) {
            $asesi->skemas()->attach($skemaId, ['status' => 'belum_mulai']);
        }

        return redirect()->route('admin.asesi.index')->with('success', 'Data Asesi berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($nik)
    {
        $asesi = Asesi::findOrFail($nik);
        $asesi->delete();

        return redirect()->route('admin.asesi.index')->with('success', 'Data Asesi berhasil dihapus!');
    }

    /**
     * Import data asesi untuk akun yang sudah aktivasi.
     */
    public function importActivated(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
        ], [
            'file.required' => 'File wajib diunggah.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $uploadedFile = $request->file('file');
        $extension = strtolower($uploadedFile->getClientOriginalExtension());

        if (!in_array($extension, ['csv', 'xlsx', 'txt'])) {
            return redirect()->route('admin.asesi.index')
                ->with('error', 'Format file tidak didukung. Gunakan .xlsx atau .csv.');
        }

        $import = new AsesiActivatedImport();

        try {
            Excel::import($import, $uploadedFile);
        } catch (\Exception $e) {
            return redirect()->route('admin.asesi.index')
                ->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        $msg = "Import selesai: {$import->imported} data asesi diperbarui.";
        if ($import->skipped > 0) {
            $msg .= " {$import->skipped} baris dilewati (belum aktivasi / data tidak ditemukan).";
        }
        if ($import->invalid > 0) {
            $msg .= " {$import->invalid} baris tidak valid.";
        }

        $type = $import->imported > 0 ? 'success' : 'error';

        return redirect()->route('admin.asesi.index')
            ->with($type, $msg)
            ->with('import_errors_activated', $import->errors);
    }

    /**
     * Download template import asesi aktivasi.
     */
    public function downloadActivatedTemplate()
    {
        $xlsxContent = $this->buildActivatedXlsxTemplate();

        return response($xlsxContent, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template_import_asesi_aktivasi.xlsx"',
            'Content-Length'      => strlen($xlsxContent),
        ]);
    }

    /**
     * Generate FR.APL.01 PDF for approved asesi only.
     */
    public function generatePdf($nik)
    {
        $asesi = Asesi::with(['jurusan', 'skemas', 'buktiPendukung', 'verifiedBy'])->findOrFail($nik);

        if (($asesi->status ?? null) !== 'approved') {
            abort(403, 'PDF APL 1 hanya tersedia untuk asesi yang sudah disetujui.');
        }

        $logoPath = public_path('images/lsp.png');
        $logoUrl = file_exists($logoPath) ? 'file://' . $logoPath : null;

        $isValidDataUri = function ($value) {
            return is_string($value)
                && preg_match('/^data:image\/(png|jpe?g);base64,[A-Za-z0-9+\/=\r\n]+$/i', $value);
        };

        $signatureRenderStyle = function (?string $dataUri, int $preferredHeight = 48, int $maxWidth = 180, int $maxHeight = 72): array {
            if (!$dataUri) {
                return ['src' => null, 'style' => null];
            }

            if (!preg_match('/^data:image\/(png|jpe?g);base64,(.*)$/si', $dataUri, $matches)) {
                return ['src' => $dataUri, 'style' => 'max-width: ' . $maxWidth . 'px; max-height: ' . $maxHeight . 'px; width: auto; height: auto;'];
            }

            $rawImage = base64_decode(str_replace(["\r", "\n"], '', $matches[2]), true);
            if ($rawImage === false) {
                return ['src' => $dataUri, 'style' => 'max-width: ' . $maxWidth . 'px; max-height: ' . $maxHeight . 'px; width: auto; height: auto;'];
            }

            $imageInfo = @getimagesizefromstring($rawImage);
            if (!$imageInfo || empty($imageInfo[0]) || empty($imageInfo[1])) {
                return ['src' => $dataUri, 'style' => 'max-width: ' . $maxWidth . 'px; max-height: ' . $maxHeight . 'px; width: auto; height: auto; display: block; margin: 0 auto;'];
            }

            [$width, $height] = [$imageInfo[0], $imageInfo[1]];
            $scale = 1;

            if ($height < $preferredHeight) {
                $scale = max($scale, $preferredHeight / max($height, 1));
            }

            if ($width * $scale > $maxWidth || $height * $scale > $maxHeight) {
                $scale = min($maxWidth / max($width, 1), $maxHeight / max($height, 1));
            }

            $renderWidth = max(1, (int) round($width * $scale));
            $renderHeight = max(1, (int) round($height * $scale));

            if ($renderWidth > $maxWidth || $renderHeight > $maxHeight) {
                $limitScale = min($maxWidth / max($renderWidth, 1), $maxHeight / max($renderHeight, 1));
                $renderWidth = max(1, (int) round($renderWidth * $limitScale));
                $renderHeight = max(1, (int) round($renderHeight * $limitScale));
            }

            return [
                'src' => $dataUri,
                'style' => "width: {$renderWidth}px; height: {$renderHeight}px; max-width: {$maxWidth}px; max-height: {$maxHeight}px; display: block; margin: 0 auto;",
            ];
        };

        $pendaftarSignature = $isValidDataUri($asesi->tanda_tangan_pendaftar ?? null)
            ? $signatureRenderStyle($asesi->tanda_tangan_pendaftar)
            : ['src' => null, 'style' => null];

        $verifikatorSignature = $isValidDataUri($asesi->tanda_tangan_admin ?? null)
            ? $signatureRenderStyle($asesi->tanda_tangan_admin)
            : ['src' => null, 'style' => null];

        $data = [
            'asesi' => $asesi,
            'skema' => $asesi->skemas->first(),
            'bukti_persyaratan' => $asesi->verifikasi_bukti_persyaratan_dasar ?? [],
            'bukti_administratif' => $asesi->verifikasi_bukti_administratif ?? [],
            'logoUrl' => $logoUrl,
            'pendaftarSignature' => $pendaftarSignature,
            'verifikatorSignature' => $verifikatorSignature,
            'adminSignerName' => optional($asesi->verifiedBy)->name ?? optional($asesi->verifiedBy)->username,
            'pendaftarSignedAt' => optional($asesi->tanggal_tanda_tangan_pendaftar)->format('d-m-Y'),
            'adminSignedAt' => optional($asesi->tanggal_tanda_tangan_admin)->format('d-m-Y'),
            'rekomendasiText' => 'Diterima',
            'catatanAdmin' => $asesi->catatan_admin,
        ];

        // Render the same view as HTML and convert to Word (DOCX)
        $html = view('admin.asesi.pdf.formulir', $data)->render();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection([
            'marginTop' => 800,
            'marginRight' => 680,
            'marginBottom' => 800,
            'marginLeft' => 680,
        ]);

        // Clean HTML: PHPWord's HTML importer expects a fragment (no <html>/<head>/<style>).
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument('1.0', 'UTF-8');
        // Prepend XML encoding to help DOMDocument handle UTF-8 correctly
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        // Remove all <style> and <link> nodes to avoid CSS parsing issues
        $styleTags = $dom->getElementsByTagName('style');
        for ($i = $styleTags->length - 1; $i >= 0; $i--) {
            $styleTags->item($i)->parentNode->removeChild($styleTags->item($i));
        }
        $linkTags = $dom->getElementsByTagName('link');
        for ($i = $linkTags->length - 1; $i >= 0; $i--) {
            $linkTags->item($i)->parentNode->removeChild($linkTags->item($i));
        }

        $body = $dom->getElementsByTagName('body')->item(0);
        $fragment = '';
        if ($body) {
            foreach ($body->childNodes as $node) {
                $fragment .= $dom->saveHTML($node);
            }
        } else {
            // fallback to whole HTML
            $fragment = $html;
        }
        libxml_clear_errors();

        // Build DOCX directly in a layout that mirrors the FR.APL.01 template.
        try {
            $safeText = function ($value): string {
                if ($value === null) {
                    return '';
                }

                if ($value instanceof \Illuminate\Support\Collection) {
                    return $value->map(function ($item) {
                        if (is_scalar($item)) {
                            return (string) $item;
                        }

                        if (is_object($item) && method_exists($item, '__toString')) {
                            return (string) $item;
                        }

                        return json_encode($item);
                    })->implode(', ');
                }

                if (is_array($value)) {
                    return implode(', ', array_map(function ($item) {
                        if (is_scalar($item)) {
                            return (string) $item;
                        }

                        if (is_object($item) && method_exists($item, '__toString')) {
                            return (string) $item;
                        }

                        return json_encode($item);
                    }, $value));
                }

                if (is_object($value)) {
                    if (method_exists($value, '__toString')) {
                        return (string) $value;
                    }

                    return json_encode($value);
                }

                return (string) $value;
            };

            $fontTitle = ['name' => 'Calibri', 'size' => 12, 'bold' => true];
            $fontBody = ['name' => 'Calibri', 'size' => 11];
            $fontSmall = ['name' => 'Calibri', 'size' => 11];
            $fontBold = ['name' => 'Calibri', 'size' => 11, 'bold' => true];

            $skema = $data['skema'] ?? null;
            $unitList = $skema ? ($skema->units ?? collect()) : collect();
            $selectedSkemaType = strtolower(trim((string) ($skema->jenis_skema ?? '')));
            $dokumenList = is_array($data['bukti_persyaratan']) ? $data['bukti_persyaratan'] : [];
            $administratifList = is_array($data['bukti_administratif']) ? $data['bukti_administratif'] : [];

            $isLaki = str_contains(strtolower((string) ($asesi->jenis_kelamin ?? '')), 'laki');
            $isPerempuan = str_contains(strtolower((string) ($asesi->jenis_kelamin ?? '')), 'perempuan') || strtolower((string) ($asesi->jenis_kelamin ?? '')) === 'p';

            // Helper: build a paragraph style with tab stops for form rows
            // leftIndent = col1 end, colonPos = colon pos, valueStart = value starts, valueEnd = line end
            // We use underline on spaces to simulate underline blanks

            // Page width in twips (A4 = 11906, margins 680 each = 10546 usable)
            // We'll use paragraph with indent approach:
            // Col1 label: 0–2750, colon: 2800, value: 2850–9500

            $addFormRow = function (string $label, string $value) use ($section, $fontBody) {
                $pf = [
                    'tabs' => [
                        new \PhpOffice\PhpWord\Style\Tab('left', 2800),
                        new \PhpOffice\PhpWord\Style\Tab('left', 3000),
                    ],
                    'indentation' => ['left' => 0],
                ];
                $p = $section->addTextRun($pf);
                $p->addText($label, $fontBody);
                $p->addText("\t:\t", $fontBody);
                // Underline the value field using a long underline-space if empty, or the value itself
                if ($value !== '') {
                    $p->addText($value, array_merge($fontBody, ['underline' => 'single']));
                } else {
                    // draw a long underline as a string of underscores spanning to right margin
                    $p->addText(str_repeat('_', 55), $fontBody);
                }
            };

            // For complex rows we need a different helper
            $addLabelOnly = function (string $label) use ($section, $fontBody) {
                $section->addText($label, $fontBody);
            };

            // ---- Data Pribadi ----
            $section->addText('FR.APL.01. PERMOHONAN SERTIFIKASI KOMPETENSI', $fontTitle);
            $section->addTextBreak(1);
            $section->addText('Bagian 1 :  Rincian Data Pemohon Sertifikasi', $fontBold);
            $section->addText('Pada bagian ini, cantumkan data pribadi, data pendidikan formal serta data pekerjaan anda pada saat ini.', $fontSmall);
            $section->addTextBreak(1);
            $section->addText('a. Data Pribadi', $fontBold);


            // Standard tab positions (twips): label col end=2750, colon=2800, value start=3000
            $tabStops = [
                new \PhpOffice\PhpWord\Style\Tab('left', 2800),
                new \PhpOffice\PhpWord\Style\Tab('left', 3000),
            ];
            $pStyle = ['tabs' => $tabStops];

            // Helper to add underline fill (actual underline on blank spaces)
            $ul = function (string $val, int $chars = 55) use ($fontBody): array {
                // returns [text, fontStyle]
                if ($val !== '') {
                    return [$val, array_merge($fontBody, ['underline' => 'single'])];
                }
                return [str_repeat(' ', $chars), array_merge($fontBody, ['underline' => 'single'])];
            };

            // Row: Nama lengkap
            $p = $section->addTextRun($pStyle);
            $p->addText('Nama lengkap', $fontBody);
            $p->addText("\t:\t", $fontBody);
            [$t, $f] = $ul($safeText($asesi->nama ?? ''));
            $p->addText($t, $f);

            // Row: No. KTP/NIK/Paspor
            $p = $section->addTextRun($pStyle);
            $p->addText('No. KTP/NIK/Paspor', $fontBody);
            $p->addText("\t:\t", $fontBody);
            [$t, $f] = $ul($safeText($asesi->NIK ?? ''));
            $p->addText($t, $f);

            // Row: Tempat / tgl. Lahir
            $p = $section->addTextRun($pStyle);
            $p->addText('Tempat / tgl. Lahir', $fontBody);
            $p->addText("\t:\t", $fontBody);
            $ttl = trim($safeText($asesi->tempat_lahir ?? '') . ' / ' . ($asesi->tanggal_lahir ? \Carbon\Carbon::parse($asesi->tanggal_lahir)->format('d-m-Y') : ''));
            [$t, $f] = $ul($ttl);
            $p->addText($t, $f);

            // Row: Jenis kelamin (no underline – just text)
            $p = $section->addTextRun($pStyle);
            $p->addText('Jenis kelamin', $fontBody);
            $p->addText("\t:\t", $fontBody);
            if ($isLaki) {
                $p->addText('Laki-laki', array_merge($fontBody, ['bold' => true, 'underline' => 'single']));
                $p->addText(' / Wanita *)', $fontBody);
            } elseif ($isPerempuan) {
                $p->addText('Laki-laki / ', $fontBody);
                $p->addText('Wanita *)', array_merge($fontBody, ['bold' => true, 'underline' => 'single']));
            } else {
                $p->addText('Laki-laki / Wanita *)', $fontBody);
            }

            // Row: Kebangsaan
            $p = $section->addTextRun($pStyle);
            $p->addText('Kebangsaan', $fontBody);
            $p->addText("\t:\t", $fontBody);
            [$t, $f] = $ul($safeText($asesi->kebangsaan ?? ''));
            $p->addText($t, $f);

            // Row: Alamat rumah
            $p = $section->addTextRun($pStyle);
            $p->addText('Alamat rumah', $fontBody);
            $p->addText("\t:\t", $fontBody);
            [$t, $f] = $ul($safeText($asesi->alamat ?? ''));
            $p->addText($t, $f);

            // Row: (Kode pos – indented sub-row)
            $tabsKodepos = [
                new \PhpOffice\PhpWord\Style\Tab('left', 5200),
                new \PhpOffice\PhpWord\Style\Tab('left', 5800),
            ];
            $p = $section->addTextRun(['tabs' => $tabsKodepos]);
            $p->addText("\t", $fontBody);
            $p->addText('Kode pos :', $fontBody);
            $p->addText("\t", $fontBody);
            [$t, $f] = $ul($safeText($asesi->kode_pos ?? ''), 20);
            $p->addText($t, $f);

            // Row: No. Telepon/E-mail – 2 sub-rows (Rumah/Kantor, HP/Email)
            $tabsTelp = [
                new \PhpOffice\PhpWord\Style\Tab('left', 2800),
                new \PhpOffice\PhpWord\Style\Tab('left', 3000),
                new \PhpOffice\PhpWord\Style\Tab('left', 5500),
                new \PhpOffice\PhpWord\Style\Tab('left', 5700),
            ];
            $p = $section->addTextRun(['tabs' => $tabsTelp]);
            $p->addText('No. Telepon/E-mail', $fontBody);
            $p->addText("\t:\t", $fontBody);
            $p->addText('Rumah : ', $fontBody);
            [$t, $f] = $ul($safeText($asesi->telepon_rumah ?? ''), 18);
            $p->addText($t, $f);
            $p->addText("\t", $fontBody);
            $p->addText('Kantor : ', $fontBody);
            [$t, $f] = $ul($safeText($asesi->no_fax_lembaga ?? ''), 16);
            $p->addText($t, $f);

            // Sub-row HP / Email
            $p = $section->addTextRun(['tabs' => $tabsTelp]);
            $p->addText("\t\t", $fontBody);
            $p->addText('HP : ', $fontBody);
            [$t, $f] = $ul($safeText($asesi->telepon_hp ?? ''), 21);
            $p->addText($t, $f);
            $p->addText("\t", $fontBody);
            $p->addText('E-mail : ', $fontBody);
            [$t, $f] = $ul($safeText($asesi->email ?? ''), 16);
            $p->addText($t, $f);

            // Row: Kualifikasi Pendidikan
            $p = $section->addTextRun($pStyle);
            $p->addText('Kualifikasi Pendidikan', $fontBody);
            $p->addText("\t:\t", $fontBody);
            [$t, $f] = $ul($safeText($asesi->pendidikan_terakhir ?? ''));
            $p->addText($t, $f);

            $section->addText('*Coret yang tidak perlu', $fontSmall);
            $section->addTextBreak(1);

            // ---- Data Pekerjaan Sekarang ----
            $section->addText('b. Data Pekerjaan Sekarang', $fontBold);

            // Row: Nama Institusi / Perusahaan
            $p = $section->addTextRun($pStyle);
            $p->addText('Nama Institusi / Perusahaan', $fontBody);
            $p->addText("\t:\t", $fontBody);
            [$t, $f] = $ul($safeText($asesi->nama_lembaga ?? ''));
            $p->addText($t, $f);

            // Row: Jabatan
            $p = $section->addTextRun($pStyle);
            $p->addText('Jabatan', $fontBody);
            $p->addText("\t:\t", $fontBody);
            [$t, $f] = $ul($safeText($asesi->jabatan ?? ''));
            $p->addText($t, $f);

            // Row: Alamat Kantor
            $p = $section->addTextRun($pStyle);
            $p->addText('Alamat Kantor', $fontBody);
            $p->addText("\t:\t", $fontBody);
            [$t, $f] = $ul($safeText($asesi->alamat_lembaga ?? ''));
            $p->addText($t, $f);

            // Row: Kode pos (sub-row)
            $p = $section->addTextRun(['tabs' => $tabsKodepos]);
            $p->addText("\t", $fontBody);
            $p->addText('Kode pos :', $fontBody);
            $p->addText("\t", $fontBody);
            [$t, $f] = $ul($safeText($asesi->unit_lembaga ?? ''), 20);
            $p->addText($t, $f);

            // Row: No. Telp/Fax/E-mail
            $p = $section->addTextRun(['tabs' => $tabsTelp]);
            $p->addText('No. Telp/Fax/E-mail', $fontBody);
            $p->addText("\t:\t", $fontBody);
            $p->addText('Telp : ', $fontBody);
            [$t, $f] = $ul($safeText($asesi->telepon_rumah ?? ''), 18);
            $p->addText($t, $f);
            $p->addText("\t", $fontBody);
            $p->addText('Fax : ', $fontBody);
            [$t, $f] = $ul($safeText($asesi->no_fax_lembaga ?? ''), 16);
            $p->addText($t, $f);

            // Sub-row E-mail
            $tabsEmailKantor = [
                new \PhpOffice\PhpWord\Style\Tab('left', 3000),
            ];
            $p = $section->addTextRun(['tabs' => $tabsEmailKantor]);
            $p->addText('E-mail : ', $fontBody);
            [$t, $f] = $ul($safeText($asesi->email_lembaga ?? ''), 50);
            $p->addText($t, $f);

            $section->addTextBreak(1);
            $section->addText('Bagian 2 : Data Sertifikasi', $fontBold);
            $section->addText('Tuliskan Judul dan Nomor Skema Sertifikasi yang anda ajukan berikut Daftar Unit Kompetensi sesuai kemasan pada skema sertifikasi untuk mendapatkan pengakuan sesuai dengan latar belakang pendidikan, pelatihan serta pengalaman kerja yang anda miliki.', $fontSmall);

            $section->addTextBreak(1);
            $dataTable = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 60,
                'width' => 10546,
                'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::TWIP,
            ]);

            // Row 1: Skema Sertifikasi, Judul, Name
            $dataTable->addRow();
            $cell1 = $dataTable->addCell(3400, ['vMerge' => 'restart', 'valign' => 'center']);
            $cell1->addText('Skema Sertifikasi', $fontBody);
            $textRun = $cell1->addTextRun();
            $textRun->addText('(', $fontBody);
            $textRun->addText('KKNI', array_merge($fontBody, $selectedSkemaType === 'kkni' ? [] : ['strikethrough' => true]));
            $textRun->addText('/', $fontBody);
            $textRun->addText('Okupasi', array_merge($fontBody, $selectedSkemaType === 'okupasi' ? [] : ['strikethrough' => true]));
            $textRun->addText('/', $fontBody);
            $textRun->addText('Klaster', array_merge($fontBody, $selectedSkemaType === 'klaster' ? [] : ['strikethrough' => true]));
            $textRun->addText(')', $fontBody);

            $dataTable->addCell(1400, ['valign' => 'center'])->addText('Judul', $fontBody);
            $dataTable->addCell(300, ['valign' => 'center'])->addText(':', $fontBody, ['alignment' => 'center']);
            $dataTable->addCell(5446, ['gridSpan' => 2, 'valign' => 'center'])->addText($safeText($skema->nama_skema ?? '-'), $fontBody);

            // Row 2: Skema Sertifikasi (Merge), Nomor, Number
            $dataTable->addRow();
            $dataTable->addCell(3400, ['vMerge' => 'continue']);
            $dataTable->addCell(1400, ['valign' => 'center'])->addText('Nomor', $fontBody);
            $dataTable->addCell(300, ['valign' => 'center'])->addText(':', $fontBody, ['alignment' => 'center']);
            $dataTable->addCell(5446, ['gridSpan' => 2, 'valign' => 'center'])->addText($safeText($skema->nomor_skema ?? '-'), $fontBody);

            // Row 3: Tujuan Asesmen, (Empty), :, Box, Sertifikasi
            $dataTable->addRow();
            $cellTujuan = $dataTable->addCell(3400, ['vMerge' => 'restart', 'valign' => 'center']);
            $cellTujuan->addText('Tujuan Asesmen', $fontBody);

            $dataTable->addCell(1400, ['valign' => 'center']);
            $dataTable->addCell(300, ['valign' => 'center'])->addText(':', $fontBody, ['alignment' => 'center']);
            $dataTable->addCell(500, ['valign' => 'center'])->addText('☑', ['name' => 'Calibri', 'size' => 11], ['alignment' => 'center']);
            $dataTable->addCell(4946, ['valign' => 'center'])->addText('Sertifikasi', $fontBody);

            // Row 4: Tujuan Asesmen (Merge), (Empty), (Empty), Box, PKT
            $dataTable->addRow();
            $dataTable->addCell(3400, ['vMerge' => 'continue']);
            $dataTable->addCell(1400, ['valign' => 'center']);
            $dataTable->addCell(300, ['valign' => 'center']);
            $dataTable->addCell(500, ['valign' => 'center'])->addText('☐', ['name' => 'Calibri', 'size' => 11], ['alignment' => 'center']);
            $dataTable->addCell(4946, ['valign' => 'center'])->addText('Pengakuan Kompetensi Terkini (PKT)', $fontBody);

            // Row 5: Tujuan Asesmen (Merge), (Empty), (Empty), Box, RPL
            $dataTable->addRow();
            $dataTable->addCell(3400, ['vMerge' => 'continue']);
            $dataTable->addCell(1400, ['valign' => 'center']);
            $dataTable->addCell(300, ['valign' => 'center']);
            $dataTable->addCell(500, ['valign' => 'center'])->addText('☐', ['name' => 'Calibri', 'size' => 11], ['alignment' => 'center']);
            $dataTable->addCell(4946, ['valign' => 'center'])->addText('Rekognisi Pembelajaran Lampau (RPL)', $fontBody);

            // Row 6: Tujuan Asesmen (Merge), (Empty), (Empty), Box, Lainnya
            $dataTable->addRow();
            $dataTable->addCell(3400, ['vMerge' => 'continue']);
            $dataTable->addCell(1400, ['valign' => 'center']);
            $dataTable->addCell(300, ['valign' => 'center']);
            $dataTable->addCell(500, ['valign' => 'center'])->addText('☐', ['name' => 'Calibri', 'size' => 11], ['alignment' => 'center']);
            $dataTable->addCell(4946, ['valign' => 'center'])->addText('Lainnya', $fontBody);

            $section->addTextBreak(1);

            $section->addText('Daftar Unit Kompetensi sesuai kemasan:', $fontSmall);
            $unitTable = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 60,
                'width' => 10546,
                'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::TWIP,
            ]);
            $unitTable->addRow();
            $unitTable->addCell(600, ['valign' => 'center'])->addText('No.', $fontBody, ['alignment' => 'center']);
            $unitTable->addCell(2200, ['valign' => 'center'])->addText('Kode Unit', $fontBody, ['alignment' => 'center']);
            $unitTable->addCell(4746, ['valign' => 'center'])->addText('Judul Unit', $fontBody, ['alignment' => 'center']);
            $unitTable->addCell(3000, ['valign' => 'center'])->addText('Standar Kompetensi Kerja', $fontBody, ['alignment' => 'center']);

            if ($unitList instanceof \Illuminate\Support\Collection && $unitList->isNotEmpty()) {
                foreach ($unitList as $index => $unit) {
                    $unitTable->addRow();
                    $unitTable->addCell(600)->addText((string) ($index + 1), $fontBody, ['alignment' => 'center']);
                    $unitTable->addCell(2200)->addText($safeText($unit->kode_unit ?? $unit->kode ?? '-'), $fontBody);
                    $unitTable->addCell(4746)->addText($safeText($unit->judul_unit ?? $unit->nama_unit ?? $unit->judul ?? '-'), $fontBody);
                    $unitTable->addCell(3000)->addText($safeText($unit->standar_kompetensi ?? 'SKKNI'), $fontBody);
                }
            } else {
                $unitTable->addRow();
                $unitTable->addCell(600)->addText('1', $fontBody, ['alignment' => 'center']);
                $unitTable->addCell(2200)->addText('...', $fontBody);
                $unitTable->addCell(4746)->addText('...', $fontBody);
                $unitTable->addCell(3000)->addText('...', $fontBody);
            }

            $section->addTextBreak(1);
            $section->addText('Bagian 3 : Bukti Kelengkapan Pemohon', $fontBold);
            $section->addText('3.1 Bukti Persyaratan Dasar Pemohon', $fontSmall);

            $docTable = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 60,
                'width' => 10546,
                'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::TWIP,
            ]);
            $docTable->addRow();
            $docTable->addCell(600, ['valign' => 'center'])->addText('No.', $fontBody, ['alignment' => 'center']);
            $docTable->addCell(6346, ['valign' => 'center'])->addText('Bukti Persyaratan Dasar', $fontBody, ['alignment' => 'center']);
            $docTable->addCell(1200, ['valign' => 'center'])->addText('Ada Memenuhi Syarat', $fontBody, ['alignment' => 'center']);
            $docTable->addCell(1200, ['valign' => 'center'])->addText('Tidak Memenuhi Syarat', $fontBody, ['alignment' => 'center']);
            $docTable->addCell(1200, ['valign' => 'center'])->addText('Tidak Ada', $fontBody, ['alignment' => 'center']);

            $docRows = !empty($dokumenList) ? $dokumenList : [
                'Fotocopy Rapor pada kesesuaian/hasil nilai yang relevan',
                'Fotocopy Sertifikat/Surat Keterangan telah melaksanakan PKL bidang Multimedia',
                'Portofolio / Bukti pendukung kompetensi lain',
            ];

            foreach ($docRows as $index => $row) {
                $label = is_string($row) ? $row : ($row['label'] ?? $row['nama'] ?? '');
                $state = is_array($row) ? ($row['status'] ?? '') : '';
                $docTable->addRow();
                $docTable->addCell(600)->addText((string) ($index + 1), $fontBody, ['alignment' => 'center']);
                $docTable->addCell(6346)->addText($safeText($label), $fontBody);
                $docTable->addCell(1200)->addText($state === 'memenuhi' ? '☑' : '☐', ['name' => 'Calibri', 'size' => 11], ['alignment' => 'center']);
                $docTable->addCell(1200)->addText($state === 'tidak_memenuhi' ? '☑' : '☐', ['name' => 'Calibri', 'size' => 11], ['alignment' => 'center']);
                $docTable->addCell(1200)->addText($state === 'tidak_ada' ? '☑' : '☐', ['name' => 'Calibri', 'size' => 11], ['alignment' => 'center']);
            }

            $section->addTextBreak(1);
            $section->addText('3.2 Bukti Administratif', $fontSmall);
            $adminTable = $section->addTable([
                'borderSize' => 6,
                'borderColor' => '000000',
                'cellMargin' => 60,
                'width' => 10546,
                'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::TWIP,
            ]);
            $adminTable->addRow();
            $adminTable->addCell(600, ['valign' => 'center'])->addText('No.', $fontBody, ['alignment' => 'center']);
            $adminTable->addCell(6346, ['valign' => 'center'])->addText('Bukti Administratif', $fontBody, ['alignment' => 'center']);
            $adminTable->addCell(1200, ['valign' => 'center'])->addText('Ada Memenuhi Syarat', $fontBody, ['alignment' => 'center']);
            $adminTable->addCell(1200, ['valign' => 'center'])->addText('Tidak Memenuhi Syarat', $fontBody, ['alignment' => 'center']);
            $adminTable->addCell(1200, ['valign' => 'center'])->addText('Tidak Ada', $fontBody, ['alignment' => 'center']);

            $adminRows = !empty($administratifList) ? $administratifList : [
                'Fotocopy Kartu Pelajar',
                'Fotocopy Kartu Keluarga/KTP',
                'Pas foto 3 x 4 berwarna sebanyak 2 lembar',
            ];

            foreach ($adminRows as $index => $row) {
                $label = is_string($row) ? $row : ($row['label'] ?? $row['nama'] ?? '');
                $state = is_array($row) ? ($row['status'] ?? '') : '';
                $adminTable->addRow();
                $adminTable->addCell(600)->addText((string) ($index + 1), $fontBody, ['alignment' => 'center']);
                $adminTable->addCell(6346)->addText($safeText($label), $fontBody);
                $adminTable->addCell(1200)->addText($state === 'memenuhi' ? '☑' : '☐', ['name' => 'Calibri', 'size' => 11], ['alignment' => 'center']);
                $adminTable->addCell(1200)->addText($state === 'tidak_memenuhi' ? '☑' : '☐', ['name' => 'Calibri', 'size' => 11], ['alignment' => 'center']);
                $adminTable->addCell(1200)->addText($state === 'tidak_ada' ? '☑' : '☐', ['name' => 'Calibri', 'size' => 11], ['alignment' => 'center']);
            }

            $section->addTextBreak(1);
            $signTable = $section->addTable([
                'borderSize' => 0,
                'cellMargin' => 0,
                'width' => 10546,
                'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::TWIP,
            ]);
            $signTable->addRow();
            $signTable->addCell(5546, ['valign' => 'top'])->addText('Rekomendasi (diisi oleh LSP):', $fontBold);
            $signTable->addCell(5000, ['valign' => 'top'])->addText('Pemohon/ Kandidat :', $fontBold, ['alignment' => 'center']);
            $signTable->addRow();
            $signTable->addCell(5546, ['valign' => 'top'])->addText('Berdasarkan ketentuan persyaratan dasar, maka pemohon: ' . ((strtolower(trim((string) ($data['rekomendasiText'] ?? 'Diterima'))) === 'diterima') ? 'Diterima' : 'Tidak diterima') . ' sebagai peserta sertifikasi', $fontBody);
            $candidateCell = $signTable->addCell(5000, ['valign' => 'top']);
            $candidateCell->addText('Nama : ' . $safeText($asesi->nama ?? '-'), $fontBody);
            $candidateCell->addTextBreak(2);

            // Prepare temporary image storage for data URIs
            $tempImages = [];
            $saveDataUriToTemp = function (?string $dataUri) use (&$tempImages) {
                if (!$dataUri || !is_string($dataUri)) {
                    return null;
                }
                if (!preg_match('/^data:image\/([a-zA-Z0-9+]+);base64,(.*)$/s', $dataUri, $m)) {
                    return null;
                }
                $ext = strtolower($m[1]);
                if ($ext === 'jpeg') $ext = 'jpg';
                $raw = base64_decode(str_replace(["\r", "\n"], ['', ''], $m[2]));
                if ($raw === false) {
                    return null;
                }
                $tmp = tempnam(sys_get_temp_dir(), 'apl_img_') . '.' . $ext;
                file_put_contents($tmp, $raw);
                if (@getimagesize($tmp) === false) {
                    @unlink($tmp);
                    return null;
                }
                $tempImages[] = $tmp;
                return $tmp;
            };

            if (!empty($pendaftarSignature['src'])) {
                $imgPath = $pendaftarSignature['src'];
                if (str_starts_with($imgPath, 'data:')) {
                    $imgPath = $saveDataUriToTemp($imgPath);
                    if ($imgPath === null) {
                        \Log::warning('PHPWord: invalid pendaftar signature data URI for APL.01 (skipping image)');
                    }
                }
                if (!empty($imgPath) && file_exists($imgPath)) {
                    $candidateCell->addImage($imgPath, ['width' => 140, 'height' => 52, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                }
            }

            $candidateCell->addText($safeText($data['pendaftarSignedAt'] ?? '-'), $fontBody, ['alignment' => 'center']);
            $signTable->addRow();
            $signTable->addCell(5546, ['valign' => 'top'])->addText('* coret yang tidak sesuai', $fontSmall);
            $adminCell = $signTable->addCell(5000, ['valign' => 'top']);
            $adminCell->addText('Admin LSP :', $fontBold);
            $adminCell->addText('Nama : ' . $safeText($data['adminSignerName'] ?? '-'), $fontBody);
            $adminCell->addTextBreak(2);
            if (!empty($verifikatorSignature['src'])) {
                $imgAdmin = $verifikatorSignature['src'];
                if (str_starts_with($imgAdmin, 'data:')) {
                    $imgAdmin = $saveDataUriToTemp($imgAdmin);
                    if ($imgAdmin === null) {
                        \Log::warning('PHPWord: invalid verifikator signature data URI for APL.01 (skipping image)');
                    }
                }
                if (!empty($imgAdmin) && file_exists($imgAdmin)) {
                    $adminCell->addImage($imgAdmin, ['width' => 140, 'height' => 52, 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
                }
            }
            $adminCell->addText($safeText($data['adminSignedAt'] ?? '-'), $fontBody, ['alignment' => 'center']);

        } catch (\Throwable $e) {
            \Log::error('PHPWord build failed for APL.01: ' . $e->getMessage());
            // Fallback to PDF to keep functionality working
            $pdf = Pdf::loadView('admin.asesi.pdf.formulir', $data)->setPaper('a4', 'portrait');
            $fileNamePdf = 'FR_APL_01_' . ($asesi->NIK ?? 'asesi') . '.pdf';
            return $pdf->stream($fileNamePdf);
        }

        $fileName = 'FR_APL_01_' . ($asesi->NIK ?? 'asesi') . '.docx';

        $tempFile = tempnam(sys_get_temp_dir(), 'apl_') . '.docx';
        try {
            \Log::info('PHPWord: attempting to write DOCX to ' . $tempFile);
            $writer = WordIO::createWriter($phpWord, 'Word2007');
            $writer->save($tempFile);
            \Log::info('PHPWord: DOCX written to ' . $tempFile);
            // cleanup any temporary images created from data URIs
            if (!empty($tempImages) && is_array($tempImages)) {
                foreach ($tempImages as $ti) {
                    try {
                        if (file_exists($ti)) {
                            @unlink($ti);
                        }
                    } catch (\Throwable $_) {
                        // ignore cleanup errors
                    }
                }
                \Log::info('PHPWord: cleaned up ' . count($tempImages) . ' temp images for APL.01');
            }
        } catch (\Throwable $e) {
            \Log::error('PHPWord write failed for APL.01: ' . $e->getMessage());
            // If saving fails, fallback to PDF as before
            $pdf = Pdf::loadView('admin.asesi.pdf.formulir', $data)->setPaper('a4', 'portrait');
            $fileNamePdf = 'FR_APL_01_' . ($asesi->NIK ?? 'asesi') . '.pdf';
            return $pdf->stream($fileNamePdf);
        }

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Export data asesi aktivasi dengan filter jurusan, skema, status, dan tanggal daftar (bisa ditumpuk).
     */
    public function exportActivated(Request $request)
    {
        $validated = $request->validate([
            'jurusan' => 'nullable|array',
            'jurusan.*' => 'nullable|exists:jurusan,ID_jurusan',
            'skema' => 'nullable|array',
            'skema.*' => 'nullable|exists:skemas,id',
            'status_asesmen' => 'nullable|in:pending,approved,rejected',
            'tanggal_dari' => 'nullable|date',
            'tanggal_sampai' => 'nullable|date',
        ]);

        $query = Asesi::query()
            ->whereHas('account', function ($q) {
                $q->where('role', 'asesi');
            })
            ->with('skemas');

        $jurusanIds = array_values(array_filter($validated['jurusan'] ?? []));
        if (!empty($jurusanIds)) {
            $query->whereIn('ID_jurusan', $jurusanIds);
        }

        $skemaIds = array_values(array_filter($validated['skema'] ?? []));
        if (!empty($skemaIds)) {
            $query->whereHas('skemas', function ($q) use ($skemaIds) {
                $q->whereIn('skemas.id', $skemaIds);
            });
        }

        $statusAsesmen = $validated['status_asesmen'] ?? null;
        if (!empty($statusAsesmen)) {
            $query->where('status', $statusAsesmen);
        }

        $tanggalDari = $validated['tanggal_dari'] ?? null;
        $tanggalSampai = $validated['tanggal_sampai'] ?? null;

        // Keep date range usable even if user selects dates in reverse order.
        if (!empty($tanggalDari) && !empty($tanggalSampai) && $tanggalDari > $tanggalSampai) {
            [$tanggalDari, $tanggalSampai] = [$tanggalSampai, $tanggalDari];
        }

        if (!empty($tanggalDari)) {
            $query->whereDate('created_at', '>=', $tanggalDari);
        }

        if (!empty($tanggalSampai)) {
            $query->whereDate('created_at', '<=', $tanggalSampai);
        }

        $asesiRows = $query->orderBy('nama')->get();

        $xlsxContent = $this->buildActivatedExportXlsx($asesiRows);

        return response($xlsxContent, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="export_data_asesi_aktivasi.xlsx"',
            'Content-Length' => strlen($xlsxContent),
        ]);
    }

    /**
     * Build minimal XLSX template dengan header biru #0073BD.
     */
    private function buildActivatedXlsxTemplate(): string
    {
        $strings = [
            'No',
            'Nama Asesi',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Tempat Tinggal',
            'Telp',
            'Email',
            '1',
            'Budi Santoso',
            '3204010101010001',
            'Bandung',
            '12/05/2007',
            'L',
            'Jl. Melati No. 1',
            '081234567890',
            'budi@example.com',
        ];

        $ssCount = count($strings);
        $ssItems = implode('', array_map(fn($s) => "<si><t xml:space=\"preserve\">{$s}</t></si>", $strings));

        $sharedStringsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . "<sst xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\" count=\"{$ssCount}\" uniqueCount=\"{$ssCount}\">"
            . $ssItems
            . '</sst>';

        $stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<numFmts count="1"><numFmt numFmtId="49" formatCode="@"/></numFmts>'
            . '<fonts count="2">'
            . '<font><sz val="11"/><name val="Calibri"/></font>'
            . '<font><sz val="11"/><name val="Calibri"/><b/><color rgb="FFFFFFFF"/></font>'
            . '</fonts>'
            . '<fills count="3">'
            . '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF0073BD"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="3">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0"/>'
            . '<xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>'
            . '</cellXfs>'
            . '</styleSheet>';

        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetFormatPr defaultRowHeight="15"/>'
            . '<cols>'
            . '<col min="1" max="1" width="8" customWidth="1"/>'
            . '<col min="2" max="2" width="26" customWidth="1"/>'
            . '<col min="3" max="3" width="22" customWidth="1"/>'
            . '<col min="4" max="4" width="18" customWidth="1"/>'
            . '<col min="5" max="5" width="16" customWidth="1"/>'
            . '<col min="6" max="6" width="16" customWidth="1"/>'
            . '<col min="7" max="7" width="30" customWidth="1"/>'
            . '<col min="8" max="8" width="18" customWidth="1"/>'
            . '<col min="9" max="9" width="26" customWidth="1"/>'
            . '</cols>'
            . '<sheetData>'
            . '<row r="1">'
            . '<c r="A1" t="s" s="1"><v>0</v></c>'
            . '<c r="B1" t="s" s="1"><v>1</v></c>'
            . '<c r="C1" t="s" s="1"><v>2</v></c>'
            . '<c r="D1" t="s" s="1"><v>3</v></c>'
            . '<c r="E1" t="s" s="1"><v>4</v></c>'
            . '<c r="F1" t="s" s="1"><v>5</v></c>'
            . '<c r="G1" t="s" s="1"><v>6</v></c>'
            . '<c r="H1" t="s" s="1"><v>7</v></c>'
            . '<c r="I1" t="s" s="1"><v>8</v></c>'
            . '</row>'
            . '<row r="2">'
            . '<c r="A2" t="s"><v>9</v></c>'
            . '<c r="B2" t="s"><v>10</v></c>'
            . '<c r="C2" t="s" s="2"><v>11</v></c>'
            . '<c r="D2" t="s"><v>12</v></c>'
            . '<c r="E2" t="s"><v>13</v></c>'
            . '<c r="F2" t="s"><v>14</v></c>'
            . '<c r="G2" t="s"><v>15</v></c>'
            . '<c r="H2" t="s"><v>16</v></c>'
            . '<c r="I2" t="s"><v>17</v></c>'
            . '</row>'
            . '</sheetData>'
            . '</worksheet>';

        $workbookXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="template_import_asesi_aktivasi" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';

        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';

        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';

        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>'
            . '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';

        $tmpFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new \ZipArchive();
        $zip->open($tmpFile, \ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', $contentTypes);
        $zip->addFromString('_rels/.rels', $rels);
        $zip->addFromString('xl/workbook.xml', $workbookXml);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->addFromString('xl/sharedStrings.xml', $sharedStringsXml);
        $zip->addFromString('xl/styles.xml', $stylesXml);
        $zip->close();

        $content = file_get_contents($tmpFile);
        unlink($tmpFile);

        return $content;
    }

    /**
     * Build XLSX export dengan struktur kolom yang sama seperti template aktivasi.
     */
    private function buildActivatedExportXlsx($asesiRows): string
    {
        $headers = [
            'No',
            'Nama Asesi',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Tempat Tinggal',
            'Telp',
            'Email',
        ];

        $sharedStrings = [];
        $sharedMap = [];

        $addSharedString = function (string $value) use (&$sharedStrings, &$sharedMap): int {
            if (!array_key_exists($value, $sharedMap)) {
                $sharedMap[$value] = count($sharedStrings);
                $sharedStrings[] = $value;
            }
            return $sharedMap[$value];
        };

        $escapeXml = function (string $value): string {
            return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
        };

        // Preload headers
        foreach ($headers as $header) {
            $addSharedString($header);
        }

        $toGender = function ($val): string {
            if ($val === 'L' || $val === 'Laki-laki') {
                return 'Laki-laki';
            }
            if ($val === 'P' || $val === 'Perempuan') {
                return 'Perempuan';
            }
            return '';
        };

        $rowsXml = '';

        // Header row
        $rowsXml .= '<row r="1">'
            . '<c r="A1" t="s" s="1"><v>' . $addSharedString('No') . '</v></c>'
            . '<c r="B1" t="s" s="1"><v>' . $addSharedString('Nama Asesi') . '</v></c>'
            . '<c r="C1" t="s" s="1"><v>' . $addSharedString('NIK') . '</v></c>'
            . '<c r="D1" t="s" s="1"><v>' . $addSharedString('Tempat Lahir') . '</v></c>'
            . '<c r="E1" t="s" s="1"><v>' . $addSharedString('Tanggal Lahir') . '</v></c>'
            . '<c r="F1" t="s" s="1"><v>' . $addSharedString('Jenis Kelamin') . '</v></c>'
            . '<c r="G1" t="s" s="1"><v>' . $addSharedString('Tempat Tinggal') . '</v></c>'
            . '<c r="H1" t="s" s="1"><v>' . $addSharedString('Telp') . '</v></c>'
            . '<c r="I1" t="s" s="1"><v>' . $addSharedString('Email') . '</v></c>'
            . '</row>';

        foreach ($asesiRows as $i => $row) {
            $r = $i + 2;
            $tanggalLahir = $row->tanggal_lahir ? \Carbon\Carbon::parse($row->tanggal_lahir)->format('d/m/Y') : '';

            $values = [
                (string) ($i + 1),
                (string) ($row->nama ?? ''),
                (string) ($row->NIK ?? ''),
                (string) ($row->tempat_lahir ?? ''),
                $tanggalLahir,
                $toGender($row->jenis_kelamin ?? null),
                (string) ($row->alamat ?? ''),
                (string) ($row->telepon_hp ?? ''),
                (string) ($row->email ?? ''),
            ];

            $rowsXml .= '<row r="' . $r . '">';
            foreach ($values as $colIndex => $value) {
                $colLetter = chr(65 + $colIndex);
                $cellRef = $colLetter . $r;
                $sharedIndex = $addSharedString($value);
                $style = $colLetter === 'C' ? ' s="2"' : '';
                $rowsXml .= '<c r="' . $cellRef . '" t="s"' . $style . '><v>' . $sharedIndex . '</v></c>';
            }
            $rowsXml .= '</row>';
        }

        $ssItems = implode('', array_map(function ($s) use ($escapeXml) {
            return '<si><t xml:space="preserve">' . $escapeXml($s) . '</t></si>';
        }, $sharedStrings));

        $sharedStringsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="' . count($sharedStrings) . '" uniqueCount="' . count($sharedStrings) . '">'
            . $ssItems
            . '</sst>';

        $stylesXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<numFmts count="1"><numFmt numFmtId="49" formatCode="@"/></numFmts>'
            . '<fonts count="2">'
            . '<font><sz val="11"/><name val="Calibri"/></font>'
            . '<font><sz val="11"/><name val="Calibri"/><b/><color rgb="FFFFFFFF"/></font>'
            . '</fonts>'
            . '<fills count="3">'
            . '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF0073BD"/></patternFill></fill>'
            . '</fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="3">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0"/>'
            . '<xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>'
            . '</cellXfs>'
            . '</styleSheet>';

        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetFormatPr defaultRowHeight="15"/>'
            . '<cols>'
            . '<col min="1" max="1" width="8" customWidth="1"/>'
            . '<col min="2" max="2" width="26" customWidth="1"/>'
            . '<col min="3" max="3" width="22" customWidth="1"/>'
            . '<col min="4" max="4" width="18" customWidth="1"/>'
            . '<col min="5" max="5" width="16" customWidth="1"/>'
            . '<col min="6" max="6" width="16" customWidth="1"/>'
            . '<col min="7" max="7" width="30" customWidth="1"/>'
            . '<col min="8" max="8" width="18" customWidth="1"/>'
            . '<col min="9" max="9" width="26" customWidth="1"/>'
            . '</cols>'
            . '<sheetData>' . $rowsXml . '</sheetData>'
            . '</worksheet>';

        $workbookXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="export_data_asesi_aktivasi" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';

        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';

        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';

        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>'
            . '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';

        $tmpFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new \ZipArchive();
        $zip->open($tmpFile, \ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', $contentTypes);
        $zip->addFromString('_rels/.rels', $rels);
        $zip->addFromString('xl/workbook.xml', $workbookXml);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->addFromString('xl/sharedStrings.xml', $sharedStringsXml);
        $zip->addFromString('xl/styles.xml', $stylesXml);
        $zip->close();

        $content = file_get_contents($tmpFile);
        unlink($tmpFile);

        return $content;
    }

    /**
     * Display a listing of asesi for verification.
     */
    public function verifikasi(Request $request)
    {
        // Get the filter status from query parameter
        $status = $request->query('status', '');
        
            // If no status provided and no search/filter, redirect to pending view
            if (empty($status) && !$request->has('search') && !$request->has('jurusan') && !$request->has('reject_type')) {
                return redirect()->route('admin.asesi.verifikasi', ['status' => 'pending']);
            }
        
        // Get reject type filter (sementara/permanen)
        $rejectType = $request->query('reject_type', '');
        
        // Get search query
        $search = $request->query('search');
        
        // Get jurusan filter
        $jurusanFilter = $request->query('jurusan');
        
        // Get sort parameter
        $sort = $request->query('sort', 'desc'); // default: newest first

        // Get per_page with safe bounds
        $perPage = (int) $request->query('per_page', 10);
        $perPage = max(1, min($perPage, 200));
        
        // Build the query
        $query = Asesi::with('jurusan');
        
        // Apply status filter if provided
        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'approved') {
            $query->where('status', 'approved');
        } elseif ($status === 'rejected') {
            // Include both 'rejected' (sementara) and 'banned' (permanen)
            $query->whereIn('status', ['rejected', 'banned']);
            
            // Apply reject_type filter if provided
            if ($rejectType === 'temporary') {
                $query->where('status', 'rejected');
            } elseif ($rejectType === 'permanent') {
                $query->where('status', 'banned');
            }
        }
        
        // Apply jurusan filter if provided
        if ($jurusanFilter) {
            $query->where('ID_jurusan', $jurusanFilter);
        }
        
        // Apply search filter if provided
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('NIK', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        // Apply sorting
        if ($sort === 'asc') {
            $query->orderBy('nama', 'asc');
        } elseif ($sort === 'desc') {
            $query->orderBy('nama', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // Get paginated results
        $asesi = $query->paginate($perPage)->appends($request->except('page'));
        
        // Get counts for stats
        $counts = [
            'pending' => Asesi::where('status', 'pending')->count(),
            'approved' => Asesi::where('status', 'approved')->count(),
            'rejected' => Asesi::whereIn('status', ['rejected', 'banned'])->count(),
            'rejected_temporary' => Asesi::where('status', 'rejected')->count(),
            'rejected_permanent' => Asesi::where('status', 'banned')->count(),
            'total' => Asesi::count(),
        ];
        
        // Get jurusan list for filter dropdown (include kelas items for dependent select)
        $jurusanList = Jurusan::with('kelasItems')->orderBy('nama_jurusan')->get();

        // Selected kelas filter
        $kelasFilter = $request->query('kelas');
        
        // If AJAX request, return only table rows
        if ($request->ajax()) {
            return view('admin.asesi.partials.verifikasi-table-rows', compact('asesi'))->render();
        }
        
        return view('admin.asesi.verifikasi', compact('asesi', 'status', 'rejectType', 'counts', 'jurusanList', 'perPage', 'jurusanFilter', 'kelasFilter'));
    }

    /**
     * Show the verification detail page for a specific asesi.
     */
    public function showVerifikasi($nik)
    {
        $asesi = Asesi::with(['jurusan', 'buktiPendukung', 'verifiedBy', 'skemas.buktiPersyaratanDasarPemohon'])
            ->findOrFail($nik);
        
        $asesiSkemas = \DB::table('asesi_skema')
            ->join('skemas', 'asesi_skema.skema_id', '=', 'skemas.id')
            ->where('asesi_skema.asesi_nik', $asesi->NIK)
            ->whereRaw('asesi_skema.attempt = (SELECT MAX(b.attempt) FROM asesi_skema b WHERE b.asesi_nik = asesi_skema.asesi_nik AND b.skema_id = asesi_skema.skema_id)')
            ->select('skemas.*', 'asesi_skema.status as status_mandiri', 'asesi_skema.tanggal_selesai as tgl_selesai_mandiri', 'asesi_skema.attempt')
            ->get();

        $hasilUjikom = [];

        foreach ($asesiSkemas as $skema) {
            // 1. Pendaftaran & Verifikasi
            $isStep1Completed = ($asesi->status === 'approved');
            $stepPendaftaran = [
                'name' => 'Pendaftaran & Verifikasi',
                'status' => $isStep1Completed ? 'completed' : 'pending',
                'label' => $isStep1Completed ? 'Terverifikasi' : 'Menunggu Verifikasi',
                'description' => $isStep1Completed 
                    ? 'Berkas pendaftaran telah diverifikasi oleh admin.'
                    : 'Berkas pendaftaran sedang dalam proses verifikasi oleh admin.'
            ];

            // 2. Asesmen Mandiri
            $isMandiriSelesai = ($skema->status_mandiri === 'selesai');
            $isStep2Completed = $isMandiriSelesai;
            $stepMandiri = [
                'name' => 'Asesmen Mandiri (FR.APL.02)',
                'status' => $isStep2Completed ? 'completed' : 'pending',
                'label' => $isMandiriSelesai ? 'Selesai' : 'Belum Selesai',
                'description' => $isMandiriSelesai 
                    ? 'Asesi telah menyelesaikan pengisian form asesmen mandiri.'
                    : 'Asesi belum mengisi form asesmen mandiri.'
            ];

            // 3. Jadwal Ujikom
            $jadwal = \DB::table('jadwal_peserta')
                ->join('jadwal_ujikom', 'jadwal_ujikom.id', '=', 'jadwal_peserta.jadwal_id')
                ->where('jadwal_peserta.asesi_nik', $asesi->NIK)
                ->where('jadwal_ujikom.skema_id', $skema->id)
                ->where('jadwal_peserta.attempt', $skema->attempt)
                ->select('jadwal_ujikom.*')
                ->first();

            $isJadwalSelesai = (bool)$jadwal;
            $isStep3Completed = $isJadwalSelesai && $isStep2Completed;
            
            $jadwalDesc = 'Menunggu penjadwalan uji kompetensi dari admin/asesor.';
            if ($isJadwalSelesai) {
                $tukName = \DB::table('tuk')->where('id', $jadwal->tuk_id)->value('nama_tuk') ?? 'TUK';
                $tglMulai = $jadwal->tanggal_mulai ? \Carbon\Carbon::parse($jadwal->tanggal_mulai)->locale('id')->isoFormat('D MMMM YYYY') : '-';
                $jadwalDesc = 'Jadwal: ' . $tglMulai . ' di ' . $tukName;
            }

            $stepJadwal = [
                'name' => 'Jadwal Uji Kompetensi',
                'status' => $isStep3Completed ? 'completed' : 'pending',
                'label' => $isJadwalSelesai ? 'Sudah Dijadwalkan' : 'Belum Dijadwalkan',
                'description' => $jadwalDesc
            ];

            // 4. Persetujuan Asesmen
            $useNik = \Illuminate\Support\Facades\Schema::hasColumn('persetujuan_asesmen', 'asesi_nik');
            $persetujuan = \DB::table('persetujuan_asesmen')
                ->where('nomor_skema', $skema->nomor_skema)
                ->where('attempt', $skema->attempt)
                ->where(function($q) use ($asesi, $useNik) {
                    $q->where('nama_asesi', $asesi->nama);
                    if ($useNik) {
                        $q->orWhere('asesi_nik', $asesi->NIK);
                    }
                })
                ->first();

            $isPersetujuanSelesai = $persetujuan && !empty($persetujuan->ttd_asesi_nama) && !empty($persetujuan->ttd_asesor_nama);
            $isStep4Completed = $isPersetujuanSelesai && $isStep3Completed;
            
            $stepPersetujuan = [
                'name' => 'Persetujuan Asesmen (FR.APL.03)',
                'status' => $isStep4Completed ? 'completed' : 'pending',
                'label' => $isPersetujuanSelesai ? 'Selesai & Ditandatangani' : 'Belum Ditandatangani',
                'description' => $isPersetujuanSelesai 
                    ? 'Persetujuan asesmen telah disepakati dan ditandatangani oleh Asesi dan Asesor.'
                    : 'Dokumen persetujuan asesmen belum selesai ditandatangani.'
            ];

            // 5. Penilaian / Ceklis Observasi
            $ceklis = \DB::table('ceklis_observasi_aktivitas_praktiks')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skema->id)
                ->where('attempt', $skema->attempt)
                ->first();

            $isPenilaianSelesai = $ceklis && !empty($ceklis->ttd_asesi_file) && !empty($ceklis->ttd_asesor_file);
            $isStep5Completed = $isPenilaianSelesai && $isStep4Completed;
            
            $penilaianLabel = 'Belum Dinilai';
            $penilaianDesc = 'Menunggu penilaian observasi praktik dari Asesor.';
            if ($ceklis) {
                if ($isPenilaianSelesai) {
                    $penilaianLabel = 'Selesai & Ditandatangani';
                    $penilaianDesc = 'Proses observasi praktik telah dinilai dan disetujui kedua belah pihak.';
                } elseif (!empty($ceklis->ttd_asesor_file)) {
                    $penilaianLabel = 'Menunggu Tanda Tangan Asesi';
                    $penilaianDesc = 'Asesor telah menilai. Menunggu tanda tangan Asesi.';
                }
            }

            $stepPenilaian = [
                'name' => 'Penilaian & Ceklis Observasi (FR.IA.01)',
                'status' => $isStep5Completed ? 'completed' : 'pending',
                'label' => $penilaianLabel,
                'description' => $penilaianDesc
            ];

            // 6. Rekaman Asesmen
            $rekaman = \DB::table('rekaman_asesmen_kompetensi')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skema->id)
                ->where('attempt', $skema->attempt)
                ->first();

            $isRekamanSelesai = $rekaman && !empty($rekaman->ttd_asesi_file) && !empty($rekaman->ttd_asesor_file);
            $isStep6Completed = $isRekamanSelesai && $isStep5Completed;

            $rekamanLabel = 'Belum Diisi';
            $rekamanDesc = 'Menunggu pengisian rekaman asesmen dari Asesor.';
            if ($rekaman) {
                if ($isRekamanSelesai) {
                    $rekamanLabel = 'Selesai & Ditandatangani';
                    $rekamanDesc = 'Rekaman asesmen kompetensi telah ditandatangani oleh kedua belah pihak.';
                } elseif (!empty($rekaman->ttd_asesor_file)) {
                    $rekamanLabel = 'Menunggu Tanda Tangan Asesi';
                    $rekamanDesc = 'Asesor telah mengisi rekaman asesmen. Menunggu tanda tangan Asesi.';
                }
            }

            $stepRekaman = [
                'name' => 'Rekaman Asesmen (FR.AK.02)',
                'status' => $isStep6Completed ? 'completed' : 'pending',
                'label' => $rekamanLabel,
                'description' => $rekamanDesc
            ];

            // 7. Nilai Asesor
            $nilaiExists = \DB::table('asesor_nilai_elemens')
                ->where('asesi_nik', $asesi->NIK)
                ->where('skema_id', $skema->id)
                ->exists();
            $isStep7Completed = $nilaiExists && $isStep6Completed;
            
            $stepNilai = [
                'name' => 'Nilai Asesor',
                'status' => $isStep7Completed ? 'completed' : 'pending',
                'label' => $nilaiExists ? 'Selesai' : 'Belum Dinilai',
                'description' => $nilaiExists
                    ? 'Asesor telah menginput nilai kompetensi.'
                    : 'Asesor belum menginput nilai kompetensi.'
            ];

            $allCompleted = $isStep7Completed;

            $hasilUjikom[] = (object)[
                'skema_id' => $skema->id,
                'nama_skema' => $skema->nama_skema,
                'nomor_skema' => $skema->nomor_skema,
                'steps' => [$stepPendaftaran, $stepMandiri, $stepJadwal, $stepPersetujuan, $stepPenilaian, $stepRekaman, $stepNilai],
                'all_completed' => $allCompleted,
                'ceklis' => $ceklis,
                'rekaman' => $rekaman,
                'jadwal' => $jadwal,
                'persetujuan' => $persetujuan,
                'is_jadwal_selesai' => $isJadwalSelesai,
                'is_persetujuan_selesai' => $isPersetujuanSelesai,
                'is_nilai_selesai' => $nilaiExists,
                'rekomendasi' => $ceklis ? $ceklis->rekomendasi : null,
                'tanggal_ceklis' => $ceklis && $ceklis->ttd_asesi_tanggal ? \Carbon\Carbon::parse($ceklis->ttd_asesi_tanggal)->locale('id')->isoFormat('D MMMM YYYY') : null,
                'asesor_nama' => $ceklis && $ceklis->ttd_asesor_nama ? $ceklis->ttd_asesor_nama : null,
            ];
        }

        $hasilUjikom = collect($hasilUjikom);
        
        return view('admin.asesi.verifikasi-detail', compact('asesi', 'hasilUjikom'));
    }

    /**
     * Approve an asesi registration.
     */
    public function approve(Request $request, $nik)
    {
        $validated = $request->validate([
            'tanda_tangan_admin' => ['required', 'string', 'regex:/^data:image\/png;base64,[A-Za-z0-9+\/=]+$/'],
            'verifikasi_bukti_persyaratan_dasar' => 'nullable|string',
            'verifikasi_bukti_administratif' => 'nullable|string',
        ], [
            'tanda_tangan_admin.required' => 'Tanda tangan admin wajib diisi.',
            'tanda_tangan_admin.regex' => 'Format tanda tangan admin tidak valid.',
        ]);

        $asesi = Asesi::findOrFail($nik);
        $verifikasiBuktiPersyaratanDasar = $this->decodeChecklistPayload($request->input('verifikasi_bukti_persyaratan_dasar'));
        $verifikasiBuktiAdministratif = $this->decodeChecklistPayload($request->input('verifikasi_bukti_administratif'));
        
        // Generate unique no_reg based on birth date and index
        $noReg = $this->generateNoReg($asesi);
        
        // Create plain password (same as no_reg)
        $plainPassword = $noReg;
        
        // Update asesi status and no_reg
        $updatePayload = [
            'no_reg' => $noReg,
            'status' => 'approved',
            'catatan_admin' => $request->input('catatan_admin'),
            'verified_at' => now(),
            'verified_by' => auth('admin')->id(),
            'tanda_tangan_admin' => $validated['tanda_tangan_admin'],
            'tanggal_tanda_tangan_admin' => now(),
        ];

        if (Schema::hasColumn('asesi', 'verifikasi_bukti_persyaratan_dasar') && Schema::hasColumn('asesi', 'verifikasi_bukti_administratif')) {
            $updatePayload['verifikasi_bukti_persyaratan_dasar'] = $verifikasiBuktiPersyaratanDasar;
            $updatePayload['verifikasi_bukti_administratif'] = $verifikasiBuktiAdministratif;
        }

        $asesi->update($updatePayload);
        
        // Check if account already exists (NIK-based flow)
        $existingAccount = \App\Models\Account::where('NIK', $asesi->NIK)->first();
        
        if ($existingAccount) {
            // Account already exists (created by admin with NIK), no need to create again
            $plainPassword = null;
        } else {
            // Old flow: create account for asesi to login
            $plainPassword = $noReg;
            \App\Models\Account::create([
                'id' => $noReg,
                'NIK' => $asesi->NIK,
                'password' => $plainPassword,
                'role' => 'asesi',
            ]);
        }
        
        // Email notification dispatch removed per request
        
        return redirect()->route('admin.asesi.verifikasi')
            ->with('success', 'Pendaftaran asesi ' . $asesi->nama . ' telah disetujui dan akun login telah dibuat!');
    }

    /**
     * Generate unique no_reg for asesi based on birth date and sequential index.
     * Format: YYYYMMDD + 3-digit sequential number (e.g., 20000101001)
     */
    private function generateNoReg(Asesi $asesi): string
    {
        // Get birth date or use current date if not available
        if ($asesi->tanggal_lahir) {
            // Convert to date string: YYYYMMDD
            $dateObj = $asesi->tanggal_lahir;
            $birthDate = date('Ymd', strtotime($dateObj));
        } else {
            $birthDate = date('Ymd');
        }
        
        // Find the highest existing index for this birth date
        $prefix = $birthDate;
        $lastNoReg = \App\Models\Asesi::where('no_reg', 'like', $prefix . '%')
            ->orderBy('no_reg', 'desc')
            ->value('no_reg');
        
        if ($lastNoReg) {
            // Extract the sequential number and increment
            $lastIndex = (int) substr($lastNoReg, -3);
            $newIndex = $lastIndex + 1;
        } else {
            // First registration for this birth date
            $newIndex = 1;
        }
        
        // Format: YYYYMMDD + 3-digit index (001, 002, etc.)
        return $prefix . str_pad($newIndex, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Decode checklist payload from a hidden JSON field.
     */
    private function decodeChecklistPayload(?string $payload): ?array
    {
        if (empty($payload)) {
            return null;
        }

        $decoded = json_decode($payload, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return null;
        }

        return $decoded;
    }

    /**
     * Build a rejection note from checklist items that are not compliant.
     */
    private function buildRejectionChecklistNote(?array $persyaratanDasar, ?array $administratif): string
    {
        $issues = [];

        foreach ([
            'Bukti Persyaratan Dasar Pemohon' => $persyaratanDasar,
            'Bukti Administratif' => $administratif,
        ] as $sectionLabel => $items) {
            if (empty($items) || !is_array($items)) {
                continue;
            }

            $sectionIssues = [];

            foreach ($items as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $status = (string) ($item['status'] ?? '');
                if (!in_array($status, ['tidak_memenuhi', 'tidak_ada'], true)) {
                    continue;
                }

                $label = trim((string) ($item['label'] ?? $item['name'] ?? $item['nama'] ?? 'Item tidak dikenal'));
                $statusLabel = $status === 'tidak_ada' ? 'tidak ada' : 'tidak memenuhi syarat';
                $sectionIssues[] = $label . ' (' . $statusLabel . ')';
            }

            if ($sectionIssues) {
                $issues[] = $sectionLabel . ': ' . implode(', ', $sectionIssues);
            }
        }

        if (empty($issues)) {
            return '';
        }

        return "Berdasarkan hasil verifikasi, terdapat dokumen yang belum memenuhi ketentuan.\n" . implode("\n", $issues);
    }

    /**
     * Reject an asesi registration.
     */
    public function reject(Request $request, $nik)
    {
        $request->validate([
            'catatan_admin' => 'required|string',
            'reject_type'   => 'required|in:rejected,banned',
            'tanda_tangan_admin' => ['nullable', 'string', 'regex:/^data:image\/png;base64,[A-Za-z0-9+\/=]+$/'],
            'verifikasi_bukti_persyaratan_dasar' => 'nullable|string',
            'verifikasi_bukti_administratif' => 'nullable|string',
        ], [
            'catatan_admin.required' => 'Catatan penolakan wajib diisi.',
            'reject_type.required'   => 'Jenis penolakan wajib dipilih.',
            'reject_type.in'         => 'Jenis penolakan tidak valid.',
            'tanda_tangan_admin.regex' => 'Format tanda tangan admin tidak valid.',
        ]);
        
        $asesi = Asesi::findOrFail($nik);
        $rejectType = $request->input('reject_type', 'rejected'); // 'rejected' or 'banned'
        $verifikasiBuktiPersyaratanDasar = $this->decodeChecklistPayload($request->input('verifikasi_bukti_persyaratan_dasar'));
        $verifikasiBuktiAdministratif = $this->decodeChecklistPayload($request->input('verifikasi_bukti_administratif'));
        
        $existingCatatan = trim((string) $request->input('catatan_admin'));
        $autoCatatan = $rejectType === 'rejected'
            ? $this->buildRejectionChecklistNote($verifikasiBuktiPersyaratanDasar, $verifikasiBuktiAdministratif)
            : '';
        $combinedCatatan = trim(collect([$existingCatatan, $autoCatatan])->filter()->implode("\n\n"));

        $updateData = [
            'status'        => $rejectType,
            'catatan_admin' => $combinedCatatan !== '' ? $combinedCatatan : $existingCatatan,
            'verified_at'   => now(),
            'verified_by'   => auth('admin')->id(),
            'verifikasi_bukti_persyaratan_dasar' => $verifikasiBuktiPersyaratanDasar,
            'verifikasi_bukti_administratif' => $verifikasiBuktiAdministratif,
        ];

        if ($request->filled('tanda_tangan_admin')) {
            $updateData['tanda_tangan_admin'] = $request->input('tanda_tangan_admin');
            $updateData['tanggal_tanda_tangan_admin'] = now();
        }

        $asesi->update($updateData);
        
        // Email notification dispatch removed per request
        
        $message = $rejectType === 'banned'
            ? 'Pendaftaran asesi ' . $asesi->nama . ' ditolak secara permanen.'
            : 'Pendaftaran asesi ' . $asesi->nama . ' telah ditolak (dapat mendaftar ulang).';
        
        return redirect()->route('admin.asesi.verifikasi')
            ->with('success', $message);
    }

    /**
     * Delete registration data so asesi can refill from scratch.
     * Account login is intentionally kept.
     */
    public function deleteRegistration(Request $request, $nik)
    {
        $asesi = Asesi::with('buktiPendukung')->findOrFail($nik);

        if ($asesi->status === 'approved') {
            return redirect()->route('admin.asesi.verifikasi')
                ->with('error', 'Data asesi yang sudah disetujui tidak dapat dihapus dari menu verifikasi.');
        }

        DB::beginTransaction();
        try {
            $paths = [];

            if (!empty($asesi->pas_foto)) {
                $paths[] = $asesi->pas_foto;
            }

            foreach ($asesi->buktiPendukung as $dokumen) {
                if (!empty($dokumen->file_path)) {
                    $paths[] = $dokumen->file_path;
                }
            }

            $paths = array_values(array_unique($paths));
            foreach ($paths as $path) {
                Storage::disk('public')->delete($path);
            }

            // Remove pivot mappings first so the re-registration starts cleanly.
            $asesi->skemas()->detach();
            $asesi->buktiPendukung()->delete();
            $asesi->delete();

            DB::commit();

            return redirect()->route('admin.asesi.verifikasi')
                ->with('success', 'Data pendaftaran asesi berhasil dihapus. Asesi dapat mengisi ulang formulir pendaftaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Delete registration error: ' . $e->getMessage());

            return redirect()->route('admin.asesi.verifikasi')
                ->with('error', 'Gagal menghapus data pendaftaran: ' . $e->getMessage());
        }
    }

    /**
     * Reset password asesi ke NIK.
     */
    public function resetPassword($nik)
    {
        $asesi = Asesi::findOrFail($nik);
        $account = $asesi->account;

        if (!$account) {
            $account = Account::create([
                'id'       => $asesi->NIK,
                'NIK'      => $asesi->NIK,
                'nama'     => $asesi->nama,
                'password' => Hash::make($asesi->NIK),
                'role'     => 'asesi',
            ]);
            return redirect()->back()->with('success', 'Akun asesi ' . $asesi->nama . ' belum terdaftar. Akun baru telah otomatis dibuat dengan password NIK.');
        }

        $account->password = Hash::make($asesi->NIK);
        $account->save();

        return redirect()->back()->with('success', 'Password asesi ' . $asesi->nama . ' berhasil direset ke NIK.');
    }
}
