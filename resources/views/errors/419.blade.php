<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Page Expired</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-lg w-full text-center">
            <div class="mb-8">
                <h1 class="text-9xl font-medium text-orange-300">419</h1>
                <h2 class="text-3xl font-medium text-gray-800 mt-4">Page Expired</h2>
                <p class="text-gray-600 mt-4">Your session has expired. Please refresh the page and try again.</p>
            </div>
            
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-8">
                <p class="text-orange-800 ">
                    This usually happens when you've been inactive for too long or opened the page in multiple tabs.
                </p>
            </div>
            
            <div class="flex justify-center gap-4">
                <button onclick="location.reload()" class="px-6 py-3 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition">
                    Refresh Page
                </button>
                <a href="{{ url('/') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                    Go Home
                </a>
            </div>
            
            <div class="mt-12">
                <svg class="mx-auto h-48 w-48 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</body>
</html>
