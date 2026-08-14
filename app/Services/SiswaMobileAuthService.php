<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SiswaMobileAuthService
{
    public function authenticate(string $identifier, string $password): ?User
    {
        $identifier = trim((string) $identifier);

        $siswa = Siswa::query()
            ->where(function ($query) use ($identifier) {
                $query->where('email', strtolower($identifier))
                    ->orWhere('nis', $identifier)
                    ->orWhere('nisn', $identifier);
            })
            ->where('is_active', true)
            ->whereNotNull('password')
            ->first();

        if (!$siswa || blank($siswa->password) || !Hash::check($password, $siswa->password)) {
            return null;
        }

        return $this->syncUserFromSiswa($siswa);
    }

    /** Login path used by the Flutter student form: NISN only. */
    public function authenticateByNisn(string $nisn, string $password): ?User
    {
        $nisn = trim($nisn);

        $siswa = Siswa::query()
            ->where('nisn', $nisn)
            ->where('is_active', true)
            ->whereNotNull('password')
            ->first();

        if (!$siswa || blank($siswa->password) || !Hash::check($password, $siswa->password)) {
            return null;
        }

        return $this->syncUserFromSiswa($siswa);
    }

    public function syncUserFromSiswa(Siswa $siswa): User
    {
        $linkKey = $this->buildLinkKey($siswa);
        // A student can use NISN login even when the source school data has no
        // personal email. The linked user still needs a unique email value.
        $accountEmail = filled($siswa->email)
            ? strtolower(trim($siswa->email))
            : strtolower(trim($siswa->nisn)) . '@nuist.id';

        $user = User::query()
            ->where('nuist_id', $linkKey)
            ->first();

        if (!$user) {
            $user = User::query()
                ->where('email', $accountEmail)
                ->where('role', 'siswa')
                ->first();
        }

        $conflictingUser = User::query()
            ->where('email', $accountEmail)
            ->when($user?->exists, fn ($query) => $query->where('id', '!=', $user->id))
            ->where('role', '!=', 'siswa')
            ->first();

        if ($conflictingUser) {
            throw ValidationException::withMessages([
                'email' => 'Email siswa sudah digunakan oleh akun lain. Hubungi administrator.',
            ]);
        }

        $user ??= new User();

        $user->fill([
            'name' => $siswa->nama_lengkap,
            'email' => $accountEmail,
            'password' => $siswa->password,
            'role' => 'siswa',
            'nuist_id' => $linkKey,
            'madrasah_id' => $siswa->madrasah_id,
            'no_hp' => $siswa->no_hp,
            'alamat' => $siswa->alamat,
        ]);

        if ($siswa->email_verified_at && !$user->email_verified_at) {
            $user->email_verified_at = $siswa->email_verified_at;
        }

        $user->save();

        return $user->fresh();
    }

    private function buildLinkKey(Siswa $siswa): string
    {
        return 'S' . str_pad(strtoupper(base_convert((string) $siswa->id, 10, 36)), 5, '0', STR_PAD_LEFT);
    }
}
