<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MadrasahPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any madrasah data.
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, ['super_admin', 'admin', 'pengurus']);
    }

    /**
     * Determine whether the user can view the madrasah data.
     */
    public function view(User $user)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can create madrasah data.
     */
    public function create(User $user)
    {
        return in_array($user->role, ['super_admin', 'admin', 'pengurus']);
    }

    /**
     * Determine whether the user can update madrasah data.
     */
    public function update(User $user)
    {
        return in_array($user->role, ['super_admin', 'admin', 'pengurus']);
    }

    /**
     * Determine whether the user can delete madrasah data.
     */
    public function delete(User $user)
    {
        return in_array($user->role, ['super_admin', 'admin', 'pengurus']);
    }
}
