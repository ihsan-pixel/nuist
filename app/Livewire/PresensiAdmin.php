<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Support\Carbon;

class PresensiAdmin extends Component
{
    public $selectedDate;
    public $madrasahData = [];
    public $summary = [];
    public $user;

    public function mount()
    {
        $this->user = auth()->user();
        $this->selectedDate = Carbon::today();
        $this->refreshData();
    }

    public function updatedSelectedDate()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        if (!in_array($this->user->role, ['super_admin', 'pengurus'])) {
            abort(403, 'Unauthorized access');
        }

        // Calculate summary metrics
        $this->summary = $this->calculatePresensiSummary($this->selectedDate, $this->user);

        // Get madrasah data for super_admin and pengurus
        $kabupatenOrder = [
            'Kabupaten Gunungkidul',
            'Kabupaten Bantul',
            'Kabupaten Kulon Progo',
            'Kabupaten Sleman',
            'Kota Yogyakarta'
        ];

        $this->madrasahData = [];
        foreach ($kabupatenOrder as $kabupaten) {
            $madrasahs = \App\Models\Madrasah::where('kabupaten', $kabupaten)
                ->orderByRaw("CAST(scod AS UNSIGNED) ASC")
                ->get();

            foreach ($madrasahs as $madrasah) {
                $tenagaPendidik = User::where('role', 'tenaga_pendidik')
                    ->where('madrasah_id', $madrasah->id)
                    ->with(['presensis' => function ($q) {
                        $q->whereDate('tanggal', $this->selectedDate);
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

                $this->madrasahData[] = [
                    'madrasah' => $madrasah,
                    'presensi' => $presensiData,
                ];
            }
        }
    }

    private function calculatePresensiSummary($selectedDate, $user)
    {
        $summary = [
            'users_presensi' => 0,
            'sekolah_presensi' => 0,
            'guru_tidak_presensi' => 0,
        ];

        if (in_array($user->role, ['super_admin', 'pengurus'])) {
            // For super_admin and pengurus: all data
            $presensiUsers = Presensi::whereDate('tanggal', $selectedDate)
                ->distinct('user_id')
                ->count('user_id');
            $summary['users_presensi'] = $presensiUsers;

            $sekolahPresensi = Presensi::whereDate('tanggal', $selectedDate)
                ->join('users', 'presensis.user_id', '=', 'users.id')
                ->join('madrasahs', 'users.madrasah_id', '=', 'madrasahs.id')
                ->distinct('madrasahs.id')
                ->count('madrasahs.id');
            $summary['sekolah_presensi'] = $sekolahPresensi;

            $totalGuru = User::where('role', 'tenaga_pendidik')->count();
            $summary['guru_tidak_presensi'] = $totalGuru - $presensiUsers;
        }

        return $summary;
    }

    public function render()
    {
        return view('livewire.presensi-admin');
    }
}
