<?php

namespace App\Imports;

use App\Models\Madrasah;
use App\Models\TeachingSchedule;
use App\Models\TeachingSchedulePeriod;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeachingScheduleImport implements ToCollection, WithHeadingRow
{
    private const ALLOWED_DAYS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    protected $createdBy;
    protected TeachingSchedulePeriod $period;
    protected array $errors = [];
    protected array $schoolCache = [];
    protected array $teacherCache = [];

    public function __construct($createdBy, TeachingSchedulePeriod $period)
    {
        $this->createdBy = $createdBy;
        $this->period = $period;
    }

    public function collection(Collection $rows): void
    {
        $preparedRows = [];

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $rowData = $this->normalizeRow($row->toArray());

            if ($this->isRowEmpty($rowData)) {
                continue;
            }

            try {
                $preparedRows[] = $this->prepareRow($rowData, $rowNumber);
            } catch (\Throwable $e) {
                $this->errors[] = $e->getMessage();
                Log::error('Failed to validate teaching schedule import row', [
                    'row' => $rowNumber,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        if (!empty($this->errors)) {
            return;
        }

        $teacherBuckets = [];
        $classBuckets = [];

        foreach ($preparedRows as $preparedRow) {
            $this->validateAgainstExistingSchedules($preparedRow);
            $this->validateAgainstImportedSchedules($preparedRow, $teacherBuckets, $classBuckets);
        }

        if (!empty($this->errors)) {
            return;
        }

        DB::transaction(function () use ($preparedRows) {
            foreach ($preparedRows as $preparedRow) {
                TeachingSchedule::create([
                    'school_id' => $preparedRow['school_id'],
                    'teaching_schedule_period_id' => $this->period->id,
                    'teacher_id' => $preparedRow['teacher_id'],
                    'day' => $preparedRow['day'],
                    'subject' => $preparedRow['subject'],
                    'class_name' => $preparedRow['class_name'],
                    'start_time' => $preparedRow['start_time'],
                    'end_time' => $preparedRow['end_time'],
                    'created_by' => $this->createdBy,
                ]);
            }
        });
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            $normalized[(string) $key] = is_string($value) ? trim($value) : $value;
        }

        return $normalized;
    }

    private function isRowEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if (is_string($value) && trim($value) !== '') {
                return false;
            }

            if (!is_string($value) && $value !== null && $value !== '') {
                return false;
            }
        }

        return true;
    }

    private function prepareRow(array $row, int $rowNumber): array
    {
        $requiredFields = [
            'school_scod',
            'teacher_nuist_id',
            'day',
            'subject',
            'class_name',
            'start_time',
            'end_time',
        ];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $row) || $this->valueIsEmpty($row[$field])) {
                throw new \RuntimeException("Baris {$rowNumber}: kolom {$field} wajib diisi.");
            }
        }

        $rawDay = (string) $row['day'];
        $day = $this->normalizeDayValue($rawDay);
        if ($day === null) {
            throw new \RuntimeException(
                "Baris {$rowNumber}: hari '" . trim($rawDay) . "' tidak valid. Gunakan salah satu dari " . implode(', ', self::ALLOWED_DAYS) . '.'
            );
        }

        $schoolScod = trim((string) $row['school_scod']);
        $madrasah = $this->resolveSchool($schoolScod);
        if (!$madrasah) {
            throw new \RuntimeException("Baris {$rowNumber}: kode SCOD madrasah '{$schoolScod}' tidak ditemukan.");
        }

        if ((int) $madrasah->id !== (int) $this->period->school_id) {
            throw new \RuntimeException(
                "Baris {$rowNumber}: kode SCOD madrasah '{$schoolScod}' tidak sesuai dengan periode yang dipilih."
            );
        }

        $teacherNuistId = trim((string) $row['teacher_nuist_id']);
        $teacher = $this->resolveTeacher($teacherNuistId);
        if (!$teacher) {
            throw new \RuntimeException(
                "Baris {$rowNumber}: NUist ID guru '{$teacherNuistId}' tidak ditemukan atau bukan tenaga pendidik."
            );
        }

        $startTime = $this->normalizeTimeValue($row['start_time'], 'start_time', $rowNumber);
        $endTime = $this->normalizeTimeValue($row['end_time'], 'end_time', $rowNumber);

        if ($startTime >= $endTime) {
            throw new \RuntimeException("Baris {$rowNumber}: jam selesai harus lebih besar dari jam mulai.");
        }

        return [
            'row_number' => $rowNumber,
            'school_id' => (int) $madrasah->id,
            'school_scod' => $schoolScod,
            'teacher_id' => (int) $teacher->id,
            'teacher_name' => trim((string) $teacher->name),
            'teacher_nuist_id' => $teacherNuistId,
            'day' => $day,
            'subject' => trim((string) $row['subject']),
            'class_name' => trim((string) $row['class_name']),
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }

    private function validateAgainstExistingSchedules(array $row): void
    {
        $teacherOverlap = TeachingSchedule::query()
            ->with('teacher:id,name')
            ->where('teacher_id', $row['teacher_id'])
            ->where('teaching_schedule_period_id', $this->period->id)
            ->where('day', $row['day'])
            ->where(function ($query) use ($row) {
                $query->where('start_time', '<', $row['end_time'])
                    ->where('end_time', '>', $row['start_time']);
            })
            ->first();

        if ($teacherOverlap) {
            $this->errors[] = sprintf(
                'Baris %d: jadwal guru %s bentrok dengan jadwal yang sudah ada, yaitu %s kelas %s pada hari %s jam %s-%s.',
                $row['row_number'],
                $row['teacher_name'],
                trim((string) $teacherOverlap->subject),
                trim((string) $teacherOverlap->class_name),
                $row['day'],
                substr((string) $teacherOverlap->start_time, 0, 5),
                substr((string) $teacherOverlap->end_time, 0, 5),
            );
        }

        if (in_array((int) $row['school_id'], [8, 9], true)) {
            return;
        }

        $classOverlap = TeachingSchedule::query()
            ->with('teacher:id,name')
            ->where('school_id', $row['school_id'])
            ->where('teaching_schedule_period_id', $this->period->id)
            ->where('class_name', $row['class_name'])
            ->where('day', $row['day'])
            ->where(function ($query) use ($row) {
                $query->where('start_time', '<', $row['end_time'])
                    ->where('end_time', '>', $row['start_time']);
            })
            ->first();

        if ($classOverlap) {
            $teacherName = trim((string) optional($classOverlap->teacher)->name);

            $this->errors[] = sprintf(
                'Baris %d: kelas %s bentrok dengan jadwal %s milik %s pada hari %s jam %s-%s.',
                $row['row_number'],
                $row['class_name'],
                trim((string) $classOverlap->subject),
                $teacherName !== '' ? 'guru ' . $teacherName : 'guru lain',
                $row['day'],
                substr((string) $classOverlap->start_time, 0, 5),
                substr((string) $classOverlap->end_time, 0, 5),
            );
        }
    }

    private function validateAgainstImportedSchedules(array $row, array &$teacherBuckets, array &$classBuckets): void
    {
        $teacherKey = $row['teacher_id'] . '|' . $row['day'];
        foreach ($teacherBuckets[$teacherKey] ?? [] as $existingRow) {
            if ($this->timesOverlap($row['start_time'], $row['end_time'], $existingRow['start_time'], $existingRow['end_time'])) {
                $this->errors[] = sprintf(
                    'Baris %d: jadwal guru %s bentrok dengan baris %d pada hari %s jam %s-%s.',
                    $row['row_number'],
                    $row['teacher_name'],
                    $existingRow['row_number'],
                    $row['day'],
                    $existingRow['start_time'],
                    $existingRow['end_time'],
                );
            }
        }
        $teacherBuckets[$teacherKey][] = $row;

        if (in_array((int) $row['school_id'], [8, 9], true)) {
            return;
        }

        $classKey = $row['school_id'] . '|' . mb_strtolower($row['class_name']) . '|' . $row['day'];
        foreach ($classBuckets[$classKey] ?? [] as $existingRow) {
            if ($this->timesOverlap($row['start_time'], $row['end_time'], $existingRow['start_time'], $existingRow['end_time'])) {
                $this->errors[] = sprintf(
                    'Baris %d: kelas %s bentrok dengan baris %d (%s - %s) pada hari %s jam %s-%s.',
                    $row['row_number'],
                    $row['class_name'],
                    $existingRow['row_number'],
                    $existingRow['teacher_name'],
                    $existingRow['subject'],
                    $row['day'],
                    $existingRow['start_time'],
                    $existingRow['end_time'],
                );
            }
        }
        $classBuckets[$classKey][] = $row;
    }

    private function resolveSchool(string $schoolScod): ?Madrasah
    {
        if (!array_key_exists($schoolScod, $this->schoolCache)) {
            $this->schoolCache[$schoolScod] = Madrasah::query()
                ->where('scod', $schoolScod)
                ->first();
        }

        return $this->schoolCache[$schoolScod];
    }

    private function resolveTeacher(string $teacherNuistId): ?User
    {
        if (!array_key_exists($teacherNuistId, $this->teacherCache)) {
            $this->teacherCache[$teacherNuistId] = User::query()
                ->where('nuist_id', $teacherNuistId)
                ->where('role', 'tenaga_pendidik')
                ->first();
        }

        return $this->teacherCache[$teacherNuistId];
    }

    private function normalizeTimeValue(mixed $value, string $field, int $rowNumber): string
    {
        if ($this->valueIsEmpty($value)) {
            throw new \RuntimeException("Baris {$rowNumber}: kolom {$field} wajib diisi.");
        }

        if (is_numeric($value) && !is_string($value)) {
            return $this->normalizeNumericTime((float) $value, $field, $rowNumber);
        }

        $stringValue = trim((string) $value);
        if ($stringValue === '') {
            throw new \RuntimeException("Baris {$rowNumber}: kolom {$field} wajib diisi.");
        }

        if (preg_match('/^\d{1,2}[:.,;\-\s]\d{1,2}(?:[:.,;\-\s]\d{1,2})?$/', $stringValue)) {
            $parts = preg_split('/[:.,;\-\s]+/', $stringValue);

            return $this->buildTimeFromParts($parts, $field, $rowNumber);
        }

        if (preg_match('/^\d{3,4}$/', $stringValue)) {
            $stringValue = str_pad($stringValue, 4, '0', STR_PAD_LEFT);

            return $this->buildTimeFromParts([
                substr($stringValue, 0, 2),
                substr($stringValue, 2, 2),
            ], $field, $rowNumber);
        }

        if (preg_match('/^\d{1,2}$/', $stringValue)) {
            return $this->buildTimeFromParts([$stringValue, '00'], $field, $rowNumber);
        }

        if (is_numeric(str_replace(',', '.', $stringValue)) && !str_contains($stringValue, ':') && !str_contains($stringValue, '.') && !str_contains($stringValue, ',')) {
            return $this->normalizeNumericTime((float) str_replace(',', '.', $stringValue), $field, $rowNumber);
        }

        throw new \RuntimeException(
            "Baris {$rowNumber}: format {$field} tidak dikenali. Gunakan contoh seperti 09:00, 09.00, 09,00, 0900, atau cell waktu Excel."
        );
    }

    private function normalizeNumericTime(float $value, string $field, int $rowNumber): string
    {
        if ($value >= 0 && $value < 1) {
            $minutes = (int) round($value * 24 * 60);
            $minutes %= (24 * 60);

            return sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
        }

        if (fmod($value, 1.0) === 0.0) {
            $digits = (string) (int) $value;

            if (strlen($digits) <= 2) {
                return $this->buildTimeFromParts([$digits, '00'], $field, $rowNumber);
            }

            if (strlen($digits) <= 4) {
                $digits = str_pad($digits, 4, '0', STR_PAD_LEFT);

                return $this->buildTimeFromParts([
                    substr($digits, 0, 2),
                    substr($digits, 2, 2),
                ], $field, $rowNumber);
            }
        }

        throw new \RuntimeException(
            "Baris {$rowNumber}: format {$field} tidak valid. Gunakan contoh seperti 09:00, 09.00, 09,00, 0900, atau cell waktu Excel."
        );
    }

    private function buildTimeFromParts(array $parts, string $field, int $rowNumber): string
    {
        $hour = isset($parts[0]) ? (int) $parts[0] : null;
        $minute = isset($parts[1]) ? (int) $parts[1] : 0;

        if ($hour === null || $hour < 0 || $hour > 23 || $minute < 0 || $minute > 59) {
            throw new \RuntimeException(
                "Baris {$rowNumber}: nilai {$field} tidak valid. Gunakan jam antara 00:00 sampai 23:59."
            );
        }

        return sprintf('%02d:%02d', $hour, $minute);
    }

    private function normalizeDayValue(string $value): ?string
    {
        $normalized = preg_replace('/\s+/u', ' ', trim($value));
        if ($normalized === null || $normalized === '') {
            return null;
        }

        $lookup = [
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
        ];

        $key = mb_strtolower($normalized);

        return $lookup[$key] ?? null;
    }

    private function valueIsEmpty(mixed $value): bool
    {
        if (is_string($value)) {
            return trim($value) === '';
        }

        return $value === null || $value === '';
    }

    private function timesOverlap(string $startA, string $endA, string $startB, string $endB): bool
    {
        return $startA < $endB && $endA > $startB;
    }
}
