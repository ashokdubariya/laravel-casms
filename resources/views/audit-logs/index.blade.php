@extends('layouts.dashboard')

@section('title', 'Audit Logs')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-medium text-[#0F172A]">Audit Logs</h1>
            <p class="mt-1  text-gray-500">System activity and security audit trail</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block font-medium text-[#0F172A] mb-2">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        placeholder="Search description, user..." 
                        class="w-full rounded-lg border-[#CBD5E1] focus:border-[#1a425f] focus:ring-[#1a425f]  py-2.5">
                </div>

                <!-- Module Filter -->
                <div>
                    <label for="module" class="block font-medium text-[#0F172A] mb-2">Module</label>
                    <select name="module" id="module" class="w-full rounded-lg border-[#CBD5E1] focus:border-[#1a425f] focus:ring-[#1a425f]  py-2.5 font-medium">
                        <option value="">All Modules</option>
                        @foreach($modules as $module)
                        <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>
                            {{ ucfirst($module) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Action Filter -->
                <div>
                    <label for="action" class="block font-medium text-[#0F172A] mb-2">Action</label>
                    <select name="action" id="action" class="w-full rounded-lg border-[#CBD5E1] focus:border-[#1a425f] focus:ring-[#1a425f]  py-2.5 font-medium">
                        <option value="">All Actions</option>
                        @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label for="date_from" class="block font-medium text-[#0F172A] mb-2">Date From</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                        class="w-full rounded-lg border-[#CBD5E1] focus:border-[#1a425f] focus:ring-[#1a425f]  py-2.5">
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="block font-medium text-[#0F172A] mb-2">Date To</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                        class="w-full rounded-lg border-[#CBD5E1] focus:border-[#1a425f] focus:ring-[#1a425f]  py-2.5">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors shadow-sm">
                    <i class="fas fa-filter mr-2"></i>Apply Filters
                </button>
                <a href="{{ route('audit-logs.index') }}" class="px-5 py-2.5 border border-[#CBD5E1] text-gray-600 hover:bg-[#F8FAFC] font-medium rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] overflow-hidden">
        @if($logs->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#F8FAFC]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#0F172A] uppercase tracking-wider">
                            Timestamp
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#0F172A] uppercase tracking-wider">
                            User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#0F172A] uppercase tracking-wider">
                            Module
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#0F172A] uppercase tracking-wider">
                            Action
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#0F172A] uppercase tracking-wider">
                            Description
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#0F172A] uppercase tracking-wider">
                            IP Address
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-[#0F172A] uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($logs as $log)
                    <tr class="hover:bg-[#F8FAFC] transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap  text-gray-900">
                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                            <div class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($log->user)
                            <div class=" font-medium text-[#0F172A]">{{ $log->user->full_name }}</div>
                            <div class="text-xs text-gray-500">{{ $log->user->email }}</div>
                            @else
                            <div class=" text-gray-500">{{ $log->user_name ?? 'System' }}</div>
                            <div class="text-xs text-gray-400">{{ $log->user_email ?? 'N/A' }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#1a425f]/10 text-[#1a425f]">
                                {{ ucfirst(str_replace('_', ' ', $log->module)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($log->color === 'green') bg-[#85c34e]/10 text-[#85c34e]
                                @elseif($log->color === 'red') bg-red-100 text-red-800
                                @elseif($log->color === 'blue') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                <i class="fas {{ $log->icon }} mr-1"></i>
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class=" text-[#0F172A]">{{ $log->description }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap  text-gray-500">
                            {{ $log->ip_address ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                            <a href="{{ route('audit-logs.show', $log) }}" class="text-[#1a425f] hover:text-[#1a425f]/80" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-[#CBD5E1] sm:px-6">
            {{ $logs->links() }}
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <i class="fas fa-history text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-[#0F172A] mb-2">No audit logs found</h3>
            <p class="text-gray-500">Activity logs will appear here as users interact with the system.</p>
        </div>
        @endif
    </div>
</div>
@endsection
