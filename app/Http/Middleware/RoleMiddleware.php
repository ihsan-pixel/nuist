<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = trim(strtolower(Auth::user()->role));
        Log::info('RoleMiddleware raw roles parameter: [' . implode(',', $roles) . ']');

        $rolesArray = array_map(function($role) {
            return trim(strtolower($role));
        }, $roles);

        Log::info('RoleMiddleware check: User role: [' . $userRole . '], Allowed roles: [' . implode(',', $rolesArray) . '], URL: ' . $request->url());

        // Additional debug: log raw role from database
        $rawRole = Auth::user()->role;
        Log::info('RoleMiddleware raw user role from DB: [' . $rawRole . ']');

        if (!in_array($userRole, $rolesArray)) {
            Log::warning('RoleMiddleware: Access denied for user role: [' . $userRole . '] on URL: ' . $request->url());
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
