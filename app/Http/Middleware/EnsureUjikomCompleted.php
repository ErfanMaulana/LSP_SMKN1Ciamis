<?php

namespace App\Http\Middleware;

use App\Models\Asesi;
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

        if (!$asesi || !$asesi->hasCompletedUjikom()) {
            abort(403, 'Fitur ini tersedia setelah Anda menyelesaikan ujikom.');
        }

        return $next($request);
    }
}
