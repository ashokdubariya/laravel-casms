@extends('layouts.dashboard')

@section('title', 'Create Approval Request')

@section('content')
@can('create', App\Models\ApprovalRequest::class)
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-medium text-gray-900">Create Approval Request</h1>
        </div>
        <a href="{{ route('approvals.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('approvals.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-[#1a425f]/10 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-info-circle text-[#1a425f]"></i>
                </div>
                <h2 class="text-lg font-medium text-gray-900">Basic Information</h2>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="title" class="block font-medium text-gray-700 mb-1">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                        placeholder="e.g., Homepage Design Approval"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">
                    @error('title')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="4"
                        placeholder="Provide details about what needs approval..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent ">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="version" class="block font-medium text-gray-700 mb-1">
                        Version
                    </label>
                    <input type="text" name="version" id="version" value="{{ old('version', 'v1') }}"
                        placeholder="e.g., v1, v2, v2.1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">
                    <p class="mt-1 text-xs text-gray-500">Format: v1, v2, v2.1, etc.</p>
                    @error('version')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Client & Priority -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-tie text-purple-600"></i>
                </div>
                <h2 class="text-lg font-medium text-gray-900">Client & Priority</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label for="client_id" class="block font-medium text-gray-700 mb-1">
                        Select Client <span class="text-red-500">*</span>
                    </label>
                    <select name="client_id" id="client_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent ">
                        <option value="">Choose a client...</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->full_name }}
                            @if($client->company_name)
                                - {{ $client->company_name }}
                            @endif
                            ({{ $client->email }})
                        </option>
                        @endforeach
                    </select>
                    @error('client_id')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        Don't see your client? 
                        <a href="{{ route('clients.create') }}" class="text-[#1a425f] hover:text-[#1a425f]/80" target="_blank">
                            <i class="fas fa-plus mr-1"></i>Add New Client
                        </a>
                    </p>
                </div>

                <div>
                    <label for="priority" class="block font-medium text-gray-700 mb-1">
                        Priority <span class="text-red-500">*</span>
                    </label>
                    <select name="priority" id="priority" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent ">
                        <option value="low" {{ old('priority', 'medium') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    </select>
                    @error('priority')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Due Date & Message -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-calendar-alt text-green-600"></i>
                </div>
                <h2 class="text-lg font-medium text-gray-900">Timeline & Message</h2>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="due_date" class="block font-medium text-gray-700 mb-1">
                        Due Date
                    </label>
                    <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">
                    <p class="mt-1 text-xs text-gray-500">Expected approval date</p>
                    @error('due_date')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block font-medium text-gray-700 mb-1">
                        Message to Client
                    </label>
                    <textarea name="message" id="message" rows="3"
                        placeholder="Optional message that will be included in the approval email..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">{{ old('message') }}</textarea>
                    @error('message')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- File Attachments -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-paperclip text-amber-600"></i>
                </div>
                <h2 class="text-lg font-medium text-gray-900">Attachments</h2>
            </div>

            <div>
                <label class="block font-medium text-gray-700 mb-2">
                    Upload Files (Images, Documents, etc.)
                </label>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-[#1a425f] transition-colors">
                    <input type="file" name="attachment_files[]" id="attachment_files" multiple 
                        accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip"
                        class="hidden" onchange="updateFileList(this)">
                    
                    <label for="attachment_files" class="cursor-pointer">
                        <div class="space-y-2">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-5xl"></i>
                            <div class=" text-gray-600">
                                <span class="text-[#1a425f] hover:text-[#1a425f]/80 font-medium">Click to upload</span>
                                or drag and drop
                            </div>
                            <p class="text-xs text-gray-500">
                                Images, PDF, DOC, XLS, PPT (Max 10MB per file)
                            </p>
                        </div>
                    </label>
                </div>

                <!-- File List -->
                <div id="file-list" class="mt-4 space-y-2 hidden"></div>

                @error('attachment_files')
                <p class="mt-2  text-red-600">{{ $message }}</p>
                @enderror
                @error('attachment_files.*')
                <p class="mt-2  text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Internal Notes -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-sticky-note text-gray-600"></i>
                </div>
                <h2 class="text-lg font-medium text-gray-900">Internal Notes</h2>
            </div>

            <div>
                <label for="internal_notes" class="block font-medium text-gray-700 mb-1">
                    Team Notes (Not visible to client)
                </label>
                <textarea name="internal_notes" id="internal_notes" rows="3"
                    placeholder="Internal notes for your team..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">{{ old('internal_notes') }}</textarea>
                <p class="mt-1 text-xs text-gray-500">These notes will never be shown to the client</p>
                @error('internal_notes')
                <p class="mt-1  text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('approvals.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-paper-plane mr-2"></i>Create Approval Request
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function updateFileList(input) {
    const fileList = document.getElementById('file-list');
    const files = Array.from(input.files);
    
    if (files.length === 0) {
        fileList.classList.add('hidden');
        return;
    }
    
    fileList.classList.remove('hidden');
    fileList.innerHTML = '';
    
    files.forEach((file, index) => {
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200';
        fileItem.innerHTML = `
            <div class="flex items-center flex-1 min-w-0">
                <div class="w-10 h-10 bg-[#1a425f]/10 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <i class="fas fa-file text-[#1a425f]"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class=" font-medium text-gray-900 truncate">${file.name}</p>
                    <p class="text-xs text-gray-500">${fileSize} MB</p>
                </div>
            </div>
            <button type="button" onclick="removeFile(${index})" class="ml-3 text-red-600 hover:text-red-800">
                <i class="fas fa-times"></i>
            </button>
        `;
        fileList.appendChild(fileItem);
    });
}

function removeFile(index) {
    const input = document.getElementById('attachment_files');
    const dt = new DataTransfer();
    const files = Array.from(input.files);
    
    files.forEach((file, i) => {
        if (i !== index) {
            dt.items.add(file);
        }
    });
    
    input.files = dt.files;
    updateFileList(input);
}

// Drag and drop support
const dropZone = document.querySelector('.border-dashed');

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('border-[#1a425f]', 'bg-[#1a425f]/5');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('border-[#1a425f]', 'bg-[#1a425f]/5');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('border-[#1a425f]', 'bg-[#1a425f]/5');
    
    const input = document.getElementById('attachment_files');
    input.files = e.dataTransfer.files;
    updateFileList(input);
});
</script>
@endpush
@else
<div class="max-w-2xl mx-auto mt-8">
    <div class="bg-red-50 border border-red-200 rounded-lg p-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
            <i class="fas fa-lock text-red-600 text-3xl"></i>
        </div>
        <h3 class="text-xl font-medium text-red-900 mb-2">Access Denied</h3>
        <p class="text-red-700 mb-6">You don't have permission to create approval requests.</p>
        <a href="{{ route('approvals.index') }}" class="inline-flex items-center px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white rounded-lg">
            <i class="fas fa-arrow-left mr-2"></i>Back to Approvals
        </a>
    </div>
</div>
@endcan
@endsection
