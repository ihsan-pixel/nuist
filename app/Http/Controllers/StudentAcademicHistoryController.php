<?php

namespace App\Http\Controllers;

use App\Models\Madrasah;
use App\Models\Siswa;
use App\Models\SiswaRiwayatAkademik;
use App\Services\StudentAcademicLevelInferenceService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentAcademicHistoryController extends Controller
{
    public function preview(Request $request, StudentAcademicLevelInferenceService $inference)
    {
        $user = auth()->user();
        $role = $this->normalizedRole($user->role);
        abort_unless(in_array($role, ['super_admin', 'admin'], true), 403);
        abort_if($role === 'admin' && !$user->madrasah_id, 403, 'Admin belum terhubung ke madrasah.');

        $defaults = $this->defaultPeriod();
        $filters = $request->validate([
            'tahun_ajaran' => ['nullable', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => ['nullable', Rule::in([1, 2, '1', '2'])],
            'madrasah_id' => ['nullable', 'integer', Rule::exists('madrasahs', 'id')],
        ]);

        $tahunAjaran = $filters['tahun_ajaran'] ?? $defaults['tahun_ajaran'];
        $semester = (int) ($filters['semester'] ?? $defaults['semester']);
        $madrasahId = $role === 'admin'
            ? (int) $user->madrasah_id
            : (!empty($filters['madrasah_id']) ? (int) $filters['madrasah_id'] : null);

        $students = $this->eligibleStudents($tahunAjaran, $semester, $madrasahId)
            ->orderBy('madrasah_id')
            ->orderBy('nama_lengkap')
            ->get();

        $rows = $students->map(function (Siswa $siswa) use ($inference) {
            $result = $inference->infer($siswa->kelas, $siswa->madrasah?->name ?: $siswa->nama_madrasah);
            if ($result['status'] === 'aktif' && !$siswa->is_active) {
                $result['status'] = 'nonaktif';
            }

            return [
                'siswa' => $siswa,
                ...$result,
            ];
        });

        $alreadyRecorded = SiswaRiwayatAkademik::query()
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)
            ->when($madrasahId, fn (Builder $query) => $query->where('madrasah_id', $madrasahId))
            ->count();

        return view('data-sekolah.riwayat-akademik-preview', [
            'rows' => $rows,
            'tahunAjaran' => $tahunAjaran,
            'semester' => $semester,
            'selectedMadrasahId' => $madrasahId,
            'madrasahOptions' => $role === 'admin'
                ? Madrasah::query()->whereKey($user->madrasah_id)->get()
                : Madrasah::query()->orderBy('name')->get(),
            'userRole' => $role,
            'alreadyRecorded' => $alreadyRecorded,
            'recognizedCount' => $rows->whereIn('status', ['aktif', 'nonaktif'])->count(),
            'reviewCount' => $rows->where('status', 'perlu_verifikasi')->count(),
        ]);
    }

    public function store(Request $request, StudentAcademicLevelInferenceService $inference): RedirectResponse
    {
        $user = auth()->user();
        abort_unless($this->normalizedRole($user->role) === 'super_admin', 403);

        $validated = $request->validate([
            'tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => ['required', Rule::in([1, 2, '1', '2'])],
            'madrasah_id' => ['nullable', 'integer', Rule::exists('madrasahs', 'id')],
            'confirmation' => ['accepted'],
        ]);

        [$startYear, $endYear] = array_map('intval', explode('/', $validated['tahun_ajaran']));
        if ($endYear !== $startYear + 1) {
            return back()->withErrors(['tahun_ajaran' => 'Tahun ajaran harus berurutan, misalnya 2026/2027.']);
        }

        $semester = (int) $validated['semester'];
        $madrasahId = !empty($validated['madrasah_id']) ? (int) $validated['madrasah_id'] : null;
        $created = 0;
        $review = 0;

        DB::transaction(function () use (
            $validated,
            $semester,
            $madrasahId,
            $inference,
            $user,
            &$created,
            &$review
        ) {
            $this->eligibleStudents($validated['tahun_ajaran'], $semester, $madrasahId)
                ->orderBy('id')
                ->chunkById(500, function ($students) use (
                    $validated,
                    $semester,
                    $inference,
                    $user,
                    &$created,
                    &$review
                ) {
                    $now = now();
                    $records = [];

                    foreach ($students as $siswa) {
                        $result = $inference->infer(
                            $siswa->kelas,
                            $siswa->madrasah?->name ?: $siswa->nama_madrasah
                        );
                        $status = $result['status'];
                        if ($status === 'aktif' && !$siswa->is_active) {
                            $status = 'nonaktif';
                        }

                        if ($status === 'perlu_verifikasi') {
                            $review++;
                        }

                        $records[] = [
                            'siswa_id' => $siswa->id,
                            'madrasah_id' => $siswa->madrasah_id,
                            'tahun_ajaran' => $validated['tahun_ajaran'],
                            'semester' => $semester,
                            'jenjang_sekolah' => $result['jenjang_sekolah'],
                            'tingkat' => $result['tingkat'],
                            'kelas' => $siswa->kelas,
                            'jurusan' => $siswa->jurusan,
                            'status' => $status,
                            'is_current' => true,
                            'sumber_data' => 'migrasi_kelas_awal',
                            'catatan' => $result['catatan'],
                            'updated_by' => $user->id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    $created += DB::table('siswa_riwayat_akademik')->insertOrIgnore($records);
                });
        });

        return redirect()->route('data-sekolah.data-siswa.academic-history.preview', [
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'semester' => $semester,
            'madrasah_id' => $madrasahId,
        ])->with(
            'success',
            "Snapshot akademik selesai: {$created} riwayat dibuat, {$review} perlu verifikasi. Data siswa lama tidak diubah."
        );
    }

    private function eligibleStudents(string $tahunAjaran, int $semester, ?int $madrasahId): Builder
    {
        return Siswa::query()
            ->with('madrasah')
            ->when($madrasahId, fn (Builder $query) => $query->where('madrasah_id', $madrasahId))
            ->whereDoesntHave('riwayatAkademik', function (Builder $query) use ($tahunAjaran, $semester) {
                $query->where('tahun_ajaran', $tahunAjaran)->where('semester', $semester);
            });
    }

    private function defaultPeriod(): array
    {
        $now = now('Asia/Jakarta');
        $startYear = $now->month >= 7 ? $now->year : $now->year - 1;

        return [
            'tahun_ajaran' => $startYear.'/'.($startYear + 1),
            'semester' => $now->month >= 7 ? 1 : 2,
        ];
    }

    private function normalizedRole(?string $role): string
    {
        return trim(preg_replace('/[^a-z0-9]+/', '_', strtolower(trim((string) $role))) ?? '', '_');
    }
}
