<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tuk;
use Illuminate\Http\Request;

class TukController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status', 'all');

        $query = Tuk::withCount('jadwalUjikom');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_tuk', 'like', "%{$search}%")
                  ->orWhere('kode_tuk', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%");
            });
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $tuks = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total'    => Tuk::count(),
            'aktif'    => Tuk::where('status', 'aktif')->count(),
            'nonaktif' => Tuk::where('status', 'nonaktif')->count(),
        ];

        return view('admin.tuk.index', compact('tuks', 'stats', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.tuk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tuk'    => 'required|string|max:255',
            'kode_tuk'    => 'nullable|string|max:50|unique:tuk,kode_tuk',
            'tipe_tuk'    => 'required|in:sewaktu,tempat_kerja,mandiri',
            'alamat'      => 'nullable|string',
            'provinsi'    => 'nullable|string|max:100',
            'kota'        => 'nullable|string|max:100',
            'no_telepon'  => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:255',
            'kapasitas'   => 'required|integer|min:1',
            'status'      => 'required|in:aktif,nonaktif',
            'keterangan'  => 'nullable|string',
        ], [
            'nama_tuk.required'  => 'Nama TUK wajib diisi.',
            'tipe_tuk.required'  => 'Tipe TUK wajib dipilih.',
            'kapasitas.required' => 'Kapasitas wajib diisi.',
            'kapasitas.min'      => 'Kapasitas minimal 1.',
            'kode_tuk.unique'    => 'Kode TUK sudah digunakan.',
        ]);

        Tuk::create($validated);

        return redirect()->route('admin.tuk.index')->with('success', 'Data TUK berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $tuk = Tuk::findOrFail($id);
        return view('admin.tuk.edit', compact('tuk'));
    }

    public function update(Request $request, $id)
    {
        $tuk = Tuk::findOrFail($id);

        $validated = $request->validate([
            'nama_tuk'    => 'required|string|max:255',
            'kode_tuk'    => 'nullable|string|max:50|unique:tuk,kode_tuk,' . $id,
            'tipe_tuk'    => 'required|in:sewaktu,tempat_kerja,mandiri',
            'alamat'      => 'nullable|string',
            'provinsi'    => 'nullable|string|max:100',
            'kota'        => 'nullable|string|max:100',
            'no_telepon'  => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:255',
            'kapasitas'   => 'required|integer|min:1',
            'status'      => 'required|in:aktif,nonaktif',
            'keterangan'  => 'nullable|string',
        ], [
            'nama_tuk.required'  => 'Nama TUK wajib diisi.',
            'tipe_tuk.required'  => 'Tipe TUK wajib dipilih.',
            'kapasitas.required' => 'Kapasitas wajib diisi.',
            'kapasitas.min'      => 'Kapasitas minimal 1.',
            'kode_tuk.unique'    => 'Kode TUK sudah digunakan.',
        ]);

        $tuk->update($validated);

        return redirect()->route('admin.tuk.index')->with('success', 'Data TUK berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tuk = Tuk::withCount('jadwalUjikom')->findOrFail($id);

        if ($tuk->jadwal_ujikom_count > 0) {
            return redirect()->route('admin.tuk.index')
                ->with('error', 'Tidak dapat menghapus TUK yang masih memiliki jadwal ujikom!');
        }

        $tuk->delete();

        return redirect()->route('admin.tuk.index')->with('success', 'Data TUK berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $tuk = Tuk::findOrFail($id);
        $tuk->update(['status' => $tuk->status === 'aktif' ? 'nonaktif' : 'aktif']);

        return redirect()->back()->with('success', 'Status TUK berhasil diubah!');
    }
}
