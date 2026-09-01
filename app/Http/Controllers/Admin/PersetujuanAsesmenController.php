<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\PersetujuanAsesmen;
use App\Models\Skema;
use App\Models\Tuk;
use Illuminate\Http\Request;

class PersetujuanAsesmenController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $skemaFilter = $request->get('skema');

        $items = PersetujuanAsesmen::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul_skema', 'like', "%{$search}%")
                        ->orWhere('nomor_skema', 'like', "%{$search}%")
                        ->orWhere('nama_asesor', 'like', "%{$search}%")
                        ->orWhere('nama_asesi', 'like', "%{$search}%");
                });
            })
            ->when($skemaFilter, function ($query) use ($skemaFilter) {
                $query->where('judul_skema', $skemaFilter);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $skemaList = Skema::query()
            ->orderBy('nama_skema')
            ->pluck('nama_skema');

        $stats = [
            'total_skema' => PersetujuanAsesmen::distinct('judul_skema')->count('judul_skema'),
            'total_asesi' => PersetujuanAsesmen::distinct('nama_asesi')->count('nama_asesi'),
            'total_asesor' => PersetujuanAsesmen::distinct('nama_asesor')->count('nama_asesor'),
        ];

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'rows' => view('admin.persetujuan-asesmen.partials.table-rows', compact('items'))->render(),
                'pagination' => $items->hasPages() ? (string) $items->links() : '',
            ]);
        }

        return view('admin.persetujuan-asesmen.index', compact('items', 'search', 'skemaList', 'skemaFilter', 'stats'));
    }

    public function create()
    {
        $defaults = $this->defaultContent();
        $skemaList = Skema::query()
            ->orderBy('nama_skema')
            ->get(['id', 'nama_skema', 'nomor_skema']);
        $tukList = Tuk::query()
            ->orderBy('nama_tuk')
            ->get(['id', 'nama_tuk', 'tipe_tuk', 'kota', 'status']);

        return view('admin.persetujuan-asesmen.create', compact('defaults', 'skemaList', 'tukList'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        PersetujuanAsesmen::create($data);

        return redirect()->route('admin.persetujuan-asesmen.index')
            ->with('success', 'Data persetujuan asesmen berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = PersetujuanAsesmen::findOrFail($id);
        return view('admin.persetujuan-asesmen.show', compact('item'));
    }

    public function edit($id)
    {
        $item = PersetujuanAsesmen::findOrFail($id);
        $skemaList = Skema::query()
            ->orderBy('nama_skema')
            ->get(['id', 'nama_skema', 'nomor_skema']);
        $tukList = Tuk::query()
            ->orderBy('nama_tuk')
            ->get(['id', 'nama_tuk', 'tipe_tuk', 'kota', 'status']);

        return view('admin.persetujuan-asesmen.edit', compact('item', 'skemaList', 'tukList'));
    }

    public function update(Request $request, $id)
    {
        $item = PersetujuanAsesmen::findOrFail($id);
        $data = $this->validatedData($request);
        $item->update($data);

        return redirect()->route('admin.persetujuan-asesmen.index')
            ->with('success', 'Data persetujuan asesmen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = PersetujuanAsesmen::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.persetujuan-asesmen.index')
            ->with('success', 'Data persetujuan asesmen berhasil dihapus.');
    }

    public function participantsBySkema(Request $request)
    {
        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
        ]);

        $skemaId = (int) $validated['skema_id'];

        $asesiList = Asesi::query()
            ->whereHas('skemas', function ($query) use ($skemaId) {
                $query->where('skemas.id', $skemaId);
            })
            ->orderBy('nama')
            ->get(['NIK', 'nama'])
            ->map(function ($asesi) {
                return [
                    'id' => (string) $asesi->NIK,
                    'nama' => $asesi->nama,
                ];
            })
            ->values();

        $asesorList = Asesor::query()
            ->whereHas('skemas', function ($query) use ($skemaId) {
                $query->where('skemas.id', $skemaId);
            })
            ->orderBy('nama')
            ->get(['ID_asesor', 'nama'])
            ->map(function ($asesor) {
                return [
                    'id' => (string) $asesor->ID_asesor,
                    'nama' => $asesor->nama,
                ];
            })
            ->values();

        return response()->json([
            'asesi' => $asesiList,
            'asesor' => $asesorList,
        ]);
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'kode_form' => 'required|string|max:20',
            'judul_form' => 'required|string|max:255',
            'pengantar' => 'required|string',
            'kategori_skema' => 'nullable|string|max:100',
            'judul_skema' => 'required|string|max:255',
            'nomor_skema' => 'required|string|max:255',
            'tuk' => 'nullable|string|max:255',
            'nama_asesor' => 'required|string|max:255',
            'nama_asesi' => 'required|string|max:255',
            'bukti_verifikasi_portofolio' => 'nullable|boolean',
            'bukti_reviu_produk' => 'nullable|boolean',
            'bukti_observasi_langsung' => 'nullable|boolean',
            'bukti_kegiatan_terstruktur' => 'nullable|boolean',
            'bukti_pertanyaan_lisan' => 'nullable|boolean',
            'bukti_pertanyaan_tertulis' => 'nullable|boolean',
            'bukti_pertanyaan_wawancara' => 'nullable|boolean',
            'bukti_lainnya' => 'nullable|boolean',
            'bukti_lainnya_keterangan' => 'nullable|string|max:255',
            'hari_tanggal' => 'nullable|string|max:120',
            'waktu' => 'nullable|string|max:120',
            'tuk_pelaksanaan' => 'nullable|string|max:255',
            'pernyataan_asesi_1' => 'required|string',
            'pernyataan_asesor' => 'required|string',
            'pernyataan_asesi_2' => 'required|string',
            'ttd_asesor_nama' => 'nullable|string|max:255',
            'ttd_asesor_tanggal' => 'nullable|date',
            'ttd_asesi_nama' => 'nullable|string|max:255',
            'ttd_asesi_tanggal' => 'nullable|date',
            'catatan_footer' => 'nullable|string|max:255',
        ]);

        foreach ([
            'bukti_verifikasi_portofolio',
            'bukti_reviu_produk',
            'bukti_observasi_langsung',
            'bukti_kegiatan_terstruktur',
            'bukti_pertanyaan_lisan',
            'bukti_pertanyaan_tertulis',
            'bukti_pertanyaan_wawancara',
            'bukti_lainnya',
        ] as $field) {
            $data[$field] = $request->boolean($field);
        }

        return $data;
    }

    private function defaultContent(): array
    {
        return [
            'kode_form' => 'FR.AK.01.',
            'judul_form' => 'PERSETUJUAN ASESMEN DAN KERAHASIAAN',
            'pengantar' => 'Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen',
            'kategori_skema' => 'KKNI/Okupasi/Klaster',
            'tuk' => 'Sewaktu/Tempat Kerja/Mandiri*',
            'pernyataan_asesi_1' => 'Bahwa saya telah mendapatkan penjelasan terkait hak dan prosedur banding asesmen dari asesor.',
            'pernyataan_asesor' => 'Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.',
            'pernyataan_asesi_2' => 'Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.',
            'catatan_footer' => '* Coret yang tidak perlu',
        ];
    }

    public function export($id)
    {
        $templatePath = storage_path('app/template/fr_ak_01.docx');
        if (file_exists($templatePath)) {
            return $this->exportWord($id);
        }

        $item = PersetujuanAsesmen::findOrFail($id);
        $skema = Skema::where('nomor_skema', $item->nomor_skema)->first();

        $logoPath = public_path('images/lsp.png');
        $logoDataUri = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $ttdAsesiDataUri = null;
        if (!empty($item->ttd_asesi_file)) {
            if (str_starts_with($item->ttd_asesi_file, 'data:image')) {
                $ttdAsesiDataUri = $item->ttd_asesi_file;
            } else {
                $filePath = storage_path('app/public/' . ltrim($item->ttd_asesi_file, '/'));
                if (file_exists($filePath)) {
                    $mime = mime_content_type($filePath) ?: 'image/png';
                    $ttdAsesiDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($filePath));
                }
            }
        }

        $ttdAsesorDataUri = null;
        if (!empty($item->ttd_asesor_file)) {
            if (str_starts_with($item->ttd_asesor_file, 'data:image')) {
                $ttdAsesorDataUri = $item->ttd_asesor_file;
            } else {
                $filePath = storage_path('app/public/' . ltrim($item->ttd_asesor_file, '/'));
                if (file_exists($filePath)) {
                    $mime = mime_content_type($filePath) ?: 'image/png';
                    $ttdAsesorDataUri = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($filePath));
                }
            }
        }

        $asesor = null;
        if (!empty($item->nama_asesor)) {
            $asesor = Asesor::where('nama', $item->nama_asesor)->first();
        }
        if (!$asesor && !empty($item->reviewed_by)) {
            $asesor = Asesor::where('no_met', (string) $item->reviewed_by)->first();
        }

        $html = view('persetujuan-asesmen.export-docx', [
            'item' => $item,
            'skema' => $skema,
            'asesor' => $asesor,
            'logoPath' => $logoPath,
            'logoDataUri' => $logoDataUri,
            'ttdAsesiDataUri' => $ttdAsesiDataUri,
            'ttdAsesorDataUri' => $ttdAsesorDataUri,
        ])->render();

        $fileSkema = preg_replace('/[^A-Za-z0-9\-]+/', '-', (string) ($skema?->nomor_skema ?? $item->nomor_skema));
        $fileName = 'FR.AK.01-' . ($item->asesi_nik ?: 'persetujuan') . '-' . trim($fileSkema, '-') . '.doc';

        return response($html, 200, [
            'Content-Type' => 'application/msword; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Export FR.AK.01 using PHPWord TemplateProcessor with .docx template
     */
    public function exportWord($id)
    {
        $item = PersetujuanAsesmen::findOrFail($id);
        $skema = Skema::where('nomor_skema', $item->nomor_skema)->first();

        $templatePath = storage_path('app/template/fr_ak_01.docx');
        if (!file_exists($templatePath)) {
            abort(404, 'Template fr_ak_01.docx tidak ditemukan.');
        }

        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // --- Basic info ---
        $templateProcessor->setValue('judul_skema', e($item->judul_skema ?? ($skema->nama_skema ?? '-')));
        $templateProcessor->setValue('nomor_skema', e($item->nomor_skema ?? ($skema->nomor_skema ?? '-')));
        $templateProcessor->setValue('nama_asesor', e($item->nama_asesor ?? '-'));
        $templateProcessor->setValue('nama_asesi', e($item->nama_asesi ?? '-'));

        // --- Skema Type & TUK with strikethrough on non-selected options ---
        $skemaType = $item->kategori_skema ?? ($skema?->jenis_skema ?? null);
        $templateProcessor->setComplexValue('type_skema', $this->buildSkemaTypeTextRun($skemaType, $skema));

        $tukVal = $item->tuk ?? ($item->tuk_pelaksanaan ?? null);
        $templateProcessor->setComplexValue('tuk_sewaktu/tempatkerja/mandiri', $this->buildTukTextRun($tukVal, $item->tuk_pelaksanaan));

        // --- Bukti yang dikumpulkan (checkboxes) ---
        $check = '☑';
        $uncheck = '☐';

        $templateProcessor->setValue('portfolio', $item->bukti_verifikasi_portofolio ? $check : $uncheck);
        $templateProcessor->setValue('review_produk', $item->bukti_reviu_produk ? $check : $uncheck);
        $templateProcessor->setValue('observasi', $item->bukti_observasi_langsung ? $check : $uncheck);
        $templateProcessor->setValue('kegiatan_terstruktur', $item->bukti_kegiatan_terstruktur ? $check : $uncheck);
        $templateProcessor->setValue('pertanyaan_lisan', $item->bukti_pertanyaan_lisan ? $check : $uncheck);
        $templateProcessor->setValue('pertanyaan_tertulis', $item->bukti_pertanyaan_tertulis ? $check : $uncheck);
        $templateProcessor->setValue('lainnya', $item->bukti_lainnya ? $check : $uncheck);
        $templateProcessor->setValue('wawancara', $item->bukti_pertanyaan_wawancara ? $check : $uncheck);

        // --- Jadwal pelaksanaan ---
        $templateProcessor->setValue('hari/tanggal_jadwal', e($item->hari_tanggal ?? '-'));
        $templateProcessor->setValue('hari_tanggal_jadwal', e($item->hari_tanggal ?? '-'));
        $templateProcessor->setValue('waktu_jadwal', e($item->waktu ?? '-'));
        $templateProcessor->setValue('tuk', e($item->tuk_pelaksanaan ?? ($item->tuk ?? '-')));

        // --- Tanda tangan Asesor ---
        $ttdAsesorImage = $this->resolveSignatureImage($item->ttd_asesor_file);
        if ($ttdAsesorImage) {
            $templateProcessor->setImageValue('ttd_asesor', [
                'path' => $ttdAsesorImage,
                'width' => 150,
                'height' => 60,
                'ratio' => false,
            ]);
        } else {
            $templateProcessor->setValue('ttd_asesor', '');
        }
        $templateProcessor->setComplexValue('tanggal_ttd_asesor', $this->buildDateTextRun($item->ttd_asesor_tanggal));

        // --- Tanda tangan Asesi ---
        $ttdAsesiImage = $this->resolveSignatureImage($item->ttd_asesi_file);
        if ($ttdAsesiImage) {
            $templateProcessor->setImageValue('ttd_asesi', [
                'path' => $ttdAsesiImage,
                'width' => 150,
                'height' => 60,
                'ratio' => false,
            ]);
        } else {
            $templateProcessor->setValue('ttd_asesi', '');
        }
        $templateProcessor->setComplexValue('tanggal_ttd_asesi', $this->buildDateTextRun($item->ttd_asesi_tanggal));

        // --- Generate output file ---
        $fileSkema = preg_replace('/[^A-Za-z0-9\-]+/', '-', (string) ($skema?->nomor_skema ?? $item->nomor_skema));
        $fileName = 'FR.AK.01-' . ($item->asesi_nik ?: 'persetujuan') . '-' . trim($fileSkema, '-') . '.docx';

        $tempFile = storage_path('app/temp/' . uniqid('fr_ak_01_') . '.docx');
        if (!is_dir(dirname($tempFile))) {
            mkdir(dirname($tempFile), 0755, true);
        }
        $templateProcessor->saveAs($tempFile);

        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Build strikethrough TextRun for Skema Sertifikasi type (KKNI/Okupasi/Klaster)
     */
    private function buildSkemaTypeTextRun(?string $type, ?Skema $skema = null): \PhpOffice\PhpWord\Element\TextRun
    {
        $typeStr = strtolower(trim((string)$type));

        if ($typeStr === 'kkni/okupasi/klaster' || empty($typeStr)) {
            if ($skema && !empty($skema->jenis_skema)) {
                $typeStr = strtolower(trim((string)$skema->jenis_skema));
            }
        }

        $isKKNI = str_contains($typeStr, 'kkni');
        $isOkupasi = str_contains($typeStr, 'okupasi');
        $isKlaster = str_contains($typeStr, 'klaster') || str_contains($typeStr, 'cluster');

        $matchedCount = ($isKKNI ? 1 : 0) + ($isOkupasi ? 1 : 0) + ($isKlaster ? 1 : 0);
        $hasSelection = ($matchedCount === 1 || $matchedCount === 2);

        $tr = new \PhpOffice\PhpWord\Element\TextRun();
        $tr->addText('(', ['name' => 'Times New Roman', 'size' => 10]);
        $tr->addText('KKNI', [
            'name' => 'Times New Roman',
            'size' => 10,
            'strikethrough' => $hasSelection ? !$isKKNI : false
        ]);
        $tr->addText('/', ['name' => 'Times New Roman', 'size' => 10]);
        $tr->addText('Okupasi', [
            'name' => 'Times New Roman',
            'size' => 10,
            'strikethrough' => $hasSelection ? !$isOkupasi : false
        ]);
        $tr->addText('/', ['name' => 'Times New Roman', 'size' => 10]);
        $tr->addText('Klaster', [
            'name' => 'Times New Roman',
            'size' => 10,
            'strikethrough' => $hasSelection ? !$isKlaster : false
        ]);
        $tr->addText(')', ['name' => 'Times New Roman', 'size' => 10]);

        return $tr;
    }

    /**
     * Build strikethrough TextRun for TUK (Sewaktu/Tempat Kerja/Mandiri*)
     */
    private function buildTukTextRun(?string $tuk, ?string $tukPelaksanaan = null): \PhpOffice\PhpWord\Element\TextRun
    {
        $tukStr = strtolower(trim((string)$tuk));

        $isSewaktu = str_contains($tukStr, 'sewaktu');
        $isTempatKerja = str_contains($tukStr, 'tempat kerja') || str_contains($tukStr, 'tempat_kerja') || str_contains($tukStr, 'tempatkerja');
        $isMandiri = str_contains($tukStr, 'mandiri');

        if (!$isSewaktu && !$isTempatKerja && !$isMandiri) {
            $tukModel = Tuk::where('nama_tuk', $tuk)->orWhere('nama_tuk', $tukPelaksanaan)->first();
            if ($tukModel && !empty($tukModel->tipe_tuk)) {
                $tipe = strtolower(trim((string)$tukModel->tipe_tuk));
                $isSewaktu = str_contains($tipe, 'sewaktu');
                $isTempatKerja = str_contains($tipe, 'tempat kerja') || str_contains($tipe, 'tempat_kerja') || str_contains($tipe, 'tempatkerja');
                $isMandiri = str_contains($tipe, 'mandiri');
            }
        }

        $matchedCount = ($isSewaktu ? 1 : 0) + ($isTempatKerja ? 1 : 0) + ($isMandiri ? 1 : 0);
        $hasSelection = ($matchedCount === 1 || $matchedCount === 2);

        $tr = new \PhpOffice\PhpWord\Element\TextRun();
        $tr->addText('Sewaktu', [
            'name' => 'Times New Roman',
            'size' => 10,
            'strikethrough' => $hasSelection ? !$isSewaktu : false
        ]);
        $tr->addText('/', ['name' => 'Times New Roman', 'size' => 10]);
        $tr->addText('Tempat Kerja', [
            'name' => 'Times New Roman',
            'size' => 10,
            'strikethrough' => $hasSelection ? !$isTempatKerja : false
        ]);
        $tr->addText('/', ['name' => 'Times New Roman', 'size' => 10]);
        $tr->addText('Mandiri', [
            'name' => 'Times New Roman',
            'size' => 10,
            'strikethrough' => $hasSelection ? !$isMandiri : false
        ]);
        $tr->addText('*', ['name' => 'Times New Roman', 'size' => 10]);

        return $tr;
    }

    /**
     * Resolve signature file to an absolute image path for PHPWord setImageValue.
     * Supports base64 data URIs and stored file paths.
     */
    private function resolveSignatureImage(?string $signatureValue): ?string
    {
        if (empty($signatureValue)) {
            return null;
        }

        // If it's a base64 data URI, decode and save to a temp file
        if (str_starts_with($signatureValue, 'data:image')) {
            $parts = explode('base64,', $signatureValue);
            $binary = base64_decode(end($parts), true);
            if ($binary && strlen($binary) > 50) {
                $tempPath = storage_path('app/temp/sig_' . uniqid() . '.png');
                if (!is_dir(dirname($tempPath))) {
                    mkdir(dirname($tempPath), 0755, true);
                }
                file_put_contents($tempPath, $binary);
                return $tempPath;
            }
            return null;
        }

        // If it's a stored file path (relative to storage/app/public)
        $filePath = storage_path('app/public/' . ltrim($signatureValue, '/'));
        if (file_exists($filePath)) {
            return $filePath;
        }

        return null;
    }

    /**
     * Build TextRun for signature date with dotted underline padding for Word export.
     */
    private function buildDateTextRun(?string $date, int $targetLength = 23): \PhpOffice\PhpWord\Element\TextRun
    {
        $tr = new \PhpOffice\PhpWord\Element\TextRun();

        if (empty($date)) {
            $tr->addText(str_repeat('.', $targetLength), [
                'name' => 'Times New Roman',
                'size' => 10,
            ]);
            return $tr;
        }

        try {
            $formatted = \Carbon\Carbon::parse($date)->locale('id')->isoFormat('D MMMM YYYY');
            $tr->addText($formatted, [
                'name' => 'Times New Roman',
                'size' => 10,
                'underline' => 'dotted',
            ]);

            $spacesNeeded = max(0, $targetLength - mb_strlen($formatted));
            if ($spacesNeeded > 0) {
                $tr->addText(str_repeat("\u{00A0}", $spacesNeeded), [
                    'name' => 'Times New Roman',
                    'size' => 10,
                    'underline' => 'dotted',
                ]);
            }
        } catch (\Exception $e) {
            $tr->addText(str_repeat('.', $targetLength), [
                'name' => 'Times New Roman',
                'size' => 10,
            ]);
        }

        return $tr;
    }
}
