<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'CASMS') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap&subset=latin" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
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
    <!-- Login Card -->
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8 border border-border-gray">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/images/casms-logo.png') }}" alt="Logo" class="h-12 w-auto">
                </div>
                <p class="text-gray-600 mt-2">Sign in to {{ config('app.name', 'CASMS') }}</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-approval-green rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-approval-green mr-3"></i>
                    <p class=" text-green-800">{{ session('status') }}</p>
                </div>
            </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

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
                            class="w-full px-4 py-3 rounded-lg border border-border-gray focus:border-primary-blue focus:ring-2 focus:ring-primary-blue focus:ring-opacity-20 transition-all @error('password') border-red-500 @enderror"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                        >
                            <i id="eye-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                    <p class="mt-2  text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="w-4 h-4 text-primary-blue border-border-gray rounded focus:ring-primary-blue"
                        >
                        <span class="ml-2  text-gray-600">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class=" text-primary-blue hover:underline font-medium">
                        Forgot password?
                    </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-primary-blue text-white py-3 rounded-lg font-medium hover:opacity-90 transition-all shadow-md hover:shadow-lg"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </button>
            </form>

            <!-- Demo Credentials -->
            <!-- <div class="mt-6 p-4 bg-light-bg border border-border-gray rounded-lg">
                <p class="text-xs font-medium text-dark-text mb-2">
                    <i class="fas fa-info-circle mr-1"></i>Demo Credentials:
                </p>
                <div class="text-xs text-gray-700 space-y-1 mb-2">
                    <p><strong>Email:</strong> admin@example.com</p>
                    <p><strong>Password:</strong> password123</p>
                </div>

                <div class="text-xs text-gray-700 space-y-1 mb-2">
                    <p><strong>Email:</strong> manager@example.com</p>
                    <p><strong>Password:</strong> password123</p>
                </div>

                <div class="text-xs text-gray-700 space-y-1 mb-2">
                    <p><strong>Email:</strong> user@example.com</p>
                    <p><strong>Password:</strong> password123</p>
                </div>
            </div> -->
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-600 ">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'CASMS') }}. All rights reserved.</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
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
    </script>
</body>
</html>
