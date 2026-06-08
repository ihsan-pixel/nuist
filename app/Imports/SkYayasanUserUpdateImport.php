<?php

namespace App\Imports;

use App\Models\SkYayasanEmployeeData;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SkYayasanUserUpdateImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $updated = 0;
    public int $skipped = 0;
    public int $notFound = 0;
    public array $errors = [];

    public function __construct(private readonly int $madrasahId)
    {
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $rowData = $row->toArray();

            $user = $this->resolveUser($rowData);

            if (!$user) {
                $this->notFound++;
                $this->skipped++;
                $this->errors[] = "Baris {$rowNumber}: user tidak ditemukan untuk sekolah ini.";
                continue;
            }

            $userPayload = array_filter([
                'nuist_id' => $this->nullableString($rowData['nuist_id'] ?? null),
                'name' => $this->nullableString($rowData['nama'] ?? null),
                'gelar' => $this->nullableString($rowData['gelar'] ?? null),
                'tempat_lahir' => $this->nullableString($rowData['tempat_lahir'] ?? null),
                'tanggal_lahir' => $this->parseDateValue($rowData['tanggal_lahir'] ?? null),
                'nip' => $this->nullableString($rowData['nip_maarif'] ?? null),
                'nuptk' => $this->nullableString($rowData['nuptk'] ?? null),
                'kartanu' => $this->nullableString($rowData['nomor_kartanu'] ?? null),
                'tmt' => $this->parseDateValue($rowData['tmt_pertama'] ?? null),
                'masa_kerja' => $this->nullableString($rowData['masa_kerja'] ?? null),
                'pendidikan_terakhir' => $this->nullableString($rowData['pendidikan_terakhir'] ?? null),
                'tahun_lulus' => $this->parseYearValue($rowData['tahun_lulus'] ?? null),
                'program_studi' => $this->nullableString($rowData['program_studi'] ?? null),
                'mengajar' => $this->nullableString($rowData['mapel_tugas_yang_diampu'] ?? null),
            ], fn ($value) => $value !== null && $value !== '');

            $skYayasanPayload = array_filter([
                'penilaian_kinerja' => $this->parseDecimalValue($rowData['penilaian_kinerja'] ?? null),
                'keterangan' => $this->nullableString($rowData['keterangan'] ?? null),
            ], fn ($value) => $value !== null && $value !== '');

            if (empty($userPayload) && empty($skYayasanPayload)) {
                $this->skipped++;
                $this->errors[] = "Baris {$rowNumber}: tidak ada data yang bisa diperbarui.";
                continue;
            }

            $hasChanges = false;

            if (!empty($userPayload)) {
                $user->fill($userPayload);
            }

            if ($user->isDirty()) {
                $user->save();
                $hasChanges = true;
            }

            if (!empty($skYayasanPayload)) {
                $skYayasanData = SkYayasanEmployeeData::firstOrNew([
                    'user_id' => $user->id,
                ]);

                $skYayasanData->fill($skYayasanPayload);

                if ($skYayasanData->isDirty()) {
                    $skYayasanData->save();
                    $hasChanges = true;
                }
            }

            if ($hasChanges) {
                $this->updated++;
                continue;
            }

            $this->skipped++;
            $this->errors[] = "Baris {$rowNumber}: data sama dengan database, tidak ada perubahan.";
        }
    }

    private function resolveUser(array $row): ?User
    {
        $query = User::query()
            ->where('role', 'tenaga_pendidik')
            ->where('madrasah_id', $this->madrasahId);

        $nuistId = $this->nullableString($row['nuist_id'] ?? null);
        if ($nuistId) {
            return (clone $query)->where('nuist_id', $nuistId)->first();
        }

        $nip = $this->nullableString($row['nip_maarif'] ?? null);
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

    private function nullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $string = trim((string) $value);

        return $string === '' ? null : $string;
    }

    private function parseDateValue(mixed $value): ?string
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

        $formats = ['d F Y', 'd/m/Y', 'Y-m-d', 'd-m-Y'];

        foreach ($formats as $format) {
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

    private function parseYearValue(mixed $value): ?string
    {
        $string = $this->nullableString($value);

        if (!$string) {
            return null;
        }

        return preg_match('/^\d{4}$/', $string) ? $string : null;
    }

    private function parseDecimalValue(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $normalized = str_replace(',', '.', trim((string) $value));

        return is_numeric($normalized) ? (float) $normalized : null;
    }
}
