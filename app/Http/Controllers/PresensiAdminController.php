<?php

namespace App\Http\Controllers;

use App\Models\PresensiSettings;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class PresensiAdminController extends Controller
{
    public function __construct()
    {
        // Middleware to restrict access to super_admin and admin roles
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!in_array($user->role, ['super_admin', 'admin'])) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    // Show presensi settings form
    public function settings()
    {
        $settings = PresensiSettings::first();
        return view('presensi_admin.settings', compact('settings'));
    }

    // Update presensi settings
    public function updateSettings(Request $request)
    {
        // Normalize time inputs: replace '.' with ':'
        $timeFields = [
            'waktu_mulai_presensi_masuk',
            'waktu_akhir_presensi_masuk',
            'waktu_mulai_presensi_pulang',
            'waktu_akhir_presensi_pulang'
        ];

        foreach ($timeFields as $field) {
            if ($request->has($field) && $request->$field) {
                $request->merge([
                    $field => str_replace('.', ':', $request->$field),
                ]);
            }
        }

        $request->validate([
            'waktu_mulai_presensi_masuk' => 'nullable|date_format:H:i',
            'waktu_akhir_presensi_masuk' => 'nullable|date_format:H:i',
            'waktu_mulai_presensi_pulang' => 'nullable|date_format:H:i',
            'waktu_akhir_presensi_pulang' => 'nullable|date_format:H:i',
        ]);

        $settings = PresensiSettings::first();
        if (!$settings) {
            $settings = new PresensiSettings();
        }

        $settings->waktu_mulai_presensi_masuk = $request->waktu_mulai_presensi_masuk;
        $settings->waktu_akhir_presensi_masuk = $request->waktu_akhir_presensi_masuk;
        $settings->waktu_mulai_presensi_pulang = $request->waktu_mulai_presensi_pulang;
        $settings->waktu_akhir_presensi_pulang = $request->waktu_akhir_presensi_pulang;
        $settings->singleton = true;
        $settings->save();

        // Jalankan perintah untuk membersihkan duplikat
        Artisan::call('presensi:clean-duplicates');

        return redirect()->route('presensi_admin.settings')->with('success', 'Pengaturan presensi berhasil diperbarui.');
    }

    // Display all presensi data with user name, madrasah_id, and status
    public function index()
    {
        $user = Auth::user();

        $query = Presensi::with('user.madrasah');

        // If user is admin, filter by madrasah_id
        if ($user->role === 'admin' && $user->madrasah_id) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('madrasah_id', $user->madrasah_id);
            });
        }
        // If user is super_admin, show all data (no additional filtering needed)

        $presensis = $query->orderBy('tanggal', 'desc')->get();

        return view('presensi_admin.index', compact('presensis'));
    }
}
