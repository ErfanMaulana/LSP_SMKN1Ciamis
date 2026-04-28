<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skema;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SkemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Skema::with('jurusan')->withCount('units');
        
        // Filter by jenis_skema
        if ($request->filled('jenis_skema') && $request->jenis_skema !== 'all') {
            $query->where('jenis_skema', $request->jenis_skema);
        }
        
        // Search by nama or nomor
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_skema', 'like', "%{$search}%")
                  ->orWhere('nomor_skema', 'like', "%{$search}%");
            });
        }
        
        // Sort
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);
        
        $skemas = $query->paginate(10)->withQueryString();
        
        // Stats
        $stats = [
            'total' => Skema::count(),
            'kkni' => Skema::where('jenis_skema', 'KKNI')->count(),
            'okupasi' => Skema::where('jenis_skema', 'Okupasi')->count(),
            'klaster' => Skema::where('jenis_skema', 'Klaster')->count(),
        ];
        
        // If AJAX request, return rows + pagination payload
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'rows' => view('admin.skema.partials.table-rows', compact('skemas'))->render(),
                'pagination_info' => sprintf(
                    'Menampilkan %d sampai %d dari %d data',
                    $skemas->firstItem() ?? 0,
                    $skemas->lastItem() ?? 0,
                    $skemas->total()
                ),
                'pagination_links' => (string) $skemas->links(),
            ]);
        }
        
        return view('admin.skema.index', compact('skemas', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();
        return view('admin.skema.create', compact('jurusans'));
    }

    /**
     * Validate duplicate unit codes in create form (AJAX).
     */
    public function validateUnitCodes(Request $request)
    {
        $request->validate([
            'codes' => 'required|array|min:1',
            'codes.*' => 'nullable|string|max:255',
        ]);

        $normalized = [];
        $duplicateIndices = [];

        foreach ($request->input('codes', []) as $index => $code) {
            $key = strtoupper(trim((string) $code));

            if ($key === '') {
                continue;
            }

            if (isset($normalized[$key])) {
                $duplicateIndices[] = $normalized[$key];
                $duplicateIndices[] = $index;
                continue;
            }

            $normalized[$key] = $index;
        }

        $duplicateIndices = array_values(array_unique($duplicateIndices));

        return response()->json([
            'is_valid' => empty($duplicateIndices),
            'duplicate_indices' => $duplicateIndices,
            'message' => empty($duplicateIndices)
                ? null
                : 'Kode unit tidak boleh sama antar unit kompetensi.',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_skema' => 'required|string|max:255',
            'nomor_skema' => 'required|string|max:255|unique:skemas,nomor_skema',
            'jenis_skema' => 'required|in:KKNI,Okupasi,Klaster',
            'jurusan_id' => 'nullable|exists:jurusan,ID_jurusan',
            'units' => 'required|array|min:1',
            'units.*.kode_unit' => 'required|string|max:255|distinct',
            'units.*.judul_unit' => 'required|string|max:255',
            'units.*.pertanyaan_unit' => 'nullable|string',
            'units.*.elemens' => 'required|array|min:1',
            'units.*.elemens.*.nama_elemen' => 'required|string',
            'units.*.elemens.*.kriteria' => 'required|array|min:1',
            'units.*.elemens.*.kriteria.*.deskripsi_kriteria' => 'required|string',
        ], [
            'nama_skema.required' => 'Nama skema wajib diisi.',
            'nomor_skema.required' => 'Nomor skema wajib diisi.',
            'nomor_skema.unique' => 'Nomor skema sudah terdaftar.',
            'jenis_skema.required' => 'Jenis skema wajib dipilih.',
            'jenis_skema.in' => 'Jenis skema tidak valid.',
            'units.required' => 'Minimal satu unit kompetensi harus ditambahkan.',
            'units.*.kode_unit.required' => 'Kode unit wajib diisi.',
            'units.*.kode_unit.distinct' => 'Kode unit tidak boleh duplikat antar unit.',
            'units.*.judul_unit.required' => 'Judul unit wajib diisi.',
            'units.*.elemens.required' => 'Minimal satu elemen harus ditambahkan per unit.',
            'units.*.elemens.*.nama_elemen.required' => 'Nama elemen wajib diisi.',
            'units.*.elemens.*.kriteria.required' => 'Minimal satu kriteria unjuk kerja harus ditambahkan per elemen.',
            'units.*.elemens.*.kriteria.*.deskripsi_kriteria.required' => 'Deskripsi kriteria wajib diisi.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $this->addDuplicateUnitCodeErrors($validator, $request->input('units', []));
        });

        $validated = $validator->validate();

        DB::beginTransaction();
        try {
            $skema = Skema::create([
                'nama_skema' => $validated['nama_skema'],
                'nomor_skema' => $validated['nomor_skema'],
                'jenis_skema' => $validated['jenis_skema'],
                'jurusan_id' => $validated['jurusan_id'] ?? null,
            ]);

            foreach ($validated['units'] as $unitData) {
                $unit = $skema->units()->create([
                    'kode_unit' => $unitData['kode_unit'],
                    'judul_unit' => $unitData['judul_unit'],
                    'pertanyaan_unit' => $unitData['pertanyaan_unit'] ?? null,
                ]);

                foreach ($unitData['elemens'] as $elemenData) {
                    $elemen = $unit->elemens()->create([
                        'nama_elemen' => $elemenData['nama_elemen'],
                    ]);

                    foreach ($elemenData['kriteria'] as $index => $kriteriaData) {
                        $elemen->kriteria()->create([
                            'deskripsi_kriteria' => $kriteriaData['deskripsi_kriteria'],
                            'urutan' => $index + 1,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.skema.index')->with('success', 'Skema sertifikasi berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan skema: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $skema = Skema::with(['jurusan', 'units.elemens.kriteria'])
            ->withCount('units')
            ->findOrFail($id);
        
        // Count total elemens and kriteria
        $elemensCount = 0;
        $kriteriaCount = 0;
        
        foreach ($skema->units as $unit) {
            $elemensCount += $unit->elemens->count();
            foreach ($unit->elemens as $elemen) {
                $kriteriaCount += $elemen->kriteria->count();
            }
        }
        
        return view('admin.skema.show', compact('skema', 'elemensCount', 'kriteriaCount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $skema = Skema::with('units.elemens.kriteria')->findOrFail($id);
        $jurusans = Jurusan::orderBy('nama_jurusan')->get();
        return view('admin.skema.edit', compact('skema', 'jurusans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $skema = Skema::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_skema' => 'required|string|max:255',
            'nomor_skema' => 'required|string|max:255|unique:skemas,nomor_skema,' . $id,
            'jenis_skema' => 'required|in:KKNI,Okupasi,Klaster',
            'jurusan_id' => 'nullable|exists:jurusan,ID_jurusan',
            'units' => 'required|array|min:1',
            'units.*.kode_unit' => 'required|string|max:255|distinct',
            'units.*.judul_unit' => 'required|string|max:255',
            'units.*.pertanyaan_unit' => 'nullable|string',
            'units.*.elemens' => 'required|array|min:1',
            'units.*.elemens.*.nama_elemen' => 'required|string',
            'units.*.elemens.*.kriteria' => 'required|array|min:1',
            'units.*.elemens.*.kriteria.*.deskripsi_kriteria' => 'required|string',
        ], [
            'nama_skema.required' => 'Nama skema wajib diisi.',
            'nomor_skema.required' => 'Nomor skema wajib diisi.',
            'nomor_skema.unique' => 'Nomor skema sudah terdaftar.',
            'jenis_skema.required' => 'Jenis skema wajib dipilih.',
            'jenis_skema.in' => 'Jenis skema tidak valid.',
            'units.required' => 'Minimal satu unit kompetensi harus ditambahkan.',
            'units.*.kode_unit.distinct' => 'Kode unit tidak boleh duplikat antar unit.',
        ]);

        $validator->after(function ($validator) use ($request) {
            $this->addDuplicateUnitCodeErrors($validator, $request->input('units', []));
        });

        $validated = $validator->validate();

        DB::beginTransaction();
        try {
            $skema->update([
                'nama_skema' => $validated['nama_skema'],
                'nomor_skema' => $validated['nomor_skema'],
                'jenis_skema' => $validated['jenis_skema'],
                'jurusan_id' => $validated['jurusan_id'] ?? null,
            ]);

            // Delete old units (use each() for proper cascade via DB foreign keys)
            foreach ($skema->units as $unit) {
                $unit->delete();
            }

            foreach ($validated['units'] as $unitData) {
                $unit = $skema->units()->create([
                    'kode_unit' => $unitData['kode_unit'],
                    'judul_unit' => $unitData['judul_unit'],
                    'pertanyaan_unit' => $unitData['pertanyaan_unit'] ?? null,
                ]);

                foreach ($unitData['elemens'] as $elemenData) {
                    $elemen = $unit->elemens()->create([
                        'nama_elemen' => $elemenData['nama_elemen'],
                    ]);

                    foreach ($elemenData['kriteria'] as $index => $kriteriaData) {
                        $elemen->kriteria()->create([
                            'deskripsi_kriteria' => $kriteriaData['deskripsi_kriteria'],
                            'urutan' => $index + 1,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.skema.index')->with('success', 'Skema sertifikasi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui skema: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $skema = Skema::findOrFail($id);
        
        $skema->delete();

        return redirect()->route('admin.skema.index')->with('success', 'Skema sertifikasi berhasil dihapus!');
    }

    private function addDuplicateUnitCodeErrors($validator, array $units): void
    {
        $seen = [];

        foreach ($units as $index => $unit) {
            $rawCode = $unit['kode_unit'] ?? '';
            $normalizedCode = strtoupper(trim((string) $rawCode));

            if ($normalizedCode === '') {
                continue;
            }

            if (isset($seen[$normalizedCode])) {
                $validator->errors()->add(
                    "units.{$index}.kode_unit",
                    'Kode unit tidak boleh sama dengan unit lain.'
                );
                continue;
            }

            $seen[$normalizedCode] = $index;
        }
    }
}
