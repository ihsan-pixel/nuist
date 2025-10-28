public function dataPresensi()
    {
        $user = Auth::user();

        // Check if user is kepala sekolah
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this page.');
        }

        $selectedDate = request('date') ? Carbon::parse(request('date')) : Carbon::today();

        // Get presensi data for the madrasah
        $presensis = Presensi::with(['user', 'statusKepegawaian'])
            ->whereHas('user', function ($q) use ($user) {
                $q->where('madrasah_id', $user->madrasah_id);
            })
            ->whereDate('tanggal', $selectedDate)
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        // Get users who haven't done presensi
        $belumPresensi = User::where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $user->madrasah_id)
            ->whereDoesntHave('presensis', function ($q) use ($selectedDate) {
                $q->whereDate('tanggal', $selectedDate);
            })
            ->get();

        return view('mobile.data-presensi', compact('presensis', 'belumPresensi', 'selectedDate'));
    }

    public function dataJadwal()
    {
        $user = Auth::user();

        // Check if user is kepala sekolah
        if ($user->role !== 'tenaga_pendidik' || $user->ketugasan !== 'kepala madrasah/sekolah') {
            abort(403, 'Unauthorized. Only kepala sekolah can access this page.');
        }

        // Get all teaching schedules for the madrasah
        $schedules = TeachingSchedule::with(['teacher', 'school'])
            ->where('school_id', $user->madrasah_id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        // Get classes and subjects
        $classes = TeachingSchedule::where('school_id', $user->madrasah_id)
            ->select('class_name')
            ->distinct()
            ->pluck('class_name')
            ->sort();

        $subjects = TeachingSchedule::where('school_id', $user->madrasah_id)
            ->select('subject')
            ->distinct()
            ->pluck('subject')
            ->sort();

        return view('mobile.data-jadwal', compact('schedules', 'classes', 'subjects'));
    }
