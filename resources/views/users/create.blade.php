@extends('layouts.dashboard')

@section('title', 'Create User')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-medium text-gray-900">Create New User</h1>
        </div>
        <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Personal Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-[#1a425f]/10 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user text-[#1a425f]"></i>
                </div>
                <h2 class="text-lg font-medium text-gray-900">Personal Information</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Avatar Upload -->
                <div class="md:col-span-2">
                    <label class="block font-medium text-gray-700 mb-3">
                        Profile Picture
                    </label>
                    <div class="flex items-start space-x-6">
                        <div class="flex-shrink-0">
                            <img id="avatar-preview" 
                                src="https://ui-avatars.com/api/?name=U&color=fff&background=1a425f&bold=true&size=200" 
                                alt="Avatar Preview" 
                                class="w-24 h-24 rounded-full object-cover border-2 border-border-gray">
                        </div>
                        <div class="flex-1 space-y-3">
                            <div>
                                <input type="file" name="avatar" id="avatar" 
                                    accept="image/jpeg,image/png,image/jpg"
                                    class="hidden"
                                    onchange="previewAvatar(event)">
                                <label for="avatar" 
                                    class="inline-flex items-center px-4 py-2 bg-white border border-border-gray rounded-lg font-medium text-gray-700 hover:bg-gray-50 cursor-pointer transition">
                                    <i class="fas fa-upload mr-2"></i>
                                    Choose Photo
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">JPG, JPEG or PNG. Max size 2MB.</p>
                            @error('avatar')
                                <p class=" text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label for="first_name" class="block font-medium text-gray-700 mb-1">
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">
                </div>

                <div>
                    <label for="last_name" class="block font-medium text-gray-700 mb-1">
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">
                </div>

                <div>
                    <label for="email" class="block font-medium text-gray-700 mb-1">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">
                </div>

                <div>
                    <label for="phone" class="block font-medium text-gray-700 mb-1">
                        Phone Number
                    </label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">
                </div>
            </div>
        </div>

        <!-- Account Settings -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-cog text-purple-600"></i>
                </div>
                <h2 class="text-lg font-medium text-gray-900">Account Settings</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block font-medium text-gray-700 mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">
                    <p class="mt-1 text-xs text-gray-500">Minimum 8 characters</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block font-medium text-gray-700 mb-1">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent ">
                </div>

                <div>
                    <label for="role_id" class="block font-medium text-gray-700 mb-1">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role_id" id="role_id" required
                        class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent">
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                            {{ $role->display_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block font-medium text-gray-700 mb-1">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" required
                        class="w-full px-2 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1a425f] focus:border-transparent">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-200/70 text-gray-700 font-medium rounded-lg transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition-colors shadow-sm">
                <i class="fas fa-save mr-2"></i>Create User
            </button>
        </div>
    </form>
</div>

<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 2048 * 1024) {
            alert('File size must be less than 2MB');
            event.target.value = '';
            return;
        }

        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Only JPG, JPEG and PNG files are allowed');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
