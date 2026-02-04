@extends('layouts.dashboard')

@section('title', 'Email Templates')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-medium text-[#0F172A]">Email Templates</h1>
            <p class="mt-1  text-gray-500">Manage system email templates</p>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates as $template)
        <div class="bg-white rounded-lg shadow-sm border border-[#CBD5E1] hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-lg font-medium text-[#0F172A] mb-1">{{ $template->name }}</h3>
                        <p class=" text-gray-500 line-clamp-2">{{ $template->description ?? 'No description' }}</p>
                    </div>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $template->status === 'active' ? 'bg-[#85c34e]/10 text-[#85c34e]' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($template->status) }}
                    </span>
                </div>

                <div class="space-y-3 mb-4">
                    <div class="flex items-center ">
                        <i class="fas fa-tag text-gray-400 w-5 mr-2"></i>
                        <span class="text-gray-700">{{ ucfirst($template->type) }}</span>
                    </div>
                    <div class="flex items-center ">
                        <i class="fas fa-envelope text-gray-400 w-5 mr-2"></i>
                        <span class="text-gray-700 truncate">{{ $template->subject }}</span>
                    </div>
                    <div class="flex items-center ">
                        <i class="fas fa-code text-gray-400 w-5 mr-2"></i>
                        <span class="text-gray-700">{{ $template->slug }}</span>
                    </div>
                </div>

                <div class="flex gap-2 pt-4 border-t border-gray-200">
                    <a href="{{ route('email-templates.show', $template) }}" 
                       class="flex-1 text-center px-3 py-2 border border-[#CBD5E1] text-[#1a425f] hover:bg-[#F8FAFC] font-medium rounded-lg transition-colors ">
                        <i class="fas fa-eye mr-1"></i>View
                    </a>
                    <a href="{{ route('email-templates.edit', $template) }}" 
                       class="flex-1 text-center px-3 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors ">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12 bg-white rounded-lg border border-[#CBD5E1]">
            <i class="fas fa-envelope text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-[#0F172A] mb-2">No email templates found</h3>
            <p class="text-gray-500 mb-4">Get started by creating your first email template.</p>
            <a href="{{ route('email-templates.create') }}" class="inline-flex items-center px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Create Template
            </a>
        </div>
        @endforelse
    </div>

    @if($templates->hasPages())
    <div class="bg-white px-4 py-3 border border-[#CBD5E1] rounded-lg">
        {{ $templates->links() }}
    </div>
    @endif
</div>
@endsection
