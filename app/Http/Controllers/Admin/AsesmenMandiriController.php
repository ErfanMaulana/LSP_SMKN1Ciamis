<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Skema;
use App\Models\JawabanElemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsesmenMandiriController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('asesi_skema')
            ->join('asesi', 'asesi_skema.asesi_nik', '=', 'asesi.NIK')
            ->join('skemas', 'asesi_skema.skema_id', '=', 'skemas.id')
            ->leftJoin('jurusan', 'asesi.ID_jurusan', '=', 'jurusan.ID_jurusan')
            ->select(
                'asesi_skema.*',
                'asesi.NIK',
                'asesi.nama as asesi_nama',
                'asesi.kelas',
                'skemas.id as skema_id',
                'skemas.nama_skema',
                'skemas.nomor_skema as kode_skema',
                'jurusan.nama_jurusan'
            );

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('asesi.nama', 'like', "%{$search}%")
                  ->orWhere('asesi.NIK', 'like', "%{$search}%")
                  ->orWhere('skemas.nama_skema', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('asesi_skema.status', $status);
        }

        // Filter by skema
        if ($skemaId = $request->get('skema_id')) {
            $query->where('asesi_skema.skema_id', $skemaId);
        }

        $query->orderByRaw("FIELD(asesi_skema.status, 'selesai', 'sedang_mengerjakan', 'belum_mulai')");
        $query->orderBy('asesi_skema.updated_at', 'desc');

        $data = $query->paginate(15)->withQueryString();

        // Stats
        $stats = DB::table('asesi_skema')
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'belum_mulai' THEN 1 ELSE 0 END) as belum_mulai,
                SUM(CASE WHEN status = 'sedang_mengerjakan' THEN 1 ELSE 0 END) as sedang_mengerjakan,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai
            ")
            ->first();

        $skemas = Skema::orderBy('nama_skema')->get();

        if ($request->ajax()) {
            return view('admin.asesmen-mandiri._table', compact('data'))->render();
        }

        return view('admin.asesmen-mandiri.index', compact('data', 'stats', 'skemas'));
    }

    public function show($asesiNik, $skemaId)
    {
        $asesi = Asesi::where('NIK', $asesiNik)->firstOrFail();
        $skema = Skema::with('units.elemens.kriteria')->findOrFail($skemaId);

        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesiNik)
            ->where('skema_id', $skemaId)
            ->first();

        if (!$pivot) {
            return redirect()->route('admin.asesmen-mandiri.index')
                ->with('error', 'Data asesmen tidak ditemukan.');
        }

        $jawaban = JawabanElemen::where('asesi_nik', $asesiNik)
            ->whereIn('elemen_id', $skema->units->pluck('elemens')->flatten()->pluck('id'))
            ->get()
            ->keyBy('elemen_id');

        $reviewer = null;
        if ($pivot->reviewed_by) {
            $reviewer = \App\Models\Asesor::where('no_reg', $pivot->reviewed_by)->first();
        }

        return view('admin.asesmen-mandiri.show', compact('asesi', 'skema', 'pivot', 'jawaban', 'reviewer'));
    }
}
