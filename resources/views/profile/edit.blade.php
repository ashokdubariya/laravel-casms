@extends('layouts.dashboard')

@section('title', 'Profile Settings')

@section('content')
<div class="space-y-6">
    <!-- Success Messages -->
    @if(session('status') === 'profile-updated')
    <div class="bg-approval-green bg-opacity-10 border border-approval-green rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-check-circle text-approval-green text-lg mr-3 mt-0.5"></i>
            <div>
                <p class=" font-medium text-dark-text">Profile Updated Successfully</p>
                <p class="text-xs text-gray-600 mt-1">Your changes have been saved.</p>
            </div>
        </div>
    </div>
    @endif

    @if(session('status') === 'avatar-deleted')
    <div class="bg-approval-green bg-opacity-10 border border-approval-green rounded-lg p-4">
        <div class="flex items-start">
            <i class="fas fa-check-circle text-approval-green text-lg mr-3 mt-0.5"></i>
            <div>
                <p class=" font-medium text-dark-text">Avatar Deleted Successfully</p>
                <p class="text-xs text-gray-600 mt-1">Your avatar has been removed.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Profile Information -->
    <div class="bg-white rounded-lg shadow-sm border border-border-gray">
        <div class="px-6 py-4 border-b border-border-gray">
            <h2 class="text-lg font-medium text-dark-text">
                <i class="fas fa-user text-primary-blue mr-2"></i>
                Profile Information
            </h2>
            <p class=" text-gray-600 mt-1">Update your account's profile information and email address.</p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PATCH')

            <!-- Avatar Upload -->
            <div>
                <label class="block font-medium text-gray-700 mb-3">
                    Profile Picture
                </label>
                <div class="flex items-start space-x-6">
                    <!-- Current Avatar -->
                    <div class="flex-shrink-0">
                        <img id="avatar-preview" 
                            src="{{ $user->avatar_url }}" 
                            alt="{{ $user->full_name }}" 
                            class="w-24 h-24 rounded-full object-cover border-2 border-border-gray">
                    </div>

                    <!-- Upload Controls -->
                    <div class="flex-1 space-y-3">
                        <div>
                            <input type="file" name="avatar" id="avatar" 
                                accept="image/jpeg,image/png,image/jpg"
                                class="hidden"
                                onchange="previewAvatar(event)">
                            <label for="avatar" 
                                class="inline-flex items-center px-4 py-2 bg-white border border-border-gray rounded-lg font-medium text-gray-700 hover:bg-gray-50 cursor-pointer transition">
                                <i class="fas fa-upload mr-2"></i>
                                Choose New Photo
                            </label>
                            @if($user->avatar)
                            <button type="button" 
                                onclick="deleteAvatar()"
                                class="ml-3 inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 rounded-lg font-medium text-red-600 hover:bg-red-100 cursor-pointer transition">
                                <i class="fas fa-trash mr-2"></i>
                                Remove Photo
                            </button>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">JPG, JPEG or PNG. Max size 2MB.</p>
                        @error('avatar')
                            <p class=" text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- First Name & Last Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block font-medium text-gray-700 mb-2">
                        First Name
                    </label>
                    <input type="text" name="first_name" id="first_name" 
                        value="{{ old('first_name', $user->first_name) }}"
                        class="w-full px-4 py-2.5 border border-border-gray rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent transition @error('first_name') border-red-500 @enderror"
                        required>
                    @error('first_name')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="block font-medium text-gray-700 mb-2">
                        Last Name
                    </label>
                    <input type="text" name="last_name" id="last_name" 
                        value="{{ old('last_name', $user->last_name) }}"
                        class="w-full px-4 py-2.5 border border-border-gray rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent transition @error('last_name') border-red-500 @enderror"
                        required>
                    @error('last_name')
                        <p class="mt-1  text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block font-medium text-gray-700 mb-2">
                    Email Address
                </label>
                <input type="email" name="email" id="email" 
                    value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-2.5 border border-border-gray rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent transition @error('email') border-red-500 @enderror"
                    required>
                @error('email')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block font-medium text-gray-700 mb-2">
                    Phone Number
                </label>
                <input type="text" name="phone" id="phone" 
                    value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-2.5 border border-border-gray rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent transition @error('phone') border-red-500 @enderror"
                    placeholder="+1-234-567-8900">
                @error('phone')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role (Read-only) -->
            <div>
                <label class="block font-medium text-gray-700 mb-2">
                    Role
                </label>
                <div class="px-4 py-2.5 bg-gray-50 border border-border-gray rounded-lg text-gray-600">
                    <i class="fas fa-shield-alt text-primary-blue mr-2"></i>
                    {{ $user->role?->display_name ?? 'User' }}
                </div>
                <p class="mt-1 text-xs text-gray-500">Contact an administrator to change your role.</p>
            </div>

            <!-- Save Button -->
            <div class="flex items-center justify-end pt-4 border-t border-border-gray">
                <button type="submit" 
                    class="inline-flex items-center px-6 py-2.5 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg shadow-sm transition">
                    <i class="fas fa-save mr-2"></i>
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Update Password -->
    <div class="bg-white rounded-lg shadow-sm border border-border-gray">
        <div class="px-6 py-4 border-b border-border-gray">
            <h2 class="text-lg font-medium text-dark-text">
                <i class="fas fa-lock text-primary-blue mr-2"></i>
                Update Password
            </h2>
            <p class=" text-gray-600 mt-1">Ensure your account is using a long, random password to stay secure.</p>
        </div>

        <form method="POST" action="{{ route('profile.password.update') }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="current_password" class="block font-medium text-gray-700 mb-2">
                    Current Password
                </label>
                <input type="password" name="current_password" id="current_password" 
                    class="w-full px-4 py-2.5 border border-border-gray rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent transition @error('current_password', 'updatePassword') border-red-500 @enderror"
                    autocomplete="current-password">
                @error('current_password', 'updatePassword')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block font-medium text-gray-700 mb-2">
                    New Password
                </label>
                <input type="password" name="password" id="password" 
                    class="w-full px-4 py-2.5 border border-border-gray rounded-lg focus:ring-2 focus:ring-primary-blue focus:border-transparent transition @error('password', 'updatePassword') border-red-500 @enderror"
                    autocomplete="new-password">
                @error('password', 'updatePassword')
                    <p class="mt-1  text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long.</p>
            </div>

            <div>
                <label for="password_confirmation" class="block font-medium text-gray-700 mb-2">
                    Confirm Password
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-transparent "
                    autocomplete="new-password">
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-border-gray">
                <button type="submit" 
                    class="inline-flex items-center px-6 py-2.5 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg shadow-sm transition">
                    <i class="fas fa-key mr-2"></i>
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file size
        if (file.size > 2048 * 1024) {
            alert('File size must be less than 2MB');
            event.target.value = '';
            return;
        }

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Only JPG, JPEG and PNG files are allowed');
            event.target.value = '';
            return;
        }

        // Preview the image
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function deleteAvatar() {
    if (confirm('Are you sure you want to delete your profile picture?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('profile.avatar.delete') }}';
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
