<?php

namespace App\Http\Controllers;

use App\Models\PresensiSettings;
use App\Models\Presensi;
use App\Models\User;
use App\Models\Holiday;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PresensiPerBulanExport;
use App\Exports\PresensiSemuaExport;
use App\Services\ExternalTeachingPermissionService;

class PresensiAdminController extends Controller
{
    public function __construct()
    {
        // Middleware to restrict access to super_admin, admin, pengurus, and tenaga_pendidik with ketugasan kepala
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            $allowed = in_array($user->role, ['super_admin', 'admin', 'pengurus']);

            // Allow tenaga_pendidik with ketugasan kepala madrasah/sekolah
            if (!$allowed && $user->role === 'tenaga_pendidik' && $user->ketugasan === 'kepala madrasah/sekolah') {
                $allowed = true;
            }

            if (!$allowed) {
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
            $pulangStart = '15:00';
            $pulangEnd = '22:00';
        } elseif ($hariKbm == '6') {
            $masukStart = '06:00';
            $masukEnd = '07:00';
            // Khusus hari Jumat untuk 6 hari KBM, presensi pulang mulai pukul 14:30
            $pulangStart = ($dayOfWeek == 5) ? '14:30' : '15:00';
            $pulangEnd = '22:00';
        } else {
            // Default or fallback
            $masukStart = '06:00';
            $masukEnd = '07:00';
            $pulangStart = '15:00';
            $pulangEnd = '22:00';
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
        $threeMonthAbsenceData = $this->getThreeMonthAbsenceData($selectedDate, $user);
        $teacherAbsenceRecapData = $this->getTeacherAbsenceRecapData($request, $user);

        // Calculate summary metrics
        $summary = $this->calculatePresensiSummary($selectedDate, $user);

        if (in_array($user->role, ['super_admin', 'pengurus'])) {
            // For super_admin and pengurus, show all madrasah tables (5 per row)
            $kabupatenOrder = [
                'Kabupaten Gunungkidul',
                'Kabupaten Bantul',
                'Kabupaten Kulon Progo',
                'Kabupaten Sleman',
                'Kota Yogyakarta'
            ];

            $madrasahData = [];
            foreach ($kabupatenOrder as $kabupaten) {
                $madrasahs = \App\Models\Madrasah::where('kabupaten', $kabupaten)
                    ->orderByRaw("CAST(scod AS UNSIGNED) ASC")
                    ->get();

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
                    $dailyPresensi = $this->resolveDailyPresensiData($tp, $selectedDate, $presensi);
                    $presensiData[] = [
                        'user_id' => $tp->id,
                        'nama' => $tp->name,
                        'status' => $dailyPresensi['status'],
                        'waktu_masuk' => $dailyPresensi['waktu_masuk'],
                        'waktu_keluar' => $dailyPresensi['waktu_keluar'],
                        'keterangan' => $dailyPresensi['keterangan'],
                        'is_fake_location' => $dailyPresensi['is_fake_location'],
                    ];
                }

                $madrasahData[] = [
                    'madrasah' => $madrasah,
                    'presensi' => $presensiData,
                ];
            }
            }

            return view('presensi_admin.index', compact('madrasahData', 'user', 'selectedDate', 'summary', 'threeMonthAbsenceData', 'teacherAbsenceRecapData'));
        } elseif ($user->role === 'admin' && $user->madrasah_id) {
            // For admin, redirect to their madrasah detail page
            return redirect()->route('presensi_admin.show_detail', $user->madrasah_id)->withInput(['date' => $selectedDate->format('Y-m-d')]);
        } else {
            // For tenaga_pendidik kepala, show original view
            $query = Presensi::with('user.madrasah', 'statusKepegawaian');

            // If user is tenaga_pendidik kepala, filter by madrasah_id
            if (($user->role === 'tenaga_pendidik' && $user->ketugasan === 'kepala madrasah/sekolah') && $user->madrasah_id) {
                $query->where(function ($q) use ($user) {
                    // Jika presensi.madrasah_id bernilai null, tampilkan data presensi di mana user.madrasah_id == user.madrasah_id
                    $q->where(function ($subQ) use ($user) {
                        $subQ->whereNull('madrasah_id')
                             ->whereHas('user', function ($userQ) use ($user) {
                                 $userQ->where('madrasah_id', $user->madrasah_id);
                             });
                    })
                    // Jika presensi.madrasah_id tidak bernilai null, tampilkan data presensi di mana presensi.madrasah_id == user.madrasah_id
                    ->orWhere('madrasah_id', $user->madrasah_id);
                });
            }

            $presensis = $query->orderBy('tanggal', 'desc')->get();

            // Query users with role 'tenaga_pendidik' who haven't done presensi on selected date
            $belumPresensiQuery = User::where('role', 'tenaga_pendidik');

            if (($user->role === 'tenaga_pendidik' && $user->ketugasan === 'kepala madrasah/sekolah') && $user->madrasah_id) {
                $belumPresensiQuery->where('madrasah_id', $user->madrasah_id);
            }

            $belumPresensi = $belumPresensiQuery->whereDoesntHave('presensis', function ($q) use ($selectedDate) {
                $q->whereDate('tanggal', $selectedDate);
            })->with('madrasah')->get()
                ->reject(fn ($teacher) => ExternalTeachingPermissionService::hasApprovedNoPresenceDay($teacher, $selectedDate))
                ->values();

            return view('presensi_admin.index', compact('presensis', 'belumPresensi', 'user', 'selectedDate', 'summary', 'threeMonthAbsenceData', 'teacherAbsenceRecapData'));
        }
    }

    // API endpoint for real-time data
    public function getData(Request $request)
    {
        $user = Auth::user();
        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        if (in_array($user->role, ['super_admin', 'pengurus'])) {
            $kabupatenOrder = [
                'Kabupaten Gunungkidul',
                'Kabupaten Bantul',
                'Kabupaten Kulon Progo',
                'Kabupaten Sleman',
                'Kota Yogyakarta'
            ];

            $madrasahData = [];
            foreach ($kabupatenOrder as $kabupaten) {
                $madrasahs = \App\Models\Madrasah::where('kabupaten', $kabupaten)
                    ->orderByRaw("CAST(scod AS UNSIGNED) ASC")
                    ->get();

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
                    $dailyPresensi = $this->resolveDailyPresensiData($tp, $selectedDate, $presensi);
                    $presensiData[] = [
                        'user_id' => $tp->id,
                        'nama' => $tp->name,
                        'status' => $dailyPresensi['status'],
                        'waktu_masuk' => $dailyPresensi['waktu_masuk'],
                        'waktu_keluar' => $dailyPresensi['waktu_keluar'],
                        'keterangan' => $dailyPresensi['keterangan'],
                        'is_fake_location' => $dailyPresensi['is_fake_location'],
                    ];
                }

                $madrasahData[] = [
                    'madrasah' => $madrasah,
                    'presensi' => $presensiData,
                ];
            }
            }

            return response()->json($madrasahData);
        } elseif (($user->role === 'admin' || ($user->role === 'tenaga_pendidik' && $user->ketugasan === 'kepala madrasah/sekolah')) && $user->madrasah_id) {
            // For admin and tenaga_pendidik kepala, return filtered data for their madrasah
            $madrasah = \App\Models\Madrasah::find($user->madrasah_id);
            if (!$madrasah) {
                return response()->json(['error' => 'Madrasah not found'], 404);
            }

            $tenagaPendidik = User::where('role', 'tenaga_pendidik')
                ->where('madrasah_id', $user->madrasah_id)
                ->with(['presensis' => function ($q) use ($selectedDate) {
                    $q->whereDate('tanggal', $selectedDate);
                }])
                ->get();

            $presensiData = [];
            foreach ($tenagaPendidik as $tp) {
                $presensi = $tp->presensis->first();
                $dailyPresensi = $this->resolveDailyPresensiData($tp, $selectedDate, $presensi);
                $presensiData[] = [
                    'user_id' => $tp->id,
                    'nama' => $tp->name,
                    'status' => $dailyPresensi['status'],
                    'waktu_masuk' => $dailyPresensi['waktu_masuk'],
                    'waktu_keluar' => $dailyPresensi['waktu_keluar'],
                    'keterangan' => $dailyPresensi['keterangan'],
                    'is_fake_location' => $dailyPresensi['is_fake_location'],
                ];
            }

            $madrasahData = [
                [
                    'madrasah' => $madrasah,
                    'presensi' => $presensiData,
                ]
            ];

            return response()->json($madrasahData);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // API endpoint for summary data
    public function getSummary(Request $request)
    {
        $user = Auth::user();
        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        $summary = $this->calculatePresensiSummary($selectedDate, $user);

        return response()->json($summary);
    }

    // API endpoint for user detail popup
    public function getDetail($userId)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'pengurus'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $userDetail = User::with(['madrasah', 'statusKepegawaian', 'presensis' => function($q) {
            $q->orderBy('tanggal', 'desc')->limit(10);
        }])->findOrFail($userId);

        $presensiHistory = $userDetail->presensis->map(function($presensi) {
            return [
                'tanggal' => $presensi->tanggal->format('d-m-Y'),
                'waktu_masuk' => $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i') : null,
                'waktu_keluar' => $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i') : null,
                'status' => $presensi->status,
                'keterangan' => $presensi->keterangan,
                'lokasi' => $presensi->lokasi,
            ];
        });

        return response()->json([
            'user' => [
                'name' => $userDetail->name,
                'email' => $userDetail->email,
                'avatar' => $userDetail->avatar,
                'madrasah' => $userDetail->madrasah->name ?? '-',
                'status_kepegawaian' => $userDetail->statusKepegawaian->name ?? '-',
                'nip' => $userDetail->nip,
                'nuptk' => $userDetail->nuptk,
                'no_hp' => $userDetail->no_hp,
            ],
            'presensi_history' => $presensiHistory
        ]);
    }

    // API endpoint for madrasah detail popup
    public function getMadrasahDetail($madrasahId, Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'pengurus'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        $madrasah = \App\Models\Madrasah::find($madrasahId);
        if (!$madrasah) {
            return response()->json(['error' => 'Madrasah not found'], 404);
        }

        $tenagaPendidik = User::where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $madrasahId)
            ->with(['statusKepegawaian', 'presensis' => function ($q) use ($selectedDate) {
                $q->whereDate('tanggal', $selectedDate);
            }])
            ->get();

        $tenagaPendidikData = $tenagaPendidik->map(function($tp) use ($selectedDate) {
            $presensi = $tp->presensis->first();
            $dailyPresensi = $this->resolveDailyPresensiData($tp, $selectedDate, $presensi);
            return [
                'id' => $tp->id,
                'nama' => $tp->name,
                'nip' => $tp->nip,
                'nuptk' => $tp->nuptk,
                'status_kepegawaian' => $tp->statusKepegawaian ? $tp->statusKepegawaian->name : '-',
                'status' => $dailyPresensi['status'],
                'waktu_masuk' => $dailyPresensi['waktu_masuk'] ? $dailyPresensi['waktu_masuk']->format('H:i:s') : null,
                'waktu_keluar' => $dailyPresensi['waktu_keluar'] ? $dailyPresensi['waktu_keluar']->format('H:i:s') : null,
                'latitude' => $dailyPresensi['latitude'],
                'longitude' => $dailyPresensi['longitude'],
                'lokasi' => $dailyPresensi['lokasi'],
                'keterangan' => $dailyPresensi['keterangan'],
                'is_fake_location' => $dailyPresensi['is_fake_location'],
                'accuracy' => $dailyPresensi['accuracy'],
                'created_at' => $dailyPresensi['created_at'] ? $dailyPresensi['created_at']->format('Y-m-d H:i:s') : null,
                'face_verified' => $dailyPresensi['face_verified'],
                'face_similarity_score' => $dailyPresensi['face_similarity_score'],
                'liveness_score' => $dailyPresensi['liveness_score'],
            ];
        });

        return response()->json([
            'madrasah' => [
                'name' => $madrasah->name,
                'scod' => $madrasah->scod ?? '-',
                'kabupaten' => $madrasah->kabupaten ?? '-',
                'alamat' => $madrasah->alamat ?? '-',
                'hari_kbm' => $madrasah->hari_kbm ?? '-',
                'latitude' => $madrasah->latitude ?? '-',
                'longitude' => $madrasah->longitude ?? '-',
                'map_link' => $madrasah->map_link,
                'polygon_koordinat' => $madrasah->polygon_koordinat,
                'polygon_koordinat_2' => $madrasah->polygon_koordinat_2,
                'enable_dual_polygon' => $madrasah->enable_dual_polygon,
            ],
            'tenaga_pendidik' => $tenagaPendidikData
        ]);
    }

    // Show madrasah detail page
    public function showMadrasahDetail($madrasahId, Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'pengurus', 'admin'])) {
            abort(403, 'Unauthorized');
        }

        // For admin users, ensure they can only access their own madrasah
        if ($user->role === 'admin' && $user->madrasah_id != $madrasahId) {
            abort(403, 'Unauthorized');
        }

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $summaryPeriod = $request->input('summary_period', 'week') === 'month' ? 'month' : 'week';
        $today = Carbon::today('Asia/Jakarta');
        $selectedWeek = $request->filled('week') && preg_match('/^\d{4}-W\d{2}$/', $request->week)
            ? Carbon::now('Asia/Jakarta')->setISODate(
                (int) substr($request->week, 0, 4),
                (int) substr($request->week, 6, 2)
            )->startOfWeek(Carbon::MONDAY)
            : $today->copy()->startOfWeek(Carbon::MONDAY);
        $selectedMonth = $request->filled('month')
            ? Carbon::createFromFormat('Y-m', $request->month, 'Asia/Jakarta')->startOfMonth()
            : $today->copy()->startOfMonth();

        $madrasah = \App\Models\Madrasah::findOrFail($madrasahId);

        // Build query with search functionality
        $query = User::where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $madrasahId)
            ->with(['statusKepegawaian', 'presensis' => function ($q) use ($selectedDate) {
                $q->whereDate('tanggal', $selectedDate);
            }]);

        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('nuptk', 'like', '%' . $search . '%');
            });
        }

        // Calculate total matched records and paginate them all on one page so the view shows everything
        $totalMatched = $query->count();
        $perPage = $totalMatched > 0 ? $totalMatched : 1;

        // Get paginated results (all rows in one page)
        $tenagaPendidik = $query->paginate($perPage, ['*'], 'page', $page);

        $tenagaPendidikData = $tenagaPendidik->map(function($tp) use ($selectedDate) {
            $presensi = $tp->presensis->first();
            $dailyPresensi = $this->resolveDailyPresensiData($tp, $selectedDate, $presensi);
            return [
                'id' => $tp->id,
                'nama' => $tp->name,
                'nip' => $tp->nip,
                'nuptk' => $tp->nuptk,
                'status_kepegawaian' => $tp->statusKepegawaian ? $tp->statusKepegawaian->name : '-',
                'status' => $dailyPresensi['status'],
                'waktu_masuk' => $dailyPresensi['waktu_masuk'] ? $dailyPresensi['waktu_masuk']->format('H:i:s') : null,
                'waktu_keluar' => $dailyPresensi['waktu_keluar'] ? $dailyPresensi['waktu_keluar']->format('H:i:s') : null,
                'latitude' => $dailyPresensi['latitude'],
                'longitude' => $dailyPresensi['longitude'],
                'lokasi' => $dailyPresensi['lokasi'],
                'keterangan' => $dailyPresensi['keterangan'],
                'is_fake_location' => $dailyPresensi['is_fake_location'],
                'accuracy' => $dailyPresensi['accuracy'],
                'created_at' => $dailyPresensi['created_at'] ? $dailyPresensi['created_at']->format('Y-m-d H:i:s') : null,
                'face_verified' => $dailyPresensi['face_verified'],
                'face_similarity_score' => $dailyPresensi['face_similarity_score'],
                'liveness_score' => $dailyPresensi['liveness_score'],
            ];
        });

        $summaryStartDate = $summaryPeriod === 'month'
            ? $selectedMonth->copy()->startOfMonth()
            : $selectedWeek->copy()->startOfWeek(Carbon::MONDAY);
        $summaryEndDate = $summaryPeriod === 'month'
            ? $selectedMonth->copy()->endOfMonth()
            : $selectedWeek->copy()->endOfWeek(Carbon::SUNDAY);
        $summaryLabel = $summaryPeriod === 'month'
            ? 'Bulanan'
            : 'Mingguan';

        $attendancePercentageRows = $tenagaPendidik->getCollection()
            ->map(function ($tp) use ($madrasah, $summaryStartDate, $summaryEndDate, $today) {
                $summary = $this->buildTeacherAttendanceSummary(
                    $tp->id,
                    $madrasah->hari_kbm,
                    $summaryStartDate,
                    $summaryEndDate,
                    $today
                );

                return [
                    'id' => $tp->id,
                    'nama' => $tp->name,
                    'nip' => $tp->nip,
                    'nuptk' => $tp->nuptk,
                    'status_kepegawaian' => $tp->statusKepegawaian?->name ?? '-',
                    'total_hari_kerja' => $summary['total_hari_kerja'],
                    'total_hadir' => $summary['total_hadir'],
                    'total_izin' => $summary['total_izin'],
                    'total_belum_hadir' => $summary['total_belum_hadir'],
                    'persentase_kehadiran' => $summary['persentase_kehadiran'],
                ];
            })
            ->sortBy([
                ['persentase_kehadiran', 'desc'],
                ['nama', 'asc'],
            ])
            ->values();

        $bulanTersedia = DB::table('presensis')
            ->join('users', 'presensis.user_id', '=', 'users.id')
            ->selectRaw("MONTH(presensis.tanggal) AS bulan, DATE_FORMAT(presensis.tanggal, '%M %Y') AS nama_bulan")
            ->where(function ($q) use ($madrasah) {
                $q->where('presensis.madrasah_id', $madrasah->id)
                  ->orWhere(function ($subQ) use ($madrasah) {
                      $subQ->whereNull('presensis.madrasah_id')
                           ->where('users.madrasah_id', $madrasah->id)
                           ->where('users.role', 'tenaga_pendidik');
                  });
            })
            ->groupBy('bulan', 'nama_bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        return view('presensi_admin.detail', compact(
            'madrasah', 'tenagaPendidik', 'tenagaPendidikData',
            'selectedDate', 'user', 'search', 'bulanTersedia',
            'summaryPeriod', 'selectedWeek', 'selectedMonth',
            'summaryLabel', 'summaryStartDate', 'summaryEndDate',
            'attendancePercentageRows'
        ));
    }

    // Export presensi data to Excel
    public function export(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['super_admin', 'pengurus'])) {
            abort(403, 'Unauthorized');
        }

        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // Get all madrasah with their presensi data ordered by kabupaten and scod
        $kabupatenOrder = [
            'Kabupaten Gunungkidul',
            'Kabupaten Bantul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];

        $data = [];
        foreach ($kabupatenOrder as $kabupaten) {
            $madrasahs = \App\Models\Madrasah::where('kabupaten', $kabupaten)
                ->orderByRaw("CAST(scod AS UNSIGNED) ASC")
                ->get();

            foreach ($madrasahs as $madrasah) {
                $tenagaPendidik = User::where('role', 'tenaga_pendidik')
                    ->where('madrasah_id', $madrasah->id)
                    ->with(['statusKepegawaian', 'presensis' => function ($q) use ($selectedDate) {
                        $q->whereDate('tanggal', $selectedDate);
                    }])
                    ->get();

                foreach ($tenagaPendidik as $tp) {
                    $presensi = $tp->presensis->first();
                    $dailyPresensi = $this->resolveDailyPresensiData($tp, $selectedDate, $presensi);
                    $data[] = [
                        'Madrasah' => $madrasah->name,
                        'SCOD' => $madrasah->scod,
                        'Kabupaten' => $madrasah->kabupaten,
                        'Nama Guru' => $tp->name,
                        'Status Kepegawaian' => $tp->statusKepegawaian->name ?? '-',
                        'NIP' => $tp->nip,
                        'NUPTK' => $tp->nuptk,
                        'Status Presensi' => $dailyPresensi['status'],
                        'Waktu Masuk' => $dailyPresensi['waktu_masuk'] ? $dailyPresensi['waktu_masuk']->format('H:i:s') : null,
                        'Waktu Keluar' => $dailyPresensi['waktu_keluar'] ? $dailyPresensi['waktu_keluar']->format('H:i:s') : null,
                        'Keterangan' => $dailyPresensi['keterangan'],
                        'Lokasi' => $dailyPresensi['lokasi'],
                        'Tanggal' => $selectedDate->format('Y-m-d'),
                    ];
                }
            }
        }

        // Create Excel file
        $filename = 'Data_Presensi_' . $selectedDate->format('Y-m-d') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return [
                    'Madrasah',
                    'SCOD',
                    'Kabupaten',
                    'Nama Guru',
                    'Status Kepegawaian',
                    'NIP',
                    'NUPTK',
                    'Status Presensi',
                    'Waktu Masuk',
                    'Waktu Keluar',
                    'Keterangan',
                    'Lokasi',
                    'Tanggal'
                ];
            }
        }, $filename);
    }

    // Export presensi data per madrasah to Excel
    public function exportMadrasah(Request $request, $madrasahId)
    {
        $user = Auth::user();
        if ($user->role !== 'super_admin') {
            abort(403, 'Unauthorized');
        }

        $madrasah = \App\Models\Madrasah::findOrFail($madrasahId);
        $type = $request->input('type', 'all'); // 'month' or 'all'

        if ($type === 'month') {
            $month = $request->input('month', Carbon::now()->format('Y-m'));
            $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
        }

        $query = Presensi::with(['user.statusKepegawaian'])
            ->whereHas('user', function ($q) use ($madrasahId) {
                $q->where('madrasah_id', $madrasahId)
                  ->where('role', 'tenaga_pendidik');
            });

        if ($type === 'month') {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }

        $presensis = $query->orderBy('tanggal', 'desc')->get();

        $data = [];
        foreach ($presensis as $presensi) {
            $data[] = [
                'Tanggal' => $presensi->tanggal->format('Y-m-d'),
                'Nama Guru' => $presensi->user->name,
                'Status Kepegawaian' => $presensi->statusKepegawaian->name ?? '-',
                'NIP' => $presensi->user->nip,
                'NUPTK' => $presensi->user->nuptk,
                'Status Presensi' => $presensi->status,
                'Waktu Masuk' => $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i:s') : null,
                'Waktu Keluar' => $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i:s') : null,
                'Keterangan' => $presensi->keterangan,
                'Lokasi' => $presensi->lokasi,
            ];
        }

        $filename = 'Data_Presensi_' . $madrasah->name . '_' . ($type === 'month' ? $month : 'Semua_Data') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return [
                    'Tanggal',
                    'Nama Guru',
                    'Status Kepegawaian',
                    'NIP',
                    'NUPTK',
                    'Status Presensi',
                    'Waktu Masuk',
                    'Waktu Keluar',
                    'Keterangan',
                    'Lokasi'
                ];
            }
        }, $filename);
    }

    // Export presensi data bulanan untuk admin
    public function exportMonthly(Request $request)
    {
        $user = Auth::user();

        // Hanya untuk role admin
        if ($user->role !== 'admin' || !$user->madrasah_id) {
            abort(403, 'Unauthorized');
        }

        $type = $request->input('type', 'month');

        if ($type === 'all') {
            // Export semua data presensi untuk madrasah admin
            $query = Presensi::with(['user.statusKepegawaian'])
                ->whereHas('user', function ($q) use ($user) {
                    $q->where('madrasah_id', $user->madrasah_id)
                      ->where('role', 'tenaga_pendidik');
                });

            $presensis = $query->orderBy('tanggal', 'desc')->get();

            $data = [];
            foreach ($presensis as $presensi) {
                $data[] = [
                    'Tanggal' => $presensi->tanggal->format('Y-m-d'),
                    'Nama Guru' => $presensi->user->name,
                    'Status Kepegawaian' => $presensi->statusKepegawaian->name ?? '-',
                    'NIP' => $presensi->user->nip,
                    'NUPTK' => $presensi->user->nuptk,
                    'Status Presensi' => $presensi->status,
                    'Waktu Masuk' => $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i:s') : null,
                    'Waktu Keluar' => $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i:s') : null,
                    'Keterangan' => $presensi->keterangan,
                    'Lokasi' => $presensi->lokasi,
                ];
            }

            $filename = 'Data_Presensi_Semua_' . str_replace(' ', '_', $user->madrasah->name) . '_' . Carbon::now()->format('Y-m-d') . '.xlsx';

            return \Maatwebsite\Excel\Facades\Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
                private $data;

                public function __construct($data)
                {
                    $this->data = $data;
                }

                public function array(): array
                {
                    return $this->data;
                }

                public function headings(): array
                {
                    return [
                        'Tanggal',
                        'Nama Guru',
                        'Status Kepegawaian',
                        'NIP',
                        'NUPTK',
                        'Status Presensi',
                        'Waktu Masuk',
                        'Waktu Keluar',
                        'Keterangan',
                        'Lokasi'
                    ];
                }
            }, $filename);
        } else {
            // Export bulanan
            $month = $request->input('month', Carbon::now()->format('Y-m'));
            list($year, $monthNum) = explode('-', $month);

            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\PresensiMonthlyExport($monthNum, $year, $user),
                'Data_Presensi_Bulanan_' . $month . '.xlsx'
            );
        }
    }

    // Export presensi data to Excel
    public function exportExcel(Request $request)
    {
        $madrasahId = $request->madrasah_id;
        $jenis = $request->jenis;
        $bulan = $request->bulan;

        if ($jenis === 'bulan') {
            return Excel::download(new PresensiPerBulanExport($madrasahId, $bulan),
                "Presensi_Madrasah_Bulan_$bulan.xlsx");
        }

        return Excel::download(new PresensiSemuaExport($madrasahId),
            "Presensi_Madrasah_Semua.xlsx");
    }

    // Laporan Presensi Mingguan - Super Admin Only
    public function laporanMingguan(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'super_admin') {
            abort(403, 'Unauthorized');
        }

        // Get month for top 10 madrasah
        $month = $request->input('month', now()->format('Y-m'));
        $year = (int) substr($month, 0, 4);
        $monthNum = (int) substr($month, 5, 2);
        $startOfMonth = Carbon::create($year, $monthNum, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $today = Carbon::today();
        $effectiveEndOfMonth = $startOfMonth->isSameMonth($today) && $startOfMonth->isSameYear($today)
            ? $today->copy()
            : $endOfMonth->copy();

        // Calculate top 10 madrasah for the month
        $madrasahPercentages = [];
        $allMadrasahs = \App\Models\Madrasah::orderByRaw("CAST(scod AS UNSIGNED) ASC")->get();

        foreach ($allMadrasahs as $madrasah) {
            $teachers = User::where('madrasah_id', $madrasah->id)
                ->where('role', 'tenaga_pendidik')
                ->get();

            $totalTeachers = $teachers->count();

            if ($totalTeachers == 0) {
                $madrasahPercentages[] = [
                    'nama' => $madrasah->name,
                    'persentase' => 0
                ];
                continue;
            }

            $totalHadir = 0;
            $totalIzin = 0;
            $totalAlpha = 0;
            $totalPresensi = 0;
            $currentDate = $startOfMonth->copy();

            while ($currentDate <= $effectiveEndOfMonth) {
                $dayOfWeek = $currentDate->dayOfWeek;
                $isWorkingDay = $madrasah->hari_kbm == 5
                    ? ($dayOfWeek >= Carbon::MONDAY && $dayOfWeek <= Carbon::FRIDAY)
                    : ($dayOfWeek >= Carbon::MONDAY && $dayOfWeek <= Carbon::SATURDAY);
                $isHoliday = Holiday::where('date', $currentDate->toDateString())->exists();

                if ($isWorkingDay && !$isHoliday) {
                    $hadir = 0;
                    $izin = 0;
                    $alpha = 0;

                    foreach ($teachers as $guru) {
                        $presensi = Presensi::where('user_id', $guru->id)
                            ->whereDate('tanggal', $currentDate)
                            ->first();

                        if ($presensi && $presensi->status === 'hadir') {
                            $hadir++;
                        } elseif ($this->isTeacherIzinForDate($guru, $currentDate, $presensi)) {
                            $izin++;
                        } else {
                            $alpha++;
                        }
                    }

                    $totalHadir += $hadir;
                    $totalIzin += $izin;
                    $totalAlpha += $alpha;
                    $totalPresensi += ($hadir + $izin + $alpha);
                }

                $currentDate->addDay();
            }

            $persentase = $totalPresensi > 0 ? (($totalHadir + $totalIzin) / $totalPresensi) * 100 : 0;

            $madrasahPercentages[] = [
                'nama' => $madrasah->name,
                'persentase' => $persentase
            ];
        }

        $top10Madrasah = collect($madrasahPercentages)->sortByDesc('persentase')->take(10)->values()->all();

        // Format input week: YYYY-Www (contoh: 2025-W49)
        $weekInput = trim($request->input('week', now()->format('Y-\WW')));

        // Pecah format: 2025-W49
        if (!preg_match('/^(\d{4})-W(\d{2})$/', $weekInput, $matches)) {
            abort(400, 'Format minggu tidak valid');
        }

        $year = (int) $matches[1];
        $week = (int) $matches[2];

        // ISO Week → AMAN, TANPA Trailing Data
        $startOfWeek = Carbon::now()
            ->setISODate($year, $week)
            ->startOfWeek(Carbon::MONDAY);

        $endOfWeek = $startOfWeek->copy()
            ->endOfWeek(Carbon::SATURDAY);

        // Export Excel
        if ($request->filled('export') && $request->export === 'excel') {
            return $this->exportLaporanMingguan($startOfWeek, $endOfWeek);
        }

        $kabupatenOrder = [
            'Kabupaten Bantul',
            'Kabupaten Gunungkidul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];

        $laporanData = [];
        $laporanBulananData = [];

        foreach ($kabupatenOrder as $kabupaten) {

            $madrasahs = \App\Models\Madrasah::where('kabupaten', $kabupaten)
                ->orderByRaw("CAST(scod AS UNSIGNED) ASC")
                ->get();

            $kabupatenData = [
                'kabupaten' => $kabupaten,
                'madrasahs' => [],
                'total_hadir' => 0,
                'total_izin' => 0,
                'total_alpha' => 0,
                'total_presensi' => 0,
                'persentase_kehadiran' => 0
            ];

            foreach ($madrasahs as $madrasah) {

                $tenagaPendidik = User::where('role', 'tenaga_pendidik')
                    ->where('madrasah_id', $madrasah->id)
                    ->get();

                $presensiMingguan = [];
                $totalHadir = 0;
                $totalIzin = 0;
                $totalPresensi = 0;

                $currentDate = $startOfWeek->copy();
                $daysToCount = $madrasah->hari_kbm == 5 ? 5 : 6; // Jika 5 hari kerja, jangan hitung Sabtu

                for ($i = 0; $i < $daysToCount; $i++) {
                    $isHoliday = Holiday::where('date', $currentDate->toDateString())->exists();

                    if ($isHoliday) {
                        $presensiMingguan[] = ['hadir' => '-', 'izin' => '-', 'alpha' => '-'];
                    } else {
                        $hadir = 0;
                        $izin = 0;
                        $alpha = 0;

                        foreach ($tenagaPendidik as $guru) {
                            $presensi = Presensi::where('user_id', $guru->id)
                                ->whereDate('tanggal', $currentDate)
                                ->first();

                            if ($presensi && $presensi->status === 'hadir') {
                                $hadir++;
                            } elseif ($this->isTeacherIzinForDate($guru, $currentDate, $presensi)) {
                                $izin++;
                            } else {
                                $alpha++;
                            }
                        }

                        $presensiMingguan[] = compact('hadir', 'izin', 'alpha');

                        $totalHadir += $hadir;
                        $totalIzin += $izin;
                        $totalPresensi += ($hadir + $izin + $alpha);
                    }

                    $currentDate->addDay();
                }

                // Jika 5 hari kerja, tambahkan data kosong untuk Sabtu agar tetap 6 kolom
                if ($madrasah->hari_kbm == 5) {
                    $presensiMingguan[] = ['hadir' => '-', 'izin' => '-', 'alpha' => '-'];
                }

                $persentase = $totalPresensi > 0
                    ? (($totalHadir + $totalIzin) / $totalPresensi) * 100
                    : 0;

                $kabupatenData['madrasahs'][] = [
                    'scod' => $madrasah->scod,
                    'nama' => $madrasah->name,
                    'hari_kbm' => $madrasah->hari_kbm,
                    'total_tenaga_pendidik' => $tenagaPendidik->count(),
                    'presensi' => $presensiMingguan,
                    'persentase_kehadiran' => $persentase
                ];

                $kabupatenData['total_hadir'] += $totalHadir;
                $kabupatenData['total_izin'] += collect($presensiMingguan)->sum(function ($item) {
                    return is_numeric($item['izin']) ? $item['izin'] : 0;
                });
                $kabupatenData['total_alpha'] += collect($presensiMingguan)->sum(function ($item) {
                    return is_numeric($item['alpha']) ? $item['alpha'] : 0;
                });
                $kabupatenData['total_presensi'] += $totalPresensi;
            }

            $kabupatenData['persentase_kehadiran'] =
                $kabupatenData['total_presensi'] > 0
                    ? (($kabupatenData['total_hadir'] + $kabupatenData['total_izin']) / $kabupatenData['total_presensi']) * 100
                    : 0;

            $laporanData[] = $kabupatenData;
        }

        foreach ($kabupatenOrder as $kabupaten) {
            $madrasahs = \App\Models\Madrasah::where('kabupaten', $kabupaten)
                ->orderByRaw("CAST(scod AS UNSIGNED) ASC")
                ->get();

            $kabupatenBulananData = [
                'kabupaten' => $kabupaten,
                'madrasahs' => [],
                'total_hadir' => 0,
                'total_izin' => 0,
                'total_alpha' => 0,
                'total_presensi' => 0,
                'persentase_kehadiran' => 0
            ];

            foreach ($madrasahs as $madrasah) {
                $tenagaPendidik = User::where('role', 'tenaga_pendidik')
                    ->where('madrasah_id', $madrasah->id)
                    ->get();

                $totalHadirBulanan = 0;
                $totalIzinBulanan = 0;
                $totalAlphaBulanan = 0;
                $totalPresensiBulanan = 0;
                $currentDate = $startOfMonth->copy();

                while ($currentDate <= $effectiveEndOfMonth) {
                    $dayOfWeek = $currentDate->dayOfWeek;
                    $isWorkingDay = $madrasah->hari_kbm == 5
                        ? ($dayOfWeek >= Carbon::MONDAY && $dayOfWeek <= Carbon::FRIDAY)
                        : ($dayOfWeek >= Carbon::MONDAY && $dayOfWeek <= Carbon::SATURDAY);

                    $isHoliday = Holiday::where('date', $currentDate->toDateString())->exists();

                    if ($isWorkingDay && !$isHoliday) {
                        $hadir = 0;
                        $izin = 0;
                        $alpha = 0;

                        foreach ($tenagaPendidik as $guru) {
                            $presensi = Presensi::where('user_id', $guru->id)
                                ->whereDate('tanggal', $currentDate)
                                ->first();

                            if ($presensi && $presensi->status === 'hadir') {
                                $hadir++;
                            } elseif ($this->isTeacherIzinForDate($guru, $currentDate, $presensi)) {
                                $izin++;
                            } else {
                                $alpha++;
                            }
                        }

                        $totalHadirBulanan += $hadir;
                        $totalIzinBulanan += $izin;
                        $totalAlphaBulanan += $alpha;
                        $totalPresensiBulanan += ($hadir + $izin + $alpha);
                    }

                    $currentDate->addDay();
                }

                $persentaseBulanan = $totalPresensiBulanan > 0
                    ? (($totalHadirBulanan + $totalIzinBulanan) / $totalPresensiBulanan) * 100
                    : 0;

                $kabupatenBulananData['madrasahs'][] = [
                    'scod' => $madrasah->scod,
                    'nama' => $madrasah->name,
                    'hari_kbm' => $madrasah->hari_kbm,
                    'total_tenaga_pendidik' => $tenagaPendidik->count(),
                    'total_hadir' => $totalHadirBulanan,
                    'total_izin' => $totalIzinBulanan,
                    'total_alpha' => $totalAlphaBulanan,
                    'persentase_kehadiran' => $persentaseBulanan
                ];

                $kabupatenBulananData['total_hadir'] += $totalHadirBulanan;
                $kabupatenBulananData['total_izin'] += $totalIzinBulanan;
                $kabupatenBulananData['total_alpha'] += $totalAlphaBulanan;
                $kabupatenBulananData['total_presensi'] += $totalPresensiBulanan;
            }

            $kabupatenBulananData['persentase_kehadiran'] =
                $kabupatenBulananData['total_presensi'] > 0
                    ? (($kabupatenBulananData['total_hadir'] + $kabupatenBulananData['total_izin']) / $kabupatenBulananData['total_presensi']) * 100
                    : 0;

            $laporanBulananData[] = $kabupatenBulananData;
        }

        return view('backend.presensi_admin.laporan_mingguan', compact(
            'laporanData',
            'laporanBulananData',
            'startOfWeek',
            'startOfMonth',
            'top10Madrasah'
        ));
    }

    // Export Laporan Presensi Mingguan to Excel
    private function exportLaporanMingguan($startOfWeek, $endOfWeek)
    {
        $kabupatenOrder = [
            'Kabupaten Gunungkidul',
            'Kabupaten Bantul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];

        $data = [];

        foreach ($kabupatenOrder as $kabupaten) {
            $madrasahs = \App\Models\Madrasah::where('kabupaten', $kabupaten)
                ->orderByRaw("CAST(scod AS UNSIGNED) ASC")
                ->get();

            // Add kabupaten header
            $data[] = [$kabupaten];

            // Add column headers
            $headers = ['SCOD', 'Nama Sekolah / Madrasah', 'Hari KBM'];
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            foreach ($days as $day) {
                $headers[] = $day . ' - Hadir';
                $headers[] = $day . ' - Izin';
                $headers[] = $day . ' - Alpha';
            }
            $headers[] = 'Persentase Kehadiran (%)';
            $data[] = $headers;

            foreach ($madrasahs as $madrasah) {
                $tenagaPendidik = User::where('role', 'tenaga_pendidik')
                    ->where('madrasah_id', $madrasah->id)
                    ->get();

                $row = [$madrasah->scod, $madrasah->name, $madrasah->hari_kbm];
                $totalHadir = 0;
                $totalIzin = 0;
                $totalPresensi = 0;

                $currentDate = $startOfWeek->copy();
                $daysToCount = $madrasah->hari_kbm == 5 ? 5 : 6; // Jika 5 hari kerja, jangan hitung Sabtu

                for ($i = 0; $i < $daysToCount; $i++) {
                    $isHoliday = Holiday::where('date', $currentDate->toDateString())->exists();

                    if ($isHoliday) {
                        $row[] = '-';
                        $row[] = '-';
                        $row[] = '-';
                    } else {
                        $hadir = 0;
                        $izin = 0;
                        $alpha = 0;

                        foreach ($tenagaPendidik as $guru) {
                            $presensi = Presensi::where('user_id', $guru->id)
                                ->whereDate('tanggal', $currentDate)
                                ->first();

                            if ($presensi && $presensi->status === 'hadir') {
                                $hadir++;
                            } elseif ($this->isTeacherIzinForDate($guru, $currentDate, $presensi)) {
                                $izin++;
                            } else {
                                $alpha++;
                            }
                        }

                        $row[] = $hadir;
                        $row[] = $izin;
                        $row[] = $alpha;

                        $totalHadir += $hadir;
                        $totalIzin += $izin;
                        $totalPresensi += $hadir + $izin + $alpha;
                    }

                    $currentDate->addDay();
                }

                // Jika 5 hari kerja, tambahkan kolom kosong untuk Sabtu
                if ($madrasah->hari_kbm == 5) {
                    $row[] = '-';
                    $row[] = '-';
                    $row[] = '-';
                }

                $persentase = $totalPresensi > 0 ? (($totalHadir + $totalIzin) / $totalPresensi) * 100 : 0;
                $row[] = number_format($persentase, 2) . '%';

                $data[] = $row;
            }

            // Add empty row for separation
            $data[] = [];
        }

        $filename = 'laporan-presensi-mingguan-' . $startOfWeek->format('Y-m-d') . '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new class($data) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return [];
            }
        }, $filename);
    }

    /**
     * Calculate presensi summary metrics based on user role and selected date.
     */
    private function resolveDailyPresensiData(User $teacher, Carbon $selectedDate, ?Presensi $presensi = null): array
    {
        if ($presensi) {
            return [
                'status' => $presensi->status,
                'waktu_masuk' => $presensi->waktu_masuk,
                'waktu_keluar' => $presensi->waktu_keluar,
                'latitude' => $presensi->latitude,
                'longitude' => $presensi->longitude,
                'lokasi' => $presensi->lokasi,
                'keterangan' => $presensi->keterangan,
                'is_fake_location' => $presensi->is_fake_location,
                'accuracy' => $presensi->accuracy,
                'created_at' => $presensi->created_at,
                'face_verified' => $presensi->face_verified,
                'face_similarity_score' => $presensi->face_similarity_score,
                'liveness_score' => $presensi->liveness_score,
            ];
        }

        if (ExternalTeachingPermissionService::hasApprovedNoPresenceDay($teacher, $selectedDate)) {
            return [
                'status' => 'izin',
                'waktu_masuk' => null,
                'waktu_keluar' => null,
                'latitude' => null,
                'longitude' => null,
                'lokasi' => null,
                'keterangan' => ExternalTeachingPermissionService::KETERANGAN_TIDAK_PRESENSI,
                'is_fake_location' => false,
                'accuracy' => null,
                'created_at' => null,
                'face_verified' => false,
                'face_similarity_score' => null,
                'liveness_score' => null,
            ];
        }

        return [
            'status' => 'tidak_hadir',
            'waktu_masuk' => null,
            'waktu_keluar' => null,
            'latitude' => null,
            'longitude' => null,
            'lokasi' => null,
            'keterangan' => null,
            'is_fake_location' => false,
            'accuracy' => null,
            'created_at' => null,
            'face_verified' => false,
            'face_similarity_score' => null,
            'liveness_score' => null,
        ];
    }

    private function isTeacherIzinForDate(User $teacher, Carbon $date, ?Presensi $presensi = null): bool
    {
        if ($presensi && $presensi->status === 'izin') {
            return $presensi->status === 'izin';
        }

        return ExternalTeachingPermissionService::hasApprovedNoPresenceDay($teacher, $date);
    }

    private function calculatePresensiSummary($selectedDate, $user)
    {
        $summary = [
            'users_presensi' => 0,
            'users_izin' => 0,
            'sekolah_presensi' => 0,
            'guru_tidak_presensi' => 0,
        ];

        if (in_array($user->role, ['super_admin', 'pengurus'])) {
            // For super_admin and pengurus: all data
            $hadirUserIds = Presensi::whereDate('tanggal', $selectedDate)
                ->where('status', 'hadir')
                ->distinct()
                ->pluck('user_id');
            $summary['users_presensi'] = $hadirUserIds->count();

            $izinUserIds = Presensi::whereDate('tanggal', $selectedDate)
                ->where('status', 'izin')
                ->distinct()
                ->pluck('user_id');

            $externalTeachingIzinUserIds = User::with('madrasah')
                ->where('role', 'tenaga_pendidik')
                ->get()
                ->filter(fn ($teacher) => ExternalTeachingPermissionService::hasApprovedNoPresenceDay($teacher, $selectedDate))
                ->pluck('id');

            $izinUserIds = $izinUserIds
                ->merge($externalTeachingIzinUserIds)
                ->diff($hadirUserIds)
                ->unique()
                ->values();
            $summary['users_izin'] = $izinUserIds->count();

            $sekolahPresensi = Presensi::whereDate('tanggal', $selectedDate)
                ->join('users', 'presensis.user_id', '=', 'users.id')
                ->join('madrasahs', 'users.madrasah_id', '=', 'madrasahs.id')
                ->distinct('madrasahs.id')
                ->count('madrasahs.id');
            $summary['sekolah_presensi'] = $sekolahPresensi;

            $totalGuru = User::where('role', 'tenaga_pendidik')->count();
            $summary['guru_tidak_presensi'] = $totalGuru - $hadirUserIds->merge($izinUserIds)->unique()->count();
        } else {
            // For admin and tenaga_pendidik kepala: filter by madrasah
            if ($user->madrasah_id) {
                $hadirUserIds = Presensi::whereDate('tanggal', $selectedDate)
                    ->where('status', 'hadir')
                    ->where(function ($q) use ($user) {
                        // Jika presensi.madrasah_id bernilai null, tampilkan data presensi di mana user.madrasah_id == admin.madrasah_id
                        $q->where(function ($subQ) use ($user) {
                            $subQ->whereNull('madrasah_id')
                                 ->whereHas('user', function ($userQ) use ($user) {
                                     $userQ->where('madrasah_id', $user->madrasah_id);
                                 });
                        })
                        // Jika presensi.madrasah_id tidak bernilai null, tampilkan data presensi di mana presensi.madrasah_id == admin.madrasah_id
                        ->orWhere('madrasah_id', $user->madrasah_id);
                    })
                    ->distinct()
                    ->pluck('user_id');
                $summary['users_presensi'] = $hadirUserIds->count();

                $izinUserIds = Presensi::whereDate('tanggal', $selectedDate)
                    ->where('status', 'izin')
                    ->where(function ($q) use ($user) {
                        // Jika presensi.madrasah_id bernilai null, tampilkan data presensi di mana user.madrasah_id == admin.madrasah_id
                        $q->where(function ($subQ) use ($user) {
                            $subQ->whereNull('madrasah_id')
                                 ->whereHas('user', function ($userQ) use ($user) {
                                     $userQ->where('madrasah_id', $user->madrasah_id);
                                 });
                        })
                        // Jika presensi.madrasah_id tidak bernilai null, tampilkan data presensi di mana presensi.madrasah_id == admin.madrasah_id
                        ->orWhere('madrasah_id', $user->madrasah_id);
                    })
                    ->distinct()
                    ->pluck('user_id');

                $externalTeachingIzinUserIds = User::with('madrasah')
                    ->where('role', 'tenaga_pendidik')
                    ->where('madrasah_id', $user->madrasah_id)
                    ->get()
                    ->filter(fn ($teacher) => ExternalTeachingPermissionService::hasApprovedNoPresenceDay($teacher, $selectedDate))
                    ->pluck('id');

                $izinUserIds = $izinUserIds
                    ->merge($externalTeachingIzinUserIds)
                    ->diff($hadirUserIds)
                    ->unique()
                    ->values();
                $summary['users_izin'] = $izinUserIds->count();

                $hasPresensi = Presensi::whereDate('tanggal', $selectedDate)
                    ->where(function ($q) use ($user) {
                        // Jika presensi.madrasah_id bernilai null, tampilkan data presensi di mana user.madrasah_id == admin.madrasah_id
                        $q->where(function ($subQ) use ($user) {
                            $subQ->whereNull('madrasah_id')
                                 ->whereHas('user', function ($userQ) use ($user) {
                                     $userQ->where('madrasah_id', $user->madrasah_id);
                                 });
                        })
                        // Jika presensi.madrasah_id tidak bernilai null, tampilkan data presensi di mana presensi.madrasah_id == admin.madrasah_id
                        ->orWhere('madrasah_id', $user->madrasah_id);
                    })
                    ->exists();
                $summary['sekolah_presensi'] = $hasPresensi ? 1 : 0;

                $totalGuru = User::where('role', 'tenaga_pendidik')
                    ->where('madrasah_id', $user->madrasah_id)
                    ->count();
                $summary['guru_tidak_presensi'] = $totalGuru - $hadirUserIds->merge($izinUserIds)->unique()->count();
            }
        }

        return $summary;
    }

    private function getThreeMonthAbsenceData(Carbon $selectedDate, $user): array
    {
        $monthStarts = collect([
            $selectedDate->copy()->startOfMonth()->subMonths(2),
            $selectedDate->copy()->startOfMonth()->subMonth(),
            $selectedDate->copy()->startOfMonth(),
        ]);

        $usersQuery = User::query()
            ->where('role', 'tenaga_pendidik')
            ->whereNotNull('madrasah_id')
            ->with(['madrasah.yayasan']);

        if (!in_array($user->role, ['super_admin', 'pengurus']) && $user->madrasah_id) {
            $usersQuery->where('madrasah_id', $user->madrasah_id);
        }

        foreach ($monthStarts as $monthStart) {
            $monthEnd = $monthStart->copy()->endOfMonth();

            $usersQuery->whereDoesntHave('presensis', function ($query) use ($monthStart, $monthEnd) {
                $query->whereBetween('tanggal', [$monthStart->toDateString(), $monthEnd->toDateString()])
                    ->where(function ($subQuery) {
                        $subQuery->whereIn('status', ['hadir', 'terlambat'])
                            ->orWhereNotNull('waktu_masuk');
                    });
            });
        }

        $users = $usersQuery
            ->join('madrasahs', 'users.madrasah_id', '=', 'madrasahs.id')
            ->orderByRaw("CAST(madrasahs.scod AS UNSIGNED) ASC")
            ->orderBy('users.name')
            ->select('users.*')
            ->get()
            ->map(function ($teacher) use ($monthStarts) {
                return [
                    'scod' => $teacher->madrasah->scod ?? '-',
                    'name' => $teacher->name,
                    'madrasah' => $teacher->madrasah->name ?? '-',
                    'periode' => $monthStarts
                        ->map(fn ($month) => $month->locale('id')->translatedFormat('F Y'))
                        ->implode(', '),
                ];
            });

        return [
            'rows' => $users,
            'label' => $monthStarts->first()->locale('id')->translatedFormat('F Y') . ' - ' .
                $monthStarts->last()->locale('id')->translatedFormat('F Y'),
        ];
    }

    private function getTeacherAbsenceRecapData(Request $request, $user): array
    {
        $today = Carbon::today('Asia/Jakarta');
        $period = $request->input('absence_recap_period') === 'month' ? 'month' : 'week';

        $selectedWeekValue = $request->input('absence_recap_week', $today->format('o-\WW'));
        if (preg_match('/^(\d{4})-W(\d{2})$/', $selectedWeekValue, $matches)) {
            $startOfWeek = Carbon::now('Asia/Jakarta')
                ->setISODate((int) $matches[1], (int) $matches[2])
                ->startOfWeek(Carbon::MONDAY);
        } else {
            $startOfWeek = $today->copy()->startOfWeek(Carbon::MONDAY);
            $selectedWeekValue = $startOfWeek->format('o-\WW');
        }

        $selectedMonthValue = $request->input('absence_recap_month', $today->format('Y-m'));
        if (preg_match('/^\d{4}-\d{2}$/', $selectedMonthValue)) {
            $startOfMonth = Carbon::createFromFormat('Y-m', $selectedMonthValue, 'Asia/Jakarta')->startOfMonth();
        } else {
            $startOfMonth = $today->copy()->startOfMonth();
            $selectedMonthValue = $startOfMonth->format('Y-m');
        }

        $startDate = $period === 'month'
            ? $startOfMonth->copy()
            : $startOfWeek->copy();
        $endDate = $period === 'month'
            ? $startOfMonth->copy()->endOfMonth()
            : $startOfWeek->copy()->endOfWeek(Carbon::SATURDAY);

        if ($user->role !== 'super_admin') {
            return [
                'rows' => collect(),
                'period' => $period,
                'week_value' => $selectedWeekValue,
                'month_value' => $selectedMonthValue,
                'label' => $startDate->locale('id')->translatedFormat('d F Y') . ' - ' .
                    $endDate->locale('id')->translatedFormat('d F Y'),
                'summary' => [
                    'total_tenaga_pendidik' => 0,
                    'total_tidak_presensi' => 0,
                    'total_hari_tidak_presensi' => 0,
                ],
            ];
        }

        $teachers = User::query()
            ->where('role', 'tenaga_pendidik')
            ->whereNotNull('madrasah_id')
            ->with(['madrasah'])
            ->join('madrasahs', 'users.madrasah_id', '=', 'madrasahs.id')
            ->orderByRaw("CAST(madrasahs.scod AS UNSIGNED) ASC")
            ->orderBy('users.name')
            ->select('users.*')
            ->get();

        $rows = $teachers
            ->map(function ($teacher) use ($startDate, $endDate, $today) {
                $summary = $this->buildTeacherAttendanceSummary(
                    $teacher->id,
                    $teacher->madrasah->hari_kbm ?? null,
                    $startDate->copy(),
                    $endDate->copy(),
                    $today->copy()
                );

                if ($summary['total_belum_hadir'] <= 0) {
                    return null;
                }

                $persentaseTidakPresensi = $summary['total_hari_kerja'] > 0
                    ? round(($summary['total_belum_hadir'] / $summary['total_hari_kerja']) * 100, 1)
                    : 0;

                return [
                    'scod' => $teacher->madrasah->scod ?? '-',
                    'name' => $teacher->name,
                    'madrasah' => $teacher->madrasah->name ?? '-',
                    'hari_kbm' => $teacher->madrasah->hari_kbm ?? '-',
                    'total_hari_kerja' => $summary['total_hari_kerja'],
                    'total_hadir' => $summary['total_hadir'],
                    'total_izin' => $summary['total_izin'],
                    'total_tidak_presensi' => $summary['total_belum_hadir'],
                    'persentase_tidak_presensi' => $persentaseTidakPresensi,
                ];
            })
            ->filter()
            ->values();

        return [
            'rows' => $rows,
            'period' => $period,
            'week_value' => $selectedWeekValue,
            'month_value' => $selectedMonthValue,
            'label' => $startDate->locale('id')->translatedFormat('d F Y') . ' - ' .
                $endDate->locale('id')->translatedFormat('d F Y'),
            'summary' => [
                'total_tenaga_pendidik' => $teachers->count(),
                'total_tidak_presensi' => $rows->count(),
                'total_hari_tidak_presensi' => $rows->sum('total_tidak_presensi'),
            ],
        ];
    }

    private function buildTeacherAttendanceSummary(
        int $userId,
        ?string $hariKbm,
        Carbon $startDate,
        Carbon $endDate,
        Carbon $today
    ): array {
        $effectiveEndDate = $endDate->copy()->min($today);

        if ($effectiveEndDate->lt($startDate)) {
            return [
                'total_hari_kerja' => 0,
                'total_hadir' => 0,
                'total_izin' => 0,
                'total_belum_hadir' => 0,
                'persentase_kehadiran' => 0,
            ];
        }

        $presensiByDate = Presensi::query()
            ->where('user_id', $userId)
            ->whereBetween('tanggal', [$startDate->toDateString(), $effectiveEndDate->toDateString()])
            ->orderBy('tanggal')
            ->get()
            ->groupBy(fn ($item) => $item->tanggal->toDateString());

        $summaryUser = User::with('madrasah')->find($userId);
        $totalHariKerja = 0;
        $totalHadir = 0;
        $totalIzinApproved = 0;

        foreach (CarbonPeriod::create($startDate, $effectiveEndDate) as $date) {
            if (!$this->isAttendanceWorkingDay($date, $hariKbm)) {
                continue;
            }

            $records = $presensiByDate->get($date->toDateString(), collect());
            $isHadir = $records->whereIn('status', ['hadir', 'terlambat'])->isNotEmpty();
            $isIzinApproved = !$isHadir
                && $records->where('status', 'izin')->where('status_izin', 'approved')->isNotEmpty();
            $externalTeachingIzin = (!$isHadir && !$isIzinApproved && $summaryUser)
                ? ExternalTeachingPermissionService::approvedRequestForDate($summaryUser, $date)
                : null;

            $totalHariKerja++;
            if ($isHadir) {
                $totalHadir++;
            } elseif ($isIzinApproved || $externalTeachingIzin) {
                $totalIzinApproved++;
            }
        }

        $totalDasarPersentase = max($totalHariKerja - $totalIzinApproved, 0);

        return [
            'total_hari_kerja' => $totalHariKerja,
            'total_hadir' => $totalHadir,
            'total_izin' => $totalIzinApproved,
            'total_belum_hadir' => max($totalHariKerja - $totalHadir - $totalIzinApproved, 0),
            'persentase_kehadiran' => $totalDasarPersentase > 0 ? round(($totalHadir / $totalDasarPersentase) * 100, 1) : 0,
        ];
    }

    private function isAttendanceWorkingDay(Carbon $date, ?string $hariKbm): bool
    {
        if ($date->isSunday() || Holiday::isHoliday($date->toDateString())) {
            return false;
        }

        if ((string) $hariKbm === '5' && $date->isSaturday()) {
            return false;
        }

        return true;
    }
}
