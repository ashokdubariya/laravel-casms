@extends('layouts.dashboard')

@section('content')
@can('view', $approval)
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <a href="{{ route('approvals.index') }}" class="text-[#1a425f] hover:text-[#1a425f]/80  inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Approvals
            </a>
            <h1 class="mt-2 text-3xl font-medium text-gray-900">{{ $approval->title }}</h1>
            <div class="mt-2 flex items-center gap-3">
                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                    {{ $approval->version }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                      style="background-color: {{ $approval->status_color }}20; color: {{ $approval->status_color }}">
                    {{ ucfirst($approval->status) }}
                </span>
                @if($approval->priority ?? false)
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                    {{ $approval->priority === 'high' ? 'bg-red-100 text-red-700' : 
                       ($approval->priority === 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}">
                    {{ ucfirst($approval->priority) }} Priority
                </span>
                @endif
            </div>
        </div>

        <div class="flex gap-2">
            @can('update', $approval)
            @if($approval->isPending())
                <a href="{{ route('approvals.edit', $approval) }}" 
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition ">
                    <i class="fas fa-edit mr-1"></i>Edit
                </a>
            @endif
            @endcan
            
            @can('downloadPdf', $approval)
            @if(!$approval->isPending())
                <a href="{{ route('approvals.pdf', $approval) }}" 
                   class="bg-[#1a425f] text-white px-4 py-2 rounded-lg hover:bg-[#1a425f]/90 transition ">
                    <i class="fas fa-file-pdf mr-1"></i>Download PDF
                </a>
            @endif
            @endcan
        </div>
    </div>

    <!-- Approval Details -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 space-y-4">
        <div>
            <h3 class=" font-medium text-gray-700">Description</h3>
            <p class="mt-1 text-gray-900">{{ $approval->description ?: 'No description provided.' }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
            <div>
                <h3 class=" font-medium text-gray-700">Client</h3>
                <p class="mt-1 text-gray-900 font-medium">
                    @if($approval->client)
                        {{ $approval->client->full_name }}
                        @if($approval->client->company_name)
                            <span class="text-gray-500 font-normal">- {{ $approval->client->company_name }}</span>
                        @endif
                    @else
                        {{ $approval->client_name }}
                    @endif
                </p>
                <p class="mt-0.5  text-gray-600">
                    @if($approval->client)
                        {{ $approval->client->email }}
                    @else
                        {{ $approval->client_email }}
                    @endif
                </p>
            </div>
            <div>
                <h3 class=" font-medium text-gray-700">Created By</h3>
                <p class="mt-1 text-gray-900">
                    @if($approval->creator)
                        {{ $approval->creator->full_name ?? $approval->creator->name }}
                    @else
                        N/A
                    @endif
                </p>
                <p class="mt-0.5  text-gray-600">{{ $approval->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
        </div>

        @if($approval->due_date ?? false)
        <div class="pt-4 border-t border-gray-200">
            <h3 class=" font-medium text-gray-700">Due Date</h3>
            <p class="mt-1 {{ $approval->due_date->isPast() && $approval->isPending() ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                {{ $approval->due_date->format('F j, Y') }}
                @if($approval->due_date->isPast() && $approval->isPending())
                    <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded ml-2">OVERDUE</span>
                @endif
            </p>
        </div>
        @endif

        @if($approval->message)
        <div class="pt-4 border-t border-gray-200">
            <h3 class=" font-medium text-gray-700">Message to Client</h3>
            <p class="mt-1 text-gray-900 bg-gray-50 p-3 rounded">{{ $approval->message }}</p>
        </div>
        @endif

        @if($approval->approved_at)
            <div class="pt-4 border-t border-gray-200">
                <h3 class=" font-medium text-green-700">Approved At</h3>
                <p class="mt-1 text-gray-900">{{ $approval->approved_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
        @endif

        @if($approval->rejected_at)
            <div class="pt-4 border-t border-gray-200">
                <h3 class=" font-medium text-red-700">Rejected At</h3>
                <p class="mt-1 text-gray-900">{{ $approval->rejected_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
        @endif

        @if($approval->internal_notes)
            <div class="pt-4 border-t border-gray-200 bg-yellow-50 -m-6 mt-4 p-6 rounded-b-lg">
                <h3 class=" font-medium text-gray-700 flex items-center">
                    <i class="fas fa-lock text-yellow-600 mr-2"></i>
                    Internal Notes (Team Only)
                </h3>
                <p class="mt-1 text-gray-900">{{ $approval->internal_notes }}</p>
            </div>
        @endif
    </div>

    <!-- Approval Link (if pending) -->
    @if($approval->isPending() && $approval->activeToken)
        <div class="bg-[#1a425f]/5 border border-[#1a425f]/20 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Client Approval Link</h3>
            <p class=" text-gray-600 mb-4">
                Share this secure link with your client. It expires {{ $approval->activeToken->expires_at->diffForHumans() }}.
            </p>
            
            <div class="flex gap-2">
                <input type="text" readonly value="{{ $approval->activeToken->approval_link }}" 
                       class="flex-1 w-full pl-2 border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-[#1a425f] ">
                <button onclick="navigator.clipboard.writeText('{{ $approval->activeToken->approval_link }}')" 
                        class="bg-[#1a425f] text-white px-4 py-2 rounded-lg hover:bg-[#1a425f]/90 transition font-medium">
                    <i class="fas fa-copy mr-1"></i>Copy Link
                </button>
            </div>

            <div class="mt-4 flex gap-2">
                @can('sendReminder', $approval)
                <form method="POST" action="{{ route('approvals.send-reminder', $approval) }}">
                    @csrf
                    <button type="submit" class="bg-[#1a425f] text-white px-4 py-2 rounded-lg hover:bg-[#1a425f]/90 transition font-medium">
                        <i class="fas fa-envelope mr-1"></i>Send Reminder Email
                    </button>
                </form>
                @endcan

                @can('regenerateToken', $approval)
                <a href="{{ route('approvals.regenerate-token', $approval) }}" 
                   class="bg-[#1a425f] text-white px-4 py-2 rounded-lg hover:bg-[#1a425f]/90 transition font-medium"
                   onclick="return confirm('This will invalidate the current link. Continue?')">
                    <i class="fas fa-sync-alt mr-1"></i>Generate New Link
                </a>
                @endcan
            </div>
        </div>
    @endif

    <!-- Attachments -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Attachments</h2>
            @if($approval->isPending())
                <button @click="$refs.attachmentForm.classList.toggle('hidden')" 
                        class=" text-[#1a425f] hover:text-[#1a425f]/80">
                    <i class="fas fa-plus mr-1"></i>Add Attachment
                </button>
            @endif
        </div>

        <!-- Add Attachment Form -->
        @if($approval->isPending())
            <div x-ref="attachmentForm" class="hidden px-6 py-4 bg-gray-50 border-b border-gray-200">
                <form method="POST" action="{{ route('approvals.attachments.store', $approval) }}" 
                      enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#1a425f] focus:ring-[#1a425f]">
                            <option value="image">Image</option>
                            <option value="document">Document</option>
                            <option value="url">URL</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-gray-700 mb-1">File or URL</label>
                        <input type="file" name="file" class="w-full">
                        <input type="url" name="url" placeholder="https://..." class="w-full mt-2 border-gray-300 rounded-lg shadow-sm focus:border-[#1a425f] focus:ring-[#1a425f]">
                    </div>

                    <button type="submit" class="bg-[#1a425f] text-white px-4 py-2 rounded-lg hover:bg-[#1a425f]/90 transition ">
                        <i class="fas fa-plus mr-1"></i>Add Attachment
                    </button>
                </form>
            </div>
        @endif

        <!-- Attachments List -->
        <div class="divide-y divide-gray-200">
            @forelse($approval->attachments as $attachment)
                <div class="px-6 py-4 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        @if($attachment->isImage())
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-image text-blue-600 text-lg"></i>
                            </div>
                        @elseif($attachment->isDocument())
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-alt text-green-600 text-lg"></i>
                            </div>
                        @else
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-link text-purple-600 text-lg"></i>
                            </div>
                        @endif

                        <div>
                            <a href="{{ config('app.url') }}{{ $attachment->file_url }}" target="_blank" 
                               class="text-[#1a425f] hover:text-[#1a425f]/80 font-medium">
                                {{ $attachment->file_name ?: $attachment->url }}
                            </a>
                            @if($attachment->human_file_size)
                                <p class="text-xs text-gray-500">{{ $attachment->human_file_size }}</p>
                            @endif
                        </div>
                    </div>

                    @if($approval->isPending())
                        <form method="POST" action="{{ route('approvals.attachments.destroy', [$approval, $attachment]) }}" 
                              onsubmit="return confirm('Delete this attachment?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 ">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-paperclip text-gray-300 text-3xl mb-2"></i>
                    <p>No attachments yet.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- History Timeline -->
    @can('viewHistory', $approval)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Activity History</h2>
            <a href="{{ route('approvals.history', $approval) }}" class=" text-[#1a425f] hover:text-[#1a425f]/80">
                <i class="fas fa-external-link-alt mr-1"></i>View Full History
            </a>
        </div>

        <div class="px-6 py-4 space-y-4">
            @foreach($approval->history->take(5) as $event)
                <div class="flex gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-lg"
                         style="background-color: {{ $event->action_color }}20;">
                        {!! $event->action_icon !!}
                    </div>
                    <div class="flex-1">
                        <p class=" font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $event->action)) }}</p>
                        <p class="text-xs text-gray-600">
                            by {{ $event->performed_by }} <i class="fas fa-circle text-gray-400" style="font-size: 4px;"></i> {{ $event->created_at->diffForHumans() }}
                        </p>
                        @if($event->comment)
                            <p class="mt-1  text-gray-700 bg-gray-50 p-2 rounded">{{ $event->comment }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
            
            @if($approval->history->count() > 5)
                <div class="pt-2 text-center">
                    <a href="{{ route('approvals.history', $approval) }}" class=" text-[#1a425f] hover:text-[#1a425f]/80">
                        View all {{ $approval->history->count() }} events →
                    </a>
                </div>
            @endif
        </div>
    </div>
    @endcan

    <!-- Danger Zone -->
    @can('delete', $approval)
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-red-900 mb-2">
                <i class="fas fa-exclamation-triangle mr-2"></i>Danger Zone
            </h3>
            <p class=" text-red-700 mb-4">
                Deleting this approval request will permanently remove all associated data including attachments and history.
            </p>
            
            <form method="POST" action="{{ route('approvals.destroy', $approval) }}" 
                  onsubmit="return confirm('Are you sure you want to delete \"{{ addslashes($approval->title) }}\"? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition ">
                    <i class="fas fa-trash mr-2"></i>Delete Approval Request
                </button>
            </form>
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
        <p class="text-red-700 mb-6">You don't have permission to view this approval request.</p>
        <a href="{{ route('approvals.index') }}" class="inline-flex items-center px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>Back to Approvals
        </a>
    </div>
</div>
@endcan
@endsection
