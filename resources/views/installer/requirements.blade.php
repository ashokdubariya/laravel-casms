@extends('installer.layout')

@php
    $currentStep = 2;
@endphp

@section('content')
    <h2 class="text-2xl font-medium text-gray-900 mb-6">Server Requirements Check</h2>

    <!-- PHP Requirements -->
    <div class="mb-8">
        <h3 class="text-lg font-medium text-gray-800 mb-4">PHP Requirements</h3>
        <div class="space-y-3">
            @foreach($requirements as $key => $requirement)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">{{ $requirement['name'] }}</span>
                    <div class="flex items-center">
                        @if($requirement['status'])
                            <i class="fas fa-check-circle fa-1x text-approval-green"></i>
                            <span class="ml-2 text-approval-green font-medium">Installed</span>
                        @else
                            <i class="fas fa-times-circle fa-1x text-red-500"></i>
                            <span class="ml-2 text-red-600 font-medium">{{ $requirement['required'] ? 'Required' : 'Optional' }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- File Permissions -->
    <div class="mb-8">
        <h3 class="text-lg font-medium text-gray-800 mb-4">File Permissions</h3>
        <div class="space-y-3">
            @foreach($permissions as $path => $writable)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700 font-mono ">{{ $path }}</span>
                    <div class="flex items-center">
                        @if($writable)
                            <i class="fas fa-check-circle fa-1x text-approval-green"></i>
                            <span class="ml-2 text-approval-green font-medium">Writable</span>
                        @else
                            <i class="fas fa-times-circle fa-1x text-red-500"></i>
                            <span class="ml-2 text-red-600 font-medium">Not Writable</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if(!$allPassed)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-red-800 font-medium">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Some requirements are not met. Please fix the issues above before proceeding.
            </p>
        </div>
    @endif

    <!-- Navigation -->
    <div class="flex justify-between items-center pt-6 border-t">
        <a href="{{ route('installer.welcome') }}" class="inline-flex items-center px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Back
        </a>
        
        @if($allPassed)
            <a href="{{ route('installer.database') }}" class="inline-flex items-center px-6 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition">
                Continue
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        @else
            <button disabled class="px-6 py-2 bg-gray-400 text-gray-200 font-medium rounded-lg cursor-not-allowed">
                Continue
            </button>
        @endif
    </div>
@endsection
