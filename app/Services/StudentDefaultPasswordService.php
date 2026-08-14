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

        $siswa->password = Hash::make($this->plainDefaultPassword());
        $siswa->save();

        return true;
    }

    public function plainDefaultPassword(): string
    {
        return 'Nuist' . now()->format('dmY');
    }

    public function resetToDefault(Siswa $siswa): void
    {
        $siswa->forceFill([
            'password' => Hash::make($this->plainDefaultPassword()),
        ])->save();
    }
}
