<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\RekamanAsesmenKompetensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RekamanAsesmenController extends Controller
{
    /**
     * Show rekaman asesmen details for asesi to view and sign
     */
    public function show($id)
    {
        $account = Auth::guard('account')->user();
        abort_unless($account !== null, 403);

        $item = RekamanAsesmenKompetensi::with([
            'asesi:NIK,nama',
            'skema:id,nama_skema,nomor_skema',
            'details.unit:id,kode_unit,judul_unit',
            'asesor:ID_asesor,nama',
        ])->findOrFail($id);

        // Verify this record belongs to the logged-in candidate
        abort_unless($item->asesi_nik === $account->id, 403);
        abort_unless(!empty($item->ttd_asesor_nama) && !empty($item->ttd_asesor_file), 403, 'Rekaman asesmen belum diisi atau belum ditandatangani oleh asesor.');

        $details = $item->details->sortBy([
            ['unit.id', 'asc'],
        ])->values();

        return view('asesi.rekaman-asesmen.view-and-sign', compact('item', 'details', 'account'));
    }

    /**
     * Sign the rekaman asesmen as asesi
     */
    public function sign(Request $request, $id)
    {
        $account = Auth::guard('account')->user();
        abort_unless($account !== null, 403);

        $item = RekamanAsesmenKompetensi::findOrFail($id);

        // Verify this record belongs to the logged-in candidate
        abort_unless($item->asesi_nik === $account->id, 403);
        abort_unless(!empty($item->ttd_asesor_nama) && !empty($item->ttd_asesor_file), 403, 'Rekaman asesmen belum diisi atau belum ditandatangani oleh asesor.');

        try {
            $request->validate([
                'ttd_asesi_nama' => 'required|string|max:255',
                'ttd_asesi_tanggal' => 'required|date',
                'ttd_asesi_file' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        }

        $ttdAsesiFile = $item->ttd_asesi_file;
        if (!empty($request->ttd_asesi_file) && strpos($request->ttd_asesi_file, 'data:image') === 0) {
            try {
                $signatureData = $request->ttd_asesi_file;
                list($type, $signatureData) = explode(';', $signatureData);
                list(, $signatureData) = explode(',', $signatureData);
                $signatureData = base64_decode($signatureData);

                $filename = 'signature_asesi_' . uniqid() . '_' . time() . '.png';
                $path = 'rekaman-asesmen/signatures';

                \Illuminate\Support\Facades\Storage::disk('public')->put($path . '/' . $filename, $signatureData);
                $ttdAsesiFile = $path . '/' . $filename;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to save ttd_asesi_file on rekaman asesmen: ' . $e->getMessage());
            }
        }

        $item->update([
            'ttd_asesi_nama' => $request->input('ttd_asesi_nama'),
            'ttd_asesi_tanggal' => $request->input('ttd_asesi_tanggal'),
            'ttd_asesi_file' => $ttdAsesiFile,
        ]);

        return redirect()->route('asesi.rekaman-asesmen.view', $item->id)
            ->with('success', 'Rekaman asesmen kompetensi telah ditandatangani.');
    }
}
