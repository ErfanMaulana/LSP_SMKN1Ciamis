<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\CeklisObservasiAktivitasPraktik;
use App\Models\Kriteria;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class CeklisObservasiAktivitasPraktikController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));

        $items = CeklisObservasiAktivitasPraktik::query()
            ->with([
                'skema:id,nama_skema,nomor_skema',
                'asesi:NIK,nama',
                'asesor:ID_asesor,nama,no_met',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('kode_form', 'like', "%{$search}%")
                        ->orWhere('judul_form', 'like', "%{$search}%")
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
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.ceklis-observasi-aktivitas-praktik.index', compact('items', 'search'));
    }

    public function create()
    {
        $skemaList = Skema::query()
            ->orderBy('nama_skema')
            ->get(['id', 'nama_skema', 'nomor_skema']);

        $defaults = [
            'kode_form' => 'FR.IA.01.',
            'judul_form' => 'CEKLIS OBSERVASI AKTIVITAS PRAKTIK',
            'catatan_footer' => '* Coret yang tidak perlu',
        ];

        return view('admin.ceklis-observasi-aktivitas-praktik.create', compact('skemaList', 'defaults'));
    }

    public function store(Request $request)
    {
        [$data, $details] = $this->validatedData($request);

        DB::transaction(function () use ($data, $details) {
            $item = CeklisObservasiAktivitasPraktik::create($data);
            $item->details()->createMany($details);
        });

        return redirect()->route('admin.ceklis-observasi-aktivitas-praktik.index')
            ->with('success', 'Data ceklis observasi aktivitas praktik berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = CeklisObservasiAktivitasPraktik::query()
            ->with([
                'skema:id,nama_skema,nomor_skema',
                'asesi:NIK,nama',
                'asesor:ID_asesor,nama,no_met',
                'details.unit:id,kode_unit,judul_unit',
                'details.elemen:id,unit_id,nama_elemen',
                'details.kriteria:id,elemen_id,deskripsi_kriteria,urutan',
            ])
            ->findOrFail($id);

        $detailsByUnit = $item->details
            ->sortBy([
                ['unit_id', 'asc'],
                ['elemen_id', 'asc'],
                ['kriteria.urutan', 'asc'],
                ['kriteria_id', 'asc'],
            ])
            ->groupBy('unit_id');

        return view('admin.ceklis-observasi-aktivitas-praktik.show', compact('item', 'detailsByUnit'));
    }

    public function edit($id)
    {
        $item = CeklisObservasiAktivitasPraktik::query()
            ->with('details')
            ->findOrFail($id);

        $skemaList = Skema::query()
            ->orderBy('nama_skema')
            ->get(['id', 'nama_skema', 'nomor_skema']);

        return view('admin.ceklis-observasi-aktivitas-praktik.edit', compact('item', 'skemaList'));
    }

    public function update(Request $request, $id)
    {
        $item = CeklisObservasiAktivitasPraktik::findOrFail($id);
        [$data, $details] = $this->validatedData($request);

        DB::transaction(function () use ($item, $data, $details) {
            $item->update($data);
            $item->details()->delete();
            $item->details()->createMany($details);
        });

        return redirect()->route('admin.ceklis-observasi-aktivitas-praktik.index')
            ->with('success', 'Data ceklis observasi aktivitas praktik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = CeklisObservasiAktivitasPraktik::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.ceklis-observasi-aktivitas-praktik.index')
            ->with('success', 'Data ceklis observasi aktivitas praktik berhasil dihapus.');
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

    public function skemaStructure(Request $request)
    {
        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
        ]);

        $skema = Skema::query()
            ->with([
                'units' => fn ($query) => $query->orderBy('id'),
                'units.elemens' => fn ($query) => $query->orderBy('id'),
                'units.elemens.kriteria' => fn ($query) => $query->orderBy('urutan')->orderBy('id'),
            ])
            ->findOrFail($validated['skema_id']);

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

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'kode_form' => 'required|string|max:20',
            'judul_form' => 'required|string|max:255',
            'skema_id' => 'required|exists:skemas,id',
            'asesi_nik' => 'required|exists:asesi,NIK',
            'asesor_id' => 'nullable|exists:asesor,ID_asesor',
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

        $rawDetails = array_values($data['detail']);
        unset($data['detail']);

        $kriteriaIds = collect($rawDetails)
            ->pluck('kriteria_id')
            ->unique()
            ->values();

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

        return [$data, $details];
    }
}
