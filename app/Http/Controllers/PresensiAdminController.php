<?php

namespace App\Http\Controllers;

use App\Models\PresensiSettings;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

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
        // Since times are now based on hari_kbm, show the fixed time ranges
        $hariKbmOptions = [
            '5' => '5 Hari KBM',
            '6' => '6 Hari KBM'
        ];

        // Calculate time ranges for today (to show Friday exception if applicable)
        $today = Carbon::now('Asia/Jakarta')->toDateString();
        $timeRanges5 = $this->getPresensiTimeRanges('5', $today);
        $timeRanges6 = $this->getPresensiTimeRanges('6', $today);

        return view('presensi_admin.settings', compact('hariKbmOptions', 'timeRanges5', 'timeRanges6'));
    }

    /**
     * Get presensi time ranges based on hari_kbm and current day.
     * @param string|null $hariKbm
     * @param string $today
     * @return array
     */
    private function getPresensiTimeRanges($hariKbm, $today)
    {
        $dayOfWeek = Carbon::parse($today)->dayOfWeek; // 0=Sunday, 5=Friday

        if ($hariKbm == '5') {
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = ($dayOfWeek == 5) ? '14:00' : '14:30'; // Friday starts at 14:00
            $pulangEnd = '17:00';
        } elseif ($hariKbm == '6') {
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = '13:00';
            $pulangEnd = '17:00';
        } else {
            // Default or fallback
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = '13:00';
            $pulangEnd = '17:00';
        }

        return [
            'masuk_start' => $masukStart,
            'masuk_end' => $masukEnd,
            'pulang_start' => $pulangStart,
            'pulang_end' => $pulangEnd,
        ];
    }

    // Update presensi settings
    public function updateSettings(Request $request)
    {
        if ($request->has('status_id')) {
            // Single status update
            $statusId = $request->input('status_id');
            $status = \App\Models\StatusKepegawaian::findOrFail($statusId);
            $prefix = "status_{$statusId}_";

            $request->validate([
                $prefix . 'waktu_mulai_presensi_masuk' => 'nullable|date_format:H:i',
                $prefix . 'waktu_akhir_presensi_masuk' => 'nullable|date_format:H:i',
                $prefix . 'waktu_mulai_presensi_pulang' => 'nullable|date_format:H:i',
                $prefix . 'waktu_akhir_presensi_pulang' => 'nullable|date_format:H:i',
            ]);

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

            // Update or create for this status
            \App\Models\PresensiSettings::updateOrCreate(
                ['status_kepegawaian_id' => $statusId],
                [
                    'waktu_mulai_presensi_masuk' => $request->input($prefix . 'waktu_mulai_presensi_masuk'),
                    'waktu_akhir_presensi_masuk' => $request->input($prefix . 'waktu_akhir_presensi_masuk'),
                    'waktu_mulai_presensi_pulang' => $request->input($prefix . 'waktu_mulai_presensi_pulang'),
                    'waktu_akhir_presensi_pulang' => $request->input($prefix . 'waktu_akhir_presensi_pulang'),
                ]
            );

            return redirect()->route('presensi_admin.settings')->with('success', "Pengaturan presensi untuk status '{$status->name}' berhasil diperbarui.");
        } else {
            // All statuses update (legacy support)
            $statuses = \App\Models\StatusKepegawaian::all();

            $createdCount = 0;
            DB::transaction(function () use ($request, $statuses, &$createdCount) {
                // Delete all existing per-status records to avoid duplicates
                \App\Models\PresensiSettings::whereNotNull('status_kepegawaian_id')->delete();

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

                    // Create record (since deleted, always create)
                    \App\Models\PresensiSettings::create([
                        'status_kepegawaian_id' => $status->id,
                        'waktu_mulai_presensi_masuk' => $request->input($prefix . 'waktu_mulai_presensi_masuk'),
                        'waktu_akhir_presensi_masuk' => $request->input($prefix . 'waktu_akhir_presensi_masuk'),
                        'waktu_mulai_presensi_pulang' => $request->input($prefix . 'waktu_mulai_presensi_pulang'),
                        'waktu_akhir_presensi_pulang' => $request->input($prefix . 'waktu_akhir_presensi_pulang'),
                    ]);

                    $createdCount++;
                }
            });

            // Jalankan perintah untuk membersihkan duplikat
            Artisan::call('presensi:clean-duplicates');

            return redirect()->route('presensi_admin.settings')->with('success', "Pengaturan presensi berhasil diperbarui. Dibuat {$createdCount} records untuk semua status kepegawaian.");
        }
    }

    // Display all presensi data with user name, madrasah_id, and status
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get selected date or default to today
        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        if ($user->role === 'super_admin') {
            // For super_admin, show all madrasah tables (5 per row)
            $madrasahs = \App\Models\Madrasah::orderBy('id')->get();

            $madrasahData = [];
            foreach ($madrasahs as $madrasah) {
                $tenagaPendidik = User::where('role', 'tenaga_pendidik')
                    ->where('madrasah_id', $madrasah->id)
                    ->with(['presensis' => function ($q) use ($selectedDate) {
                        $q->whereDate('tanggal', $selectedDate);
                    }])
                    ->get();

                $presensiData = [];
                foreach ($tenagaPendidik as $tp) {
                    $presensi = $tp->presensis->first();
                    $presensiData[] = [
                        'nama' => $tp->name,
                        'status' => $presensi ? $presensi->status : 'tidak_hadir',
                        'waktu_masuk' => $presensi ? $presensi->waktu_masuk : null,
                        'waktu_keluar' => $presensi ? $presensi->waktu_keluar : null,
                        'keterangan' => $presensi ? $presensi->keterangan : null,
                    ];
                }

                $madrasahData[] = [
                    'madrasah' => $madrasah,
                    'presensi' => $presensiData,
                ];
            }

            return view('presensi_admin.index', compact('madrasahData', 'user', 'selectedDate'));
        } else {
            // For admin and others, show original view
            $query = Presensi::with('user.madrasah', 'statusKepegawaian');

            // If user is admin, filter by madrasah_id
            if ($user->role === 'admin' && $user->madrasah_id) {
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('madrasah_id', $user->madrasah_id);
                });
            }

            $presensis = $query->orderBy('tanggal', 'desc')->get();

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

    // API endpoint for real-time data
    public function getData(Request $request)
    {
        $user = Auth::user();
        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        if ($user->role === 'super_admin') {
            $madrasahs = \App\Models\Madrasah::orderBy('id')->get();

            $madrasahData = [];
            foreach ($madrasahs as $madrasah) {
                $tenagaPendidik = User::where('role', 'tenaga_pendidik')
                    ->where('madrasah_id', $madrasah->id)
                    ->with(['presensis' => function ($q) use ($selectedDate) {
                        $q->whereDate('tanggal', $selectedDate);
                    }])
                    ->get();

                $presensiData = [];
                foreach ($tenagaPendidik as $tp) {
                    $presensi = $tp->presensis->first();
                    $presensiData[] = [
                        'nama' => $tp->name,
                        'status' => $presensi ? $presensi->status : 'tidak_hadir',
                        'waktu_masuk' => $presensi ? $presensi->waktu_masuk : null,
                        'waktu_keluar' => $presensi ? $presensi->waktu_keluar : null,
                        'keterangan' => $presensi ? $presensi->keterangan : null,
                    ];
                }

                $madrasahData[] = [
                    'madrasah' => $madrasah,
                    'presensi' => $presensiData,
                ];
            }

            return response()->json($madrasahData);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
