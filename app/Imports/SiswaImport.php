<?php

namespace App\Imports;

use App\Models\Madrasah;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class SiswaImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public int $created = 0;
    public int $updated = 0;

    public function __construct(
        private readonly ?int $restrictedMadrasahId = null
    ) {
    }

    public function collection(Collection $rows): void
    {
        foreach ($rows as $index => $row) {
            $line = $index + 2;

            foreach (['scod', 'asal_sekolah_madrasah', 'nis', 'nama_peserta_didik', 'kelas'] as $field) {
                if (blank($this->getRowValue($row, $field))) {
                    throw new \InvalidArgumentException("Baris {$line}: kolom {$field} wajib diisi.");
                }
            }

            $madrasah = $this->resolveMadrasahForRow($row, $line);
            $nis = trim((string) $this->getRowValue($row, 'nis'));
            $nisn = $this->nullableString($this->getRowValue($row, 'nisn'));
            $nik = $this->nullableString($this->getRowValue($row, 'nik'));
            $emailSiswa = $this->nullableString($this->getRowValue($row, 'email_siswa'));

            if ($emailSiswa && !filter_var($emailSiswa, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException("Baris {$line}: format email_siswa tidak valid.");
            }

            $attributes = [
                'madrasah_id' => $madrasah->id,
                'scod' => $this->nullableString($madrasah->scod ?: $this->getRowValue($row, 'scod')),
                'nis' => $nis,
                'nisn' => $nisn,
                'nik' => $nik,
                'no_kk' => $this->nullableString($this->getRowValue($row, 'no_kk')),
                'nama_lengkap' => trim((string) $this->getRowValue($row, 'nama_peserta_didik')),
                'jenis_kelamin' => $this->normalizeGender($this->getRowValue($row, 'jenis_kelamin')),
                'tempat_lahir' => $this->nullableString($this->getRowValue($row, 'tempat_lahir')),
                'tanggal_lahir' => $this->normalizeDate($this->getRowValue($row, 'tanggal_lahir')),
                'agama' => $this->nullableString($this->getRowValue($row, 'agama')),
                'kelas' => trim((string) $this->getRowValue($row, 'kelas')),
                'jurusan' => $this->nullableString($this->getRowValue($row, 'jurusan')),
                'nama_madrasah' => $this->nullableString($this->getRowValue($row, 'asal_sekolah_madrasah')) ?: $madrasah->name,
                'alamat' => $this->resolveAddress($row),
                'dusun' => $this->nullableString($this->getRowValue($row, 'dusun')),
                'kelurahan' => $this->nullableString($this->getRowValue($row, 'kelurahan')),
                'kecamatan' => $this->nullableString($this->getRowValue($row, 'kecamatan')),
                'email' => $emailSiswa,
                'no_hp' => $this->nullableString($this->getRowValue($row, 'no_hp_siswa')),
                'nama_ayah' => $this->nullableString($this->getRowValue($row, 'nama_ayah')),
                'nama_ibu' => $this->nullableString($this->getRowValue($row, 'nama_ibu')),
                'nama_orang_tua_wali' => $this->resolveParentGuardianName($row),
                'no_hp_orang_tua_wali' => $this->nullableString($this->getRowValue($row, 'no_hp_orang_tua_wali')),
                'email_orang_tua_wali' => null,
                'tahun_masuk' => null,
                'jenis_tinggal' => null,
                'alat_transportasi' => null,
                'kode_pos' => null,
                'pendidikan_ayah' => null,
                'pekerjaan_ayah' => null,
                'penghasilan_ayah' => null,
                'pendidikan_ibu' => null,
                'pekerjaan_ibu' => null,
                'penghasilan_ibu' => null,
                'nama_wali' => null,
                'pendidikan_wali' => null,
                'pekerjaan_wali' => null,
                'penghasilan_wali' => null,
                'password' => null,
                'email_verified_at' => null,
                'last_login_at' => null,
                'is_active' => true,
            ];

            $existing = $this->findExistingStudent($madrasah->id, $nis, $nisn, $nik);

            if ($existing) {
                $existing->fill($attributes);
                $existing->save();
                $this->updated++;
                continue;
            }

            Siswa::create($attributes);
            $this->created++;
        }
    }

    private function resolveMadrasahForRow($row, int $line): Madrasah
    {
        $scod = trim((string) $this->getRowValue($row, 'scod'));
        $schoolName = trim((string) $this->getRowValue($row, 'asal_sekolah_madrasah'));

        $query = Madrasah::query();

        if ($scod !== '') {
            $query->where('scod', $scod);
        } else {
            $query->where('name', $schoolName);
        }

        $madrasah = $query->first();

        if (!$madrasah && $scod !== '') {
            $madrasah = Madrasah::query()
                ->where('name', $schoolName)
                ->first();
        }

        if (!$madrasah) {
            throw new \InvalidArgumentException("Baris {$line}: sekolah dengan SCOD '{$scod}' / nama '{$schoolName}' tidak ditemukan.");
        }

        if ($this->restrictedMadrasahId !== null && $madrasah->id !== $this->restrictedMadrasahId) {
            throw new \InvalidArgumentException("Baris {$line}: Anda tidak memiliki akses untuk mengimpor data ke sekolah {$madrasah->name}.");
        }

        return $madrasah;
    }

    private function findExistingStudent(int $madrasahId, string $nis, ?string $nisn, ?string $nik): ?Siswa
    {
        return Siswa::query()
            ->where(function ($query) use ($madrasahId, $nis, $nisn, $nik) {
                $query->where(function ($scopedQuery) use ($madrasahId, $nis) {
                    $scopedQuery->where('madrasah_id', $madrasahId)
                        ->where('nis', $nis);
                });

                if ($nisn) {
                    $query->orWhere('nisn', $nisn);
                }

                if ($nik) {
                    $query->orWhere('nik', $nik);
                }
            })
            ->first();
    }

    private function getRowValue($row, string $field): mixed
    {
        $aliases = [
            'scod' => ['scod', 'kode_sekolah'],
            'asal_sekolah_madrasah' => ['asal_sekolah_madrasah', 'asal_sekolah', 'nama_madrasah', 'madrasah'],
            'nama_peserta_didik' => ['nama_peserta_didik', 'nama_lengkap', 'nama_siswa'],
            'no_hp_siswa' => ['no_hp_siswa', 'no_hp', 'hp_siswa'],
            'email_siswa' => ['email_siswa', 'email'],
            'no_hp_orang_tua_wali' => ['no_hp_orang_tua_wali', 'no_hp_wali', 'hp_orang_tua_wali'],
        ];

        $rowArray = $row->toArray();
        $normalizedRow = [];

        foreach ($rowArray as $key => $value) {
            $normalizedRow[$this->normalizeHeadingKey((string) $key)] = $value;
        }

        foreach ($aliases[$field] ?? [$field] as $key) {
            if (array_key_exists($key, $rowArray) && !blank($row[$key])) {
                return $row[$key];
            }

            $normalizedKey = $this->normalizeHeadingKey($key);
            if (array_key_exists($normalizedKey, $normalizedRow) && !blank($normalizedRow[$normalizedKey])) {
                return $normalizedRow[$normalizedKey];
            }
        }

        return null;
    }

    private function normalizeHeadingKey(string $value): string
    {
        return preg_replace('/[^a-z0-9]+/i', '', strtolower(trim($value))) ?: '';
    }

    private function resolveParentGuardianName($row): ?string
    {
        foreach ([
            $this->getRowValue($row, 'nama_orang_tua_wali'),
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
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            }

            $normalized = $this->nullableString($value);
            if (!$normalized) {
                return null;
            }

            return Carbon::parse($normalized)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }
}
