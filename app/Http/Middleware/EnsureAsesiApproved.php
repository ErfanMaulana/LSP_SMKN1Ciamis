<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Asesi;
use Symfony\Component\HttpFoundation\Response;

class EnsureAsesiApproved
{
    /**
     * Handle an incoming request.
     * Only allow access if the asesi has been approved by admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $account = Auth::guard('account')->user();

        if (!$account) {
            return redirect()->route('login');
        }

        $asesi = Asesi::find($account->NIK);

        // Belum mendaftar atau belum diverifikasi
        if (!$asesi || $asesi->status !== 'approved') {
            return redirect()->route('asesi.pendaftaran.formulir');
        }

        return $next($request);
    }
}
