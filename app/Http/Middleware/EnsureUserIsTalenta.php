<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsTalenta
{
    /**
     * Handle an incoming request.
     * Allow only authenticated users that have a talentaPeserta relation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->talentaPeserta) {
            abort(403, 'Anda bukan peserta talenta.');
        }

        return $next($request);
    }
}
