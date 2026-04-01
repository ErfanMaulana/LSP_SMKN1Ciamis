<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skema;
use App\Models\UmpanBalikKomponen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UmpanBalikKomponenController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $status = $request->get('status', 'all');
        $skemaId = $request->get('skema_id', 'all');

        $query = UmpanBalikKomponen::query()
            ->select([
                'skema_id',
                DB::raw('COUNT(*) as total_komponen'),
                DB::raw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as total_active'),
                DB::raw('SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as total_inactive'),
            ])
            ->with('skema')
            ->groupBy('skema_id');

        if ($search !== '') {
            $query->whereHas('skema', function ($q) use ($search) {
                $q->where('nama_skema', 'like', "%{$search}%")
                    ->orWhere('nomor_skema', 'like', "%{$search}%");
            });
        }

        if ($skemaId !== 'all') {
            $query->where('skema_id', $skemaId);
        }

        if ($status === 'active') {
            $query->havingRaw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) > 0');
        } elseif ($status === 'inactive') {
            $query->havingRaw('SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) > 0');
        }

        $komponen = $query->orderBy('skema_id')->paginate(10)->withQueryString();

        $skemaList = Skema::orderBy('nama_skema')->get(['id', 'nama_skema', 'nomor_skema']);

        $stats = [
            'total_skema' => UmpanBalikKomponen::distinct('skema_id')->count('skema_id'),
            'total_komponen' => UmpanBalikKomponen::count(),
            'active' => UmpanBalikKomponen::where('is_active', true)->count(),
            'inactive' => UmpanBalikKomponen::where('is_active', false)->count(),
        ];

        return view('admin.umpan-balik-komponen.index', compact('komponen', 'stats', 'search', 'status', 'skemaId', 'skemaList'));
    }

    public function create()
    {
        $skemaList = Skema::orderBy('nama_skema')->get(['id', 'nama_skema', 'nomor_skema']);

        return view('admin.umpan-balik-komponen.create', compact('skemaList'));
    }

    public function show($skemaId)
    {
        $skema = Skema::findOrFail($skemaId);

        $query = UmpanBalikKomponen::with('skema')
            ->where('skema_id', $skemaId);

        $komponen = $query->orderBy('urutan')->orderBy('id')->paginate(10)->withQueryString();

        $stats = [
            'total' => UmpanBalikKomponen::where('skema_id', $skemaId)->count(),
            'active' => UmpanBalikKomponen::where('skema_id', $skemaId)->where('is_active', true)->count(),
            'inactive' => UmpanBalikKomponen::where('skema_id', $skemaId)->where('is_active', false)->count(),
        ];

        return view('admin.umpan-balik-komponen.show', compact('skema', 'komponen', 'stats'));
    }

    public function editSkema($skemaId)
    {
        $skema = Skema::findOrFail($skemaId);

        $komponen = UmpanBalikKomponen::where('skema_id', $skemaId)
            ->orderBy('urutan')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.umpan-balik-komponen.edit-skema', compact('skema', 'komponen'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'skema_ids' => 'required|array|min:1',
            'skema_ids.*' => 'required|exists:skemas,id',
            'pernyataan' => 'required|array|min:1',
            'pernyataan.*' => 'required|string',
            'is_active' => 'nullable|boolean',
        ], [
            'skema_ids.required' => 'Minimal satu skema wajib dipilih.',
            'skema_ids.min' => 'Minimal satu skema wajib dipilih.',
            'skema_ids.*.exists' => 'Ada skema yang tidak valid.',
            'pernyataan.required' => 'Minimal 1 pernyataan komponen wajib diisi.',
            'pernyataan.*.required' => 'Pernyataan komponen tidak boleh kosong.',
        ]);

        $isActive = $request->has('is_active');
        $selectedSkemaIds = collect($validated['skema_ids'])
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        $statements = collect($validated['pernyataan'])
            ->map(fn($text) => trim((string) $text))
            ->filter(fn($text) => $text !== '')
            ->values();

        foreach ($selectedSkemaIds as $skemaId) {
            $baseUrutan = ((int) UmpanBalikKomponen::where('skema_id', $skemaId)->max('urutan')) + 1;

            foreach ($statements as $index => $text) {
                UmpanBalikKomponen::create([
                    'skema_id' => $skemaId,
                    'pernyataan' => $text,
                    'urutan' => $baseUrutan + $index,
                    'is_active' => $isActive,
                ]);
            }
        }

        return redirect()->route('admin.umpan-balik-komponen.index')->with('success', 'Komponen umpan balik berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $komponen = UmpanBalikKomponen::findOrFail($id);
        $skemaList = Skema::orderBy('nama_skema')->get(['id', 'nama_skema', 'nomor_skema']);

        return view('admin.umpan-balik-komponen.edit', compact('komponen', 'skemaList'));
    }

    public function update(Request $request, $id)
    {
        $komponen = UmpanBalikKomponen::findOrFail($id);

        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
            'pernyataan' => 'required|string',
            'urutan' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ], [
            'skema_id.required' => 'Skema wajib dipilih.',
            'skema_id.exists' => 'Skema tidak valid.',
            'pernyataan.required' => 'Pernyataan komponen wajib diisi.',
            'urutan.required' => 'Urutan wajib diisi.',
            'urutan.min' => 'Urutan minimal 1.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $komponen->update($validated);

        return redirect()->route('admin.umpan-balik-komponen.index')->with('success', 'Komponen umpan balik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $komponen = UmpanBalikKomponen::findOrFail($id);
        $komponen->delete();

        return redirect()->route('admin.umpan-balik-komponen.index')->with('success', 'Komponen umpan balik berhasil dihapus.');
    }

    public function destroyBySkema($skemaId)
    {
        $skema = Skema::findOrFail($skemaId);

        UmpanBalikKomponen::where('skema_id', $skemaId)->delete();

        return redirect()->route('admin.umpan-balik-komponen.index')->with('success', 'Semua komponen pada skema ' . $skema->nama_skema . ' berhasil dihapus.');
    }
}
