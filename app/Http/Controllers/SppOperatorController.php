<?php

namespace App\Http\Controllers;

use App\Mail\SppOperatorApprovedMail;
use App\Mail\SppOperatorRegistrationSubmittedMail;
use App\Models\Madrasah;
use App\Models\SppOperatorRegistration;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SppOperatorController extends Controller
{
    public function registerForm(): View
    {
        $matchedMadrasah = null;
        $oldScod = trim((string) old('scod'));

        if ($oldScod !== '') {
            $matchedMadrasah = Madrasah::query()
                ->where('scod', $oldScod)
                ->first(['id', 'name', 'kabupaten', 'scod']);
        }

        return view('spp-operator.public-register', compact('matchedMadrasah'));
    }

    public function lookupSchool(Request $request)
    {
        $scod = trim((string) $request->query('scod', ''));

        if ($scod === '') {
            return response()->json([
                'found' => false,
                'message' => 'SCOD wajib diisi.',
            ], 422);
        }

        $madrasah = Madrasah::query()
            ->where('scod', $scod)
            ->first(['id', 'name', 'kabupaten', 'scod']);

        if (!$madrasah) {
            return response()->json([
                'found' => false,
                'message' => 'SCOD tidak ditemukan.',
            ], 404);
        }

        if ($this->madrasahAlreadyHasOperator($madrasah->id)) {
            return response()->json([
                'found' => true,
                'available' => false,
                'madrasah' => $madrasah,
                'message' => 'Sekolah ini sudah pernah mengajukan atau sudah memiliki akun Operator SPP.',
            ]);
        }

        return response()->json([
            'found' => true,
            'available' => true,
            'madrasah' => $madrasah,
        ]);
    }

    public function registerStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'scod' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                'unique:spp_operator_registrations,email',
            ],
            'no_hp' => ['nullable', 'string', 'max:32'],
            'jabatan' => ['required', 'string', 'max:100'],
        ], [
            'scod.required' => 'SCOD sekolah wajib diisi.',
            'email.unique' => 'Email sudah terdaftar di sistem atau sudah pernah dipakai untuk pendaftaran Operator SPP. Gunakan email lain yang khusus untuk akun Operator SPP.',
        ]);

        $madrasah = Madrasah::query()
            ->where('scod', trim((string) $validated['scod']))
            ->first();

        if (!$madrasah) {
            return back()
                ->withErrors([
                    'scod' => 'SCOD sekolah tidak ditemukan.',
                ])
                ->withInput();
        }

        if ($this->madrasahAlreadyHasOperator($madrasah->id)) {
            return back()
                ->withErrors([
                    'scod' => 'Sekolah ini sudah pernah mengajukan atau sudah memiliki akun Operator SPP.',
                ])
                ->withInput();
        }

        $registration = SppOperatorRegistration::create([
            'madrasah_id' => $madrasah->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?: null,
            'jabatan' => $validated['jabatan'],
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        try {
            Mail::to($registration->email)->send(new SppOperatorRegistrationSubmittedMail($registration));
        } catch (\Throwable $exception) {
            \Log::warning('Gagal mengirim email pendaftaran operator SPP: ' . $exception->getMessage());
        }

        return redirect()
            ->route('spp-operator.register')
            ->with('success', 'Pendaftaran Operator SPP berhasil dikirim. Silakan tunggu proses review dari Super Admin.');
    }

    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();

        $registrationQuery = SppOperatorRegistration::query()
            ->with(['madrasah', 'approvedUser', 'reviewer'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 WHEN status = 'approved' THEN 1 ELSE 2 END")
            ->orderByDesc('submitted_at');

        if (in_array($status, ['pending', 'approved', 'rejected'], true)) {
            $registrationQuery->where('status', $status);
        }

        $registrations = $registrationQuery->paginate(12)->withQueryString();

        $approvedOperators = User::query()
            ->with(['madrasah', 'approvedSppOperatorRegistration'])
            ->where('role', 'admin_spp')
            ->orderBy('name')
            ->get();

        $stats = [
            'pending' => SppOperatorRegistration::where('status', 'pending')->count(),
            'approved' => SppOperatorRegistration::where('status', 'approved')->count(),
            'rejected' => SppOperatorRegistration::where('status', 'rejected')->count(),
            'active_accounts' => User::where('role', 'admin_spp')->where('is_active', true)->count(),
        ];

        return view('masterdata.operator-spp.index', compact('registrations', 'approvedOperators', 'stats', 'status'));
    }

    public function approve(SppOperatorRegistration $registration): RedirectResponse
    {
        if ($registration->status === 'approved') {
            return back()->with('error', 'Pendaftaran ini sudah disetujui sebelumnya.');
        }

        if ($this->madrasahHasApprovedOperatorUser($registration->madrasah_id)) {
            return back()->with('error', 'Sekolah ini sudah memiliki akun Operator SPP aktif di sistem.');
        }

        if (User::where('email', $registration->email)->exists()) {
            return back()->with('error', 'Email pendaftar sudah digunakan oleh akun lain di sistem.');
        }

        $plainPassword = $this->generateRandomPassword();

        $user = User::create([
            'name' => $registration->name,
            'email' => $registration->email,
            'password' => Hash::make($plainPassword),
            'role' => 'admin_spp',
            'madrasah_id' => $registration->madrasah_id,
            'no_hp' => $registration->no_hp,
            'jabatan' => $registration->jabatan,
            'ketugasan' => $registration->jabatan,
            'password_changed' => false,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $registration->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'approved_user_id' => $user->id,
            'reviewed_at' => now(),
            'approved_at' => now(),
            'review_notes' => null,
        ]);

        try {
            Mail::to($user->email)->send(new SppOperatorApprovedMail($user, $registration->fresh('madrasah'), $plainPassword));
        } catch (\Throwable $exception) {
            \Log::warning('Gagal mengirim email approval operator SPP: ' . $exception->getMessage());
        }

        return back()->with('success', 'Pendaftaran Operator SPP berhasil disetujui dan akun login telah dibuat.');
    }

    public function reject(Request $request, SppOperatorRegistration $registration): RedirectResponse
    {
        if ($registration->status === 'approved') {
            return back()->with('error', 'Pendaftaran yang sudah disetujui tidak bisa ditolak.');
        }

        $validated = $request->validate([
            'review_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $registration->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $validated['review_notes'] ?? null,
        ]);

        return back()->with('success', 'Pendaftaran Operator SPP berhasil ditolak.');
    }

    public function updateAccount(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'admin_spp', 404);

        $registration = $user->approvedSppOperatorRegistration;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'no_hp' => ['nullable', 'string', 'max:32'],
            'jabatan' => ['required', 'string', 'max:100'],
        ]);

        $emailUsedByOtherRegistration = SppOperatorRegistration::query()
            ->when($registration, function ($query) use ($registration) {
                $query->where('id', '!=', $registration->id);
            })
            ->where('email', $validated['email'])
            ->exists();

        if ($emailUsedByOtherRegistration) {
            return back()->with('error', 'Email sudah digunakan oleh pendaftaran Operator SPP lain.');
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?: null,
            'jabatan' => $validated['jabatan'],
            'ketugasan' => $validated['jabatan'],
        ]);

        if ($registration) {
            $registration->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'no_hp' => $validated['no_hp'] ?: null,
                'jabatan' => $validated['jabatan'],
            ]);
        }

        return back()->with('success', 'Data akun Operator SPP berhasil diperbarui.');
    }

    public function updateAccountStatus(Request $request, User $user): RedirectResponse
    {
        abort_unless($user->role === 'admin_spp', 404);

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
        ]);

        $user->update([
            'is_active' => (bool) $validated['is_active'],
        ]);

        return back()->with('success', 'Status akun Operator SPP berhasil diperbarui.');
    }

    private function madrasahAlreadyHasOperator(int $madrasahId): bool
    {
        return $this->madrasahHasApprovedOperatorUser($madrasahId)
            || SppOperatorRegistration::where('madrasah_id', $madrasahId)->exists();
    }

    private function madrasahHasApprovedOperatorUser(int $madrasahId): bool
    {
        return User::where('role', 'admin_spp')
            ->where('madrasah_id', $madrasahId)
            ->exists();
    }

    private function generateRandomPassword(int $length = 10): string
    {
        $pool = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
        $password = '';

        for ($index = 0; $index < $length; $index++) {
            $password .= $pool[random_int(0, strlen($pool) - 1)];
        }

        return $password . Str::upper(Str::random(2));
    }
}
