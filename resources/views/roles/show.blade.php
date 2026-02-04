@extends('layouts.dashboard')

@section('title', 'Role Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-medium text-gray-900">{{ $role->display_name }}</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-200/70 text-gray-700 font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            @can('update', $role)
            <a href="{{ route('roles.edit', $role) }}" class="px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            @endcan
        </div>
    </div>

    @if($role->is_system)
    <div class="bg-[#1a425f]/5 border border-[#1a425f]/20 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-shield-alt text-[#1a425f] text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class=" font-medium text-[#1a425f]">System Role</h3>
                <p class="mt-1  text-gray-700">
                    This is a system-protected role that cannot be deleted.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" font-medium text-gray-600">Total Users</p>
                    <p class="text-3xl font-medium text-gray-900 mt-2">{{ $role->users->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-[#1a425f]/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-[#1a425f] text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" font-medium text-gray-600">Permissions</p>
                    <p class="text-3xl font-medium text-gray-900 mt-2">{{ $role->permissions->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-key text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" font-medium text-gray-600">Role Type</p>
                    <p class="text-lg font-medium mt-2">
                        @if($role->is_system)
                        <span class="text-[#1a425f]"><i class="fas fa-shield-alt mr-1"></i>System</span>
                        @else
                        <span class="text-purple-600"><i class="fas fa-user-tag mr-1"></i>Custom</span>
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tag text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Permissions -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-6">
                    <i class="fas fa-key text-gray-400 mr-2"></i>
                    Assigned Permissions
                </h2>

                @php
                    $groupedPermissions = $role->permissions->groupBy('module');
                @endphp

                @if($groupedPermissions->count() > 0)
                <div class="space-y-6">
                    @foreach($groupedPermissions as $module => $permissions)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="text-base font-medium text-gray-900 capitalize mb-4">
                            <i class="fas fa-folder text-gray-400 mr-2"></i>
                            {{ ucfirst($module) }} Module
                            <span class="ml-2 font-normal text-gray-500">({{ $permissions->count() }} permissions)</span>
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($permissions as $permission)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-green-600 "></i>
                                </div>
                                <div>
                                    <p class=" font-medium text-gray-900">{{ $permission->display_name }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $permission->name }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-key text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500">No permissions assigned to this role</p>
                    @can('update', $role)
                    <a href="{{ route('roles.edit', $role) }}" class="inline-flex items-center px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors mt-4">
                        <i class="fas fa-plus mr-2"></i>
                        Assign Permissions
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Role Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-gray-400 mr-2"></i>
                    Role Information
                </h3>

                <div class="space-y-4">
                    <div>
                        <p class=" font-medium text-gray-500 mb-1">System Name</p>
                        <p class=" text-gray-900 font-mono">{{ $role->name }}</p>
                    </div>

                    <div>
                        <p class=" font-medium text-gray-500 mb-1">Display Name</p>
                        <p class=" text-gray-900">{{ $role->display_name }}</p>
                    </div>

                    @if($role->description)
                    <div>
                        <p class=" font-medium text-gray-500 mb-1">Description</p>
                        <p class=" text-gray-900">{{ $role->description }}</p>
                    </div>
                    @endif

                    <div>
                        <p class=" font-medium text-gray-500 mb-1">Created</p>
                        <p class=" text-gray-900">
                            {{ $role->created_at->format('M d, Y') }}
                            <span class="text-gray-500 text-xs">({{ $role->created_at->diffForHumans() }})</span>
                        </p>
                    </div>

                    @if($role->updated_at != $role->created_at)
                    <div>
                        <p class=" font-medium text-gray-500 mb-1">Last Updated</p>
                        <p class=" text-gray-900">
                            {{ $role->updated_at->format('M d, Y') }}
                            <span class="text-gray-500 text-xs">({{ $role->updated_at->diffForHumans() }})</span>
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Assigned Users -->
            @if($role->users->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-users text-gray-400 mr-2"></i>
                    Assigned Users
                </h3>

                <div class="space-y-3">
                    @foreach($role->users->take(10) as $user)
                    <a href="{{ route('users.show', $user) }}" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-[#1a425f] to-[#1a425f]/70 flex items-center justify-center text-white font-medium flex-shrink-0">
                            {{ strtoupper(substr($user->full_name, 0, 1)) }}
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class=" font-medium text-gray-900 truncate">{{ $user->full_name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        </div>
                        @if($user->status === 'active')
                        <div class="ml-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </div>
                        @endif
                    </a>
                    @endforeach

                    @if($role->users->count() > 10)
                    <p class=" text-gray-500 text-center pt-2">
                        And {{ $role->users->count() - 10 }} more users...
                    </p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
