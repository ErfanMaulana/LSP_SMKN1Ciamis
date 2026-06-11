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

            return view('asesor.ceklis-observasi.index', compact('account', 'asesor', 'items', 'search'));
        }

        $search = trim((string) $request->get('search'));

        $items = CeklisObservasiAktivitasPraktik::query()
            ->with([
                'skema:id,nama_skema,nomor_skema',
                'asesi:NIK,nama',
            ])
            ->where('asesor_id', $asesor->ID_asesor)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul_form', 'like', "%{$search}%")
                        ->orWhereHas('skema', function ($sq) use ($search) {
                            $sq->where('nama_skema', 'like', "%{$search}%")
                                ->orWhere('nomor_skema', 'like', "%{$search}%");
                        })
                        ->orWhereHas('asesi', function ($aq) use ($search) {
                            $aq->where('nama', 'like', "%{$search}%")
                                ->orWhere('NIK', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('asesor.ceklis-observasi.index', compact('account', 'asesor', 'items', 'search'));
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
                $existing = CeklisObservasiAktivitasPraktik::query()
                    ->where('asesor_id', $asesor->ID_asesor)
                    ->where('asesi_nik', $asesi->NIK)
                    ->where('skema_id', $activeSkema->id)
                    ->latest('id')
                    ->first(['id']);

                if ($existing) {
                    return redirect()->route('asesor.ceklis-observasi.show', $existing->id);
                }

                $defaults['asesi_nik'] = $asesi->NIK;
                $defaults['asesi_nama'] = $asesi->nama;
                $defaults['ttd_asesi_nama'] = $asesi->nama;
            }
        }

        return view('asesor.ceklis-observasi.create', compact('account', 'asesor', 'activeSkema', 'skemas', 'defaults'));
    }

    public function store(Request $request)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        [$data, $details] = $this->validatedData($request, $asesor, false);

        DB::transaction(function () use ($data, $details) {
            $item = CeklisObservasiAktivitasPraktik::create($data);
            $item->details()->createMany($details);
        });

        return redirect()->route('asesor.ceklis-observasi.index')
            ->with('success', 'Ceklis observasi berhasil disimpan.');
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

        $detailsByUnit = $item->details
            ->sortBy([
                ['unit_id', 'asc'],
                ['elemen_id', 'asc'],
                ['kriteria.urutan', 'asc'],
                ['kriteria_id', 'asc'],
            ])
            ->groupBy('unit_id');

        $logoPath = public_path('images/lsp.png');

        $html = view('asesor.ceklis-observasi.export-docx', [
            'item' => $item,
            'detailsByUnit' => $detailsByUnit,
            'logoPath' => $logoPath,
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

        return view('asesor.ceklis-observasi.edit', compact('account', 'asesor', 'item', 'activeSkema', 'skemas'));
    }

    public function update(Request $request, $id)
    {
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $item = CeklisObservasiAktivitasPraktik::query()
            ->where('asesor_id', $asesor->ID_asesor)
            ->findOrFail($id);

        [$data, $details] = $this->validatedData($request, $asesor, true);

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
        $tanggal = null;

        $jadwalPick = \App\Models\JadwalUjikom::query()
            ->with('tuk')
            ->where('skema_id', $skemaId)
            ->whereHas('peserta', function ($query) use ($validated) {
                $query->where('NIK', $validated['asesi_nik']);
            })
            ->orderBy('tanggal_mulai')
            ->first();

        if ($jadwalPick) {
            $tukName = $jadwalPick->tuk?->nama_tuk;
            $tanggal = $jadwalPick->tanggal_mulai
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
                'id' => (string) $asesi->NIK,
                'nama' => $asesi->nama,
            ],
            'tuk' => $tukName,
            'tanggal' => $tanggal,
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
            'ttd_asesor_nama' => 'nullable|string|max:255',
            'ttd_asesor_no_reg' => 'nullable|string|max:255',
            'ttd_asesor_tanggal' => 'nullable|date',
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

        return [$data, $details];
    }
}
