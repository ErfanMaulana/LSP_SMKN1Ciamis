<?php

namespace App\Http\Controllers\Asesi;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BandingAsesmenController extends Controller
{
    /**
     * Get authenticated asesi
     */
    private function getAsesi(): ?Asesi
    {
        $account = Auth::guard('account')->user();
        return Asesi::where('NIK', $account->NIK)->first();
    }

    /**
     * Display list of banding asesmen (appeals)
     */
    public function index()
    {
        $asesi = $this->getAsesi();

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        // TODO: Implement banding asesmen listing
        // Awaiting database migration and model implementation
        $bandings = collect([]);

        return view('asesi.banding.index', compact('bandings'));
    }

    /**
     * Show banding asesmen detail
     */
    public function show($skemaId)
    {
        $asesi = $this->getAsesi();
        $skema = Skema::findOrFail($skemaId);

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        // TODO: Implement banding detail view
        // Awaiting database migration and model implementation
        $banding = null;

        return view('asesi.banding.show', compact('skema', 'banding'));
    }

    /**
     * Store new banding asesmen
     */
    public function store(Request $request, $skemaId)
    {
        $asesi = $this->getAsesi();
        $skema = Skema::findOrFail($skemaId);

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        // TODO: Implement banding submission
        // Awaiting database migration, model, and validation rules

        return redirect()->route('asesi.banding.index')
            ->with('success', 'Banding asesmen berhasil diajukan.');
    }

    /**
     * Decline/withdraw banding asesmen
     */
    public function decline(Request $request, $skemaId)
    {
        $asesi = $this->getAsesi();
        $skema = Skema::findOrFail($skemaId);

        if (!$asesi) {
            return redirect()->route('asesi.dashboard')
                ->with('error', 'Data asesi tidak ditemukan.');
        }

        // TODO: Implement banding decline/withdrawal
        // Awaiting database migration and model implementation

        return redirect()->route('asesi.banding.index')
            ->with('success', 'Banding asesmen berhasil dibatalkan.');
    }
}
