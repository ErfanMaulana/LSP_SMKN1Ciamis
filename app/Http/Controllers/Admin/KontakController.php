<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kontak;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    /**
     * Display kontak information (single page)
     */
    public function index()
    {
        $kontak = Kontak::getKontak();
        return view('admin.kontak.index', compact('kontak'));
    }

    /**
     * Show the form for editing kontak
     */
    public function edit()
    {
        $kontak = Kontak::getKontak();
        return view('admin.kontak.edit', compact('kontak'));
    }

    /**
     * Update kontak information
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'alamat'               => 'nullable|string',
            'telepon'              => 'nullable|string|max:20',
            'telepon_whatsapp'     => 'nullable|string|max:20',
            'email_1'              => 'nullable|email',
            'email_2'              => 'nullable|email',
            'jam_senin_kamis_awal' => 'nullable|date_format:H:i',
            'jam_senin_kamis_akhir'=> 'nullable|date_format:H:i',
            'jam_jumat_awal'       => 'nullable|date_format:H:i',
            'jam_jumat_akhir'      => 'nullable|date_format:H:i',
        ]);

        $kontak = Kontak::getKontak();
        
        // Build jam_pelayanan array
        $jamPelayanan = [
            'senin_kamis' => [
                'awal'  => $validated['jam_senin_kamis_awal'] ?? '07:00',
                'akhir' => $validated['jam_senin_kamis_akhir'] ?? '15:00',
            ],
            'jumat' => [
                'awal'  => $validated['jam_jumat_awal'] ?? '07:00',
                'akhir' => $validated['jam_jumat_akhir'] ?? '11:30',
            ],
        ];

        if ($kontak->id) {
            $kontak->update([
                'alamat'               => $validated['alamat'],
                'telepon'              => $validated['telepon'],
                'telepon_whatsapp'     => $validated['telepon_whatsapp'],
                'email_1'              => $validated['email_1'],
                'email_2'              => $validated['email_2'],
                'jam_pelayanan'        => $jamPelayanan,
            ]);
        } else {
            Kontak::create([
                'alamat'               => $validated['alamat'],
                'telepon'              => $validated['telepon'],
                'telepon_whatsapp'     => $validated['telepon_whatsapp'],
                'email_1'              => $validated['email_1'],
                'email_2'              => $validated['email_2'],
                'jam_pelayanan'        => $jamPelayanan,
            ]);
        }

        return redirect()->route('admin.kontak.index')
            ->with('success', 'Informasi kontak berhasil diperbarui!');
    }
}
