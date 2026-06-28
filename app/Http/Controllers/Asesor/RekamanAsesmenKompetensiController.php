<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\RekamanAsesmenKompetensi;
use App\Models\PersetujuanAsesmen;
use App\Models\Skema;
use App\Models\JadwalUjikom;
use App\Models\Unit;
use App\Models\CeklisObservasiAktivitasPraktik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class RekamanAsesmenKompetensiController extends Controller
{
    private function getAsesor(): ?Asesor
    {
        $account = Auth::guard('account')->user();

        if (!$account) {
            return null;
        }

        return Asesor::with('skemas')->where('no_met', $account->id)->first();
    }

    private function isAsesiAssignedToSkema(Asesor $asesor, string $asesiNik, int $skemaId): bool
    {
        $assignedDirectly = Asesi::query()
            ->where('NIK', $asesiNik)
            ->where('ID_asesor', $asesor->ID_asesor)
            ->exists();

        if ($assignedDirectly) {
            return true;
        }

        return Asesi::query()
            ->where('NIK', $asesiNik)
            ->whereHas('skemas', function ($query) use ($skemaId) {
                $query->where('skemas.id', $skemaId);
            })
            ->exists();
    }

    private function resolveJadwalForAsesiSkema(string $asesiNik, int $skemaId): ?JadwalUjikom
    {
        return JadwalUjikom::query()
            ->with('tuk')
            ->where('skema_id', $skemaId)
            ->whereHas('peserta', function ($query) use ($asesiNik) {
                $query->where('NIK', $asesiNik);
            })
            ->latest('id')
            ->first();
    }

    public function index(Request $request)
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();
        $search = trim((string) $request->get('search'));
        $rekomendasi = trim((string) $request->get('rekomendasi'));

        if (!$asesor) {
            $items = RekamanAsesmenKompetensi::query()->whereRaw('1 = 0')->paginate(10);

            if ($request->ajax()) {
                return view('asesor.rekaman-asesmen-kompetensi.partials.table-rows', compact('items'))->render();
            }

            return view('asesor.rekaman-asesmen-kompetensi.index', compact('account', 'asesor', 'items', 'search', 'rekomendasi'));
        }

        $items = RekamanAsesmenKompetensi::query()
            ->with([
                'skema:id,nama_skema,nomor_skema',
                'asesi:NIK,nama',
            ])
            ->where('asesor_id', $asesor->ID_asesor)
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
                        });
                });
            })
            ->when($rekomendasi !== '', function ($query) use ($rekomendasi) {
                $query->where('rekomendasi', $rekomendasi);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return view('asesor.rekaman-asesmen-kompetensi.partials.table-rows', compact('items'))->render();
        }

        return view('asesor.rekaman-asesmen-kompetensi.index', compact('account', 'asesor', 'items', 'search', 'rekomendasi'));
    }

    public function create(Request $request)
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();

        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $skemaList = $asesor->skemas()
            ->orderBy('nama_skema')
            ->get(['skemas.id', 'nama_skema', 'nomor_skema', 'jenis_skema']);

        abort_unless($skemaList->count() > 0, 403, 'Skema asesor belum ditetapkan. Hubungi admin.');

        $defaults = [
            'kode_form' => 'FR.AK.02.',
            'judul_form' => 'REKAMAN ASESMEN KOMPETENSI',
            'kategori_skema' => '',
            'tuk' => '',
            'tipe_tuk' => '',
            'asesi_nik' => null,
            'skema_id' => null,
        ];

        // Pre-fill with skema_id if provided
        if ($request->has('skema_id')) {
            $skemaId = (int) $request->get('skema_id');
            if ($skemaList->contains('id', $skemaId)) {
                $defaults['skema_id'] = $skemaId;
            }
        }

        // If skema not provided but only one skema available
        if (!$defaults['skema_id'] && $skemaList->count() === 1) {
            $defaults['skema_id'] = $skemaList->first()->id;
        }

        // Pre-fill with asesi_nik if provided. Accept if NIK exists in DB
        // (fallback API will provide full data even if not assigned to this asesor)
        if ($request->has('asesi_nik')) {
            $asesiNik = (string) trim($request->get('asesi_nik'));

            $existsAny = Asesi::query()
                ->where('NIK', $asesiNik)
                ->exists();

            if ($existsAny) {
                $defaults['asesi_nik'] = $asesiNik;
            }
        }

        // Enforce that a Ceklis Observasi exists for this asesi + skema before allowing Rekaman creation
        if ($defaults['skema_id'] && $defaults['asesi_nik']) {
            $asesiModel = Asesi::where('NIK', $defaults['asesi_nik'])->first(['NIK', 'nama', 'kelompok_id']);
            $jadwalPick = null;
            if ($asesiModel) {
                $jadwalPick = JadwalUjikom::query()
                    ->with('tuk')
                    ->where('skema_id', (int) $defaults['skema_id'])
                    ->whereHas('peserta', function ($query) use ($asesiModel) {
                        $query->where('NIK', $asesiModel->NIK);
                    })
                    ->orderBy('tanggal_mulai')
                    ->first();

                if (!$jadwalPick && $asesiModel->kelompok_id) {
                    $jadwalPick = JadwalUjikom::query()
                        ->with('tuk')
                        ->where('skema_id', (int) $defaults['skema_id'])
                        ->whereHas('kelompoks', function ($query) use ($asesiModel) {
                            $query->where('kelompok.id', $asesiModel->kelompok_id);
                        })
                        ->orderBy('tanggal_mulai')
                        ->first();
                }
            }

            if ($jadwalPick?->tuk) {
                $defaults['tuk'] = $jadwalPick->tuk->nama_tuk ?? '';
                $defaults['tipe_tuk'] = $jadwalPick->tuk->tipe_tuk ?? '';
            } else {
                $skema = Skema::find((int) $defaults['skema_id']);
                if ($skema && $asesiModel) {
                    $persetujuan = PersetujuanAsesmen::query()
                        ->where('nomor_skema', $skema->nomor_skema)
                        ->where('nama_asesi', $asesiModel->nama)
                        ->latest('id')
                        ->first(['tuk']);
                    if ($persetujuan) {
                        $defaults['tuk'] = $persetujuan->tuk;
                        $tukRecord = \App\Models\Tuk::where('nama_tuk', $persetujuan->tuk)->first();
                        if ($tukRecord) {
                            $defaults['tipe_tuk'] = $tukRecord->tipe_tuk;
                        }
                    }
                }
            }

            $hasCeklis = CeklisObservasiAktivitasPraktik::query()
                ->where('skema_id', (int) $defaults['skema_id'])
                ->where('asesi_nik', $defaults['asesi_nik'])
                ->exists();

            if (!$hasCeklis) {
                return redirect()->route('asesor.ceklis-observasi.create', [
                    'asesi_nik' => $defaults['asesi_nik'],
                    'skema_id' => $defaults['skema_id'],
                ])->with('info', 'Silakan isi Ceklis Observasi terlebih dahulu sebelum membuat Rekaman Asesmen.');
            }
        }

        return view('asesor.rekaman-asesmen-kompetensi.create', compact('account', 'asesor', 'skemaList', 'defaults'));
    }

    public function store(Request $request)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        // If request provides asesi_nik and skema_id, ensure pivot exists so validation passes
        $reqAsesiNik = $request->get('asesi_nik');
        $reqSkemaId = $request->get('skema_id');
        if ($reqAsesiNik && $reqSkemaId) {
            $asesiModel = Asesi::query()->where('NIK', (string) $reqAsesiNik)->first();
            if ($asesiModel) {
                $attached = $asesiModel->skemas()->where('skemas.id', (int) $reqSkemaId)->exists();
                if (!$attached) {
                    // Attach with minimal pivot data; do not overwrite existing relations
                    $asesiModel->skemas()->syncWithoutDetaching([(int) $reqSkemaId => ['status' => 'terdaftar']]);
                }
            }
        }

        [$data, $details] = $this->validatedData($request, $asesor);

        DB::transaction(function () use ($data, $details) {
            $item = RekamanAsesmenKompetensi::create($data);
            $item->details()->createMany($details);
        });

        return redirect()->route('asesor.rekaman-asesmen-kompetensi.index')
            ->with('success', 'Rekaman asesmen kompetensi berhasil disimpan.');
    }

    public function show($id)
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();

        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $item = RekamanAsesmenKompetensi::query()
            ->with([
                'skema:id,nama_skema,nomor_skema',
                'asesi:NIK,nama',
                'details.unit:id,kode_unit,judul_unit',
            ])
            ->where('asesor_id', $asesor->ID_asesor)
            ->findOrFail($id);

        $details = $item->details->sortBy([
            ['unit.id', 'asc'],
        ])->values();

        return view('asesor.rekaman-asesmen-kompetensi.show', compact('account', 'asesor', 'item', 'details'));
    }

    public function export($id)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $item = RekamanAsesmenKompetensi::query()
            ->with([
                'skema:id,nama_skema,nomor_skema,jenis_skema',
                'asesi:NIK,nama',
                'details.unit:id,kode_unit,judul_unit',
            ])
            ->where('asesor_id', $asesor->ID_asesor)
            ->findOrFail($id);

        $ceklis = CeklisObservasiAktivitasPraktik::query()
            ->where('skema_id', $item->skema_id)
            ->where('asesi_nik', $item->asesi_nik)
            ->first();

        $logoPath = public_path('images/lsp.png');
        $logoDataUri = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : null;

        $details = $item->details->sortBy([
            ['unit.id', 'asc'],
        ])->values();

        $html = view('asesor.rekaman-asesmen-kompetensi.export-docx', [
            'item' => $item,
            'ceklis' => $ceklis,
            'details' => $details,
            'logoPath' => $logoPath,
            'logoDataUri' => $logoDataUri,
        ])->render();

        $fileSkema = preg_replace('/[^A-Za-z0-9\-]+/', '-', (string) ($item->skema?->nomor_skema ?? $item->skema_id));
        $fileName = 'FR.AK.02-' . ($item->asesi_nik ?? 'asesi') . '-' . trim($fileSkema, '-') . '.doc';

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

        $item = RekamanAsesmenKompetensi::query()
            ->with(['details', 'skema:id,nama_skema,nomor_skema'])
            ->where('asesor_id', $asesor->ID_asesor)
            ->findOrFail($id);

        $skemaList = $asesor->skemas()
            ->orderBy('nama_skema')
            ->get(['skemas.id', 'nama_skema', 'nomor_skema', 'jenis_skema']);

        return view('asesor.rekaman-asesmen-kompetensi.edit', compact('account', 'asesor', 'item', 'skemaList'));
    }

    public function update(Request $request, $id)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $item = RekamanAsesmenKompetensi::query()
            ->where('asesor_id', $asesor->ID_asesor)
            ->findOrFail($id);

        [$data, $details] = $this->validatedData($request, $asesor);

        DB::transaction(function () use ($item, $data, $details) {
            $item->update($data);
            $item->details()->delete();
            $item->details()->createMany($details);
        });

        return redirect()->route('asesor.rekaman-asesmen-kompetensi.index')
            ->with('success', 'Rekaman asesmen kompetensi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $item = RekamanAsesmenKompetensi::query()
            ->where('asesor_id', $asesor->ID_asesor)
            ->findOrFail($id);

        $item->delete();

        return redirect()->route('asesor.rekaman-asesmen-kompetensi.index')
            ->with('success', 'Rekaman asesmen kompetensi berhasil dihapus.');
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

        $directAsesi = Asesi::query()
            ->where('ID_asesor', $asesor->ID_asesor)
            ->whereNotExists(function ($query) use ($skemaId) {
                $query->select(DB::raw(1))
                    ->from('rekaman_asesmen_kompetensi')
                    ->whereColumn('rekaman_asesmen_kompetensi.asesi_nik', 'asesi.NIK')
                    ->where('rekaman_asesmen_kompetensi.skema_id', $skemaId);
            })
            ->with(['jurusan:ID_jurusan,kode_jurusan,nama_jurusan'])
            ->get(['NIK', 'nama', 'email', 'telepon_hp', 'ID_jurusan']);

        $skemaAsesi = Asesi::query()
            ->whereHas('skemas', function ($query) use ($skemaId) {
                $query->where('skemas.id', $skemaId);
            })
            ->whereNotExists(function ($query) use ($skemaId) {
                $query->select(DB::raw(1))
                    ->from('rekaman_asesmen_kompetensi')
                    ->whereColumn('rekaman_asesmen_kompetensi.asesi_nik', 'asesi.NIK')
                    ->where('rekaman_asesmen_kompetensi.skema_id', $skemaId);
            })
            ->with(['jurusan:ID_jurusan,kode_jurusan,nama_jurusan'])
            ->get(['NIK', 'nama', 'email', 'telepon_hp', 'ID_jurusan']);

        $asesi = $directAsesi
            ->concat($skemaAsesi)
            ->unique(fn ($item) => (string) $item->NIK)
            ->sortBy('nama')
            ->values()
            ->map(fn ($item) => [
                'id' => (string) $item->NIK,
                'nama' => $item->nama,
                'email' => $item->email,
                'telepon_hp' => $item->telepon_hp,
                'jurusan' => $item->jurusan?->kode_jurusan ? trim($item->jurusan->kode_jurusan . ' - ' . $item->jurusan->nama_jurusan) : ($item->jurusan?->nama_jurusan ?? '-'),
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

        $asesiNik = $request->get('asesi_nik');
        abort_unless($asesiNik, 400, 'asesi_nik is required');
        $selectedSkemaId = (int) $request->get('skema_id');

        $asesi = Asesi::query()
            ->where('NIK', $asesiNik)
            ->where('ID_asesor', $asesor->ID_asesor)
            ->with(['jurusan:ID_jurusan,kode_jurusan,nama_jurusan', 'kelompok.jadwals'])
            ->first(['NIK', 'nama', 'email', 'telepon_hp', 'ID_jurusan', 'kelompok_id']);

        if (!$asesi) {
            $asesi = Asesi::query()
                ->where('NIK', $asesiNik)
                ->with(['jurusan:ID_jurusan,kode_jurusan,nama_jurusan', 'kelompok.jadwals'])
                ->first(['NIK', 'nama', 'email', 'telepon_hp', 'ID_jurusan', 'kelompok_id']);
        }

        if (!$asesi) {
            return response()->json(['asesi' => null], 404);
        }

        $skemaIds = [];
        try {
            $skemaIds = method_exists($asesi, 'skemas') ? $asesi->skemas()->pluck('id')->map(fn ($i) => (int) $i)->values()->all() : [];
        } catch (\Throwable $e) {
            $skemaIds = [];
        }

        $tukName = null;
        $tipeTuk = null;
        $jadwalData = null;

        if ($selectedSkemaId > 0) {
            $jadwalPick = JadwalUjikom::query()
                ->with('tuk')
                ->where('skema_id', $selectedSkemaId)
                ->whereHas('peserta', function ($query) use ($asesi) {
                    $query->where('NIK', $asesi->NIK);
                })
                ->orderBy('tanggal_mulai')
                ->first();

            if (!$jadwalPick && $asesi->kelompok_id) {
                $jadwalPick = JadwalUjikom::query()
                    ->with('tuk')
                    ->where('skema_id', $selectedSkemaId)
                    ->whereHas('kelompoks', function ($query) use ($asesi) {
                        $query->where('kelompok.id', $asesi->kelompok_id);
                    })
                    ->orderBy('tanggal_mulai')
                    ->first();
            }

            if ($jadwalPick) {
                $tukName = $jadwalPick->tuk?->nama_tuk;
                $tipeTuk = $jadwalPick->tuk?->tipe_tuk;
                $jadwalData = [
                    'tanggal_mulai' => $jadwalPick->tanggal_mulai?->format('Y-m-d') ?? null,
                    'tanggal_selesai' => $jadwalPick->tanggal_selesai?->format('Y-m-d') ?? null,
                ];
            }
        }

        if (!$tukName && $selectedSkemaId > 0) {
            $skema = Skema::find($selectedSkemaId);
            if ($skema) {
                $persetujuan = PersetujuanAsesmen::query()
                    ->where('nomor_skema', $skema->nomor_skema)
                    ->where('nama_asesi', $asesi->nama)
                    ->latest('id')
                    ->first(['tuk']);
                $tukName = $persetujuan?->tuk;
                if ($tukName) {
                    $tukRecord = \App\Models\Tuk::where('nama_tuk', $tukName)->first();
                    if ($tukRecord) {
                        $tipeTuk = $tukRecord->tipe_tuk;
                    }
                }
            }
        }

        return response()->json([
            'asesi' => [
                'id' => (string) $asesi->NIK,
                'nama' => $asesi->nama,
                'email' => $asesi->email,
                'telepon_hp' => $asesi->telepon_hp,
                'jurusan' => $asesi->jurusan?->kode_jurusan ? trim($asesi->jurusan->kode_jurusan . ' - ' . $asesi->jurusan->nama_jurusan) : ($asesi->jurusan?->nama_jurusan ?? '-'),
                'skema_ids' => $skemaIds,
                'jadwal' => $jadwalData,
                'tuk' => $tukName,
                'tuk_pelaksanaan' => $tukName,
                'tipe_tuk' => $tipeTuk,
            ],
        ]);
    }

    public function skemaUnits(Request $request)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
        ]);

        $skemaId = (int) $validated['skema_id'];
        $isOwnedSkema = $asesor->skemas->contains('id', $skemaId);
        abort_unless($isOwnedSkema, 403, 'Skema tidak termasuk penugasan asesor Anda.');

        $units = Unit::query()
            ->where('skema_id', $skemaId)
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

    private function validatedData(Request $request, Asesor $asesor): array
    {
        $data = $request->validate([
            'kode_form' => 'nullable|string|max:20',
            'judul_form' => 'nullable|string|max:255',
            'kategori_skema' => 'nullable|string|max:100',
            'skema_id' => 'required|exists:skemas,id',
            'tuk' => 'nullable|string|max:255',
            'asesi_nik' => 'required|exists:asesi,NIK',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'rekomendasi' => 'required|in:kompeten,belum_kompeten',
            'tindak_lanjut' => 'nullable|string',
            'komentar_observasi' => 'nullable|string',
            'ttd_asesor_nama' => 'nullable|string|max:255',
            'ttd_asesor_tanggal' => 'nullable|date',
            'ttd_asesor_file' => 'nullable|string',
            'simpan_tanda_tangan' => 'nullable|in:0,1',
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

        if (!$asesor->skemas->contains('id', (int) $data['skema_id'])) {
            throw ValidationException::withMessages([
                'skema_id' => 'Skema tidak termasuk penugasan asesor Anda.',
            ]);
        }

        $validAsesi = $this->isAsesiAssignedToSkema($asesor, (string) $data['asesi_nik'], (int) $data['skema_id']);

        if (!$validAsesi) {
            throw ValidationException::withMessages([
                'asesi_nik' => 'Asesi tidak termasuk penugasan Anda pada skema ini.',
            ]);
        }

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

        $data['asesor_id'] = $asesor->ID_asesor;
        $data['kode_form'] = $data['kode_form'] ?? 'FR.AK.02.';
        $data['judul_form'] = $data['judul_form'] ?? 'REKAMAN ASESMEN KOMPETENSI';
        $data['kategori_skema'] = $data['kategori_skema'] ?? Skema::find($data['skema_id'])?->jenis_skema;
        $data['tuk'] = $data['tuk'] ?? null;

        if (empty($data['ttd_asesor_nama'])) {
            $data['ttd_asesor_nama'] = $asesor->nama;
        }
        if (empty($data['ttd_asesor_tanggal'])) {
            $data['ttd_asesor_tanggal'] = now()->format('Y-m-d');
        }

        // Handle signature image file
        if (!empty($data['ttd_asesor_file'])) {
            if (strpos($data['ttd_asesor_file'], 'data:image') === 0) {
                try {
                    $signatureData = $data['ttd_asesor_file'];
                    list($type, $signatureData) = explode(';', $signatureData);
                    list(, $signatureData) = explode(',', $signatureData);
                    $signatureData = base64_decode($signatureData);

                    $filename = 'signature_asesor_' . uniqid() . '_' . time() . '.png';
                    $path = 'rekaman-asesmen/signatures';

                    \Illuminate\Support\Facades\Storage::disk('public')->put($path . '/' . $filename, $signatureData);
                    $data['ttd_asesor_file'] = $path . '/' . $filename;
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to save asesor signature image: ' . $e->getMessage());
                    unset($data['ttd_asesor_file']);
                }
            }
        }

        if ($request->input('simpan_tanda_tangan') === '1' && $asesor && !empty($request->ttd_asesor_file)) {
            $asesor->update(['saved_tanda_tangan' => $request->ttd_asesor_file]);
        }

        return [$data, $details];
    }
}
