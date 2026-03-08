<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use App\Models\Asesor;
use App\Models\Asesi;
use App\Models\Skema;
use Illuminate\Http\Request;

class KelompokController extends Controller
{
    /**
     * Daftar semua kelompok.
     */
    public function index(Request $request)
    {
        $query = Kelompok::with(['skema', 'asesors', 'asesis.jurusan']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_kelompok', 'like', "%{$search}%")
                  ->orWhereHas('skema', fn($sq) => $sq->where('nama_skema', 'like', "%{$search}%"))
                  ->orWhereHas('asesors', fn($sq) => $sq->where('nama', 'like', "%{$search}%"));
            });
        }

        // Skema filter
        if ($request->has('skema') && $request->skema != '') {
            $query->where('skema_id', $request->skema);
        }

        $kelompoks = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $stats = [
            'total_kelompok'    => Kelompok::count(),
            'kelompok_aktif'    => Kelompok::has('asesis')->count(),
            'total_asesi'       => Asesi::whereNotNull('kelompok_id')->count(),
            'belum_ditugaskan'  => Asesi::where('status', 'approved')->whereNull('kelompok_id')->count(),
        ];

        // Get all skema for filter dropdown
        $skemaList = Skema::orderBy('nama_skema')->get();

        // If AJAX request, return only table rows
        if ($request->ajax()) {
            return view('admin.kelompok.partials.table-rows', compact('kelompoks'))->render();
        }

        return view('admin.kelompok.index', compact('kelompoks', 'stats', 'skemaList'));
    }

    /**
     * Form buat kelompok baru.
     */
    public function create()
    {
        $skemas  = Skema::orderBy('nama_skema')->get();
        $asesors = Asesor::with('skemas')->orderBy('nama')->get();
        $asesis  = Asesi::where('status', 'approved')
                        ->whereNull('kelompok_id')
                        ->with(['skemas', 'jurusan'])
                        ->get();

        // Build JSON maps for JS dynamic filtering
        $asesorsJson = $asesors->map(fn($a) => [
            'id'       => $a->ID_asesor,
            'nama'     => $a->nama,
            'no_met'   => $a->no_met ?? '-',
            'skema_ids'=> $a->skemas->pluck('id')->toArray(),
        ]);

        $asesisJson = $asesis->map(fn($a) => [
            'nik'      => $a->NIK,
            'nama'     => $a->nama,
            'kelas'    => $a->kelas,
            'jurusan'  => $a->jurusan->nama_jurusan ?? ($a->jurusan->nama ?? '-'),
            'skema_ids'=> $a->skemas->pluck('id')->toArray(),
        ]);

        return view('admin.kelompok.create', compact('skemas', 'asesors', 'asesis', 'asesorsJson', 'asesisJson'));
    }

    /**
     * Simpan kelompok baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'skema_id'      => 'required|exists:skemas,id',
            'asesor_id'     => 'nullable|exists:asesor,ID_asesor',
            'asesi_niks'    => 'nullable|array',
            'asesi_niks.*'  => 'exists:asesi,NIK',
        ]);

        $kelompok = Kelompok::create([
            'nama_kelompok' => $validated['nama_kelompok'],
            'skema_id'      => $validated['skema_id'],
        ]);

        // Sync single asesor (max 1)
        if (!empty($validated['asesor_id'])) {
            $kelompok->asesors()->sync([$validated['asesor_id']]);
        }

        // Assign selected asesis
        if (!empty($validated['asesi_niks'])) {
            Asesi::whereIn('NIK', $validated['asesi_niks'])
                 ->update(['kelompok_id' => $kelompok->id]);
        }

        return redirect()->route('admin.kelompok.index')
            ->with('success', "Kelompok <strong>{$kelompok->nama_kelompok}</strong> berhasil dibuat.");
    }

    /**
     * Detail kelompok + kelola asesi.
     */
    public function show(Request $request, $id)
    {
        $kelompok = Kelompok::with(['skema', 'asesors', 'asesis.jurusan'])->findOrFail($id);

        $asesiDitugaskan = $kelompok->asesis()->with('jurusan')->get();

        $asesiTersedia = Asesi::where('status', 'approved')
            ->whereNull('kelompok_id')
            ->with('jurusan')
            ->get();

        return view('admin.kelompok.show', compact('kelompok', 'asesiDitugaskan', 'asesiTersedia'));
    }

    /**
     * Form edit kelompok.
     */
    public function edit($id)
    {
        $kelompok = Kelompok::with(['asesors', 'asesis'])->findOrFail($id);
        $skemas   = Skema::orderBy('nama_skema')->get();
        $asesors  = Asesor::with('skemas')->orderBy('nama')->get();

        // Asesis available = not assigned to any kelompok OR currently in this kelompok
        $asesis = Asesi::where('status', 'approved')
                       ->where(function ($q) use ($id) {
                           $q->whereNull('kelompok_id')
                             ->orWhere('kelompok_id', $id);
                       })
                       ->with(['skemas', 'jurusan'])
                       ->get();

        $selectedAsesorId  = $kelompok->asesors->first()?->ID_asesor;
        $selectedAsesiNiks = $kelompok->asesis->pluck('NIK')->toArray();

        $asesorsJson = $asesors->map(fn($a) => [
            'id'       => $a->ID_asesor,
            'nama'     => $a->nama,
            'no_met'   => $a->no_met ?? '-',
            'skema_ids'=> $a->skemas->pluck('id')->toArray(),
        ]);

        $asesisJson = $asesis->map(fn($a) => [
            'nik'      => $a->NIK,
            'nama'     => $a->nama,
            'kelas'    => $a->kelas,
            'jurusan'  => $a->jurusan->nama_jurusan ?? ($a->jurusan->nama ?? '-'),
            'skema_ids'=> $a->skemas->pluck('id')->toArray(),
        ]);

        return view('admin.kelompok.edit', compact(
            'kelompok', 'skemas', 'asesors', 'asesis',
            'selectedAsesorId', 'selectedAsesiNiks',
            'asesorsJson', 'asesisJson'
        ));
    }

    /**
     * Update kelompok.
     */
    public function update(Request $request, $id)
    {
        $kelompok = Kelompok::findOrFail($id);

        $validated = $request->validate([
            'nama_kelompok' => 'required|string|max:255',
            'skema_id'      => 'required|exists:skemas,id',
            'asesor_id'     => 'nullable|exists:asesor,ID_asesor',
            'asesi_niks'    => 'nullable|array',
            'asesi_niks.*'  => 'exists:asesi,NIK',
        ]);

        $kelompok->update([
            'nama_kelompok' => $validated['nama_kelompok'],
            'skema_id'      => $validated['skema_id'],
        ]);

        // Sync single asesor
        $kelompok->asesors()->sync(
            !empty($validated['asesor_id']) ? [$validated['asesor_id']] : []
        );

        // Release all previously assigned asesis, then reassign selected
        Asesi::where('kelompok_id', $id)->update(['kelompok_id' => null]);
        if (!empty($validated['asesi_niks'])) {
            Asesi::whereIn('NIK', $validated['asesi_niks'])
                 ->update(['kelompok_id' => $kelompok->id]);
        }

        return redirect()->route('admin.kelompok.index')
            ->with('success', "Kelompok <strong>{$kelompok->nama_kelompok}</strong> berhasil diperbarui.");
    }

    /**
     * Hapus kelompok.
     */
    public function destroy($id)
    {
        $kelompok = Kelompok::findOrFail($id);

        // Release asesis first
        Asesi::where('kelompok_id', $id)->update(['kelompok_id' => null]);

        $kelompok->delete();

        return redirect()->route('admin.kelompok.index')
            ->with('success', "Kelompok <strong>{$kelompok->nama_kelompok}</strong> berhasil dihapus.");
    }

    /**
     * Tugaskan satu asesi ke kelompok.
     */
    public function assignAsesi(Request $request, $id)
    {
        $request->validate([
            'NIK' => 'required|exists:asesi,NIK',
        ]);

        $kelompok = Kelompok::findOrFail($id);
        $asesi    = Asesi::findOrFail($request->NIK);

        $asesi->update(['kelompok_id' => $kelompok->id]);

        return redirect()->route('admin.kelompok.show', $id)
            ->with('success', "Asesi <strong>{$asesi->nama}</strong> berhasil ditugaskan ke kelompok <strong>{$kelompok->nama_kelompok}</strong>.");
    }

    /**
     * Tugaskan banyak asesi sekaligus ke kelompok.
     */
    public function assignBulk(Request $request, $id)
    {
        $request->validate([
            'niks'   => 'required|array|min:1',
            'niks.*' => 'exists:asesi,NIK',
        ]);

        $kelompok = Kelompok::findOrFail($id);

        Asesi::whereIn('NIK', $request->niks)
             ->update(['kelompok_id' => $kelompok->id]);

        $count = count($request->niks);

        return redirect()->route('admin.kelompok.show', $id)
            ->with('success', "{$count} asesi berhasil ditugaskan ke kelompok <strong>{$kelompok->nama_kelompok}</strong>.");
    }

    /**
     * Lepas asesi dari kelompok.
     */
    public function unassignAsesi(Request $request, $id, $NIK)
    {
        $kelompok = Kelompok::findOrFail($id);
        $asesi    = Asesi::where('NIK', $NIK)
                         ->where('kelompok_id', $id)
                         ->firstOrFail();

        $asesi->update(['kelompok_id' => null]);

        return redirect()->route('admin.kelompok.show', $id)
            ->with('success', "Asesi <strong>{$asesi->nama}</strong> berhasil dilepas dari kelompok <strong>{$kelompok->nama_kelompok}</strong>.");
    }
}
