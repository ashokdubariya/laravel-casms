<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Seed roles and permissions.
     * 
     * Creates default roles and module-based permissions.
     */
    public function run(): void
    {
        // Define modules
        $modules = [
            'dashboard' => 'Dashboard',
            'clients' => 'Clients',
            'approvals' => 'Approval Requests',
            'users' => 'Users',
            'roles' => 'Roles & Permissions',
            'settings' => 'Settings',
        ];

        // Define CRUD actions
        $actions = [
            'view' => 'View',
            'create' => 'Create',
            'update' => 'Update',
            'delete' => 'Delete',
        ];

        // Create permissions for each module (idempotent)
        $permissions = [];
        foreach ($modules as $moduleKey => $moduleName) {
            foreach ($actions as $actionKey => $actionName) {
                $permissionName = "{$moduleKey}.{$actionKey}";
                $permissions[] = Permission::firstOrCreate(
                    ['name' => $permissionName],
                    [
                        'display_name' => "{$actionName} {$moduleName}",
                        'module' => $moduleKey,
                        'description' => "Permission to {$actionName} {$moduleName}",
                    ]
                );
            }
        }

        // Create system roles (idempotent)
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrator',
                'description' => 'Full system access with all permissions',
                'is_system' => true,
            ]
        );

        $managerRole = Role::firstOrCreate(
            ['name' => 'manager'],
            [
                'display_name' => 'Manager',
                'description' => 'Can manage clients and approvals',
                'is_system' => true,
            ]
        );

        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            [
                'display_name' => 'User',
                'description' => 'Basic user with limited permissions',
                'is_system' => true,
            ]
        );

        // Assign permissions to Admin (all permissions) - sync for idempotency
        $adminRole->permissions()->sync(
            Permission::all()->pluck('id')
        );

        // Assign permissions to Manager - sync for idempotency
        $managerPermissions = Permission::whereIn('module', [
            'dashboard',
            'clients',
            'approvals',
        ])->pluck('id');
        $managerRole->permissions()->sync($managerPermissions);

        // Assign permissions to User (read-only mostly) - sync for idempotency
        $userPermissions = Permission::where(function($query) {
            $query->where('name', 'like', '%.view')
                  ->whereIn('module', ['dashboard', 'approvals']);
        })->pluck('id');
        $userRole->permissions()->sync($userPermissions);

        $this->command->info('Roles and permissions seeded successfully');
        $this->command->info(" - Created " . Permission::count() . " permissions");
        $this->command->info(" - Created " . Role::count() . " roles");
    }
}
