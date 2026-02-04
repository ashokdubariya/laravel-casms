@extends('layouts.dashboard')

@section('title', 'Edit Role')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-medium text-gray-900">Edit Role</h1>
        </div>
        <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-200/70 text-gray-700 font-medium rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
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
                    This is a system role. You can modify permissions but the role itself cannot be deleted.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Form -->
    <form action="{{ route('roles.update', $role) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-tag text-purple-600"></i>
                </div>
                <h2 class="text-lg font-medium text-gray-900">Basic Information</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block font-medium text-gray-700 mb-1">
                        Role Name (System) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                        placeholder="e.g., manager, developer"
                        class="w-full rounded-lg border-gray-300 focus:border-[#1a425f] focus:ring-[#1a425f] @error('name') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Lowercase, no spaces (use underscore or dash)</p>
                    @error('name')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="display_name" class="block font-medium text-gray-700 mb-1">
                        Display Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="display_name" id="display_name" value="{{ old('display_name', $role->display_name) }}" required
                        placeholder="e.g., Manager, Developer"
                        class="w-full rounded-lg border-gray-300 focus:border-[#1a425f] focus:ring-[#1a425f] @error('display_name') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Human-readable name</p>
                    @error('display_name')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                        placeholder="Brief description of this role..."
                        class="w-full rounded-lg border-gray-300 focus:border-[#1a425f] focus:ring-[#1a425f] @error('description') border-red-500 @enderror">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-key text-green-600"></i>
                    </div>
                    <h2 class="text-lg font-medium text-gray-900">Permissions</h2>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="selectAll()" class=" text-[#1a425f] hover:text-[#1a425f]/70">
                        <i class="fas fa-check-double mr-1"></i>Select All
                    </button>
                    <button type="button" onclick="deselectAll()" class=" text-gray-600 hover:text-gray-800">
                        <i class="fas fa-times mr-1"></i>Deselect All
                    </button>
                </div>
            </div>

            @php
                $rolePermissionIds = old('permissions', $role->permissions->pluck('id')->toArray());
            @endphp

            @if(count($permissions) > 0)
            <div class="space-y-6">
                @foreach($permissions as $module => $modulePermissions)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-medium text-gray-900 capitalize">
                            <i class="fas fa-folder text-gray-400 mr-2"></i>
                            {{ ucfirst($module) }} Module
                        </h3>
                        <button type="button" onclick="toggleModule('{{ $module }}')" class=" text-[#1a425f] hover:text-[#1a425f]/70">
                            <i class="fas fa-toggle-on mr-1"></i>Toggle All
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($modulePermissions as $permission)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            <label for="permission_{{ $permission->id }}" class=" text-gray-700 cursor-pointer flex-1">
                                {{ $permission->display_name }}
                            </label>
                            <div class="relative">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                    id="permission_{{ $permission->id }}"
                                    class="module-{{ $module }} sr-only peer"
                                    {{ in_array($permission->id, $rolePermissionIds) ? 'checked' : '' }}>
                                <label for="permission_{{ $permission->id }}" 
                                    class="flex items-center cursor-pointer select-none w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-[#1a425f] peer-focus:ring-2 peer-focus:ring-[#1a425f]/50 transition-colors duration-200">
                                    <span class="w-4 h-4 bg-white rounded-full shadow-sm transform transition-transform duration-200 ml-1 peer-checked:translate-x-5"></span>
                                </label>
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
                <p class="text-gray-500">No permissions available</p>
            </div>
            @endif

            @error('permissions')
            <p class="mt-2  text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('roles.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-200/70 text-gray-700 font-medium rounded-lg transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-save mr-2"></i>Update Role
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function selectAll() {
    document.querySelectorAll('input[type="checkbox"][name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAll() {
    document.querySelectorAll('input[type="checkbox"][name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}

function toggleModule(module) {
    const checkboxes = document.querySelectorAll('.module-' + module);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
}
</script>
@endpush
@endsection
