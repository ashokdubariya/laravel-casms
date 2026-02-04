<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Role & Permission Management Controller
 */
class RoleController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Role::class);
        
        $roles = Role::withCount(['users', 'permissions'])->paginate(15);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('create', Role::class);
        
        $permissions = Permission::all()->groupBy('module');
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Role::class);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name|alpha_dash',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_system' => false,
        ]);

        if (!empty($validated['permissions'])) {
            $role->permissions()->sync($validated['permissions']);
        }

        AuditLog::log('roles', 'create', $role, 'Role created', null, $validated);

        return redirect()->route('roles.show', $role)
            ->with('success', 'Role created successfully!');
    }

    public function show(Role $role)
    {
        $this->authorize('view', $role);
        
        $role->load(['permissions', 'users']);
        $permissionsByModule = $role->permissions->groupBy('module');
        
        return view('roles.show', compact('role', 'permissionsByModule'));
    }

    public function edit(Role $role)
    {
        $this->authorize('update', $role);
        
        $permissions = Permission::all()->groupBy('module');
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('update', $role);
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('roles')->ignore($role->id)],
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $oldValues = $role->only(['name', 'display_name', 'description']);
        $oldPermissions = $role->permissions->pluck('id')->toArray();

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        AuditLog::log('roles', 'update', $role, 'Role updated', 
            array_merge($oldValues, ['permissions' => $oldPermissions]), 
            array_merge($validated, ['permissions' => $validated['permissions'] ?? []]));

        return redirect()->route('roles.show', $role)
            ->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        
        if ($role->is_system) {
            return redirect()->route('roles.index')
                ->with('error', 'System roles cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete role that has assigned users. Please reassign users first.');
        }

        $oldValues = $role->toArray();
        $role->delete();

        AuditLog::log('roles', 'delete', $role, 'Role deleted', $oldValues);

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully!');
    }

    public function permissions()
    {
        $permissions = Permission::all()->groupBy('module');
        return view('roles.permissions', compact('permissions'));
    }
}
