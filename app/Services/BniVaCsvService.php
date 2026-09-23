<?php

namespace App\Services;

use App\Models\Madrasah;
use App\Models\Siswa;
use App\Models\SppSiswaSetting;
use App\Models\SppSiswaVirtualAccount;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BniVaCsvService
{
    public const HEADERS = ['trx_id', 'virtual_account', 'customer_name', 'customer_email', 'customer_phone', 'trx_amount', 'expired_date', 'expired_time', 'description'];

    public function generate(
        Madrasah $madrasah,
        SppSiswaSetting $setting,
        Collection $students,
        Carbon $expiredAt,
        string $vaPrefix,
        ?int $createdBy = null
    ): Collection {
        $prefix = preg_replace('/\D+/', '', $vaPrefix);
        if (strlen($prefix) !== 8) {
            throw ValidationException::withMessages([
                'bni_va_prefix' => 'Prefix VA BNI pada Pengaturan Aplikasi wajib tepat 8 digit.',
            ]);
        }

        $schoolCode = preg_replace('/[^A-Za-z0-9]/', '', (string) $madrasah->scod);
        if ($schoolCode === '') {
            throw ValidationException::withMessages(['madrasah_id' => 'SCOD madrasah wajib diisi sebelum membuat CSV BNI.']);
        }

        [$startYear, $endYear] = $this->academicYears($setting->tahun_ajaran);
        $description = sprintf('Juli %d - Juni %d', $startYear, $endYear);
        $academicCode = sprintf('TA%02d%02d', $startYear % 100, $endYear % 100);
        $errors = [];

        foreach ($students as $student) {
            if (blank($student->nama_lengkap)) $errors[] = "Siswa ID {$student->id}: nama belum diisi.";
            if (blank($student->email) || !filter_var($student->email, FILTER_VALIDATE_EMAIL)) $errors[] = "{$student->nama_lengkap}: email tidak valid.";
            if (blank($student->no_hp) || !preg_match('/^[0-9]{10,15}$/', $this->phone($student->no_hp))) $errors[] = "{$student->nama_lengkap}: nomor HP harus 10-15 digit.";
            if ($student->id > 99999999) $errors[] = "{$student->nama_lengkap}: ID siswa melebihi 8 digit.";
        }
        if ($errors !== []) {
            throw ValidationException::withMessages(['students' => array_slice($errors, 0, 20)]);
        }

        return DB::transaction(function () use ($students, $madrasah, $setting, $expiredAt, $createdBy, $prefix, $schoolCode, $academicCode, $description) {
            return $students->map(function (Siswa $student) use ($madrasah, $setting, $expiredAt, $createdBy, $prefix, $schoolCode, $academicCode, $description) {
                $existing = SppSiswaVirtualAccount::query()
                    ->where('siswa_id', $student->id)
                    ->where('tahun_ajaran', $setting->tahun_ajaran)
                    ->first();
                $attributes = [
                    'madrasah_id' => $madrasah->id,
                    'setting_id' => $setting->id,
                    'trx_id' => sprintf('INV-LPMNUDIY-%s-%s-%05d', strtoupper($schoolCode), $academicCode, $student->id),
                    'customer_name' => mb_strtoupper(trim($student->nama_lengkap)),
                    'customer_email' => strtolower(trim($student->email)),
                    'customer_phone' => $this->phone($student->no_hp),
                    'trx_amount' => 0,
                    'expired_at' => $expiredAt,
                    'description' => $description,
                    'status' => 'draft',
                    'created_by' => $createdBy,
                ];

                if ($existing) {
                    if (!str_starts_with($existing->virtual_account, $prefix)) {
                        throw ValidationException::withMessages([
                            'va_prefix' => "{$student->nama_lengkap}: VA yang sudah tersimpan memakai prefix berbeda.",
                        ]);
                    }
                    $existing->update($attributes);
                    return $existing->fresh();
                }

                $attributes['virtual_account'] = $this->uniqueVirtualAccount($prefix);
                return SppSiswaVirtualAccount::query()->create([
                    'siswa_id' => $student->id,
                    'tahun_ajaran' => $setting->tahun_ajaran,
                    ...$attributes,
                ]);
            });
        });
    }

    public function csvRow(SppSiswaVirtualAccount $account): array
    {
        return [
            $account->trx_id,
            $account->virtual_account,
            $account->customer_name,
            $account->customer_email,
            $account->customer_phone,
            (string) (int) $account->trx_amount,
            $account->expired_at->format('Y-m-d'),
            $account->expired_at->format('H:i:s'),
            $account->description,
        ];
    }

    private function academicYears(string $value): array
    {
        if (!preg_match('/(20\d{2})\D+(20\d{2})/', $value, $matches) || (int) $matches[2] !== (int) $matches[1] + 1) {
            throw ValidationException::withMessages(['setting_id' => 'Tahun ajaran harus memakai format 2026/2027.']);
        }
        return [(int) $matches[1], (int) $matches[2]];
    }

    private function phone(?string $value): string
    {
        $phone = preg_replace('/\D+/', '', (string) $value);
        return str_starts_with($phone, '62') ? '0' . substr($phone, 2) : $phone;
    }

    private function uniqueVirtualAccount(string $prefix): string
    {
        for ($attempt = 0; $attempt < 20; $attempt++) {
            $candidate = $prefix . str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            if (!SppSiswaVirtualAccount::query()->where('virtual_account', $candidate)->exists()) {
                return $candidate;
            }
        }

        throw ValidationException::withMessages(['virtual_account' => 'Gagal membentuk nomor VA unik. Silakan ulangi ekspor.']);
    }
}
