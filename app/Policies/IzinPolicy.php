<?php

namespace App\Policies;

use App\Models\Presensi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IzinPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can approve the permission request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Presensi  $presensi
     * @return bool
     */
    public function approve(User $user, Presensi $presensi): bool
    {
        if ($user->role === 'super_admin' || $user->role === 'pengurus') {
            return true;
        }

        return $user->role === 'admin' && $user->madrasah_id === $presensi->user->madrasah_id;
    }

    /**
     * Determine whether the user can reject the permission request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Presensi  $presensi
     * @return bool
     */
    public function reject(User $user, Presensi $presensi): bool
    {
        if ($user->role === 'super_admin' || $user->role === 'pengurus') {
            return true;
        }

        return $user->role === 'admin' && $user->madrasah_id === $presensi->user->madrasah_id;
    }
}
