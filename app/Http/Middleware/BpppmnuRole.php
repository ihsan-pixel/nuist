<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BpppmnuRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $user = $request->user();
        $allowed = $user && $user->is_active !== false && $user->role === $role;
        if ($role === 'pengurus_bpppmnu' && $user && $user->role === 'tenaga_pendidik') {
            $allowed = $user->bpppmnuMember?->is_active === true;
        }
        abort_unless($allowed, 403);
        $response = $next($request);
        $response->headers->set('Cache-Control', 'private, no-store');

        return $response;
    }
}
