<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenagaPendidikPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any tenaga pendidik data.
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, ['super_admin', 'admin', 'pengurus']);
    }

    /**
     * Determine whether the user can view the tenaga pendidik data.
     */
    public function view(User $user)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can create tenaga pendidik data.
     */
    public function create(User $user)
    {
        return in_array($user->role, ['super_admin', 'admin', 'pengurus']);
    }

    /**
     * Determine whether the user can update tenaga pendidik data.
     */
    public function update(User $user)
    {
        return in_array($user->role, ['super_admin', 'admin', 'pengurus']);
    }

    /**
     * Determine whether the user can delete tenaga pendidik data.
     */
    public function delete(User $user)
    {
        return in_array($user->role, ['super_admin', 'admin', 'pengurus']);
    }
}
