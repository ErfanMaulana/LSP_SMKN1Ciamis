<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BuktiPersyaratanDasarPemohon;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BuktiPersyaratanDasarPemohonController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search'));

        $items = BuktiPersyaratanDasarPemohon::query()
            ->with('skema:id,nama_skema,nomor_skema,jenis_skema')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('skema', function ($skemaQuery) use ($search) {
                    $skemaQuery->where('nama_skema', 'like', "%{$search}%")
                        ->orWhere('nomor_skema', 'like', "%{$search}%");
                })->orWhereJsonContains('items', $search);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.bukti-persyaratan-dasar-pemohon.index', compact('items', 'search'));
    }

    public function create()
    {
        $skemaList = Skema::query()
            ->with('buktiPersyaratanDasarPemohon:id,skema_id')
            ->orderBy('nama_skema')
            ->get(['id', 'nama_skema', 'nomor_skema', 'jenis_skema']);

        $item = new BuktiPersyaratanDasarPemohon([
            'items' => [''],
        ]);

        return view('admin.bukti-persyaratan-dasar-pemohon.create', compact('skemaList', 'item'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatePayload($request);

        DB::transaction(function () use ($validated) {
            BuktiPersyaratanDasarPemohon::updateOrCreate(
                ['skema_id' => $validated['skema_id']],
                ['items' => $validated['items']]
            );
        });

        return redirect()->route('admin.bukti-persyaratan-dasar-pemohon.index')
            ->with('success', 'Bukti persyaratan dasar pemohon berhasil disimpan.');
    }

    public function edit($id)
    {
        $item = BuktiPersyaratanDasarPemohon::with('skema')->findOrFail($id);

        $skemaList = Skema::query()
            ->with('buktiPersyaratanDasarPemohon:id,skema_id')
            ->orderBy('nama_skema')
            ->get(['id', 'nama_skema', 'nomor_skema', 'jenis_skema']);

        return view('admin.bukti-persyaratan-dasar-pemohon.edit', compact('item', 'skemaList'));
    }

    public function update(Request $request, $id)
    {
        $item = BuktiPersyaratanDasarPemohon::findOrFail($id);
        $validated = $this->validatePayload($request, $item->id);

        DB::transaction(function () use ($item, $validated) {
            $item->update([
                'skema_id' => $validated['skema_id'],
                'items' => $validated['items'],
            ]);
        });

        return redirect()->route('admin.bukti-persyaratan-dasar-pemohon.index')
            ->with('success', 'Bukti persyaratan dasar pemohon berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = BuktiPersyaratanDasarPemohon::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.bukti-persyaratan-dasar-pemohon.index')
            ->with('success', 'Bukti persyaratan dasar pemohon berhasil dihapus.');
    }

    private function validatePayload(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'skema_id' => [
                'required',
                'exists:skemas,id',
                Rule::unique('bukti_persyaratan_dasar_pemohon', 'skema_id')->ignore($ignoreId),
            ],
            'items' => 'required|array|min:1',
            'items.*' => 'required|string|max:255|distinct',
        ], [
            'skema_id.required' => 'Skema wajib dipilih.',
            'skema_id.unique' => 'Skema ini sudah memiliki data persyaratan dasar pemohon.',
            'items.required' => 'Minimal satu item persyaratan harus diisi.',
            'items.*.required' => 'Item persyaratan tidak boleh kosong.',
            'items.*.distinct' => 'Item persyaratan tidak boleh duplikat.',
        ]);
    }
}