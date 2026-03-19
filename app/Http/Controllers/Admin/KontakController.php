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
            'alamat'  => 'nullable|string',
            'telepon' => 'nullable|string|max:20',
            'email_1' => 'nullable|email',
        ]);

        $kontak = Kontak::getKontak();

        if ($kontak->id) {
            $kontak->update([
                'alamat'  => $validated['alamat'] ?? null,
                'telepon' => $validated['telepon'] ?? null,
                'email_1' => $validated['email_1'] ?? null,
            ]);
        } else {
            Kontak::create([
                'alamat'  => $validated['alamat'] ?? null,
                'telepon' => $validated['telepon'] ?? null,
                'email_1' => $validated['email_1'] ?? null,
            ]);
        }

        return redirect()->route('admin.kontak.index')
            ->with('success', 'Informasi kontak berhasil diperbarui!');
    }
}
