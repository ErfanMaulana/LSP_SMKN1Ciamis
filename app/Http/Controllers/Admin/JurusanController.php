<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sort   = $request->get('sort', 'nama_jurusan');
        $order  = $request->get('order', 'asc');

        $allowedSorts = ['nama_jurusan', 'kode_jurusan', 'asesi_count', 'created_at'];
        if (!in_array($sort, $allowedSorts)) $sort = 'nama_jurusan';
        if (!in_array($order, ['asc', 'desc'])) $order = 'asc';

        $query = Jurusan::withCount('asesi');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_jurusan', 'like', "%{$search}%")
                  ->orWhere('Nama_Jurusan', 'like', "%{$search}%")
                  ->orWhere('kode_jurusan', 'like', "%{$search}%");
            });
        }

        $query->orderBy($sort, $order);

        $jurusan = $query->paginate(10)->withQueryString();

        $stats = [
            'total'           => Jurusan::count(),
            'total_asesi'     => \App\Models\Asesi::count(),
            'avg_asesi'       => Jurusan::count() > 0 ? round(\App\Models\Asesi::count() / Jurusan::count()) : 0,
            'with_asesi'      => Jurusan::has('asesi')->count(),
        ];

        // If AJAX request, return only table rows
        if ($request->ajax()) {
            return view('admin.jurusan.partials.table-rows', compact('jurusan'))->render();
        }

        return view('admin.jurusan.index', compact('jurusan', 'stats', 'search', 'sort', 'order'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.jurusan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'kode_jurusan' => 'required|string|max:10|unique:jurusan,kode_jurusan',
            'visi'         => 'nullable|string',
            'misi'         => 'nullable|string',
            'kelas'        => 'nullable|array',
            'kelas.*'      => 'nullable|string|max:100',
        ]);

        $jurusan = Jurusan::create([
            'nama_jurusan' => $validated['nama_jurusan'],
            'kode_jurusan' => $validated['kode_jurusan'],
            'visi' => $validated['visi'] ?? null,
            'misi' => $validated['misi'] ?? null,
        ]);

        $kelasRows = collect($validated['kelas'] ?? [])
            ->map(fn($item) => trim((string) $item))
            ->filter()
            ->unique()
            ->values();

        if ($kelasRows->isNotEmpty()) {
            $jurusan->kelasItems()->createMany(
                $kelasRows->map(fn($nama) => ['nama_kelas' => $nama])->all()
            );
        }

        return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($ID_jurusan)
    {
        $jurusan = Jurusan::withCount(['asesi', 'skemas'])
            ->with(['skemas', 'kelasItems'])
            ->findOrFail($ID_jurusan);
        
        return view('admin.jurusan.show', compact('jurusan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ID_jurusan)
    {
        $jurusan = Jurusan::with('kelasItems')->findOrFail($ID_jurusan);
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $ID_jurusan)
    {
        $jurusan = Jurusan::findOrFail($ID_jurusan);

        $validated = $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'kode_jurusan' => 'required|string|max:10|unique:jurusan,kode_jurusan,' . $ID_jurusan . ',ID_jurusan',
            'visi'         => 'nullable|string',
            'misi'         => 'nullable|string',
            'kelas'        => 'nullable|array',
            'kelas.*'      => 'nullable|string|max:100',
        ]);

        $jurusan->update([
            'nama_jurusan' => $validated['nama_jurusan'],
            'kode_jurusan' => $validated['kode_jurusan'],
            'visi' => $validated['visi'] ?? null,
            'misi' => $validated['misi'] ?? null,
        ]);

        $kelasRows = collect($validated['kelas'] ?? [])
            ->map(fn($item) => trim((string) $item))
            ->filter()
            ->unique()
            ->values();

        $jurusan->kelasItems()->delete();
        if ($kelasRows->isNotEmpty()) {
            $jurusan->kelasItems()->createMany(
                $kelasRows->map(fn($nama) => ['nama_kelas' => $nama])->all()
            );
        }

        return redirect()->route('admin.jurusan.index')->with('success', 'Jurusan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ID_jurusan)
    {
        $jurusan = Jurusan::findOrFail($ID_jurusan);
        
        // Check if jurusan has related asesi
        if ($jurusan->asesi()->count() > 0) {
            return redirect()->route('admin.jurusan.index')->with('error', 'Tidak dapat menghapus jurusan yang memiliki data asesi!');
        }

        $jurusan->delete();

        return redirect()->route('admin.jurusan.index')->with('success', 'Data Jurusan berhasil dihapus!');
    }
}
