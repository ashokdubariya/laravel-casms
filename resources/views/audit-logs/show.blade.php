@extends('layouts.dashboard')

@section('title', 'Audit Log Details')

@section('header-actions')
    <a href="{{ route('audit-logs.index') }}" class="inline-flex items-center px-4 py-2 border border-[#CBD5E1] text-[#1a425f] hover:bg-[#F8FAFC] font-medium rounded-lg transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Audit Logs
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Info -->
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <div class=" text-gray-500 mb-1">Timestamp</div>
                <div class="text-base font-medium text-[#0F172A]">
                    {{ $auditLog->created_at->format('M d, Y H:i:s') }}
                    <div class="text-xs text-gray-500 mt-0.5">{{ $auditLog->created_at->diffForHumans() }}</div>
                </div>
            </div>
            <div>
                <div class=" text-gray-500 mb-1">User</div>
                <div class="text-base font-medium text-[#0F172A]">
                    @if($auditLog->user)
                        {{ $auditLog->user->full_name }}
                        <div class="text-xs text-gray-500 mt-0.5">{{ $auditLog->user_email }}</div>
                    @else
                        {{ $auditLog->user_name ?? 'System' }}
                        @if($auditLog->user_email)
                            <div class="text-xs text-gray-500 mt-0.5">{{ $auditLog->user_email }}</div>
                        @endif
                    @endif
                </div>
            </div>
            <div>
                <div class=" text-gray-500 mb-1">Module</div>
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($auditLog->module) }}
                    </span>
                </div>
            </div>
            <div>
                <div class=" text-gray-500 mb-1">Action</div>
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full font-medium" 
                          style="background-color: {{ $auditLog->color }}20; color: {{ $auditLog->color }};">
                        <i class="{{ $auditLog->icon }} mr-1.5"></i>
                        {{ ucfirst($auditLog->action) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
        <h3 class="text-lg font-medium text-[#0F172A] mb-3">Description</h3>
        <p class="text-gray-700">{{ $auditLog->description }}</p>
    </div>

    <!-- Record Details -->
    @if($auditLog->record_type && $auditLog->record_id)
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
        <h3 class="text-lg font-medium text-[#0F172A] mb-3">Record Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class=" text-gray-500">Record Type</div>
                <div class="text-base font-medium text-gray-900 mt-1">{{ class_basename($auditLog->record_type) }}</div>
            </div>
            <div>
                <div class=" text-gray-500">Record ID</div>
                <div class="text-base font-medium text-gray-900 mt-1">#{{ $auditLog->record_id }}</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Changes Comparison -->
    @if($auditLog->old_values || $auditLog->new_values)
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
        <h3 class="text-lg font-medium text-[#0F172A] mb-4">Changes</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#F8FAFC]">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#0F172A] uppercase tracking-wider">Field</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#0F172A] uppercase tracking-wider">Old Value</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-[#0F172A] uppercase tracking-wider">New Value</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $oldValues = is_array($auditLog->old_values) ? $auditLog->old_values : ($auditLog->old_values ? json_decode($auditLog->old_values, true) : []);
                        $newValues = is_array($auditLog->new_values) ? $auditLog->new_values : ($auditLog->new_values ? json_decode($auditLog->new_values, true) : []);
                        $allKeys = array_unique(array_merge(array_keys($oldValues ?: []), array_keys($newValues ?: [])));
                    @endphp
                    
                    @forelse($allKeys as $key)
                    <tr>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                        <td class="px-4 py-3  text-gray-600">
                            @if(isset($oldValues[$key]))
                                <span class="px-2 py-1 bg-red-50 text-red-700 rounded">
                                    {{ is_array($oldValues[$key]) ? json_encode($oldValues[$key]) : $oldValues[$key] }}
                                </span>
                            @else
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3  text-gray-600">
                            @if(isset($newValues[$key]))
                                <span class="px-2 py-1 bg-green-50 text-green-700 rounded">
                                    {{ is_array($newValues[$key]) ? json_encode($newValues[$key]) : $newValues[$key] }}
                                </span>
                            @else
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-6  text-gray-500 text-center">No changes recorded</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Technical Details -->
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
        <h3 class="text-lg font-medium text-[#0F172A] mb-4">Technical Details</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class=" text-gray-500 mb-1">IP Address</div>
                <div class=" font-mono text-gray-900">{{ $auditLog->ip_address }}</div>
            </div>
            <div>
                <div class=" text-gray-500 mb-1">User Agent</div>
                <div class=" font-mono text-gray-700 truncate" title="{{ $auditLog->user_agent }}">
                    {{ $auditLog->user_agent }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
