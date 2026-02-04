<?php

namespace App\Policies;

use App\Models\User;

/**
 * User Policy
 * 
 * Handles authorization for user management operations.
 * Uses permission-first approach with role-based fallbacks.
 */
class UserPolicy
{
    /**
     * Determine if user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('users.view') || $user->isAdmin();
    }

    /**
     * Determine if user can view a specific user.
     */
    public function view(User $user, User $model): bool
    {
        // Can view own profile
        if ($user->id === $model->id) {
            return true;
        }

        return $user->hasPermission('users.view') || $user->isAdmin();
    }

    /**
     * Determine if user can create users.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('users.create') || $user->isAdmin();
    }

    /**
     * Determine if user can update a user.
     */
    public function update(User $user, User $model): bool
    {
        // Can update own profile (limited fields)
        if ($user->id === $model->id) {
            return true;
        }

        // Prevent role escalation - cannot assign higher role than own
        return $user->hasPermission('users.update') || $user->isAdmin();
    }

    /**
     * Determine if user can delete a user.
     */
    public function delete(User $user, User $model): bool
    {
        // Cannot delete yourself
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasPermission('users.delete') || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Cannot force delete yourself
        if ($user->id === $model->id) {
            return false;
        }

        return $user->isAdmin();
    }
}
