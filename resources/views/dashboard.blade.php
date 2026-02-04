@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-[#1a425f] rounded-lg shadow-sm p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-medium">Welcome back, {{ auth()->user()->full_name }}!</h1>
                <p class="mt-1 text-white/80">{{ date('l, F j, Y') }}</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-line text-6xl text-white/20"></i>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    @if($canViewAll ?? false)
    <!-- Admin/Manager View -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class=" font-medium text-gray-600">Total Clients</p>
                    <p class="text-3xl font-medium text-[#0F172A] mt-2">{{ number_format($stats['total_clients'] ?? 0) }}</p>
                    <p class=" text-[#85c34e] mt-1">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ number_format($stats['active_clients'] ?? 0) }} active
                    </p>
                </div>
                <div class="w-12 h-12 bg-[#1a425f]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-users text-[#1a425f] text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class=" font-medium text-gray-600">Total Approvals</p>
                    <p class="text-3xl font-medium text-[#0F172A] mt-2">{{ number_format($stats['total_approvals'] ?? 0) }}</p>
                    <p class=" text-amber-600 mt-1">
                        <i class="fas fa-clock mr-1"></i>
                        {{ number_format($stats['pending_approvals'] ?? 0) }} pending
                    </p>
                </div>
                <div class="w-12 h-12 bg-[#85c34e]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-[#85c34e] text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class=" font-medium text-gray-600">Approved Today</p>
                    <p class="text-3xl font-medium text-[#0F172A] mt-2">{{ number_format($stats['approved_today'] ?? 0) }}</p>
                    <p class=" text-[#1a425f] mt-1">
                        <i class="fas fa-users-cog mr-1"></i>
                        {{ number_format($stats['total_users'] ?? 0) }} team members
                    </p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-calendar-check text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Regular User View -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class=" font-medium text-gray-600">Total Requests</p>
                    <p class="text-3xl font-medium text-[#0F172A] mt-2">{{ number_format($stats['total'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-alt text-gray-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class=" font-medium text-gray-600">Pending</p>
                    <p class="text-3xl font-medium text-amber-600 mt-2">{{ number_format($stats['pending'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clock text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class=" font-medium text-gray-600">Approved</p>
                    <p class="text-3xl font-medium text-[#85c34e] mt-2">{{ number_format($stats['approved'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-[#85c34e]/10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-[#85c34e] text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class=" font-medium text-gray-600">Rejected</p>
                    <p class="text-3xl font-medium text-red-600 mt-2">{{ number_format($stats['rejected'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Approvals -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-lg font-medium text-[#0F172A]">
                        <i class="fas fa-clipboard-list text-gray-400 mr-2"></i>
                        Recent Approval Requests
                    </h2>
                    @if(auth()->user()->hasPermission('approvals.create'))
                    <a href="{{ route('approvals.create') }}" class=" text-[#1a425f] hover:text-[#1a425f]/80 font-medium transition-colors">
                        <i class="fas fa-plus mr-1"></i>New Request
                    </a>
                    @endif
                </div>

                <div class="divide-y divide-gray-200">
                    @forelse($recentApprovals as $approval)
                    <a href="{{ route('approvals.show', $approval) }}" class="block px-6 py-4 hover:bg-[#F8FAFC] transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class=" font-medium text-[#0F172A] truncate">{{ $approval->title }}</p>
                                    @if($approval->priority === 'high')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-arrow-up mr-1"></i>High
                                    </span>
                                    @endif
                                </div>
                                
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    @if($approval->client)
                                    <span>
                                        <i class="fas fa-building mr-1"></i>
                                        {{ $approval->client->full_name }}
                                    </span>
                                    @endif
                                    <span>
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $approval->created_at->diffForHumans() }}
                                    </span>
                                    @if($approval->attachments_count > 0)
                                    <span>
                                        <i class="fas fa-paperclip mr-1"></i>
                                        {{ $approval->attachments_count }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="ml-4 flex-shrink-0">
                                @if($approval->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                                @elseif($approval->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#85c34e]/10 text-[#85c34e]">
                                    <i class="fas fa-check-circle mr-1"></i>Approved
                                </span>
                                @elseif($approval->status === 'rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>Rejected
                                </span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="px-6 py-12 text-center">
                        <i class="fas fa-clipboard text-gray-300 text-5xl mb-3"></i>
                        <p class="text-gray-500 mb-4">No approval requests yet</p>
                        @if(auth()->user()->hasPermission('approvals.create'))
                        <a href="{{ route('approvals.create') }}" class="inline-flex items-center px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>Create First Request
                        </a>
                        @endif
                    </div>
                    @endforelse
                </div>

                @if($recentApprovals->count() > 0)
                <div class="px-6 py-3 bg-[#F8FAFC] border-t border-gray-200 text-center">
                    <a href="{{ route('approvals.index') }}" class=" text-[#1a425f] hover:text-[#1a425f]/80 font-medium transition-colors">
                        View All Approvals <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <h3 class="text-lg font-medium text-[#0F172A] mb-4">
                    <i class="fas fa-bolt text-gray-400 mr-2"></i>
                    Quick Actions
                </h3>

                <div class="space-y-2">
                    @if(auth()->user()->hasPermission('approvals.create'))
                    <a href="{{ route('approvals.create') }}" class="flex items-center p-3 border border-[#CBD5E1] rounded-lg hover:bg-[#1a425f]/5 hover:border-[#1a425f] transition-all">
                        <div class="w-10 h-10 bg-[#1a425f]/10 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-plus text-[#1a425f]"></i>
                        </div>
                        <div>
                            <p class=" font-medium text-[#0F172A]">New Approval</p>
                            <p class="text-xs text-gray-500">Create request</p>
                        </div>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('clients.create'))
                    <a href="{{ route('clients.create') }}" class="flex items-center p-3 border border-[#CBD5E1] rounded-lg hover:bg-[#85c34e]/5 hover:border-[#85c34e] transition-all">
                        <div class="w-10 h-10 bg-[#85c34e]/10 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-plus text-[#85c34e]"></i>
                        </div>
                        <div>
                            <p class=" font-medium text-[#0F172A]">New Client</p>
                            <p class="text-xs text-gray-500">Add client</p>
                        </div>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('users.create'))
                    <a href="{{ route('users.create') }}" class="flex items-center p-3 border border-[#CBD5E1] rounded-lg hover:bg-amber-50 hover:border-amber-300 transition-all">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-tie text-amber-600"></i>
                        </div>
                        <div>
                            <p class=" font-medium text-[#0F172A]">New User</p>
                            <p class="text-xs text-gray-500">Add team member</p>
                        </div>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Recent Clients (Admin/Manager only) -->
            @if($canViewAll ?? false)
            @if(isset($recentClients) && $recentClients->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-[#0F172A]">
                        <i class="fas fa-users text-gray-400 mr-2"></i>
                        Recent Clients
                    </h3>
                    <a href="{{ route('clients.index') }}" class=" text-[#1a425f] hover:text-[#1a425f]/80 font-medium transition-colors">
                        View All
                    </a>
                </div>

                <div class="space-y-3">
                    @foreach($recentClients as $client)
                    <a href="{{ route('clients.show', $client) }}" class="flex items-center p-3 border border-[#CBD5E1] rounded-lg hover:bg-[#F8FAFC] hover:border-[#1a425f] transition-all">
                        <div class="h-10 w-10 rounded-full bg-[#1a425f] flex items-center justify-center text-white font-medium flex-shrink-0">
                            {{ strtoupper(substr($client->full_name, 0, 1)) }}
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class=" font-medium text-[#0F172A] truncate">{{ $client->full_name }}</p>
                            @if($client->company_name)
                            <p class="text-xs text-gray-500 truncate">{{ $client->company_name }}</p>
                            @else
                            <p class="text-xs text-gray-500 truncate">{{ $client->email }}</p>
                            @endif
                        </div>
                        @if($client->status === 'active')
                        <span class="ml-2 h-2 w-2 bg-[#85c34e] rounded-full"></span>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
            @endif
        </div>
    </div>
</div>
@endsection
