<?php

namespace App\Http\Controllers\Mobile\Pengurus;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Madrasah;
use App\Models\DataSekolah;
use App\Models\User;
use App\Models\PPDBSetting;
use App\Models\StatusKepegawaian;
use App\Models\Presensi;
use App\Models\Holiday;
use App\Models\TeachingAttendance;
use App\Models\TeachingSchedule;

class SekolahController extends \App\Http\Controllers\Controller
{
    /**
     * Get jumlah siswa from data_sekolah based on latest year
     */
    private function getJumlahSiswa($madrasahId)
    {
        $dataSekolah = DataSekolah::where('madrasah_id', $madrasahId)
            ->orderBy('tahun', 'desc')
            ->first();

        return $dataSekolah ? $dataSekolah->jumlah_siswa : 0;
    }

    /**
     * Get jumlah guru from users table (users with madrasah_id and role tenaga_pendidik)
     */
    private function getJumlahGuru($madrasahId)
    {
        return User::where('madrasah_id', $madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->count();
    }

    /**
     * Get jumlah guru total from all schools
     */
    private function getTotalGuru()
    {
        return User::where('role', 'tenaga_pendidik')
            ->whereNotNull('madrasah_id')
            ->count();
    }

    /**
     * Get jumlah siswa total from all schools
     */
    private function getTotalSiswa()
    {
        $latestData = DataSekolah::select('madrasah_id', 'jumlah_siswa')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('data_sekolah')
                    ->groupBy('madrasah_id');
            });

