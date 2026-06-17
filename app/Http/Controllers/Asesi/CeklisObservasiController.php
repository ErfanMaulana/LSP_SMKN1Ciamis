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
            'asesi:NIK,nama',
            'skema:id,nama_skema,nomor_skema',
            'details.unit:id,kode_unit,judul_unit',
            'details.elemen:id,nama_elemen',
            'details.kriteria:id,deskripsi_kriteria',
        ])->findOrFail($id);

        // Verify this ceklis belongs to the logged-in asesi
        abort_unless($ceklis->asesi_nik === $account->id, 403);
        abort_unless(!empty($ceklis->ttd_asesor_nama) && !empty($ceklis->ttd_asesor_file), 403, 'Ceklis observasi belum diisi atau belum ditandatangani oleh asesor.');

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
        abort_unless(!empty($ceklis->ttd_asesor_nama) && !empty($ceklis->ttd_asesor_file), 403, 'Ceklis observasi belum diisi atau belum ditandatangani oleh asesor.');

        try {
            $request->validate([
                'ttd_asesi_nama' => 'required|string|max:255',
                'ttd_asesi_tanggal' => 'required|date',
                'ttd_asesi_file' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        }

        $ttdAsesiFile = $ceklis->ttd_asesi_file;
        if (!empty($request->ttd_asesi_file) && strpos($request->ttd_asesi_file, 'data:image') === 0) {
            try {
                $signatureData = $request->ttd_asesi_file;
                list($type, $signatureData) = explode(';', $signatureData);
                list(, $signatureData) = explode(',', $signatureData);
                $signatureData = base64_decode($signatureData);

                $filename = 'signature_asesi_' . uniqid() . '_' . time() . '.png';
                $path = 'ceklis-observasi/signatures';

                \Illuminate\Support\Facades\Storage::disk('public')->put($path . '/' . $filename, $signatureData);
                $ttdAsesiFile = $path . '/' . $filename;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to save ttd_asesi_file: ' . $e->getMessage());
            }
        }

        $ceklis->update([
            'ttd_asesi_nama' => $request->input('ttd_asesi_nama'),
            'ttd_asesi_tanggal' => $request->input('ttd_asesi_tanggal'),
            'ttd_asesi_file' => $ttdAsesiFile,
        ]);

        return redirect()->route('asesi.ceklis-observasi.view', $ceklis->id)
            ->with('success', 'Ceklis observasi telah ditandatangani.');
    }
}
