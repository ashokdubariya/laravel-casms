<?php

namespace App\Policies;

use App\Models\ApprovalRequest;
use App\Models\User;

class ApprovalRequestPolicy
{
    /**
     * Determine if the user can view any approval requests.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('approvals.view');
    }

    /**
     * Determine if the user can view the approval request.
     */
    public function view(User $user, ApprovalRequest $approval): bool
    {
        // Must have permission AND (own it OR be admin/manager)
        if (!$user->hasPermission('approvals.view')) {
            return false;
        }

        return $user->id === $approval->created_by 
            || $user->hasRole('admin') 
            || $user->hasRole('manager');
    }

    /**
     * Determine if the user can create approval requests.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('approvals.create') && $user->status === 'active';
    }

    /**
     * Determine if the user can update the approval request.
     */
    public function update(User $user, ApprovalRequest $approval): bool
    {
        // Must have permission AND own it AND be in editable status
        if (!$user->hasPermission('approvals.update')) {
            return false;
        }

        // Only creator can edit their own pending approvals
        return $user->id === $approval->created_by && $approval->status === 'pending';
    }

    /**
     * Determine if the user can delete the approval request.
     */
    public function delete(User $user, ApprovalRequest $approval): bool
    {
        // Must have permission AND (own it OR be admin)
        if (!$user->hasPermission('approvals.delete')) {
            return false;
        }

        return $user->id === $approval->created_by || $user->hasRole('admin');
    }

    /**
     * Determine if the user can view the history.
     */
    public function viewHistory(User $user, ApprovalRequest $approval): bool
    {
        // Same as view permission
        return $this->view($user, $approval);
    }

    /**
     * Determine if the user can send reminders.
     */
    public function sendReminder(User $user, ApprovalRequest $approval): bool
    {
        // Same as update permission
        return $this->update($user, $approval) && $approval->isPending();
    }

    /**
     * Determine if the user can regenerate token.
     */
    public function regenerateToken(User $user, ApprovalRequest $approval): bool
    {
        // Same as update permission  
        return $this->update($user, $approval) && $approval->isPending();
    }

    /**
     * Determine if the user can download PDF.
     */
    public function downloadPdf(User $user, ApprovalRequest $approval): bool
    {
        return $this->view($user, $approval);
    }
}
