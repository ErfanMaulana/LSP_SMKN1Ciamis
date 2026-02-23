<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Models\Mitra;
use App\Models\Skema;
use Illuminate\Http\Request;

class AsesorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asesor = Asesor::with('mitra')->paginate(10);
        
        // Dynamic statistics
        $stats = [
            'total' => Asesor::count(),
            'with_mitra' => Asesor::whereNotNull('no_mou')->count(),
            'with_skema' => Asesor::whereNotNull('ID_skema')->count(),
            'without_skema' => Asesor::whereNull('ID_skema')->count(),
        ];
        
        return view('admin.asesor.index', compact('asesor', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mitra = Mitra::all();
        $skema = Skema::all();
        return view('admin.asesor.create', compact('mitra', 'skema'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'ID_skema' => 'nullable|integer',
            'no_mou' => 'nullable|exists:mitra,no_mou',
        ]);

        Asesor::create($validated);

        return redirect()->route('admin.asesor.index')->with('success', 'Data Asesor berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $asesor = Asesor::findOrFail($id);
        $mitra = Mitra::all();
        $skema = Skema::all();
        return view('admin.asesor.edit', compact('asesor', 'mitra', 'skema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $asesor = Asesor::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'ID_skema' => 'nullable|integer',
            'no_mou' => 'nullable|exists:mitra,no_mou',
        ]);

        $asesor->update($validated);

        return redirect()->route('admin.asesor.index')->with('success', 'Data Asesor berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $asesor = Asesor::findOrFail($id);
        $asesor->delete();

        return redirect()->route('admin.asesor.index')->with('success', 'Data Asesor berhasil dihapus!');
    }
}
