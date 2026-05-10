<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\CeklisObservasiAktivitasPraktik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CeklisObservasiController extends Controller
{
    private function resolveAsesiFromUser(): ?Asesi
    {
        $account = Auth::guard('account')->user();

        if (!$account) {
            return null;
        }

        return Asesi::query()
            ->where('NIK', $account->NIK)
            ->orWhere('no_reg', $account->id)
            ->first();
    }

    public function index()
    {
        $asesi = $this->resolveAsesiFromUser();
        abort_unless((bool) $asesi, 403);

        $items = CeklisObservasiAktivitasPraktik::query()
            ->with(['skema:id,nama_skema,nomor_skema'])
            ->where('asesi_nik', $asesi->NIK)
            ->where(function ($query) {
                $query->whereNotNull('ttd_asesor_file')
                    ->orWhere(function ($q) {
                        $q->whereNotNull('ttd_asesor_nama')
                            ->whereNotNull('ttd_asesor_tanggal');
                    });
            })
            ->latest('id')
            ->get()
            ->map(function ($item) {
                $isSigned = !empty($item->ttd_asesi_file) || (!empty($item->ttd_asesi_nama) && !empty($item->ttd_asesi_tanggal));

                return [
                    'id' => $item->id,
                    'kode_form' => $item->kode_form,
                    'judul_form' => $item->judul_form,
                    'skema_nama' => $item->skema?->nama_skema ?? '-',
                    'skema_nomor' => $item->skema?->nomor_skema ?? '-',
                    'tanggal' => optional($item->tanggal)?->format('Y-m-d'),
                    'status' => $isSigned ? 'Sudah ditandatangani' : 'Menunggu tanda tangan Anda',
                    'can_sign' => !$isSigned,
                ];
            });

        return view('asesi.ceklis-observasi.index', compact('items'));
    }

    public function show($id)
    {
        $asesi = $this->resolveAsesiFromUser();
        abort_unless((bool) $asesi, 403);

        $item = CeklisObservasiAktivitasPraktik::query()
            ->with([
                'skema:id,nama_skema,nomor_skema',
                'asesor:ID_asesor,nama,no_met',
                'details.unit:id,kode_unit,judul_unit',
                'details.elemen:id,unit_id,nama_elemen',
                'details.kriteria:id,elemen_id,deskripsi_kriteria,urutan',
            ])
            ->where('asesi_nik', $asesi->NIK)
            ->findOrFail($id);

        $hasAsesorSignature = !empty($item->ttd_asesor_file) || (!empty($item->ttd_asesor_nama) && !empty($item->ttd_asesor_tanggal));
        if (!$hasAsesorSignature) {
            return redirect()->route('asesi.ceklis-observasi.index')
                ->with('error', 'Ceklis observasi belum siap ditandatangani. Asesor belum menandatangani.');
        }

        $detailsByUnit = $item->details
            ->sortBy([
                ['unit_id', 'asc'],
                ['elemen_id', 'asc'],
                ['kriteria.urutan', 'asc'],
                ['kriteria_id', 'asc'],
            ])
            ->groupBy('unit_id');

        return view('asesi.ceklis-observasi.sign', compact('item', 'detailsByUnit', 'asesi'));
    }

    public function sign(Request $request, $id)
    {
        $asesi = $this->resolveAsesiFromUser();
        abort_unless((bool) $asesi, 403);

        $item = CeklisObservasiAktivitasPraktik::query()
            ->where('asesi_nik', $asesi->NIK)
            ->findOrFail($id);

        $hasAsesorSignature = !empty($item->ttd_asesor_file) || (!empty($item->ttd_asesor_nama) && !empty($item->ttd_asesor_tanggal));
        if (!$hasAsesorSignature) {
            return redirect()->back()->with('error', 'Asesor belum menandatangani ceklis ini.');
        }

        $data = $request->validate([
            'ttd_asesi_tanggal' => 'required|date',
            'ttd_asesi_file' => 'required|string',
        ]);

        try {
            $signatureData = $data['ttd_asesi_file'];

            if (strpos($signatureData, 'data:image') === 0) {
                [, $signatureData] = explode(';', $signatureData, 2);
                [, $signatureData] = explode(',', $signatureData, 2);
            }

            $binary = base64_decode($signatureData, true);
            if ($binary === false) {
                return redirect()->back()->with('error', 'Format tanda tangan tidak valid. Silakan tanda tangani ulang.');
            }

            $filename = 'signature_asesi_' . $item->id . '_' . time() . '.png';
            $path = 'ceklis-observasi/signatures/' . $filename;
            Storage::disk('public')->put($path, $binary);

            $item->update([
                'ttd_asesi_nama' => $asesi->nama,
                'ttd_asesi_tanggal' => $data['ttd_asesi_tanggal'],
                'ttd_asesi_file' => $path,
            ]);

            return redirect()->route('asesi.ceklis-observasi.index')
                ->with('success', 'Tanda tangan ceklis observasi berhasil disimpan.');
        } catch (\Throwable $e) {
            Log::error('Failed to save asesi signature for ceklis observasi', [
                'ceklis_id' => $item->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Gagal menyimpan tanda tangan. Silakan coba lagi.');
        }
    }
}
