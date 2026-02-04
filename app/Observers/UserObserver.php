<?php

namespace App\Observers;

use App\Models\User;
use App\Models\AuditLog;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if (auth()->check() && auth()->id() !== $user->id) {
            AuditLog::log(
                'users',
                'create',
                $user,
                null,
                null,
                $user->only(['first_name', 'last_name', 'email', 'role_id', 'status'])
            );
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if (auth()->check()) {
            $changes = $user->getChanges();
            
            // Don't log password changes in audit (security)
            unset($changes['password'], $changes['remember_token']);
            
            if (!empty($changes)) {
                AuditLog::log(
                    'users',
                    'update',
                    $user,
                    null,
                    $user->getOriginal(),
                    $changes
                );
            }
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        if (auth()->check()) {
            AuditLog::log(
                'users',
                'delete',
                $user,
                null,
                $user->only(['first_name', 'last_name', 'email', 'role_id']),
                null
            );
        }
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        if (auth()->check()) {
            AuditLog::log(
                'users',
                'restore',
                $user,
                "User {$user->full_name} restored",
                null,
                null
            );
        }
    }
}
