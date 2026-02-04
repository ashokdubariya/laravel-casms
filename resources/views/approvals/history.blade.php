@extends('layouts.dashboard')

@section('content')
@can('viewHistory', $approval)
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <a href="{{ route('approvals.show', $approval) }}" class="text-[#1a425f] hover:text-[#1a425f]/80  inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Approval
        </a>
        <h1 class="mt-2 text-3xl font-medium text-gray-900">Activity History</h1>
        <p class="mt-1 text-gray-600">Complete timeline for: <span class="font-medium">{{ $approval->title }}</span></p>
    </div>

    <!-- Timeline -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Timeline</h2>
        </div>

        <div class="p-6">
            <div class="space-y-6">
                @foreach($approval->history as $index => $event)
                    <div class="flex gap-4">
                        <!-- Timeline Line -->
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg flex-shrink-0"
                                 style="background-color: {{ $event->action_color }}20;">
                                {!! $event->action_icon !!}
                            </div>
                            @if(!$loop->last)
                                <div class="w-0.5 flex-1 bg-gray-200 mt-2" style="min-height: 40px;"></div>
                            @endif
                        </div>

                        <!-- Event Details -->
                        <div class="flex-1 pb-8">
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <!-- Action Header -->
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ ucfirst(str_replace('_', ' ', $event->action)) }}
                                    </h3>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium"
                                          style="background-color: {{ $event->action_color }}20; color: {{ $event->action_color }}">
                                        {{ ucfirst($event->action) }}
                                    </span>
                                </div>

                                <!-- Metadata -->
                                <div class="space-y-2 ">
                                    <div class="flex items-center gap-2 text-gray-600">
                                        <span class="font-medium">Performed by:</span>
                                        <span>{{ $event->performed_by }}</span>
                                    </div>

                                    <div class="flex items-center gap-2 text-gray-600">
                                        <span class="font-medium">Timestamp:</span>
                                        <span>{{ $event->created_at->format('F j, Y \a\t g:i A') }}</span>
                                        <span class="text-gray-400">({{ $event->created_at->diffForHumans() }})</span>
                                    </div>

                                    @if($event->version)
                                        <div class="flex items-center gap-2 text-gray-600">
                                            <span class="font-medium">Version:</span>
                                            <span class="px-2 py-0.5 bg-gray-200 rounded text-xs">{{ $event->version }}</span>
                                        </div>
                                    @endif

                                    @if($event->ip_address)
                                        <div class="flex items-center gap-2 text-gray-600">
                                            <span class="font-medium">IP Address:</span>
                                            <span class="font-mono text-xs">{{ $event->ip_address }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Comment -->
                                @if($event->comment)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <p class=" font-medium text-gray-700 mb-1">Comment:</p>
                                        <div class="bg-white p-3 rounded border border-gray-200">
                                            <p class=" text-gray-900 italic">"{{ $event->comment }}"</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Metadata JSON -->
                                @if($event->metadata && is_array($event->metadata) && count($event->metadata) > 0)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <details class="text-xs">
                                            <summary class="cursor-pointer text-gray-600 hover:text-gray-900">
                                                Additional Metadata
                                            </summary>
                                            <pre class="mt-2 bg-gray-900 text-green-400 p-2 rounded overflow-x-auto">{{ json_encode($event->metadata, JSON_PRETTY_PRINT) }}</pre>
                                        </details>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Summary Footer -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex justify-between items-center  text-gray-600">
                <span>Total Events: {{ $approval->history->count() }}</span>
                <span>Created: {{ $approval->created_at->format('F j, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Export Actions -->
    @can('downloadPdf', $approval)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="fas fa-download mr-2"></i>Export Options
        </h3>
        <div class="flex gap-3">
            <a href="{{ route('approvals.pdf', $approval) }}" 
               class="bg-[#1a425f] text-white px-4 py-2 rounded-lg hover:bg-[#1a425f]/90 transition inline-flex items-center">
                <i class="fas fa-file-pdf mr-2"></i>Download PDF Proof
            </a>
        </div>
    </div>
    @endcan
</div>
@else
<div class="max-w-2xl mx-auto mt-8">
    <div class="bg-red-50 border border-red-200 rounded-lg p-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
            <i class="fas fa-lock text-red-600 text-3xl"></i>
        </div>
        <h3 class="text-xl font-medium text-red-900 mb-2">Access Denied</h3>
        <p class="text-red-700 mb-6">You don't have permission to view this approval's history.</p>
        <a href="{{ route('approvals.index') }}" class="inline-flex items-center px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>Back to Approvals
        </a>
    </div>
</div>
@endcan
@endsection
