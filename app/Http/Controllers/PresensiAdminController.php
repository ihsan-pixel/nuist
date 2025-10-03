<?php

namespace App\Http\Controllers;

use App\Models\PresensiSettings;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class PresensiAdminController extends Controller
{
    public function __construct()
    {
        // Middleware to restrict access to super_admin and admin roles
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!in_array($user->role, ['super_admin', 'admin', 'pengurus'])) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    // Show presensi settings form
    public function settings()
    {
        $statuses = \App\Models\StatusKepegawaian::all();
        $settings = PresensiSettings::with('statusKepegawaian')->get()->keyBy('status_kepegawaian_id');
        return view('presensi_admin.settings', compact('statuses', 'settings'));
    }

    // Update presensi settings
    public function updateSettings(Request $request)
    {
        $statuses = \App\Models\StatusKepegawaian::all();

        // Delete legacy singleton records and any existing per-status records to avoid duplicates
        \App\Models\PresensiSettings::whereNull('status_kepegawaian_id')->delete();
        \App\Models\PresensiSettings::truncate(); // Clear all to ensure clean state

        // Validate for each status
        $rules = [];
        foreach ($statuses as $status) {
            $prefix = "status_{$status->id}_";
            $rules[$prefix . 'waktu_mulai_presensi_masuk'] = 'nullable|date_format:H:i';
            $rules[$prefix . 'waktu_akhir_presensi_masuk'] = 'nullable|date_format:H:i';
            $rules[$prefix . 'waktu_mulai_presensi_pulang'] = 'nullable|date_format:H:i';
            $rules[$prefix . 'waktu_akhir_presensi_pulang'] = 'nullable|date_format:H:i';
        }

        $request->validate($rules);

        foreach ($statuses as $status) {
            $prefix = "status_{$status->id}_";

            // Normalize time inputs: replace '.' with ':'
            $timeFields = [
                $prefix . 'waktu_mulai_presensi_masuk',
                $prefix . 'waktu_akhir_presensi_masuk',
                $prefix . 'waktu_mulai_presensi_pulang',
                $prefix . 'waktu_akhir_presensi_pulang'
            ];

            foreach ($timeFields as $field) {
                if ($request->has($field) && $request->$field) {
                    $request->merge([
                        $field => str_replace('.', ':', $request->$field),
                    ]);
                }
            }

            // Explicitly delete any existing for this status (safety)
            \App\Models\PresensiSettings::where('status_kepegawaian_id', $status->id)->delete();

            // Create new record
            \App\Models\PresensiSettings::create([
                'status_kepegawaian_id' => $status->id,
                'waktu_mulai_presensi_masuk' => $request->input($prefix . 'waktu_mulai_presensi_masuk'),
                'waktu_akhir_presensi_masuk' => $request->input($prefix . 'waktu_akhir_presensi_masuk'),
                'waktu_mulai_presensi_pulang' => $request->input($prefix . 'waktu_mulai_presensi_pulang'),
                'waktu_akhir_presensi_pulang' => $request->input($prefix . 'waktu_akhir_presensi_pulang'),
            ]);
        }

        // Jalankan perintah untuk membersihkan duplikat
        Artisan::call('presensi:clean-duplicates');

        return redirect()->route('presensi_admin.settings')->with('success', 'Pengaturan presensi berhasil diperbarui.');
    }

    // Display all presensi data with user name, madrasah_id, and status
    public function index(Request $request)
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

        // Get selected date or default to today
        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // Query users with role 'tenaga_pendidik' who haven't done presensi on selected date
        $belumPresensiQuery = User::where('role', 'tenaga_pendidik');

        if ($user->role === 'admin' && $user->madrasah_id) {
            $belumPresensiQuery->where('madrasah_id', $user->madrasah_id);
        }

        $belumPresensi = $belumPresensiQuery->whereDoesntHave('presensis', function ($q) use ($selectedDate) {
            $q->whereDate('tanggal', $selectedDate);
        })->with('madrasah')->get();

        return view('presensi_admin.index', compact('presensis', 'belumPresensi', 'user', 'selectedDate'));
    }
}
