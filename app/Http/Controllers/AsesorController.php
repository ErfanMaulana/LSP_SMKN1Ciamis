<?php

namespace App\Http\Controllers;

use App\Models\Asesor;
use App\Models\Account;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AsesorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asesor = Asesor::with('skemas')->paginate(10);

        $stats = [
            'total'           => Asesor::count(),
            'with_skema'      => Asesor::whereHas('skemas')->count(),
            'without_skema'   => Asesor::whereDoesntHave('skemas')->count(),
            'with_account'    => Asesor::whereNotNull('no_met')->count(),
        ];

        return view('admin.asesor.index', compact('asesor', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skema = Skema::orderBy('nama_skema')->get();
        return view('admin.asesor.create', compact('skema'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'skema_ids' => 'nullable|array',
            'skema_ids.*' => 'exists:skemas,id',
            'no_met'    => 'nullable|string|max:50|unique:asesor,no_met|unique:accounts,id',
        ]);

        $asesor = Asesor::create([
            'nama'   => $validated['nama'],
            'no_met' => $validated['no_met'] ?? null,
        ]);

        // Sync skemas
        if (!empty($validated['skema_ids'])) {
            $asesor->skemas()->sync($validated['skema_ids']);
        }

        // Auto-create account if no_met provided
        if (!empty($validated['no_met'])) {
            Account::create([
                'id'       => $validated['no_met'],
                'password' => Hash::make($validated['no_met']),
                'role'     => 'asesor',
            ]);
        }

        return redirect()->route('admin.asesor.index')->with('success', 'Data Asesor berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ID_asesor)
    {
        $asesor = Asesor::with('skemas')->findOrFail($ID_asesor);
        $skema  = Skema::orderBy('nama_skema')->get();
        $selectedSkemaIds = $asesor->skemas->pluck('id')->toArray();
        return view('admin.asesor.edit', compact('asesor', 'skema', 'selectedSkemaIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $ID_asesor)
    {
        $asesor = Asesor::findOrFail($ID_asesor);

        $validated = $request->validate([
            'nama'        => 'required|string|max:255',
            'skema_ids'   => 'nullable|array',
            'skema_ids.*' => 'exists:skemas,id',
            'no_met'      => 'nullable|string|max:50|unique:asesor,no_met,' . $asesor->ID_asesor . ',ID_asesor|unique:accounts,id,' . ($asesor->no_met ? $asesor->no_met : 'NULL'),
        ]);

        $oldNoMet = $asesor->no_met;
        $newNoMet = $validated['no_met'] ?? null;

        $asesor->update([
            'nama'   => $validated['nama'],
            'no_met' => $newNoMet,
        ]);

        // Sync skemas
        $asesor->skemas()->sync($validated['skema_ids'] ?? []);

        // Sync account
        if ($newNoMet && $newNoMet !== $oldNoMet) {
            if ($oldNoMet) {
                Account::where('id', $oldNoMet)->where('role', 'asesor')->delete();
            }
            Account::create([
                'id'       => $newNoMet,
                'password' => Hash::make($newNoMet),
                'role'     => 'asesor',
            ]);
        } elseif (!$newNoMet && $oldNoMet) {
            Account::where('id', $oldNoMet)->where('role', 'asesor')->delete();
        }

        return redirect()->route('admin.asesor.index')->with('success', 'Data Asesor berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ID_asesor)
    {
        $asesor = Asesor::findOrFail($ID_asesor);
        $asesor->delete();

        return redirect()->route('admin.asesor.index')->with('success', 'Data Asesor berhasil dihapus!');
    }
}
