<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_email',
        'user_name',
        'module',
        'action',
        'record_id',
        'record_type',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create an audit log entry
     */
    public static function log(
        string $module,
        string $action,
        ?Model $record = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        $user = auth()->user();
        
        return static::create([
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'user_name' => $user?->full_name ?? $user?->name,
            'module' => $module,
            'action' => $action,
            'record_id' => $record?->id,
            'record_type' => $record ? get_class($record) : null,
            'description' => $description ?? static::generateDescription($module, $action, $record),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Generate a human-readable description
     */
    protected static function generateDescription(string $module, string $action, ?Model $record): string
    {
        $recordIdentifier = $record ? "#{$record->id}" : '';
        
        return match($action) {
            'create' => "Created {$module} {$recordIdentifier}",
            'update' => "Updated {$module} {$recordIdentifier}",
            'delete' => "Deleted {$module} {$recordIdentifier}",
            'view' => "Viewed {$module} {$recordIdentifier}",
            'login' => "User logged in",
            'logout' => "User logged out",
            'approve' => "Approved {$module} {$recordIdentifier}",
            'reject' => "Rejected {$module} {$recordIdentifier}",
            default => ucfirst($action) . " {$module} {$recordIdentifier}",
        };
    }

    /**
     * Get icon for action
     */
    public function getIconAttribute(): string
    {
        return match($this->action) {
            'create' => 'fa-plus',
            'update' => 'fa-edit',
            'delete' => 'fa-trash',
            'view' => 'fa-eye',
            'login' => 'fa-sign-in-alt',
            'logout' => 'fa-sign-out-alt',
            'approve' => 'fa-check',
            'reject' => 'fa-times',
            default => 'fa-circle',
        };
    }

    /**
     * Get color for action
     */
    public function getColorAttribute(): string
    {
        return match($this->action) {
            'create' => 'green',
            'update' => 'blue',
            'delete' => 'red',
            'view' => 'gray',
            'login' => 'green',
            'logout' => 'gray',
            'approve' => 'green',
            'reject' => 'red',
            default => 'gray',
        };
    }
}
