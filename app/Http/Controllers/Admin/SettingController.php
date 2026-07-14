<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'max_asesi_per_asesor' => Setting::get('max_asesi_per_asesor'),
            'kkm_nilai'            => Setting::get('kkm_nilai', 70),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'max_asesi_per_asesor' => 'nullable|integer|min:1|max:9999',
            'kkm_nilai'            => 'required|integer|min:0|max:100',
        ], [
            'max_asesi_per_asesor.integer' => 'Jumlah maksimal asesi harus berupa angka bulat.',
            'max_asesi_per_asesor.min'     => 'Jumlah maksimal asesi minimal 1.',
            'max_asesi_per_asesor.max'     => 'Jumlah maksimal asesi tidak boleh melebihi 9999.',
            'kkm_nilai.required'           => 'KKM nilai wajib diisi.',
            'kkm_nilai.integer'            => 'KKM nilai harus berupa angka bulat.',
            'kkm_nilai.min'                => 'KKM nilai minimal 0.',
            'kkm_nilai.max'                => 'KKM nilai tidak boleh melebihi 100.',
        ]);

        Setting::updateOrCreate(
            ['key' => 'max_asesi_per_asesor'],
            [
                'value' => $validated['max_asesi_per_asesor'] ?? null,
                'label' => 'Jumlah Maksimal Asesi per Asesor',
            ]
        );

        Setting::updateOrCreate(
            ['key' => 'kkm_nilai'],
            [
                'value' => $validated['kkm_nilai'],
                'label' => 'KKM Nilai (Batas Kelulusan)',
            ]
        );

        return redirect()->back()
            ->with('success', 'Pengaturan global berhasil disimpan.');
    }
}
