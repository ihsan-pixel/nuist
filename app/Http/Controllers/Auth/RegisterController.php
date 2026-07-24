<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\PendingRegistration;
use App\Mail\RegistrationPendingNotification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $pendingRegistration = $this->create($request->all());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registration submitted successfully. Please wait for admin approval.',
                'data' => $pendingRegistration
            ]);
        }

        return redirect($this->redirectPath());
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $madrasahs = \App\Models\Madrasah::orderBy('scod')->get();
        return view('auth.register', compact('madrasahs'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $role = $data['role'] ?? null;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'unique:pending_registrations,email'],
            'role' => ['required', 'in:pengurus,tenaga_pendidik'],
        ];

        // Bidirectional validation: role determines required fields and vice versa
        if ($role === 'pengurus') {
            $rules['jabatan'] = ['required', 'string', 'max:255'];
            // If role is pengurus, asal_sekolah should not be provided
            if (!empty($data['asal_sekolah'])) {
                $rules['asal_sekolah'] = ['prohibited'];
            }
        } elseif ($role === 'tenaga_pendidik') {
            $rules['asal_sekolah'] = ['required', 'exists:madrasahs,id'];
            // If role is tenaga_pendidik, jabatan should not be provided
            if (!empty($data['jabatan'])) {
                $rules['jabatan'] = ['prohibited'];
            }
        }

        // Additional validation: if jabatan is provided, role must be pengurus
        if (!empty($data['jabatan']) && $role !== 'pengurus') {
            $rules['role'] = ['required', 'in:pengurus'];
        }

        // Additional validation: if asal_sekolah is provided, role must be tenaga_pendidik
        if (!empty($data['asal_sekolah']) && $role !== 'tenaga_pendidik') {
            $rules['role'] = ['required', 'in:tenaga_pendidik'];
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $pendingRegistration = PendingRegistration::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make(Str::random(32)),
            'role' => $data['role'],
            'jabatan' => $data['jabatan'] ?? null,
            'asal_sekolah' => $data['asal_sekolah'] ?? null,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        try {
            Mail::to($data['email'])->send(new RegistrationPendingNotification($pendingRegistration));
        } catch (\Throwable $exception) {
            Log::error('Failed to send pending registration email.', [
                'pending_registration_id' => $pendingRegistration->id,
                'email' => $data['email'],
                'error' => $exception->getMessage(),
            ]);
        }

        return $pendingRegistration;
    }
}
