<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Asesi;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BandingAsesmenController extends Controller
{
    /**
     * Display list of banding asesmen (appeals) for asesor
     */
    public function index()
    {
        $account = Auth::guard('account')->user();

        // TODO: Implement asesor banding list
        // Get bandings assigned to this asesor
        // Awaiting database migration and model implementation
        $bandings = collect([]);

        return view('asesor.banding.index', compact('bandings'));
    }

    /**
     * Show banding asesmen form for asesor to process
     */
    public function form($asesiNik, $skemaId)
    {
        $asesi = Asesi::where('NIK', $asesiNik)->firstOrFail();
        $skema = Skema::findOrFail($skemaId);
        $account = Auth::guard('account')->user();

        // TODO: Implement banding form
        // Asesor should be able to approve/reject banding
        // Awaiting database migration and model implementation
        $banding = null;

        return view('asesor.banding.form', compact('asesi', 'skema', 'banding'));
    }
}
