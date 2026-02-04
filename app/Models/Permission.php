<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Permission Model
 * 
 * Represents granular permissions in the RBAC system.
 * Permissions are grouped by module and assigned to roles.
 * 
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string $module
 * @property string|null $description
 */
class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'module',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Roles that have this permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role')
            ->withTimestamps();
    }

    /**
     * Group permissions by module.
     */
    public static function groupedByModule(): array
    {
        return static::all()->groupBy('module')->toArray();
    }

    /**
     * Get permissions for a specific module.
     */
    public static function forModule(string $module)
    {
        return static::where('module', $module)->get();
    }
}
