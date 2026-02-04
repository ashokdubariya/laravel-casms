@extends('layouts.dashboard')

@section('title', 'Approval Requests')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-medium text-[#0F172A]">Approval Requests</h1>
            <p class="mt-1  text-gray-500">Manage client approval requests</p>
        </div>
        @can('create', App\Models\ApprovalRequest::class)
        <a href="{{ route('approvals.create') }}" 
           class="inline-flex items-center px-4 py-2.5 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors shadow-sm">
            <i class="fas fa-plus mr-2"></i>
            New Request
        </a>
        @endcan
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" font-medium text-gray-600">Total Requests</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-[#1a425f]/10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-[#1a425f] text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" font-medium text-gray-600">Pending</p>
                    <p class="text-3xl font-bold text-amber-600 mt-2">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" font-medium text-gray-600">Approved</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['approved'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class=" font-medium text-gray-600">Rejected</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['rejected'] }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <form method="GET" action="{{ route('approvals.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-2">Client</label>
                <select name="client_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">
                    <option value="">All Clients</option>
                    @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->full_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-2">Priority</label>
                <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">
                    <option value="">All Priorities</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-2">&nbsp;</label>
                <button type="submit" class="px-6 py-2.5 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-filter mr-2"></i>
                    Apply Filters
                </button>
                @if(request()->hasAny(['status', 'client_id', 'priority', 'date_from', 'date_to']))
                <a href="{{ route('approvals.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Approvals List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="divide-y divide-gray-200">
            @forelse($approvals as $approval)
                <div class="px-6 py-5 hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                @can('view', $approval)
                                <a href="{{ route('approvals.show', $approval) }}" 
                                   class="text-lg font-medium text-[#1a425f] hover:text-[#1a425f]/80 transition-colors">
                                    {{ $approval->title }}
                                </a>
                                @else
                                <span class="text-lg font-medium text-gray-900">{{ $approval->title }}</span>
                                @endcan
                                
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ $approval->version }}
                                </span>

                                @if($approval->priority ?? false)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                                    {{ $approval->priority === 'high' ? 'bg-red-100 text-red-700' : 
                                       ($approval->priority === 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                                    {{ ucfirst($approval->priority) }} Priority
                                </span>
                                @endif
                            </div>
                            
                            <div class="flex items-center gap-4  text-gray-600 mb-2">
                                <span class="flex items-center">
                                    <i class="fas fa-user text-gray-400 mr-2"></i>
                                    @if($approval->client)
                                        <span class="font-medium">{{ $approval->client->full_name }}</span>
                                        @if($approval->client->company_name)
                                            <span class="text-gray-400 mx-1">·</span>
                                            <span>{{ $approval->client->company_name }}</span>
                                        @endif
                                    @else
                                        <span class="font-medium">{{ $approval->client_name }}</span>
                                        <span class="text-gray-400 mx-1">·</span>
                                        <span>{{ $approval->client_email }}</span>
                                    @endif
                                </span>
                            </div>
                            
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    Created {{ $approval->created_at->diffForHumans() }}
                                </span>
                                
                                @if($approval->creator)
                                <span class="flex items-center">
                                    <i class="fas fa-user-circle mr-1"></i>
                                    by {{ $approval->creator->full_name ?? $approval->creator->name }}
                                </span>
                                @endif
                                
                                @if($approval->attachments->isNotEmpty())
                                <span class="flex items-center">
                                    <i class="fas fa-paperclip mr-1"></i>
                                    {{ $approval->attachments->count() }} attachment(s)
                                </span>
                                @endif

                                @if($approval->due_date ?? false)
                                <span class="flex items-center {{ $approval->due_date->isPast() && $approval->isPending() ? 'text-red-600 font-medium' : '' }}">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Due {{ $approval->due_date->format('M d, Y') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="ml-6 flex flex-col items-end gap-3">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium"
                                  style="background-color: {{ $approval->status_color }}20; color: {{ $approval->status_color }}">
                                {{ ucfirst($approval->status) }}
                            </span>
                            
                            @if($approval->isPending() && $approval->activeToken)
                                <span class="text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-hourglass-half mr-1"></i>
                                    Expires {{ $approval->activeToken->expires_at->diffForHumans() }}
                                </span>
                            @endif
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center gap-2">
                                @can('view', $approval)
                                <a href="{{ route('approvals.show', $approval) }}" 
                                   class="text-[#1a425f] hover:text-[#1a425f]/80" 
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endcan
                                
                                @can('update', $approval)
                                @if($approval->isPending())
                                <a href="{{ route('approvals.edit', $approval) }}" 
                                   class="text-blue-600 hover:text-blue-800" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @endcan

                                @can('viewHistory', $approval)
                                <a href="{{ route('approvals.history', $approval) }}" 
                                   class="text-purple-600 hover:text-purple-800" 
                                   title="View History">
                                    <i class="fas fa-history"></i>
                                </a>
                                @endcan
                                
                                @can('delete', $approval)
                                <form action="{{ route('approvals.destroy', $approval) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete {{ addslashes($approval->title) }}? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-16 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                        <i class="fas fa-clipboard-check text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No approval requests found</h3>
                    <p class="text-gray-500 mb-6">Get started by creating your first approval request.</p>
                    @can('create', App\Models\ApprovalRequest::class)
                    <a href="{{ route('approvals.create') }}" 
                       class="inline-flex items-center px-4 py-2.5 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg shadow-sm">
                        <i class="fas fa-plus mr-2"></i>
                        Create Approval Request
                    </a>
                    @endcan
                </div>
            @endforelse
        </div>
        
        @if($approvals->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $approvals->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
