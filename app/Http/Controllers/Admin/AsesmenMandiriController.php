<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Skema;
use App\Models\JawabanElemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
                'asesi.status as asesi_status',
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
            $reviewer = \App\Models\Asesor::where('no_met', $pivot->reviewed_by)->first();
        }

        return view('admin.asesmen-mandiri.show', compact('asesi', 'skema', 'pivot', 'jawaban', 'reviewer'));
    }

    public function reset(Request $request, $asesiNik, $skemaId)
    {
        // authorize via middleware route permission

        DB::beginTransaction();
        try {
            // Reset pivot row in asesI_skema
            DB::table('asesi_skema')
                ->where('asesi_nik', $asesiNik)
                ->where('skema_id', $skemaId)
                ->update([
                    'status' => 'belum_mulai',
                    'tanggal_mulai' => null,
                    'tanggal_selesai' => null,
                    'rekomendasi' => null,
                    'catatan_asesor' => null,
                    'reviewed_by' => null,
                    'reviewed_at' => null,
                    'tanda_tangan' => null,
                    'tanggal_tanda_tangan' => null,
                    'updated_at' => now(),
                ]);

            // Delete jawaban elemen for this asesi and skema elements
            $skema = Skema::with('units.elemens')->find($skemaId);
            if ($skema) {
                $elemenIds = $skema->units->pluck('elemens')->flatten()->pluck('id')->all();
                if (!empty($elemenIds)) {
                    JawabanElemen::where('asesi_nik', $asesiNik)
                        ->whereIn('elemen_id', $elemenIds)
                        ->delete();
                }
            }

            DB::commit();
            return redirect()->route('admin.asesmen-mandiri.index')->with('success', 'Asesmen mandiri direset. Asesi akan mengulang pengisian.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.asesmen-mandiri.index')->with('error', 'Gagal mereset asesmen: ' . $e->getMessage());
        }
    }
}
