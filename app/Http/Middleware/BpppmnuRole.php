<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BpppmnuRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        abort_unless($request->user() && $request->user()->role === $role && $request->user()->is_active !== false, 403);
        $response = $next($request);
        $response->headers->set('Cache-Control', 'private, no-store');

        return $response;
    }
}
