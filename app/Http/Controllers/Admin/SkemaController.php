<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skema;
use App\Models\Unit;
use App\Models\Elemen;
use App\Models\Kriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        
        return view('admin.skema.index', compact('skemas', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.skema.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'nomor_skema' => 'required|string|max:255|unique:skemas,nomor_skema',
            'jenis_skema' => 'required|in:KKNI,Okupasi,Klaster',
            'units' => 'required|array|min:1',
            'units.*.kode_unit' => 'required|string|max:255',
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
            'units.*.judul_unit.required' => 'Judul unit wajib diisi.',
            'units.*.elemens.required' => 'Minimal satu elemen harus ditambahkan per unit.',
            'units.*.elemens.*.nama_elemen.required' => 'Nama elemen wajib diisi.',
            'units.*.elemens.*.kriteria.required' => 'Minimal satu kriteria unjuk kerja harus ditambahkan per elemen.',
            'units.*.elemens.*.kriteria.*.deskripsi_kriteria.required' => 'Deskripsi kriteria wajib diisi.',
        ]);

        DB::beginTransaction();
        try {
            $skema = Skema::create([
                'nama_skema' => $validated['nama_skema'],
                'nomor_skema' => $validated['nomor_skema'],
                'jenis_skema' => $validated['jenis_skema'],
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
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $skema = Skema::with('units.elemens.kriteria')->findOrFail($id);
        return view('admin.skema.edit', compact('skema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $skema = Skema::findOrFail($id);

        $validated = $request->validate([
            'nama_skema' => 'required|string|max:255',
            'nomor_skema' => 'required|string|max:255|unique:skemas,nomor_skema,' . $id,
            'jenis_skema' => 'required|in:KKNI,Okupasi,Klaster',
            'units' => 'required|array|min:1',
            'units.*.kode_unit' => 'required|string|max:255',
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
        ]);

        DB::beginTransaction();
        try {
            $skema->update([
                'nama_skema' => $validated['nama_skema'],
                'nomor_skema' => $validated['nomor_skema'],
                'jenis_skema' => $validated['jenis_skema'],
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
}
