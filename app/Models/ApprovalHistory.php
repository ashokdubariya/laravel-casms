<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    use HasFactory;

    /**
     * Disable updated_at (history is immutable).
     */
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'approval_request_id',
        'action',
        'performed_by',
        'version',
        'comment',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relationship: History entry belongs to an approval request.
     */
    public function approvalRequest()
    {
        return $this->belongsTo(ApprovalRequest::class);
    }

    /**
     * Log a history event.
     * 
     * @param ApprovalRequest $approval
     * @param string $action
     * @param string|null $performedBy
     * @param array $additionalData
     */
    public static function log(
        ApprovalRequest $approval,
        string $action,
        ?string $performedBy = null,
        array $additionalData = []
    ): self {
        return self::create([
            'approval_request_id' => $approval->id,
            'action' => $action,
            'performed_by' => $performedBy ?? 'system',
            'version' => $approval->version,
            'comment' => $additionalData['comment'] ?? null,
            'ip_address' => $additionalData['ip_address'] ?? request()->ip(),
            'user_agent' => $additionalData['user_agent'] ?? request()->userAgent(),
            'metadata' => $additionalData['metadata'] ?? null,
        ]);
    }

    /**
     * Get the icon for this action (for UI).
     */
    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'created' => '<i class="fas fa-file-alt"></i>',
            'approved' => '<i class="fas fa-check-circle"></i>',
            'rejected' => '<i class="fas fa-times-circle"></i>',
            'reminded' => '<i class="fas fa-bell"></i>',
            'viewed' => '<i class="fas fa-eye"></i>',
            default => '<i class="fas fa-circle"></i>',
        };
    }

    /**
     * Get the color for this action (for UI).
     */
    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'approved' => '#85c34e',
            'rejected' => '#ef4444',
            'created' => '#1a425f',
            'reminded' => '#f59e0b',
            default => '#6b7280',
        };
    }

    /**
     * Scope: Get history for a specific action.
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Get history by performer.
     */
    public function scopePerformedBy($query, string $performer)
    {
        return $query->where('performed_by', $performer);
    }
}
