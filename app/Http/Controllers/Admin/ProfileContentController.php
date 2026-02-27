<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileContent;
use Illuminate\Http\Request;

class ProfileContentController extends Controller
{
    public function index()
    {
        $sejarah = ProfileContent::byType('sejarah')->get();
        $milestones = ProfileContent::byType('milestone')->orderBy('order')->get();
        $visions = \App\Models\ProfileVisionMission::byType('visi')->active()->get();
        $missions = \App\Models\ProfileVisionMission::byType('misi')->active()->get();
        
        return view('admin.profile-content.index', compact('sejarah', 'milestones', 'visions', 'missions'));
    }

    public function create()
    {
        return view('admin.profile-content.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'    => 'required|in:sejarah,milestone',
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'icon'    => 'nullable|string|max:100',
            'order'   => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'type.required' => 'Tipe konten wajib dipilih.',
            'title.required' => 'Judul wajib diisi.',
            'content.required' => 'Konten wajib diisi.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['order'] = $validated['order'] ?? 0;

        ProfileContent::create($validated);

        $typeLabel = $validated['type'] === 'sejarah' ? 'Sejarah Singkat' : 'Milestone Perjalanan';
        return redirect()->route('admin.profile-content.index')->with('success', "$typeLabel berhasil ditambahkan!");
    }

    public function edit($id)
    {
        $content = ProfileContent::findOrFail($id);
        return view('admin.profile-content.edit', compact('content'));
    }

    public function update(Request $request, $id)
    {
        $content = ProfileContent::findOrFail($id);

        $validated = $request->validate([
            'type'    => 'required|in:sejarah,milestone',
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'icon'    => 'nullable|string|max:100',
            'order'   => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'type.required' => 'Tipe konten wajib dipilih.',
            'title.required' => 'Judul wajib diisi.',
            'content.required' => 'Konten wajib diisi.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['order'] = $validated['order'] ?? 0;

        $content->update($validated);

        $typeLabel = $validated['type'] === 'sejarah' ? 'Sejarah Singkat' : 'Milestone Perjalanan';
        return redirect()->route('admin.profile-content.index')->with('success', "$typeLabel berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $content = ProfileContent::findOrFail($id);
        $typeLabel = $content->type === 'sejarah' ? 'Sejarah Singkat' : 'Milestone Perjalanan';
        
        $content->delete();

        return redirect()->route('admin.profile-content.index')->with('success', "$typeLabel berhasil dihapus!");
    }

    /**
     * Toggle status aktif/nonaktif konten
     */
    public function toggleStatus($id)
    {
        $content = ProfileContent::findOrFail($id);
        $content->update(['is_active' => !$content->is_active]);

        $status = $content->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $typeLabel = $content->type === 'sejarah' ? 'Sejarah Singkat' : 'Milestone Perjalanan';
        return redirect()->route('admin.profile-content.index')->with('success', "$typeLabel berhasil {$status}!");
    }

    /**
     * Create Visi atau Misi
     */
    public function createVisionMission($type)
    {
        if (!in_array($type, ['visi', 'misi'])) {
            abort(404);
        }
        return view('admin.profile-content.create-vision-mission', compact('type'));
    }

    /**
     * Store Visi atau Misi
     */
    public function storeVisionMission(Request $request)
    {
        $type = $request->input('type');
        if (!in_array($type, ['visi', 'misi'])) {
            abort(404);
        }

        $validated = $request->validate([
            'type'    => 'required|in:visi,misi',
            'content' => 'required|string',
            'order'   => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'content.required' => 'Konten wajib diisi.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['order'] = $validated['order'] ?? 0;

        \App\Models\ProfileVisionMission::create($validated);

        $typeLabel = $type === 'visi' ? 'Visi' : 'Misi';
        return redirect()->route('admin.profile-content.index')->with('success', "$typeLabel berhasil ditambahkan!");
    }

    /**
     * Edit Visi atau Misi
     */
    public function editVisionMission($id)
    {
        $item = \App\Models\ProfileVisionMission::findOrFail($id);
        $type = $item->type;
        return view('admin.profile-content.edit-vision-mission', compact('item', 'type'));
    }

    /**
     * Update Visi atau Misi
     */
    public function updateVisionMission(Request $request, $id)
    {
        $item = \App\Models\ProfileVisionMission::findOrFail($id);
        $type = $item->type;

        $validated = $request->validate([
            'content' => 'required|string',
            'order'   => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'content.required' => 'Konten wajib diisi.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['order'] = $validated['order'] ?? 0;

        $item->update($validated);

        $typeLabel = $type === 'visi' ? 'Visi' : 'Misi';
        return redirect()->route('admin.profile-content.index')->with('success', "$typeLabel berhasil diperbarui!");
    }

    /**
     * Destroy Visi atau Misi
     */
    public function destroyVisionMission($id)
    {
        $item = \App\Models\ProfileVisionMission::findOrFail($id);
        $type = $item->type;
        
        $item->delete();

        $typeLabel = $type === 'visi' ? 'Visi' : 'Misi';
        return redirect()->route('admin.profile-content.index')->with('success', "$typeLabel berhasil dihapus!");
    }

    /**
     * Toggle status Visi atau Misi
     */
    public function toggleVisionMissionStatus($id)
    {
        $item = \App\Models\ProfileVisionMission::findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);

        $status = $item->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $typeLabel = $item->type === 'visi' ? 'Visi' : 'Misi';
        return redirect()->route('admin.profile-content.index')->with('success', "$typeLabel berhasil {$status}!");
    }
}
