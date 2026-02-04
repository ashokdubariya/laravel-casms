@extends('layouts.dashboard')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-medium text-[#0F172A]">User Management</h1>
            <p class="mt-1  text-gray-500">Manage system users and their roles</p>
        </div>
        @can('create', App\Models\User::class)
        <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Add User
        </a>
        @endcan
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
        <form method="GET" action="{{ route('users.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label for="search" class="block font-medium text-[#0F172A] mb-2">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                            placeholder="Search by name, email, phone..." 
                            class="w-full pl-10 pr-4 py-2.5 border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-[#1a425f] font-medium">
                    </div>
                </div>

                <!-- Role Filter -->
                <div>
                    <label for="role_id" class="block font-medium text-[#0F172A] mb-2">Role</label>
                    <select name="role_id" id="role_id" class="w-full pl-1 pr-1 py-2.5 border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-[#1a425f] font-medium">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->display_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block font-medium text-[#0F172A] mb-2">Status</label>
                    <select name="status" id="status" class="w-full pl-1 pr-1 py-2.5 border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-[#1a425f] font-medium">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-[#0F172A] mb-2">&nbsp;</label>
                    <button type="submit" class="px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors shadow-sm">
                        <i class="fas fa-filter mr-2"></i>Apply Filters
                    </button>
                    <a href="{{ route('users.index') }}" class="px-5 py-2.5 border border-[#CBD5E1] text-gray-600 hover:bg-[#F8FAFC] font-medium rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Last Login
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <div class="w-8 h-8 bg-primary-blue rounded-full flex items-center justify-center text-white font-medium ">
                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class=" font-medium text-gray-900">{{ $user->full_name }}</div>
                                    <div class=" text-gray-500">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class=" text-gray-900">
                                <i class="fas fa-envelope text-gray-400 mr-1"></i>
                                {{ $user->email }}
                            </div>
                            @if($user->phone)
                            <div class=" text-gray-500 mt-1">
                                <i class="fas fa-phone text-gray-400 mr-1"></i>
                                {{ $user->phone }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->role)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-user-tag mr-1"></i>
                                {{ $user->role->display_name }}
                            </span>
                            @else
                            <span class=" text-gray-400">No role</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                Active
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Inactive
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap  text-gray-500">
                            @if($user->last_login_at)
                                {{ $user->last_login_at->diffForHumans() }}
                            @else
                                <span class="text-gray-400">Never</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium space-x-2">
                            <a href="{{ route('users.show', $user) }}" class="text-[#1a425f] hover:text-[#1a425f]/70" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can('update', $user)
                            <a href="{{ route('users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                            
                            @can('delete', $user)
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
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
            {{ $users->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No users found</h3>
            <p class="text-gray-500 mb-4">
                @if(request()->has('search') || request()->has('role_id') || request()->has('status'))
                    Try adjusting your search or filter criteria
                @else
                    Get started by creating your first user
                @endif
            </p>
            @can('create', App\Models\User::class)
            @if(!request()->has('search') && !request()->has('role_id') && !request()->has('status'))
            <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Add User
            </a>
            @endif
            @endcan
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleStatus(userId) {
    if (!confirm('Are you sure you want to toggle this user\'s status?')) {
        return;
    }

    fetch(`/users/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Failed to toggle status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to toggle status');
    });
}
</script>
@endpush
@endsection
