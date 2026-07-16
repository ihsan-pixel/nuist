<?php

namespace App\Http\Controllers\Admin;

use App\Models\AttendanceKioskLog;
use App\Http\Controllers\Controller;
use App\Models\Madrasah;
use App\Models\RegisteredAttendanceDevice;
use App\Models\User;
use App\Services\AttendanceDeviceRegistryService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AttendanceKioskDeviceController extends Controller
{
    public function __construct(
        private AttendanceDeviceRegistryService $attendanceDeviceRegistryService,
    ) {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            $allowed = in_array($user->role, ['super_admin', 'admin', 'pengurus'], true);

            if (!$allowed && $user->role === 'tenaga_pendidik' && $user->ketugasan === 'kepala madrasah/sekolah') {
                $allowed = true;
            }

            if (!$allowed) {
                abort(403, 'Unauthorized');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $schools = $this->accessibleSchoolsQuery($user)->get(['id', 'name', 'kabupaten', 'scod']);
        $selectedMadrasahId = $this->resolveSelectedMadrasahId($request, $user, $schools);

        $devices = RegisteredAttendanceDevice::query()
            ->with(['madrasah:id,name,kabupaten,scod', 'registeredBy:id,name'])
            ->whereIn('madrasah_id', $schools->pluck('id'))
            ->when($selectedMadrasahId, fn (Builder $query) => $query->where('madrasah_id', $selectedMadrasahId))
            ->orderByDesc('is_active')
            ->latest()
            ->get();

        $logs = AttendanceKioskLog::query()
            ->with([
                'device:id,name,madrasah_id',
                'madrasah:id,name',
                'operator:id,name',
                'targetUser:id,name',
            ])
            ->whereIn('madrasah_id', $schools->pluck('id'))
            ->when($selectedMadrasahId, fn (Builder $query) => $query->where('madrasah_id', $selectedMadrasahId))
            ->latest()
            ->limit(25)
            ->get();

        $stats = [
            'total_devices' => $devices->count(),
            'active_devices' => $devices->where('is_active', true)->count(),
            'submit_success_today' => $logs->where('action', 'kiosk_submit')->where('status', 'success')->filter(
                fn ($log) => optional($log->created_at)->isToday()
            )->count(),
            'access_denied_today' => $logs->where('action', 'kiosk_access')->where('status', 'denied')->filter(
                fn ($log) => optional($log->created_at)->isToday()
            )->count(),
        ];

        return view('admin.attendance-kiosk-devices', [
            'schools' => $schools,
            'devices' => $devices,
            'logs' => $logs,
            'stats' => $stats,
            'selectedMadrasahId' => $selectedMadrasahId,
            'canChooseMadrasah' => $this->canChooseMadrasah($user),
            'currentIp' => $request->ip(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $schools = $this->accessibleSchoolsQuery($user)->get(['id', 'name']);
        $defaultMadrasahId = $this->resolveSelectedMadrasahId($request, $user, $schools);

        $validated = $request->validate([
            'madrasah_id' => [
                'required',
                'integer',
                Rule::in($schools->pluck('id')->all()),
            ],
            'name' => ['required', 'string', 'max:255'],
            'browser_fingerprint' => ['nullable', 'string', 'max:500'],
            'allowed_ip_addresses' => ['nullable', 'string', 'max:1000'],
        ]);

        $madrasah = $schools->firstWhere('id', (int) $validated['madrasah_id']);
        abort_if(!$madrasah, 403, 'Madrasah tidak diizinkan.');

        $plainToken = $this->attendanceDeviceRegistryService->issuePlainToken();
        $allowedIps = $this->parseAllowedIpAddresses(
            $validated['allowed_ip_addresses'] ?? '',
            $request->ip()
        );

        $device = $this->attendanceDeviceRegistryService->registerSchoolKiosk(
            Madrasah::findOrFail($madrasah->id),
            $user,
            $validated['name'],
            $plainToken,
            $validated['browser_fingerprint'] ?? null,
            $allowedIps,
            [
                'last_seen_at' => now(),
                'last_ip_address' => $request->ip(),
                'last_user_agent' => $request->userAgent(),
            ],
        );

        $redirect = redirect()
            ->route('presensi_admin.kiosk_devices', ['madrasah_id' => $defaultMadrasahId ?: $device->madrasah_id])
            ->with('success', "Komputer presensi \"{$device->name}\" berhasil didaftarkan.")
            ->with('kiosk_registration', [
                'device_name' => $device->name,
                'madrasah_name' => $device->madrasah->name,
                'plain_token' => $plainToken,
                'fingerprint' => $validated['browser_fingerprint'] ?? null,
            ]);

        $redirect->withCookie(cookie(
            'nuist_kiosk_token',
            $plainToken,
            60 * 24 * 365,
            '/',
            null,
            false,
            false,
            false,
            'Lax'
        ));

        if (!empty($validated['browser_fingerprint'])) {
            $redirect->withCookie(cookie(
                'nuist_kiosk_fingerprint',
                $validated['browser_fingerprint'],
                60 * 24 * 365,
                '/',
                null,
                false,
                false,
                false,
                'Lax'
            ));
        }

        return $redirect;
    }

    public function toggle(RegisteredAttendanceDevice $device): RedirectResponse
    {
        $this->authorizeDeviceSchool(Auth::user(), $device);

        $device->is_active = !$device->is_active;
        $device->save();

        $message = $device->is_active
            ? "Komputer presensi \"{$device->name}\" berhasil diaktifkan."
            : "Komputer presensi \"{$device->name}\" berhasil dinonaktifkan.";

        return redirect()
            ->route('presensi_admin.kiosk_devices', ['madrasah_id' => $device->madrasah_id])
            ->with('success', $message);
    }

    public function syncCurrentIp(Request $request, RegisteredAttendanceDevice $device): RedirectResponse
    {
        $this->authorizeDeviceSchool(Auth::user(), $device);

        $device = $this->attendanceDeviceRegistryService->updateAllowedIpAddresses($device, [$request->ip()]);
        $this->attendanceDeviceRegistryService->touchSeen($device, $request->ip(), $request->userAgent());

        return redirect()
            ->route('presensi_admin.kiosk_devices', ['madrasah_id' => $device->madrasah_id])
            ->with('success', "IP aktif untuk \"{$device->name}\" berhasil disinkronkan ke {$request->ip()}.");
    }

    public function syncCurrentFingerprint(Request $request, RegisteredAttendanceDevice $device): RedirectResponse
    {
        $this->authorizeDeviceSchool(Auth::user(), $device);

        $validated = $request->validate([
            'browser_fingerprint' => ['required', 'string', 'max:500'],
        ]);

        $device = $this->attendanceDeviceRegistryService->updateFingerprint($device, $validated['browser_fingerprint']);
        $this->attendanceDeviceRegistryService->touchSeen($device, $request->ip(), $request->userAgent());

        $redirect = redirect()->back()->with(
            'success',
            "Fingerprint browser untuk \"{$device->name}\" berhasil disinkronkan."
        );

        $redirect->withCookie(cookie(
            'nuist_kiosk_fingerprint',
            $validated['browser_fingerprint'],
            60 * 24 * 365,
            '/',
            null,
            false,
            false,
            false,
            'Lax'
        ));

        return $redirect;
    }

    private function accessibleSchoolsQuery(User $user): Builder
    {
        $query = Madrasah::query()->orderBy('kabupaten')->orderBy('name');

        if (in_array($user->role, ['super_admin', 'pengurus'], true)) {
            return $query;
        }

        return $query->where('id', $user->madrasah_id);
    }

    private function resolveSelectedMadrasahId(Request $request, User $user, $schools): ?int
    {
        if (!$this->canChooseMadrasah($user)) {
            return $schools->first()?->id;
        }

        $requested = (int) $request->input('madrasah_id');

        if ($requested && $schools->pluck('id')->contains($requested)) {
            return $requested;
        }

        return $schools->first()?->id;
    }

    private function canChooseMadrasah(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'pengurus'], true);
    }

    private function authorizeDeviceSchool(User $user, RegisteredAttendanceDevice $device): void
    {
        if (in_array($user->role, ['super_admin', 'pengurus'], true)) {
            return;
        }

        abort_if((int) $user->madrasah_id !== (int) $device->madrasah_id, 403, 'Unauthorized');
    }

    private function parseAllowedIpAddresses(?string $raw, ?string $fallbackIp): array
    {
        $ips = preg_split('/[\s,]+/', (string) $raw, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        if ($ips === [] && $fallbackIp) {
            $ips = [$fallbackIp];
        }

        return $this->attendanceDeviceRegistryService->normalizeIpAddresses($ips);
    }
}
