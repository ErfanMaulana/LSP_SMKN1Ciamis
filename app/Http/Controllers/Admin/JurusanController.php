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
    public function index()
    {
        $jurusan = Jurusan::withCount('asesi')->paginate(10);
        return view('admin.jurusan.index', compact('jurusan'));
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
        ]);

        Jurusan::create($validated);

        return redirect()->route('admin.jurusan.index')->with('success', 'Data Jurusan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $validated = $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'kode_jurusan' => 'required|string|max:10|unique:jurusan,kode_jurusan,' . $id . ',ID_jurusan',
        ]);

        $jurusan->update($validated);

        return redirect()->route('admin.jurusan.index')->with('success', 'Data Jurusan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);
        
        // Check if jurusan has related asesi
        if ($jurusan->asesi()->count() > 0) {
            return redirect()->route('admin.jurusan.index')->with('error', 'Tidak dapat menghapus jurusan yang memiliki data asesi!');
        }

        $jurusan->delete();

        return redirect()->route('admin.jurusan.index')->with('success', 'Data Jurusan berhasil dihapus!');
    }
}
