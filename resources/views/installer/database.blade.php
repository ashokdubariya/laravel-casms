@extends('installer.layout')

@php
    $currentStep = 3;
@endphp

@section('content')
    <h2 class="text-2xl font-medium text-gray-900 mb-6">Database Configuration</h2>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <ul class="list-disc list-inside text-red-800">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('installer.database.store') }}" method="POST">
        @csrf

        <div class="space-y-6">
            <div>
                <label for="db_host" class="block font-medium text-gray-700 mb-2">Database Host</label>
                <input type="text" name="db_host" id="db_host" value="{{ old('db_host', '127.0.0.1') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                    placeholder="127.0.0.1 or localhost" required>
                <p class="mt-1  text-gray-500">Usually "127.0.0.1" or "localhost"</p>
            </div>

            <div>
                <label for="db_port" class="block font-medium text-gray-700 mb-2">Database Port</label>
                <input type="number" name="db_port" id="db_port" value="{{ old('db_port', '3306') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                    placeholder="3306" required>
                <p class="mt-1  text-gray-500">Default MySQL port is 3306</p>
            </div>

            <div>
                <label for="db_name" class="block font-medium text-gray-700 mb-2">Database Name</label>
                <input type="text" name="db_name" id="db_name" value="{{ old('db_name', '') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                    placeholder="approval_system" required>
                <p class="mt-1  text-gray-500">The database must already exist</p>
            </div>

            <div>
                <label for="db_username" class="block font-medium text-gray-700 mb-2">Database Username</label>
                <input type="text" name="db_username" id="db_username" value="{{ old('db_username', '') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                    placeholder="root" required>
            </div>

            <div>
                <label for="db_password" class="block font-medium text-gray-700 mb-2">Database Password</label>
                <input type="password" name="db_password" id="db_password" value="{{ old('db_password', '') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                    placeholder="Enter password (leave blank if none)">
                <p class="mt-1  text-gray-500">Leave blank if no password is set</p>
            </div>
        </div>

        <div class="bg-light-bg border border-border-gray rounded-lg p-4 mt-6">
            <p class="text-dark-text ">
                <strong>Note:</strong> We will test the database connection before saving the configuration.
            </p>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between items-center pt-6 border-t mt-8">
            <a href="{{ route('installer.requirements') }}" class="inline-flex items-center px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </a>
            
            <button type="submit" class="inline-flex items-center px-6 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition">
                <i class="fas fa-database mr-2"></i>
                Test & Save
                <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>
    </form>
@endsection
