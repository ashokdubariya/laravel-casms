@extends('layouts.dashboard')

@section('title', $emailTemplate->name)

@section('header-actions')
    <div class="flex gap-2">
        <a href="{{ route('email-templates.index') }}" class="inline-flex items-center px-4 py-2 border border-[#CBD5E1] text-[#1a425f] hover:bg-[#F8FAFC] font-medium rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back
        </a>
        <a href="{{ route('email-templates.edit', $emailTemplate) }}" class="inline-flex items-center px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors">
            <i class="fas fa-edit mr-2"></i>
            Edit Template
        </a>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Template Details -->
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-2xl font-medium text-[#0F172A] mb-2">{{ $emailTemplate->name }}</h2>
                @if($emailTemplate->description)
                <p class="text-gray-600">{{ $emailTemplate->description }}</p>
                @endif
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full font-medium
                {{ $emailTemplate->status === 'active' ? 'bg-[#85c34e]/10 text-[#85c34e]' : 'bg-gray-100 text-gray-800' }}">
                {{ ucfirst($emailTemplate->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <div class=" text-gray-500 mb-1">Slug</div>
                <div class=" font-mono text-gray-900">{{ $emailTemplate->slug }}</div>
            </div>
            <div>
                <div class=" text-gray-500 mb-1">Type</div>
                <div class=" font-medium text-gray-900">{{ ucfirst($emailTemplate->type) }}</div>
            </div>
            <div>
                <div class=" text-gray-500 mb-1">Created</div>
                <div class=" text-gray-900">{{ $emailTemplate->created_at->format('M d, Y') }}</div>
            </div>
            <div>
                <div class=" text-gray-500 mb-1">Last Updated</div>
                <div class=" text-gray-900">{{ $emailTemplate->updated_at->format('M d, Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Subject -->
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
        <h3 class="text-lg font-medium text-[#0F172A] mb-3">Email Subject</h3>
        <div class="px-4 py-3 bg-gray-50 rounded-lg border border-gray-200">
            <p class="text-gray-900">{{ $emailTemplate->subject }}</p>
        </div>
    </div>

    <!-- HTML Preview -->
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-[#0F172A]">HTML Preview</h3>
            <button onclick="toggleRawHTML()" class=" text-[#1a425f] hover:underline font-medium">
                <i class="fas fa-code mr-1"></i>
                <span id="toggle-text">View Raw HTML</span>
            </button>
        </div>
        
        <div id="html-preview" class="border border-gray-200 rounded-lg p-6 bg-white">
            {!! $emailTemplate->body_html !!}
        </div>

        <div id="html-raw" class="hidden">
            <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto "><code>{{ $emailTemplate->body_html }}</code></pre>
        </div>
    </div>

    <!-- Plain Text Version -->
    @if($emailTemplate->body_text)
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
        <h3 class="text-lg font-medium text-[#0F172A] mb-3">Plain Text Version</h3>
        <pre class="bg-gray-50 border border-gray-200 rounded-lg p-4  text-gray-900 whitespace-pre-wrap font-mono">{{ $emailTemplate->body_text }}</pre>
    </div>
    @endif

    <!-- Variables -->
    @if($emailTemplate->variables && count($emailTemplate->variables) > 0)
    <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
        <h3 class="text-lg font-medium text-[#0F172A] mb-4">Available Variables</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach($emailTemplate->variables as $var)
            <div class="bg-blue-50 border border-blue-200 px-3 py-2 rounded">
                <code class="text-blue-700 font-medium ">@{{ $var }}</code>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Metadata -->
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
        <h4 class=" font-medium text-gray-700 mb-4">Template Information</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 ">
            @if($emailTemplate->creator)
            <div>
                <span class="text-gray-500">Created By:</span>
                <span class="text-gray-900 ml-2 font-medium">{{ $emailTemplate->creator->full_name }}</span>
            </div>
            @endif
            @if($emailTemplate->updater)
            <div>
                <span class="text-gray-500">Last Updated By:</span>
                <span class="text-gray-900 ml-2 font-medium">{{ $emailTemplate->updater->full_name }}</span>
            </div>
            @endif
            <div>
                <span class="text-gray-500">Created At:</span>
                <span class="text-gray-900 ml-2">{{ $emailTemplate->created_at->format('M d, Y H:i:s') }}</span>
            </div>
            <div>
                <span class="text-gray-500">Updated At:</span>
                <span class="text-gray-900 ml-2">{{ $emailTemplate->updated_at->format('M d, Y H:i:s') }}</span>
            </div>
        </div>
    </div>
</div>

<script>
function toggleRawHTML() {
    const preview = document.getElementById('html-preview');
    const raw = document.getElementById('html-raw');
    const toggleText = document.getElementById('toggle-text');
    
    if (preview.classList.contains('hidden')) {
        preview.classList.remove('hidden');
        raw.classList.add('hidden');
        toggleText.textContent = 'View Raw HTML';
    } else {
        preview.classList.add('hidden');
        raw.classList.remove('hidden');
        toggleText.textContent = 'View Preview';
    }
}
</script>
@endsection
