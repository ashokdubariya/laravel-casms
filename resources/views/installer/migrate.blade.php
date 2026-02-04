@extends('installer.layout')

@php
    $currentStep = 4;
@endphp

@section('content')
    <h2 class="text-2xl font-medium text-gray-900 mb-6">Database Migration</h2>

    <div class="bg-light-bg border border-border-gray rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-dark-text mb-3">What will happen?</h3>
        <ul class="space-y-2 text-dark-text">
            <li class="flex items-start">
                <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                <span>Create all necessary database tables</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                <span>Set up relationships and constraints</span>
            </li>
            <li class="flex items-start">
                <i class="fas fa-check-circle text-approval-green mt-0.5 mr-2 flex-shrink-0"></i>
                <span>Optionally seed sample data for testing (recommended)</span>
            </li>
        </ul>
    </div>

    <div class="mb-6">
        <label class="flex items-center p-4 bg-gray-50 rounded-lg border-2 border-gray-200 cursor-pointer hover:bg-gray-100 transition">
            <input type="checkbox" id="seed_sample_data" class="h-5 w-5 text-primary-blue rounded focus:ring-2 focus:ring-primary-blue" checked>
            <div class="ml-3">
                <span class="block font-medium text-gray-900">Seed Sample Data</span>
                <span class="block  text-gray-600">Import sample data with complete history (recommended for testing)</span>
            </div>
        </label>
    </div>

    <!-- Progress Display -->
    <div id="migration-progress" class="hidden mb-6">
        <div class="bg-gray-100 rounded-lg p-6">
            <div class="flex items-center mb-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-blue mr-3"></div>
                <span class="text-gray-700 font-medium" id="migration-status">Preparing migration...</span>
            </div>
            <div class="w-full bg-gray-300 rounded-full h-2">
                <div id="progress-bar" class="bg-primary-blue h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <div id="migration-success" class="hidden bg-light-bg border border-approval-green rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle fa-3x text-approval-green mr-3"></i>
            <div>
                <h3 class="text-lg font-medium text-dark-text">Migration Completed Successfully!</h3>
                <p class="text-dark-text  mt-1">All database tables have been created and data has been seeded. Redirecting to admin setup...</p>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <div id="migration-error" class="hidden bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <div class="flex items-start">
            <i class="fas fa-times-circle fa-2x text-red-500 mr-3 mt-0.5"></i>
            <div>
                <h3 class="text-lg font-medium text-red-900">Migration Failed</h3>
                <p class="text-red-700  mt-1" id="error-message">An error occurred during migration.</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="flex justify-between items-center pt-6 border-t">
        <a href="{{ route('installer.database') }}" class="inline-flex items-center px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition" id="back-btn">
            <i class="fas fa-arrow-left mr-2"></i>
            Back
        </a>
        
        <button type="button" id="migrate-btn" class="inline-flex items-center px-6 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition">
            <i class="fas fa-database mr-2"></i>
            Run Migration
        </button>
    </div>
@endsection

@section('scripts')
<script>
    document.getElementById('migrate-btn').addEventListener('click', function() {
        const btn = this;
        const backBtn = document.getElementById('back-btn');
        const progressDiv = document.getElementById('migration-progress');
        const successDiv = document.getElementById('migration-success');
        const errorDiv = document.getElementById('migration-error');
        const continueBtn = document.getElementById('continue-btn');
        const statusText = document.getElementById('migration-status');
        const progressBar = document.getElementById('progress-bar');
        const seedSampleData = document.getElementById('seed_sample_data').checked;

        // Disable buttons
        btn.disabled = true;
        backBtn.style.opacity = '0.5';
        backBtn.style.pointerEvents = 'none';

        // Show progress
        progressDiv.classList.remove('hidden');
        successDiv.classList.add('hidden');
        errorDiv.classList.add('hidden');

        // Simulate progress
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += 5;
            if (progress <= 90) {
                progressBar.style.width = progress + '%';
            }
        }, 200);

        // Run migration
        fetch('{{ route('installer.migrate.process') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                seed_sample_data: seedSampleData
            })
        })
        .then(response => response.json())
        .then(data => {
            clearInterval(progressInterval);
            progressBar.style.width = '100%';

            setTimeout(() => {
                if (data.success) {
                    progressDiv.classList.add('hidden');
                    successDiv.classList.remove('hidden');
                    btn.classList.add('hidden');
                    
                    // Auto-redirect to admin setup after 2 seconds
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 500);
                    } else {
                        continueBtn.classList.remove('hidden');
                    }
                } else {
                    progressDiv.classList.add('hidden');
                    errorDiv.classList.remove('hidden');
                    document.getElementById('error-message').textContent = data.message;
                    btn.disabled = false;
                    backBtn.style.opacity = '1';
                    backBtn.style.pointerEvents = 'auto';
                }
            }, 500);
        })
        .catch(error => {
            clearInterval(progressInterval);
            progressDiv.classList.add('hidden');
            errorDiv.classList.remove('hidden');
            document.getElementById('error-message').textContent = error.message;
            btn.disabled = false;
            backBtn.style.opacity = '1';
            backBtn.style.pointerEvents = 'auto';
        });
    });
</script>
@endsection
