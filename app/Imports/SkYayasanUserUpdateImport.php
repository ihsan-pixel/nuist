<?php

namespace App\Imports;

use App\Models\User;
use App\Support\SkYayasanImportSynchronizer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SkYayasanUserUpdateImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $updated = 0;
    public int $skipped = 0;
    public int $notFound = 0;
    public int $validRows = 0;
    public int $invalidRows = 0;
    public array $errors = [];
    public array $matchedUserIds = [];

    private SkYayasanImportSynchronizer $synchronizer;

    public function __construct(private readonly int $madrasahId)
    {
        $this->synchronizer = new SkYayasanImportSynchronizer($this->madrasahId);
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $analysis = $this->synchronizer->analyzeRow($row->toArray(), $index + 2);

            if (!$analysis['is_valid']) {
                $this->invalidRows++;
                $this->skipped++;
                $this->errors[] = 'Baris ' . $analysis['row_number'] . ': ' . implode(' ', $analysis['errors']);

                if (collect($analysis['errors'])->contains(fn ($error) => str_contains($error, 'User tidak ditemukan'))) {
                    $this->notFound++;
                }

                continue;
            }

            $this->validRows++;
            $this->matchedUserIds[] = $analysis['user_id'];

            $user = User::query()->find($analysis['user_id']);

            if (!$user) {
                $this->invalidRows++;
                $this->notFound++;
                $this->skipped++;
                $this->errors[] = 'Baris ' . $analysis['row_number'] . ': user tidak ditemukan saat proses sinkronisasi.';
                continue;
            }

            $hasChanges = $this->synchronizer->syncRow(
                $user,
                $analysis['user_payload'],
                $analysis['sk_payload']
            );

            if ($hasChanges) {
                $this->updated++;
                continue;
            }

            $this->skipped++;
            $this->errors[] = 'Baris ' . $analysis['row_number'] . ': data sama dengan database, tidak ada perubahan.';
        }

        $this->matchedUserIds = array_values(array_unique(array_filter($this->matchedUserIds)));
    }
}
