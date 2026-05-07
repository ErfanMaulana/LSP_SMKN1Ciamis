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

class PersetujuanAsesmenFrontController extends Controller
{
    public function asesorIndex(Request $request)
    {
        $account = $request->user();
        $asesor = $account ? Asesor::where('no_met', $account->id)->first() : null;

        $items = collect();

        if ($asesor) {
            $asesiList = $asesor->asesis()->with('skemas')->orderBy('nama')->get();

            $items = $asesiList->flatMap(function ($asesi) {
                return $asesi->skemas->map(function ($skema) use ($asesi) {
                    $record = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
                        ->where(function ($q) use ($asesi) {
                            $q->where('nama_asesi', $asesi->nama)
                              ->orWhere('asesi_nik', $asesi->NIK);
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

        $items = collect();

        if ($asesi) {
            $items = $asesi->skemas()->withPivot('status', 'tanggal_mulai', 'tanggal_selesai')->get()->map(function ($skema) use ($asesi) {
                $record = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
                    ->where(function ($q) use ($asesi) {
                        $q->where('nama_asesi', $asesi->nama)
                          ->orWhere('asesi_nik', $asesi->NIK);
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
        $asesi = Asesi::where('NIK', $asesiNik)->first();
        $skema = Skema::find($skemaId);

        if (!$skema) {
            abort(404);
        }

        $namaAsesi = $asesi ? $asesi->nama : null;

        $item = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
            ->where(function ($q) use ($namaAsesi, $asesiNik) {
                if ($namaAsesi) {
                    $q->where('nama_asesi', $namaAsesi);
                }
                $q->orWhere('asesi_nik', $asesiNik);
            })->latest()->first();

        if (!$item) {
            // create placeholder record so form can be signed
            $item = PersetujuanAsesmen::create([
                'kode_form' => 'FR.AK.01.',
                'judul_form' => 'PERSETUJUAN ASESMEN DAN KERAHASIAAN',
                'judul_skema' => $skema->nama_skema ?? '',
                'nomor_skema' => $skema->nomor_skema ?? '',
                'nama_asesi' => $namaAsesi ?? '',
                'asesi_nik' => $asesiNik,
            ]);
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

        $asesi = Asesi::where('ID_asesi', $user->id)->orWhere('NIK', $user->NIK ?? '')->first();
        $skema = Skema::find($skemaId);
        if (!$skema) abort(404);

        $item = PersetujuanAsesmen::where('nomor_skema', $skema->nomor_skema)
            ->where(function ($q) use ($asesi) {
                if ($asesi) {
                    $q->where('nama_asesi', $asesi->nama)->orWhere('asesi_nik', $asesi->NIK);
                }
            })->latest()->first();

        if (!$item) {
            $item = PersetujuanAsesmen::create([
                'kode_form' => 'FR.AK.01.',
                'judul_form' => 'PERSETUJUAN ASESMEN DAN KERAHASIAAN',
                'judul_skema' => $skema->nama_skema ?? '',
                'nomor_skema' => $skema->nomor_skema ?? '',
                'nama_asesi' => $asesi->nama ?? '',
                'asesi_nik' => $asesi->NIK ?? null,
            ]);
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
