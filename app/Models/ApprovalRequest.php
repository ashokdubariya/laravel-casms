<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'created_by', // Renamed from user_id for clarity
        'client_id', // NEW: Link to clients table
        'title',
        'description',
        'version',
        'priority', // NEW: low, medium, high
        'due_date', // NEW: deadline
        'message', // NEW: message to client
        'client_name', // Legacy field - can be removed after migration
        'client_email', // Legacy field - can be removed after migration
        'status',
        'approved_at',
        'rejected_at',
        'internal_notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'due_date' => 'date',
    ];

    /**
     * Relationship: Approval request belongs to a client.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relationship: Approval request created by a user (team member).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Legacy relationship - backward compatibility.
     * @deprecated Use creator() instead
     */
    public function user()
    {
        return $this->creator();
    }

    /**
     * Relationship: Approval request has many attachments.
     */
    public function attachments()
    {
        return $this->hasMany(ApprovalAttachment::class);
    }

    /**
     * Relationship: Approval request has many tokens.
     */
    public function tokens()
    {
        return $this->hasMany(ApprovalToken::class);
    }

    /**
     * Relationship: Approval request has many history entries.
     */
    public function history()
    {
        return $this->hasMany(ApprovalHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the active (valid, unused) token for this approval request.
     */
    public function activeToken()
    {
        return $this->hasOne(ApprovalToken::class)
                    ->where('expires_at', '>', now())
                    ->whereNull('used_at')
                    ->latest();
    }

    /**
     * Check if approval is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if approval is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if approval is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Scope: Get only pending approvals.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get only approved approvals.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Get only rejected approvals.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: Get approvals for a specific client email.
     */
    public function scopeForClient($query, string $email)
    {
        return $query->where('client_email', $email);
    }

    /**
     * Get the status badge color (for UI).
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'approved' => '#85c34e',
            'rejected' => '#ef4444',
            'pending' => '#f59e0b',
            default => '#6b7280',
        };
    }
}
