<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Redirect pengurus users to mobile pengurus dashboard
        if ($user->role === 'pengurus') {
            return redirect()->route('dashboard');
        }

        // Redirect mgmp users to mgmp dashboard
        if ($user->role === 'mgmp') {
            return redirect()->route('mgmp.dashboard');
        }

        // Redirect pemateri and fasilitator to talenta index
        if ($user->role === 'pemateri' || $user->role === 'fasilitator') {
            return redirect()->route('talenta.dashboard');
        }

        // For other roles, use default redirect
        return redirect()->intended($this->redirectPath());
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Log the current user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

        /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        // Validasi input login (email/username dan password)
        $this->validateLogin($request);

        // Jika user terlalu sering gagal login
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Coba login
        if ($this->attemptLogin($request)) {
            // Regenerasi session untuk mencegah session fixation
            $request->session()->regenerate();

            // Tambahkan CSRF token baru agar tidak mismatch setelah logout-login cepat
            $request->session()->regenerateToken();

            // Hapus cache lama jika masih ada (pencegahan untuk PWA)
            if (app()->environment('production')) {
                header('Cache-Control: no-cache, no-store, must-revalidate');
                header('Pragma: no-cache');
                header('Expires: 0');
            }

            // Redirect normal
            return $this->sendLoginResponse($request);
        }

        // Jika gagal login
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }
    protected function redirectTo()
    {
        $user = Auth::user();

        if ($user->role === 'mgmp') {
            return '/mgmp/dashboard';
        }

        return '/dashboard';
    }


}
