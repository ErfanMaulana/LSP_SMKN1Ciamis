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
     * Handle CSV / XLSX import
     */
    public function import(Request $request)
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
            return redirect()->route('admin.akun-asesi.index')
                ->with('error', 'Format file tidak didukung. Gunakan .xlsx atau .csv.');
        }

        $rows = $extension === 'xlsx'
            ? $this->readXlsx($uploadedFile->getRealPath())
            : $this->readCsv($uploadedFile->getRealPath());

        if ($rows === null) {
            return redirect()->route('admin.akun-asesi.index')
                ->with('error', 'Gagal membaca file. Pastikan file tidak rusak.');
        }

        $imported = 0;
        $skipped  = 0;
        $invalid  = 0;
        $errors   = [];
        $rowNum   = 0;

        foreach ($rows as $row) {
            $rowNum++;

            $nik  = trim((string) ($row[0] ?? ''));
            $nama = trim((string) ($row[1] ?? ''));

            // Skip header
            if (strtolower($nik) === 'nik') {
                continue;
            }

            // Skip empty rows
            if ($nik === '' && $nama === '') {
                continue;
            }

            // Detect scientific notation (e.g. "1.23457E+15") — this means Excel
            // converted the NIK to a number and precision is LOST. We must reject it
            // because the original 16-digit NIK cannot be recovered.
            if (preg_match('/[eE]/', $nik)) {
                $invalid++;
                $errors[] = "Baris {$rowNum}: NIK \"{$nik}\" dalam format scientific notation (presisi hilang). "
                    . "Gunakan template XLSX (kolom NIK sudah diformat sebagai Text) agar NIK tidak berubah.";
                continue;
            }

            // Remove trailing ".0" if a number is stored as decimal (e.g. "1234570000000000.0")
            if (preg_match('/^(\d{16})\.0*$/', $nik, $m)) {
                $nik = $m[1];
            }

            // Validate NIK (16 digits)
            if (!preg_match('/^\d{16}$/', $nik)) {
                $invalid++;
                $errors[] = "Baris {$rowNum}: NIK \"{$nik}\" tidak valid (harus 16 digit angka). "
                    . "Jika menggunakan CSV, format kolom NIK sebagai Text di Excel.";
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

        $msg = "Import selesai: {$imported} akun dibuat.";
        if ($skipped > 0) $msg .= " {$skipped} NIK sudah ada (dilewati).";
        if ($invalid > 0) $msg .= " {$invalid} baris tidak valid.";

        $type = $imported > 0 ? 'success' : 'error';

        return redirect()->route('admin.akun-asesi.index')
            ->with($type, $msg)
            ->with('import_errors', $errors);
    }

    /**
     * Download XLSX template (column A pre-formatted as Text to prevent scientific notation)
     */
    public function downloadTemplate()
    {
        $xlsxContent = $this->buildXlsxTemplate();

        return response($xlsxContent, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template_import_akun_asesi.xlsx"',
            'Content-Length'      => strlen($xlsxContent),
        ]);
    }

    /**
     * Read CSV file and return rows array
     */
    private function readCsv(string $path): ?array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) return null;

        $rows = [];
        while (($row = fgetcsv($handle)) !== false) {
            $rows[] = $row;
        }
        fclose($handle);
        return $rows;
    }

    /**
     * Read XLSX file without any third-party library.
     * Extracts xl/worksheets/sheet1.xml from the ZIP and parses cell values.
     * Cells with @t="s" (shared strings) are resolved from xl/sharedStrings.xml.
     * Cells with @s (number format) that map to a text format return the raw value
     * so 16-digit NIK stored as text is never converted to float.
     */
    private function readXlsx(string $path): ?array
    {
        if (!class_exists('ZipArchive')) return null;

        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) return null;

        // Load shared strings
        $sharedStrings = [];
        $ssXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($ssXml !== false) {
            $ss = @simplexml_load_string($ssXml);
            if ($ss) {
                foreach ($ss->si as $si) {
                    // Concatenate all <t> children (rich text support)
                    $text = '';
                    foreach ($si->xpath('.//t') as $t) {
                        $text .= (string) $t;
                    }
                    $sharedStrings[] = $text;
                }
            }
        }

        // Load sheet
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) return null;

        $sheet = @simplexml_load_string($sheetXml);
        if (!$sheet) return null;

        $rows = [];
        foreach ($sheet->sheetData->row as $row) {
            $rowData = [];
            foreach ($row->c as $cell) {
                $type  = (string) ($cell['t'] ?? '');
                $value = (string) ($cell->v ?? '');

                if ($type === 's') {
                    // shared string index
                    $value = $sharedStrings[(int) $value] ?? '';
                } elseif ($type === 'inlineStr') {
                    $value = (string) ($cell->is->t ?? '');
                } elseif ($type === 'str') {
                    $value = (string) ($cell->f ?? $value);
                }
                // type '' = number — keep as raw XML string.
                // For large integers (like NIK), the XML might store them as
                // "1234570000000000" or "1.23456789876543E+015". We use string
                // manipulation to convert scientific notation without float precision loss.
                if ($type === '' && stripos($value, 'E') !== false) {
                    $value = self::scientificToString($value);
                }

                $rowData[] = $value;
            }
            $rows[] = $rowData;
        }

        return $rows;
    }

    /**
     * Convert scientific notation string to full decimal string
     * without going through PHP float (which loses precision for >15 digits).
     *
     * Examples:
     *   "1.23456789876543E+015" → "1234567898765430"
     *   "1.23457E+15"           → "1234570000000000"
     */
    private static function scientificToString(string $val): string
    {
        if (!preg_match('/^([+-]?)(\d+)\.?(\d*)[eE]\+?(\d+)$/', trim($val), $m)) {
            return $val;
        }

        $sign    = $m[1];
        $intPart = $m[2];
        $decPart = $m[3];
        $exp     = (int) $m[4];

        $digits = $intPart . $decPart;
        $shift  = $exp - strlen($decPart);

        if ($shift >= 0) {
            return $sign . $digits . str_repeat('0', $shift);
        }

        // Decimal point needed (shouldn't happen for NIK but handle gracefully)
        $pos = strlen($digits) + $shift;
        if ($pos <= 0) {
            return $sign . '0.' . str_repeat('0', -$pos) . $digits;
        }
        return $sign . substr($digits, 0, $pos) . '.' . substr($digits, $pos);
    }

    /**
     * Build a minimal XLSX file with column A formatted as Text (@).
     * This prevents Excel from displaying 16-digit NIK in scientific notation.
     */
    private function buildXlsxTemplate(): string
    {
        // Shared strings
        $strings = ['NIK', 'Nama', '3204010101010001', 'Budi Santoso', '3204010101010002', 'Siti Rahayu'];
        $ssCount = count($strings);
        $ssItems = implode('', array_map(fn($s) => "<si><t xml:space=\"preserve\">{$s}</t></si>", $strings));

        $sharedStringsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . "<sst xmlns=\"http://schemas.openxmlformats.org/spreadsheetml/2006/main\" count=\"{$ssCount}\" uniqueCount=\"{$ssCount}\">"
            . $ssItems
            . '</sst>';

        // Styles — define a text number format (numFmtId=49 = "@" = Text)
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
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF0061A5"/></fgColor></fill>'
            . '</fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="3">'
            . '<xf numFmtId="0"  fontId="0" fillId="0" borderId="0" xfId="0"/>'          // 0: default
            . '<xf numFmtId="0"  fontId="1" fillId="2" borderId="0" xfId="0"/>'          // 1: header style
            . '<xf numFmtId="49" fontId="0" fillId="0" borderId="0" xfId="0" applyNumberFormat="1"/>' // 2: Text
            . '</cellXfs>'
            . '</styleSheet>';

        // Sheet — row 1: header (style 1), rows 2-3: data with s="2" (Text format) for NIK column
        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<sheetFormatPr defaultRowHeight="15"/>'
            . '<cols>'
            . '<col min="1" max="1" width="22" customWidth="1"/>'
            . '<col min="2" max="2" width="30" customWidth="1"/>'
            . '</cols>'
            . '<sheetData>'
            // Header row
            . '<row r="1"><c r="A1" t="s" s="1"><v>0</v></c><c r="B1" t="s" s="1"><v>1</v></c></row>'
            // Data row 2 — NIK as shared string (t="s") with Text style
            . '<row r="2"><c r="A2" t="s" s="2"><v>2</v></c><c r="B2" t="s"><v>3</v></c></row>'
            // Data row 3
            . '<row r="3"><c r="A3" t="s" s="2"><v>4</v></c><c r="B3" t="s"><v>5</v></c></row>'
            . '</sheetData>'
            . '</worksheet>';

        // workbook.xml
        $workbookXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" '
            . 'xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="template_import_asesi" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';

        // [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';

        // _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';

        // xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>'
            . '<Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';

        // Build ZIP in memory
        $tmpFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new \ZipArchive();
        $zip->open($tmpFile, \ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml',         $contentTypes);
        $zip->addFromString('_rels/.rels',                 $rels);
        $zip->addFromString('xl/workbook.xml',             $workbookXml);
        $zip->addFromString('xl/_rels/workbook.xml.rels',  $wbRels);
        $zip->addFromString('xl/worksheets/sheet1.xml',    $sheetXml);
        $zip->addFromString('xl/sharedStrings.xml',        $sharedStringsXml);
        $zip->addFromString('xl/styles.xml',               $stylesXml);
        $zip->close();

        $content = file_get_contents($tmpFile);
        unlink($tmpFile);

        return $content;
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
