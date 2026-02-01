<?php

namespace App\Http\Controllers\Mobile\Pengurus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Madrasah;
use App\Models\DataTenagaPendidik;
use App\Models\AppSetting;

class DashboardController extends \App\Http\Controllers\Controller
{
    // Dashboard for pengurus
    public function index(Request $request)
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
        $totalMadrasah = Madrasah::count();
        $totalTenagaPendidik = DataTenagaPendidik::count();
        $totalPengurus = \App\Models\User::where('role', 'pengurus')->count();

        // Get recent activities or notifications
        $recentActivities = []; // You can populate this with relevant data

        return view('mobile.pengurus.dashboard', compact(
            'bannerImage', 'showBanner', 'currentMonth', 'currentYear',
            'totalMadrasah', 'totalTenagaPendidik', 'totalPengurus', 'recentActivities'
        ));
    }
}
