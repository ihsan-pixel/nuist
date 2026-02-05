<?php

namespace App\Policies;

use App\Models\Talenta;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TalentaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return true; // All authenticated users can view talenta
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Talenta $talenta)
    {
        return true; // All authenticated users can view any talenta
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return true; // All authenticated users can create talenta
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Talenta $talenta)
    {
        return true; // All authenticated users can update any talenta
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Talenta $talenta)
    {
        return true; // All authenticated users can delete any talenta
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Talenta $talenta)
    {
        return true; // All authenticated users can restore any talenta
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Talenta $talenta)
    {
        return true; // All authenticated users can force delete any talenta
    }
}
