<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Skema;
use App\Models\Tuk;
use App\Models\PersetujuanAsesmen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class PersetujuanAsesmenFrontController extends Controller
{
    private function resolveAsesiFromUser($user): ?Asesi
    {
        if (!$user) {
            return null;
        }

        $asesiQuery = Asesi::query();
        $hasCondition = false;

        if (!empty($user->NIK)) {
            $asesiQuery->where('NIK', $user->NIK);
            $hasCondition = true;
        }

        if (Schema::hasColumn('asesi', 'no_reg') && !empty($user->id)) {
            if ($hasCondition) {
                $asesiQuery->orWhere('no_reg', $user->id);
            } else {
                $asesiQuery->where('no_reg', $user->id);
                $hasCondition = true;
            }
        }

        return $hasCondition ? $asesiQuery->first() : null;
    }

    private function hasAsesiNikColumn(): bool
    {
        static $hasColumn = null;

        if ($hasColumn === null) {
            $hasColumn = Schema::hasColumn('persetujuan_asesmen', 'asesi_nik');
        }

        return $hasColumn;
    }

    private function buildDefaultPayload(array $overrides = []): array
    {
        $payload = array_merge([
            'kode_form' => 'FR.AK.01.',
            'judul_form' => 'PERSETUJUAN ASESMEN DAN KERAHASIAAN',
            'pengantar' => 'Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen',
            'kategori_skema' => 'KKNI/Okupasi/Klaster',
            'tuk' => 'Sewaktu/Tempat Kerja/Mandiri*',
            'nama_asesor' => '-',
            'pernyataan_asesi_1' => 'Bahwa saya telah mendapatkan penjelasan terkait hak dan prosedur banding asesmen dari asesor.',
            'pernyataan_asesor' => 'Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.',
            'pernyataan_asesi_2' => 'Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.',
            'catatan_footer' => '* Coret yang tidak perlu',
        ], $overrides);

        if (!$this->hasAsesiNikColumn()) {
            unset($payload['asesi_nik']);
        }

        return $payload;
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

        $isAsesorRoute = $request->routeIs('asesor.*');
        $account = $request->user();
        $loggedAsesor = $account ? Asesor::where('no_met', $account->id)->first() : null;

        if ($isAsesorRoute && $loggedAsesor) {
            $asesorList = collect([[
                'id' => (string) $loggedAsesor->ID_asesor,
                'nama' => $loggedAsesor->nama,
            ]]);
        } else {
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
        }

        return response()->json([
            'asesi' => $asesiList,
            'asesor' => $asesorList,
        ]);
    }

    public function asesorCreate(Request $request)
    {
        $account = $request->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;

        $defaults = array_merge($this->defaultContent(), [
            'nama_asesor' => $asesor?->nama ?? '-',
        ]);

        $skemaList = Skema::query()
            ->orderBy('nama_skema')
            ->get(['id', 'nama_skema', 'nomor_skema']);

        $tukList = Tuk::query()
            ->orderBy('nama_tuk')
            ->get(['id', 'nama_tuk', 'tipe_tuk', 'kota', 'status']);

        return view('persetujuan-asesmen.create', [
            'defaults' => $defaults,
            'skemaList' => $skemaList,
            'tukList' => $tukList,
            'submitLabel' => 'Simpan Data',
            'backUrl' => route('asesor.persetujuan-asesmen.index'),
            'participantsEndpoint' => route('asesor.persetujuan-asesmen.skema-participants'),
        ]);
    }

    public function asesorStore(Request $request)
    {
        $data = $this->validatedData($request);
        $account = $request->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;

        if ($asesor) {
            // Keep persisted asesor name aligned with the logged-in asesor account.
            $data['nama_asesor'] = $asesor->nama;
        }

        PersetujuanAsesmen::create($data);

        return redirect()->route('asesor.persetujuan-asesmen.index')
            ->with('success', 'Data persetujuan asesmen berhasil ditambahkan.');
    }

    public function asesorIndex(Request $request)
    {
        $account = $request->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;
        $useNik = $this->hasAsesiNikColumn();
        $search = $request->get('search');

        $items = collect();

        if ($asesor) {
            $records = PersetujuanAsesmen::query()
                ->where('nama_asesor', $asesor->nama)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('judul_skema', 'like', "%{$search}%")
                            ->orWhere('nomor_skema', 'like', "%{$search}%")
                            ->orWhere('nama_asesi', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->get();

            $skemaByNomor = Skema::query()
                ->get(['id', 'nama_skema', 'nomor_skema'])
                ->keyBy('nomor_skema');

            $asesiByNik = Asesi::query()
                ->get(['NIK', 'nama'])
                ->keyBy('NIK');

            $items = $records->map(function ($record) use ($skemaByNomor, $asesiByNik, $useNik) {
                $skema = $skemaByNomor->get($record->nomor_skema);

                $asesiNik = null;
                if ($useNik && !empty($record->asesi_nik)) {
                    $asesiNik = $record->asesi_nik;
                }

                if (!$asesiNik) {
                    $matchingAsesi = Asesi::query()
                        ->where('nama', $record->nama_asesi)
                        ->orderBy('NIK')
                        ->first(['NIK']);
                    $asesiNik = $matchingAsesi?->NIK;
                }

                $asesiNama = $record->nama_asesi;
                if ($asesiNik && $asesiByNik->has($asesiNik)) {
                    $asesiNama = $asesiByNik->get($asesiNik)->nama;
                }

                return [
                    'asesi_nik' => $asesiNik,
                    'asesi_nama' => $asesiNama,
                    'skema_id' => $skema?->id,
                    'skema_nama' => $record->judul_skema ?: ($skema?->nama_skema ?? '-'),
                    'skema_nomor' => $record->nomor_skema,
                    'status' => $record->ttd_asesi_nama ? 'Sudah Ditandatangani' : 'Belum Ditandatangani',
                ];
            })->values();
        }

        return view('persetujuan-asesmen.asesor-index', [
            'items' => $items,
            'asesor' => $asesor,
            'search' => $search,
        ]);
    }

    public function asesorExport(Request $request)
    {
        $account = $request->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;
        $useNik = $this->hasAsesiNikColumn();
        $search = $request->get('search');

        $items = collect();

        if ($asesor) {
            $records = PersetujuanAsesmen::query()
                ->where('nama_asesor', $asesor->nama)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('judul_skema', 'like', "%{$search}%")
                            ->orWhere('nomor_skema', 'like', "%{$search}%")
                            ->orWhere('nama_asesi', 'like', "%{$search}%");
                    });
                })
                ->latest()
                ->get();

            $asesiByNik = collect();
            if ($useNik) {
                $asesiByNik = Asesi::query()->get()->keyBy('NIK');
            }

            $items = $records->map(function ($record) use ($asesiByNik, $useNik) {
                $asesiNik = null;
                if ($useNik && $record->asesi_nik) {
                    $asesiNik = $record->asesi_nik;
                } else {
                    $matchingAsesi = Asesi::where('nama', $record->nama_asesi)
                        ->orderBy('NIK')
                        ->first(['NIK']);
                    $asesiNik = $matchingAsesi?->NIK;
                }

                $asesiNama = $record->nama_asesi;
                if ($asesiNik && $asesiByNik->has($asesiNik)) {
                    $asesiNama = $asesiByNik->get($asesiNik)->nama;
                }

                return [
                    'skema_nama' => $record->judul_skema ?: ($record->judul_skema ?? '-'),
                    'skema_nomor' => $record->nomor_skema,
                    'asesi_nama' => $asesiNama,
                    'status' => $record->ttd_asesi_nama ? 'Sudah Ditandatangani' : 'Belum Ditandatangani',
                ];
            })->values();
        }

        // Build simple HTML table that resembles the index view
        $html = '<h2>Persetujuan Asesmen dan Kerahasiaan</h2>';
        $html .= '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;width:100%">';
        $html .= '<thead><tr><th style="width:45px">No</th><th>Skema</th><th>Nomor Skema</th><th>Asesi</th><th>Status</th></tr></thead>';
        $html .= '<tbody>';
        foreach ($items as $i => $item) {
            $html .= '<tr>';
            $html .= '<td>' . ($i+1) . '</td>';
            $html .= '<td>' . e($item['skema_nama']) . '</td>';
            $html .= '<td>' . e($item['skema_nomor']) . '</td>';
            $html .= '<td>' . e($item['asesi_nama']) . '</td>';
            $html .= '<td>' . e($item['status']) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        // Generate docx using PhpWord
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);

        $fileName = 'persetujuan-asesmen-asesor-' . date('YmdHis') . '.docx';
        $tempFile = sys_get_temp_dir() . '/' . $fileName;
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function asesiIndex(Request $request)
    {
        $asesi = $this->resolveAsesiFromUser($request->user());
        $useNik = $this->hasAsesiNikColumn();

        $items = collect();

        if ($asesi) {
            $items = $asesi->skemas()->withPivot('status', 'tanggal_mulai', 'tanggal_selesai')->get()->map(function ($skema) use ($asesi, $useNik) {
                $record = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
                    ->where(function ($q) use ($asesi, $useNik) {
                        $q->where('nama_asesi', $asesi->nama);
                        if ($useNik) {
                            $q->orWhere('asesi_nik', $asesi->NIK);
                        }
                    })
                    ->whereNotNull('ttd_asesor_nama')
                    ->whereNotNull('ttd_asesor_tanggal')
                    ->latest()
                    ->first();

                if (!$record) {
                    return null;
                }

                return [
                    'persetujuan_id' => $record->id,
                    'skema_id' => $skema->id,
                    'skema_nama' => $skema->nama_skema,
                    'skema_nomor' => $skema->nomor_skema,
                    'status' => $record->ttd_asesi_nama ? 'Sudah Ditandatangani Asesi' : 'Menunggu Tanda Tangan Asesi',
                    'can_sign' => empty($record->ttd_asesi_nama),
                ];
            })->filter()->values();
        }

        return view('persetujuan-asesmen.asesi-index', [
            'items' => $items,
            'asesi' => $asesi,
        ]);
    }

    public function asesorShow(Request $request, $asesiNik, $skemaId)
    {
        $account = $request->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;
        $asesi = Asesi::where('NIK', $asesiNik)->first();
        $skema = Skema::find($skemaId);

        if (!$skema) {
            abort(404);
        }

        $namaAsesi = $asesi ? $asesi->nama : null;
        $useNik = $this->hasAsesiNikColumn();

        $item = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
            ->where(function ($q) use ($namaAsesi, $asesiNik, $useNik) {
                if ($namaAsesi) {
                    $q->where('nama_asesi', $namaAsesi);
                }
                if ($useNik) {
                    $q->orWhere('asesi_nik', $asesiNik);
                }
            })->latest()->first();

        if (!$item) {
            // create placeholder record so form can be signed
            $item = PersetujuanAsesmen::create($this->buildDefaultPayload([
                'judul_skema' => $skema->nama_skema ?? '',
                'nomor_skema' => $skema->nomor_skema ?? '',
                'nama_asesi' => $namaAsesi ?? '',
                'asesi_nik' => $asesiNik,
                'nama_asesor' => $asesor?->nama ?? '-',
            ]));
        }

        $tukList = Tuk::orderBy('nama_tuk')->get(['id','nama_tuk','tipe_tuk','kota']);

        return view('persetujuan-asesmen.front', [
            'item' => $item,
            'role' => 'asesor',
            'skema' => $skema,
            'tukList' => $tukList,
        ]);
    }

    public function asesorSign(Request $request, $id)
    {
        $item = PersetujuanAsesmen::findOrFail($id);

        $data = $request->validate([
            'ttd_asesor_nama' => 'required|string|max:255',
            'ttd_asesor_tanggal' => 'required|date',
            'ttd_asesor_file' => 'required|string',
        ]);

        // Handle signature file from the canvas (base64 data URL)
        try {
            $signatureData = $request->ttd_asesor_file;

            if (strpos($signatureData, 'data:image') === 0) {
                list($type, $signatureData) = explode(';', $signatureData);
                list(, $signatureData) = explode(',', $signatureData);
                $signatureData = base64_decode($signatureData);
            } else {
                $signatureData = base64_decode($signatureData);
            }

            $filename = 'signature_asesor_' . $item->id . '_' . time() . '.png';
            $path = 'persetujuan-asesmen/signatures';

            Storage::disk('public')->put($path . '/' . $filename, $signatureData);
            $data['ttd_asesor_file'] = $path . '/' . $filename;

            Log::info('Asesor signature image saved', ['file' => $data['ttd_asesor_file']]);
        } catch (\Exception $e) {
            Log::error('Failed to save asesor signature image', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Tanda tangan asesor tidak tersimpan. Pastikan tanda tangan digambar di canvas sebelum menyimpan.');
        }

        Log::info('Updating asesor signature', ['id' => $item->id, 'data' => $data]);

        $item->update($data);

        return redirect()->back()->with('success', 'Tanda tangan asesor tersimpan');
    }

    public function asesiShow(Request $request, $skemaId)
    {
        $asesi = $this->resolveAsesiFromUser($request->user());
        if (!$asesi) {
            abort(403);
        }

        $skema = Skema::find($skemaId);
        if (!$skema) abort(404);
        $useNik = $this->hasAsesiNikColumn();

        $item = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
            ->where(function ($q) use ($asesi, $useNik) {
                if ($asesi) {
                    $q->where('nama_asesi', $asesi->nama);
                    if ($useNik) {
                        $q->orWhere('asesi_nik', $asesi->NIK);
                    }
                }
            })
            ->whereNotNull('ttd_asesor_nama')
            ->whereNotNull('ttd_asesor_tanggal')
            ->latest()
            ->first();

        if (!$item) {
            return redirect()->route('asesi.persetujuan-asesmen.index')
                ->with('error', 'Form belum tersedia. Asesor belum menandatangani persetujuan asesmen untuk skema ini.');
        }

        $tukList = Tuk::orderBy('nama_tuk')->get(['id','nama_tuk','tipe_tuk','kota']);

        return view('persetujuan-asesmen.front', [
            'item' => $item,
            'role' => 'asesi',
            'skema' => $skema,
            'tukList' => $tukList,
        ]);
    }

    public function asesiSign(Request $request, $id)
    {
        $asesi = $this->resolveAsesiFromUser($request->user());
        if (!$asesi) {
            abort(403);
        }

        $item = PersetujuanAsesmen::findOrFail($id);
        $useNik = $this->hasAsesiNikColumn();

        $sameAsesi = $item->nama_asesi === $asesi->nama;
        if ($useNik && !empty($item->asesi_nik) && !empty($asesi->NIK)) {
            $sameAsesi = $sameAsesi || $item->asesi_nik === $asesi->NIK;
        }

        if (!$sameAsesi) {
            Log::error('Asesi mismatch', [
                'item_nama' => $item->nama_asesi,
                'asesi_nama' => $asesi->nama,
                'item_nik' => $item->asesi_nik,
                'asesi_nik' => $asesi->NIK ?? null,
            ]);
            abort(403);
        }

        if (empty($item->ttd_asesor_nama) || empty($item->ttd_asesor_tanggal)) {
            Log::error('Asesor not signed yet', [
                'item_id' => $item->id,
                'ttd_asesor_nama' => $item->ttd_asesor_nama,
                'ttd_asesor_tanggal' => $item->ttd_asesor_tanggal,
            ]);
            return redirect()->back()->with('error', 'Tanda tangan asesi belum bisa dilakukan sebelum asesor menandatangani.');
        }

        Log::info('Asesi sign request received', [
            'id' => $id,
            'request_data' => $request->all(),
        ]);

        $data = $request->validate([
            'ttd_asesi_tanggal' => 'required|date_format:Y-m-d',
        ], [
            'ttd_asesi_tanggal.required' => 'Tanggal harus diisi',
            'ttd_asesi_tanggal.date_format' => 'Format tanggal tidak valid',
        ]);

        $data['ttd_asesi_nama'] = $asesi->nama;

        // Handle signature image file
        if ($request->has('ttd_asesi_file') && !empty($request->ttd_asesi_file)) {
            try {
                $signatureData = $request->ttd_asesi_file;
                
                // Extract base64 data
                if (strpos($signatureData, 'data:image') === 0) {
                    list($type, $signatureData) = explode(';', $signatureData);
                    list(, $signatureData) = explode(',', $signatureData);
                    $signatureData = base64_decode($signatureData);
                } else {
                    $signatureData = base64_decode($signatureData);
                }
                
                // Create filename
                $filename = 'signature_asesi_' . $item->id . '_' . time() . '.png';
                $path = 'persetujuan-asesmen/signatures';
                
                // Store file
                Storage::disk('public')->put($path . '/' . $filename, $signatureData);
                
                $data['ttd_asesi_file'] = $path . '/' . $filename;
                
                Log::info('Signature image saved', ['file' => $data['ttd_asesi_file']]);
            } catch (\Exception $e) {
                Log::error('Failed to save signature image', ['error' => $e->getMessage()]);
                // Continue without image, don't fail validation
            }
        }

        // Fallback: if no signature image was uploaded, generate a simple SVG with the name
        if (empty($data['ttd_asesi_file']) && !empty($data['ttd_asesi_nama'])) {
            try {
                $name = htmlspecialchars($data['ttd_asesi_nama'], ENT_QUOTES, 'UTF-8');
                $svg = "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"600\" height=\"140\">";
                $svg .= "<rect width=\"100%\" height=\"100%\" fill=\"#ffffff\"/>";
                $svg .= "<text x=\"50%\" y=\"60%\" dominant-baseline=\"middle\" text-anchor=\"middle\" font-family=\"Segoe UI, Arial, sans-serif\" font-size=36 fill=\"#111827\">" . $name . "</text>";
                $svg .= "</svg>";

                $filename = 'signature_asesi_name_' . $item->id . '_' . time() . '.svg';
                $path = 'persetujuan-asesmen/signatures';
                Storage::disk('public')->put($path . '/' . $filename, $svg);
                $data['ttd_asesi_file'] = $path . '/' . $filename;
                Log::info('Generated fallback signature SVG for asesi', ['file' => $data['ttd_asesi_file']]);
            } catch (\Exception $e) {
                Log::error('Failed to generate fallback signature SVG', ['error' => $e->getMessage()]);
            }
        }

        Log::info('Updating asesi signature', [
            'id' => $item->id,
            'data' => $data,
        ]);

        $item->update($data);

        Log::info('Asesi signature saved', [
            'id' => $item->id,
            'ttd_asesi_tanggal' => $item->ttd_asesi_tanggal,
            'ttd_asesi_nama' => $item->ttd_asesi_nama,
        ]);

        return redirect()->route('asesi.persetujuan-asesmen.index')->with('success', 'Tanda tangan asesi tersimpan');
    }
}

