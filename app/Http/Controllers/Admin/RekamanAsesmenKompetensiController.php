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
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.rekaman-asesmen-kompetensi.index', compact('items', 'search'));
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
            'tuk' => 'Sewaktu/Tempat Kerja/Mandiri*',
            'catatan_footer' => '* Coret yang tidak perlu',
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
            'catatan_footer' => 'nullable|string|max:255',
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
}
