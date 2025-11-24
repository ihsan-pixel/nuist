<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DevelopmentHistory;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AppSettingsController extends Controller
{
    /**
     * Display application settings page for super admin
     */
    public function index()
    {
        // Check if user is super_admin
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }

        // Get app settings from database (including version)
        $appSettings = AppSetting::getSettings();

        // Get latest development history for version info
        $latestHistory = DevelopmentHistory::orderBy('development_date', 'desc')->first();

        $settings = [
            'app_name' => $appSettings->app_name,
            'app_version' => $appSettings->app_version,
            'banner_image' => $appSettings->banner_image,
            'banner_image_url' => $appSettings->banner_image_url,
            'maintenance_mode' => $appSettings->maintenance_mode,
            'timezone' => $appSettings->timezone,
            'locale' => $appSettings->locale,
            'debug_mode' => $appSettings->debug_mode,
            'cache_enabled' => $appSettings->cache_enabled,
            'session_lifetime' => $appSettings->session_lifetime,
            'max_upload_size' => ini_get('upload_max_filesize'),
            'memory_limit' => ini_get('memory_limit'),
        ];

        return view('app-settings.index', compact('settings', 'latestHistory'));
    }

    /**
     * Update application settings
     */
    public function update(Request $request)
    {
        // Check if user is super_admin
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_version' => 'required|string|max:50',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'maintenance_mode' => 'boolean',
            'timezone' => 'required|string',
            'locale' => 'required|string',
            'debug_mode' => 'boolean',
            'cache_enabled' => 'boolean',
            'session_lifetime' => 'required|integer|min:1|max:525600',
        ]);

        // Get or create app settings
        $appSettings = AppSetting::getSettings();

        // Handle banner image upload
        $bannerImagePath = $appSettings->banner_image;
        if ($request->hasFile('banner_image')) {
            // Delete old banner if exists
            if ($bannerImagePath && Storage::disk('public')->exists($bannerImagePath)) {
                Storage::disk('public')->delete($bannerImagePath);
            }
            // Store new banner
            $bannerImagePath = $request->file('banner_image')->store('banners', 'public');
        }

        // Update settings
        $appSettings->update([
            'app_name' => $request->app_name,
            'app_version' => $request->app_version,
            'banner_image' => $bannerImagePath,
            'maintenance_mode' => $request->boolean('maintenance_mode'),
            'timezone' => $request->timezone,
            'locale' => $request->locale,
            'debug_mode' => $request->boolean('debug_mode'),
            'cache_enabled' => $request->boolean('cache_enabled'),
            'session_lifetime' => $request->session_lifetime,
        ]);

        // Update app name in cache for immediate effect
        Cache::put('app_name', $request->app_name, now()->addHours(24));

        // If maintenance mode changed
        if ($request->boolean('maintenance_mode')) {
            \Artisan::call('down');
        } else {
            \Artisan::call('up');
        }

        // Clear cache if cache settings changed
        if ($request->boolean('cache_enabled') !== (config('cache.default') !== 'array')) {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
        }

        return redirect()->route('app-settings.index')
                        ->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
    }

    /**
     * Get current app version
     */
    private function getCurrentAppVersion()
    {
        // Try to get from cache first
        $cachedVersion = Cache::get('app_version');
        if ($cachedVersion) {
            return $cachedVersion;
        }

        // Get from latest development history
        $latestHistory = DevelopmentHistory::whereNotNull('version')
                                          ->orderBy('development_date', 'desc')
                                          ->first();

        if ($latestHistory && $latestHistory->version) {
            $version = $latestHistory->version;
            Cache::put('app_version', $version, now()->addHours(1));
            return $version;
        }

        // Fallback to config version
        $version = config('app.version', '1.0.0');
        Cache::put('app_version', $version, now()->addHours(1));
        return $version;
    }

    /**
     * Update app version manually
     */
    public function updateVersion(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'version' => 'required|string|max:50|regex:/^\d+\.\d+\.\d+$/',
        ]);

        // Update latest development history with new version
        $latestHistory = DevelopmentHistory::orderBy('development_date', 'desc')->first();
        if ($latestHistory) {
            $latestHistory->update(['version' => $request->version]);
        }

        // Clear cache
        Cache::forget('app_version');

        return response()->json([
            'success' => true,
            'message' => 'Versi aplikasi berhasil diperbarui.',
            'version' => $request->version
        ]);
    }

    /**
     * Trigger automatic update check
     */
    public function checkForUpdates()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }

        try {
            // Run commit tracking
            $exitCode = \Artisan::call('development:track-commits', [
                '--since' => '1 day ago'
            ]);

            $output = \Artisan::output();

            // Check for new commits
            $newCommits = DevelopmentHistory::where('created_at', '>=', now()->subMinutes(5))
                                           ->whereNotNull('details->commit_hash')
                                           ->count();

            return response()->json([
                'success' => true,
                'message' => 'Pengecekan update selesai.',
                'new_commits' => $newCommits,
                'output' => $output
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengecek update: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Turn off debug mode
     */
    public function turnOffDebug()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }

        try {
            // Update .env file
            $envPath = base_path('.env');
            $envContent = file_get_contents($envPath);

            // Replace APP_DEBUG=true with APP_DEBUG=false
            $envContent = preg_replace('/APP_DEBUG\s*=\s*true/i', 'APP_DEBUG=false', $envContent);

            file_put_contents($envPath, $envContent);

            // Clear and recache configuration
            \Artisan::call('config:clear');
            \Artisan::call('config:cache');

            return response()->json([
                'success' => true,
                'message' => 'Mode debug berhasil dimatikan.',
                'debug_mode' => false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mematikan mode debug: ' . $e->getMessage()
            ]);
        }
    }
}
