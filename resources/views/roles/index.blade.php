@extends('layouts.dashboard')

@section('title', 'Role Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-medium text-gray-900">Role Management</h1>
            <p class="mt-1  text-gray-500">Manage user roles and permissions</p>
        </div>
        @can('create', App\Models\Role::class)
        <a href="{{ route('roles.create') }}" class="inline-flex items-center px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add Role
        </a>
        @endcan
    </div>

    <!-- Roles Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($roles->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Users
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Permissions
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($roles as $role)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0 rounded-lg bg-[#1a425f] flex items-center justify-center">
                                    <i class="fas fa-user-tag text-white"></i>
                                </div>
                                <div class="ml-4">
                                    <div class=" font-medium text-gray-900">{{ $role->display_name }}</div>
                                    <div class=" text-gray-500">
                                        <span class="font-mono">{{ $role->name }}</span>
                                        @if($role->is_system)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#1a425f]/10 text-[#1a425f]">
                                            <i class="fas fa-shield-alt mr-1"></i>System
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class=" text-gray-900">{{ $role->description ?: 'No description' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full font-medium bg-[#1a425f]/10 text-[#1a425f]">
                                <i class="fas fa-users mr-1"></i>
                                {{ $role->users_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full font-medium bg-green-100 text-green-800">
                                <i class="fas fa-key mr-1"></i>
                                {{ $role->permissions_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium space-x-2">
                            <a href="{{ route('roles.show', $role) }}" class="text-[#1a425f] hover:text-[#1a425f]/70" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can('update', $role)
                            <a href="{{ route('roles.edit', $role) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                            @can('delete', $role)
                            @if(!$role->is_system && $role->users_count == 0)
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @else
                            <button disabled class="text-gray-300 cursor-not-allowed" title="Cannot delete system role or role with users">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $roles->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <i class="fas fa-user-shield text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No roles found</h3>
            <p class="text-gray-500 mb-4">Get started by creating your first role</p>
            @can('create', App\Models\Role::class)
            <a href="{{ route('roles.create') }}" class="inline-flex items-center px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Add Role
            </a>
            @endcan
        </div>
        @endif
    </div>

    <!-- Info Box -->
    <div class="bg-[#1a425f]/5 border border-[#1a425f]/20 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-[#1a425f] text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class=" font-medium text-[#1a425f]">About Roles & Permissions</h3>
                <div class="mt-2  text-gray-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>System roles cannot be deleted but can be modified</li>
                        <li>Roles with assigned users cannot be deleted</li>
                        <li>Each role can have multiple permissions across different modules</li>
                        <li>Users inherit all permissions from their assigned role</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
