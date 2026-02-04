@extends('layouts.dashboard')

@section('title', 'Edit Email Template')

@section('header-actions')
    <a href="{{ route('email-templates.index') }}" class="inline-flex items-center px-4 py-2 border border-[#CBD5E1] text-[#1a425f] hover:bg-[#F8FAFC] font-medium rounded-lg transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Templates
    </a>
@endsection

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('email-templates.update', $emailTemplate) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
            <h3 class="text-lg font-medium text-[#0F172A] mb-6">Basic Information</h3>
            
            <div class="space-y-5">
                <div>
                    <label for="name" class="block font-medium text-[#0F172A] mb-2">
                        Template Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $emailTemplate->name) }}" required
                           class="w-full px-4 py-2.5 border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-[#1a425f]  @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block font-medium text-[#0F172A] mb-2">
                        Slug
                    </label>
                    <input type="text" id="slug" value="{{ $emailTemplate->slug }}" disabled
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50  text-gray-600">
                    <p class="mt-1 text-xs text-gray-500">Auto-generated from template name</p>
                </div>

                <div>
                    <label for="subject" class="block font-medium text-[#0F172A] mb-2">
                        Email Subject <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject', $emailTemplate->subject) }}" required
                           class="w-full px-4 py-2.5 border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-[#1a425f]  @error('subject') border-red-500 @enderror">
                    @error('subject')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">You can use variables like @{{name}}, @{{email}}, @{{approval_url}}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="type" class="block font-medium text-[#0F172A] mb-2">
                            Template Type <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type" required
                                class="w-full px-4 py-2.5 border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-[#1a425f] font-medium @error('type') border-red-500 @enderror">
                            <option value="">Select Type</option>
                            <option value="notification" {{ old('type', $emailTemplate->type) == 'notification' ? 'selected' : '' }}>Notification</option>
                            <option value="approval" {{ old('type', $emailTemplate->type) == 'approval' ? 'selected' : '' }}>Approval</option>
                            <option value="system" {{ old('type', $emailTemplate->type) == 'system' ? 'selected' : '' }}>System</option>
                        </select>
                        @error('type')
                            <p class="mt-1  text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block font-medium text-[#0F172A] mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                                class="w-full px-4 py-2.5 border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-[#1a425f] font-medium @error('status') border-red-500 @enderror">
                            <option value="active" {{ old('status', $emailTemplate->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $emailTemplate->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1  text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block font-medium text-[#0F172A] mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="2"
                              class="w-full px-4 py-2.5 border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-[#1a425f]  @error('description') border-red-500 @enderror">{{ old('description', $emailTemplate->description) }}</textarea>
                    @error('description')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Email Content -->
        <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] p-6">
            <h3 class="text-lg font-medium text-[#0F172A] mb-6">Email Content</h3>
            
            <div class="space-y-5">
                <div>
                    <label for="body_html" class="block font-medium text-[#0F172A] mb-2">
                        HTML Body <span class="text-red-500">*</span>
                    </label>
                    <textarea id="body_html" name="body_html" rows="12" required
                              class="w-full px-4 py-2.5 border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-[#1a425f] font-mono @error('body_html') border-red-500 @enderror">{{ old('body_html', $emailTemplate->body_html) }}</textarea>
                    @error('body_html')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">HTML content for the email. Use variables like @{{name}}, @{{email}}, @{{approval_url}}, etc.</p>
                </div>

                <div>
                    <label for="body_text" class="block font-medium text-[#0F172A] mb-2">
                        Plain Text Body
                    </label>
                    <textarea id="body_text" name="body_text" rows="8"
                              class="w-full px-4 py-2.5 border border-[#CBD5E1] rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-[#1a425f] font-mono @error('body_text') border-red-500 @enderror">{{ old('body_text', $emailTemplate->body_text) }}</textarea>
                    @error('body_text')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Plain text version (optional)</p>
                </div>
            </div>
        </div>

        <!-- Available Variables -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h4 class=" font-medium text-blue-900 mb-3">
                <i class="fas fa-info-circle mr-2"></i>Available Variables
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                <div class="bg-white px-3 py-2 rounded border border-blue-200">
                    <code class="text-blue-700 font-medium">@{{name}}</code>
                    <div class="text-gray-600 mt-0.5">User name</div>
                </div>
                <div class="bg-white px-3 py-2 rounded border border-blue-200">
                    <code class="text-blue-700 font-medium">@{{email}}</code>
                    <div class="text-gray-600 mt-0.5">User email</div>
                </div>
                <div class="bg-white px-3 py-2 rounded border border-blue-200">
                    <code class="text-blue-700 font-medium">@{{approval_url}}</code>
                    <div class="text-gray-600 mt-0.5">Approval link</div>
                </div>
                <div class="bg-white px-3 py-2 rounded border border-blue-200">
                    <code class="text-blue-700 font-medium">@{{app_name}}</code>
                    <div class="text-gray-600 mt-0.5">Application name</div>
                </div>
            </div>
        </div>

        <!-- Metadata -->
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
            <h4 class=" font-medium text-gray-700 mb-3">Template Information</h4>
            <div class="grid grid-cols-2 gap-4 ">
                <div>
                    <span class="text-gray-500">Created:</span>
                    <span class="text-gray-900 ml-2">{{ $emailTemplate->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-500">Last Updated:</span>
                    <span class="text-gray-900 ml-2">{{ $emailTemplate->updated_at->format('M d, Y H:i') }}</span>
                </div>
                @if($emailTemplate->creator)
                <div>
                    <span class="text-gray-500">Created By:</span>
                    <span class="text-gray-900 ml-2">{{ $emailTemplate->creator->full_name }}</span>
                </div>
                @endif
                @if($emailTemplate->updater)
                <div>
                    <span class="text-gray-500">Updated By:</span>
                    <span class="text-gray-900 ml-2">{{ $emailTemplate->updater->full_name }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('email-templates.index') }}" 
               class="px-5 py-2.5 border border-[#CBD5E1] text-gray-700 hover:bg-[#F8FAFC] font-medium rounded-lg transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-5 py-2.5 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i>
                Update Template
            </button>
        </div>
    </form>
</div>
@endsection
