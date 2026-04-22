<?php

namespace App\Imports;

use App\Models\Madrasah;
use App\Models\Siswa;
use App\Models\SppSiswaBill;
use App\Models\SppSiswaSetting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SppSiswaBillImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $created = 0;
    public int $skipped = 0;

    public function __construct(
        private readonly Madrasah $madrasah,
        private readonly ?SppSiswaSetting $setting = null
    ) {
    }

    public function collection(Collection $rows): void
    {
        DB::transaction(function () use ($rows) {
            foreach ($rows as $index => $row) {
                $line = $index + 2;

                foreach (['nis', 'jenis_tagihan', 'periode', 'jatuh_tempo', 'nominal'] as $field) {
                    if (blank($row[$field] ?? null)) {
                        throw new \InvalidArgumentException("Baris {$line}: kolom {$field} wajib diisi.");
                    }
                }

                $nis = trim((string) $row['nis']);
                $jenisTagihan = trim((string) $row['jenis_tagihan']);
                $periodeDate = $this->parsePeriod($row['periode'], $line);
                $jatuhTempo = $this->parseDate($row['jatuh_tempo'], $line, 'jatuh_tempo');
                $nominal = $this->parseNominal($row['nominal'], $line);
                $status = $this->parseStatus($row['status'] ?? null, $line);
                $catatan = filled($row['catatan'] ?? null) ? trim((string) $row['catatan']) : null;

                $siswa = Siswa::query()
                    ->where('madrasah_id', $this->madrasah->id)
                    ->where('nis', $nis)
                    ->first();

                if (!$siswa) {
                    throw new \InvalidArgumentException("Baris {$line}: siswa dengan NIS {$nis} tidak ditemukan pada madrasah {$this->madrasah->name}.");
                }

                $exists = SppSiswaBill::query()
                    ->where('siswa_id', $siswa->id)
                    ->where('periode', $periodeDate->format('Y-m'))
                    ->where('jenis_tagihan', $jenisTagihan)
                    ->exists();

                if ($exists) {
                    $this->skipped++;
                    continue;
                }

                SppSiswaBill::create([
                    'siswa_id' => $siswa->id,
                    'madrasah_id' => $siswa->madrasah_id,
                    'setting_id' => $this->setting?->id,
                    'jenis_tagihan' => $jenisTagihan,
                    'nomor_tagihan' => SppSiswaBill::makeBillNumber($siswa, $periodeDate, $jenisTagihan),
                    'periode' => $periodeDate->format('Y-m'),
                    'jatuh_tempo' => $jatuhTempo,
                    'nominal' => $nominal,
                    'total_tagihan' => $nominal,
                    'status' => $status,
                    'catatan' => $catatan,
                ]);

                $this->created++;
            }
        });
    }

    private function parsePeriod(mixed $value, int $line): Carbon
    {
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value)->startOfMonth();
        }

        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value))->startOfMonth();
        }

        $period = trim((string) $value);

        if (preg_match('/^\d{4}-\d{2}$/', $period)) {
            return Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        }

        try {
            return Carbon::parse($period)->startOfMonth();
        } catch (\Throwable) {
            throw new \InvalidArgumentException("Baris {$line}: format periode tidak valid. Gunakan YYYY-MM, contoh 2026-04.");
        }
    }

    private function parseDate(mixed $value, int $line, string $field): string
    {
        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value)->toDateString();
        }

        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value))->toDateString();
        }

        try {
            return Carbon::parse(trim((string) $value))->toDateString();
        } catch (\Throwable) {
            throw new \InvalidArgumentException("Baris {$line}: format {$field} tidak valid. Gunakan YYYY-MM-DD, contoh 2026-04-10.");
        }
    }

    private function parseNominal(mixed $value, int $line): float
    {
        $normalized = is_numeric($value)
            ? (string) $value
            : str_replace(['Rp', 'rp', ' ', '.'], '', (string) $value);
        $normalized = str_replace(',', '.', $normalized);

        if (!is_numeric($normalized) || (float) $normalized < 0) {
            throw new \InvalidArgumentException("Baris {$line}: nominal harus berupa angka dan minimal 0.");
        }

        return (float) $normalized;
    }

    private function parseStatus(mixed $value, int $line): string
    {
        if (blank($value)) {
            return 'belum_lunas';
        }

        $status = str_replace([' ', '-'], '_', strtolower(trim((string) $value)));
        $allowedStatuses = ['belum_lunas', 'sebagian', 'lunas'];

        if (!in_array($status, $allowedStatuses, true)) {
            throw new \InvalidArgumentException("Baris {$line}: status tidak valid. Gunakan belum_lunas, sebagian, atau lunas.");
        }

        return $status;
    }
}
