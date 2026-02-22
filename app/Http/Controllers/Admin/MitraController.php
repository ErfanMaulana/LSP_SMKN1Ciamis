<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mitras = Mitra::withCount('asesor')->latest()->paginate(10);
        return view('admin.mitra.index', compact('mitras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mitra.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_mou' => 'required|string|max:255|unique:mitra,no_mou',
            'nama_mitra' => 'required|string|max:255',
            'jenis_usaha' => 'nullable|string|max:255',
            'tanggal_mou' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mou',
        ], [
            'no_mou.required' => 'Nomor MOU wajib diisi.',
            'no_mou.unique' => 'Nomor MOU sudah terdaftar.',
            'nama_mitra.required' => 'Nama mitra wajib diisi.',
            'tanggal_berakhir.after_or_equal' => 'Tanggal berakhir harus sama atau setelah tanggal MOU.',
        ]);

        Mitra::create($validated);

        return redirect()->route('admin.mitra.index')->with('success', 'Data Mitra berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($no_mou)
    {
        $mitra = Mitra::findOrFail($no_mou);
        return view('admin.mitra.edit', compact('mitra'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $no_mou)
    {
        $mitra = Mitra::findOrFail($no_mou);

        $validated = $request->validate([
            'no_mou' => 'required|string|max:255|unique:mitra,no_mou,' . $no_mou . ',no_mou',
            'nama_mitra' => 'required|string|max:255',
            'jenis_usaha' => 'nullable|string|max:255',
            'tanggal_mou' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mou',
        ], [
            'no_mou.required' => 'Nomor MOU wajib diisi.',
            'no_mou.unique' => 'Nomor MOU sudah terdaftar.',
            'nama_mitra.required' => 'Nama mitra wajib diisi.',
            'tanggal_berakhir.after_or_equal' => 'Tanggal berakhir harus sama atau setelah tanggal MOU.',
        ]);

        $mitra->update($validated);

        return redirect()->route('admin.mitra.index')->with('success', 'Data Mitra berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($no_mou)
    {
        $mitra = Mitra::findOrFail($no_mou);
        
        // Check if mitra has related asesor
        if ($mitra->asesor()->count() > 0) {
            return redirect()->route('admin.mitra.index')
                ->with('error', 'Tidak dapat menghapus mitra yang masih memiliki asesor terkait!');
        }
        
        $mitra->delete();

        return redirect()->route('admin.mitra.index')->with('success', 'Data Mitra berhasil dihapus!');
    }
}
