<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Asesor;
use App\Models\Asesi;
use App\Models\Skema;
use App\Models\UmpanBalikHasil;
use App\Models\UmpanBalikKomponen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UmpanBalikController extends Controller
{
    private function getAsesor()
    {
        $account = Auth::guard('account')->user();
        return Asesor::where('no_met', $account->id)->first();
    }

    public function index(Request $request)
    {
        $asesor = $this->getAsesor();
        if (!$asesor) {
            abort(404, 'Data asesor tidak ditemukan.');
        }

        $search = trim((string) $request->input('search'));

        $baseQuery = DB::table('umpan_balik_hasil as ubh')
            ->join('asesi as a', 'ubh.asesi_nik', '=', 'a.NIK')
            ->join('skemas as s', 'ubh.skema_id', '=', 's.id')
            ->where(function ($q) use ($asesor) {
                $q->where('a.ID_asesor', $asesor->ID_asesor)
                  ->orWhereExists(function ($sq) use ($asesor) {
                      $sq->select(DB::raw(1))
                         ->from('asesi_skema as ask')
                         ->whereColumn('ask.asesi_nik', 'ubh.asesi_nik')
                         ->whereColumn('ask.skema_id', 'ubh.skema_id')
                         ->where('ask.reviewed_by', (string) $asesor->no_met);
                  })
                  ->orWhereExists(function ($sq) use ($asesor) {
                      $sq->select(DB::raw(1))
                         ->from('kelompok_asesor as ka')
                         ->join('kelompok as k', 'ka.kelompok_id', '=', 'k.id')
                         ->whereColumn('a.kelompok_id', 'k.id')
                         ->whereColumn('k.skema_id', 'ubh.skema_id')
                         ->where('ka.asesor_id', $asesor->ID_asesor);
                  })
                  ->orWhereExists(function ($sq) use ($asesor) {
                      $sq->select(DB::raw(1))
                         ->from('ceklis_observasi_aktivitas_praktiks as co')
                         ->whereColumn('co.asesi_nik', 'ubh.asesi_nik')
                         ->whereColumn('co.skema_id', 'ubh.skema_id')
                         ->where('co.asesor_id', $asesor->ID_asesor);
                  });
            });

        // Compute stats for this asesor
        $totalRespon = (clone $baseQuery)
            ->select('ubh.asesi_nik', 'ubh.skema_id')
            ->distinct()
            ->get()
            ->count();

        $totalJawaban = (clone $baseQuery)->count();

        $totalYa = (clone $baseQuery)
            ->where('ubh.jawaban', 'ya')
            ->count();

        $totalCatatan = (clone $baseQuery)
            ->whereNotNull('ubh.catatan')
            ->where('ubh.catatan', '!=', '')
            ->count();

        $persenPositif = $totalJawaban > 0 ? round(($totalYa / $totalJawaban) * 100) : 100;

        $stats = [
            'total_respon'   => $totalRespon,
            'total_jawaban'  => $totalJawaban,
            'persen_positif' => $persenPositif . '%',
            'total_catatan'  => $totalCatatan,
        ];

        $query = clone $baseQuery;

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('a.nama', 'like', '%' . $search . '%')
                  ->orWhere('s.nama_skema', 'like', '%' . $search . '%')
                  ->orWhere('ubh.asesi_nik', 'like', '%' . $search . '%');
            });
        }

        $items = $query->select(
                'ubh.asesi_nik', 
                'ubh.skema_id', 
                'a.nama as asesi_nama', 
                's.nama_skema', 
                's.nomor_skema',
                DB::raw('MAX(ubh.created_at) as submitted_at')
            )
            ->groupBy('ubh.asesi_nik', 'ubh.skema_id', 'a.nama', 's.nama_skema', 's.nomor_skema')
            ->orderBy('submitted_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('asesor.umpan-balik.index', compact('asesor', 'items', 'stats', 'search'));
    }

    public function show($asesiNik, $skemaId)
    {
        $asesor = $this->getAsesor();
        if (!$asesor) {
            abort(404, 'Data asesor tidak ditemukan.');
        }

        $asesi = Asesi::where('NIK', $asesiNik)->firstOrFail();
        $skema = Skema::findOrFail($skemaId);

        $komponenList = UmpanBalikKomponen::where('skema_id', $skemaId)
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $results = UmpanBalikHasil::where('asesi_nik', $asesiNik)
            ->where('skema_id', $skemaId)
            ->get()
            ->keyBy('komponen_id');

        return view('asesor.umpan-balik.show', compact('asesor', 'asesi', 'skema', 'komponenList', 'results'));
    }
}
