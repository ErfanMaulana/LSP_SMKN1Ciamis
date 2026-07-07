<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\CeklisObservasiAktivitasPraktik;
use App\Models\Kriteria;
use App\Models\PersetujuanAsesmen;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class CeklisObservasiController extends Controller
{
    private function getAsesor(): ?Asesor
    {
        $account = Auth::guard('account')->user();

        if (!$account) {
            return null;
        }

        return Asesor::with('skemas')->where('no_met', $account->id)->first();
    }

    public function index(Request $request)
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();

        if (!$asesor) {
            $items = CeklisObservasiAktivitasPraktik::query()->whereRaw('1 = 0')->paginate(10);
            $search = trim((string) $request->get('search'));
            $rekomendasi = (string) $request->get('rekomendasi');

            if ($request->ajax()) {
                return view('asesor.ceklis-observasi.partials.table-rows', compact('items'))->render();
            }

            return view('asesor.ceklis-observasi.index', compact('account', 'asesor', 'items', 'search', 'rekomendasi'));
        }

        $search = trim((string) $request->get('search'));
        $rekomendasi = (string) $request->get('rekomendasi');
        $viewMode = (string) $request->get('view', 'menunggu');

        // Fetch completed ceklis records for this asesor
        $completedRows = CeklisObservasiAktivitasPraktik::query()
            ->with([
                'skema:id,nama_skema,nomor_skema',
                'asesi:NIK,nama',
            ])
            ->where('asesor_id', $asesor->ID_asesor)
            ->get();

        foreach ($completedRows as $c) {
            $c->is_pending = false;
        }

        // Fetch pending ceklis records
        $skemaIds = $asesor->skemas->pluck('id')->toArray();
        $nomorSkemas = $asesor->skemas->pluck('nomor_skema')->toArray();

        // 1. Get fully signed persetujuan_asesmen records
        $fullySignedPersetujuans = DB::table('persetujuan_asesmen')
            ->whereIn('nomor_skema', $nomorSkemas)
            ->whereNotNull('ttd_asesor_nama')->where('ttd_asesor_nama', '!=', '')
            ->whereNotNull('ttd_asesi_nama')->where('ttd_asesi_nama', '!=', '')
            ->get(['nomor_skema', 'asesi_nik', 'nama_asesi']);

        $persetujuanKeys = [];
        foreach ($fullySignedPersetujuans as $p) {
            if (!empty($p->asesi_nik)) {
                $persetujuanKeys["{$p->nomor_skema}|{$p->asesi_nik}"] = true;
            }
            if (!empty($p->nama_asesi)) {
                $persetujuanKeys["{$p->nomor_skema}|" . strtolower($p->nama_asesi)] = true;
            }
        }

        // 2. Get registered asesis for these skemas
        $asesiSkemasForCeklis = DB::table('asesi_skema')
            ->whereIn('skema_id', $skemaIds)
            ->get(['asesi_nik', 'skema_id']);

        // 3. Existing ceklis map
        $ceklisKeys = [];
        foreach ($completedRows as $cr) {
            $ceklisKeys["{$cr->asesi_nik}|{$cr->skema_id}"] = true;
        }

        // 4. Get asesi details
        $asesisLookup = Asesi::whereIn('NIK', $asesiSkemasForCeklis->pluck('asesi_nik')->toArray())
            ->get(['NIK', 'nama'])
            ->keyBy('NIK');

        $pendingRows = [];
        foreach ($asesiSkemasForCeklis as $as) {
            $sk = $asesor->skemas->firstWhere('id', $as->skema_id);
            if (!$sk) continue;

            $key = "{$as->asesi_nik}|{$as->skema_id}";
            if (isset($ceklisKeys[$key])) continue;

            $asesiObj = $asesisLookup->get($as->asesi_nik);
            $hasPersetujuan = isset($persetujuanKeys["{$sk->nomor_skema}|{$as->asesi_nik}"]) ||
                ($asesiObj && isset($persetujuanKeys["{$sk->nomor_skema}|" . strtolower($asesiObj->nama)]));

            if ($hasPersetujuan && $asesiObj) {
                $obj = new CeklisObservasiAktivitasPraktik();
                $obj->id = null;
                $obj->is_pending = true;
                $obj->asesi_nik = $as->asesi_nik;
                $obj->skema_id = $as->skema_id;
                $obj->asesor_id = $asesor->ID_asesor;
                $obj->rekomendasi = null;
                $obj->tanggal = null;
                $obj->setRelation('asesi', $asesiObj);
                $obj->setRelation('skema', $sk);

                $pendingRows[] = $obj;
            }
        }

        // Combine all rows for counting
        $allRows = collect(array_merge($pendingRows, $completedRows->all()));
        $pendingCount = collect($pendingRows)->count();
        $completedCount = $completedRows->count();

        // View mode filter (switcher)
        if ($viewMode === 'selesai') {
            $allRows = $allRows->filter(fn($row) => !$row->is_pending);
        } else {
            $allRows = $allRows->filter(fn($row) => $row->is_pending);
        }

        // Search Filter
        if ($search !== '') {
            $allRows = $allRows->filter(function ($row) use ($search) {
                $matchAsesi = $row->asesi && (str_contains(strtolower($row->asesi->nama), strtolower($search)) || str_contains($row->asesi->NIK, $search));
                $matchSkema = $row->skema && (str_contains(strtolower($row->skema->nama_skema), strtolower($search)) || str_contains(strtolower($row->skema->nomor_skema), strtolower($search)));
                return $matchAsesi || $matchSkema;
            });
        }

        // Rekomendasi filter (only relevant for 'selesai' view)
        if ($rekomendasi !== '' && $viewMode === 'selesai') {
            $allRows = $allRows->filter(function ($row) use ($rekomendasi) {
                if ($rekomendasi === 'kompeten') {
                    return $row->rekomendasi === 'kompeten';
                }
                if ($rekomendasi === 'belum_kompeten') {
                    return $row->rekomendasi === 'belum_kompeten';
                }
                return true;
            });
        }

        // Sort
        $allRows = $allRows->sort(function ($a, $b) {
            if ($a->is_pending && $b->is_pending) {
                return strcmp($a->asesi->nama ?? '', $b->asesi->nama ?? '');
            }
            if ($a->is_pending) return -1;
            if ($b->is_pending) return 1;
            return ($b->id ?? 0) <=> ($a->id ?? 0);
        });

        // Paginate collection
        $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $allRows->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $items = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $allRows->count(),
            $perPage,
            $currentPage,
            [
                'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
                'query' => $request->query(),
            ]
        );

        if ($request->ajax()) {
            return view('asesor.ceklis-observasi.partials.table-rows', compact('items'))->render();
        }

        return view('asesor.ceklis-observasi.index', compact('account', 'asesor', 'items', 'search', 'rekomendasi', 'viewMode', 'pendingCount', 'completedCount'));
    }

    public function create(Request $request)
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();

        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $skemas = $asesor->skemas()
            ->orderBy('nama_skema')
            ->get(['skemas.id', 'nama_skema', 'nomor_skema', 'jenis_skema']);

        $activeSkema = $skemas->first();
        abort_unless((bool) $activeSkema, 403, 'Skema asesor belum ditetapkan. Hubungi admin.');

        $defaults = [
            'kode_form' => 'FR.IA.01.',
            'judul_form' => 'CEKLIS OBSERVASI AKTIVITAS PRAKTIK',
            'skema_id' => $activeSkema->id,
            'catatan_footer' => '* Coret yang tidak perlu',
            'ttd_asesor_nama' => $asesor->nama,
            'ttd_asesor_no_reg' => $asesor->no_met,
        ];

        $requestedSkemaId = (int) $request->get('skema_id');
        if ($requestedSkemaId && $skemas->contains('id', $requestedSkemaId)) {
            $activeSkema = $skemas->firstWhere('id', $requestedSkemaId) ?? $activeSkema;
            $defaults['skema_id'] = $activeSkema->id;
        }

        $requestedAsesiNik = trim((string) $request->get('asesi_nik'));
        if ($requestedAsesiNik !== '') {
            $asesi = Asesi::query()
                ->where('NIK', $requestedAsesiNik)
                ->whereHas('skemas', function ($query) use ($activeSkema) {
                    $query->where('skemas.id', $activeSkema->id);
                })
                ->first(['NIK', 'nama']);

            if ($asesi) {
                // Guard: pastikan persetujuan asesmen sudah ditandatangani oleh KEDUA pihak
                $persetujuan = PersetujuanAsesmen::query()
                    ->where('nomor_skema', $activeSkema->nomor_skema)
                    ->where(function ($q) use ($asesi) {
                        $q->where('asesi_nik', $asesi->NIK)
                          ->orWhere('nama_asesi', $asesi->nama);
                    })
                    ->latest('id')
                    ->first();

                $signedByAsesor = !empty($persetujuan?->ttd_asesor_nama);
                $signedByAsesi  = !empty($persetujuan?->ttd_asesi_nama);

                if (!$persetujuan || !$signedByAsesor || !$signedByAsesi) {
                    return redirect()->route('asesor.asesi.index')
                        ->with('error', 'Ceklis Observasi hanya dapat diisi setelah Persetujuan Asesmen ditandatangani oleh asesor dan asesi.');
                }

                $existing = CeklisObservasiAktivitasPraktik::query()
                    ->where('asesor_id', $asesor->ID_asesor)
                    ->where('asesi_nik', $asesi->NIK)
                    ->where('skema_id', $activeSkema->id)
                    ->latest('id')
                    ->first(['id']);

                if ($existing) {
                    return redirect()->route('asesor.ceklis-observasi.show', $existing->id);
                }

                $defaults['asesi_nik']    = $asesi->NIK;
                $defaults['asesi_nama']   = $asesi->nama;
                $defaults['ttd_asesi_nama'] = $asesi->nama;

                // Auto-fill TUK dari jadwal asesi (via jadwal_peserta atau kelompok)
                $jadwalForAsesi = \App\Models\JadwalUjikom::query()
                    ->with('tuk')
                    ->where('skema_id', $activeSkema->id)
                    ->whereHas('peserta', function ($q) use ($asesi) {
                        $q->where('NIK', $asesi->NIK);
                    })
                    ->orderBy('tanggal_mulai')
                    ->first();

                if (!$jadwalForAsesi) {
                    // Fallback: cari via kelompok asesi
                    $asesiWithKelompok = \App\Models\Asesi::where('NIK', $asesi->NIK)
                        ->first(['NIK', 'kelompok_id']);
                    if ($asesiWithKelompok?->kelompok_id) {
                        $jadwalForAsesi = \App\Models\JadwalUjikom::query()
                            ->with('tuk')
                            ->where('skema_id', $activeSkema->id)
                            ->whereHas('kelompoks', function ($q) use ($asesiWithKelompok) {
                                $q->where('kelompok.id', $asesiWithKelompok->kelompok_id);
                            })
                            ->orderBy('tanggal_mulai')
                            ->first();
                    }
                }

                if ($jadwalForAsesi) {
                    $defaults['tuk']      = $jadwalForAsesi->tuk?->nama_tuk ?? '';
                    $defaults['tipe_tuk'] = $jadwalForAsesi->tuk?->tipe_tuk ?? '';
                }

            }
        }

        $savedSignature = $this->formatSignatureToUrl($asesor?->saved_tanda_tangan);

        return view('asesor.ceklis-observasi.create', compact('account', 'asesor', 'activeSkema', 'skemas', 'defaults', 'savedSignature'));
    }

    public function store(Request $request)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        // Guard: validasi persetujuan asesmen sebelum menyimpan data
        $skemaId  = (int) $request->input('skema_id');
        $asesiNik = trim((string) $request->input('asesi_nik'));
        if ($skemaId && $asesiNik) {
            $skema = \App\Models\Skema::find($skemaId);
            if ($skema) {
                // Cari nama asesi untuk query persetujuan berdasarkan nama_asesi
                $asesiObj = Asesi::where('NIK', $asesiNik)->first(['NIK', 'nama']);
                $asesiNama = $asesiObj?->nama ?? '';

                $persetujuan = PersetujuanAsesmen::query()
                    ->where('nomor_skema', $skema->nomor_skema)
                    ->where(function ($q) use ($asesiNik, $asesiNama) {
                        $q->where('asesi_nik', $asesiNik);
                        if ($asesiNama !== '') {
                            $q->orWhere('nama_asesi', $asesiNama);
                        }
                    })
                    ->latest('id')
                    ->first();

                $signedByAsesor = !empty($persetujuan?->ttd_asesor_nama);
                $signedByAsesi  = !empty($persetujuan?->ttd_asesi_nama);

                if (!$persetujuan || !$signedByAsesor || !$signedByAsesi) {
                    return redirect()->back()
                        ->with('error', 'Ceklis Observasi hanya dapat disimpan setelah Persetujuan Asesmen ditandatangani oleh asesor dan asesi.')
                        ->withInput();
                }
            }
        }

        [$data, $details] = $this->validatedData($request, $asesor, false);

        if ($request->input('simpan_tanda_tangan') === '1' && $asesor && !empty($data['ttd_asesor_file'])) {
            $asesor->update(['saved_tanda_tangan' => $data['ttd_asesor_file']]);
        }

        DB::transaction(function () use ($data, $details) {
            $item = CeklisObservasiAktivitasPraktik::create($data);
            $item->details()->createMany($details);
        });

        return redirect()->route('asesor.ceklis-observasi.index')
            ->with('success', 'Ceklis observasi berhasil disimpan.');
    }

    private function formatSignatureToUrl(?string $sig): ?string
    {
        if (empty($sig)) {
            return null;
        }

        $sig = trim($sig);

        if (str_contains($sig, '/storage/')) {
            $relativePath = ltrim(explode('/storage/', $sig)[1], '/');
            return asset('storage/' . $relativePath);
        }

        if (str_starts_with($sig, 'http://') || str_starts_with($sig, 'https://')) {
            return $sig;
        }

        if (str_starts_with($sig, 'ceklis-observasi/') || str_starts_with($sig, 'persetujuan-asesmen/') || str_starts_with($sig, 'signatures/') || str_starts_with($sig, 'pendaftar/')) {
            return asset('storage/' . ltrim($sig, '/'));
        }

        if (str_starts_with($sig, 'data:image')) {
            return preg_replace('/\s+/', '', $sig);
        }

        $clean = preg_replace('/\s+/', '', $sig);
        return 'data:image/png;base64,' . $clean;
    }

    public function show($id)
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $item = CeklisObservasiAktivitasPraktik::query()
            ->with([
                'skema:id,nama_skema,nomor_skema,jenis_skema',
                'asesi:NIK,nama',
                'details.unit:id,kode_unit,judul_unit',
                'details.elemen:id,unit_id,nama_elemen',
                'details.kriteria:id,elemen_id,deskripsi_kriteria,urutan',
            ])
            ->where('asesor_id', $asesor->ID_asesor)
            ->findOrFail($id);

        $detailsByUnit = $item->details
            ->sortBy([
                ['unit_id', 'asc'],
                ['elemen_id', 'asc'],
                ['kriteria.urutan', 'asc'],
                ['kriteria_id', 'asc'],
            ])
            ->groupBy('unit_id');

        return view('asesor.ceklis-observasi.show', compact('account', 'asesor', 'item', 'detailsByUnit'));
    }

    public function export($id)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $item = CeklisObservasiAktivitasPraktik::query()
            ->with([
                'skema:id,nama_skema,nomor_skema,jenis_skema',
                'asesi:NIK,nama',
                'details.unit:id,kode_unit,judul_unit',
                'details.elemen:id,unit_id,nama_elemen',
                'details.kriteria:id,elemen_id,deskripsi_kriteria,urutan',
            ])
            ->where('asesor_id', $asesor->ID_asesor)
            ->findOrFail($id);

        if (empty($item->ttd_asesi_file)) {
            return redirect()->back()->with('error', 'Form FR.IA.01 belum dapat diexport karena asesi belum menandatangani ceklis observasi.');
        }

        $detailsByUnit = $item->details
            ->sortBy([
                ['unit_id', 'asc'],
                ['elemen_id', 'asc'],
                ['kriteria.urutan', 'asc'],
                ['kriteria_id', 'asc'],
            ])
            ->groupBy('unit_id');

        $logoPath = public_path('images/lsp.png');
        $logoDataUri = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        // Convert signature files to Base64 data URIs (same as FR.AK.01 export)
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

        $html = view('asesor.ceklis-observasi.export-docx', [
            'item'           => $item,
            'detailsByUnit'  => $detailsByUnit,
            'logoPath'       => $logoPath,
            'logoDataUri'    => $logoDataUri,
            'ttdAsesiDataUri' => $ttdAsesiDataUri,
            'ttdAsesorDataUri' => $ttdAsesorDataUri,
        ])->render();

        $fileSkema = preg_replace('/[^A-Za-z0-9\-]+/', '-', (string) ($item->skema?->nomor_skema ?? $item->skema_id));
        $fileName = 'FR.IA.01-' . ($item->asesi_nik ?? 'asesi') . '-' . trim($fileSkema, '-') . '.doc';

        return response($html, 200, [
            'Content-Type' => 'application/msword; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function edit($id)
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $skemas = $asesor->skemas()
            ->orderBy('nama_skema')
            ->get(['skemas.id', 'nama_skema', 'nomor_skema', 'jenis_skema']);

        $item = CeklisObservasiAktivitasPraktik::query()
            ->with(['details', 'skema:id,nama_skema,nomor_skema,jenis_skema'])
            ->where('asesor_id', $asesor->ID_asesor)
            ->findOrFail($id);

        $activeSkema = $item->skema;
        abort_unless((bool) $activeSkema, 403, 'Skema pada data ceklis tidak ditemukan.');

        $savedSignature = $this->formatSignatureToUrl($asesor?->saved_tanda_tangan);

        return view('asesor.ceklis-observasi.edit', compact('account', 'asesor', 'item', 'activeSkema', 'skemas', 'savedSignature'));
    }

    public function update(Request $request, $id)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $item = CeklisObservasiAktivitasPraktik::query()
            ->where('asesor_id', $asesor->ID_asesor)
            ->findOrFail($id);

        [$data, $details] = $this->validatedData($request, $asesor, true);

        if ($request->input('simpan_tanda_tangan') === '1' && $asesor && !empty($data['ttd_asesor_file'])) {
            $asesor->update(['saved_tanda_tangan' => $data['ttd_asesor_file']]);
        }

        DB::transaction(function () use ($item, $data, $details) {
            $item->update($data);
            $item->details()->delete();
            $item->details()->createMany($details);
        });

        return redirect()->route('asesor.ceklis-observasi.index')
            ->with('success', 'Ceklis observasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $item = CeklisObservasiAktivitasPraktik::query()
            ->where('asesor_id', $asesor->ID_asesor)
            ->findOrFail($id);

        $item->delete();

        return redirect()->route('asesor.ceklis-observasi.index')
            ->with('success', 'Ceklis observasi berhasil dihapus.');
    }

    public function participantsBySkema(Request $request)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
        ]);

        $skemaId = (int) $validated['skema_id'];

        $isOwnedSkema = $asesor->skemas->contains('id', $skemaId);
        abort_unless($isOwnedSkema, 403, 'Skema tidak termasuk penugasan asesor Anda.');

        $asesi = Asesi::query()
            ->whereHas('skemas', function ($query) use ($skemaId) {
                $query->where('skemas.id', $skemaId);
            })
            ->whereNotExists(function ($query) use ($skemaId) {
                $query->select(DB::raw(1))
                    ->from('ceklis_observasi_aktivitas_praktiks')
                    ->whereColumn('ceklis_observasi_aktivitas_praktiks.asesi_nik', 'asesi.NIK')
                    ->where('ceklis_observasi_aktivitas_praktiks.skema_id', $skemaId);
            })
            ->orderBy('nama')
            ->get(['NIK', 'nama'])
            ->map(fn ($item) => [
                'id' => (string) $item->NIK,
                'nama' => $item->nama,
            ])
            ->values();

        return response()->json([
            'asesi' => $asesi,
            'asesor' => [[
                'id' => (string) $asesor->ID_asesor,
                'nama' => $asesor->nama,
                'no_reg' => $asesor->no_met,
            ]],
        ]);
    }

    public function getAsesiData(Request $request)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $validated = $request->validate([
            'asesi_nik' => 'required|exists:asesi,NIK',
            'skema_id' => 'required|exists:skemas,id',
        ]);

        $skemaId = (int) $validated['skema_id'];
        $isOwnedSkema = $asesor->skemas->contains('id', $skemaId);
        abort_unless($isOwnedSkema, 403, 'Skema tidak termasuk penugasan asesor Anda.');

        $asesi = Asesi::query()
            ->where('NIK', $validated['asesi_nik'])
            ->whereHas('skemas', function ($query) use ($skemaId) {
                $query->where('skemas.id', $skemaId);
            })
            ->first(['NIK', 'nama', 'kelompok_id']);

        if (!$asesi) {
            return response()->json(['asesi' => null], 404);
        }

        $tukName = null;
        $tipeTuk = null;
        $tanggal = null;

        // Cari jadwal via jadwal_peserta (direct asesi linkage)
        $jadwalPick = \App\Models\JadwalUjikom::query()
            ->with('tuk')
            ->where('skema_id', $skemaId)
            ->whereHas('peserta', function ($query) use ($validated) {
                $query->where('NIK', $validated['asesi_nik']);
            })
            ->orderBy('tanggal_mulai')
            ->first();

        // Fallback: cari jadwal via kelompok asesi
        if (!$jadwalPick && $asesi->kelompok_id) {
            $jadwalPick = \App\Models\JadwalUjikom::query()
                ->with('tuk')
                ->where('skema_id', $skemaId)
                ->whereHas('kelompoks', function ($query) use ($asesi) {
                    $query->where('kelompok.id', $asesi->kelompok_id);
                })
                ->orderBy('tanggal_mulai')
                ->first();
        }

        if ($jadwalPick) {
            $tukName  = $jadwalPick->tuk?->nama_tuk;
            $tipeTuk  = $jadwalPick->tuk?->tipe_tuk;
            $tanggal  = $jadwalPick->tanggal_mulai
                ? Carbon::parse($jadwalPick->tanggal_mulai)->format('Y-m-d')
                : ($jadwalPick->tanggal_selesai
                    ? Carbon::parse($jadwalPick->tanggal_selesai)->format('Y-m-d')
                    : null);
        }

        if (!$tukName) {
            $skema = Skema::find($skemaId);
            if ($skema) {
                $persetujuan = PersetujuanAsesmen::query()
                    ->where('nomor_skema', $skema->nomor_skema)
                    ->where('nama_asesi', $asesi->nama)
                    ->latest('id')
                    ->first(['tuk']);

                $tukName = $persetujuan?->tuk;
            }
        }

        return response()->json([
            'asesi' => [
                'id'   => (string) $asesi->NIK,
                'nama' => $asesi->nama,
            ],
            'tuk'      => $tukName,
            'tipe_tuk' => $tipeTuk,
            'tanggal'  => $tanggal,
        ]);
    }

    public function skemaStructure(Request $request)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
        ]);

        $skemaId = (int) $validated['skema_id'];
        $isOwnedSkema = $asesor->skemas->contains('id', $skemaId);
        abort_unless($isOwnedSkema, 403, 'Skema tidak termasuk penugasan asesor Anda.');

        $skema = Skema::query()
            ->with([
                'units' => fn ($query) => $query->orderBy('id'),
                'units.elemens' => fn ($query) => $query->orderBy('id'),
                'units.elemens.kriteria' => fn ($query) => $query->orderBy('urutan')->orderBy('id'),
            ])
            ->findOrFail($skemaId);

        $units = $skema->units->map(function ($unit) {
            return [
                'id' => $unit->id,
                'kelompok_pekerjaan' => $unit->kelompok_pekerjaan,
                'kode_unit' => $unit->kode_unit,
                'judul_unit' => $unit->judul_unit,
                'elemens' => $unit->elemens->map(function ($elemen) {
                    return [
                        'id' => $elemen->id,
                        'nama_elemen' => $elemen->nama_elemen,
                        'kriteria' => $elemen->kriteria->map(function ($kriteria) {
                            return [
                                'id' => $kriteria->id,
                                'deskripsi_kriteria' => $kriteria->deskripsi_kriteria,
                                'urutan' => $kriteria->urutan,
                            ];
                        })->values(),
                    ];
                })->values(),
            ];
        })->values();

        return response()->json([
            'skema' => [
                'id' => $skema->id,
                'nama_skema' => $skema->nama_skema,
                'nomor_skema' => $skema->nomor_skema,
            ],
            'units' => $units,
        ]);
    }

    private function validatedData(Request $request, Asesor $asesor, bool $isUpdate): array
    {
        $data = $request->validate([
            'kode_form' => 'required|string|max:20',
            'judul_form' => 'required|string|max:255',
            'skema_id' => 'required|exists:skemas,id',
            'asesi_nik' => 'required|exists:asesi,NIK',
            'tuk' => 'nullable|string|max:255',
            'tanggal' => 'nullable|date',
            'rekomendasi' => 'required|in:kompeten,belum_kompeten',
            'belum_kompeten_kelompok_pekerjaan' => 'nullable|string|max:255',
            'belum_kompeten_unit' => 'nullable|string|max:255',
            'belum_kompeten_elemen' => 'nullable|string|max:255',
            'belum_kompeten_kuk' => 'nullable|string|max:255',
            'ttd_asesi_nama' => 'nullable|string|max:255',
            'ttd_asesi_tanggal' => 'nullable|date',
            'ttd_asesi_file' => 'nullable|string',
            'ttd_asesor_nama' => 'nullable|string|max:255',
            'ttd_asesor_no_reg' => 'nullable|string|max:255',
            'ttd_asesor_tanggal' => 'nullable|date',
            'ttd_asesor_file' => 'nullable|string',
            'catatan_footer' => 'nullable|string|max:255',
            'detail' => 'required|array|min:1',
            'detail.*.unit_id' => 'required|exists:units,id',
            'detail.*.elemen_id' => 'required|exists:elemens,id',
            'detail.*.kriteria_id' => 'required|exists:kriteria,id',
            'detail.*.pencapaian' => 'nullable|in:ya,tidak',
            'detail.*.penilaian_lanjut' => 'nullable|string',
        ]);

        if (!$asesor->skemas->contains('id', (int) $data['skema_id'])) {
            throw ValidationException::withMessages([
                'skema_id' => 'Skema tidak termasuk penugasan asesor Anda.',
            ]);
        }

        $validAsesi = Asesi::query()
            ->where('NIK', $data['asesi_nik'])
            ->whereHas('skemas', function ($query) use ($data) {
                $query->where('skemas.id', $data['skema_id']);
            })
            ->exists();

        if (!$validAsesi) {
            throw ValidationException::withMessages([
                'asesi_nik' => 'Asesi tidak termasuk penugasan Anda pada skema ini.',
            ]);
        }

        $rawDetails = array_values($data['detail']);
        unset($data['detail']);

        $kriteriaIds = collect($rawDetails)->pluck('kriteria_id')->unique()->values();

        $allowedKriteria = Kriteria::query()
            ->join('elemens', 'kriteria.elemen_id', '=', 'elemens.id')
            ->join('units', 'elemens.unit_id', '=', 'units.id')
            ->where('units.skema_id', $data['skema_id'])
            ->whereIn('kriteria.id', $kriteriaIds)
            ->pluck('kriteria.id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $allowedLookup = array_fill_keys($allowedKriteria, true);

        $details = collect($rawDetails)
            ->filter(function ($detail) use ($allowedLookup) {
                return isset($allowedLookup[(int) $detail['kriteria_id']]);
            })
            ->map(function ($detail) {
                return [
                    'unit_id' => (int) $detail['unit_id'],
                    'elemen_id' => (int) $detail['elemen_id'],
                    'kriteria_id' => (int) $detail['kriteria_id'],
                    'pencapaian' => $detail['pencapaian'] ?? null,
                    'penilaian_lanjut' => $detail['penilaian_lanjut'] ?? null,
                ];
            })
            ->values()
            ->all();

        if (count($details) === 0) {
            throw ValidationException::withMessages([
                'detail' => 'Detail ceklis tidak valid untuk skema yang dipilih.',
            ]);
        }

        // Force ownership to the logged-in asesor.
        $data['asesor_id'] = $asesor->ID_asesor;
        if (empty($data['ttd_asesor_nama'])) {
            $data['ttd_asesor_nama'] = $asesor->nama;
        }
        if (empty($data['ttd_asesor_no_reg'])) {
            $data['ttd_asesor_no_reg'] = $asesor->no_met;
        }

        // Decode and save signature files if base64 data URL
        foreach (['ttd_asesor_file' => 'signature_asesor_', 'ttd_asesi_file' => 'signature_asesi_'] as $field => $prefix) {
            if (!empty($data[$field]) && strpos($data[$field], 'data:image') === 0) {
                try {
                    $signatureData = $data[$field];
                    list($type, $signatureData) = explode(';', $signatureData);
                    list(, $signatureData) = explode(',', $signatureData);
                    $signatureData = base64_decode($signatureData);

                    $filename = $prefix . uniqid() . '_' . time() . '.png';
                    $path = 'ceklis-observasi/signatures';

                    \Illuminate\Support\Facades\Storage::disk('public')->put($path . '/' . $filename, $signatureData);
                    $data[$field] = $path . '/' . $filename;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to save ' . $field . ': ' . $e->getMessage());
                    unset($data[$field]);
                }
            }
        }

        return [$data, $details];
    }
}
