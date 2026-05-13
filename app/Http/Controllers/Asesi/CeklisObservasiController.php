<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\CeklisObservasiAktivitasPraktik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CeklisObservasiController extends Controller
{
    /**
     * Show ceklis observasi details for asesi to view and sign
     */
    public function show($id)
    {
        $account = Auth::guard('account')->user();
        abort_unless($account !== null, 403);

        $ceklis = CeklisObservasiAktivitasPraktik::with([
            'asesi:NIK,nama,tuk',
            'skema:id,nama_skema,nomor_skema',
            'details.unit:id,kode_unit,judul_unit',
            'details.elemen:id,nama_elemen',
            'details.kriteria:id,deskripsi_kriteria',
        ])->findOrFail($id);

        // Verify this ceklis belongs to the logged-in asesi
        abort_unless($ceklis->asesi_nik === $account->id, 403);

        $detailsByUnit = $ceklis->details->groupBy(function ($detail) {
            return $detail->unit_id;
        })->map(function ($details) {
            return [
                'unit' => $details->first()->unit,
                'items' => $details->map(function ($detail) {
                    return [
                        'elemen' => $detail->elemen,
                        'kriteria' => $detail->kriteria,
                        'pencapaian' => $detail->pencapaian,
                        'penilaian_lanjut' => $detail->penilaian_lanjut,
                    ];
                })->toArray(),
            ];
        })->values()->toArray();

        return view('asesi.ceklis-observasi.view-and-sign', compact('ceklis', 'detailsByUnit', 'account'));
    }

    /**
     * Sign the ceklis observasi as asesi
     */
    public function sign(Request $request, $id)
    {
        $account = Auth::guard('account')->user();
        abort_unless($account !== null, 403);

        $ceklis = CeklisObservasiAktivitasPraktik::findOrFail($id);

        // Verify this ceklis belongs to the logged-in asesi
        abort_unless($ceklis->asesi_nik === $account->id, 403);

        try {
            $request->validate([
                'ttd_asesi_nama' => 'required|string|max:255',
                'ttd_asesi_tanggal' => 'required|date',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        }

        $ceklis->update([
            'ttd_asesi_nama' => $request->input('ttd_asesi_nama'),
            'ttd_asesi_tanggal' => $request->input('ttd_asesi_tanggal'),
        ]);

        return redirect()->route('asesi.ceklis-observasi.view', $ceklis->id)
            ->with('success', 'Ceklis observasi telah ditandatangani.');
    }
}
