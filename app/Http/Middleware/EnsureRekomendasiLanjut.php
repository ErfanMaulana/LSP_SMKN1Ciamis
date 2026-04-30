<?php

namespace App\Http\Middleware;

use App\Models\Asesi;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRekomendasiLanjut
{
    public function handle(Request $request, Closure $next): Response
    {
        $account = Auth::guard('account')->user();

        if (! $account) {
            return redirect()->route('login');
        }

        $asesi = Asesi::find($account->NIK);

        if (! $asesi) {
            abort(403, 'Akun asesi tidak ditemukan.');
        }

        if (! method_exists($asesi, 'hasRekomendasiLanjut') || ! $asesi->hasRekomendasiLanjut()) {
            return redirect()->route('asesi.asesmen-mandiri.index')
                ->with('warning', 'Jadwal ujikom tersedia setelah rekomendasi asesor.');
        }

        return $next($request);
    }
}
