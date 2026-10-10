<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BpppmnuRole
{
    public function handle(Request $request, Closure $next, string ...$requestedRoles)
    {
        $user = $request->user();
        $roles = [];
        foreach ($requestedRoles as $requestedRole) {
            array_push($roles, ...array_filter(array_map('trim', explode(',', $requestedRole))));
        }
        $allowed = $user && $user->is_active !== false && in_array($user->role, $roles, true);
        if (in_array('pengurus_bpppmnu', $roles, true) && $user && $user->role === 'tenaga_pendidik') {
            $allowed = $user->bpppmnuMember?->is_active === true;
        }
        abort_unless($allowed, 403);
        $response = $next($request);
        $response->headers->set('Cache-Control', 'private, no-store');

        return $response;
    }
}
