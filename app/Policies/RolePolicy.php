<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

/**
 * Role Policy
 * 
 * Handles authorization for role management operations.
 * Uses permission-first approach with role-based fallbacks.
 */
class RolePolicy
{
    /**
     * Determine if user can view any roles.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('roles.view') || $user->isAdmin();
    }

    /**
     * Determine if user can view a specific role.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasPermission('roles.view') || $user->isAdmin();
    }

    /**
     * Determine if user can create roles.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('roles.create') || $user->isAdmin();
    }

    /**
     * Determine if user can update a role.
     */
    public function update(User $user, Role $role): bool
    {
        // Cannot edit system roles unless admin
        if ($role->is_system && !$user->isAdmin()) {
            return false;
        }

        return $user->hasPermission('roles.update') || $user->isAdmin();
    }

    /**
     * Determine if user can delete a role.
     */
    public function delete(User $user, Role $role): bool
    {
        // Cannot delete system roles
        if ($role->is_system) {
            return false;
        }

        // Cannot delete role with assigned users
        if ($role->users()->count() > 0) {
            return false;
        }

        return $user->hasPermission('roles.delete') || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $user->isAdmin() && !$role->is_system;
    }
}
