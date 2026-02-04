<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - {{ config('app.name') }}</title>
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
    <!-- Forgot Password Card -->
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8 border border-border-gray">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/images/casms-logo.png') }}" alt="Logo" class="h-12 w-auto">
                </div>
                <h1 class="text-3xl font-medium text-dark-text">Forgot Password?</h1>
                <p class="text-gray-600 mt-2">No worries, we'll send you reset instructions</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-approval-green rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-check-circle text-approval-green mr-3 mt-0.5"></i>
                    <div>
                        <p class=" text-green-800 font-medium">Email Sent Successfully!</p>
                        <p class=" text-green-700 mt-1">{{ session('status') }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full bg-primary-blue text-white py-3 rounded-lg font-medium hover:opacity-90 transition-all shadow-md hover:shadow-lg"
                >
                    <i class="fas fa-paper-plane mr-2"></i>Send Reset Link
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-border-gray"></div>
                </div>
                <div class="relative flex justify-center ">
                    <span class="px-4 bg-white text-gray-500">Remember your password?</span>
                </div>
            </div>

            <!-- Back to Login -->
            <a 
                href="{{ route('login') }}"
                class="block text-center py-3 rounded-lg border-2 border-border-gray text-gray-700 font-medium hover:border-primary-blue hover:text-primary-blue transition-all"
            >
                <i class="fas fa-arrow-left mr-2"></i>Back to Sign In
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-gray-600 ">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
