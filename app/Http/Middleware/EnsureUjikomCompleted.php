<?php

namespace App\Http\Middleware;

use App\Models\Asesi;
use App\Models\RekamanAsesmenKompetensi;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUjikomCompleted
{
    public function handle(Request $request, Closure $next): Response
    {
        $account = Auth::guard('account')->user();

        if (!$account) {
            return redirect()->route('login');
        }

        $asesi = Asesi::find($account->NIK);

        if (!$asesi) {
            abort(403, 'Akun asesi tidak ditemukan.');
        }

        // If route includes a skemaId parameter, check completion for that skema specifically
        $skemaId = $request->route('skemaId') ?? $request->route('skema_id') ?? null;

        $query = RekamanAsesmenKompetensi::where('asesi_nik', $asesi->NIK)
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<=', now());

        if ($skemaId) {
            $query->where('skema_id', $skemaId);
        }

        $hasCompleted = $query->exists();

        if (! $hasCompleted) {
            abort(403, 'Fitur ini tersedia setelah Anda menyelesaikan ujikom.');
        }

        return $next($request);
    }
}
