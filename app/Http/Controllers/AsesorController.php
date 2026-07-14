<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Models\Account;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AsesorImport;


class AsesorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Asesor::with(['skemas', 'account']);
        
        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('ID_asesor', 'LIKE', "%{$search}%");
            });
        }
        
        // Skema filter
        if ($request->has('keahlian') && $request->keahlian != '') {
            $keahlian = $request->keahlian;
            $query->whereHas('skemas', function($q) use ($keahlian) {
                $q->where('skemas.id', $keahlian);
            });
        }

        // Card quick-filter
        $cardFilter = $request->get('card_filter', '');
        if ($cardFilter === 'with_skema') {
            $query->whereHas('skemas');
        } elseif ($cardFilter === 'without_skema') {
            $query->whereDoesntHave('skemas');
        }
        
        // Sort filter (A-Z or Z-A)
        $sortOrder = 'asc'; // default
        if ($request->has('sort') && $request->sort != '') {
            $sortOrder = $request->sort;
        }
        $query->orderBy('nama', $sortOrder);
        
        $asesor = $query->paginate(10)->appends($request->except('page'));

        $stats = [
            'total'           => Asesor::count(),
            'with_skema'      => Asesor::whereHas('skemas')->count(),
            'without_skema'   => Asesor::whereDoesntHave('skemas')->count(),
        ];

        $skemaList = Skema::orderBy('nama_skema')->get();

        // If AJAX request, return only table rows
        if ($request->ajax()) {
            return view('admin.asesor.partials.table-rows', compact('asesor'))->render();
        }

        return view('admin.asesor.index', compact('asesor', 'stats', 'skemaList', 'cardFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skema = Skema::orderBy('nama_skema')->get();
        return view('admin.asesor.create', compact('skema'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'skema_ids' => 'nullable|array',
            'skema_ids.*' => 'exists:skemas,id',
            'no_met'    => 'required|string|max:50|unique:asesor,no_met|unique:accounts,id',
            'max_asesi' => 'nullable|integer|min:1',
        ]);

        $asesor = Asesor::create([
            'nama'      => $validated['nama'],
            'no_met'    => $validated['no_met'] ?? null,
            'max_asesi' => $validated['max_asesi'] ?? null,
        ]);

        // Sync skemas
        if (!empty($validated['skema_ids'])) {
            $asesor->skemas()->sync($validated['skema_ids']);
        }

        // Always create account with no_met as login ID
        Account::create([
            'id'       => $validated['no_met'],
            'nama'     => $validated['nama'],
            'password' => Hash::make($validated['no_met']),
            'role'     => 'asesor',
        ]);

        return redirect()->route('admin.asesor.index')->with('success', 'Data Asesor berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($ID_asesor)
    {
        $asesor = Asesor::with('skemas')->findOrFail($ID_asesor);
        return view('admin.asesor.show', compact('asesor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ID_asesor)
    {
        $asesor = Asesor::with('skemas')->findOrFail($ID_asesor);
        $skema  = Skema::orderBy('nama_skema')->get();
        $selectedSkemaIds = $asesor->skemas->pluck('id')->toArray();
        return view('admin.asesor.edit', compact('asesor', 'skema', 'selectedSkemaIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $ID_asesor)
    {
        $asesor = Asesor::findOrFail($ID_asesor);

        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'skema_ids'   => 'nullable|array',
            'skema_ids.*' => 'exists:skemas,id',
            'no_met'      => 'nullable|string|max:50|unique:asesor,no_met,' . $asesor->ID_asesor . ',ID_asesor|unique:accounts,id,' . ($asesor->no_met ? $asesor->no_met : 'NULL'),
            'max_asesi'   => 'nullable|integer|min:1',
        ]);

        $oldNoMet = $asesor->no_met;
        $newNoMet = $validated['no_met'] ?? null;

        $asesor->update([
            'nama'      => $validated['nama'],
            'no_met'    => $newNoMet,
            'max_asesi' => $validated['max_asesi'] ?? null,
        ]);

        // Sync skemas
        $asesor->skemas()->sync($validated['skema_ids'] ?? []);

        // Sync account
        if ($newNoMet && $newNoMet !== $oldNoMet) {
            if ($oldNoMet) {
                Account::where('id', $oldNoMet)->where('role', 'asesor')->delete();
            }
            Account::create([
                'id'       => $newNoMet,
                'password' => Hash::make($newNoMet),
                'role'     => 'asesor',
            ]);
        } elseif (!$newNoMet && $oldNoMet) {
            Account::where('id', $oldNoMet)->where('role', 'asesor')->delete();
        }

        return redirect()->route('admin.asesor.index')->with('success', 'Data Asesor berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ID_asesor)
    {
        $asesor = Asesor::findOrFail($ID_asesor);
        $asesor->delete();

        return redirect()->route('admin.asesor.index')->with('success', 'Data Asesor berhasil dihapus!');
    }

    /**
     * Reset password asesor ke No. Met.
     */
    public function resetPassword($ID_asesor)
    {
        $asesor = Asesor::findOrFail($ID_asesor);
        if (!$asesor->no_met) {
            return redirect()->back()->with('error', 'Asesor tidak memiliki No. Met untuk digunakan sebagai password.');
        }

        $account = $asesor->account;
        if (!$account) {
            $account = Account::create([
                'id'       => $asesor->no_met,
                'nama'     => $asesor->nama,
                'password' => Hash::make($asesor->no_met),
                'role'     => 'asesor',
            ]);
            return redirect()->back()->with('success', 'Akun asesor ' . $asesor->nama . ' belum terdaftar. Akun baru telah otomatis dibuat dengan password No. Met.');
        }

        $account->password = Hash::make($asesor->no_met);
        $account->save();

        return redirect()->back()->with('success', 'Password asesor ' . $asesor->nama . ' berhasil direset ke No. Met.');
    }

    /**
     * Export data asesor ke XLSX.
     */
    public function exportAsesor(Request $request)
    {
        $query = Asesor::with('skemas')->orderBy('nama');

        // Filter skema
        $skemaIds = array_values(array_filter($request->input('skema', [])));
        if (!empty($skemaIds)) {
            $query->whereHas('skemas', function ($q) use ($skemaIds) {
                $q->whereIn('skemas.id', $skemaIds);
            });
        }

        $rows = $query->get();

        $xlsxContent = $this->buildAsesorExportXlsx($rows);

        return response($xlsxContent, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="export_data_asesor.xlsx"',
            'Content-Length'      => strlen($xlsxContent),
        ]);
    }

    /**
     * Build XLSX export asesor: No, Nama Asesor, No. Met, Skema.
     */
    private function buildAsesorExportXlsx($rows): string
    {
        $headers = ['No', 'Nama Asesor', 'No. Met', 'Skema'];

        $sharedStrings = [];
        $sharedMap     = [];

        $addStr = function (string $value) use (&$sharedStrings, &$sharedMap): int {
            if (!array_key_exists($value, $sharedMap)) {
                $sharedMap[$value] = count($sharedStrings);
                $sharedStrings[]   = $value;
            }
            return $sharedMap[$value];
        };

        $escXml = function (string $v): string {
            return htmlspecialchars($v, ENT_QUOTES | ENT_XML1, 'UTF-8');
        };

        // pre-load headers
        foreach ($headers as $h) { $addStr($h); }

        $rowsXml = '<row r="1">'
            . '<c r="A1" t="s" s="1"><v>' . $addStr('No') . '</v></c>'
            . '<c r="B1" t="s" s="1"><v>' . $addStr('Nama Asesor') . '</v></c>'
            . '<c r="C1" t="s" s="1"><v>' . $addStr('No. Met') . '</v></c>'
            . '<c r="D1" t="s" s="1"><v>' . $addStr('Skema') . '</v></c>'
            . '</row>';

        foreach ($rows as $i => $row) {
            $r     = $i + 2;
            $skema = $row->skemas->pluck('nama_skema')->implode(', ');

            $values = [
                (string) ($i + 1),
                (string) ($row->nama ?? ''),
                (string) ($row->no_met ?? ''),
                $skema,
            ];

            $rowsXml .= '<row r="' . $r . '">';
            foreach ($values as $ci => $val) {
                $col      = chr(65 + $ci);
                $cellRef  = $col . $r;
                $si       = $addStr($val);
                $rowsXml .= '<c r="' . $cellRef . '" t="s"><v>' . $si . '</v></c>';
            }
            $rowsXml .= '</row>';
        }

        $ssItems = implode('', array_map(function ($s) use ($escXml) {
            return '<si><t xml:space="preserve">' . $escXml($s) . '</t></si>';
        }, $sharedStrings));

        $sharedStringsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="' . count($sharedStrings) . '" uniqueCount="' . count($sharedStrings) . '">'
            . $ssItems . '</sst>';

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
            . '<cellXfs count="2">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0"/>'
            . '</cellXfs>'
            . '</styleSheet>';

        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetFormatPr defaultRowHeight="15"/>'
            . '<cols>'
            . '<col min="1" max="1" width="8"  customWidth="1"/>'
            . '<col min="2" max="2" width="28"  customWidth="1"/>'
            . '<col min="3" max="3" width="16"  customWidth="1"/>'
            . '<col min="4" max="4" width="40"  customWidth="1"/>'
            . '</cols>'
            . '<sheetData>' . $rowsXml . '</sheetData>'
            . '</worksheet>';

        $workbookXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="export_data_asesor" sheetId="1" r:id="rId1"/></sheets>'
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
        $zip     = new \ZipArchive();
        $zip->open($tmpFile, \ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml',        $contentTypes);
        $zip->addFromString('_rels/.rels',                $rels);
        $zip->addFromString('xl/workbook.xml',            $workbookXml);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);
        $zip->addFromString('xl/worksheets/sheet1.xml',   $sheetXml);
        $zip->addFromString('xl/sharedStrings.xml',       $sharedStringsXml);
        $zip->addFromString('xl/styles.xml',              $stylesXml);
        $zip->close();

        $content = file_get_contents($tmpFile);
        unlink($tmpFile);

        return $content;
    }

    /**
     * Handle CSV / XLSX import for Asesor
     */
    public function importAsesor(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
        ], [
            'file.required' => 'File wajib diunggah.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        $uploadedFile = $request->file('file');
        $extension    = strtolower($uploadedFile->getClientOriginalExtension());

        if (!in_array($extension, ['csv', 'xlsx', 'txt'])) {
            return redirect()->route('admin.asesor.index')
                ->with('error', 'Format file tidak didukung. Gunakan .xlsx atau .csv.');
        }

        $import = new AsesorImport();

        try {
            Excel::import($import, $uploadedFile);
        } catch (\Exception $e) {
            return redirect()->route('admin.asesor.index')
                ->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }

        $msg = "Import selesai: {$import->imported} asesor berhasil di-import.";
        if ($import->skipped > 0) $msg .= " {$import->skipped} No. Met sudah ada (dilewati).";
        if ($import->invalid > 0) $msg .= " {$import->invalid} baris tidak valid.";

        $type = $import->imported > 0 ? 'success' : 'error';

        return redirect()->route('admin.asesor.index')
            ->with($type, $msg)
            ->with('import_errors', $import->errors);
    }

    /**
     * Download XLSX template for importing Asesor
     */
    public function downloadImportTemplate()
    {
        $xlsxContent = $this->buildAsesorXlsxTemplate();

        return response($xlsxContent, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template_import_asesor.xlsx"',
            'Content-Length'      => strlen($xlsxContent),
        ]);
    }

    /**
     * Build minimal XLSX template for importing Asesor: Nama Asesor, No. Met, Skema
     */
    private function buildAsesorXlsxTemplate(): string
    {
        $strings = [
            'Nama Asesor',
            'No. Met',
            'Skema',
            'Andi Saputra',
            'MET-001',
            'Skema A, Skema B'
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
            . '<xf numFmtId="0"  fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0"  fontId="1" fillId="2" borderId="0" xfId="0"/>'
            . '<xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>'
            . '</cellXfs>'
            . '</styleSheet>';

        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetFormatPr defaultRowHeight="15"/>'
            . '<cols>'
            . '<col min="1" max="1" width="28" customWidth="1"/>'
            . '<col min="2" max="2" width="20" customWidth="1"/>'
            . '<col min="3" max="3" width="36" customWidth="1"/>'
            . '</cols>'
            . '<sheetData>'
            . '<row r="1">'
            . '<c r="A1" t="s" s="1"><v>0</v></c>'
            . '<c r="B1" t="s" s="1"><v>1</v></c>'
            . '<c r="C1" t="s" s="1"><v>2</v></c>'
            . '</row>'
            . '<row r="2">'
            . '<c r="A2" t="s"><v>3</v></c>'
            . '<c r="B2" t="s" s="2"><v>4</v></c>'
            . '<c r="C2" t="s"><v>5</v></c>'
            . '</row>'
            . '</sheetData>'
            . '</worksheet>';

        $workbookXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="template_import_asesor" sheetId="1" r:id="rId1"/></sheets>'
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
}


