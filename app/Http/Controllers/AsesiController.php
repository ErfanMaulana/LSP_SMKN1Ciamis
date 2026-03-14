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
use Maatwebsite\Excel\Facades\Excel;

class AsesiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asesi::with('jurusan');
        
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
        
        $asesi = $query->paginate(10)->appends($request->except('page'));
        
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

        // Akun role=asesi yang tidak punya data di tabel asesi
        $akunTanpaAsesi = Account::where('role', 'asesi')
            ->whereNotIn('NIK', Asesi::pluck('NIK')->filter())
            ->orderBy('nama')
            ->get();
        
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
    public function create()
    {
        $jurusan = Jurusan::with('kelasItems')->orderBy('nama_jurusan')->get();
        $skemaList = Skema::orderBy('jurusan_id')->orderBy('nama_skema')->get();
        return view('admin.asesi.create', compact('jurusan', 'skemaList'));
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
            'kewarganegaraan' => 'required|string|max:100',
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
        ]);

        $skemaId = $validated['skema_id'];
        unset($validated['skema_id']);

        // Normalize gender to DB enum values.
        if (($validated['jenis_kelamin'] ?? null) === 'L') {
            $validated['jenis_kelamin'] = 'Laki-laki';
        } elseif (($validated['jenis_kelamin'] ?? null) === 'P') {
            $validated['jenis_kelamin'] = 'Perempuan';
        }

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
        $asesi = Asesi::with(['jurusan', 'skemas'])->findOrFail($nik);
        
        return view('admin.asesi.show', compact('asesi'));
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
     * Export data asesi aktivasi dengan filter jurusan + skema (bisa ditumpuk).
     */
    public function exportActivated(Request $request)
    {
        $validated = $request->validate([
            'jurusan' => 'nullable|array',
            'jurusan.*' => 'nullable|exists:jurusan,ID_jurusan',
            'skema' => 'nullable|array',
            'skema.*' => 'nullable|exists:skemas,id',
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
            '2007-05-12',
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
            if ($val === 'L') {
                return 'Laki-laki';
            }
            if ($val === 'P') {
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
            $tanggalLahir = $row->tanggal_lahir ? \Carbon\Carbon::parse($row->tanggal_lahir)->format('Y-m-d') : '';

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
        if (in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
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
            'rejected' => Asesi::where('status', 'rejected')->count(),
            'total' => Asesi::count(),
        ];
        
        // Get jurusan list for filter dropdown
        $jurusanList = Jurusan::orderBy('nama_jurusan')->get();
        
        // If AJAX request, return only table rows
        if ($request->ajax()) {
            return view('admin.asesi.partials.verifikasi-table-rows', compact('asesi'))->render();
        }
        
        return view('admin.asesi.verifikasi', compact('asesi', 'status', 'counts', 'jurusanList', 'perPage'));
    }

    /**
     * Show the verification detail page for a specific asesi.
     */
    public function showVerifikasi($nik)
    {
        $asesi = Asesi::with(['jurusan', 'buktiPendukung', 'verifiedBy'])
            ->findOrFail($nik);
        
        return view('admin.asesi.verifikasi-detail', compact('asesi'));
    }

    /**
     * Approve an asesi registration.
     */
    public function approve(Request $request, $nik)
    {
        $asesi = Asesi::findOrFail($nik);
        
        // Generate unique no_reg based on birth date and index
        $noReg = $this->generateNoReg($asesi);
        
        // Create plain password (same as no_reg)
        $plainPassword = $noReg;
        
        // Update asesi status and no_reg
        $asesi->update([
            'no_reg' => $noReg,
            'status' => 'approved',
            'catatan_admin' => $request->input('catatan_admin'),
            'verified_at' => now(),
            'verified_by' => auth('admin')->id(),
        ]);
        
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
        
        // Send approval email if asesi has email
        if ($asesi->email) {
            try {
                \Mail::to($asesi->email)->send(new \App\Mail\AsesiApprovedMail($asesi, $noReg, $plainPassword));
            } catch (\Exception $e) {
                // Log error but don't fail the approval
                \Log::error('Failed to send approval email: ' . $e->getMessage());
            }
        }
        
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
     * Reject an asesi registration.
     */
    public function reject(Request $request, $nik)
    {
        $request->validate([
            'catatan_admin' => 'required|string',
            'reject_type'   => 'required|in:rejected,banned',
        ], [
            'catatan_admin.required' => 'Catatan penolakan wajib diisi.',
            'reject_type.required'   => 'Jenis penolakan wajib dipilih.',
            'reject_type.in'         => 'Jenis penolakan tidak valid.',
        ]);
        
        $asesi = Asesi::findOrFail($nik);
        $rejectType = $request->input('reject_type', 'rejected'); // 'rejected' or 'banned'
        
        $asesi->update([
            'status'        => $rejectType,
            'catatan_admin' => $request->input('catatan_admin'),
            'verified_at'   => now(),
            'verified_by'   => auth('admin')->id(),
        ]);
        
        // Send rejection email if asesi has email
        if ($asesi->email) {
            try {
                \Mail::to($asesi->email)->send(new \App\Mail\AsesiRejectedMail($asesi));
            } catch (\Exception $e) {
                \Log::error('Failed to send rejection email: ' . $e->getMessage());
            }
        }
        
        $message = $rejectType === 'banned'
            ? 'Pendaftaran asesi ' . $asesi->nama . ' ditolak secara permanen.'
            : 'Pendaftaran asesi ' . $asesi->nama . ' telah ditolak (dapat mendaftar ulang).';
        
        return redirect()->route('admin.asesi.verifikasi')
            ->with('success', $message);
    }

    /**
     * Bulk approve multiple pending asesi.
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'niks' => 'required|array|min:1',
            'niks.*' => 'string',
        ]);

        $approved = 0;
        $skipped  = 0;

        foreach ($request->input('niks') as $nik) {
            $asesi = Asesi::where('NIK', $nik)->where('status', 'pending')->first();
            if (!$asesi) { $skipped++; continue; }

            $noReg = $this->generateNoReg($asesi);

            $asesi->update([
                'no_reg'      => $noReg,
                'status'      => 'approved',
                'verified_at' => now(),
                'verified_by' => auth('admin')->id(),
            ]);

            $existingAccount = \App\Models\Account::where('NIK', $asesi->NIK)->first();
            $plainPassword = null;

            if (!$existingAccount) {
                $plainPassword = $noReg;
                \App\Models\Account::create([
                    'id'       => $noReg,
                    'NIK'      => $asesi->NIK,
                    'password' => $plainPassword,
                    'role'     => 'asesi',
                ]);
            }

            if ($asesi->email) {
                try {
                    \Mail::to($asesi->email)->send(new \App\Mail\AsesiApprovedMail($asesi, $noReg, $plainPassword));
                } catch (\Exception $e) {
                    \Log::error('Bulk approve email error: ' . $e->getMessage());
                }
            }

            $approved++;
        }

        $msg = $approved . ' asesi berhasil disetujui.';
        if ($skipped) $msg .= ' ' . $skipped . ' dilewati (bukan pending atau tidak ditemukan).';

        return redirect()->route('admin.asesi.verifikasi')->with('success', $msg);
    }

    /**
     * Bulk reject multiple pending asesi.
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'niks'          => 'required|array|min:1',
            'niks.*'        => 'string',
            'catatan_admin' => 'required|string',
            'reject_type'   => 'required|in:rejected,banned',
        ], [
            'catatan_admin.required' => 'Catatan penolakan wajib diisi.',
            'reject_type.required'   => 'Jenis penolakan wajib dipilih.',
        ]);

        $rejectType   = $request->input('reject_type');
        $catatanAdmin = $request->input('catatan_admin');
        $rejected = 0;
        $skipped  = 0;

        foreach ($request->input('niks') as $nik) {
            $asesi = Asesi::where('NIK', $nik)->where('status', 'pending')->first();
            if (!$asesi) { $skipped++; continue; }

            $asesi->update([
                'status'        => $rejectType,
                'catatan_admin' => $catatanAdmin,
                'verified_at'   => now(),
                'verified_by'   => auth('admin')->id(),
            ]);

            if ($asesi->email) {
                try {
                    \Mail::to($asesi->email)->send(new \App\Mail\AsesiRejectedMail($asesi));
                } catch (\Exception $e) {
                    \Log::error('Bulk reject email error: ' . $e->getMessage());
                }
            }

            $rejected++;
        }

        $label = $rejectType === 'banned' ? 'ditolak permanen' : 'ditolak';
        $msg   = $rejected . ' asesi berhasil ' . $label . '.';
        if ($skipped) $msg .= ' ' . $skipped . ' dilewati (bukan pending atau tidak ditemukan).';

        return redirect()->route('admin.asesi.verifikasi')->with('success', $msg);
    }
}
