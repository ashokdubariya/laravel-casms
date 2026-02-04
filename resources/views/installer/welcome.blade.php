@extends('installer.layout')

@php
    $currentStep = 1;
@endphp

@section('content')
    <div class="text-center">
        <div class="mb-6">
            <i class="fas fa-check-circle fa-4x text-primary-blue"></i>
        </div>

        <h2 class="text-3xl font-medium text-dark-text mb-4">Welcome to Installation Wizard</h2>
        <p class="text-lg text-gray-600 mb-8">Thank you for choosing Client Approval & Sign-Off Management System</p>

        <div class="bg-light-bg border border-border-gray rounded-lg p-6 mb-8 text-left">
            <h3 class="text-lg font-medium text-dark-text mb-3">Before You Begin</h3>
            <ul class="space-y-2 text-gray-700">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>Ensure you have a MySQL database created</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>Have your database credentials ready (host, name, username, password)</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>PHP 8.2 or higher with required extensions</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>Write permissions for storage and bootstrap/cache directories</span>
                </li>
            </ul>
        </div>

        <div class="bg-light-bg border border-border-gray rounded-lg p-6 mb-8 text-left">
            <h3 class="text-lg font-medium text-dark-text mb-3">
                <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                Important Notes
            </h3>
            <ul class="space-y-2 text-gray-700">
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>The installation process will create database tables and configure your application</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>Make sure you have backup of any existing data</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>This wizard should only be run once during initial setup</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                    <span>Estimated completion time: 5-10 minutes</span>
                </li>
            </ul>
        </div>

        <a href="{{ route('installer.requirements') }}" class="inline-flex items-center px-8 py-3 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg shadow-lg transition duration-150">
            Get Started
            <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
@endsection
