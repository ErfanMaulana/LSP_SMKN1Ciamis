<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Display a listing of berita
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = Berita::where('status', 'published')
            ->orderBy('tanggal_publikasi', 'desc')
            ->orderBy('created_at', 'desc');
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('konten', 'like', "%{$search}%");
            });
        }
        
        $beritaList = $query->paginate(9);
        
        // Get latest 3 news for sidebar
        $latestBerita = Berita::where('status', 'published')
            ->orderBy('tanggal_publikasi', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        return view('front.berita.index', compact('beritaList', 'latestBerita', 'search'));
    }
    
    /**
     * Display the specified berita
     */
    public function show($slug)
    {
        $berita = Berita::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        
        // Get related news (same author or recent)
        $relatedBerita = Berita::where('status', 'published')
            ->where('id', '!=', $berita->id)
            ->orderBy('tanggal_publikasi', 'desc')
            ->take(3)
            ->get();
        
        // Get active social media
        $socialMedias = SocialMedia::where('is_active', true)
            ->orderBy('urutan')
            ->get();
        
        return view('front.berita.show', compact('berita', 'relatedBerita', 'socialMedias'));
    }
}
