<?php

namespace App\Imports;

use App\Models\TalentaKehadiranPeserta;
use App\Models\TalentaMateri;
use App\Models\TalentaPeserta;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class TalentaKehadiranPesertaImport implements ToCollection, WithHeadingRow
{
    protected array $errors = [];
    protected int $processedCount = 0;

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            return;
        }

        if (! $this->looksLikeTemplateSheet($rows->first())) {
            return;
        }

        foreach ($rows as $index => $row) {
            if ($this->isEmptyRow($row)) {
                continue;
            }

            $rowNumber = $index + 2;

            try {
                $tanggalRaw = $this->getRowValue($row, ['tanggal', 'tgl', 'tanggal_kehadiran', 'tanggal_presensi']);
                $kodePesertaRaw = $this->getRowValue($row, ['kode_peserta', 'kode peserta', 'kodepeserta']);
                $materiIdRaw = $this->getRowValue($row, ['materi_id', 'materi id', 'id_materi']);
                $statusRaw = $this->getRowValue($row, ['status_kehadiran', 'status kehadiran', 'status']);
                $sesiRaw = $this->getRowValue($row, ['sesi', 'session']);
                $catatanRaw = $this->getRowValue($row, ['catatan', 'keterangan', 'notes']);

                $tanggal = $this->parseTanggal($tanggalRaw);
                $kodePeserta = trim((string) $kodePesertaRaw);
                $materiId = trim((string) $materiIdRaw);
                $status = $this->normalizeStatus($statusRaw);
                $catatan = trim((string) $catatanRaw);

                if ($tanggal === null) {
                    $rawTanggal = $tanggalRaw;
                    $displayTanggal = is_scalar($rawTanggal) ? (string) $rawTanggal : json_encode($rawTanggal);
                    throw new \Exception('Tanggal tidak terbaca. Nilai yang diterima: ' . ($displayTanggal !== '' ? $displayTanggal : '[kosong]') . '. Gunakan format YYYY-MM-DD atau DD/MM/YYYY.');
                }

                if ($kodePeserta === '') {
                    throw new \Exception('kode_peserta wajib diisi.');
                }

                if ($materiId === '' || !ctype_digit($materiId)) {
                    throw new \Exception('materi_id wajib diisi dengan angka.');
                }

                $allowedStatuses = ['hadir', 'telat', 'izin', 'sakit', 'tidak_hadir', 'lainnya'];
                if (! in_array($status, $allowedStatuses, true)) {
                    throw new \Exception('status_kehadiran tidak valid.');
                }

                $peserta = TalentaPeserta::where('kode_peserta', $kodePeserta)->first();
                if (! $peserta) {
                    throw new \Exception("kode_peserta {$kodePeserta} tidak ditemukan.");
                }

                $materi = TalentaMateri::find((int) $materiId);
                if (! $materi) {
                    throw new \Exception("materi_id {$materiId} tidak ditemukan.");
                }

                $sesi = $this->parseSesi($sesiRaw);

                TalentaKehadiranPeserta::updateOrCreate(
                    [
                        'tanggal' => $tanggal,
                        'talenta_peserta_id' => $peserta->id,
                        'materi_id' => $materi->id,
                    ],
                    [
                        'status_kehadiran' => $status,
                        'sesi' => $sesi,
                        'catatan' => $catatan !== '' ? $catatan : null,
                    ]
                );

                $this->processedCount++;
            } catch (\Throwable $e) {
                $this->errors[] = "Baris {$rowNumber}: {$e->getMessage()}";
            }
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getProcessedCount(): int
    {
        return $this->processedCount;
    }

    protected function isEmptyRow($row): bool
    {
        return collect($row)->filter(function ($value) {
            return $value !== null && trim((string) $value) !== '';
        })->isEmpty();
    }

    protected function parseTanggal($value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance(\DateTimeImmutable::createFromInterface($value))->format('Y-m-d');
        }

        if (is_numeric($value)) {
            return Carbon::instance(ExcelDate::excelToDateTimeObject($value))->format('Y-m-d');
        }

        $value = trim((string) $value);
        $value = preg_replace('/^[\'"\s]+|[\'"\s]+$/u', '', $value);
        $value = preg_replace('/\x{00A0}/u', ' ', $value);

        if ($value === '') {
            return null;
        }

        if (ctype_digit($value)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value))->format('Y-m-d');
            } catch (\Throwable $e) {
            }
        }

        $formats = [
            'Y-m-d',
            'd-m-Y',
            'd/m/Y',
            'm/d/Y',
            'Y/m/d',
            'd.m.Y',
            'd-m-y',
            'd/m/y',
            'm/d/y',
            'Ymd',
            'd M Y',
            'd F Y',
            'd-m-Y H:i:s',
            'd/m/Y H:i:s',
            'Y-m-d H:i:s',
            'm/d/Y H:i:s',
        ];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $value)->format('Y-m-d');
            } catch (\Throwable $e) {
            }
        }

        try {
            return Carbon::parse(str_replace('.', '/', $value))->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function parseSesi($value): array
    {
        if ($value === null || trim((string) $value) === '') {
            return ['1', '2', '3', '4'];
        }

        $sesi = collect(explode(',', (string) $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->unique()
            ->values();

        $allowed = ['1', '2', '3', '4'];
        foreach ($sesi as $item) {
            if (! in_array($item, $allowed, true)) {
                throw new \Exception('sesi harus berisi kombinasi 1,2,3,4 dipisahkan koma.');
            }
        }

        return $sesi->all();
    }

    protected function normalizeStatus($value): string
    {
        $status = strtolower(trim((string) $value));
        $status = str_replace(['-', '/'], '_', $status);
        $status = preg_replace('/\s+/', '_', $status);

        return match ($status) {
            'hadir' => 'hadir',
            'telat', 'terlambat' => 'telat',
            'izin', 'ijin' => 'izin',
            'sakit' => 'sakit',
            'tidak_hadir', 'tidakhadir', 'alpha', 'alpa', 'absen' => 'tidak_hadir',
            'lainnya', 'lain', 'other', 'keterangan' => 'lainnya',
            default => $status,
        };
    }

    protected function getRowValue($row, array $aliases)
    {
        $items = $row instanceof Collection ? $row->all() : (array) $row;
        $normalizedItems = [];

        foreach ($items as $key => $value) {
            $normalizedItems[$this->normalizeKey($key)] = $value;
        }

        foreach ($aliases as $alias) {
            $normalizedAlias = $this->normalizeKey($alias);
            if (array_key_exists($normalizedAlias, $normalizedItems)) {
                return $normalizedItems[$normalizedAlias];
            }
        }

        foreach ($normalizedItems as $key => $value) {
            foreach ($aliases as $alias) {
                $normalizedAlias = $this->normalizeKey($alias);
                if ($normalizedAlias !== '' && str_contains($key, $normalizedAlias)) {
                    return $value;
                }
            }
        }

        return null;
    }

    protected function normalizeKey($key): string
    {
        $key = strtolower(trim((string) $key));
        $key = preg_replace('/\x{00A0}/u', ' ', $key);
        $key = str_replace(['-', '/', '.'], '_', $key);
        $key = preg_replace('/\s+/', '_', $key);
        $key = preg_replace('/[^a-z0-9_]/', '', $key);

        return trim((string) $key, '_');
    }

    protected function looksLikeTemplateSheet($row): bool
    {
        $items = $row instanceof Collection ? $row->all() : (array) $row;
        $keys = collect(array_keys($items))
            ->map(fn ($key) => $this->normalizeKey($key))
            ->filter()
            ->values()
            ->all();

        $groups = [
            ['tanggal', 'tgl', 'tanggal_kehadiran', 'tanggal_presensi'],
            ['kode_peserta', 'kodepeserta'],
            ['status_kehadiran', 'status', 'statuskehadiran'],
        ];

        foreach ($groups as $aliases) {
            $found = false;
            foreach ($aliases as $alias) {
                if (in_array($this->normalizeKey($alias), $keys, true)) {
                    $found = true;
                    break;
                }
            }

            if (! $found) {
                return false;
            }
        }

        return true;
    }
}
