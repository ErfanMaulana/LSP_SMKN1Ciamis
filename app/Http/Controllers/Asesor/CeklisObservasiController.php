<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\CeklisObservasiAktivitasPraktik;
use App\Models\Kriteria;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    public function create()
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();

        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $activeSkema = $asesor->skemas()->orderBy('nama_skema')->first(['skemas.id', 'nama_skema', 'nomor_skema']);
        abort_unless((bool) $activeSkema, 403, 'Skema asesor belum ditetapkan. Hubungi admin.');

        $defaults = [
            'kode_form' => 'FR.IA.01.',
            'judul_form' => 'CEKLIS OBSERVASI AKTIVITAS PRAKTIK',
            'skema_id' => $activeSkema->id,
            'catatan_footer' => '* Coret yang tidak perlu',
            'ttd_asesor_nama' => $asesor->nama,
            'ttd_asesor_no_reg' => $asesor->no_met,
        ];

        return view('asesor.ceklis-observasi.create', compact('account', 'asesor', 'activeSkema', 'defaults'));
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

    public function edit($id)
    {
        $account = Auth::guard('account')->user();
        $asesor = $this->getAsesor();
        abort_unless((bool) $asesor, 403, 'Profil asesor tidak ditemukan.');

        $item = CeklisObservasiAktivitasPraktik::query()
            ->with(['details', 'skema:id,nama_skema,nomor_skema'])
            ->where('asesor_id', $asesor->ID_asesor)
            ->findOrFail($id);

        $activeSkema = $item->skema;
        abort_unless((bool) $activeSkema, 403, 'Skema pada data ceklis tidak ditemukan.');

        return view('asesor.ceklis-observasi.edit', compact('account', 'asesor', 'item', 'activeSkema'));
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
            ->where('ID_asesor', $asesor->ID_asesor)
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
            ->where('ID_asesor', $asesor->ID_asesor)
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
