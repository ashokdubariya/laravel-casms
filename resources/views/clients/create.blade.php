@extends('layouts.dashboard')

@section('title', 'Create Client')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-medium text-[#0F172A]">Create New Client</h1>
            <p class="mt-1  text-gray-500">Add a new client to the system</p>
        </div>
        <a href="{{ route('clients.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-200/70 text-gray-700 font-medium rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Clients
        </a>
    </div>

    <form action="{{ route('clients.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Personal Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-user text-[#1a425f] mr-2"></i>
                Personal Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block font-medium text-gray-700 mb-2">
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required placeholder="John"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent @error('first_name') border-red-500 @enderror">
                    @error('first_name')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block font-medium text-gray-700 mb-2">
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required placeholder="Doe"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent @error('last_name') border-red-500 @enderror">
                    @error('last_name')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="john.doe@example.com"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block font-medium text-gray-700 mb-2">
                        Phone
                    </label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+1 (555) 123-4567"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Company Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-building text-[#1a425f] mr-2"></i>
                Company Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="company_name" class="block font-medium text-gray-700 mb-2">
                        Company Name
                    </label>
                    <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent @error('company_name') border-red-500 @enderror">
                    @error('company_name')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="website" class="block font-medium text-gray-700 mb-2">
                        Website
                    </label>
                    <input type="url" name="website" id="website" value="{{ old('website') }}" placeholder="https://example.com"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent @error('website') border-red-500 @enderror">
                    @error('website')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-map-marker-alt text-[#1a425f] mr-2"></i>
                Address (Optional)
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="address" class="block font-medium text-gray-700 mb-2">
                        Street Address
                    </label>
                    <textarea name="address" id="address" rows="2" placeholder="123 Main Street, Suite 100"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="city" class="block font-medium text-gray-700 mb-2">
                            City
                        </label>
                        <input type="text" name="city" id="city" value="{{ old('city') }}" placeholder="New York"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent @error('city') border-red-500 @enderror">
                        @error('city')
                            <p class="mt-1  text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="state" class="block font-medium text-gray-700 mb-2">
                            State / Province
                        </label>
                        <input type="text" name="state" id="state" value="{{ old('state') }}" placeholder="NY"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent @error('state') border-red-500 @enderror">
                        @error('state')
                            <p class="mt-1  text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="country" class="block font-medium text-gray-700 mb-2">
                            Country
                        </label>
                        <input type="text" name="country" id="country" value="{{ old('country') }}" placeholder="United States"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent @error('country') border-red-500 @enderror">
                        @error('country')
                            <p class="mt-1  text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="postal_code" class="block font-medium text-gray-700 mb-2">
                            Postal Code
                        </label>
                        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code') }}" placeholder="10001"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent @error('postal_code') border-red-500 @enderror">
                        @error('postal_code')
                            <p class="mt-1  text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & Notes -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                <i class="fas fa-info-circle text-[#1a425f] mr-2"></i>
                Status & Notes
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="status" class="block font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block font-medium text-gray-700 mb-2">
                        Internal Notes
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent @error('notes') border-red-500 @enderror"
                              placeholder="Add any internal notes about this client...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 pt-2">
            <a href="{{ route('clients.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-200/70 text-gray-700 font-medium rounded-lg transition-colors">
                <i class="fas fa-times mr-2"></i>
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-save mr-2"></i>
                Create Client
            </button>
        </div>
    </form>
</div>
@endsection
