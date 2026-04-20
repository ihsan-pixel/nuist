<?php

namespace App\Services;

use App\Models\DpsMember;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DpsAccountService
{
    /**
     * Create a DPS user for the given DPS member if missing.
     *
     * @return array|null Returns ['email' => ..., 'password' => ...] when a new user was created.
     */
    public function ensureUser(DpsMember $member): ?array
    {
        if ($member->user_id && User::whereKey($member->user_id)->exists()) {
            return null;
        }

        $scod = $member->madrasah ? ($member->madrasah->scod ?? null) : null;
        $scodPart = $scod ? preg_replace('/[^0-9A-Za-z]/', '', (string) $scod) : (string) $member->madrasah_id;

        // Unique, deterministic email (based on DPS member id).
        $email = strtolower("dps-{$scodPart}-{$member->id}@nuist.id");

        // If an account with this email already exists (e.g. restored data), just link it.
        $existing = User::where('email', $email)->first();
        if ($existing) {
            $member->user_id = $existing->id;
            $member->save();
            return null;
        }

        // Simple password (shown via import credential download). Not logged.
        $passwordPlain = 'Nuist' . random_int(1000, 9999);

        $user = User::create([
            'name' => $member->nama,
            'email' => $email,
            'password' => Hash::make($passwordPlain),
            'role' => 'dps',
            'madrasah_id' => $member->madrasah_id,
            'password_changed' => false,
        ]);

        // Avoid blocking flows that rely on verified users, if enabled in this app.
        $user->email_verified_at = now();
        $user->save();

        $member->user_id = $user->id;
        $member->save();

        return ['email' => $email, 'password' => $passwordPlain];
    }
}
