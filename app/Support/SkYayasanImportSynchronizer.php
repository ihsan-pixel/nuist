<?php

namespace App\Support;

use App\Models\SkYayasanEmployeeData;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SkYayasanImportSynchronizer
{
    public function __construct(private readonly int $madrasahId)
    {
    }

    public static function expectedHeadings(): array
    {
        return [
            'No',
            'NUIST ID',
            'Nama',
            'Gelar',
            'Tempat Lahir',
            'Tanggal Lahir',
            "NIP Ma'arif",
            'NUPTK',
            'Nomor Kartanu',
            'TMT Pertama',
            'Masa Kerja',
            'Pendidikan Terakhir',
            'Tahun Lulus',
            'Program Studi',
            'Mapel/Tugas yang Diampu',
            'Penilaian Kinerja',
            'Keterangan',
        ];
    }

    public static function headingKeyMap(): array
    {
        return [
            'No' => 'no',
            'NUIST ID' => 'nuist_id',
            'Nama' => 'nama',
            'Gelar' => 'gelar',
            'Tempat Lahir' => 'tempat_lahir',
            'Tanggal Lahir' => 'tanggal_lahir',
            "NIP Ma'arif" => 'nip_ma_arif',
            'NUPTK' => 'nuptk',
            'Nomor Kartanu' => 'nomor_kartanu',
            'TMT Pertama' => 'tmt_pertama',
            'Masa Kerja' => 'masa_kerja',
            'Pendidikan Terakhir' => 'pendidikan_terakhir',
            'Tahun Lulus' => 'tahun_lulus',
            'Program Studi' => 'program_studi',
            'Mapel/Tugas yang Diampu' => 'mapel_tugas_yang_diampu',
            'Penilaian Kinerja' => 'penilaian_kinerja',
            'Keterangan' => 'keterangan',
        ];
    }

    public static function normalizeHeading(?string $heading): string
    {
        $heading = trim((string) $heading);
        $heading = str_replace(["\r", "\n"], ' ', $heading);
        $heading = preg_replace('/[^A-Za-z0-9]+/u', '_', $heading);

        return trim(Str::lower((string) $heading), '_');
    }

    public function inspectSheet(array $sheet): array
    {
        $headerRow = array_values($sheet[0] ?? []);
        $expectedHeadings = self::expectedHeadings();
        $expectedNormalized = array_map([self::class, 'normalizeHeading'], $expectedHeadings);
        $actualNormalized = array_map(fn ($heading) => self::normalizeHeading((string) $heading), $headerRow);

        $missingNormalized = array_values(array_diff($expectedNormalized, $actualNormalized));
        $unexpectedNormalized = array_values(array_diff($actualNormalized, $expectedNormalized));
        $missingHeadings = [];
        $unexpectedHeadings = [];
        $headingMap = [];

        foreach ($missingNormalized as $normalized) {
            $index = array_search($normalized, $expectedNormalized, true);
            $missingHeadings[] = $index !== false ? $expectedHeadings[$index] : $normalized;
        }

        foreach ($unexpectedNormalized as $normalized) {
            $index = array_search($normalized, $actualNormalized, true);
            $unexpectedHeadings[] = $index !== false ? (string) ($headerRow[$index] ?? $normalized) : $normalized;
        }

        foreach ($headerRow as $index => $heading) {
            $normalized = self::normalizeHeading((string) $heading);

            if ($normalized !== '') {
                $headingMap[$index] = $normalized;
            }
        }

        $rows = [];
        $validUserIds = [];
        $validCount = 0;
        $invalidCount = 0;

        foreach (array_slice($sheet, 1) as $index => $row) {
            if ($this->rowIsEmpty($row)) {
                continue;
            }

            $rowData = [];

            foreach ($row as $columnIndex => $value) {
                if (!array_key_exists($columnIndex, $headingMap)) {
                    continue;
                }

                $rowData[$headingMap[$columnIndex]] = $value;
            }

            $analysis = $this->analyzeRow($rowData, $index + 2);
            $rows[] = $analysis;

            if ($analysis['is_valid']) {
                $validCount++;
                $validUserIds[] = $analysis['user_id'];
            } else {
                $invalidCount++;
            }
        }

        $validUserIds = array_values(array_unique(array_filter($validUserIds)));

        return [
            'expected_headings' => $expectedHeadings,
            'headings_valid' => empty($missingHeadings) && empty($unexpectedHeadings),
            'missing_headings' => $missingHeadings,
            'unexpected_headings' => $unexpectedHeadings,
            'rows' => $rows,
            'valid_count' => $validCount,
            'invalid_count' => $invalidCount,
            'valid_user_ids' => $validUserIds,
            'can_upload' => empty($missingHeadings) && empty($unexpectedHeadings) && $validCount > 0 && $invalidCount === 0,
        ];
    }

    public function analyzeRow(array $rowData, int $rowNumber): array
    {
        $errors = [];
        $user = $this->resolveUser($rowData);

        if (
            !$this->nullableString($rowData['nuist_id'] ?? null)
            && !$this->nullableString($rowData['nip_ma_arif'] ?? null)
            && !$this->nullableString($rowData['nuptk'] ?? null)
            && !$this->nullableString($rowData['nama'] ?? null)
        ) {
            $errors[] = 'Isi minimal salah satu data pencocokan: NUIST ID, NIP Ma\'arif, NUPTK, atau Nama.';
        }

        if (!$user) {
            $errors[] = 'User tidak ditemukan pada database sekolah ini.';
        }

        $tanggalLahir = $this->parseDateValue($rowData['tanggal_lahir'] ?? null);
        if (($rowData['tanggal_lahir'] ?? null) !== null && ($rowData['tanggal_lahir'] ?? null) !== '' && $tanggalLahir === null) {
            $errors[] = 'Tanggal Lahir tidak valid.';
        }

        $tmtPertama = $this->parseDateValue($rowData['tmt_pertama'] ?? null);
        if (($rowData['tmt_pertama'] ?? null) !== null && ($rowData['tmt_pertama'] ?? null) !== '' && $tmtPertama === null) {
            $errors[] = 'TMT Pertama tidak valid.';
        }

        $tahunLulus = $this->parseYearValue($rowData['tahun_lulus'] ?? null);
        if (($rowData['tahun_lulus'] ?? null) !== null && ($rowData['tahun_lulus'] ?? null) !== '' && $tahunLulus === null) {
            $errors[] = 'Tahun Lulus harus 4 digit.';
        }

        $penilaianKinerja = $this->parseDecimalValue($rowData['penilaian_kinerja'] ?? null);
        if (($rowData['penilaian_kinerja'] ?? null) !== null && ($rowData['penilaian_kinerja'] ?? null) !== '' && $penilaianKinerja === null) {
            $errors[] = 'Penilaian Kinerja harus berupa angka.';
        }

        $userPayload = array_filter([
            'nuist_id' => $this->nullableString($rowData['nuist_id'] ?? null),
            'name' => $this->nullableString($rowData['nama'] ?? null),
            'gelar' => $this->nullableString($rowData['gelar'] ?? null),
            'tempat_lahir' => $this->nullableString($rowData['tempat_lahir'] ?? null),
            'tanggal_lahir' => $tanggalLahir,
            'nip' => $this->nullableString($rowData['nip_ma_arif'] ?? null),
            'nuptk' => $this->nullableString($rowData['nuptk'] ?? null),
            'kartanu' => $this->nullableString($rowData['nomor_kartanu'] ?? null),
            'tmt' => $tmtPertama,
            'masa_kerja' => $this->nullableString($rowData['masa_kerja'] ?? null),
            'pendidikan_terakhir' => $this->nullableString($rowData['pendidikan_terakhir'] ?? null),
            'tahun_lulus' => $tahunLulus,
            'program_studi' => $this->nullableString($rowData['program_studi'] ?? null),
            'mengajar' => $this->nullableString($rowData['mapel_tugas_yang_diampu'] ?? null),
        ], fn ($value) => $value !== null && $value !== '');

        $skPayload = array_filter([
            'penilaian_kinerja' => $penilaianKinerja,
            'keterangan' => $this->nullableString($rowData['keterangan'] ?? null),
        ], fn ($value) => $value !== null && $value !== '');

        if (empty($userPayload) && empty($skPayload)) {
            $errors[] = 'Tidak ada data yang bisa disinkronkan.';
        }

        return [
            'row_number' => $rowNumber,
            'source_name' => $this->nullableString($rowData['nama'] ?? null) ?? '-',
            'source_columns' => $this->buildSourceColumns($rowData),
            'matched_name' => $user?->name,
            'user_id' => $user?->id,
            'is_valid' => empty($errors),
            'errors' => $errors,
            'user_payload' => $userPayload,
            'sk_payload' => $skPayload,
            'status_label' => empty($errors) ? 'Siap upload' : 'Perlu perbaikan',
        ];
    }

    private function buildSourceColumns(array $rowData): array
    {
        $columns = [];

        foreach (self::headingKeyMap() as $heading => $key) {
            $columns[$heading] = $this->formatSourceColumnValue($key, $rowData[$key] ?? null);
        }

        return $columns;
    }

    private function formatSourceColumnValue(string $key, mixed $value): string
    {
        if ($value === null || trim((string) $value) === '') {
            return '-';
        }

        if (in_array($key, ['tanggal_lahir', 'tmt_pertama'], true)) {
            $parsedDate = $this->parseDateValue($value);

            return $parsedDate ? Carbon::parse($parsedDate)->translatedFormat('d F Y') : (string) $value;
        }

        return trim((string) $value);
    }

    public function syncRow(User $user, array $userPayload, array $skPayload): bool
    {
        $hasChanges = false;

        if (!empty($userPayload)) {
            $user->fill($userPayload);
        }

        if ($user->isDirty()) {
            $user->save();
            $hasChanges = true;
        }

        if (!empty($skPayload)) {
            $skYayasanData = SkYayasanEmployeeData::firstOrNew([
                'user_id' => $user->id,
            ]);

            $skYayasanData->fill($skPayload);

            if ($skYayasanData->isDirty()) {
                $skYayasanData->save();
                $hasChanges = true;
            }
        }

        return $hasChanges;
    }

    public function resolveUser(array $row): ?User
    {
        $query = User::query()
            ->where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $this->madrasahId);

        $nuistId = $this->nullableString($row['nuist_id'] ?? null);
        if ($nuistId) {
            return (clone $query)->where('nuist_id', $nuistId)->first();
        }

        $nip = $this->nullableString($row['nip_ma_arif'] ?? $row['nip_maarif'] ?? null);
        if ($nip) {
            return (clone $query)->where('nip', $nip)->first();
        }

        $nuptk = $this->nullableString($row['nuptk'] ?? null);
        if ($nuptk) {
            return (clone $query)->where('nuptk', $nuptk)->first();
        }

        $name = $this->nullableString($row['nama'] ?? null);
        if ($name) {
            return (clone $query)->whereRaw('LOWER(name) = ?', [Str::lower($name)])->first();
        }

        return null;
    }

    public function nullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $string = trim((string) $value);

        return $string === '' ? null : $string;
    }

    public function parseDateValue(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject($value))->format('Y-m-d');
        }

        $normalized = str_ireplace(
            ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            trim((string) $value)
        );

        foreach (['d F Y', 'd/m/Y', 'Y-m-d', 'd-m-Y'] as $format) {
            try {
                return Carbon::createFromFormat($format, $normalized)->format('Y-m-d');
            } catch (\Throwable $e) {
            }
        }

        try {
            return Carbon::parse($normalized)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function parseYearValue(mixed $value): ?string
    {
        $string = $this->nullableString($value);

        if (!$string) {
            return null;
        }

        return preg_match('/^\d{4}$/', $string) ? $string : null;
    }

    public function parseDecimalValue(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $normalized = str_replace(',', '.', trim((string) $value));

        return is_numeric($normalized) ? (float) $normalized : null;
    }

    private function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== null && trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }
}
