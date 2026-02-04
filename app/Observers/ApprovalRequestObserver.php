<?php

namespace App\Observers;

use App\Models\ApprovalRequest;
use App\Models\AuditLog;

class ApprovalRequestObserver
{
    /**
     * Handle the ApprovalRequest "created" event.
     */
    public function created(ApprovalRequest $approvalRequest): void
    {
        if (auth()->check()) {
            AuditLog::log(
                'approvals',
                'create',
                $approvalRequest,
                null,
                null,
                $approvalRequest->only(['title', 'status', 'client_id', 'version_number'])
            );
        }
    }

    /**
     * Handle the ApprovalRequest "updated" event.
     */
    public function updated(ApprovalRequest $approvalRequest): void
    {
        if (auth()->check()) {
            $changes = $approvalRequest->getChanges();
            
            if (!empty($changes)) {
                // Special handling for status changes (approval/rejection)
                if (isset($changes['status'])) {
                    $action = match($changes['status']) {
                        'approved' => 'approve',
                        'rejected' => 'reject',
                        default => 'update'
                    };
                    
                    AuditLog::log(
                        'approvals',
                        $action,
                        $approvalRequest,
                        null,
                        $approvalRequest->getOriginal(),
                        $changes
                    );
                } else {
                    AuditLog::log(
                        'approvals',
                        'update',
                        $approvalRequest,
                        null,
                        $approvalRequest->getOriginal(),
                        $changes
                    );
                }
            }
        }
    }

    /**
     * Handle the ApprovalRequest "deleted" event.
     */
    public function deleted(ApprovalRequest $approvalRequest): void
    {
        if (auth()->check()) {
            AuditLog::log(
                'approvals',
                'delete',
                $approvalRequest,
                null,
                $approvalRequest->only(['title', 'status']),
                null
            );
        }
    }

    /**
     * Handle the ApprovalRequest "restored" event.
     */
    public function restored(ApprovalRequest $approvalRequest): void
    {
        if (auth()->check()) {
            AuditLog::log(
                'approvals',
                'restore',
                $approvalRequest,
                "Approval request {$approvalRequest->title} restored",
                null,
                null
            );
        }
    }
}
