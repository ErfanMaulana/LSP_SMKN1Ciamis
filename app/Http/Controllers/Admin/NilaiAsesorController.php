<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiAsesorController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('asesor_nilai_elemens as ane')
            ->join('asesi as a', 'ane.asesi_nik', '=', 'a.NIK')
            ->join('skemas as s', 'ane.skema_id', '=', 's.id')
            ->leftJoin('asesor as ar', 'ane.asesor_id', '=', 'ar.ID_asesor')
            ->selectRaw('ane.asesi_nik, ane.skema_id, MAX(a.nama) as nama_asesi, MAX(a.email) as email_asesi, MAX(s.nama_skema) as nama_skema, MAX(s.nomor_skema) as nomor_skema, MAX(ar.nama) as nama_asesor, COUNT(*) as total_elemen, AVG(ane.nilai) as rata_rata, SUM(CASE WHEN ane.status = "K" THEN 1 ELSE 0 END) as total_k, MAX(ane.updated_at) as terakhir_dinilai')
            ->groupBy('ane.asesi_nik', 'ane.skema_id');

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('a.nama', 'like', "%{$search}%")
                    ->orWhere('ane.asesi_nik', 'like', "%{$search}%")
                    ->orWhere('s.nama_skema', 'like', "%{$search}%")
                    ->orWhere('ar.nama', 'like', "%{$search}%");
            });
        }

        if ($skemaId = $request->get('skema_id')) {
            $query->where('ane.skema_id', $skemaId);
        }

        $data = $query
            ->orderByDesc('terakhir_dinilai')
            ->paginate(15)
            ->withQueryString();

        $stats = DB::table('asesor_nilai_elemens')
            ->selectRaw('COUNT(DISTINCT CONCAT(asesi_nik, "-", skema_id)) as total_form, COUNT(*) as total_elemen_dinilai, ROUND(AVG(nilai), 2) as rata_global')
            ->first();

        $skemas = Skema::orderBy('nama_skema')->get(['id', 'nama_skema']);

        return view('admin.nilai-asesor.index', compact('data', 'stats', 'skemas'));
    }

    public function show($asesiNik, $skemaId)
    {
        $asesi = Asesi::where('NIK', $asesiNik)->firstOrFail();
        $skema = Skema::with('units.elemens')->findOrFail($skemaId);

        $rows = DB::table('asesor_nilai_elemens as ane')
            ->join('elemens as e', 'ane.elemen_id', '=', 'e.id')
            ->join('units as u', 'e.unit_id', '=', 'u.id')
            ->leftJoin('asesor as ar', 'ane.asesor_id', '=', 'ar.ID_asesor')
            ->where('ane.asesi_nik', $asesiNik)
            ->where('ane.skema_id', $skemaId)
            ->select([
                'ane.id',
                'ane.nilai',
                'ane.status',
                'ane.updated_at',
                'e.id as elemen_id',
                'e.nama_elemen',
                'u.kode_unit',
                'u.nama_unit',
                'ar.nama as nama_asesor',
            ])
            ->orderBy('u.kode_unit')
            ->orderBy('e.id')
            ->get();

        $summary = (object) [
            'total_elemen' => $rows->count(),
            'total_k' => $rows->where('status', 'K')->count(),
            'rata_rata' => $rows->count() ? round($rows->avg('nilai'), 2) : 0,
        ];

        return view('admin.nilai-asesor.show', compact('asesi', 'skema', 'rows', 'summary'));
    }
}
