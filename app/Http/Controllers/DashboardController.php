<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $normalizedRole = $this->normalizeRole($user->role);

        if ($normalizedRole === 'admin_spp') {
            return redirect()->route('spp-siswa.dashboard');
        }

        // Hitung data presensi bulan ini untuk user yang login
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        // Cari tanggal pertama user melakukan presensi
        $firstPresensiDate = \App\Models\Presensi::where('user_id', $user->id)
            ->orderBy('tanggal', 'asc')
            ->value('tanggal');

        if ($firstPresensiDate) {
            $startDate = \Carbon\Carbon::parse($firstPresensiDate);
        } else {
            // Jika belum ada presensi, gunakan tanggal pertama user dibuat
            $startDate = \Carbon\Carbon::parse($user->created_at);
        }

        // Hitung hari kerja mulai dari tanggal pertama presensi sampai hari ini
        $today = now();

        // Hitung hari kerja dari 1 Juli tahun berjalan sampai 30 Juni tahun berikutnya
        $year = $today->month >= 7 ? $today->year : $today->year - 1;
        $startFiscalYear = \Carbon\Carbon::create($year, 7, 1);
        $endFiscalYear = \Carbon\Carbon::create($year + 1, 6, 30);

        $workingDays = $this->calculateWorkingDaysInMonth($startFiscalYear, $endFiscalYear);

        $presensiCounts = \App\Models\Presensi::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startDate, $today])
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $hadir = $presensiCounts['hadir'] ?? 0;
        $izin = $presensiCounts['izin'] ?? 0;
        $sakit = $presensiCounts['sakit'] ?? 0;
        $alpha = $presensiCounts['alpha'] ?? 0;

        // Gunakan jumlah data presensi sebagai dasar perhitungan persentase
        $totalBasis = array_sum($presensiCounts);

        $kehadiranPercent = $totalBasis > 0 ? round(($hadir / $totalBasis) * 100, 2) : 0;
        $izinSakitPercent = $totalBasis > 0 ? round((($izin + $sakit) / $totalBasis) * 100, 2) : 0;

        // Pastikan alpha dihitung dari total presensi (hadir + izin + sakit + alpha)
        $alphaCount = $alpha;
        if ($alphaCount < 0) {
            $alphaCount = 0;
        }
        $alphaPercent = $totalBasis > 0 ? round(($alphaCount / $totalBasis) * 100, 2) : 0;

        $attendanceData = [
            'kehadiran' => $kehadiranPercent,
            'izin_sakit' => $izinSakitPercent,
            'alpha' => $alphaCount > 0 ? round(($alphaCount / $totalBasis) * 100, 2) : 0,
            'total_hari_kerja' => $workingDays,
            'total_presensi' => $totalBasis,
        ];

        // Statistics untuk admin
        $adminStats = null;
        $madrasahData = null;
        $schoolPrincipal = null;
        if ($user->role === 'admin') {
            $adminStats = $this->getAdminStatistics($user->madrasah_id);
            $madrasahData = $this->getMadrasahData($user->madrasah_id);
            $schoolPrincipal = $this->getSchoolPrincipal($user->madrasah_id);
        }

        // Statistics untuk super_admin dan pengurus
        $superAdminStats = null;
        $foundationData = null;
        $todayAttendance = null;
        if (in_array($user->role, ['super_admin', 'pengurus', 'admin_yayasan'])) {
            $superAdminStats = $this->getSuperAdminStatistics();
            $foundationData = $this->getFoundationData();
            $todayAttendance = $this->getTodayAttendanceSummary();
        }

        if ($user->role === 'tenaga_pendidik') {
            // Redirect tenaga pendidik to mobile interface
            return redirect()->route('mobile.dashboard');
        }

        // Untuk role lain, tampilkan dashboard default tanpa data users
        return view('dashboard.index', [
            'showUsers' => false,
            'attendanceData' => $attendanceData,
            'adminStats' => $adminStats,
            'madrasahData' => $madrasahData,
            'schoolPrincipal' => $schoolPrincipal,
            'superAdminStats' => $superAdminStats,
            'foundationData' => $foundationData,
            'todayAttendance' => $todayAttendance,
        ]);
    }

    public function todayAttendanceSummary()
    {
        abort_unless(in_array(Auth::user()->role, ['super_admin', 'pengurus', 'admin_yayasan']), 403);

        return response()->json($this->getTodayAttendanceSummary());
    }

    private function getTodayAttendanceSummary(): array
    {
        $totalTeachers = User::where('role', 'tenaga_pendidik')
            ->whereNotNull('madrasah_id')
            ->count();

        $latestAttendance = \App\Models\Presensi::whereDate('tanggal', now()->toDateString())
            ->orderByDesc('id')
            ->get(['user_id', 'status'])
            ->unique('user_id');

        $present = $latestAttendance->whereIn('status', ['hadir', 'terlambat'])->count();
        $permission = $latestAttendance->whereIn('status', ['izin', 'sakit'])->count();
        $absent = max($totalTeachers - $present - $permission, 0);

        $teachersBySchool = User::where('role', 'tenaga_pendidik')
            ->whereNotNull('madrasah_id')
            ->selectRaw('madrasah_id, COUNT(*) as total')
            ->groupBy('madrasah_id')
            ->pluck('total', 'madrasah_id');

        $presentBySchool = \App\Models\Presensi::whereDate('tanggal', now()->toDateString())
            ->whereIn('status', ['hadir', 'terlambat'])
            ->whereNotNull('madrasah_id')
            ->selectRaw('madrasah_id, COUNT(DISTINCT user_id) as total')
            ->groupBy('madrasah_id')
            ->pluck('total', 'madrasah_id');

        $schoolNames = \App\Models\Madrasah::whereIn('id', $teachersBySchool->keys())
            ->pluck('name', 'id');

        $topSchools = $teachersBySchool
            ->map(function ($schoolTotal, $schoolId) use ($presentBySchool, $schoolNames) {
                $schoolPresent = (int) ($presentBySchool[$schoolId] ?? 0);

                return [
                    'id' => (int) $schoolId,
                    'name' => $schoolNames[$schoolId] ?? 'Sekolah Tidak Diketahui',
                    'present' => $schoolPresent,
                    'total' => (int) $schoolTotal,
                    'percentage' => $schoolTotal > 0 ? round($schoolPresent / $schoolTotal * 100, 1) : 0,
                ];
            })
            ->sortByDesc('percentage')
            ->take(5)
            ->values();

        return [
            'date' => now()->format('d-m-Y'),
            'total' => $totalTeachers,
            'present' => $present,
            'permission' => $permission,
            'absent' => $absent,
            'present_percentage' => $totalTeachers > 0 ? round($present / $totalTeachers * 100, 1) : 0,
            'permission_percentage' => $totalTeachers > 0 ? round($permission / $totalTeachers * 100, 1) : 0,
            'absent_percentage' => $totalTeachers > 0 ? round($absent / $totalTeachers * 100, 1) : 0,
            'updated_at' => now()->format('H:i:s'),
            'top_schools' => $topSchools,
        ];
    }

    /**
     * Calculate working days in a month (Monday-Saturday, excluding holidays)
     */
    private function calculateWorkingDaysInMonth($startDate, $endDate)
    {
        $workingDays = 0;
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            // Skip Sundays (day 0 in Carbon)
            if ($currentDate->dayOfWeek !== \Carbon\Carbon::SUNDAY) {
                // Check if it's not a holiday
                if (!\App\Models\Holiday::isHoliday($currentDate)) {
                    $workingDays++;
                }
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }

    /**
     * Get statistics for admin dashboard
     */
    private function getAdminStatistics($madrasahId)
    {
        // Hitung total tenaga pendidik/guru berdasarkan madrasah_id
        $totalTeachers = User::where('madrasah_id', $madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->count();

        // Hitung berdasarkan status kepegawaian
        $statusStats = User::where('madrasah_id', $madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->with('statusKepegawaian')
            ->selectRaw('status_kepegawaian_id, COUNT(*) as count')
            ->groupBy('status_kepegawaian_id')
            ->get()
            ->map(function ($item) {
                return [
                    'status_id' => $item->status_kepegawaian_id,
                    'status_name' => $item->statusKepegawaian ? $item->statusKepegawaian->name : 'Tidak Diketahui',
                    'count' => $item->count
                ];
            });

        // Hitung ringkasan data
        $summary = [
            'total_teachers' => $totalTeachers,
            'total_by_status' => $statusStats,
            'madrasah_id' => $madrasahId,
        ];

        return $summary;
    }

    /**
     * Get madrasah data for map and address display
     */
    private function getMadrasahData($madrasahId)
    {
        return \App\Models\Madrasah::where('id', $madrasahId)
            ->select('id', 'name', 'alamat', 'latitude', 'longitude', 'map_link')
            ->first();
    }

    /**
     * Get school principal data for the madrasah
     */
    private function getSchoolPrincipal($madrasahId)
    {
        return User::where('madrasah_id', $madrasahId)
            ->where('ketugasan', 'kepala madrasah/sekolah')
            ->select('id', 'name', 'avatar', 'nuist_id')
            ->first();
    }

    /**
     * Get statistics for super_admin dashboard
     */
    private function getSuperAdminStatistics()
    {
        // Total madrasah/sekolah
        $totalMadrasah = \App\Models\Madrasah::count();

        // Total guru dan pegawai (tenaga_pendidik only)
        $totalTeachers = User::where('role', 'tenaga_pendidik')
            ->whereNotNull('madrasah_id')
            ->count();

        // Total by employment status
        $statusStats = User::where('role', 'tenaga_pendidik')
            ->whereNotNull('madrasah_id')
            ->with('statusKepegawaian')
            ->selectRaw('status_kepegawaian_id, COUNT(*) as count')
            ->groupBy('status_kepegawaian_id')
            ->get()
            ->map(function ($item) {
                return [
                    'status_id' => $item->status_kepegawaian_id,
                    'status_name' => $item->statusKepegawaian ? $item->statusKepegawaian->name : 'Tidak Diketahui',
                    'count' => $item->count
                ];
            });

        // Total users by role
        $totalAdmin = User::where('role', 'admin')->count();
        $totalSuperAdmin = User::where('role', 'super_admin')->count();
        $totalPengurus = User::where('role', 'pengurus')->count();
        $totalSchoolPrincipals = User::where('ketugasan', 'kepala madrasah/sekolah')->count();

        return [
            'total_madrasah' => $totalMadrasah,
            'total_teachers' => $totalTeachers,
            'total_by_status' => $statusStats,
            'total_admin' => $totalAdmin,
            'total_super_admin' => $totalSuperAdmin,
            'total_pengurus' => $totalPengurus,
            'total_school_principals' => $totalSchoolPrincipals,
        ];
    }

    /**
     * Get foundation data for map and address display
     */
    private function getFoundationData()
    {
        // Get the first yayasan as foundation data
        return \App\Models\Yayasan::select('id', 'name', 'alamat', 'latitude', 'longitude', 'map_link')
            ->first();
    }

    private function normalizeRole(?string $role): string
    {
        return preg_replace('/\s+/', '_', trim(strtolower((string) $role))) ?? '';
    }
}
