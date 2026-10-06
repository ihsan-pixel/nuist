<?php

namespace App\Imports;

use App\Models\Madrasah;
use App\Models\Siswa;
use App\Services\StudentDefaultPasswordService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SiswaImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $created = 0;
    public int $updated = 0;
    public array $processedStudentIds = [];

    public function __construct(
        private readonly ?int $fallbackMadrasahId = null,
        private readonly ?int $restrictedMadrasahId = null,
        private readonly ?int $overrideMadrasahId = null
    ) {
    }

    public function collection(Collection $rows): void
    {
        $this->prepareNisReassignments($rows);

        foreach ($rows as $index => $row) {
            $line = $index + 2;

            $madrasah = $this->resolveMadrasahForRow($row, $line);
            $nis = $this->nullableString($this->getRowValue($row, 'nis'));
            $nisn = $this->nullableString($this->getRowValue($row, 'nisn'));
            $nik = $this->nullableString($this->getRowValue($row, 'nik'));
            $emailSiswa = $this->nullableString($this->getRowValue($row, 'email_siswa'));

            if ($emailSiswa && !filter_var($emailSiswa, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException("Baris {$line}: format email_siswa tidak valid.");
            }

            $attributes = [
                'madrasah_id' => $madrasah->id,
                'scod' => $this->nullableString($this->getRowValue($row, 'scod')),
                'nis' => $nis,
                'nisn' => $nisn,
                'nik' => $nik,
                'no_kk' => $this->nullableString($this->getRowValue($row, 'no_kk')),
                'nama_lengkap' => $this->nullableString($this->getRowValue($row, 'nama_peserta_didik')),
                'jenis_kelamin' => $this->normalizeGender($this->getRowValue($row, 'jenis_kelamin')),
                'tempat_lahir' => $this->nullableString($this->getRowValue($row, 'tempat_lahir')),
                'tanggal_lahir' => $this->normalizeDate($this->getRowValue($row, 'tanggal_lahir')),
                'agama' => $this->nullableString($this->getRowValue($row, 'agama')),
                'kelas' => $this->nullableString($this->getRowValue($row, 'kelas')),
                'jurusan' => $this->nullableString($this->getRowValue($row, 'jurusan')),
                'nama_madrasah' => $this->nullableString($this->getRowValue($row, 'asal_sekolah_madrasah')),
                'alamat' => $this->resolveAddress($row),
                'dusun' => $this->nullableString($this->getRowValue($row, 'dusun')),
                'kelurahan' => $this->nullableString($this->getRowValue($row, 'kelurahan')),
                'kecamatan' => $this->nullableString($this->getRowValue($row, 'kecamatan')),
                'email' => $emailSiswa,
                'no_hp' => $this->nullableString($this->getRowValue($row, 'no_hp_siswa')),
                'nama_ayah' => $this->nullableString($this->getRowValue($row, 'nama_ayah')),
                'nama_ibu' => $this->nullableString($this->getRowValue($row, 'nama_ibu')),
                'nama_orang_tua_wali' => $this->resolveParentGuardianName($row),
                'no_hp_orang_tua_wali' => $this->nullableString($this->getRowValue($row, 'no_hp_orang_tua_wali')),
                'email_orang_tua_wali' => null,
                'tahun_masuk' => null,
                'jenis_tinggal' => null,
                'alat_transportasi' => null,
                'kode_pos' => null,
                'pendidikan_ayah' => null,
                'pekerjaan_ayah' => null,
                'penghasilan_ayah' => null,
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
                'is_active' => true,
            ];

            $existing = $this->findExistingStudent($madrasah->id, $nis, $nisn, $nik, $line);

            if ($existing) {
                // Do not invalidate a password already selected by a student.
                $attributes['password'] = $existing->password;
                $existing->fill($attributes);
                $existing->save();
                app(StudentDefaultPasswordService::class)->ensurePassword($existing);
                $this->processedStudentIds[] = (int) $existing->id;
                $this->updated++;
                continue;
            }

            $created = Siswa::create($attributes);
            app(StudentDefaultPasswordService::class)->ensurePassword($created);
            $this->processedStudentIds[] = (int) $created->id;
            $this->created++;
        }
    }

    /**
     * Release NIS values that are being reassigned between students in the same
     * import batch. The controller wraps the import in a transaction, so these
     * temporary nulls are rolled back if any row later fails validation.
     */
    private function prepareNisReassignments(Collection $rows): void
    {
        $plans = collect();
        $targetRows = collect();

        foreach ($rows as $index => $row) {
            $line = $index + 2;
            $madrasah = $this->resolveMadrasahForRow($row, $line);
            $nis = $this->nullableString($this->getRowValue($row, 'nis'));
            $nisn = $this->nullableString($this->getRowValue($row, 'nisn'));
            $nik = $this->nullableString($this->getRowValue($row, 'nik'));

            if (!$nis || (!$nisn && !$nik)) {
                continue;
            }

            $identityMatches = collect([
                'NISN' => $nisn ? Siswa::query()->where('nisn', $nisn)->first() : null,
                'NIK' => $nik ? Siswa::query()->where('nik', $nik)->first() : null,
            ])->filter();

            $identityIds = $identityMatches->pluck('id')->unique()->values();
            if ($identityIds->count() > 1) {
                $details = $identityMatches
                    ->map(fn (Siswa $student, string $identifier) => "{$identifier} cocok ke ID {$student->id}")
                    ->implode(', ');

                throw new \InvalidArgumentException(
                    "Baris {$line}: identifier siswa saling bertentangan ({$details})."
                );
            }

            $target = $identityMatches->first();
            if (!$target) {
                continue;
            }

            if ($targetRows->has((int) $target->id)) {
                throw new \InvalidArgumentException(
                    "Baris {$line}: siswa ID {$target->id} sudah muncul pada baris {$targetRows->get((int) $target->id)} dalam file import."
                );
            }
            $targetRows->put((int) $target->id, $line);

            if ((int) $target->madrasah_id !== (int) $madrasah->id) {
                throw new \InvalidArgumentException(
                    "Baris {$line}: NISN/NIK cocok dengan siswa ID {$target->id} di madrasah lain."
                );
            }

            $planKey = $madrasah->id.'|'.$nis;
            if ($plans->has($planKey) && (int) $plans->get($planKey)['target_id'] !== (int) $target->id) {
                throw new \InvalidArgumentException(
                    "Baris {$line}: NIS {$nis} digunakan lebih dari sekali untuk siswa berbeda dalam file import."
                );
            }

            $plans->put($planKey, [
                'line' => $line,
                'madrasah_id' => (int) $madrasah->id,
                'desired_nis' => $nis,
                'target_id' => (int) $target->id,
                'current_nis' => $this->nullableString($target->nis),
            ]);
        }

        if ($plans->isEmpty()) {
            return;
        }

        $plannedTargetIds = $plans->pluck('target_id')->unique();

        foreach ($plans as $plan) {
            $owner = Siswa::query()
                ->where('madrasah_id', $plan['madrasah_id'])
                ->where('nis', $plan['desired_nis'])
                ->first();

            if ($owner && (int) $owner->id !== $plan['target_id'] && !$plannedTargetIds->contains((int) $owner->id)) {
                throw new \InvalidArgumentException(
                    "Baris {$plan['line']}: NIS {$plan['desired_nis']} masih digunakan oleh siswa ID {$owner->id} " .
                    'yang tidak ikut diperbarui dalam file ini.'
                );
            }
        }

        $idsToRelease = $plans
            ->filter(fn (array $plan) => $plan['current_nis'] !== $plan['desired_nis'])
            ->pluck('target_id')
            ->unique()
            ->values();

        if ($idsToRelease->isNotEmpty()) {
            Siswa::query()->whereIn('id', $idsToRelease)->update(['nis' => null]);
        }
    }

    private function resolveMadrasahForRow($row, int $line): Madrasah
    {
        if ($this->overrideMadrasahId) {
            $madrasah = Madrasah::query()->find($this->overrideMadrasahId);

            if (!$madrasah) {
                throw new \InvalidArgumentException('Madrasah tujuan update data siswa tidak ditemukan.');
            }

            if ($this->restrictedMadrasahId !== null && $madrasah->id !== $this->restrictedMadrasahId) {
                throw new \InvalidArgumentException("Baris {$line}: Anda tidak memiliki akses untuk mengimpor data ke sekolah {$madrasah->name}.");
            }

            return $madrasah;
        }

        $scod = trim((string) $this->getRowValue($row, 'scod'));
        $schoolName = trim((string) $this->getRowValue($row, 'asal_sekolah_madrasah'));

        if ($scod === '' && $schoolName === '' && $this->fallbackMadrasahId) {
            $madrasah = Madrasah::query()->find($this->fallbackMadrasahId);

            if ($madrasah) {
                return $madrasah;
            }
        }

        $query = Madrasah::query();

        if ($scod !== '') {
            $query->where('scod', $scod);
        } elseif ($schoolName !== '') {
            $query->where('name', $schoolName);
        } else {
            throw new \InvalidArgumentException("Baris {$line}: kolom SCOD dan ASAL SEKOLAH/MADRASAH kosong. Pilih madrasah pada form import atau isi salah satu kolom tersebut.");
        }

        $madrasah = $query->first();

        if (!$madrasah && $scod !== '') {
            $madrasah = Madrasah::query()
                ->where('name', $schoolName)
                ->first();
        }

        if (!$madrasah) {
            throw new \InvalidArgumentException("Baris {$line}: sekolah dengan SCOD '{$scod}' / nama '{$schoolName}' tidak ditemukan.");
        }

        if ($this->restrictedMadrasahId !== null && $madrasah->id !== $this->restrictedMadrasahId) {
            throw new \InvalidArgumentException("Baris {$line}: Anda tidak memiliki akses untuk mengimpor data ke sekolah {$madrasah->name}.");
        }

        return $madrasah;
    }

    private function findExistingStudent(
        int $madrasahId,
        ?string $nis,
        ?string $nisn,
        ?string $nik,
        int $line
    ): ?Siswa
    {
        if (!$nis && !$nisn && !$nik) {
            return null;
        }

        $matches = collect([
            'NIS' => $nis
                ? Siswa::query()->where('madrasah_id', $madrasahId)->where('nis', $nis)->first()
                : null,
            'NISN' => $nisn
                ? Siswa::query()->where('nisn', $nisn)->first()
                : null,
            'NIK' => $nik
                ? Siswa::query()->where('nik', $nik)->first()
                : null,
        ])->filter();

        $matchedStudentIds = $matches->pluck('id')->unique()->values();

        if ($matchedStudentIds->count() > 1) {
            $details = $matches
                ->map(fn (Siswa $student, string $identifier) => "{$identifier} cocok ke ID {$student->id}")
                ->implode(', ');

            throw new \InvalidArgumentException(
                "Baris {$line}: identifier siswa saling bertentangan ({$details}). " .
                'Periksa data duplikat di aplikasi sebelum import dilanjutkan.'
            );
        }

        $existing = $matches->first();

        if (!$existing) {
            return null;
        }

        // Guard against a target value owned by another row even if the initial
        // identity match was found through NISN or NIK.
        $nisOwner = $nis
            ? Siswa::query()
                ->where('madrasah_id', $madrasahId)
                ->where('nis', $nis)
                ->where('id', '!=', $existing->id)
                ->first()
            : null;

        if ($nisOwner) {
            throw new \InvalidArgumentException(
                "Baris {$line}: NIS {$nis} sudah digunakan oleh siswa ID {$nisOwner->id} pada madrasah yang sama. " .
                'Periksa data duplikat di aplikasi sebelum import dilanjutkan.'
            );
        }

        return $existing;
    }

    private function getRowValue($row, string $field): mixed
    {
        $aliases = [
            'scod' => ['scod', 'kode_sekolah'],
            'asal_sekolah_madrasah' => ['asal_sekolah_madrasah', 'asal_sekolah', 'nama_madrasah', 'madrasah'],
            'nama_peserta_didik' => ['nama_peserta_didik', 'nama_lengkap', 'nama_siswa'],
            'no_hp_siswa' => ['no_hp_siswa', 'no_hp', 'hp_siswa'],
            'email_siswa' => ['email_siswa', 'email'],
            'no_hp_orang_tua_wali' => ['no_hp_orang_tua_wali', 'no_hp_wali', 'hp_orang_tua_wali'],
        ];

        $rowArray = $row->toArray();
        $normalizedRow = [];

        foreach ($rowArray as $key => $value) {
            $normalizedRow[$this->normalizeHeadingKey((string) $key)] = $value;
        }

        foreach ($aliases[$field] ?? [$field] as $key) {
            if (array_key_exists($key, $rowArray) && !blank($row[$key])) {
                return $row[$key];
            }

            $normalizedKey = $this->normalizeHeadingKey($key);
            if (array_key_exists($normalizedKey, $normalizedRow) && !blank($normalizedRow[$normalizedKey])) {
                return $normalizedRow[$normalizedKey];
            }
        }

        return null;
    }

    private function normalizeHeadingKey(string $value): string
    {
        return preg_replace('/[^a-z0-9]+/i', '', strtolower(trim($value))) ?: '';
    }

    private function resolveParentGuardianName($row): ?string
    {
        foreach ([
            $this->getRowValue($row, 'nama_orang_tua_wali'),
            $this->getRowValue($row, 'nama_ayah'),
            $this->getRowValue($row, 'nama_ibu'),
        ] as $candidate) {
            $normalized = $this->nullableString($candidate);
            if ($normalized) {
                return $normalized;
            }
        }

        return null;
    }

    private function resolveAddress($row): ?string
    {
        $alamat = $this->nullableString($this->getRowValue($row, 'alamat'));
        if ($alamat) {
            return $alamat;
        }

        $segments = array_filter([
            $this->nullableString($this->getRowValue($row, 'dusun')),
            $this->nullableString($this->getRowValue($row, 'kelurahan')),
            $this->nullableString($this->getRowValue($row, 'kecamatan')),
        ]);

        return $segments !== [] ? implode(', ', $segments) : null;
    }

    private function nullableString($value): ?string
    {
        $normalized = trim((string) $value);

        return $normalized !== '' ? $normalized : null;
    }

    private function normalizeGender($value): ?string
    {
        $normalized = strtoupper(trim((string) $value));

        return in_array($normalized, ['L', 'P'], true) ? $normalized : null;
    }

    private function normalizeDate($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            }

            $normalized = $this->nullableString($value);
            if (!$normalized) {
                return null;
            }

            return Carbon::parse($normalized)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }
}
