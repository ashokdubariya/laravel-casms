<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - {{ config('app.name') }}</title>
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
    <!-- Reset Password Card -->
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8 border border-border-gray">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/images/casms-logo.png') }}" alt="Logo" class="h-12 w-auto">
                </div>
                <h1 class="text-3xl font-medium text-dark-text">Reset Password</h1>
                <p class="text-gray-600 mt-2">Create a new secure password</p>
            </div>

            <!-- Instructions -->
            <div class="mb-6 p-4 bg-light-bg border border-border-gray rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-shield-alt text-primary-blue mr-3 mt-0.5"></i>
                    <p class=" text-gray-700">
                        Choose a strong password that you haven't used before.
                    </p>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div>
                    <label for="email" class="block font-medium text-dark-text mb-2">
                        <i class="fas fa-envelope text-gray-400 mr-2"></i>Email Address
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email', $request->email) }}" 
                        required 
                        autofocus
                        class="w-full px-4 py-3 rounded-lg border border-border-gray focus:border-primary-blue focus:ring-2 focus:ring-primary-blue focus:ring-opacity-20 transition-all @error('email') border-red-500 @enderror"
                        placeholder="you@example.com"
                    >
                    @error('email')
                    <p class="mt-2  text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block font-medium text-dark-text mb-2">
                        <i class="fas fa-lock text-gray-400 mr-2"></i>New Password
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

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block font-medium text-dark-text mb-2">
                        <i class="fas fa-check-circle text-gray-400 mr-2"></i>Confirm New Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password_confirmation" 
                            id="password_confirmation" 
                            required
                            oninput="checkPasswordMatch()"
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
                        <div id="match-indicator" class="absolute right-12 top-1/2 -translate-y-1/2 hidden">
                            <i class="fas fa-times-circle text-red-500"></i>
                        </div>
                    </div>
                    <p id="match-message" class="mt-2  hidden"></p>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-primary-blue text-white py-3 rounded-lg font-medium hover:opacity-90 transition-all shadow-md hover:shadow-lg"
                >
                    <i class="fas fa-save mr-2"></i>Reset Password
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-border-gray"></div>
                </div>
                <div class="relative flex justify-center ">
                    <span class="px-4 bg-white text-gray-500">Or</span>
                </div>
            </div>

            <!-- Back to Login -->
            <a 
                href="{{ route('login') }}"
                class="block text-center py-3 rounded-lg border-2 border-border-gray text-gray-700 font-medium hover:border-primary-blue hover:text-primary-blue transition-all"
            >
                <i class="fas fa-arrow-left mr-2"></i>Back to Sign In
            </a>

            <!-- Security Info -->
            <div class="mt-6 p-4 bg-green-50 border border-approval-green rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mr-3 mt-0.5"></i>
                    <div class="text-xs text-green-800">
                        <p class="font-medium mb-1">Your account is secure</p>
                        <p>This password reset link will expire in 60 minutes for security.</p>
                    </div>
                </div>
            </div>
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
                strengthText.className = 'text-xs font-medium';
                strengthText.style.color = '#85c34e';
            }
            
            checkPasswordMatch();
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchIndicator = document.getElementById('match-indicator');
            const matchMessage = document.getElementById('match-message');
            
            if (confirmation.length === 0) {
                matchIndicator.classList.add('hidden');
                matchMessage.classList.add('hidden');
                return;
            }
            
            if (password === confirmation) {
                matchIndicator.classList.remove('hidden');
                matchIndicator.innerHTML = '<i class="fas fa-check-circle text-approval-green"></i>';
                matchMessage.classList.remove('hidden');
                matchMessage.className = 'mt-2  text-approval-green flex items-center';
                matchMessage.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Passwords match';
            } else {
                matchIndicator.classList.remove('hidden');
                matchIndicator.innerHTML = '<i class="fas fa-times-circle text-red-500"></i>';
                matchMessage.classList.remove('hidden');
                matchMessage.className = 'mt-2  text-red-600 flex items-center';
                matchMessage.innerHTML = '<i class="fas fa-times-circle mr-2"></i>Passwords do not match';
            }
        }
    </script>
</body>
</html>
