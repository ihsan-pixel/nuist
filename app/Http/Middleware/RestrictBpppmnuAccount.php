<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** Only the new role is affected; existing role routing stays unchanged. */
class RestrictBpppmnuAccount
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user?->role === 'pengurus_bpppmnu') {
            if ($request->is('/') || $request->is('dashboard') || $request->is('mobile/dashboard')) {
                return redirect('/mobile/bpppmnu/presensi');
            }
            $allowed = $request->routeIs('mobile.bpppmnu.*', 'api.bpppmnu.*')
                || $request->is('logout', 'mobile/login', 'login', 'api/mobile/logout', 'api/mobile/me', 'api/mobile/push-token', 'mobile/push-token');
            abort_unless($allowed && $user->is_active !== false, 403);
        }

        return $next($request);
    }
}
