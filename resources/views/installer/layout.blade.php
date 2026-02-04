<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Wizard - Client Approval System</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap&subset=latin" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light-bg">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/images/casms-logo.png') }}" alt="Logo" class="h-12 w-auto">
                </div>
                <p class="text-gray-600 mt-2">Installation Wizard</p>
            </div>

            <!-- Progress Bar -->
            <?php /* @include('installer.partials.progress', ['step' => $currentStep ?? 1]) */ ?>

            <!-- Content Card -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                @yield('content')
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center  text-gray-500">
                <p>{{ config('app.name') }} v{{ config('app.version') }}</p>
                <p class="mt-1">&copy; {{ date('Y') }} All Rights Reserved</p>
            </div>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
