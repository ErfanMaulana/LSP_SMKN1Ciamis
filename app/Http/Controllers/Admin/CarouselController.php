<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            // Konversi ke WebP untuk optimasi ukuran file
            $validated['image'] = $this->convertToWebP($request->file('image'));
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
            // Konversi ke WebP untuk optimasi ukuran file
            $validated['image'] = $this->convertToWebP($request->file('image'));
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

    /**
     * Konversi gambar ke format WebP dengan kualitas tinggi
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return string Path file yang disimpan
     */
    private function convertToWebP($file)
    {
        // Cek apakah fungsi imagewebp tersedia
        if (!function_exists('imagewebp')) {
            // Jika GD WebP tidak tersedia, simpan file asli
            return $file->store('carousels', 'public');
        }

        try {
            // Generate nama file unik
            $filename = Str::random(40) . '.webp';
            $path = 'carousels/' . $filename;
            $fullPath = storage_path('app/public/' . $path);

            // Pastikan direktori ada
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Baca file gambar berdasarkan MIME type
            $mimeType = $file->getMimeType();
            $image = null;
            
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    $image = @imagecreatefromjpeg($file->getRealPath());
                    break;
                case 'image/png':
                    $image = @imagecreatefrompng($file->getRealPath());
                    if ($image) {
                        // Preserve transparency untuk PNG
                        imagealphablending($image, false);
                        imagesavealpha($image, true);
                    }
                    break;
                case 'image/webp':
                    $image = @imagecreatefromwebp($file->getRealPath());
                    break;
                case 'image/gif':
                    $image = @imagecreatefromgif($file->getRealPath());
                    break;
                default:
                    // Format tidak didukung, simpan file asli
                    return $file->store('carousels', 'public');
            }

            if (!$image) {
                // Jika gagal membuat image, simpan file asli
                return $file->store('carousels', 'public');
            }

            // Konversi ke WebP dengan kualitas tinggi (90 = kualitas sangat baik)
            $success = @imagewebp($image, $fullPath, 90);
            
            // Bersihkan memory
            imagedestroy($image);

            if ($success && file_exists($fullPath)) {
                return $path;
            }

            // Jika gagal konversi, gunakan metode default
            return $file->store('carousels', 'public');
            
        } catch (\Exception $e) {
            // Log error dan fallback ke metode default
            \Log::error('WebP conversion failed: ' . $e->getMessage());
            return $file->store('carousels', 'public');
        }
    }
}
