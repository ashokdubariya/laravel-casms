<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name', // Legacy - keep for backward compatibility
        'first_name',
        'last_name',
        'email',
        'avatar',
        'password',
        'phone',
        'role_id', // Foreign key to roles table
        'status',
        'is_active', // Legacy - keep for backward compatibility
        'last_login_at',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role', // Hide legacy role column to prevent shadowing role() relationship
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            return "{$this->first_name} {$this->last_name}";
        }
        return $this->name ?? 'Unknown User';
    }

    /**
     * Get the user's avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && \Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }
        
        // Return default avatar with initials
        return $this->getDefaultAvatarUrl();
    }

    /**
     * Get default avatar URL with initials.
     */
    public function getDefaultAvatarUrl(): string
    {
        $initials = strtoupper(substr($this->first_name ?? $this->name ?? 'U', 0, 1) . substr($this->last_name ?? '', 0, 1));
        return 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&color=fff&background=1a425f&bold=true&size=200';
    }

    /**
     * Override the role attribute to return the relationship instead of the legacy column.
     * This is needed because the database still has a 'role' column that shadows the relationship.
     */
    public function getRoleAttribute()
    {
        // If we're trying to access the raw attribute (for queries), return it
        if (!$this->relationLoaded('role')) {
            $this->load('role');
        }
        return $this->getRelation('role');
    }

    /**
     * Relationship: User belongs to a role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relationship: User who created this user.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship: User who last updated this user.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relationship: Clients created by this user.
     */
    public function createdClients()
    {
        return $this->hasMany(Client::class, 'created_by');
    }

    /**
     * Relationship: User has many approval requests (as creator).
     */
    public function approvalRequests()
    {
        return $this->hasMany(ApprovalRequest::class, 'created_by');
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->role?->hasPermission($permissionName) ?? false;
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role?->name === $roleName;
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' || $this->is_active === true;
    }

    /**
     * Scope: Get only active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get only admin users.
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope: Get only team members.
     */
    public function scopeTeamMembers($query)
    {
        return $query->where('role', 'team_member');
    }
}
