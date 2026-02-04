@extends('layouts.dashboard')

@section('title', 'User Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-medium text-gray-900">{{ $user->full_name }}</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-200/70 text-gray-700 font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
            @can('update', $user)
            <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            @endcan
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" font-medium text-gray-600">Created Clients</p>
                    <p class="text-3xl font-medium text-gray-900 mt-2">{{ $user->createdClients->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-[#1a425f]/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-[#1a425f] text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" font-medium text-gray-600">Created Approvals</p>
                    <p class="text-3xl font-medium text-gray-900 mt-2">{{ $user->approvalRequests->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" font-medium text-gray-600">Account Status</p>
                    <p class="text-lg font-medium mt-2">
                        @if($user->status === 'active')
                        <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Active</span>
                        @else
                        <span class="text-red-600"><i class="fas fa-times-circle mr-1"></i>Inactive</span>
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-shield text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-[#1a425f]/10 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-user text-[#1a425f]"></i>
                    </div>
                    <h2 class="text-lg font-medium text-gray-900">Personal Information</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class=" font-medium text-gray-500 mb-1">Full Name</p>
                        <p class="text-base text-gray-900">{{ $user->full_name }}</p>
                    </div>

                    <div>
                        <p class=" font-medium text-gray-500 mb-1">Email Address</p>
                        <p class="text-base text-gray-900">
                            <a href="mailto:{{ $user->email }}" class="text-[#1a425f] hover:text-[#1a425f]/70">
                                {{ $user->email }}
                            </a>
                        </p>
                    </div>

                    @if($user->phone)
                    <div>
                        <p class=" font-medium text-gray-500 mb-1">Phone Number</p>
                        <p class="text-base text-gray-900">
                            <a href="tel:{{ $user->phone }}" class="text-[#1a425f] hover:text-[#1a425f]/70">
                                {{ $user->phone }}
                            </a>
                        </p>
                    </div>
                    @endif

                    <div>
                        <p class=" font-medium text-gray-500 mb-1">User ID</p>
                        <p class="text-base text-gray-900 font-mono">#{{ $user->id }}</p>
                    </div>
                </div>
            </div>

            <!-- Recent Approval Requests -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-clipboard-list text-gray-400 mr-2"></i>
                    Recent Approval Requests
                </h3>

                @if($user->approvalRequests->count() > 0)
                <div class="space-y-3">
                    @foreach($user->approvalRequests->take(10) as $approval)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class=" font-medium text-gray-900">{{ $approval->title }}</h4>
                                @if($approval->description)
                                <p class=" text-gray-600 mt-1 line-clamp-2">{{ $approval->description }}</p>
                                @endif
                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                    @if($approval->client)
                                    <span>
                                        <i class="fas fa-building mr-1"></i>
                                        {{ $approval->client->full_name }}
                                    </span>
                                    @endif
                                    <span>
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $approval->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4">
                                @if($approval->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    Pending
                                </span>
                                @elseif($approval->status === 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Approved
                                </span>
                                @elseif($approval->status === 'rejected')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Rejected
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-clipboard text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500">No approval requests created yet</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Account Information -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-info-circle text-gray-400 mr-2"></i>
                    Account Information
                </h3>

                <div class="space-y-4">
                    <div>
                        <p class=" font-medium text-gray-500 mb-1">Role</p>
                        @if($user->role)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="fas fa-user-tag mr-1"></i>
                            {{ $user->role->display_name }}
                        </span>
                        @else
                        <span class=" text-gray-400">No role assigned</span>
                        @endif
                    </div>

                    <div>
                        <p class=" font-medium text-gray-500 mb-1">Status</p>
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
                    </div>

                    <div>
                        <p class=" font-medium text-gray-500 mb-1">Last Login</p>
                        <p class=" text-gray-900">
                            @if($user->last_login_at)
                                {{ $user->last_login_at->format('M d, Y H:i') }}
                                <span class="text-gray-500 text-xs">({{ $user->last_login_at->diffForHumans() }})</span>
                            @else
                                <span class="text-gray-400">Never logged in</span>
                            @endif
                        </p>
                    </div>

                    <div>
                        <p class=" font-medium text-gray-500 mb-1">Member Since</p>
                        <p class=" text-gray-900">
                            {{ $user->created_at->format('M d, Y') }}
                            <span class="text-gray-500 text-xs">({{ $user->created_at->diffForHumans() }})</span>
                        </p>
                    </div>

                    @if($user->creator)
                    <div>
                        <p class=" font-medium text-gray-500 mb-1">Created By</p>
                        <p class=" text-gray-900">{{ $user->creator->full_name }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Clients Created -->
            @if($user->createdClients->count() > 0)
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-users text-gray-400 mr-2"></i>
                    Recent Clients Created
                </h3>

                <div class="space-y-3">
                    @foreach($user->createdClients->take(5) as $client)
                    <a href="{{ route('clients.show', $client) }}" class="block border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-[#1a425f] to-[#1a425f]/70 flex items-center justify-center text-white font-medium flex-shrink-0">
                                {{ strtoupper(substr($client->full_name, 0, 1)) }}
                            </div>
                            <div class="ml-3 flex-1 min-w-0">
                                <p class=" font-medium text-gray-900 truncate">{{ $client->full_name }}</p>
                                @if($client->company_name)
                                <p class="text-xs text-gray-500 truncate">{{ $client->company_name }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
