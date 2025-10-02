<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any admin data.
     */
    public function viewAny(User $user)
    {
        return in_array($user->role, ['super_admin', 'admin', 'pengurus']);
    }

    /**
     * Determine whether the user can view the admin data.
     */
    public function view(User $user)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can create admin data.
     */
    public function create(User $user)
    {
        return in_array($user->role, ['super_admin', 'admin', 'pengurus']);
    }

    /**
     * Determine whether the user can update admin data.
     */
    public function update(User $user)
    {
        return in_array($user->role, ['super_admin', 'admin', 'pengurus']);
    }

    /**
     * Determine whether the user can delete admin data.
     */
    public function delete(User $user)
    {
        return in_array($user->role, ['super_admin', 'admin', 'pengurus']);
    }
}
