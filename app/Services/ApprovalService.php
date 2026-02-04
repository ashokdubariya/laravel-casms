<?php

namespace App\Services;

use App\Models\ApprovalRequest;
use App\Models\ApprovalToken;
use App\Models\ApprovalHistory;
use App\Models\ApprovalAttachment;
use App\Notifications\ApprovalRequestCreated;
use App\Notifications\ApprovalReminder;
use App\Notifications\ApprovalCompleted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class ApprovalService
{
    /**
     * Create a new approval request with attachments.
     */
    public function createApprovalRequest(array $data, int $userId): ApprovalRequest
    {
        return DB::transaction(function () use ($data, $userId) {
            // Create approval request
            $approval = ApprovalRequest::create([
                'created_by' => $userId,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'version' => $data['version'] ?? 'v1',
                'client_id' => $data['client_id'],
                'priority' => $data['priority'] ?? 'medium',
                'internal_notes' => $data['internal_notes'] ?? null,
                'due_date' => $data['due_date'] ?? null,
                'message' => $data['message'] ?? null,
                'client_name' => $data['client_name'] ?? null,
                'client_email' => $data['client_email'] ?? null,
            ]);

            // Log creation
            ApprovalHistory::log($approval, 'created', auth()->user()->name);

            // Generate approval token
            $token = $this->generateToken($approval);

            // Send notification to client
            Notification::route('mail', $approval->client->email)
                ->notify(new ApprovalRequestCreated($approval, $token));

            return $approval;
        });
    }

    /**
     * Update an existing approval request.
     */
    public function updateApprovalRequest(ApprovalRequest $approval, array $data): ApprovalRequest
    {
        // Only allow updates if pending
        if (!$approval->isPending()) {
            throw new \Exception('Cannot update a completed approval request.');
        }

        $approval->update([
            'title' => $data['title'] ?? $approval->title,
            'description' => $data['description'] ?? $approval->description,
            'version' => $data['version'] ?? $approval->version,
            'client_id' => $data['client_id'] ?? $approval->client_id,
            'priority' => $data['priority'] ?? $approval->priority,
            'due_date' => $data['due_date'] ?? $approval->due_date,
            'message' => $data['message'] ?? $approval->message,
            'client_name' => $data['client_name'] ?? $approval->client_name,
            'client_email' => $data['client_email'] ?? $approval->client_email,
            'status' => $data['status'] ?? $approval->status,
            'internal_notes' => $data['internal_notes'] ?? $approval->internal_notes,
        ]);

        ApprovalHistory::log($approval, 'updated', auth()->user()->name);

        return $approval->fresh();
    }

    /**
     * Generate a secure approval token.
     */
    public function generateToken(ApprovalRequest $approval, ?int $expiryDays = null): ApprovalToken
    {
        // Invalidate old tokens (mark as used)
        $approval->tokens()
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        // Create new token
        $token = ApprovalToken::create([
            'approval_request_id' => $approval->id,
            'token' => ApprovalToken::generateSecureToken(),
            'expires_at' => now()->addDays((int) ($expiryDays ?? config('approval.token.expiry_days', 7))),
        ]);

        ApprovalHistory::log($approval, 'token_generated', 'system');

        return $token;
    }

    /**
     * Client approves the request.
     */
    public function approveByClient(ApprovalToken $token): ApprovalRequest
    {
        return DB::transaction(function () use ($token) {
            $approval = $token->approvalRequest;

            // Update approval status
            $approval->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            // Mark token as used
            $token->markAsUsed();

            // Log approval
            ApprovalHistory::log($approval, 'approved', 'client', [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Notify approval owner
            $approval->user->notify(new ApprovalCompleted($approval, 'approved'));

            return $approval->fresh();
        });
    }

    /**
     * Client rejects the request.
     */
    public function rejectByClient(ApprovalToken $token, string $comment): ApprovalRequest
    {
        return DB::transaction(function () use ($token, $comment) {
            $approval = $token->approvalRequest;

            // Update approval status
            $approval->update([
                'status' => 'rejected',
                'rejected_at' => now(),
            ]);

            // Mark token as used
            $token->markAsUsed();

            // Log rejection with comment
            ApprovalHistory::log($approval, 'rejected', 'client', [
                'comment' => $comment,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Notify approval owner
            $approval->user->notify(new ApprovalCompleted($approval, 'rejected', $comment));

            return $approval->fresh();
        });
    }

    /**
     * Add attachment to approval request.
     */
    public function addAttachment(ApprovalRequest $approval, array $data): ApprovalAttachment
    {
        if ($data['type'] === 'url') {
            return $approval->attachments()->create([
                'type' => 'url',
                'url' => $data['url'],
            ]);
        }

        // Handle file upload
        $file = $data['file'];
        $path = $file->store('approvals/' . $approval->id, 'public');

        return $approval->attachments()->create([
            'type' => $data['type'],
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);
    }

    /**
     * Delete attachment.
     */
    public function deleteAttachment(ApprovalAttachment $attachment): bool
    {
        return $attachment->delete();
    }

    /**
     * Send reminder to client.
     */
    public function sendReminder(ApprovalRequest $approval): void
    {
        $token = $approval->activeToken;

        if (!$token) {
            throw new \Exception('No active token found for this approval request.');
        }

        // Send reminder notification
        Notification::route('mail', $approval->client_email)
            ->notify(new ApprovalReminder($approval, $token));

        // Log reminder sent
        ApprovalHistory::log($approval, 'reminded', auth()->user()->name);
    }

    /**
     * Delete approval request and all related data.
     */
    public function deleteApprovalRequest(ApprovalRequest $approval): bool
    {
        return DB::transaction(function () use ($approval) {
            // Cascade delete will handle attachments, tokens, and history
            return $approval->delete();
        });
    }
}
