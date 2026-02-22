<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    public function index()
    {
        $carousels = Carousel::orderBy('urutan')->get();
        return view('admin.carousel.index', compact('carousels'));
    }

    public function create()
    {
        return view('admin.carousel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'is_active'   => 'nullable|boolean',
            'urutan'      => 'nullable|integer|min:0',
        ], [
            'image.required' => 'Gambar banner wajib diupload.',
            'image.image'    => 'File harus berupa gambar.',
            'image.mimes'    => 'Format gambar harus jpeg, jpg, png, atau webp.',
            'image.max'      => 'Ukuran gambar maksimal 5MB.',
            'title.required' => 'Judul banner wajib diisi.',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('carousels', 'public');
        }

        $validated['is_active']   = $request->has('is_active') ? 1 : 0;
        $validated['button_text'] = $validated['button_text'] ?? 'Lihat Skema';
        $validated['button_link'] = $validated['button_link'] ?? '#skema';
        $validated['urutan']      = $validated['urutan'] ?? 0;

        Carousel::create($validated);

        return redirect()->route('admin.carousel.index')->with('success', 'Banner carousel berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $carousel = Carousel::findOrFail($id);
        return view('admin.carousel.edit', compact('carousel'));
    }

    public function update(Request $request, $id)
    {
        $carousel = Carousel::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'subtitle'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'is_active'   => 'nullable|boolean',
            'urutan'      => 'nullable|integer|min:0',
        ], [
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, jpg, png, atau webp.',
            'image.max'   => 'Ukuran gambar maksimal 5MB.',
            'title.required' => 'Judul banner wajib diisi.',
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($carousel->image && Storage::disk('public')->exists($carousel->image)) {
                Storage::disk('public')->delete($carousel->image);
            }
            $validated['image'] = $request->file('image')->store('carousels', 'public');
        }

        $validated['is_active']   = $request->has('is_active') ? 1 : 0;
        $validated['button_text'] = $validated['button_text'] ?? 'Lihat Skema';
        $validated['button_link'] = $validated['button_link'] ?? '#skema';
        $validated['urutan']      = $validated['urutan'] ?? 0;

        $carousel->update($validated);

        return redirect()->route('admin.carousel.index')->with('success', 'Banner carousel berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $carousel = Carousel::findOrFail($id);

        // Hapus gambar
        if ($carousel->image && Storage::disk('public')->exists($carousel->image)) {
            Storage::disk('public')->delete($carousel->image);
        }

        $carousel->delete();

        return redirect()->route('admin.carousel.index')->with('success', 'Banner carousel berhasil dihapus!');
    }

    /**
     * Toggle status aktif/nonaktif carousel
     */
    public function toggleStatus($id)
    {
        $carousel = Carousel::findOrFail($id);
        $carousel->update(['is_active' => !$carousel->is_active]);

        $status = $carousel->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.carousel.index')->with('success', "Banner berhasil {$status}!");
    }
}
