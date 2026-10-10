<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BpppmnuGuestInvitationsImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public array $guests = [];

    public array $invalidRows = [];

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $name = trim((string) ($row['nama'] ?? $row['nama_lengkap'] ?? $row['name'] ?? ''));
            if ($name === '') {
                $this->invalidRows[] = $index + 2;
                continue;
            }

            $this->guests[] = [
                'name' => $name,
                'organization' => trim((string) ($row['instansi'] ?? $row['organisasi'] ?? $row['organization'] ?? '')),
                'phone' => trim((string) ($row['nomor_hp'] ?? $row['no_hp'] ?? $row['phone'] ?? '')),
            ];
        }
    }
}
