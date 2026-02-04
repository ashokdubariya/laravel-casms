<?php

namespace App\Observers;

use App\Models\Role;
use App\Models\AuditLog;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        if (auth()->check()) {
            AuditLog::log(
                'roles',
                'create',
                $role,
                null,
                null,
                $role->only(['name', 'slug', 'description'])
            );
        }
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        if (auth()->check()) {
            $changes = $role->getChanges();
            
            if (!empty($changes)) {
                AuditLog::log(
                    'roles',
                    'update',
                    $role,
                    null,
                    $role->getOriginal(),
                    $changes
                );
            }
        }
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        if (auth()->check()) {
            AuditLog::log(
                'roles',
                'delete',
                $role,
                null,
                $role->only(['name', 'slug']),
                null
            );
        }
    }

    /**
     * Handle the Role "restored" event.
     */
    public function restored(Role $role): void
    {
        if (auth()->check()) {
            AuditLog::log(
                'roles',
                'restore',
                $role,
                "Role {$role->name} restored",
                null,
                null
            );
        }
    }
}
