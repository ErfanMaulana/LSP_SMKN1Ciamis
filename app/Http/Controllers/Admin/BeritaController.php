<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $sort   = $request->get('sort', 'created_at');
        $order  = $request->get('order', 'desc');
        $status = $request->get('status');

        $allowedSorts = ['judul', 'penulis', 'tanggal_publikasi', 'status', 'created_at'];
        if (!in_array($sort, $allowedSorts)) $sort = 'created_at';
        if (!in_array($order, ['asc', 'desc'])) $order = 'desc';

        $query = Berita::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('penulis', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%");
            });
        }

        if ($status && in_array($status, ['draft', 'published'])) {
            $query->where('status', $status);
        }

        $query->orderBy($sort, $order);

        $berita = $query->paginate(10)->withQueryString();

        $stats = [
            'total'       => Berita::count(),
            'published'   => Berita::where('status', 'published')->count(),
            'draft'       => Berita::where('status', 'draft')->count(),
        ];

        // If AJAX request, return only table rows
        if ($request->ajax()) {
            return view('admin.berita.partials.table-rows', compact('berita'))->render();
        }

        return view('admin.berita.index', compact('berita', 'stats', 'search', 'sort', 'order', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.berita.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'             => 'required|string|max:255',
            'konten'            => 'required|string',
            'gambar'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'penulis'           => 'required|string|max:255',
            'tanggal_publikasi' => 'required|date',
            'status'            => 'required|in:draft,published',
        ]);

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '_' . Str::slug($validated['judul']) . '.' . $image->getClientOriginalExtension();
            
            // Store the image to public disk
            $path = $image->storeAs('berita', $imageName, 'public');
            
            if ($path) {
                $validated['gambar'] = $path;
            }
        }

        Berita::create($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.show', compact('berita'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('admin.berita.edit', compact('berita'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $validated = $request->validate([
            'judul'             => 'required|string|max:255',
            'konten'            => 'required|string',
            'gambar'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'penulis'           => 'required|string|max:255',
            'tanggal_publikasi' => 'required|date',
            'status'            => 'required|in:draft,published',
        ]);

        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($berita->gambar) {
                Storage::disk('public')->delete($berita->gambar);
            }

            $image = $request->file('gambar');
            $imageName = time() . '_' . Str::slug($validated['judul']) . '.' . $image->getClientOriginalExtension();
            
            // Store the image to public disk
            $path = $image->storeAs('berita', $imageName, 'public');
            
            if ($path) {
                $validated['gambar'] = $path;
            }
        }

        $berita->update($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        
        // Delete image if exists
        if ($berita->gambar) {
            Storage::disk('public')->delete($berita->gambar);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus!');
    }
}
