<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AppSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        abort_unless($user && $user->role === 'karyawan', 403);

        $today = Carbon::today('Asia/Jakarta');
        $todayAttendance = $user->presensis()->whereDate('tanggal', $today)->latest('id')->first();
        $attendanceHistory = $user->presensis()
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $settings = AppSetting::getSettings();
        $currentIps = collect(array_merge([$request->ip()], $request->ips()))->filter()->unique();
        $allowedIps = collect(preg_split('/[\r\n,]+/', (string) $settings->employee_wifi_allowed_ips))->map(fn ($ip) => trim($ip))->filter();
        $wifiAllowed = $settings->employee_attendance_enabled && $currentIps->intersect($allowedIps)->isNotEmpty();
        $now = Carbon::now('Asia/Jakarta')->format('H:i');
        $hasEntry = filled($todayAttendance?->waktu_masuk);
        $hasExit = filled($todayAttendance?->waktu_keluar);
        $attendanceAction = $hasExit
            ? ['label' => 'Presensi Hari Ini Selesai', 'mode' => null, 'disabled' => true, 'hint' => 'Presensi masuk dan pulang sudah tercatat.']
            : (! $hasEntry
                ? ['label' => 'Presensi Masuk', 'mode' => 'masuk', 'disabled' => false, 'hint' => 'Presensi masuk tersedia sesuai jam yang diatur.']
                : ($now >= $settings->employee_exit_start
                    ? ['label' => 'Presensi Keluar', 'mode' => 'keluar', 'disabled' => false, 'hint' => 'Jam presensi keluar sudah dimulai.']
                    : ['label' => 'Presensi Masuk Tercatat', 'mode' => null, 'disabled' => true, 'hint' => 'Presensi keluar tersedia mulai pukul '.Carbon::parse($settings->employee_exit_start)->format('H:i').'.']));

        return view('mobile.karyawan.dashboard', compact('todayAttendance', 'attendanceHistory', 'attendanceAction', 'wifiAllowed'));
    }

    public function index(Request $request)
    {
        $query = User::query()->where('role', 'karyawan');

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $employees = $query->latest()->paginate(15)->withQueryString();

        return view('admin.karyawan.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.karyawan.create');
    }

    public function update(Request $request, User $user)
    {
        abort_unless($user->role === 'karyawan', 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('users', 'nip')->where(fn ($query) => $query->where('role', 'karyawan'))->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'jabatan' => ['nullable', 'string', 'max:150'],
            'instansi_asal' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($data, $user): void {
            $attributes = [
                'name' => trim($data['name']),
                'nip' => filled($data['nip'] ?? null) ? trim($data['nip']) : null,
                'email' => strtolower(trim($data['email'])),
                'no_hp' => $data['no_hp'] ?? null,
                'jabatan' => $data['jabatan'] ?? null,
                'instansi_asal' => $data['instansi_asal'] ?? null,
            ];

            if (filled($data['password'] ?? null)) {
                $attributes['password'] = Hash::make($data['password']);
            }

            $user->update($attributes);
        });

        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('users', 'nip')->where(fn ($query) => $query->where('role', 'karyawan'))],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'jabatan' => ['nullable', 'string', 'max:150'],
            'instansi_asal' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($data): void {
            User::create([
                'name' => trim($data['name']),
                'nip' => filled($data['nip'] ?? null) ? trim($data['nip']) : null,
                'email' => strtolower(trim($data['email'])),
                'no_hp' => $data['no_hp'] ?? null,
                'jabatan' => $data['jabatan'] ?? null,
                'instansi_asal' => $data['instansi_asal'] ?? null,
                'role' => 'karyawan',
                'password' => Hash::make($data['password']),
                'is_active' => true,
            ]);
        });

        return redirect()->route('admin.karyawan.index')->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function destroy(User $user)
    {
        abort_unless($user->role === 'karyawan', 404);
        $user->update(['is_active' => false]);

        return back()->with('success', 'Akun karyawan berhasil dinonaktifkan.');
    }
}
