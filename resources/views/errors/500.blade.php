<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-lg w-full text-center">
            <div class="mb-8">
                <h1 class="text-9xl font-medium text-yellow-300">500</h1>
                <h2 class="text-3xl font-medium text-gray-800 mt-4">Server Error</h2>
                <p class="text-gray-600 mt-4">Oops! Something went wrong on our end. We're working to fix it.</p>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8 text-left">
                <h3 class=" font-medium text-yellow-900 mb-2">What you can try:</h3>
                <ul class="text-yellow-800  space-y-1 list-disc list-inside">
                    <li>Refresh the page</li>
                    <li>Clear your browser cache</li>
                    <li>Try again in a few minutes</li>
                    <li>Contact support if the problem persists</li>
                </ul>
            </div>
            
            <div class="flex justify-center gap-4">
                <a href="{{ url('/') }}" class="px-6 py-3 bg-[#1a425f] hover:bg-[#1a425f]/90 text-white font-medium rounded-lg transition">
                    Go Home
                </a>
                <button onclick="location.reload()" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition">
                    Refresh Page
                </button>
            </div>
            
            <div class="mt-12">
                <svg class="mx-auto h-48 w-48 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            @if(config('app.debug') && isset($exception))
                <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-4 text-left">
                    <p class="text-red-900 font-medium  mb-2">Debug Information (APP_DEBUG=true):</p>
                    <p class="text-red-700 text-xs font-mono break-all">{{ $exception->getMessage() }}</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
