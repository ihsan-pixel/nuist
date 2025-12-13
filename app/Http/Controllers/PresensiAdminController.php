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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PresensiPerBulanExport;
use App\Exports\PresensiSemuaExport;

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
                    $presensiData[] = [
                        'user_id' => $tp->id,
                        'nama' => $tp->name,
                        'status' => $presensi ? $presensi->status : 'tidak_hadir',
                        'waktu_masuk' => $presensi ? $presensi->waktu_masuk : null,
                        'waktu_keluar' => $presensi ? $presensi->waktu_keluar : null,
                        'keterangan' => $presensi ? $presensi->keterangan : null,
                        'is_fake_location' => $presensi ? $presensi->is_fake_location : false,
                    ];
                }

                $madrasahData[] = [
                    'madrasah' => $madrasah,
                    'presensi' => $presensiData,
                ];
            }
            }

            return view('presensi_admin.index', compact('madrasahData', 'user', 'selectedDate', 'summary'));
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
            })->with('madrasah')->get();

            return view('presensi_admin.index', compact('presensis', 'belumPresensi', 'user', 'selectedDate', 'summary'));
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
                    $presensiData[] = [
                        'user_id' => $tp->id,
                        'nama' => $tp->name,
                        'status' => $presensi ? $presensi->status : 'tidak_hadir',
                        'waktu_masuk' => $presensi ? $presensi->waktu_masuk : null,
                        'waktu_keluar' => $presensi ? $presensi->waktu_keluar : null,
                        'keterangan' => $presensi ? $presensi->keterangan : null,
                        'is_fake_location' => $presensi ? $presensi->is_fake_location : false,
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
                $presensiData[] = [
                    'user_id' => $tp->id,
                    'nama' => $tp->name,
                    'status' => $presensi ? $presensi->status : 'tidak_hadir',
                    'waktu_masuk' => $presensi ? $presensi->waktu_masuk : null,
                    'waktu_keluar' => $presensi ? $presensi->waktu_keluar : null,
                    'keterangan' => $presensi ? $presensi->keterangan : null,
                    'is_fake_location' => $presensi ? $presensi->is_fake_location : false,
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

        $tenagaPendidikData = $tenagaPendidik->map(function($tp) {
            $presensi = $tp->presensis->first();
            return [
                'id' => $tp->id,
                'nama' => $tp->name,
                'nip' => $tp->nip,
                'nuptk' => $tp->nuptk,
                'status_kepegawaian' => $tp->statusKepegawaian ? $tp->statusKepegawaian->name : '-',
                'status' => $presensi ? $presensi->status : 'tidak_hadir',
                'waktu_masuk' => $presensi && $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i:s') : null,
                'waktu_keluar' => $presensi && $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i:s') : null,
                'latitude' => $presensi ? $presensi->latitude : null,
                'longitude' => $presensi ? $presensi->longitude : null,
                'lokasi' => $presensi ? $presensi->lokasi : null,
                'keterangan' => $presensi ? $presensi->keterangan : null,
                'is_fake_location' => $presensi ? $presensi->is_fake_location : false,
                'accuracy' => $presensi ? $presensi->accuracy : null,
                'created_at' => $presensi ? $presensi->created_at->format('Y-m-d H:i:s') : null,
                'face_verified' => $presensi ? $presensi->face_verified : false,
                'face_similarity_score' => $presensi ? $presensi->face_similarity_score : null,
                'liveness_score' => $presensi ? $presensi->liveness_score : null,
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
    // Show all rows on a single page by setting perPage to the total matched records
    // This preserves the Paginator API used in the view (firstItem(), total(), etc.)

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

        $tenagaPendidikData = $tenagaPendidik->map(function($tp) {
            $presensi = $tp->presensis->first();
            return [
                'id' => $tp->id,
                'nama' => $tp->name,
                'nip' => $tp->nip,
                'nuptk' => $tp->nuptk,
                'status_kepegawaian' => $tp->statusKepegawaian ? $tp->statusKepegawaian->name : '-',
                'status' => $presensi ? $presensi->status : 'tidak_hadir',
                'waktu_masuk' => $presensi && $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i:s') : null,
                'waktu_keluar' => $presensi && $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i:s') : null,
                'latitude' => $presensi ? $presensi->latitude : null,
                'longitude' => $presensi ? $presensi->longitude : null,
                'lokasi' => $presensi ? $presensi->lokasi : null,
                'keterangan' => $presensi ? $presensi->keterangan : null,
                'is_fake_location' => $presensi ? $presensi->is_fake_location : false,
                'accuracy' => $presensi ? $presensi->accuracy : null,
                'created_at' => $presensi ? $presensi->created_at->format('Y-m-d H:i:s') : null,
                'face_verified' => $presensi ? $presensi->face_verified : false,
                'face_similarity_score' => $presensi ? $presensi->face_similarity_score : null,
                'liveness_score' => $presensi ? $presensi->liveness_score : null,
            ];
        });

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
            'selectedDate', 'user', 'search', 'bulanTersedia'
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
                    $data[] = [
                        'Madrasah' => $madrasah->name,
                        'SCOD' => $madrasah->scod,
                        'Kabupaten' => $madrasah->kabupaten,
                        'Nama Guru' => $tp->name,
                        'Status Kepegawaian' => $tp->statusKepegawaian->name ?? '-',
                        'NIP' => $tp->nip,
                        'NUPTK' => $tp->nuptk,
                        'Status Presensi' => $presensi ? $presensi->status : 'tidak_hadir',
                        'Waktu Masuk' => $presensi && $presensi->waktu_masuk ? $presensi->waktu_masuk->format('H:i:s') : null,
                        'Waktu Keluar' => $presensi && $presensi->waktu_keluar ? $presensi->waktu_keluar->format('H:i:s') : null,
                        'Keterangan' => $presensi ? $presensi->keterangan : null,
                        'Lokasi' => $presensi ? $presensi->lokasi : null,
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

        // Only super_admin can access this
        if ($user->role !== 'super_admin') {
            abort(403, 'Unauthorized');
        }

        // Get week parameter or default to current week
        $week = $request->input('week', now()->format('Y-W'));
        $startOfWeek = \Carbon\Carbon::createFromFormat('Y-W', $week)->startOfWeek(\Carbon\Carbon::MONDAY);
        $endOfWeek = $startOfWeek->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);

        // Handle export
        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportLaporanMingguan($startOfWeek, $endOfWeek);
        }

        // Get all madrasah ordered by kabupaten
        $kabupatenOrder = [
            'Kabupaten Gunungkidul',
            'Kabupaten Bantul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];

        $laporanData = [];

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
                $totalHadirMadrasah = 0;
                $totalIzinMadrasah = 0;
                $totalAlphaMadrasah = 0;
                $totalPresensiMadrasah = 0;

                // Loop through each day of the week (Monday to Saturday)
                $currentDate = $startOfWeek->copy();
                for ($i = 0; $i < 6; $i++) {
                    $hadir = 0;
                    $izin = 0;
                    $alpha = 0;

                    foreach ($tenagaPendidik as $guru) {
                        $presensi = Presensi::where('user_id', $guru->id)
                            ->whereDate('tanggal', $currentDate)
                            ->first();

                        if ($presensi) {
                            if ($presensi->status === 'hadir') {
                                $hadir++;
                            } elseif ($presensi->status === 'izin') {
                                $izin++;
                            } else {
                                $alpha++;
                            }
                        } else {
                            $alpha++;
                        }
                    }

                    $presensiMingguan[] = [
                        'hadir' => $hadir,
                        'izin' => $izin,
                        'alpha' => $alpha
                    ];

                    $totalHadirMadrasah += $hadir;
                    $totalIzinMadrasah += $izin;
                    $totalAlphaMadrasah += $alpha;
                    $totalPresensiMadrasah += $hadir + $izin + $alpha;

                    $currentDate->addDay();
                }

                $persentaseKehadiran = $totalPresensiMadrasah > 0 ? ($totalHadirMadrasah / $totalPresensiMadrasah) * 100 : 0;

                $kabupatenData['madrasahs'][] = [
                    'scod' => $madrasah->scod,
                    'nama' => $madrasah->name,
                    'hari_kbm' => $madrasah->hari_kbm,
                    'presensi' => $presensiMingguan,
                    'persentase_kehadiran' => $persentaseKehadiran
                ];

                $kabupatenData['total_hadir'] += $totalHadirMadrasah;
                $kabupatenData['total_izin'] += $totalIzinMadrasah;
                $kabupatenData['total_alpha'] += $totalAlphaMadrasah;
                $kabupatenData['total_presensi'] += $totalPresensiMadrasah;
            }

            $kabupatenData['persentase_kehadiran'] = $kabupatenData['total_presensi'] > 0
                ? ($kabupatenData['total_hadir'] / $kabupatenData['total_presensi']) * 100
                : 0;

            $laporanData[] = $kabupatenData;
        }

        return view('backend.presensi_admin.laporan_mingguan', compact('laporanData', 'startOfWeek'));
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
                $totalPresensi = 0;

                $currentDate = $startOfWeek->copy();
                for ($i = 0; $i < 6; $i++) {
                    $hadir = 0;
                    $izin = 0;
                    $alpha = 0;

                    foreach ($tenagaPendidik as $guru) {
                        $presensi = Presensi::where('user_id', $guru->id)
                            ->whereDate('tanggal', $currentDate)
                            ->first();

                        if ($presensi) {
                            if ($presensi->status === 'hadir') {
                                $hadir++;
                            } elseif ($presensi->status === 'izin') {
                                $izin++;
                            } else {
                                $alpha++;
                            }
                        } else {
                            $alpha++;
                        }
                    }

                    $row[] = $hadir;
                    $row[] = $izin;
                    $row[] = $alpha;

                    $totalHadir += $hadir;
                    $totalPresensi += $hadir + $izin + $alpha;

                    $currentDate->addDay();
                }

                $persentase = $totalPresensi > 0 ? ($totalHadir / $totalPresensi) * 100 : 0;
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
            $hadirUsers = Presensi::whereDate('tanggal', $selectedDate)
                ->where('status', 'hadir')
                ->distinct('user_id')
                ->count('user_id');
            $summary['users_presensi'] = $hadirUsers;

            $izinUsers = Presensi::whereDate('tanggal', $selectedDate)
                ->where('status', 'izin')
                ->distinct('user_id')
                ->count('user_id');
            $summary['users_izin'] = $izinUsers;

            $sekolahPresensi = Presensi::whereDate('tanggal', $selectedDate)
                ->join('users', 'presensis.user_id', '=', 'users.id')
                ->join('madrasahs', 'users.madrasah_id', '=', 'madrasahs.id')
                ->distinct('madrasahs.id')
                ->count('madrasahs.id');
            $summary['sekolah_presensi'] = $sekolahPresensi;

            $totalGuru = User::where('role', 'tenaga_pendidik')->count();
            $summary['guru_tidak_presensi'] = $totalGuru - ($hadirUsers + $izinUsers);
        } else {
            // For admin and tenaga_pendidik kepala: filter by madrasah
            if ($user->madrasah_id) {
                $hadirUsers = Presensi::whereDate('tanggal', $selectedDate)
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
                    ->distinct('user_id')
                    ->count('user_id');
                $summary['users_presensi'] = $hadirUsers;

                $izinUsers = Presensi::whereDate('tanggal', $selectedDate)
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
                    ->distinct('user_id')
                    ->count('user_id');
                $summary['users_izin'] = $izinUsers;

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
                $summary['guru_tidak_presensi'] = $totalGuru - ($hadirUsers + $izinUsers);
            }
        }

        return $summary;
    }
}
