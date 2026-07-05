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

        $asesiModel = \App\Models\Asesi::where('NIK', $account->id)->first(['NIK', 'tanda_tangan_pendaftar', 'tanda_tangan']);
        $savedSignature = null;
        if ($asesiModel) {
            $raw = $asesiModel->tanda_tangan_pendaftar ?? $asesiModel->tanda_tangan ?? null;
            $savedSignature = $this->formatSignatureUrl($raw);
        }

        return view('asesi.rekaman-asesmen.view-and-sign', compact('item', 'details', 'account', 'savedSignature'));
    }

    private function formatSignatureUrl(?string $sig): ?string
    {
        if (empty($sig)) return null;
        $sig = trim($sig);
        if (str_contains($sig, '/storage/')) {
            return asset('storage/' . ltrim(explode('/storage/', $sig)[1], '/'));
        }
        if (str_starts_with($sig, 'http://') || str_starts_with($sig, 'https://')) {
            return $sig;
        }
        if (str_starts_with($sig, 'rekaman-asesmen/') || str_starts_with($sig, 'ceklis-observasi/') || str_starts_with($sig, 'persetujuan-asesmen/') || str_starts_with($sig, 'signatures/') || str_starts_with($sig, 'pendaftar/')) {
            return asset('storage/' . ltrim($sig, '/'));
        }
        if (str_starts_with($sig, 'data:image')) {
            return preg_replace('/\s+/', '', $sig);
        }
        return 'data:image/png;base64,' . preg_replace('/\s+/', '', $sig);
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
        $rawInput = $request->ttd_asesi_file ?? '';

        if (!empty($rawInput)) {
            if (str_starts_with($rawInput, 'data:image')) {
                // Canvas base64 data URL — decode and save
                try {
                    $parts = explode('base64,', $rawInput);
                    $b64 = end($parts);
                    $binary = base64_decode(preg_replace('/\s+/', '', $b64), true);
                    if ($binary !== false && strlen($binary) > 50) {
                        $filename = 'signature_asesi_' . uniqid() . '_' . time() . '.png';
                        $path = 'rekaman-asesmen/signatures';
                        \Illuminate\Support\Facades\Storage::disk('public')->put($path . '/' . $filename, $binary);
                        $ttdAsesiFile = $path . '/' . $filename;
                    }
                } catch (\Exception $ex) {
                    \Illuminate\Support\Facades\Log::error('Failed to save ttd_asesi_file on rekaman asesmen: ' . $ex->getMessage());
                }
            } elseif (str_contains($rawInput, '/storage/')) {
                // Full URL path from saved signature — convert to relative
                $ttdAsesiFile = ltrim(explode('/storage/', $rawInput)[1], '/');
            } elseif (str_starts_with($rawInput, 'rekaman-asesmen/') || str_starts_with($rawInput, 'ceklis-observasi/') || str_starts_with($rawInput, 'persetujuan-asesmen/') || str_starts_with($rawInput, 'signatures/') || str_starts_with($rawInput, 'pendaftar/')) {
                // Already a relative storage path
                $ttdAsesiFile = $rawInput;
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
