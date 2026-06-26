<?php

namespace App\Http\Controllers;

use App\Exports\SiswaTemplateExport;
use App\Imports\SiswaImport;
use App\Models\Madrasah;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class DataSiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $userRole = $this->normalizedRole($user->role);
        $madrasahOptions = $this->hasRestrictedMadrasahScope($userRole)
            ? Madrasah::query()->whereKey($user->madrasah_id)->orderBy('name')->get()
            : Madrasah::query()->orderBy('name')->get();

        $selectedMadrasahId = $this->hasRestrictedMadrasahScope($userRole)
            ? $user->madrasah_id
            : $request->integer('madrasah_id');

        $query = Siswa::query()->with('madrasah')->latest();

        if ($selectedMadrasahId) {
            $query->where('madrasah_id', $selectedMadrasahId);
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', 'like', '%' . trim((string) $request->kelas) . '%');
        }

        if ($request->filled('jurusan')) {
            $query->where('jurusan', 'like', '%' . trim((string) $request->jurusan) . '%');
        }

        if ($request->filled('q')) {
            $keyword = trim((string) $request->q);
            $query->where(function ($builder) use ($keyword) {
                $builder->where('scod', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_madrasah', 'like', '%' . $keyword . '%')
                    ->orWhere('nis', 'like', '%' . $keyword . '%')
                    ->orWhere('nisn', 'like', '%' . $keyword . '%')
                    ->orWhere('nik', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_lengkap', 'like', '%' . $keyword . '%')
                    ->orWhere('no_hp', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_ayah', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_ibu', 'like', '%' . $keyword . '%')
                    ->orWhere('nama_orang_tua_wali', 'like', '%' . $keyword . '%');
            });
        }

        $siswas = $query->get()->transform(function (Siswa $siswa) {
            $completion = $this->calculateCompletionStats($siswa);

            $siswa->setAttribute('completion_filled', $completion['filled']);
            $siswa->setAttribute('completion_total', $completion['total']);
            $siswa->setAttribute('completion_percentage', $completion['percentage']);

            return $siswa;
        });

        $statsQuery = Siswa::query();
        if ($selectedMadrasahId) {
            $statsQuery->where('madrasah_id', $selectedMadrasahId);
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'aktif' => (clone $statsQuery)->where('is_active', true)->count(),
            'kelengkapan' => $siswas->isNotEmpty() ? (int) round($siswas->avg('completion_percentage')) : 0,
            'kelengkapan_penuh' => $siswas->where('completion_percentage', 100)->count(),
            'kelas' => (clone $statsQuery)->distinct('kelas')->count('kelas'),
            'nisn' => (clone $statsQuery)->whereNotNull('nisn')->count(),
        ];

        return view('data-sekolah.data-siswa', [
            'siswas' => $siswas,
            'madrasahOptions' => $madrasahOptions,
            'selectedMadrasahId' => $selectedMadrasahId,
            'stats' => $stats,
            'userRole' => $userRole,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $this->authorizeStudentDataMutation($user);
        $validated = $this->validateSiswa($request, $user);
        $madrasah = $this->resolveMadrasah($validated['madrasah_id'], $user);

        Siswa::create($this->buildSiswaPayload($validated, $madrasah, true));

        return back()->with('success', 'Data siswa berhasil ditambahkan tanpa membuat akun login siswa.');
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $user = auth()->user();
        $this->authorizeSiswaAccess($siswa, $user);

        $validated = $this->validateSiswa($request, $user, $siswa);
        $madrasah = $this->resolveMadrasah($validated['madrasah_id'], $user);

        $siswa->update($this->buildSiswaPayload(
            $validated,
            $madrasah,
            (bool) $siswa->is_active,
            $siswa
        ));

        return back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function bulkUpdate(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $this->authorizeStudentDataMutation($user);

        $rows = $request->input('rows', []);
        if (!is_array($rows) || $rows === []) {
            return back()->withErrors(['bulk_edit' => 'Tidak ada data siswa yang dikirim untuk update massal.']);
        }

        $siswaIds = collect(array_keys($rows))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        $siswas = Siswa::query()
            ->with('madrasah')
            ->whereIn('id', $siswaIds)
            ->get()
            ->keyBy('id');

        if ($siswas->count() !== $siswaIds->count()) {
            return back()->withErrors(['bulk_edit' => 'Sebagian data siswa tidak ditemukan untuk update massal.']);
        }

        DB::transaction(function () use ($rows, $siswas, $user) {
            $rowNumber = 1;

            foreach ($rows as $siswaId => $row) {
                $siswa = $siswas->get((int) $siswaId);
                if (!$siswa) {
                    throw ValidationException::withMessages([
                        'bulk_edit' => ["Baris {$rowNumber}: data siswa tidak ditemukan."],
                    ]);
                }

                $this->authorizeSiswaAccess($siswa, $user);

                try {
                    $validated = $this->validateSiswaData((array) $row, $user, $siswa);
                } catch (ValidationException $exception) {
                    throw ValidationException::withMessages($this->prefixBulkValidationMessages(
                        $exception->errors(),
                        $rowNumber
                    ));
                }

                $madrasah = $this->resolveMadrasah((int) $validated['madrasah_id'], $user);

                $siswa->update($this->buildSiswaPayload(
                    $validated,
                    $madrasah,
                    (bool) $siswa->is_active,
                    $siswa
                ));

                $rowNumber++;
            }
        });

        return back()->with('success', 'Perubahan data siswa berhasil disimpan melalui edit massal.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $this->authorizeSiswaAccess($siswa, auth()->user());
        $siswa->delete();

        return back()->with('success', 'Data siswa berhasil dihapus.');
    }

    public function import(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $this->authorizeStudentDataMutation($user);
        $userRole = $this->normalizedRole($user->role);

        $madrasahRule = $this->hasRestrictedMadrasahScope($userRole)
            ? Rule::in([$user->madrasah_id])
            : Rule::exists('madrasahs', 'id');

        $validated = $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
            'madrasah_id' => ['nullable', 'integer', $madrasahRule],
        ]);

        $fallbackMadrasahId = $this->resolveImportMadrasahId($validated, $user);
        $import = new SiswaImport($fallbackMadrasahId, $this->restrictedMadrasahIdFor($user));

        try {
            DB::transaction(function () use ($validated, $import) {
                Excel::import($import, $validated['file']);
            });
        } catch (\InvalidArgumentException $exception) {
            return back()
                ->withInput(Arr::except($request->all(), ['file']))
                ->withErrors(['file' => $exception->getMessage()]);
        }

        return back()->with(
            'success',
            "Import selesai. {$import->created} data baru ditambahkan, {$import->updated} data diperbarui. Data siswa hanya disimpan sebagai data administrasi tanpa akun login."
        );
    }

    public function template()
    {
        $this->authorizeStudentDataMutation(auth()->user());

        return Excel::download(new SiswaTemplateExport(), 'template-import-data-siswa.xlsx');
    }

    private function validateSiswa(Request $request, $user, ?Siswa $siswa = null): array
    {
        return $this->validateSiswaData($request->all(), $user, $siswa);
    }

    private function validateSiswaData(array $input, $user, ?Siswa $siswa = null): array
    {
        $madrasahId = (int) (($input['madrasah_id'] ?? null) ?: $user->madrasah_id);
        $madrasahRule = $this->hasRestrictedMadrasahScope($this->normalizedRole($user->role))
            ? Rule::in([$user->madrasah_id])
            : Rule::exists('madrasahs', 'id');

        return Validator::make($input, [
            'madrasah_id' => ['required', 'integer', $madrasahRule],
            'scod' => ['nullable', 'string', 'max:50'],
            'asal_sekolah_madrasah' => ['nullable', 'string', 'max:255'],
            'nis' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('siswa', 'nis')
                    ->where(fn ($query) => $query->where('madrasah_id', $madrasahId))
                    ->ignore($siswa?->id),
            ],
            'nisn' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('siswa', 'nisn')->ignore($siswa?->id),
            ],
            'nik' => ['nullable', 'string', 'max:32'],
            'no_kk' => ['nullable', 'string', 'max:32'],
            'nama_lengkap' => ['nullable', 'string', 'max:255'],
            'jenis_kelamin' => ['nullable', 'in:L,P,l,p'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'agama' => ['nullable', 'string', 'max:50'],
            'nama_orang_tua_wali' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:25'],
            'no_hp_orang_tua_wali' => ['nullable', 'string', 'max:25'],
            'kelas' => ['nullable', 'string', 'max:50'],
            'jurusan' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'dusun' => ['nullable', 'string', 'max:150'],
            'kelurahan' => ['nullable', 'string', 'max:150'],
            'kecamatan' => ['nullable', 'string', 'max:150'],
            'nama_ayah' => ['nullable', 'string', 'max:255'],
            'nama_ibu' => ['nullable', 'string', 'max:255'],
        ])->validate();
    }

    private function buildSiswaPayload(array $validated, Madrasah $madrasah, bool $isActive, ?Siswa $existing = null): array
    {
        return [
            'madrasah_id' => $madrasah->id,
            'scod' => $this->nullableString($validated['scod'] ?? null),
            'nis' => $this->nullableString($validated['nis'] ?? null),
            'nisn' => $this->nullableString($validated['nisn'] ?? null),
            'nik' => $this->nullableString($validated['nik'] ?? null),
            'no_kk' => $this->nullableString($validated['no_kk'] ?? null),
            'nama_lengkap' => $this->nullableString($validated['nama_lengkap'] ?? null),
            'jenis_kelamin' => $this->normalizeGender($validated['jenis_kelamin'] ?? null),
            'tempat_lahir' => $this->nullableString($validated['tempat_lahir'] ?? null),
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'agama' => $this->nullableString($validated['agama'] ?? null),
            'nama_orang_tua_wali' => $this->resolveParentGuardianName($validated),
            'email' => $this->nullableString($validated['email'] ?? null),
            'email_orang_tua_wali' => null,
            'no_hp' => $this->nullableString($validated['no_hp'] ?? null),
            'no_hp_orang_tua_wali' => $this->nullableString($validated['no_hp_orang_tua_wali'] ?? null),
            'kelas' => $this->nullableString($validated['kelas'] ?? null),
            'jurusan' => $this->nullableString($validated['jurusan'] ?? null),
            'tahun_masuk' => null,
            'jenis_tinggal' => null,
            'alat_transportasi' => null,
            'nama_madrasah' => $this->nullableString($validated['asal_sekolah_madrasah'] ?? null),
            'alamat' => $this->resolveAddress($validated, $existing?->alamat),
            'dusun' => $this->nullableString($validated['dusun'] ?? null),
            'kelurahan' => $this->nullableString($validated['kelurahan'] ?? null),
            'kecamatan' => $this->nullableString($validated['kecamatan'] ?? null),
            'kode_pos' => null,
            'nama_ayah' => $this->nullableString($validated['nama_ayah'] ?? null),
            'pendidikan_ayah' => null,
            'pekerjaan_ayah' => null,
            'penghasilan_ayah' => null,
            'nama_ibu' => $this->nullableString($validated['nama_ibu'] ?? null),
            'pendidikan_ibu' => null,
            'pekerjaan_ibu' => null,
            'penghasilan_ibu' => null,
            'nama_wali' => null,
            'pendidikan_wali' => null,
            'pekerjaan_wali' => null,
            'penghasilan_wali' => null,
            'password' => null,
            'email_verified_at' => null,
            'last_login_at' => null,
            'is_active' => $isActive,
        ];
    }

    private function resolveMadrasah(int $madrasahId, $user): Madrasah
    {
        if ($this->hasRestrictedMadrasahScope($this->normalizedRole($user->role))) {
            return Madrasah::query()->whereKey($user->madrasah_id)->firstOrFail();
        }

        return Madrasah::query()->findOrFail($madrasahId);
    }

    private function authorizeSiswaAccess(Siswa $siswa, $user): void
    {
        abort_if(
            $this->hasRestrictedMadrasahScope($this->normalizedRole($user->role))
            && (int) $user->madrasah_id !== (int) $siswa->madrasah_id,
            403
        );
    }

    private function hasRestrictedMadrasahScope(string $userRole): bool
    {
        return in_array($userRole, ['admin', 'admin_spp'], true);
    }

    private function authorizeStudentDataMutation($user): void
    {
        abort_if($this->normalizedRole($user->role) === 'admin_spp', 403);
    }

    private function normalizedRole(?string $role): string
    {
        return preg_replace('/\s+/', '_', trim(strtolower((string) $role))) ?? '';
    }

    private function resolveParentGuardianName(array $validated): ?string
    {
        foreach ([
            $validated['nama_orang_tua_wali'] ?? null,
            $validated['nama_ayah'] ?? null,
            $validated['nama_ibu'] ?? null,
        ] as $candidate) {
            $normalized = $this->nullableString($candidate);
            if ($normalized) {
                return $normalized;
            }
        }

        return null;
    }

    private function resolveAddress(array $validated, ?string $fallback = null): ?string
    {
        $alamat = $this->nullableString($validated['alamat'] ?? null);
        if ($alamat) {
            return $alamat;
        }

        $segments = array_filter([
            $this->nullableString($validated['dusun'] ?? null),
            $this->nullableString($validated['kelurahan'] ?? null),
            $this->nullableString($validated['kecamatan'] ?? null),
            $this->nullableString($validated['kode_pos'] ?? null),
        ]);

        if ($segments !== []) {
            return implode(', ', $segments);
        }

        return $fallback ? $this->nullableString($fallback) : null;
    }

    private function normalizeGender(?string $gender): ?string
    {
        $normalized = strtoupper(trim((string) $gender));

        return in_array($normalized, ['L', 'P'], true) ? $normalized : null;
    }

    private function restrictedMadrasahIdFor($user): ?int
    {
        return $this->hasRestrictedMadrasahScope($this->normalizedRole($user->role))
            ? (int) $user->madrasah_id
            : null;
    }

    private function resolveImportMadrasahId(array $validated, $user): ?int
    {
        return $this->restrictedMadrasahIdFor($user)
            ?? (!empty($validated['madrasah_id']) ? (int) $validated['madrasah_id'] : null);
    }

    private function calculateCompletionStats(Siswa $siswa): array
    {
        $fields = [
            $siswa->scod ?: $siswa->madrasah?->scod,
            $siswa->nama_madrasah ?: $siswa->madrasah?->name,
            $siswa->nis,
            $siswa->nisn,
            $siswa->nik,
            $siswa->no_kk,
            $siswa->nama_lengkap,
            $siswa->jenis_kelamin,
            $siswa->tempat_lahir,
            $siswa->tanggal_lahir,
            $siswa->agama,
            $siswa->kelas,
            $siswa->jurusan,
            $siswa->alamat,
            $siswa->dusun,
            $siswa->kelurahan,
            $siswa->kecamatan,
            $siswa->no_hp,
            $siswa->email,
            $siswa->nama_ayah,
            $siswa->nama_ibu,
            $siswa->no_hp_orang_tua_wali,
        ];

        $filled = collect($fields)->filter(fn ($value) => $this->fieldHasValue($value))->count();
        $total = count($fields);

        return [
            'filled' => $filled,
            'total' => $total,
            'percentage' => $total > 0 ? (int) round(($filled / $total) * 100) : 0,
        ];
    }

    private function fieldHasValue($value): bool
    {
        if ($value instanceof \DateTimeInterface) {
            return true;
        }

        return trim((string) $value) !== '';
    }

    private function prefixBulkValidationMessages(array $errors, int $rowNumber): array
    {
        $messages = [];

        foreach ($errors as $field => $fieldErrors) {
            $messages[$field] = collect($fieldErrors)
                ->map(fn ($message) => "Baris {$rowNumber}: {$message}")
                ->all();
        }

        return $messages;
    }

    private function nullableString($value): ?string
    {
        $normalized = trim((string) $value);

        return $normalized !== '' ? $normalized : null;
    }
}
