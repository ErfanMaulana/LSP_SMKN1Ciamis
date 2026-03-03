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
    public function index(Request $request)
    {
        $query = Asesor::with('skema');
        
        // Search filter
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('ID_asesor', 'LIKE', "%{$search}%")
                  ->orWhere('no_reg', 'LIKE', "%{$search}%");
            });
        }
        
        // Keahlian filter
        if ($request->has('keahlian') && $request->keahlian != '') {
            $query->where('ID_skema', $request->keahlian);
        }
        
        // Status filter  
        if ($request->has('status') && $request->status != '') {
            if ($request->status === 'aktif') {
                $query->whereNotNull('ID_skema');
            } elseif ($request->status === 'tidak_aktif') {
                $query->whereNull('ID_skema');
            }
        }
        
        $asesor = $query->paginate(10);
        
        // Dynamic statistics
        $stats = [
            'total' => Asesor::count(),
            'with_skema' => Asesor::whereNotNull('ID_skema')->count(),
            'without_skema' => Asesor::whereNull('ID_skema')->count(),
        ];
        
        // If AJAX request, return only table rows
        if ($request->ajax()) {
            return view('admin.asesor.partials.table-rows', compact('asesor'))->render();
        }
        
        return view('admin.asesor.index', compact('asesor', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $skema = Skema::all();
        return view('admin.asesor.create', compact('skema'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'ID_skema' => 'nullable|integer',
            'no_reg'  => 'nullable|string|max:50|unique:asesor,no_reg|unique:accounts,id',
        ]);

        $asesor = Asesor::create($validated);

        // Auto-create account if no_reg provided
        if (!empty($validated['no_reg'])) {
            Account::create([
                'id'  => $validated['no_reg'],
                'password' => Hash::make($validated['no_reg']),
                'role'    => 'asesor',
            ]);
        }

        return redirect()->route('admin.asesor.index')->with('success', 'Data Asesor berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($ID_asesor)
    {
        $asesor = Asesor::with('skema')->findOrFail($ID_asesor);
        $account = Account::where('id', $asesor->no_reg)->where('role', 'asesor')->first();
        
        return view('admin.asesor.show', compact('asesor', 'account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($ID_asesor)
    {
        $asesor = Asesor::findOrFail($ID_asesor);
        $skema = Skema::all();
        return view('admin.asesor.edit', compact('asesor', 'skema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $ID_asesor)
    {
        $asesor = Asesor::findOrFail($ID_asesor);

        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'ID_skema' => 'nullable|integer',
            'no_reg'  => 'nullable|string|max:50|unique:asesor,no_reg,' . $asesor->ID_asesor . ',ID_asesor|unique:accounts,id,' . ($asesor->no_reg ? Account::where('id', $asesor->no_reg)->value('id') : 'NULL'),
        ]);

        $oldNoReg = $asesor->no_reg;
        $newNoReg = $validated['no_reg'] ?? null;

        $asesor->update($validated);

        // Sync account
        if ($newNoReg && $newNoReg !== $oldNoReg) {
            // Remove old account if exists
            if ($oldNoReg) {
                Account::where('id', $oldNoReg)->where('role', 'asesor')->delete();
            }
            // Create new account
            Account::create([
                'id'  => $newNoReg,
                'password' => Hash::make($newNoReg),
                'role'    => 'asesor',
            ]);
        } elseif (!$newNoReg && $oldNoReg) {
            // no_reg cleared — remove account
            Account::where('id', $oldNoReg)->where('role', 'asesor')->delete();
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
