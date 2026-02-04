@extends('layouts.dashboard')

@section('title', 'Clients')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-medium text-[#0F172A]">Client Management</h1>
            <p class="mt-1  text-gray-500">Manage clients and their information</p>
        </div>
        @if(auth()->user()->hasPermission('clients.create'))
        <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2.5 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors shadow-sm">
            <i class="fas fa-plus mr-2"></i>
            Add Client
        </a>
        @endif
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class=" font-medium text-gray-600">Total Clients</p>
                    <p class="text-3xl font-medium text-[#0F172A] mt-2">{{ number_format($stats['total'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-[#1a425f]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-users text-[#1a425f] text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class=" font-medium text-gray-600">Active</p>
                    <p class="text-3xl font-medium text-[#85c34e] mt-2">{{ number_format($stats['active'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-[#85c34e]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-[#85c34e] text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class=" font-medium text-gray-600">Inactive</p>
                    <p class="text-3xl font-medium text-gray-600 mt-2">{{ number_format($stats['inactive'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-times-circle text-gray-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class=" font-medium text-gray-600">With Approvals</p>
                    <p class="text-3xl font-medium text-[#1a425f] mt-2">{{ number_format($stats['with_approvals'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-[#1a425f]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-alt text-[#1a425f] text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters & Search -->
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
        <form method="GET" action="{{ route('clients.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search clients..." 
                           class="w-full pl-10 pr-4 py-2.5 border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-[#1a425f] ">
                </div>
            </div>

            <select name="status" class="px-4 py-2.5 border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-[#1a425f] font-medium text-[#0F172A]">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>

            <button type="submit" class="px-5 py-2.5 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors ">
                <i class="fas fa-filter mr-2"></i>
                Apply Filters
            </button>

            @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('clients.index') }}" class="px-5 py-2.5 border border-[#CBD5E1] text-gray-600 hover:bg-[#F8FAFC] font-medium rounded-lg transition-colors ">
                <i class="fas fa-times mr-2"></i>
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Clients Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($clients->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <a href="{{ route('clients.index', array_merge(request()->all(), ['sort' => 'first_name', 'direction' => request('sort') === 'first_name' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="group inline-flex items-center text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Client
                                @if(request('sort') === 'first_name')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1 text-[#1a425f]"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-0 group-hover:opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <a href="{{ route('clients.index', array_merge(request()->all(), ['sort' => 'email', 'direction' => request('sort') === 'email' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="group inline-flex items-center text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Contact
                                @if(request('sort') === 'email')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1 text-[#1a425f]"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-0 group-hover:opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <a href="{{ route('clients.index', array_merge(request()->all(), ['sort' => 'company_name', 'direction' => request('sort') === 'company_name' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="group inline-flex items-center text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Company
                                @if(request('sort') === 'company_name')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1 text-[#1a425f]"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-0 group-hover:opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Approvals
                        </th>
                        <th class="px-6 py-3 text-left">
                            <a href="{{ route('clients.index', array_merge(request()->all(), ['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="group inline-flex items-center text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Status
                                @if(request('sort') === 'status')
                                    <i class="fas fa-sort-{{ request('direction') === 'asc' ? 'up' : 'down' }} ml-1 text-[#1a425f]"></i>
                                @else
                                    <i class="fas fa-sort ml-1 opacity-0 group-hover:opacity-50"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($clients as $client)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary-blue rounded-full flex items-center justify-center text-white font-medium ">
                                    {{ substr($client->first_name, 0, 1) }}{{ substr($client->last_name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class=" font-medium text-gray-900">
                                        {{ $client->full_name }}
                                    </div>
                                    <div class=" text-gray-500">
                                        Added {{ $client->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class=" text-gray-900">
                                <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                {{ $client->email }}
                            </div>
                            @if($client->phone)
                            <div class=" text-gray-500 mt-1">
                                <i class="fas fa-phone text-gray-400 mr-2"></i>
                                {{ $client->phone }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($client->company_name)
                                <div class=" text-gray-900">{{ $client->company_name }}</div>
                                @if($client->website)
                                <a href="{{ $client->website }}" target="_blank" class=" text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-external-link-alt mr-1"></i>
                                    Website
                                </a>
                                @endif
                            @else
                                <span class=" text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class=" font-medium text-gray-900">
                                {{ number_format($client->approval_requests_count ?? 0) }}
                            </div>
                            <div class="text-xs text-gray-500">Total requests</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $client->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($client->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('clients.show', $client) }}" class="text-[#1a425f] hover:text-[#1a425f]/80" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(auth()->user()->hasPermission('clients.update'))
                                <a href="{{ route('clients.edit', $client) }}" class="text-blue-600 hover:text-blue-800" title="Edit Client">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @if(auth()->user()->hasPermission('clients.delete'))
                                <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline-block" 
                                      onsubmit="return confirm('Are you sure you want to delete {{ $client->full_name }}? This action cannot be undone.');
">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete Client">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200">
            {{ $clients->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No clients found</h3>
            <p class="text-gray-500 mb-6">Get started by adding your first client.</p>
            @if(auth()->user()->hasPermission('clients.create'))
            <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2.5 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg shadow-sm">
                <i class="fas fa-plus mr-2"></i>
                Add Client
            </a>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
