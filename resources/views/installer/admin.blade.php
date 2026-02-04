@extends('installer.layout')

@php
    $currentStep = 5;
@endphp

@section('content')
    <h2 class="text-2xl font-medium text-gray-900 mb-6">Create Admin Account</h2>

    <div class="bg-light-bg border border-border-gray rounded-lg p-4 mb-6">
        <p class="text-dark-text ">
            <strong>Important:</strong> This will be your main administrator account. Keep these credentials safe!
        </p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <ul class="list-disc list-inside text-red-800">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('installer.admin.store') }}" method="POST">
        @csrf

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block font-medium text-gray-700 mb-2">First Name</label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                        placeholder="John" required autofocus>
                </div>

                <div>
                    <label for="last_name" class="block font-medium text-gray-700 mb-2">Last Name</label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                        placeholder="Doe" required>
                </div>
            </div>

            <div>
                <label for="email" class="block font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                    placeholder="admin@example.com" required>
                <p class="mt-1  text-gray-500">You'll use this email to log in</p>
            </div>

            <div>
                <label for="password" class="block font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password" id="password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                    placeholder="Enter a strong password" required minlength="8">
                <p class="mt-1  text-gray-500">Minimum 8 characters</p>
            </div>

            <div>
                <label for="password_confirmation" class="block font-medium text-gray-700 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent"
                    placeholder="Confirm your password" required minlength="8">
            </div>
        </div>

        <div class="bg-light-bg border border-border-gray rounded-lg p-4 mt-6">
            <h3 class=" font-medium text-dark-text mb-2">Account Details:</h3>
            <ul class="text-dark-text  space-y-1">
                <li class="flex items-start">
                    <i class="fas fa-circle text-primary-blue mr-2" style="font-size: 6px; margin-top: 8px;"></i>
                    <span>Role: <strong>Administrator</strong></span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-circle text-primary-blue mr-2" style="font-size: 6px; margin-top: 8px;"></i>
                    <span>Access: Full system access</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-circle text-primary-blue mr-2" style="font-size: 6px; margin-top: 8px;"></i>
                    <span>Status: Active & Email Verified</span>
                </li>
            </ul>
        </div>

        <!-- Navigation -->
        <div class="flex justify-between items-center pt-6 border-t mt-8">
            <a href="{{ route('installer.migrate') }}" class="inline-flex items-center px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Back
            </a>
            
            <button type="submit" class="inline-flex items-center px-6 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition">
                <i class="fas fa-user-plus mr-2"></i>
                Create Account
                <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>
    </form>
@endsection
