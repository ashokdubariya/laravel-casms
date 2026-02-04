<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Forbidden</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-lg w-full text-center">
            <div class="mb-8">
                <h1 class="text-9xl font-medium text-red-300">403</h1>
                <h2 class="text-3xl font-medium text-gray-800 mt-4">Access Forbidden</h2>
                <p class="text-gray-600 mt-4">You don't have permission to access this resource.</p>
                
                @if(isset($exception) && $exception->getMessage())
                    <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-red-700 ">{{ $exception->getMessage() }}</p>
                    </div>
                @endif
            </div>
            
            <div class="flex justify-center gap-4">
                <a href="{{ url('/') }}" class="px-6 py-3 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition">
                    Go Home
                </a>
                <button onclick="history.back()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                    Go Back
                </button>
            </div>
            
            <div class="mt-12">
                <svg class="mx-auto h-48 w-48 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
        </div>
    </div>
</body>
</html>
