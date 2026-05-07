<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Asesor;
use App\Models\Account;
use App\Models\Skema;
use App\Models\Tuk;
use App\Models\PersetujuanAsesmen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PersetujuanAsesmenFrontController extends Controller
{
    private function hasAsesiNikColumn(): bool
    {
        static $hasColumn = null;

        if ($hasColumn === null) {
            $hasColumn = Schema::hasColumn('persetujuan_asesmen', 'asesi_nik');
        }

        return $hasColumn;
    }

    private function buildDefaultPayload(array $overrides = []): array
    {
        $payload = array_merge([
            'kode_form' => 'FR.AK.01.',
            'judul_form' => 'PERSETUJUAN ASESMEN DAN KERAHASIAAN',
            'pengantar' => 'Persetujuan Asesmen ini untuk menjamin bahwa Asesi telah diberi arahan secara rinci tentang perencanaan dan proses asesmen',
            'kategori_skema' => 'KKNI/Okupasi/Klaster',
            'tuk' => 'Sewaktu/Tempat Kerja/Mandiri*',
            'nama_asesor' => '-',
            'pernyataan_asesi_1' => 'Bahwa saya telah mendapatkan penjelasan terkait hak dan prosedur banding asesmen dari asesor.',
            'pernyataan_asesor' => 'Menyatakan tidak akan membuka hasil pekerjaan yang saya peroleh karena penugasan saya sebagai Asesor dalam pekerjaan Asesmen kepada siapapun atau organisasi apapun selain kepada pihak yang berwenang sehubungan dengan kewajiban saya sebagai Asesor yang ditugaskan oleh LSP.',
            'pernyataan_asesi_2' => 'Saya setuju mengikuti asesmen dengan pemahaman bahwa informasi yang dikumpulkan hanya digunakan untuk pengembangan profesional dan hanya dapat diakses oleh orang tertentu saja.',
            'catatan_footer' => '* Coret yang tidak perlu',
        ], $overrides);

        if (!$this->hasAsesiNikColumn()) {
            unset($payload['asesi_nik']);
        }

        return $payload;
    }

    public function asesorIndex(Request $request)
    {
        $account = $request->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;
        $useNik = $this->hasAsesiNikColumn();

        $items = collect();

        if ($asesor) {
            $asesiList = $asesor->asesis()->with('skemas')->orderBy('nama')->get();

            $items = $asesiList->flatMap(function ($asesi) {
                return $asesi->skemas->map(function ($skema) use ($asesi) {
                    $record = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
                        ->where(function ($q) use ($asesi, $useNik) {
                            $q->where('nama_asesi', $asesi->nama);
                            if ($useNik) {
                                $q->orWhere('asesi_nik', $asesi->NIK);
                            }
                        })
                        ->latest()
                        ->first();

                    return [
                        'asesi_nik' => $asesi->NIK,
                        'asesi_nama' => $asesi->nama,
                        'skema_id' => $skema->id,
                        'skema_nama' => $skema->nama_skema,
                        'skema_nomor' => $skema->nomor_skema,
                        'status' => $record ? ($record->ttd_asesor_nama ? 'Sudah Ditandatangani' : 'Draft') : 'Belum Ada',
                    ];
                });
            })->values();
        }

        return view('persetujuan-asesmen.asesor-index', [
            'items' => $items,
            'asesor' => $asesor,
        ]);
    }

    public function asesiIndex(Request $request)
    {
        $account = $request->user();
        $asesi = $account ? Asesi::where('NIK', $account->NIK)->first() : null;
        $useNik = $this->hasAsesiNikColumn();

        $items = collect();

        if ($asesi) {
            $items = $asesi->skemas()->withPivot('status', 'tanggal_mulai', 'tanggal_selesai')->get()->map(function ($skema) use ($asesi, $useNik) {
                $record = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
                    ->where(function ($q) use ($asesi, $useNik) {
                        $q->where('nama_asesi', $asesi->nama);
                        if ($useNik) {
                            $q->orWhere('asesi_nik', $asesi->NIK);
                        }
                    })
                    ->latest()
                    ->first();

                return [
                    'skema_id' => $skema->id,
                    'skema_nama' => $skema->nama_skema,
                    'skema_nomor' => $skema->nomor_skema,
                    'status' => $record ? ($record->ttd_asesi_nama ? 'Sudah Ditandatangani' : 'Draft') : 'Belum Ada',
                ];
            })->values();
        }

        return view('persetujuan-asesmen.asesi-index', [
            'items' => $items,
            'asesi' => $asesi,
        ]);
    }

    public function asesorShow(Request $request, $asesiNik, $skemaId)
    {
        $account = $request->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;
        $asesi = Asesi::where('NIK', $asesiNik)->first();
        $skema = Skema::find($skemaId);

        if (!$skema) {
            abort(404);
        }

        $namaAsesi = $asesi ? $asesi->nama : null;
        $useNik = $this->hasAsesiNikColumn();

        $item = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
            ->where(function ($q) use ($namaAsesi, $asesiNik, $useNik) {
                if ($namaAsesi) {
                    $q->where('nama_asesi', $namaAsesi);
                }
                if ($useNik) {
                    $q->orWhere('asesi_nik', $asesiNik);
                }
            })->latest()->first();

        if (!$item) {
            // create placeholder record so form can be signed
            $item = PersetujuanAsesmen::create($this->buildDefaultPayload([
                'judul_skema' => $skema->nama_skema ?? '',
                'nomor_skema' => $skema->nomor_skema ?? '',
                'nama_asesi' => $namaAsesi ?? '',
                'asesi_nik' => $asesiNik,
                'nama_asesor' => $asesor?->nama ?? '-',
            ]));
        }

        $tukList = Tuk::orderBy('nama_tuk')->get(['id','nama_tuk','tipe_tuk','kota']);

        return view('persetujuan-asesmen.front', [
            'item' => $item,
            'role' => 'asesor',
            'skema' => $skema,
            'tukList' => $tukList,
        ]);
    }

    public function asesorSign(Request $request, $id)
    {
        $item = PersetujuanAsesmen::findOrFail($id);

        $data = $request->validate([
            'ttd_asesor_nama' => 'required|string|max:255',
            'ttd_asesor_tanggal' => 'required|date',
        ]);

        $item->update($data);

        return redirect()->back()->with('success', 'Tanda tangan asesor tersimpan');
    }

    public function asesiShow(Request $request, $skemaId)
    {
        $user = auth()->user();
        if (!$user) abort(403);

        $asesiQuery = Asesi::query();
        $hasCondition = false;

        if (!empty($user->NIK)) {
            $asesiQuery->where('NIK', $user->NIK);
            $hasCondition = true;
        }

        if (Schema::hasColumn('asesi', 'no_reg') && !empty($user->id)) {
            if ($hasCondition) {
                $asesiQuery->orWhere('no_reg', $user->id);
            } else {
                $asesiQuery->where('no_reg', $user->id);
                $hasCondition = true;
            }
        }

        $asesi = $hasCondition ? $asesiQuery->first() : null;
        $skema = Skema::find($skemaId);
        if (!$skema) abort(404);
        $useNik = $this->hasAsesiNikColumn();

        $item = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
            ->where(function ($q) use ($asesi, $useNik) {
                if ($asesi) {
                    $q->where('nama_asesi', $asesi->nama);
                    if ($useNik) {
                        $q->orWhere('asesi_nik', $asesi->NIK);
                    }
                }
            })->latest()->first();

        if (!$item) {
            $item = PersetujuanAsesmen::create($this->buildDefaultPayload([
                'judul_skema' => $skema->nama_skema ?? '',
                'nomor_skema' => $skema->nomor_skema ?? '',
                'nama_asesi' => $asesi->nama ?? '',
                'asesi_nik' => $asesi->NIK ?? null,
            ]));
        }

        $tukList = Tuk::orderBy('nama_tuk')->get(['id','nama_tuk','tipe_tuk','kota']);

        return view('persetujuan-asesmen.front', [
            'item' => $item,
            'role' => 'asesi',
            'skema' => $skema,
            'tukList' => $tukList,
        ]);
    }

    public function asesiSign(Request $request, $id)
    {
        $item = PersetujuanAsesmen::findOrFail($id);

        $data = $request->validate([
            'ttd_asesi_nama' => 'required|string|max:255',
            'ttd_asesi_tanggal' => 'required|date',
        ]);

        $item->update($data);

        return redirect()->back()->with('success', 'Tanda tangan asesi tersimpan');
    }
}
