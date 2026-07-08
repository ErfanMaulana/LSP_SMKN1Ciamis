<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\UmpanBalikHasil;
use App\Models\UmpanBalikKomponen;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UmpanBalikController extends Controller
{
    private function getAsesi(): ?Asesi
    {
        $account = Auth::guard('account')->user();

        return Asesi::where('NIK', $account->NIK)->first();
    }

    public function index()
    {
        $account = Auth::guard('account')->user();
        $asesi = $this->getAsesi();

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        $skema = $asesi->skemas()->first();

        if (!$skema) {
            return redirect()->route('asesi.dashboard')
                ->with('warning', 'Skema belum terdaftar untuk akun Anda.');
        }

        return redirect()->route('asesi.umpan-balik.show', $skema->id);
    }

    public function show(Request $request, int $skemaId)
    {
        $account = Auth::guard('account')->user();
        $asesi = $this->getAsesi();

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        $skema = $asesi->skemas()->where('skemas.id', $skemaId)->first();

        if (!$skema) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Skema tidak terdaftar untuk akun Anda.');
        }

        $komponenList = UmpanBalikKomponen::where('skema_id', $skemaId)
            ->where('is_active', true)
            ->orderBy('urutan')
            ->get();

        $existing = UmpanBalikHasil::where('asesi_nik', $asesi->NIK)
            ->where('skema_id', $skemaId)
            ->get()
            ->keyBy('komponen_id');

        $totalKomponen = $komponenList->count();
        $totalTerisi = $existing->count();
        
        $isEditMode = (bool) $request->query('edit');
        $isCompleted = $totalKomponen > 0 && $totalKomponen === $totalTerisi;
        if ($isEditMode) {
            $isCompleted = false;
        }
        
        $submittedAt = $existing->max('updated_at');

        return view('asesi.umpan-balik.form', compact('account', 'asesi', 'skema', 'komponenList', 'existing', 'isCompleted', 'submittedAt', 'isEditMode'));
    }

    public function store(Request $request, int $skemaId)
    {
        $asesi = $this->getAsesi();

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        $skema = $asesi->skemas()->where('skemas.id', $skemaId)->first();

        if (!$skema) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Skema tidak terdaftar untuk akun Anda.');
        }

        $komponenIds = UmpanBalikKomponen::where('skema_id', $skemaId)
            ->where('is_active', true)
            ->pluck('id');

        if ($komponenIds->isEmpty()) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Komponen umpan balik untuk skema ini belum tersedia.');
        }

        $isFinalSubmit = $request->has('submit_final');

        if ($isFinalSubmit) {
            $rules = [
                'jawaban' => 'required|array',
            ];

            foreach ($komponenIds as $komponenId) {
                $rules["jawaban.$komponenId.hasil"] = 'required|in:ya,tidak';
                $rules["jawaban.$komponenId.catatan"] = ['nullable', 'string', 'max:1000'];
            }

            $request->validate($rules, [
                'jawaban.required' => 'Semua komponen umpan balik wajib diisi.',
                'jawaban.*.hasil.required' => 'Silakan pilih hasil Ya/Tidak pada semua komponen.',
                'jawaban.*.hasil.in' => 'Pilihan hasil harus Ya atau Tidak.',
            ]);
        } else {
            $request->validate([
                'jawaban' => 'nullable|array',
            ]);
        }

        $savedCount = 0;

        foreach ($komponenIds as $komponenId) {
            $item = $request->input("jawaban.$komponenId", []);
            $hasil = strtolower(trim((string) ($item['hasil'] ?? '')));
            $catatan = trim((string) ($item['catatan'] ?? ''));

            $hasAnyInput = $hasil !== '' || $catatan !== '';

            if (!$hasAnyInput) {
                continue;
            }

            $validator = Validator::make(
                [
                    'hasil' => $hasil,
                    'catatan' => $catatan,
                ],
                [
                    'hasil' => 'required|in:ya,tidak',
                    'catatan' => ['nullable', 'string', 'max:1000'],
                ],
                [
                    'hasil.required' => 'Silakan pilih hasil Ya/Tidak pada komponen yang Anda isi.',
                    'hasil.in' => 'Pilihan hasil harus Ya atau Tidak.',
                ]
            );

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            UmpanBalikHasil::updateOrCreate(
                [
                    'asesi_nik' => $asesi->NIK,
                    'skema_id' => $skemaId,
                    'komponen_id' => $komponenId,
                ],
                [
                    'jawaban' => $hasil,
                    'catatan' => $catatan,
                ]
            );

            $savedCount++;
        }

        if (!$isFinalSubmit && $savedCount === 0) {
            return back()->with('warning', 'Belum ada perubahan untuk disimpan sebagai draft.');
        }

        ActivityLogger::logUser(
            (string) $asesi->NIK,
            $asesi->nama ?? (string) $asesi->NIK,
            $isFinalSubmit ? 'Menyelesaikan Umpan Balik Asesor' : 'Menyimpan Draft Umpan Balik Asesor',
            $isFinalSubmit
                ? 'User menyelesaikan umpan balik kinerja asesor untuk skema "' . $skema->nama_skema . '".'
                : 'User menyimpan draft umpan balik kinerja asesor untuk skema "' . $skema->nama_skema . '".',
            $request,
            ['skema_id' => (int) $skemaId, 'submit_type' => $isFinalSubmit ? 'final' : 'draft']
        );

        if ($isFinalSubmit) {
            return redirect()->route('asesi.umpan-balik.show', $skemaId)
                ->with('success', 'Umpan balik untuk skema "' . $skema->nama_skema . '" berhasil diselesaikan.');
        }

        return redirect()->route('asesi.umpan-balik.show', $skemaId)
            ->with('success', 'Draft umpan balik untuk skema "' . $skema->nama_skema . '" berhasil disimpan.');
    }
}
