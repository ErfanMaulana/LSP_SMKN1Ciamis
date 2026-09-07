<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\RekamanAsesmenKompetensi;
use App\Models\Skema;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RekamanAsesmenKompetensiController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));
        $skemaFilter = $request->get('skema');
        $rekomendasiFilter = $request->get('rekomendasi');

        $items = RekamanAsesmenKompetensi::query()
            ->with([
                'skema:id,nama_skema,nomor_skema',
                'asesi:NIK,nama',
                'asesor:ID_asesor,nama,no_met',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_form', 'like', "%{$search}%")
                        ->orWhereHas('skema', function ($skemaQuery) use ($search) {
                            $skemaQuery->where('nama_skema', 'like', "%{$search}%")
                                ->orWhere('nomor_skema', 'like', "%{$search}%");
                        })
                        ->orWhereHas('asesi', function ($asesiQuery) use ($search) {
                            $asesiQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('NIK', 'like', "%{$search}%");
                        })
                        ->orWhereHas('asesor', function ($asesorQuery) use ($search) {
                            $asesorQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('no_met', 'like', "%{$search}%");
                        });
                });
            })
            ->when($skemaFilter, function ($query) use ($skemaFilter) {
                $query->whereHas('skema', function ($skemaQuery) use ($skemaFilter) {
                    $skemaQuery->where('nama_skema', $skemaFilter);
                });
            })
            ->when($rekomendasiFilter, function ($query) use ($rekomendasiFilter) {
                $query->where('rekomendasi', $rekomendasiFilter);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $skemaList = Skema::query()
            ->orderBy('nama_skema')
            ->pluck('nama_skema');

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'rows' => view('admin.rekaman-asesmen-kompetensi.partials.table-rows', compact('items'))->render(),
                'pagination' => $items->hasPages() ? (string) $items->links() : '',
            ]);
        }

        return view('admin.rekaman-asesmen-kompetensi.index', compact('items', 'search', 'skemaList', 'skemaFilter', 'rekomendasiFilter'));
    }

    public function create()
    {
        $skemaList = Skema::query()
            ->orderBy('nama_skema')
            ->get(['id', 'nama_skema', 'nomor_skema']);

        $defaults = [
            'kode_form' => 'FR.AK.02.',
            'judul_form' => 'REKAMAN ASESMEN KOMPETENSI',
            'kategori_skema' => 'KKNI/Okupasi/Klaster',
            'tuk' => 'sewaktu',
        ];

        return view('admin.rekaman-asesmen-kompetensi.create', compact('skemaList', 'defaults'));
    }

    public function store(Request $request)
    {
        [$data, $details] = $this->validatedData($request);

        DB::transaction(function () use ($data, $details) {
            $item = RekamanAsesmenKompetensi::create($data);
            $item->details()->createMany($details);
        });

        return redirect()->route('admin.rekaman-asesmen-kompetensi.index')
            ->with('success', 'Rekaman asesmen kompetensi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = RekamanAsesmenKompetensi::query()
            ->with([
                'skema:id,nama_skema,nomor_skema',
                'asesi:NIK,nama',
                'asesor:ID_asesor,nama,no_met',
                'details.unit:id,kode_unit,judul_unit',
            ])
            ->findOrFail($id);

        $details = $item->details->sortBy([
            ['unit.id', 'asc'],
        ])->values();

        return view('admin.rekaman-asesmen-kompetensi.show', compact('item', 'details'));
    }

    public function edit($id)
    {
        $item = RekamanAsesmenKompetensi::query()->with(['details', 'skema'])->findOrFail($id);

        $skemaList = Skema::query()
            ->orderBy('nama_skema')
            ->get(['id', 'nama_skema', 'nomor_skema']);

        return view('admin.rekaman-asesmen-kompetensi.edit', compact('item', 'skemaList'));
    }

    public function update(Request $request, $id)
    {
        $item = RekamanAsesmenKompetensi::findOrFail($id);
        [$data, $details] = $this->validatedData($request);

        DB::transaction(function () use ($item, $data, $details) {
            $item->update($data);
            $item->details()->delete();
            $item->details()->createMany($details);
        });

        return redirect()->route('admin.rekaman-asesmen-kompetensi.index')
            ->with('success', 'Rekaman asesmen kompetensi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = RekamanAsesmenKompetensi::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.rekaman-asesmen-kompetensi.index')
            ->with('success', 'Rekaman asesmen kompetensi berhasil dihapus.');
    }

    public function participantsBySkema(Request $request)
    {
        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
        ]);

        $skemaId = (int) $validated['skema_id'];

        $asesi = Asesi::query()
            ->whereHas('skemas', function ($query) use ($skemaId) {
                $query->where('skemas.id', $skemaId);
            })
            ->whereNotExists(function ($query) use ($skemaId) {
                $query->select(DB::raw(1))
                    ->from('rekaman_asesmen_kompetensi')
                    ->whereColumn('rekaman_asesmen_kompetensi.asesi_nik', 'asesi.NIK')
                    ->where('rekaman_asesmen_kompetensi.skema_id', $skemaId);
            })
            ->orderBy('nama')
            ->get(['NIK', 'nama'])
            ->map(fn ($item) => [
                'id' => (string) $item->NIK,
                'nama' => $item->nama,
            ])
            ->values();

        $asesor = Asesor::query()
            ->whereHas('skemas', function ($query) use ($skemaId) {
                $query->where('skemas.id', $skemaId);
            })
            ->orderBy('nama')
            ->get(['ID_asesor', 'nama', 'no_met'])
            ->map(fn ($item) => [
                'id' => (string) $item->ID_asesor,
                'nama' => $item->nama,
                'no_reg' => $item->no_met,
            ])
            ->values();

        return response()->json([
            'asesi' => $asesi,
            'asesor' => $asesor,
        ]);
    }

    public function skemaUnits(Request $request)
    {
        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
        ]);

        $units = Unit::query()
            ->where('skema_id', $validated['skema_id'])
            ->orderBy('id')
            ->get(['id', 'kode_unit', 'judul_unit'])
            ->map(fn ($unit) => [
                'id' => $unit->id,
                'kode_unit' => $unit->kode_unit,
                'judul_unit' => $unit->judul_unit,
            ])
            ->values();

        return response()->json([
            'units' => $units,
        ]);
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'kode_form' => 'required|string|max:20',
            'judul_form' => 'required|string|max:255',
            'kategori_skema' => 'nullable|string|max:100',
            'skema_id' => 'required|exists:skemas,id',
            'tuk' => 'nullable|string|max:255',
            'asesor_id' => 'nullable|exists:asesor,ID_asesor',
            'asesi_nik' => 'required|exists:asesi,NIK',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'rekomendasi' => 'required|in:kompeten,belum_kompeten',
            'tindak_lanjut' => 'nullable|string',
            'komentar_observasi' => 'nullable|string',
            'detail' => 'required|array|min:1',
            'detail.*.unit_id' => 'required|exists:units,id',
            'detail.*.observasi_demonstrasi' => 'nullable|in:1',
            'detail.*.portofolio' => 'nullable|in:1',
            'detail.*.pernyataan_pihak_ketiga' => 'nullable|in:1',
            'detail.*.pertanyaan_lisan' => 'nullable|in:1',
            'detail.*.pertanyaan_tertulis' => 'nullable|in:1',
            'detail.*.proyek_kerja' => 'nullable|in:1',
            'detail.*.lainnya' => 'nullable|in:1',
        ]);

        $rawDetails = array_values($data['detail']);
        unset($data['detail']);

        $unitIds = collect($rawDetails)->pluck('unit_id')->unique()->values();

        $allowedUnitIds = Unit::query()
            ->where('skema_id', $data['skema_id'])
            ->whereIn('id', $unitIds)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $allowedLookup = array_fill_keys($allowedUnitIds, true);

        $details = collect($rawDetails)
            ->filter(fn ($detail) => isset($allowedLookup[(int) $detail['unit_id']]))
            ->map(function ($detail) {
                return [
                    'unit_id' => (int) $detail['unit_id'],
                    'observasi_demonstrasi' => isset($detail['observasi_demonstrasi']),
                    'portofolio' => isset($detail['portofolio']),
                    'pernyataan_pihak_ketiga' => isset($detail['pernyataan_pihak_ketiga']),
                    'pertanyaan_lisan' => isset($detail['pertanyaan_lisan']),
                    'pertanyaan_tertulis' => isset($detail['pertanyaan_tertulis']),
                    'proyek_kerja' => isset($detail['proyek_kerja']),
                    'lainnya' => isset($detail['lainnya']),
                ];
            })
            ->values()
            ->all();

        if (count($details) === 0) {
            throw ValidationException::withMessages([
                'detail' => 'Detail unit kompetensi tidak valid untuk skema yang dipilih.',
            ]);
        }

        return [$data, $details];
    }

    public function export($id)
    {
        $item = RekamanAsesmenKompetensi::query()
            ->with([
                'skema:id,nama_skema,nomor_skema,jenis_skema',
                'asesi:NIK,nama',
                'asesor:ID_asesor,nama,no_met',
                'details.unit:id,kode_unit,judul_unit',
            ])
            ->findOrFail($id);

        if (empty($item->ttd_asesi_file) || empty($item->ttd_asesor_file)) {
            return redirect()->back()->with('error', 'Form FR.AK.02 belum dapat diexport karena asesi atau asesor belum menandatangani rekaman asesmen.');
        }

        $templatePath = storage_path('app/template/fr_ak_02.docx');

        $details = $item->details->sortBy([
            ['unit.id', 'asc'],
        ])->values();

        if (file_exists($templatePath)) {
            return $this->exportWithPhpWord($item, $item->skema, $item->asesi, $item->asesor, $details, $templatePath);
        }

        $ceklis = \App\Models\CeklisObservasiAktivitasPraktik::query()
            ->where('skema_id', $item->skema_id)
            ->where('asesi_nik', $item->asesi_nik)
            ->first();

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

        $html = view('asesor.rekaman-asesmen-kompetensi.export-docx', [
            'item' => $item,
            'ceklis' => $ceklis,
            'details' => $details,
            'logoPath' => $logoPath,
            'logoDataUri' => $logoDataUri,
            'ttdAsesiDataUri' => $ttdAsesiDataUri,
            'ttdAsesorDataUri' => $ttdAsesorDataUri,
        ])->render();

        $fileSkema = preg_replace('/[^A-Za-z0-9\-]+/', '-', (string) ($item->skema?->nomor_skema ?? $item->skema_id));
        $fileName = 'FR.AK.02-' . ($item->asesi_nik ?? 'asesi') . '-' . trim($fileSkema, '-') . '.doc';

        return response($html, 200, [
            'Content-Type' => 'application/msword; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Export FR.AK.02 using PHPWord TemplateProcessor with .docx template
     */
    private function exportWithPhpWord($item, $skema, $asesi, $asesor, $details, string $templatePath)
    {
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // --- Basic info ---
        $templateProcessor->setValue('judul_skema', $item->judul_skema ?? ($skema->nama_skema ?? '-'));
        $templateProcessor->setValue('nomor_skema', $item->nomor_skema ?? ($skema->nomor_skema ?? '-'));
        $templateProcessor->setValue('nama_asesor', $asesor?->nama ?? ($item->ttd_asesor_nama ?? '-'));
        $templateProcessor->setValue('nama_asesi', $asesi?->nama ?? ($item->asesi_nik ?? '-'));

        // --- Skema Type & TUK with strikethrough ---
        $skemaType = $item->kategori_skema ?? ($skema?->jenis_skema ?? null);
        $templateProcessor->setComplexValue('type_skema', $this->buildSkemaTypeTextRun($skemaType, $skema));
        $templateProcessor->setComplexValue('tuk_sewaktu_tempat_kerja_mandiri', $this->buildTukTextRun($item->tuk));

        // --- Dates ---
        $templateProcessor->setValue('tanggal_mulai', !empty($item->tanggal_mulai) ? \Carbon\Carbon::parse($item->tanggal_mulai)->locale('id')->isoFormat('D MMMM YYYY') : '-');
        $templateProcessor->setValue('tanggal_selesai', !empty($item->tanggal_selesai) ? \Carbon\Carbon::parse($item->tanggal_selesai)->locale('id')->isoFormat('D MMMM YYYY') : '-');

        // --- Dynamic Unit Kompetensi Rows ---
        if ($details->count() > 0) {
            $templateProcessor->cloneRow('unit_kompetensi', $details->count());
            foreach ($details as $idx => $detail) {
                $n = $idx + 1;
                $trUnit = new \PhpOffice\PhpWord\Element\TextRun();
                $trUnit->addText($detail->unit->kode_unit ?? '-', ['name' => 'Times New Roman', 'size' => 10, 'bold' => true]);
                $trUnit->addTextBreak();
                $trUnit->addText($detail->unit->judul_unit ?? '-', ['name' => 'Times New Roman', 'size' => 10]);

                $templateProcessor->setComplexValue("unit_kompetensi#{$n}", $trUnit);
                $templateProcessor->setValue("od#{$n}", $detail->observasi_demonstrasi ? '√' : '');
                $templateProcessor->setValue("p#{$n}", $detail->portofolio ? '√' : '');
                $templateProcessor->setValue("pppw#{$n}", $detail->pernyataan_pihak_ketiga ? '√' : '');
                $templateProcessor->setValue("pl#{$n}", $detail->pertanyaan_lisan ? '√' : '');
                $templateProcessor->setValue("pt#{$n}", $detail->pertanyaan_tertulis ? '√' : '');
                $templateProcessor->setValue("pk#{$n}", $detail->proyek_kerja ? '√' : '');
                $templateProcessor->setValue("l#{$n}", $detail->lainnya ? '√' : '');
            }
        } else {
            $templateProcessor->setValue('unit_kompetensi', '-');
            $templateProcessor->setValue('od', '');
            $templateProcessor->setValue('p', '');
            $templateProcessor->setValue('pppw', '');
            $templateProcessor->setValue('pl', '');
            $templateProcessor->setValue('pt', '');
            $templateProcessor->setValue('pk', '');
            $templateProcessor->setValue('l', '');
        }

        // --- Recommendation Checkboxes & Notes (Monochrome / Black) ---
        $checkK = $item->rekomendasi === 'kompeten' ? '☑' : '☐';
        $checkBk = $item->rekomendasi === 'belum_kompeten' ? '☑' : '☐';

        $trK = new \PhpOffice\PhpWord\Element\TextRun();
        $trK->addText($checkK, [
            'name' => 'Segoe UI Symbol',
            'size' => 11,
            'color' => '000000',
            'bold' => true,
        ]);
        $templateProcessor->setComplexValue('ceklis_k', $trK);

        $trBk = new \PhpOffice\PhpWord\Element\TextRun();
        $trBk->addText($checkBk, [
            'name' => 'Segoe UI Symbol',
            'size' => 11,
            'color' => '000000',
            'bold' => true,
        ]);
        $templateProcessor->setComplexValue('ceklis_bk', $trBk);

        $templateProcessor->setValue('tltd', $item->tindak_lanjut ?? '-');
        $templateProcessor->setValue('komentar_asesor', $item->komentar_observasi ?? '-');

        // --- Asesor Reg Number ---
        $templateProcessor->setValue('noreg', $asesor?->no_met ?? ($asesor?->no_reg ?? '-'));

        // --- Signatures ---
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

        // --- Save and Download ---
        $fileSkema = preg_replace('/[^A-Za-z0-9\-]+/', '-', (string) ($skema?->nomor_skema ?? $skema?->id ?? 'skema'));
        $fileName = 'FR.AK.02-' . ($item->asesi_nik ?? 'asesi') . '-' . trim($fileSkema, '-') . '.docx';

        $tempFile = storage_path('app/temp/' . uniqid('fr_ak_02_') . '.docx');
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
    private function buildTukTextRun(?string $tuk): \PhpOffice\PhpWord\Element\TextRun
    {
        $tukStr = strtolower(trim((string)$tuk));

        $isSewaktu = str_contains($tukStr, 'sewaktu');
        $isTempatKerja = str_contains($tukStr, 'tempat kerja') || str_contains($tukStr, 'tempat_kerja') || str_contains($tukStr, 'tempatkerja');
        $isMandiri = str_contains($tukStr, 'mandiri');

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
     */
    private function resolveSignatureImage(?string $signatureValue): ?string
    {
        if (empty($signatureValue)) {
            return null;
        }

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

        $filePath = storage_path('app/public/' . ltrim($signatureValue, '/'));
        if (file_exists($filePath)) {
            return $filePath;
        }

        return null;
    }

    /**
     * Build TextRun for signature date with dotted underline padding for Word export.
     */
    private function buildDateTextRun(?string $date, int $targetLength = 20): \PhpOffice\PhpWord\Element\TextRun
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
