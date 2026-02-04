<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-lg w-full text-center">
            <div class="mb-8">
                <h1 class="text-9xl font-medium text-gray-300">404</h1>
                <h2 class="text-3xl font-medium text-gray-800 mt-4">Page Not Found</h2>
                <p class="text-gray-600 mt-4">Sorry, the page you are looking for doesn't exist or has been moved.</p>
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
                <svg class="mx-auto h-48 w-48 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</body>
</html>
