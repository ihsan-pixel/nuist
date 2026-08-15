<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Madrasah;
use App\Models\Siswa;
use App\Models\SppSiswaBill;
use App\Models\SppSiswaTransaction;
use App\Models\UppmPaymentUpdate;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class PengurusAppController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        $user = $this->authorizePengurus($request);
        $today = now()->toDateString();

        $data = Cache::remember('pengurus:dashboard:' . $today, now()->addMinute(), function () use ($today) {
            $openBills = SppSiswaBill::query()->whereIn('status', ['belum_lunas', 'sebagian']);
            $verifiedPayments = DB::table('spp_siswa_transactions')
                ->selectRaw('bill_id, SUM(nominal_bayar) as paid_amount')
                ->where('status_verifikasi', 'diverifikasi')
                ->groupBy('bill_id');

            // `outstanding_amount` is an Eloquent accessor, not a database column.
            // Calculate it in SQL so this dashboard does not load every bill and its
            // transactions into memory.
            $finance = (clone $openBills)
                ->leftJoinSub($verifiedPayments, 'verified_payments', function ($join) {
                    $join->on('verified_payments.bill_id', '=', 'spp_siswa_bills.id');
                })
                ->selectRaw('COUNT(spp_siswa_bills.id) as open_bills')
                ->selectRaw('COALESCE(SUM(GREATEST(spp_siswa_bills.total_tagihan - COALESCE(verified_payments.paid_amount, 0), 0)), 0) as outstanding_amount')
                ->first();

            return [
                'summary' => [
                    'schools' => Madrasah::query()->count(),
                    'students' => Siswa::query()->where('is_active', true)->count(),
                    'teachers' => User::query()->where('role', 'tenaga_pendidik')->where('is_active', true)->count(),
                    'attendance_today' => DB::table('presensis')->where('tanggal', $today)->distinct('user_id')->count('user_id'),
                ],
                'finance' => [
                    'open_bills' => (int) ($finance->open_bills ?? 0),
                    'outstanding_amount' => (float) ($finance->outstanding_amount ?? 0),
                ],
                'recent_uppm_updates' => $this->uppmUpdateItems(3),
                'recent_spp_updates' => $this->sppUpdateItems(3),
                'generated_at' => now()->toIso8601String(),
            ];
        });

        return response()->json(['data' => array_merge($data, [
            'greeting' => 'Selamat datang, ' . $user->name,
        ])]);
    }

    public function schools(Request $request): JsonResponse
    {
        $this->authorizePengurus($request);
        $items = Cache::remember('pengurus:schools:v2', now()->addMinutes(5), function () {
            $studentCounts = Siswa::query()->where('is_active', true)->selectRaw('madrasah_id, COUNT(*) as total')->groupBy('madrasah_id')->pluck('total', 'madrasah_id');
            $teacherCounts = User::query()->where('role', 'tenaga_pendidik')->where('is_active', true)->selectRaw('madrasah_id, COUNT(*) as total')->groupBy('madrasah_id')->pluck('total', 'madrasah_id');

            return Madrasah::query()
            ->orderByRaw("CASE WHEN kabupaten IS NULL OR TRIM(kabupaten) = '' THEN 1 ELSE 0 END")
            ->orderBy('kabupaten')
            ->orderBy('scod')
            ->orderBy('name')
            ->get(['id', 'name', 'scod', 'kabupaten', 'logo'])
            ->map(fn (Madrasah $school) => [
                'id' => $school->id, 'name' => $school->name, 'scod' => $school->scod,
                'kabupaten' => $school->kabupaten,
                'logo' => $school->logo,
                'logo_url' => filled($school->logo) ? url('storage/' . ltrim($school->logo, '/')) : null,
                'students' => (int) ($studentCounts[$school->id] ?? 0),
                'teachers' => (int) ($teacherCounts[$school->id] ?? 0),
            ])->values()->all();
        });
        return response()->json(['data' => ['items' => $items]]);
    }

    public function uppmUpdates(Request $request): JsonResponse
    {
        $this->authorizePengurus($request);

        return response()->json(['data' => [
            'items' => $this->uppmUpdateItems(50),
        ]]);
    }

    public function school(Request $request, Madrasah $madrasah): JsonResponse
    {
        $this->authorizePengurus($request);

        $headmaster = User::query()
            ->where('madrasah_id', $madrasah->id)
            ->where('role', 'tenaga_pendidik')
            // Kolom ketugasan tersedia pada seluruh instalasi lama. Jangan
            // bergantung pada kolom jabatan karena belum ada di sebagian DB.
            ->where('ketugasan', 'like', '%kepala%')
            ->with(['statusKepegawaian:id,name', 'skYayasanEmployeeData:id,user_id,penilaian_kinerja,keterangan'])
            ->orderBy('name')
            ->first();

        $teachers = User::query()
            ->where('madrasah_id', $madrasah->id)
            ->where('role', 'tenaga_pendidik')
            ->where('is_active', true)
            ->with(['statusKepegawaian:id,name', 'skYayasanEmployeeData:id,user_id,penilaian_kinerja,keterangan'])
            ->orderBy('name')
            ->get();
        $students = Siswa::query()
            ->where('madrasah_id', $madrasah->id)
            ->where('is_active', true)
            ->orderBy('nama_lengkap')
            ->limit(30)
            ->get(['id', 'nama_lengkap', 'kelas', 'jurusan']);

        return response()->json(['data' => [
            'school' => [
                'id' => $madrasah->id,
                'name' => $madrasah->name,
                'kabupaten' => $madrasah->kabupaten,
                'alamat' => $madrasah->alamat,
                'logo' => $madrasah->logo,
                'logo_url' => filled($madrasah->logo) ? url('storage/' . ltrim($madrasah->logo, '/')) : null,
            ],
            'headmaster' => $headmaster ? [
                'id' => $headmaster->id,
                'name' => trim($headmaster->name . ' ' . ($headmaster->gelar ?? '')),
                'position' => $headmaster->ketugasan ?: 'Kepala Sekolah',
                'status_kepegawaian' => $headmaster->statusKepegawaian?->name,
                'photo_url' => $headmaster->avatar ? asset('storage/' . ltrim($headmaster->avatar, '/')) : null,
                'details' => $this->teacherDetailPayload($headmaster),
            ] : null,
            'teacher_count' => $teachers->count(),
            'student_count' => Siswa::query()->where('madrasah_id', $madrasah->id)->where('is_active', true)->count(),
            'teachers' => $teachers->map(fn (User $teacher) => [
                'id' => $teacher->id,
                'name' => trim($teacher->name . ' ' . ($teacher->gelar ?? '')),
                'position' => $teacher->ketugasan ?: 'Tenaga Pendidik',
                'status_kepegawaian' => $teacher->statusKepegawaian?->name,
                'photo_url' => $teacher->avatar ? asset('storage/' . ltrim($teacher->avatar, '/')) : null,
                'details' => $this->teacherDetailPayload($teacher),
            ])->all(),
            'students' => $students->map(fn (Siswa $student) => [
                'name' => $student->nama_lengkap,
                'class' => trim(implode(' • ', array_filter([$student->kelas, $student->jurusan]))),
            ])->all(),
            'students_preview_limited' => $students->count() >= 30,
        ]]);
    }

    public function sppUpdates(Request $request): JsonResponse
    {
        $this->authorizePengurus($request);

        return response()->json(['data' => [
            'items' => $this->sppUpdateItems(50),
        ]]);
    }

    private function uppmUpdateItems(int $limit): array
    {
        return UppmPaymentUpdate::query()
            ->with('madrasah:id,name')
            ->latest('transfer_date')
            ->latest('id')
            ->limit($limit)
            ->get()
            ->map(fn (UppmPaymentUpdate $update) => [
                'id' => $update->id,
                'title' => $update->madrasah?->name ?? 'Sekolah',
                'subtitle' => 'UPPM • ' . $update->payment_period_label,
                'amount' => (float) $update->amount,
                'status' => 'Pembaruan pembayaran',
                'date' => optional($update->transfer_date)->toDateString() ?? optional($update->created_at)->toDateString(),
            ])
            ->all();
    }

    private function sppUpdateItems(int $limit): array
    {
        return SppSiswaTransaction::query()
            ->with('madrasah:id,name')
            ->latest('tanggal_bayar')
            ->latest('id')
            ->limit($limit)
            ->get()
            ->map(fn (SppSiswaTransaction $transaction) => [
                'id' => $transaction->id,
                'title' => $transaction->madrasah?->name ?? 'Sekolah',
                'subtitle' => 'SPP Siswa • ' . ($transaction->metode_pembayaran ?: 'Pembayaran'),
                'amount' => (float) $transaction->nominal_bayar,
                'status' => match ($transaction->status_verifikasi) {
                    'diverifikasi' => 'Terverifikasi',
                    'ditolak' => 'Ditolak',
                    default => 'Menunggu verifikasi',
                },
                'date' => optional($transaction->tanggal_bayar)->toDateString() ?? optional($transaction->created_at)->toDateString(),
            ])
            ->all();
    }

    private function authorizePengurus(Request $request): User
    {
        $user = $request->user();
        abort_unless($user && $user->role === 'pengurus' && $user->is_active, 403);
        return $user;
    }

    private function teacherDetailPayload(User $teacher): array
    {
        return [
            'name' => trim($teacher->name . ' ' . ($teacher->gelar ?? '')) ?: '-',
            'status_kepegawaian' => $teacher->statusKepegawaian?->name ?: ($teacher->ketugasan ?: '-'),
            'ketugasan' => $teacher->ketugasan ?: '-',
            'nuist_id' => $teacher->nuist_id ?: '-',
            'email' => $teacher->email ?: '-',
            'phone' => $teacher->no_hp ?: '-',
            'tempat_lahir' => $teacher->tempat_lahir ?: '-',
            'tanggal_lahir' => optional($teacher->tanggal_lahir)->format('d-m-Y') ?: '-',
            'nip' => $teacher->nip ?: '-',
            'nuptk' => $teacher->nuptk ?: '-',
            'npk' => $teacher->npk ?: '-',
            'kartanu' => $teacher->kartanu ?: '-',
            'pendidikan_terakhir' => $teacher->pendidikan_terakhir ?: '-',
            'tahun_lulus' => $teacher->tahun_lulus ?: '-',
            'program_studi' => $teacher->program_studi ?: '-',
            'tmt' => optional($teacher->tmt)->format('d-m-Y') ?: '-',
            'masa_kerja' => $teacher->masa_kerja ?: '-',
            'alamat' => $teacher->alamat ?: '-',
            'mengajar' => $teacher->mengajar ?: '-',
            'pemenuhan_beban_kerja_lain' => $teacher->pemenuhan_beban_kerja_lain ?: '-',
            'status_kepegawaian_label' => $teacher->statusKepegawaian?->name ?: '-',
            'sk_yayasan' => $teacher->skYayasanEmployeeData ? [
                'penilaian_kinerja' => $teacher->skYayasanEmployeeData->penilaian_kinerja !== null
                    ? (string) $teacher->skYayasanEmployeeData->penilaian_kinerja
                    : '-',
                'keterangan' => $teacher->skYayasanEmployeeData->keterangan ?: '-',
            ] : null,
        ];
    }
}
