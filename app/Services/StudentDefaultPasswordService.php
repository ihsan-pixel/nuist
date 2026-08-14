<?php

namespace App\Services;

use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;

class StudentDefaultPasswordService
{
    /**
     * The initial credential is deliberately generated only when an account
     * has no password yet. Existing student passwords must never be replaced
     * by an import or an ordinary data edit.
     */
    public function ensurePassword(Siswa $siswa): bool
    {
        if (blank($siswa->nisn) || filled($siswa->password)) {
            return false;
        }

        $plainPassword = $this->plainDefaultPassword($siswa);
        if (!$plainPassword) {
            return false;
        }

        $siswa->password = Hash::make($plainPassword);
        $siswa->save();

        return true;
    }

    public function plainDefaultPassword(Siswa $siswa): ?string
    {
        return $siswa->tanggal_lahir
            ? 'Nuist' . $siswa->tanggal_lahir->format('dmY')
            : null;
    }

    public function resetToDefault(Siswa $siswa): bool
    {
        $plainPassword = $this->plainDefaultPassword($siswa);
        if (!$plainPassword) {
            return false;
        }

        $siswa->forceFill([
            'password' => Hash::make($plainPassword),
        ])->save();

        return true;
    }
}
