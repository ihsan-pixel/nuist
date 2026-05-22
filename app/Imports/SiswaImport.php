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
                'kelas',
                'nama_peserta_didik',
            ];

            foreach ($requiredFields as $field) {
                if (blank($this->getRowValue($row, $field))) {
                    throw new \InvalidArgumentException("Baris {$line}: kolom {$field} wajib diisi.");
                }
            }

            $emailSiswa = $this->nullableString($this->getRowValue($row, 'email_siswa'));
            if ($emailSiswa && !filter_var($emailSiswa, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException("Baris {$line}: format email_siswa tidak valid.");
            }

            $emailWali = $this->nullableString($this->getRowValue($row, 'email_orang_tua_wali'));
            if ($emailWali && !filter_var($emailWali, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException("Baris {$line}: format email_orang_tua_wali tidak valid.");
            }

            $madrasah = $this->forcedMadrasah;
            $nis = trim((string) $this->getRowValue($row, 'nis'));
            $nisn = $this->nullableString($this->getRowValue($row, 'nisn'));

            $attributes = [
                'madrasah_id' => $madrasah->id,
                'nis' => $nis,
                'nisn' => $nisn,
                'nik' => $this->nullableString($this->getRowValue($row, 'nik')),
                'no_kk' => $this->nullableString($this->getRowValue($row, 'no_kk')),
                'nama_lengkap' => trim((string) $this->getRowValue($row, 'nama_peserta_didik')),
                'jenis_kelamin' => $this->normalizeGender($this->getRowValue($row, 'jenis_kelamin')),
                'tempat_lahir' => $this->nullableString($this->getRowValue($row, 'tempat_lahir')),
                'tanggal_lahir' => $this->normalizeDate($this->getRowValue($row, 'tanggal_lahir')),
                'agama' => $this->nullableString($this->getRowValue($row, 'agama')),
                'nama_ayah' => $this->nullableString($this->getRowValue($row, 'nama_ayah')),
                'pendidikan_ayah' => $this->nullableString($this->getRowValue($row, 'pendidikan_ayah')),
                'pekerjaan_ayah' => $this->nullableString($this->getRowValue($row, 'pekerjaan_ayah')),
                'penghasilan_ayah' => $this->nullableString($this->getRowValue($row, 'penghasilan_ayah')),
                'nama_ibu' => $this->nullableString($this->getRowValue($row, 'nama_ibu')),
                'pendidikan_ibu' => $this->nullableString($this->getRowValue($row, 'pendidikan_ibu')),
                'pekerjaan_ibu' => $this->nullableString($this->getRowValue($row, 'pekerjaan_ibu')),
                'penghasilan_ibu' => $this->nullableString($this->getRowValue($row, 'penghasilan_ibu')),
                'nama_wali' => $this->nullableString($this->getRowValue($row, 'nama_wali')),
                'pendidikan_wali' => $this->nullableString($this->getRowValue($row, 'pendidikan_wali')),
                'pekerjaan_wali' => $this->nullableString($this->getRowValue($row, 'pekerjaan_wali')),
                'penghasilan_wali' => $this->nullableString($this->getRowValue($row, 'penghasilan_wali')),
                'nama_orang_tua_wali' => $this->resolveParentGuardianName($row),
                'email' => $emailSiswa ?: $this->generateInternalEmail($nis, $nisn, $madrasah->id),
                'email_orang_tua_wali' => $emailWali,
                'no_hp' => $this->nullableString($this->getRowValue($row, 'no_hp_siswa')),
                'no_hp_orang_tua_wali' => $this->nullableString($this->getRowValue($row, 'no_hp_orang_tua_wali')),
                'kelas' => trim((string) $this->getRowValue($row, 'kelas')),
                'jurusan' => $this->nullableString($this->getRowValue($row, 'jurusan')),
                'tahun_masuk' => $this->nullableString($this->getRowValue($row, 'tahun_masuk')),
                'jenis_tinggal' => $this->nullableString($this->getRowValue($row, 'jenis_tinggal')),
                'alat_transportasi' => $this->nullableString($this->getRowValue($row, 'alat_transportasi')),
                'nama_madrasah' => $madrasah->name,
                'alamat' => $this->resolveAddress($row),
                'dusun' => $this->nullableString($this->getRowValue($row, 'dusun')),
                'kelurahan' => $this->nullableString($this->getRowValue($row, 'kelurahan')),
                'kecamatan' => $this->nullableString($this->getRowValue($row, 'kecamatan')),
                'kode_pos' => $this->nullableString($this->getRowValue($row, 'kode_pos')),
                'is_active' => true,
            ];

            $existing = Siswa::query()
                ->where('nis', $attributes['nis'])
                ->when($attributes['nisn'], fn ($query) => $query->orWhere('nisn', $attributes['nisn']))
                ->when($emailSiswa, fn ($query) => $query->orWhere('email', $attributes['email']))
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

    private function getRowValue($row, string $field): mixed
    {
        $aliases = [
            'nama_peserta_didik' => ['nama_peserta_didik', 'nama_lengkap_siswa', 'nama_lengkap', 'nama_siswa'],
            'no_hp_siswa' => ['no_hp_siswa', 'no_hp', 'hp_siswa'],
            'no_hp_orang_tua_wali' => ['no_hp_orang_tua_wali', 'no_hp_wali', 'hp_orang_tua_wali'],
            'email_siswa' => ['email_siswa', 'email'],
            'email_orang_tua_wali' => ['email_orang_tua_wali', 'email_wali'],
        ];

        foreach ($aliases[$field] ?? [$field] as $key) {
            if (array_key_exists($key, $row->toArray()) && !blank($row[$key])) {
                return $row[$key];
            }
        }

        return null;
    }

    private function resolveParentGuardianName($row): ?string
    {
        foreach ([
            $this->getRowValue($row, 'nama_orang_tua_wali'),
            $this->getRowValue($row, 'nama_wali'),
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

    private function resolveAddress($row): string
    {
        $alamat = $this->nullableString($this->getRowValue($row, 'alamat'));
        if ($alamat) {
            return $alamat;
        }

        $segments = array_filter([
            $this->nullableString($this->getRowValue($row, 'dusun')),
            $this->nullableString($this->getRowValue($row, 'kelurahan')),
            $this->nullableString($this->getRowValue($row, 'kecamatan')),
            $this->nullableString($this->getRowValue($row, 'kode_pos')),
        ]);

        return $segments !== [] ? implode(', ', $segments) : '-';
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
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
            }

            $normalized = $this->nullableString($value);
            if (!$normalized) {
                return null;
            }

            return \Carbon\Carbon::parse($normalized)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }

    private function generateInternalEmail(string $nis, ?string $nisn, int $madrasahId): string
    {
        $identifier = $nisn ?: $nis;

        return sprintf(
            'siswa.%s.%s@nuist.local',
            $madrasahId,
            preg_replace('/[^a-zA-Z0-9]+/', '', strtolower($identifier))
        );
    }
}
