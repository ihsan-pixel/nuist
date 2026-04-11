<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SiswaMobileAuthService
{
    public function authenticate(string $email, string $password): ?User
    {
        $siswa = Siswa::query()
            ->where('email', trim(strtolower($email)))
            ->where('is_active', true)
            ->first();

        if (!$siswa || !Hash::check($password, $siswa->password)) {
            return null;
        }

        return $this->syncUserFromSiswa($siswa);
    }

    public function syncUserFromSiswa(Siswa $siswa): User
    {
        $linkKey = $this->buildLinkKey($siswa);

        $user = User::query()
            ->where('nuist_id', $linkKey)
            ->first();

        if (!$user) {
            $user = User::query()
                ->where('email', $siswa->email)
                ->where('role', 'siswa')
                ->first();
        }

        $conflictingUser = User::query()
            ->where('email', $siswa->email)
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
            'email' => $siswa->email,
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
        return 'SISWA-' . $siswa->id;
    }
}
