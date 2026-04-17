<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BandingAsesmenKomponen;
use Illuminate\Http\Request;

class BandingAsesmenKomponenController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $query = BandingAsesmenKomponen::query();

        if ($search !== '') {
            $query->where('pernyataan', 'like', "%{$search}%");
        }

        $komponen = $query->orderBy('urutan')->orderBy('id')->paginate(10)->withQueryString();

        $stats = [
            'total' => BandingAsesmenKomponen::count(),
            'active' => BandingAsesmenKomponen::where('is_active', true)->count(),
            'inactive' => BandingAsesmenKomponen::where('is_active', false)->count(),
        ];

        return view('admin.banding-asesmen-komponen.index', compact('komponen', 'stats', 'search'));
    }

    public function create()
    {
        return view('admin.banding-asesmen-komponen.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pernyataan' => 'required|array|min:1',
            'pernyataan.*' => ['required', 'string', 'regex:/\\S/'],
            'is_active' => 'nullable|boolean',
        ], [
            'pernyataan.required' => 'Minimal 1 pernyataan ceklis wajib diisi.',
            'pernyataan.*.required' => 'Pernyataan ceklis tidak boleh kosong.',
            'pernyataan.*.regex' => 'Pernyataan ceklis tidak boleh hanya berisi spasi.',
        ]);

        $isActive = $request->has('is_active');

        $list = collect($validated['pernyataan'])
            ->map(fn ($item) => trim((string) $item))
            ->filter(fn ($item) => $item !== '')
            ->values();

        $baseUrutan = ((int) BandingAsesmenKomponen::max('urutan')) + 1;

        foreach ($list as $index => $item) {
            BandingAsesmenKomponen::create([
                'pernyataan' => $item,
                'urutan' => $baseUrutan + $index,
                'is_active' => $isActive,
            ]);
        }

        return redirect()->route('admin.banding-asesmen-komponen.index')
            ->with('success', 'Komponen ceklis banding berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $komponen = BandingAsesmenKomponen::findOrFail($id);

        return view('admin.banding-asesmen-komponen.edit', compact('komponen'));
    }

    public function update(Request $request, int $id)
    {
        $komponen = BandingAsesmenKomponen::findOrFail($id);

        $validated = $request->validate([
            'pernyataan' => ['required', 'string', 'regex:/\\S/'],
            'urutan' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ], [
            'pernyataan.required' => 'Pernyataan ceklis wajib diisi.',
            'pernyataan.regex' => 'Pernyataan ceklis tidak boleh hanya berisi spasi.',
            'urutan.required' => 'Urutan wajib diisi.',
        ]);

        $komponen->update([
            'pernyataan' => trim((string) $validated['pernyataan']),
            'urutan' => (int) $validated['urutan'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.banding-asesmen-komponen.index')
            ->with('success', 'Komponen ceklis banding berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $komponen = BandingAsesmenKomponen::findOrFail($id);
        $komponen->delete();

        return redirect()->route('admin.banding-asesmen-komponen.index')
            ->with('success', 'Komponen ceklis banding berhasil dihapus.');
    }
}
