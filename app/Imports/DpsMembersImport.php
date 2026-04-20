<?php

namespace App\Imports;

use App\Models\DpsMember;
use App\Models\Madrasah;
use App\Services\DpsAccountService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DpsMembersImport implements ToCollection, WithHeadingRow
{
    public int $createdCount = 0;
    public int $skippedCount = 0;

    /** @var array<int, string> */
    public array $errors = [];

    /** @var array<int, array{nama:string,email:string,password:string}> */
    public array $createdCredentials = [];

    public function collection(Collection $rows)
    {
        $accountService = new DpsAccountService();

        foreach ($rows as $i => $row) {
            // HeadingRow: first data row is excel row 2
            $excelRow = $i + 2;

            $scod = trim((string)($row['scod'] ?? $row['school_scod'] ?? ''));
            $nama = trim((string)($row['nama_dps'] ?? $row['nama'] ?? ''));
            $unsurRaw = trim((string)($row['unsur_dps'] ?? $row['unsur'] ?? ''));
            $unsur = $this->normalizeUnsur($unsurRaw);
            $periode = trim((string)($row['periode'] ?? ''));

            // Skip empty row
            if ($scod === '' && $nama === '' && $unsur === '' && $periode === '') {
                $this->skippedCount++;
                continue;
            }

            if ($scod === '' || $nama === '' || $unsur === '' || $periode === '') {
                $this->errors[] = "Baris {$excelRow}: kolom wajib kosong (scod, nama_dps, unsur_dps, periode).";
                $this->skippedCount++;
                continue;
            }

            if (!in_array($unsur, DpsMember::UNSUR_OPTIONS, true)) {
                $allowed = implode(' | ', DpsMember::UNSUR_OPTIONS);
                $this->errors[] = "Baris {$excelRow}: unsur_dps '{$unsurRaw}' tidak valid. Pilihan: {$allowed}.";
                $this->skippedCount++;
                continue;
            }

            $madrasah = Madrasah::where('scod', $scod)->first();
            if (!$madrasah) {
                $this->errors[] = "Baris {$excelRow}: SCOD '{$scod}' tidak ditemukan di data madrasah.";
                $this->skippedCount++;
                continue;
            }

            // Avoid duplicates on re-import
            $member = DpsMember::firstOrCreate(
                [
                    'madrasah_id' => $madrasah->id,
                    'nama' => $nama,
                    'unsur' => $unsur,
                    'periode' => $periode,
                ],
                [
                    'user_id' => null,
                ]
            );

            if (!$member->wasRecentlyCreated) {
                // Ensure user exists even for previously-created rows.
                $member->loadMissing('madrasah');
                $accountService->ensureUser($member);
                $this->skippedCount++;
                continue;
            }

            $this->createdCount++;

            $member->loadMissing('madrasah');
            $cred = $accountService->ensureUser($member);
            if ($cred) {
                $this->createdCredentials[] = [
                    'nama' => $member->nama,
                    'email' => $cred['email'],
                    'password' => $cred['password'],
                ];
            }
        }
    }

    private function normalizeUnsur(string $unsur): string
    {
        $u = preg_replace('/\\s+/', ' ', trim($unsur));

        if (strcasecmp($u, "Pengurus LP Ma'arif NU PWNU DIY") === 0) {
            return "Pengurus LP. Ma'arif NU PWNU DIY";
        }

        return $u;
    }
}
