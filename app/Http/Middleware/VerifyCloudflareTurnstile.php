<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class VerifyCloudflareTurnstile
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('services.turnstile.enabled')) {
            return $next($request);
        }

        $secret = trim((string) config('services.turnstile.secret_key'));
        if ($secret === '') {
            Log::critical('Cloudflare Turnstile is enabled but its secret key is missing.');

            return $this->reject($request, 'Verifikasi keamanan belum dikonfigurasi. Hubungi administrator.');
        }

        $token = trim((string) $request->input('cf-turnstile-response', ''));
        if ($token === '' || strlen($token) > 2048) {
            return $this->reject($request);
        }

        try {
            $result = Http::asForm()
                ->connectTimeout(5)
                ->timeout(10)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ])
                ->throw()
                ->json();
        } catch (Throwable $exception) {
            Log::warning('Cloudflare Turnstile verification request failed.', [
                'message' => $exception->getMessage(),
                'ip' => $request->ip(),
            ]);

            return $this->reject($request, 'Layanan verifikasi keamanan sedang tidak tersedia. Silakan coba kembali.');
        }

        $hostname = trim((string) ($result['hostname'] ?? ''));
        $allowedHostnames = config('services.turnstile.allowed_hostnames', []);
        $hostnameAllowed = $allowedHostnames === [] || in_array($hostname, $allowedHostnames, true);

        if (($result['success'] ?? false) !== true
            || ($result['action'] ?? null) !== 'login'
            || ! $hostnameAllowed) {
            Log::notice('Cloudflare Turnstile rejected a login attempt.', [
                'error_codes' => $result['error-codes'] ?? [],
                'hostname' => $hostname,
                'ip' => $request->ip(),
            ]);

            return $this->reject($request);
        }

        return $next($request);
    }

    private function reject(Request $request, string $message = 'Verifikasi keamanan gagal. Silakan centang ulang dan coba kembali.'): Response
    {
        return redirect()->back()
            ->withErrors(['turnstile' => $message])
            ->withInput($request->only(['email', 'remember']));
    }
}
