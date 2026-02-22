<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index()
    {
        $socialMedias = SocialMedia::orderBy('urutan')->get();
        return view('admin.socialmedia.index', compact('socialMedias'));
    }

    public function create()
    {
        return view('admin.socialmedia.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'platform' => 'required|string|max:50',
            'name'     => 'required|string|max:100',
            'url'      => 'required|url',
            'urutan'   => 'nullable|integer|min:0',
        ], [
            'platform.required' => 'Platform wajib dipilih.',
            'name.required'     => 'Nama tampilan wajib diisi.',
            'url.required'      => 'URL wajib diisi.',
            'url.url'           => 'Format URL tidak valid.',
        ]);

        SocialMedia::create([
            'platform'  => $request->platform,
            'name'      => $request->name,
            'url'       => $request->url,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'urutan'    => $request->urutan ?? 0,
        ]);

        return redirect()->route('admin.socialmedia.index')
            ->with('success', 'Sosial media berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $socialMedia = SocialMedia::findOrFail($id);
        return view('admin.socialmedia.edit', compact('socialMedia'));
    }

    public function update(Request $request, $id)
    {
        $socialMedia = SocialMedia::findOrFail($id);

        $request->validate([
            'platform' => 'required|string|max:50',
            'name'     => 'required|string|max:100',
            'url'      => 'required|url',
            'urutan'   => 'nullable|integer|min:0',
        ], [
            'platform.required' => 'Platform wajib dipilih.',
            'name.required'     => 'Nama tampilan wajib diisi.',
            'url.required'      => 'URL wajib diisi.',
            'url.url'           => 'Format URL tidak valid.',
        ]);

        $socialMedia->update([
            'platform'  => $request->platform,
            'name'      => $request->name,
            'url'       => $request->url,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'urutan'    => $request->urutan ?? 0,
        ]);

        return redirect()->route('admin.socialmedia.index')
            ->with('success', 'Sosial media berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $socialMedia = SocialMedia::findOrFail($id);
        $socialMedia->delete();

        return redirect()->route('admin.socialmedia.index')
            ->with('success', 'Sosial media berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $socialMedia = SocialMedia::findOrFail($id);
        $socialMedia->update(['is_active' => !$socialMedia->is_active]);

        return redirect()->route('admin.socialmedia.index')
            ->with('success', 'Status berhasil diubah!');
    }
}
