@extends('layouts.dashboard')

@section('title', $client->full_name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-medium text-[#0F172A]">{{ $client->full_name }}</h1>
            <p class="mt-1  text-gray-500">
                <a href="{{ route('clients.index') }}" class="text-[#1a425f] hover:text-[#1a425f]/80">Clients</a>
                <span class="mx-1">/</span>
                <span>{{ $client->full_name }}</span>
            </p>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('clients.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Clients
            </a>
            @if(auth()->user()->hasPermission('clients.update'))
            <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-4 py-2.5 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg shadow-sm">
                <i class="fas fa-edit mr-2"></i>
                Edit Client
            </a>
            @endif
        </div>
    </div>

    <!-- Approval Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Total Approvals</p>
                    <p class="text-2xl font-bold text-[#0F172A] mt-1">{{ number_format($stats['total'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-[#1a425f]/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-[#1a425f]"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Pending</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">{{ number_format($stats['pending'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Approved</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['approved'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Rejected</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($stats['rejected'] ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Client Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Personal Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <i class="fas fa-user text-[#1a425f] mr-2"></i>
                        Personal Information
                    </h3>
                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $client->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($client->status) }}
                    </span>
                </div>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class=" font-medium text-gray-500 mb-1">First Name</dt>
                        <dd class=" text-gray-900">{{ $client->first_name }}</dd>
                    </div>
                    <div>
                        <dt class=" font-medium text-gray-500 mb-1">Last Name</dt>
                        <dd class=" text-gray-900">{{ $client->last_name }}</dd>
                    </div>
                    <div>
                        <dt class=" font-medium text-gray-500 mb-1">Email</dt>
                        <dd class=" text-gray-900">
                            <a href="mailto:{{ $client->email }}" class="text-[#1a425f] hover:text-[#1a425f]/80">
                                <i class="fas fa-envelope mr-1"></i>{{ $client->email }}
                            </a>
                        </dd>
                    </div>
                    @if($client->phone)
                    <div>
                        <dt class=" font-medium text-gray-500 mb-1">Phone</dt>
                        <dd class=" text-gray-900">
                            <a href="tel:{{ $client->phone }}" class="text-[#1a425f] hover:text-[#1a425f]/80">
                                <i class="fas fa-phone mr-1"></i>{{ $client->phone }}
                            </a>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Company Information -->
            @if($client->company_name || $client->website)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-building text-[#1a425f] mr-2"></i>
                    Company Information
                </h3>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    @if($client->company_name)
                    <div>
                        <dt class=" font-medium text-gray-500 mb-1">Company Name</dt>
                        <dd class=" text-gray-900">{{ $client->company_name }}</dd>
                    </div>
                    @endif
                    @if($client->website)
                    <div>
                        <dt class=" font-medium text-gray-500 mb-1">Website</dt>
                        <dd class="">
                            <a href="{{ $client->website }}" target="_blank" class="text-[#1a425f] hover:text-[#1a425f]/80">
                                {{ $client->website }} <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                            </a>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
            @endif

            <!-- Address -->
            @if($client->address || $client->city || $client->state || $client->country || $client->postal_code)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-map-marker-alt text-[#1a425f] mr-2"></i>
                    Address
                </h3>
                <div class=" text-gray-900">
                    @if($client->address)
                        <p>{{ $client->address }}</p>
                    @endif
                    @if($client->city || $client->state || $client->postal_code)
                        <p class="{{ $client->address ? 'mt-1' : '' }}">
                            @if($client->city){{ $client->city }}@endif
                            @if($client->city && $client->state), @endif
                            @if($client->state){{ $client->state }}@endif
                            @if($client->postal_code){{ $client->postal_code }}@endif
                        </p>
                    @endif
                    @if($client->country)
                        <p class="mt-1">{{ $client->country }}</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Recent Approvals -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Approval Requests</h3>
                </div>
                
                @if($client->approvalRequests->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($client->approvalRequests as $approval)
                    <a href="{{ route('approvals.show', $approval) }}" class="block p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class=" font-medium text-gray-900">{{ $approval->title }}</h4>
                                <p class=" text-gray-500 mt-1">{{ Str::limit($approval->description, 80) }}</p>
                                <div class="flex items-center mt-2 space-x-4 text-xs text-gray-500">
                                    <span><i class="fas fa-user mr-1"></i>{{ $approval->creator->full_name ?? $approval->creator->name }}</span>
                                    <span><i class="fas fa-clock mr-1"></i>{{ $approval->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <span class="ml-4 px-2.5 py-1 text-xs font-medium rounded-full {{ $approval->status === 'approved' ? 'bg-green-100 text-green-800' : ($approval->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ ucfirst($approval->status) }}
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>No approval requests yet</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            
            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                
                <div class="space-y-2">
                    @if(auth()->user()->hasPermission('approvals.create'))
                    <a href="{{ route('approvals.create', ['client_id' => $client->id]) }}" class="block w-full px-4 py-2.5 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg text-center transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        New Approval Request
                    </a>
                    @endif
                    
                    @if(auth()->user()->hasPermission('clients.update'))
                    <a href="{{ route('clients.edit', $client) }}" class="block w-full px-4 py-2 border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg text-center transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Client
                    </a>
                    @endif
                </div>
            </div>

            <!-- Meta Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Information</h3>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase mb-1">Created</dt>
                        <dd class=" text-gray-900">{{ $client->created_at->format('M d, Y') }}</dd>
                        <dd class="text-xs text-gray-500">{{ $client->created_at->diffForHumans() }}</dd>
                    </div>
                    @if($client->creator)
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase mb-1">Created By</dt>
                        <dd class=" text-gray-900">{{ $client->creator->full_name ?? $client->creator->name }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase mb-1">Last Updated</dt>
                        <dd class=" text-gray-900">{{ $client->updated_at->format('M d, Y') }}</dd>
                        <dd class="text-xs text-gray-500">{{ $client->updated_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Internal Notes -->
            @if($client->notes)
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6">
                <h3 class=" font-medium text-amber-900 mb-2 flex items-center">
                    <i class="fas fa-sticky-note mr-2"></i>
                    Internal Notes
                </h3>
                <p class=" text-amber-800">{{ $client->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
