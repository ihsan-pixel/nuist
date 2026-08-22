<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

    private function normalizeRole(?string $role): string
    {
        return preg_replace('/\s+/', '_', trim(strtolower((string) $role))) ?? '';
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (isset($user->is_active) && !$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda saat ini dinonaktifkan. Silakan hubungi Super Admin.',
            ]);
        }

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

    public function showSppOperatorLoginForm(): View
    {
        return view('auth.login-operator-spp');
    }

    public function loginSppOperator(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password Operator SPP tidak sesuai.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        $user = Auth::user();
        $normalizedRole = $this->normalizeRole($user->role ?? '');

        if ($normalizedRole !== 'admin_spp') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Akun ini bukan akun Operator SPP.'])
                ->withInput($request->only('email'));
        }

        if (isset($user->is_active) && !$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Akun Operator SPP Anda saat ini dinonaktifkan.'])
                ->withInput($request->only('email'));
        }

        return redirect()->route('spp-siswa.dashboard');
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
        // If the user logged out from a mobile page, send them to the mobile login
        $referer = $request->headers->get('referer', '');
        if ($referer && strpos($referer, '/mobile') !== false) {
            return redirect()->route('mobile.login');
        }

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

        if ($this->normalizeRole($user->role ?? '') === 'admin_spp') {
            return '/spp-siswa/dashboard';
        }

        if ($user->role === 'mgmp') {
            return '/mgmp/dashboard';
        }

        return '/dashboard';
    }


}
