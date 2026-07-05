<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Skema;
use App\Models\UmpanBalikHasil;
use App\Models\UmpanBalikKomponen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UmpanBalikHasilController extends Controller
{
    private function getAsesorForAsesi(Asesi $asesi, int $skemaId): ?Asesor
    {
        // 1. Direct relationship on asesi table
        if ($asesi->asesor) {
            return $asesi->asesor;
        }

        // 2. Check asesi_skema pivot reviewed_by (no_met / account id)
        $pivot = DB::table('asesi_skema')
            ->where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->first();

        if ($pivot && !empty($pivot->reviewed_by)) {
            $asesor = Asesor::where('no_met', (string) $pivot->reviewed_by)->first();
            if ($asesor) {
                return $asesor;
            }
        }

        // 3. Check kelompok assignment for this asesi and skema
        if (!empty($asesi->kelompok_id)) {
            $kelompokAsesor = DB::table('kelompok_asesor as ka')
                ->join('kelompok as k', 'ka.kelompok_id', '=', 'k.id')
                ->join('asesor as a', 'ka.asesor_id', '=', 'a.ID_asesor')
                ->where('ka.kelompok_id', $asesi->kelompok_id)
                ->where('k.skema_id', $skemaId)
                ->select('a.ID_asesor')
                ->first();

            if ($kelompokAsesor) {
                return Asesor::find($kelompokAsesor->ID_asesor);
            }
        }

        // 4. Check any observation checklist record for this asesi & skema
        $obsAsesorId = DB::table('ceklis_observasi_aktivitas_praktiks')
            ->where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->value('asesor_id');

        if ($obsAsesorId) {
            return Asesor::find($obsAsesorId);
        }

        return null;
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $query = DB::table('umpan_balik_hasil as ubh')
            ->join('asesi as a', 'ubh.asesi_nik', '=', 'a.NIK')
            ->join('skemas as s', 'ubh.skema_id', '=', 's.id')
            ->leftJoin('asesor as asr', 'a.ID_asesor', '=', 'asr.ID_asesor');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('a.nama', 'like', "%{$search}%")
                    ->orWhere('a.NIK', 'like', "%{$search}%")
                    ->orWhere('s.nama_skema', 'like', "%{$search}%")
                    ->orWhere('s.nomor_skema', 'like', "%{$search}%")
                    ->orWhere('asr.nama', 'like', "%{$search}%");
            });
        }

        $data = $query->select([
                'ubh.asesi_nik',
                'ubh.skema_id',
                'a.nama as asesi_nama',
                's.nama_skema',
                's.nomor_skema',
                'asr.nama as asesor_nama_direct',
                DB::raw('COUNT(ubh.id) as total_terisi'),
                DB::raw('MAX(ubh.updated_at) as submitted_at'),
            ])
            ->groupBy('ubh.asesi_nik', 'ubh.skema_id', 'a.nama', 's.nama_skema', 's.nomor_skema', 'asr.nama')
            ->orderByDesc('submitted_at')
            ->paginate(12)
            ->withQueryString();

        $data->getCollection()->transform(function ($item) {
            if (empty($item->asesor_nama_direct)) {
                $asesiObj = Asesi::where('NIK', $item->asesi_nik)->first();
                if ($asesiObj) {
                    $asesorObj = $this->getAsesorForAsesi($asesiObj, (int) $item->skema_id);
                    $item->asesor_nama = $asesorObj?->nama ?? '-';
                } else {
                    $item->asesor_nama = '-';
                }
            } else {
                $item->asesor_nama = $item->asesor_nama_direct;
            }

            return $item;
        });

        $stats = [
            'total_respon' => DB::table('umpan_balik_hasil')
                ->select('asesi_nik', 'skema_id')
                ->distinct()
                ->get()
                ->count(),
            'total_jawaban' => UmpanBalikHasil::count(),
        ];

        return view('admin.umpan-balik-hasil.index', compact('data', 'stats', 'search'));
    }

    public function show(string $asesiNik, int $skemaId)
    {
        $asesi = Asesi::with(['jurusan', 'asesor'])->where('NIK', $asesiNik)->firstOrFail();
        $skema = Skema::findOrFail($skemaId);

        $asesor = $this->getAsesorForAsesi($asesi, $skemaId);

        $komponenList = UmpanBalikKomponen::where('skema_id', $skemaId)
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $results = UmpanBalikHasil::where('asesi_nik', $asesiNik)
            ->where('skema_id', $skemaId)
            ->get()
            ->keyBy('komponen_id');

        return view('admin.umpan-balik-hasil.show', compact('asesi', 'skema', 'asesor', 'komponenList', 'results'));
    }
}
