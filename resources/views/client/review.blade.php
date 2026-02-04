@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-lightbg py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-medium text-dark">Approval Request</h1>
            <p class="mt-2 text-gray-600">Please review and respond to this approval request</p>
        </div>

        <!-- Approval Details Card -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8 mb-6">
            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <h2 class="text-2xl font-medium text-dark">{{ $approval->title }}</h2>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $approval->version }}
                        </span>
                    </div>
                </div>

                <!-- Description -->
                @if($approval->description)
                    <div>
                        <h3 class=" font-medium text-gray-700 mb-1">Description</h3>
                        <p class="text-gray-900 leading-relaxed">{{ $approval->description }}</p>
                    </div>
                @endif

                <!-- Attachments -->
                @if($approval->attachments->isNotEmpty())
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-dark mb-4">Attachments</h3>
                        <div class="space-y-3">
                            @foreach($approval->attachments as $attachment)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
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

                                    <a href="{{ config('app.url') }}{{ $attachment->file_url }}" target="_blank" 
                                       class="flex-1 text-primary hover:underline font-medium">
                                        {{ $attachment->file_name ?: $attachment->url }}
                                    </a>

                                    @if($attachment->human_file_size)
                                        <span class="text-xs text-gray-500">{{ $attachment->human_file_size }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Client Information -->
                <div class="pt-6 border-t border-gray-200 bg-blue-50 -m-8 mt-6 p-8 rounded-b-lg">
                    <h3 class=" font-medium text-gray-700 mb-2">This request is for:</h3>
                    <p class="text-lg font-medium text-dark">{{ $approval->client_name }}</p>
                    <p class=" text-gray-600">{{ $approval->client_email }}</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-dark mb-4">Your Response</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Approve -->
                <div>
                    <form method="POST" action="{{ route('approval.approve', $token->token) }}" 
                          onsubmit="return confirm('Are you sure you want to approve this request?')">
                        @csrf
                        <button type="submit" 
                                class="bg-[#1a425f] hover:bg-[#1a425f]/90 w-full bg-approval text-white px-4 py-2 rounded-lg transition font-medium text-lg shadow-md">
                            <i class="fas fa-check-circle mr-2"></i>
                            Approve
                        </button>
                    </form>
                    <p class="mt-2  text-gray-600 text-center">
                        I approve this request as submitted
                    </p>
                </div>

                <!-- Reject -->
                <div x-data="{ showReject: false }">
                    <button @click="showReject = true" 
                            class="w-full bg-red-600 text-white px-2 py-2 rounded-lg hover:bg-red-700 transition font-medium text-lg shadow-md">
                        <i class="fas fa-times-circle mr-2"></i>
                        Request Changes
                    </button>
                    <p class="mt-2  text-gray-600 text-center">
                        I need changes before approval
                    </p>

                    <!-- Reject Modal -->
                    <div x-show="showReject" x-cloak
                         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                        <div @click.away="showReject = false" 
                             class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                            <h3 class="text-lg font-medium text-dark mb-4">Request Changes</h3>
                            
                            <form method="POST" action="{{ route('approval.reject', $token->token) }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="block font-medium text-gray-700 mb-2">
                                        Please explain what changes you need:
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="comment" required rows="5" 
                                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-[#1a425f] focus:ring-2 focus:ring-[#1a425f] focus:ring-opacity-20"
                                              placeholder="Please be specific about what needs to change..."></textarea>
                                    <p class="mt-1 text-xs text-gray-500">Minimum 10 characters</p>
                                </div>

                                <div class="flex gap-3">
                                    <button type="submit" 
                                            class="flex-1 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white px-4 py-2 rounded-lg transition">
                                        Submit Feedback
                                    </button>
                                    <button type="button" @click="showReject = false"
                                            class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-lock text-gray-400 mr-1"></i>
                    This is a secure one-time approval link. 
                    It will expire {{ $token->expires_at->diffForHumans() }}.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center  text-gray-500">
            <p>Powered by {{ config('app.name') }}</p>
        </div>
    </div>
</div>
@endsection
