<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap&subset=latin" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .password-strength-weak { background: #ef4444; }
        .password-strength-medium { background: #f59e0b; }
        .password-strength-strong { background: #85c34e; }
    </style>
    
    <script>
        const tailwindConfig = {
            theme: {
                extend: {
                    colors: {
                        'primary-blue': '#1a425f',
                        'approval-green': '#85c34e',
                        'dark-text': '#0F172A',
                        'light-bg': '#F8FAFC',
                        'border-gray': '#CBD5E1',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-light-bg min-h-screen flex items-center justify-center p-4">
    <!-- Register Card -->
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8 border border-border-gray">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/images/casms-logo.png') }}" alt="Logo" class="h-12 w-auto">
                </div>
                <h1 class="text-3xl font-medium text-dark-text">Create Account</h1>
                <p class="text-gray-600 mt-2">Join {{ config('app.name') }} today</p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name Fields -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block font-medium text-dark-text mb-2">
                            <i class="fas fa-user text-gray-400 mr-1"></i>First Name
                        </label>
                        <input 
                            type="text" 
                            name="first_name" 
                            id="first_name" 
                            value="{{ old('first_name') }}" 
                            required 
                            autofocus
                            class="w-full px-4 py-3 rounded-lg border border-border-gray focus:border-primary-blue focus:ring-2 focus:ring-primary-blue focus:ring-opacity-20 transition-all @error('first_name') border-red-500 @enderror"
                            placeholder="John"
                        >
                        @error('first_name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block font-medium text-dark-text mb-2">
                            <i class="fas fa-user text-gray-400 mr-1"></i>Last Name
                        </label>
                        <input 
                            type="text" 
                            name="last_name" 
                            id="last_name" 
                            value="{{ old('last_name') }}" 
                            required
                            class="w-full px-4 py-3 rounded-lg border border-border-gray focus:border-primary-blue focus:ring-2 focus:ring-primary-blue focus:ring-opacity-20 transition-all @error('last_name') border-red-500 @enderror"
                            placeholder="Doe"
                        >
                        @error('last_name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block font-medium text-dark-text mb-2">
                        <i class="fas fa-envelope text-gray-400 mr-2"></i>Email Address
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}" 
                        required
                        class="w-full px-4 py-3 rounded-lg border border-border-gray focus:border-primary-blue focus:ring-2 focus:ring-primary-blue focus:ring-opacity-20 transition-all @error('email') border-red-500 @enderror"
                        placeholder="you@example.com"
                    >
                    @error('email')
                    <p class="mt-2  text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block font-medium text-dark-text mb-2">
                        <i class="fas fa-lock text-gray-400 mr-2"></i>Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required
                            oninput="checkPasswordStrength()"
                            class="w-full px-4 py-3 rounded-lg border border-border-gray focus:border-primary-blue focus:ring-2 focus:ring-primary-blue focus:ring-opacity-20 transition-all @error('password') border-red-500 @enderror"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            <i id="eye-icon-password" class="fas fa-eye"></i>
                        </button>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="flex items-center space-x-2 mb-1">
                            <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div id="strength-bar" class="h-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <span id="strength-text" class="text-xs font-medium text-gray-500"></span>
                        </div>
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>Use 8+ characters with mix of letters, numbers & symbols
                        </p>
                    </div>
                    
                    @error('password')
                    <p class="mt-2  text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block font-medium text-dark-text mb-2">
                        <i class="fas fa-check-circle text-gray-400 mr-2"></i>Confirm Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            required
                            class="w-full px-4 py-3 rounded-lg border border-border-gray focus:border-primary-blue focus:ring-2 focus:ring-primary-blue focus:ring-opacity-20 transition-all"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword('password_confirmation')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            <i id="eye-icon-password_confirmation" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="flex items-start">
                    <input 
                        type="checkbox" 
                        name="terms" 
                        id="terms" 
                        required
                        class="mt-1 w-4 h-4 text-primary-blue border-border-gray rounded focus:ring-primary-blue"
                    >
                    <label for="terms" class="ml-2  text-gray-600">
                        I agree to the <a href="#" class="text-primary-blue hover:underline font-medium">Terms of Service</a> and <a href="#" class="text-primary-blue hover:underline font-medium">Privacy Policy</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-primary-blue text-white py-3 rounded-lg font-medium hover:opacity-90 transition-all shadow-md hover:shadow-lg"
                >
                    <i class="fas fa-rocket mr-2"></i>Create Account
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-border-gray"></div>
                </div>
                <div class="relative flex justify-center ">
                    <span class="px-4 bg-white text-gray-500">Already have an account?</span>
                </div>
            </div>

            <!-- Login Link -->
            <a 
                href="{{ route('login') }}"
                class="block text-center py-3 rounded-lg border-2 border-border-gray text-gray-700 font-medium hover:border-primary-blue hover:text-primary-blue transition-all"
            >
                <i class="fas fa-sign-in-alt mr-2"></i>Sign In
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-600 ">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const eyeIcon = document.getElementById('eye-icon-' + fieldId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            let percentage = (strength / 5) * 100;
            strengthBar.style.width = percentage + '%';
            
            if (strength <= 2) {
                strengthBar.className = 'h-full transition-all duration-300 password-strength-weak';
                strengthText.textContent = 'Weak';
                strengthText.className = 'text-xs font-medium text-red-600';
            } else if (strength <= 4) {
                strengthBar.className = 'h-full transition-all duration-300 password-strength-medium';
                strengthText.textContent = 'Medium';
                strengthText.className = 'text-xs font-medium text-yellow-600';
            } else {
                strengthBar.className = 'h-full transition-all duration-300 password-strength-strong';
                strengthText.textContent = 'Strong';
                strengthText.className = 'text-xs font-medium text-approval-green';
            }
        }
    </script>
</body>
</html>
