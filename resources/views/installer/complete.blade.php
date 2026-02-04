@extends('installer.layout')

@php
    $currentStep = 6;
@endphp

@section('content')
    <div class="text-center">
        <div class="mb-6">
            <i class="fas fa-check-circle fa-4x text-approval-green"></i>
        </div>

        <h2 class="text-3xl font-medium text-gray-900 mb-4">
            <i class="fas fa-trophy text-approval-green mr-2"></i>
            Installation Complete!
        </h2>
        <p class="text-lg text-gray-600 mb-8">Your Client Approval System is ready to use</p>

        <div class="bg-light-bg border border-approval-green rounded-lg p-6 mb-8 text-left">
            <h3 class="text-lg font-medium text-dark-text mb-3">
                <i class="fas fa-clipboard-list text-primary-blue mr-2"></i>
                What's been Setup
            </h3>
            <ul class="space-y-2 text-dark-text">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                    Database tables created successfully
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                    Roles & Permissions configured (Admin, Manager, User)
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                    Admin account created and activated
                </li>
                @if($sampleDataSeeded ?? false)
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                    Sample data seeded (3 users, 25 clients, 25 approvals)
                </li>
                @endif
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                    Configuration completed
                </li>
            </ul>
        </div>

        <div class="bg-light-bg border border-border-gray rounded-lg p-6 mb-8 text-left">
            <h3 class="text-lg font-medium text-dark-text mb-3">
                <i class="fas fa-clipboard-list text-primary-blue mr-2"></i>
                Next Steps
            </h3>
            <ol class="space-y-2 text-dark-text list-decimal list-inside">
                <li>Delete the <code class="bg-gray-100 px-2 py-1 rounded ">/installer</code> folder for security</li>
                <li>Configure your email settings in <code class="bg-gray-100 px-2 py-1 rounded ">.env</code> file</li>
                <li>Set up your cron job for scheduled tasks</li>
                <li>Review and customize the approval workflow settings</li>
                <li>Start creating approval requests!</li>
            </ol>
        </div>

        @if($sampleDataSeeded ?? false)
        <div class="bg-light-bg border border-border-gray rounded-lg p-4 mb-8 text-left">
            <h3 class=" font-medium text-dark-text mb-2">
                <i class="fas fa-key text-primary-blue mr-2"></i>
                Sample Credentials (for testing only)
            </h3>
            <div class="text-dark-text  space-y-1">
                <p><strong>Admin:</strong> admin@example.com / password123</p>
                <p><strong>Manager:</strong> manager@example.com / password123</p>
                <p><strong>User:</strong> user@example.com / password123</p>
                <p class="mt-2 text-xs">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-1"></i>
                    <strong>Change these passwords immediately in production!</strong>
                </p>
            </div>
        </div>
        @endif

        <div class="flex justify-center gap-4">
            <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-3 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg shadow-lg transition">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Login to Dashboard
            </a>
        </div>
    </div>
@endsection
