<?php

namespace App\Services;

use App\Models\DpsMember;
use App\Models\User;
use App\Models\DpsAccountPassword;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DpsAccountService
{
    /**
     * Create a DPS user for the given DPS member if missing.
     *
     * @return array|null Returns ['email' => ..., 'password' => ...] when a new user was created.
     */
    public function ensureUser(DpsMember $member): ?array
    {
        $normalizedName = $this->normalizeName($member->nama);

        // If this member already linked, nothing to do.
        if ($member->user_id && User::whereKey($member->user_id)->exists()) {
            return null;
        }

        // Prefer an existing DPS account already used by another DPS record with the same name.
        $existingMemberWithUser = DpsMember::query()
            ->whereNotNull('user_id')
            ->whereRaw('LOWER(TRIM(nama)) = ?', [$normalizedName])
            ->orderBy('id')
            ->first();

        if ($existingMemberWithUser && $existingMemberWithUser->user_id) {
            $existingUser = User::find($existingMemberWithUser->user_id);
            if ($existingUser && $existingUser->role === 'dps') {
                $member->user_id = $existingUser->id;
                $member->save();
                return null;
            }
        }

        // Fallback: match directly by DPS users table (name).
        $existingUser = User::query()
            ->where('role', 'dps')
            ->whereRaw('LOWER(TRIM(name)) = ?', [$normalizedName])
            ->orderBy('id')
            ->first();

        if ($existingUser) {
            $member->user_id = $existingUser->id;
            $member->save();
            return null;
        }

        // New account: email MUST be 6 digits only + @nuist.id (no DPS text, no name).
        $attempts = 0;
        do {
            $localPart = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $email = "{$localPart}@nuist.id";
            $attempts++;
        } while (User::where('email', $email)->exists() && $attempts < 200);

        if (User::where('email', $email)->exists()) {
            // Extremely unlikely fallback: microtime-based 6 digits.
            $digits = preg_replace('/\\D+/', '', (string) (microtime(true) * 1000000));
            $localPart = str_pad(substr($digits, -6), 6, '0', STR_PAD_LEFT);
            $email = "{$localPart}@nuist.id";
        }

        // Simple password (shown via import credential download). Not logged.
        $passwordPlain = 'Nuist' . random_int(1000, 9999);

        $user = User::create([
            'name' => $member->nama,
            'email' => $email,
            'password' => Hash::make($passwordPlain),
            'role' => 'dps',
            'password_changed' => false,
        ]);

        // Avoid blocking flows that rely on verified users, if enabled in this app.
        $user->email_verified_at = now();
        // Keep a "primary" madrasah_id only if empty; DPS may cover multiple schools.
        if (!$user->madrasah_id) {
            $user->madrasah_id = $member->madrasah_id;
        }
        $user->save();

        $member->user_id = $user->id;
        $member->save();

        // Store initial password (encrypted) for Super Admin export purposes.
        // In production this table might not exist yet; don't fail account creation/import.
        if (Schema::hasTable('dps_account_passwords')) {
            try {
                DpsAccountPassword::updateOrCreate(
                    ['user_id' => $user->id],
                    ['password_encrypted' => Crypt::encryptString($passwordPlain)]
                );
            } catch (\Throwable $e) {
                // no-op: never block DPS creation on password export feature
            }
        }

        return ['email' => $email, 'password' => $passwordPlain];
    }

    private function normalizeName(string $name): string
    {
        $n = trim(strtolower($name));
        $n = preg_replace('/\s+/', ' ', $n);
        return $n;
    }
}
