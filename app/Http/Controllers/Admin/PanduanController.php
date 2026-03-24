<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PanduanItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PanduanController extends Controller
{
    private const SECTIONS = [
        'alur-keseluruhan-sistem' => [
            'title' => 'Alur Keseluruhan Sistem',
            'front_route' => 'front.panduan.overview',
        ],
        'peran-asesi' => [
            'title' => 'Peran Asesi',
            'front_route' => 'front.panduan.asesi',
        ],
        'peran-asesor' => [
            'title' => 'Peran Asesor',
            'front_route' => 'front.panduan.asesor',
        ],
        'peran-admin' => [
            'title' => 'Peran Admin',
            'front_route' => 'front.panduan.admin',
        ],
    ];

    public function index(string $section)
    {
        $meta = $this->sectionMeta($section);

        $items = PanduanItem::bySection($section)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        return view('admin.panduan.index', [
            'section' => $section,
            'sectionMeta' => $meta,
            'sections' => self::SECTIONS,
            'items' => $items,
        ]);
    }

    public function create(string $section)
    {
        $meta = $this->sectionMeta($section);

        return view('admin.panduan.create', [
            'section' => $section,
            'sectionMeta' => $meta,
            'sections' => self::SECTIONS,
        ]);
    }

    public function store(Request $request, string $section)
    {
        $this->sectionMeta($section);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('panduan', 'public');
        }

        $validated['section'] = $section;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        PanduanItem::create($validated);

        return redirect()
            ->route('admin.panduan.index', $section)
            ->with('success', 'Poin panduan berhasil ditambahkan.');
    }

    public function edit(string $section, int $id)
    {
        $meta = $this->sectionMeta($section);

        $item = PanduanItem::bySection($section)->findOrFail($id);

        return view('admin.panduan.edit', [
            'section' => $section,
            'sectionMeta' => $meta,
            'sections' => self::SECTIONS,
            'item' => $item,
        ]);
    }

    public function update(Request $request, string $section, int $id)
    {
        $this->sectionMeta($section);

        $item = PanduanItem::bySection($section)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'remove_image' => 'nullable|boolean',
        ]);

        if ($request->boolean('remove_image') && $item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
            $validated['image'] = null;
        }

        if ($request->hasFile('image')) {
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('panduan', 'public');
        }

        unset($validated['remove_image']);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->has('is_active');

        $item->update($validated);

        return redirect()
            ->route('admin.panduan.index', $section)
            ->with('success', 'Poin panduan berhasil diperbarui.');
    }

    public function destroy(string $section, int $id)
    {
        $this->sectionMeta($section);

        $item = PanduanItem::bySection($section)->findOrFail($id);

        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()
            ->route('admin.panduan.index', $section)
            ->with('success', 'Poin panduan berhasil dihapus.');
    }

    public function bulkDestroy(Request $request, string $section)
    {
        $this->sectionMeta($section);

        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:panduan_items,id',
        ]);

        $items = PanduanItem::bySection($section)
            ->whereIn('id', $validated['ids'])
            ->get();

        if ($items->isEmpty()) {
            return redirect()
                ->route('admin.panduan.index', $section)
                ->with('error', 'Tidak ada poin yang valid untuk dihapus.');
        }

        foreach ($items as $item) {
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
        }

        PanduanItem::whereIn('id', $items->pluck('id'))->delete();

        return redirect()
            ->route('admin.panduan.index', $section)
            ->with('success', $items->count() . ' poin panduan berhasil dihapus.');
    }

    public function toggleStatus(string $section, int $id)
    {
        $this->sectionMeta($section);

        $item = PanduanItem::bySection($section)->findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);

        return redirect()
            ->route('admin.panduan.index', $section)
            ->with('success', 'Status poin panduan berhasil diubah.');
    }

    private function sectionMeta(string $section): array
    {
        abort_unless(isset(self::SECTIONS[$section]), 404);
        return self::SECTIONS[$section];
    }
}
