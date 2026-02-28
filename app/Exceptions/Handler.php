<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Redirect friendly message when CSRF token mismatch occurs (419)
        $this->renderable(function (TokenMismatchException $e, $request) {
            // If request expects JSON, return JSON response
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Sesi berakhir (CSRF). Silakan muat ulang dan coba lagi.'], 419);
            }

            return redirect()->route('mobile.login')
                ->withErrors(['email' => 'Sesi telah berakhir. Silakan muat ulang halaman dan coba lagi.']);
        });
    }
}