        return $latestData->sum('jumlah_siswa');
    }

    /**
     * Get jumlah jurusan from ppdb_settings table
     */
    private function getJumlahJurusan($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        if ($ppdbSetting && $ppdbSetting->jurusan && is_array($ppdbSetting->jurusan)) {
            return count($ppdbSetting->jurusan);
        }

        return 0;
    }

    /**
     * Get jumlah sarana from ppdb_settings table (fasilitas column - count objects with name)
     */
    private function getJumlahSarana($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        if ($ppdbSetting && $ppdbSetting->fasilitas && is_array($ppdbSetting->fasilitas)) {
            $fasilitasWithName = array_filter($ppdbSetting->fasilitas, function($item) {
                return is_array($item) && isset($item['name']) && !empty($item['name']);
            });
            return count($fasilitasWithName);
        }

        return 0;
    }

    /**
     * Get fasilitas list from ppdb_settings table
     */
    private function getFasilitas($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        if ($ppdbSetting && $ppdbSetting->fasilitas && is_array($ppdbSetting->fasilitas)) {
            $fasilitasList = array_filter($ppdbSetting->fasilitas, function($item) {
                return is_array($item) && isset($item['name']) && !empty($item['name']);
            });
            return array_values($fasilitasList);
        }

        return [];
    }

    /**
     * Get ppdb_setting for current year
     */
    private function getPpdbSetting($madrasahId)
    {
        return PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();
    }

    /**
     * Get tahun_berdiri from ppdb_settings
     */
    private function getTahunBerdiri($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        return $ppdbSetting && $ppdbSetting->tahun_berdiri ? $ppdbSetting->tahun_berdiri : null;
    }

    /**
     * Get akreditasi from ppdb_settings
     */
    private function getAkreditasi($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        return $ppdbSetting && $ppdbSetting->akreditasi ? $ppdbSetting->akreditasi : null;
    }

    /**
     * Get telepon from ppdb_settings
     */
    private function getTelepon($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        return $ppdbSetting && $ppdbSetting->telepon ? $ppdbSetting->telepon : null;
    }

    /**
     * Get email from ppdb_settings
     */
    private function getEmail($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        return $ppdbSetting && $ppdbSetting->email ? $ppdbSetting->email : null;
    }

    /**
     * Get website from ppdb_settings
     */
    private function getWebsite($madrasahId)
    {
        $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasahId)
            ->where('tahun', now()->year)
            ->first();

        return $ppdbSetting && $ppdbSetting->website ? $ppdbSetting->website : null;
    }

    /**
     * Check if user is kepala sekolah based on ketugasan column
     */
    private function isKepalaSekolah($user)
    {
        if (!$user->ketugasan) {
            return false;
        }

        $ketugasanLower = strtolower(trim($user->ketugasan));

        // Check for various patterns of kepala madrasah/sekolah
        return $ketugasanLower === 'kepala madrasah' ||
               $ketugasanLower === 'kepala sekolah' ||
               strpos($ketugasanLower, 'kepala') !== false;
    }

    /**
     * Get list of tenaga pendidik with their status kepegawaian
     * Kepala sekolah will be sorted to the top based on ketugasan column
     */
    private function getTenagaPendidik($madrasahId)
    {
        $tenagaPendidik = User::where('madrasah_id', $madrasahId)
            ->where('role', 'tenaga_pendidik')
            ->with('statusKepegawaian')
            ->get();

        // Sort: kepala sekolah first (based on ketugasan column), then by name
        $tenagaPendidik = $tenagaPendidik->sortByDesc(function($user) {
            return $this->isKepalaSekolah($user) ? 1 : 0;
        });

        return $tenagaPendidik->values();
    }

    /**
     * Menampilkan daftar sekolah (madrasah)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        $search = $request->get('search', '');

        $query = Madrasah::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('kabupaten', 'like', '%' . $search . '%')
                  ->orWhere('alamat', 'like', '%' . $search . '%');
            });
        }

        $madrasahs = $query->orderBy('scod', 'asc')
                          ->paginate(10);

        $totalSekolah = Madrasah::count();
        $sekolahAktif = $totalSekolah;
        $totalGuru = $this->getTotalGuru();
        $totalSiswa = $this->getTotalSiswa();

        $jumlahSiswaData = [];
        foreach ($madrasahs as $madrasah) {
            $jumlahSiswaData[$madrasah->id] = $this->getJumlahSiswa($madrasah->id);
        }

        return view('mobile.pengurus.sekolah', compact(
            'madrasahs', 'search', 'totalSekolah', 'sekolahAktif', 'totalGuru', 'totalSiswa', 'jumlahSiswaData'
        ));
    }

    /**
     * Menampilkan detail sekolah
     */
    public function show(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        $madrasah = Madrasah::findOrFail($id);

        $dataSekolah = DataSekolah::where('madrasah_id', $id)
            ->orderBy('tahun', 'desc')
            ->first();

        $jumlahGuru = $this->getJumlahGuru($id);
        $jumlahSiswa = $this->getJumlahSiswa($id);
        $jumlahJurusan = $this->getJumlahJurusan($id);
        $jumlahSarana = $this->getJumlahSarana($id);
        $fasilitasList = $this->getFasilitas($id);
        $ppdbSetting = $this->getPpdbSetting($id);
        $tahunBerdiri = $this->getTahunBerdiri($id);
        $akreditasi = $this->getAkreditasi($id);
        $telepon = $this->getTelepon($id);
        $email = $this->getEmail($id);
        $website = $this->getWebsite($id);
        $tenagaPendidik = $this->getTenagaPendidik($id);

        // Get selected month or default to current month
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $monthlyAttendance = $this->getMonthlyAttendanceSummary($id, $selectedMonth);

        // Get teaching attendance data
        $teachingAttendance = $this->getTeachingAttendanceSummary($id, $selectedMonth);

        return view('mobile.pengurus.sekolah-detail', compact(
            'madrasah', 'dataSekolah', 'jumlahGuru', 'jumlahSiswa',
            'jumlahJurusan', 'jumlahSarana', 'fasilitasList', 'ppdbSetting',
            'tahunBerdiri', 'akreditasi', 'telepon', 'email', 'website',
            'tenagaPendidik', 'monthlyAttendance', 'selectedMonth',
            'teachingAttendance'
        ));
    }

    /**
     * API endpoint to get monthly attendance data for AJAX requests
     */
    public function getMonthlyAttendanceData(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $madrasah = Madrasah::findOrFail($id);

        // Get selected month or default to current month
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $monthlyAttendance = $this->getMonthlyAttendanceSummary($id, $selectedMonth);

        return response()->json($monthlyAttendance);
    }

    /**
     * Get monthly attendance summary for teachers in a specific school
     */
    private function getMonthlyAttendanceSummary($madrasahId, $month = null, $year = null)
    {
        // Get selected month or current month
        $selectedMonth = $month ? Carbon::createFromFormat('Y-m', $month) : Carbon::now();
        $year = $year ?: $selectedMonth->year;
        $month = $selectedMonth->month;

        // Get madrasah to determine hari_kbm
        $madrasah = Madrasah::find($madrasahId);
        $hariKbm = $madrasah ? $madrasah->hari_kbm : 5; // Default to 5 if not set

        $tenagaPendidik = User::where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $madrasahId)
            ->get();

        $monthlyData = [];
        $totalHadir = 0;
        $totalIzin = 0;
        $totalAlpha = 0;
        $totalWorkingDays = 0;

        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $currentDate = $startOfMonth->copy();

        while ($currentDate <= $endOfMonth) {
            $dayOfWeek = $currentDate->dayOfWeek; // 0=Sunday, 1=Monday, ..., 6=Saturday
            $isHoliday = Holiday::where('date', $currentDate->toDateString())->exists();

            // Check if it's a working day based on hari_kbm
            $isWorkingDay = false;
            if ($hariKbm == 5) {
                $isWorkingDay = ($dayOfWeek >= 1 && $dayOfWeek <= 5); // Monday to Friday
            } elseif ($hariKbm == 6) {
                $isWorkingDay = ($dayOfWeek >= 1 && $dayOfWeek <= 6); // Monday to Saturday
            }

            $dayName = $currentDate->locale('id')->isoFormat('dddd');

            $dayData = [
                'date' => $currentDate->toDateString(),
                'day_name' => $dayName,
                'is_holiday' => $isHoliday,
                'is_working_day' => $isWorkingDay && !$isHoliday,
                'hadir' => 0,
                'izin' => 0,
                'alpha' => 0
            ];

            if ($isWorkingDay && !$isHoliday) {
                $totalWorkingDays++;
                foreach ($tenagaPendidik as $guru) {
                    $presensi = Presensi::where('user_id', $guru->id)
                        ->whereDate('tanggal', $currentDate)
                        ->first();

                    if ($presensi) {
                        if ($presensi->status === 'hadir') {
                            $dayData['hadir']++;
                            $totalHadir++;
                        } elseif ($presensi->status === 'izin') {
                            $dayData['izin']++;
                            $totalIzin++;
                        } else {
                            $dayData['alpha']++;
                            $totalAlpha++;
                        }
                    } else {
                        $dayData['alpha']++;
                        $totalAlpha++;
                    }
                }
            }

            $monthlyData[] = $dayData;
            $currentDate->addDay();
        }

        $totalPresensi = $totalHadir + $totalIzin + $totalAlpha;
        $persentaseKehadiran = $totalPresensi > 0 ? round(($totalHadir / $totalPresensi) * 100, 1) : 0;

        // Get available months for history
        $availableMonths = DB::table('presensis')
            ->join('users', 'presensis.user_id', '=', 'users.id')
            ->selectRaw("DISTINCT DATE_FORMAT(presensis.tanggal, '%Y-%m') as month_year, DATE_FORMAT(presensis.tanggal, '%M %Y') as month_name")
            ->where(function ($q) use ($madrasahId) {
                $q->where('presensis.madrasah_id', $madrasahId)
                  ->orWhere(function ($subQ) use ($madrasahId) {
                      $subQ->whereNull('presensis.madrasah_id')
                           ->where('users.madrasah_id', $madrasahId)
                           ->where('users.role', 'tenaga_pendidik');
                  });
            })
            ->orderBy('month_year', 'desc')
            ->get();

        return [
            'monthly_data' => $monthlyData,
            'summary' => [
                'total_guru' => $tenagaPendidik->count(),
                'total_hadir' => $totalHadir,
                'total_izin' => $totalIzin,
                'total_alpha' => $totalAlpha,
                'total_working_days' => $totalWorkingDays,
                'persentase_kehadiran' => $persentaseKehadiran,
                'hari_kbm' => $hariKbm
            ],
            'month_info' => [
                'current_month' => $selectedMonth->format('Y-m'),
                'month_name' => $selectedMonth->locale('id')->isoFormat('MMMM YYYY'),
                'available_months' => $availableMonths
            ]
        ];
    }

    /**
     * API endpoint to get monthly teaching attendance data for AJAX requests
     */
    public function getMonthlyTeachingAttendanceData(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $madrasah = Madrasah::findOrFail($id);

        // Get selected month or default to current month
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $teachingAttendance = $this->getTeachingAttendanceSummary($id, $selectedMonth);

        return response()->json($teachingAttendance);
    }

    /**
     * Get teaching attendance summary for a specific school
     */
    private function getTeachingAttendanceSummary($madrasahId, $month = null, $year = null)
    {
        // Get selected month or current month
        $selectedMonth = $month ? Carbon::createFromFormat('Y-m', $month) : Carbon::now();
        $year = $year ?: $selectedMonth->year;
        $month = $selectedMonth->month;

        // Get madrasah to determine hari_kbm
        $madrasah = Madrasah::find($madrasahId);
        $hariKbm = $madrasah ? $madrasah->hari_kbm : 5;

        // Get teaching schedules for this school
        $teachingSchedules = TeachingSchedule::where('school_id', $madrasahId)->get();

        // Get all teachers in this school
        $teacherIds = $teachingSchedules->pluck('teacher_id')->unique()->filter();
        $tenagaPendidik = User::whereIn('id', $teacherIds)->get();

        // Get teaching attendances for the selected month
        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth()->endOfDay();

        $teachingAttendances = TeachingAttendance::with(['teachingSchedule', 'teachingSchedule.teacher', 'user'])
            ->whereHas('teachingSchedule', function($q) use ($madrasahId) {
                $q->where('school_id', $madrasahId);
            })
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu', 'asc')
            ->get();

        // Group attendances by date
        $attendancesByDate = $teachingAttendances->groupBy(function($item) {
            return Carbon::parse($item->tanggal)->toDateString();
        });

        // Get available months for history (from teaching attendances)
        $availableMonths = DB::table('teaching_attendances')
            ->join('teaching_schedules', 'teaching_attendances.teaching_schedule_id', '=', 'teaching_schedules.id')
            ->selectRaw("DISTINCT DATE_FORMAT(teaching_attendances.tanggal, '%Y-%m') as month_year, DATE_FORMAT(teaching_attendances.tanggal, '%M %Y') as month_name")
            ->where('teaching_schedules.school_id', $madrasahId)
            ->orderBy('month_year', 'desc')
            ->get();

        // Calculate summary statistics
        $totalScheduledClasses = 0;
        $totalConductedClasses = 0;
        $totalAttendanceRecords = $teachingAttendances->count();

        // Count scheduled classes per day
        $scheduledByDate = [];
        foreach ($teachingSchedules as $schedule) {
            $dayOfWeek = $schedule->day;
            $startOfMonthDay = $startOfMonth->copy();
            $endOfMonthDay = $endOfMonth->copy();

            $currentDate = $startOfMonthDay->copy();
            while ($currentDate <= $endOfMonthDay) {
                if ($currentDate->dayOfWeek == $dayOfWeek) {
                    $dateStr = $currentDate->toDateString();
                    if (!isset($scheduledByDate[$dateStr])) {
                        $scheduledByDate[$dateStr] = 0;
                    }
                    $scheduledByDate[$dateStr]++;
                    $totalScheduledClasses++;
                }
                $currentDate->addDay();
            }
        }

        // Build monthly data
        $monthlyData = [];
        $currentDate = $startOfMonth->copy();

        while ($currentDate <= $endOfMonth) {
            $dayOfWeek = $currentDate->dayOfWeek;
            $dateStr = $currentDate->toDateString();
            $dayName = $currentDate->locale('id')->isoFormat('dddd');
            $isHoliday = Holiday::where('date', $dateStr)->exists();

            // Check if it's a working day
            $isWorkingDay = false;
            if ($hariKbm == 5) {
                $isWorkingDay = ($dayOfWeek >= 1 && $dayOfWeek <= 5);
            } elseif ($hariKbm == 6) {
                $isWorkingDay = ($dayOfWeek >= 1 && $dayOfWeek <= 6);
            }

            // Get attendances for this date
            $dateAttendances = $attendancesByDate->get($dateStr, collect([]));

            // Get daily teacher attendances
            $dailyTeachers = [];
            foreach ($dateAttendances as $attendance) {
                $teacherName = $attendance->user ? $attendance->user->name :
                               ($attendance->teachingSchedule && $attendance->teachingSchedule->teacher ?
                                $attendance->teachingSchedule->teacher->name : '-');

                $subject = $attendance->teachingSchedule ? $attendance->teachingSchedule->subject : '-';
                $startTime = $attendance->teachingSchedule ? $attendance->teachingSchedule->start_time : '-';
                $endTime = $attendance->teachingSchedule ? $attendance->teachingSchedule->end_time : '-';

                $dailyTeachers[] = [
                    'teacher_name' => $teacherName,
                    'subject' => $subject,
                    'time' => $startTime . ' - ' . $endTime,
                    'status' => $attendance->status ?? 'hadir'
                ];
            }

            $dayData = [
                'date' => $dateStr,
                'day_name' => $dayName,
                'is_holiday' => $isHoliday,
                'is_working_day' => $isWorkingDay && !$isHoliday,
                'total_scheduled' => $scheduledByDate[$dateStr] ?? 0,
                'total_conducted' => $dateAttendances->count(),
                'teachers' => $dailyTeachers
            ];

            if ($dateAttendances->count() > 0) {
                $totalConductedClasses += $dateAttendances->count();
            }

            $monthlyData[] = $dayData;
            $currentDate->addDay();
        }

        $persentasePelaksanaan = $totalScheduledClasses > 0 ? round(($totalAttendanceRecords / $totalScheduledClasses) * 100, 1) : 0;

        return [
            'monthly_data' => $monthlyData,
            'summary' => [
                'total_teachers' => $tenagaPendidik->count(),
                'total_scheduled_classes' => $totalScheduledClasses,
                'total_conducted_classes' => $totalAttendanceRecords,
                'total_working_days' => collect($monthlyData)->where('is_working_day', true)->count(),
                'persentase_pelaksanaan' => $persentasePelaksanaan,
                'hari_kbm' => $hariKbm
            ],
            'month_info' => [
                'current_month' => $selectedMonth->format('Y-m'),
                'month_name' => $selectedMonth->locale('id')->isoFormat('MMMM YYYY'),
                'available_months' => $availableMonths
            ],
            'teachers_list' => $tenagaPendidik->map(function($teacher) {
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'avatar' => $teacher->avatar
                ];
            })->toArray()
        ];
    }

    /**
     * Menampilkan halaman kelengkapan data sekolah
     */
    public function kelengkapanData(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'pengurus') {
            abort(403, 'Unauthorized.');
        }

        $search = $request->get('search', '');
        $kabupaten = $request->get('kabupaten', '');

        $query = Madrasah::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('kabupaten', 'like', '%' . $search . '%')
                  ->orWhere('alamat', 'like', '%' . $search . '%');
            });
        }

        if ($kabupaten) {
            $query->where('kabupaten', $kabupaten);
        }

        $madrasahs = $query->orderBy('scod', 'asc')
                          ->paginate(10);

        // Hitung kelengkapan data untuk setiap sekolah
        $madrasahs->getCollection()->transform(function ($madrasah) {
            // ========== 1. Kelengkapan Data Madrasah ==========
            $madrasahFields = ['alamat', 'logo', 'latitude', 'longitude', 'map_link', 'polygon_koordinat', 'hari_kbm', 'scod'];
            $madrasahFilled = 0;
            foreach ($madrasahFields as $field) {
                if (!is_null($madrasah->$field)) {
                    $madrasahFilled++;
                }
            }
            $madrasah->completeness_percentage = round(($madrasahFilled / count($madrasahFields)) * 100);

            // ========== 2. Persentase Presensi Kehadiran ==========
            $tenagaPendidik = User::where('madrasah_id', $madrasah->id)
                ->where('role', 'tenaga_pendidik')
                ->pluck('id');

            if ($tenagaPendidik->count() > 0) {
                $currentMonth = now()->month;
                $currentYear = now()->year;
                $startOfMonth = Carbon::create($currentYear, $currentMonth, 1);
                $endOfMonth = $startOfMonth->copy()->endOfMonth();

                $hariKbm = $madrasah->hari_kbm ?? 5;
                $workingDays = 0;
                $tempDate = $startOfMonth->copy();

                while ($tempDate <= $endOfMonth) {
                    $dayOfWeek = $tempDate->dayOfWeek;
                    $isWorkingDay = false;
                    if ($hariKbm == 5) {
                        $isWorkingDay = ($dayOfWeek >= 1 && $dayOfWeek <= 5);
                    } elseif ($hariKbm == 6) {
                        $isWorkingDay = ($dayOfWeek >= 1 && $dayOfWeek <= 6);
                    }
                    if ($isWorkingDay && !Holiday::where('date', $tempDate->toDateString())->exists()) {
                        $workingDays++;
                    }
                    $tempDate->addDay();
                }

                $totalExpectedPresensi = $tenagaPendidik->count() * $workingDays;
                $totalActualPresensi = Presensi::whereIn('user_id', $tenagaPendidik)
                    ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                    ->count();

                $madrasah->presensi_kehadiran_percentage = $totalExpectedPresensi > 0
                    ? round(($totalActualPresensi / $totalExpectedPresensi) * 100, 1)
                    : 0;
                $madrasah->presensi_kehadiran_details = [
                    'total_guru' => $tenagaPendidik->count(),
                    'working_days' => $workingDays,
                    'total_presensi' => $totalActualPresensi,
                    'expected_presensi' => $totalExpectedPresensi
                ];
            } else {
                $madrasah->presensi_kehadiran_percentage = 0;
                $madrasah->presensi_kehadiran_details = [
                    'total_guru' => 0,
                    'working_days' => 0,
                    'total_presensi' => 0,
                    'expected_presensi' => 0
                ];
            }

            // ========== 3. Persentase Presensi Mengajar ==========
            $teachingSchedules = TeachingSchedule::where('school_id', $madrasah->id)->count();
            if ($teachingSchedules > 0) {
                $startOfMonth = Carbon::create($currentYear, $currentMonth, 1)->startOfDay();
                $endOfMonth = Carbon::create($currentYear, $currentMonth, 1)->endOfMonth()->endOfDay();

                $totalTeachingAttendances = TeachingAttendance::whereHas('teachingSchedule', function($q) use ($madrasah) {
                    $q->where('school_id', $madrasah->id);
                })->whereBetween('tanggal', [$startOfMonth, $endOfMonth])->count();

                // Estimasi: jumlah jadwal x rata-rata pertemuan per bulan (4 minggu x hari KBM)
                $hariKbm = $madrasah->hari_kbm ?? 5;
                $estimatedMeetings = $teachingSchedules * 4 * $hariKbm;

                $madrasah->presensi_mengajar_percentage = $estimatedMeetings > 0
                    ? round(($totalTeachingAttendances / $estimatedMeetings) * 100, 1)
                    : 0;
                $madrasah->presensi_mengajar_details = [
                    'total_jadwal' => $teachingSchedules,
                    'total_presensi_mengajar' => $totalTeachingAttendances,
                    'estimated_meetings' => $estimatedMeetings
                ];
            } else {
                $madrasah->presensi_mengajar_percentage = 0;
                $madrasah->presensi_mengajar_details = [
                    'total_jadwal' => 0,
                    'total_presensi_mengajar' => 0,
                    'estimated_meetings' => 0
                ];
            }

            // ========== 4. Persentase Pengisian Laporan Akhir Tahun ==========
            $laporanFields = ['deskripsi_singkat', 'sejarah', 'visi', 'misi', 'keunggulan', 'fasilitas', 'program_unggulan', 'ekstrakurikuler', 'prestasi'];
            $laporanFilled = 0;
            foreach ($laporanFields as $field) {
                if (!is_null($madrasah->$field) && !empty($madrasah->$field)) {
                    $laporanFilled++;
                }
            }
            $madrasah->laporan_akhir_tahun_percentage = round(($laporanFilled / count($laporanFields)) * 100);
            $madrasah->laporan_akhir_tahun_details = [
                'filled' => $laporanFilled,
                'total' => count($laporanFields)
            ];

            // ========== 5. Kelengkapan Data PPDB Settings ==========
            $ppdbSetting = PPDBSetting::where('sekolah_id', $madrasah->id)
                ->where('tahun', now()->year)
                ->first();

            $ppdbFields = ['tagline', 'deskripsi_singkat', 'tahun_berdiri', 'akreditasi', 'visi', 'misi', 'keunggulan', 'fasilitas', 'jurusan', 'ekstrakurikuler', 'telepon', 'email', 'website', 'video_profile'];

            if ($ppdbSetting) {
                $ppdbFilled = 0;
                foreach ($ppdbFields as $field) {
                    if (!is_null($ppdbSetting->$field) && !empty($ppdbSetting->$field)) {
                        $ppdbFilled++;
                    }
                }
                $madrasah->ppdb_percentage = round(($ppdbFilled / count($ppdbFields)) * 100);
                $madrasah->ppdb_details = [
                    'filled' => $ppdbFilled,
                    'total' => count($ppdbFields),
                    'ppdb_status' => $ppdbSetting->status ?? 'belum_atur'
                ];
            } else {
                $madrasah->ppdb_percentage = 0;
                $madrasah->ppdb_details = [
                    'filled' => 0,
                    'total' => count($ppdbFields),
                    'ppdb_status' => 'belum_atur'
                ];
            }

            // ========== Status Guru ==========
            $hasTeacher = $tenagaPendidik->count() > 0;
            $madrasah->has_teacher = $hasTeacher;

            return $madrasah;
        });

        // Get kabupaten list for filter
        $kabupatenList = Madrasah::select('kabupaten')
            ->distinct()
            ->orderBy('kabupaten')
            ->pluck('kabupaten');

        // Calculate overall statistics
        $totalSekolah = Madrasah::count();
        $allMadrasah = Madrasah::all();

        // Average percentages for all schools
        $avgMadrasahCompleteness = $allMadrasah->avg(function($m) {
            $fields = ['alamat', 'logo', 'latitude', 'longitude', 'map_link', 'polygon_koordinat', 'polygon_koordinat_2', 'enable_dual_polygon', 'hari_kbm', 'scod'];
            $filled = 0;
            foreach ($fields as $field) {
                if (!is_null($m->$field)) $filled++;
            }
            return round(($filled / count($fields)) * 100);
        });

        $avgPresensiKehadiran = 0;
        $avgPresensiMengajar = 0;
        $avgLaporan = 0;
        $avgPPDB = 0;

        $sekolahLengkap = $allMadrasah->filter(function($m) use (&$avgPresensiKehadiran, &$avgPresensiMengajar, &$avgLaporan, &$avgPPDB) {
            // Hitung presensi kehadiran
            $tp = User::where('madrasah_id', $m->id)->where('role', 'tenaga_pendidik')->pluck('id');
            $ph = 0;
            if ($tp->count() > 0) {
                $currentMonth = now()->month;
                $currentYear = now()->year;
                $hariKbm = $m->hari_kbm ?? 5;
                $workingDays = 0;
                $tempDate = Carbon::create($currentYear, $currentMonth, 1);
                $endOfMonth = $tempDate->copy()->endOfMonth();
                while ($tempDate <= $endOfMonth) {
                    $dayOfWeek = $tempDate->dayOfWeek;
                    $isWorkingDay = ($hariKbm == 5 && $dayOfWeek >= 1 && $dayOfWeek <= 5) || ($hariKbm == 6 && $dayOfWeek >= 1 && $dayOfWeek <= 6);
                    if ($isWorkingDay && !Holiday::where('date', $tempDate->toDateString())->exists()) $workingDays++;
                    $tempDate->addDay();
                }
                $expected = $tp->count() * $workingDays;
                $actual = Presensi::whereIn('user_id', $tp)->whereMonth('tanggal', $currentMonth)->whereYear('tanggal', $currentYear)->count();
                $ph = $expected > 0 ? round(($actual / $expected) * 100, 1) : 0;
            }

            // Hitung presensi mengajar
            $ts = TeachingSchedule::where('school_id', $m->id)->count();
            $pm = 0;
            if ($ts > 0) {
                $totalAttendance = TeachingAttendance::whereHas('teachingSchedule', function($q) use ($m) {
                    $q->where('school_id', $m->id);
                })->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year)->count();
                $hariKbm = $m->hari_kbm ?? 5;
                $estimated = $ts * 4 * $hariKbm;
                $pm = $estimated > 0 ? round(($totalAttendance / $estimated) * 100, 1) : 0;
            }

            // Hitung laporan
            $lapFields = ['deskripsi_singkat', 'sejarah', 'visi', 'misi', 'keunggulan', 'fasilitas', 'program_unggulan', 'ekstrakurikuler', 'prestasi'];
            $lapFilled = 0;
            foreach ($lapFields as $f) { if (!empty($m->$f)) $lapFilled++; }
            $lap = round(($lapFilled / count($lapFields)) * 100);

            // Hitung ppdb
            $ppdb = PPDBSetting::where('sekolah_id', $m->id)->where('tahun', now()->year)->first();
            $ppdbFields = ['tagline', 'deskripsi_singkat', 'tahun_berdiri', 'akreditasi', 'visi', 'misi', 'keunggulan', 'fasilitas', 'jurusan', 'ekstrakurikuler', 'telepon', 'email', 'website', 'video_profile'];
            $ppdbFilled = 0;
            if ($ppdb) {
                foreach ($ppdbFields as $f) { if (!empty($ppdb->$f)) $ppdbFilled++; }
            }
            $ppdbPct = round(($ppdbFilled / count($ppdbFields)) * 100);

            $avgPresensiKehadiran += $ph;
            $avgPresensiMengajar += $pm;
            $avgLaporan += $lap;
            $avgPPDB += $ppdbPct;

            return $ph >= 80 && $pm >= 80 && $lap >= 80 && $ppdbPct >= 80;
        })->count();

        $sekolahBelumLengkap = $totalSekolah - $sekolahLengkap;

        return view('mobile.pengurus.kelengkapan-data', compact(
            'madrasahs', 'search', 'kabupaten', 'kabupatenList',
            'totalSekolah', 'sekolahLengkap', 'sekolahBelumLengkap',
            'avgMadrasahCompleteness', 'avgPresensiKehadiran', 'avgPresensiMengajar',
            'avgLaporan', 'avgPPDB'
        ));
    }
}

