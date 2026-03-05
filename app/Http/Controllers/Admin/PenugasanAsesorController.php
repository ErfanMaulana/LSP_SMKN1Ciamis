<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesor;
use App\Models\Asesi;
use Illuminate\Http\Request;

class PenugasanAsesorController extends Controller
{
    /**
     * Tampilkan daftar penugasan asesor-asesi.
     */
    public function index(Request $request)
    {
        $query = Asesor::with(['skemas', 'asesis.jurusan']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_met', 'like', "%{$search}%");
            });
        }

        $asesors = $query->paginate(10)->withQueryString();

        $stats = [
            'total_asesor'    => Asesor::count(),
            'total_asesi'     => Asesi::whereNotNull('ID_asesor')->count(),
            'belum_ditugaskan' => Asesi::where('status', 'approved')->whereNull('ID_asesor')->count(),
            'asesor_aktif'    => Asesor::has('asesis')->count(),
        ];

        return view('admin.penugasan-asesor.index', compact('asesors', 'stats'));
    }

    /**
     * Tampilkan halaman kelola asesi untuk satu asesor.
     */
    public function show(Request $request, $ID_asesor)
    {
        $asesor = Asesor::with(['skemas', 'asesis.jurusan'])->findOrFail($ID_asesor);

        // Asesi yang sudah ditugaskan ke asesor ini
        $asesiDitugaskan = $asesor->asesis()->with('jurusan')->get();

        // Asesi approved yang belum ditugaskan (atau bisa ditugaskan ulang)
        $asesiTersedia = Asesi::where('status', 'approved')
            ->where(function ($q) use ($ID_asesor) {
                $q->whereNull('ID_asesor')
                  ->orWhere('ID_asesor', '!=', $ID_asesor);
            })
            ->with('jurusan')
            ->get();

        return view('admin.penugasan-asesor.show', compact('asesor', 'asesiDitugaskan', 'asesiTersedia'));
    }

    /**
     * Tugaskan satu asesi ke asesor.
     */
    public function assign(Request $request, $ID_asesor)
    {
        $request->validate([
            'NIK' => 'required|exists:asesi,NIK',
        ]);

        $asesor = Asesor::findOrFail($ID_asesor);
        $asesi  = Asesi::findOrFail($request->NIK);

        $asesi->update(['ID_asesor' => $asesor->ID_asesor]);

        return redirect()->route('admin.penugasan-asesor.show', $ID_asesor)
            ->with('success', "Asesi <strong>{$asesi->nama}</strong> berhasil ditugaskan ke asesor <strong>{$asesor->nama}</strong>.");
    }

    /**
     * Tugaskan banyak asesi sekaligus ke asesor.
     */
    public function assignBulk(Request $request, $ID_asesor)
    {
        $request->validate([
            'niks'   => 'required|array|min:1',
            'niks.*' => 'exists:asesi,NIK',
        ]);

        $asesor = Asesor::findOrFail($ID_asesor);

        Asesi::whereIn('NIK', $request->niks)
             ->update(['ID_asesor' => $asesor->ID_asesor]);

        $count = count($request->niks);

        return redirect()->route('admin.penugasan-asesor.show', $ID_asesor)
            ->with('success', "{$count} asesi berhasil ditugaskan ke asesor <strong>{$asesor->nama}</strong>.");
    }

    /**
     * Lepas penugasan asesi dari asesor.
     */
    public function unassign(Request $request, $ID_asesor, $NIK)
    {
        $asesor = Asesor::findOrFail($ID_asesor);
        $asesi  = Asesi::where('NIK', $NIK)
                       ->where('ID_asesor', $ID_asesor)
                       ->firstOrFail();

        $asesi->update(['ID_asesor' => null]);

        return redirect()->route('admin.penugasan-asesor.show', $ID_asesor)
            ->with('success', "Penugasan asesi <strong>{$asesi->nama}</strong> dari asesor <strong>{$asesor->nama}</strong> berhasil dilepas.");
    }
}
