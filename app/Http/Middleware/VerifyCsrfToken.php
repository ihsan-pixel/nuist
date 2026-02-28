<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'midtrans/callback',
        'uppm/pembayaran/success',
        'uppm/pembayaran/add-proses',
        // Mobile webview POST login often doesn't carry CSRF token from native webview
        // Exclude mobile login POST so Capacitor webviews can authenticate via form.
        'mobile/login',
    ];
}
