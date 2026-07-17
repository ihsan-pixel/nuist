<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSekolahAdminSubdomainAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return redirect('/login');
        }

        $normalizedRole = preg_replace('/\s+/', '_', trim(strtolower((string) ($user->role ?? '')))) ?? '';
        $isActive = ! isset($user->is_active) || (bool) $user->is_active;
        $hasSchool = (int) ($user->madrasah_id ?? 0) > 0 && $user->sekolah;

        if ($normalizedRole === 'admin' && $isActive && $hasSchool) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Akses sekolah.nuist.id hanya untuk akun Admin sekolah yang aktif dan terhubung ke sekolah.',
            ], 403);
        }

        return redirect()->to(rtrim((string) config('app.url'), '/') . '/dashboard')
            ->with('error', 'Akses sekolah.nuist.id hanya untuk akun Admin sekolah yang aktif dan terhubung ke sekolah.');
    }
}
