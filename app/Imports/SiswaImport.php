<?php

namespace App\Imports;

use App\Models\Madrasah;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $created = 0;
    public int $updated = 0;

    public function __construct(
        private readonly Madrasah $forcedMadrasah
    ) {
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $line = $index + 2;

            $requiredFields = [
                'nis',
                'nama_lengkap_siswa',
                'nama_orang_tua_wali',
                'email_siswa',
                'email_orang_tua_wali',
                'no_hp_siswa',
                'no_hp_orang_tua_wali',
                'kelas',
                'jurusan',
                'alamat',
            ];

            foreach ($requiredFields as $field) {
                if (blank($row[$field] ?? null)) {
                    throw new \InvalidArgumentException("Baris {$line}: kolom {$field} wajib diisi.");
                }
            }

            if (!filter_var(trim((string) $row['email_siswa']), FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException("Baris {$line}: format email_siswa tidak valid.");
            }

            if (!filter_var(trim((string) $row['email_orang_tua_wali']), FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException("Baris {$line}: format email_orang_tua_wali tidak valid.");
            }

            $madrasah = $this->forcedMadrasah;

            $attributes = [
                'madrasah_id' => $madrasah->id,
                'nis' => trim((string) $row['nis']),
                'nama_lengkap' => trim((string) $row['nama_lengkap_siswa']),
                'nama_orang_tua_wali' => trim((string) $row['nama_orang_tua_wali']),
                'email' => trim((string) $row['email_siswa']),
                'email_orang_tua_wali' => trim((string) $row['email_orang_tua_wali']),
                'no_hp' => trim((string) $row['no_hp_siswa']),
                'no_hp_orang_tua_wali' => trim((string) $row['no_hp_orang_tua_wali']),
                'kelas' => trim((string) $row['kelas']),
                'jurusan' => trim((string) $row['jurusan']),
                'nama_madrasah' => $madrasah->name,
                'alamat' => trim((string) $row['alamat']),
                'is_active' => true,
            ];

            $existing = Siswa::query()
                ->where('nis', $attributes['nis'])
                ->orWhere('email', $attributes['email'])
                ->first();

            if ($existing) {
                $existing->fill($attributes);
                $existing->save();
                $this->updated++;
                continue;
            }

            Siswa::create($attributes + [
                'password' => Hash::make($attributes['nis']),
            ]);

            $this->created++;
        }
    }
}
