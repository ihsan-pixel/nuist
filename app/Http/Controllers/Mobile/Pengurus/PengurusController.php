<?php

namespace App\Http\Controllers\Mobile\Pengurus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Presensi;
use App\Models\User;
use App\Models\TeachingSchedule;
use App\Models\TeachingAttendance;
use App\Models\AppSetting;
use App\Models\Holiday;
use App\Models\Madrasah;
use App\Models\DataTenagaPendidik;
use App\Models\DataSekolah;
use App\Models\DevelopmentHistory;

class PengurusController extends \App\Http\Controllers\Controller
{
    // Dashboard for pengurus
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // simple role check
        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        // Get banner image from app settings
        $appSettings = AppSetting::getSettings();
        $bannerImage = $appSettings->banner_image_url;

        // Check if banner should be shown (only once per session)
        $showBanner = false;
        if ($bannerImage && !session('banner_shown')) {
            $showBanner = true;
            session(['banner_shown' => true]);
        }

        // Get current month and year
        $currentMonth = $request->get('month', Carbon::now()->month);
        $currentYear = $request->get('year', Carbon::now()->year);

        // Validate month and year
        $currentMonth = max(1, min(12, (int)$currentMonth));
        $currentYear = max(2020, min(2030, (int)$currentYear));

        // For pengurus, show general statistics
        // 1. Jumlah Sekolah - count from Madrasah table
        $jumlahSekolah = Madrasah::count();

        // 2. Jumlah Tenaga Pendidik - users with madrasah_id and role tenaga_pendidik
        $jumlahTenagaPendidik = User::whereNotNull('madrasah_id')
            ->where('role', 'tenaga_pendidik')
            ->count();

        // 3. Jumlah Siswa - get 1 record per madrasah_id with latest year, then sum jumlah_siswa column
        $latestDataPerSchool = DataSekolah::select('madrasah_id', 'jumlah_siswa', 'tahun')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('data_sekolah')
                    ->groupBy('madrasah_id');
            });

        $jumlahSiswa = $latestDataPerSchool->sum('jumlah_siswa');

        // Legacy statistics (keep for compatibility)
        $totalMadrasah = $jumlahSekolah;
        $totalTenagaPendidik = DataTenagaPendidik::count();
        $totalPengurus = User::where('role', 'pengurus')->count();

        // Get tenaga pendidik count by status kepegawaian
        $tenagaPendidikByStatus = User::whereNotNull('madrasah_id')
            ->where('role', 'tenaga_pendidik')
            ->whereNotNull('status_kepegawaian_id')
            ->select('status_kepegawaian_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('status_kepegawaian_id')
            ->with('statusKepegawaian')
            ->get()
            ->map(function ($item) {
                return [
                    'status_name' => $item->statusKepegawaian ? $item->statusKepegawaian->name : 'Tidak Diketahui',
                    'count' => $item->count,
                ];
            });

        // Get recent activities or notifications
        $recentActivities = []; // You can populate this with relevant data

        return view('mobile.pengurus.dashboard', compact(
            'bannerImage', 'showBanner', 'currentMonth', 'currentYear',
            'jumlahSekolah', 'jumlahTenagaPendidik', 'jumlahSiswa',
            'totalMadrasah', 'totalTenagaPendidik', 'totalPengurus',
            'tenagaPendidikByStatus', 'recentActivities'
        ));
    }

    // Data Presensi Mengajar
    public function dataPresensiMengajar(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        // Get teaching attendance data
        $teachingAttendances = TeachingAttendance::with(['teachingSchedule', 'teachingSchedule.teacher'])
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        return view('mobile.pengurus.data-presensi-mengajar', compact('teachingAttendances'));
    }

    // Presensi Kehadiran
    public function presensiKehadiran(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        // Get presensi data
        $presensis = Presensi::with('user')
            ->orderBy('tanggal', 'desc')
            ->paginate(20);

        return view('mobile.pengurus.presensi-kehadiran', compact('presensis'));
    }

    // UPPM
    public function uppm(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        // UPPM related data - placeholder for now
        $uppmData = []; // Implement UPPM logic here

        return view('mobile.pengurus.uppm', compact('uppmData'));
    }

    // Data Sekolah
    public function dataSekolah(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        // Get madrasah data
        $madrasahs = Madrasah::paginate(20);

        return view('mobile.pengurus.data-sekolah', compact('madrasahs'));
    }

    // Riwayat Pengembangan
    public function riwayatPengembangan(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        // Get development history
        $developmentHistories = DevelopmentHistory::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('mobile.pengurus.riwayat-pengembangan', compact('developmentHistories'));
    }

    // Pengguna Aktif
    public function penggunaAktif(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        // Get active users - placeholder for now
        $activeUsers = User::where('last_login_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('last_login_at', 'desc')
            ->paginate(20);

        return view('mobile.pengurus.pengguna-aktif', compact('activeUsers'));
    }

    // Profile
    public function profile(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        return view('mobile.pengurus.profile', compact('user'));
    }
}
