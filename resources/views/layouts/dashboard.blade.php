<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'CASMS') }}</title>
    
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=SN+Pro:ital,wght@0,200..900;1,200..900&display=swap&subset=latin" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside 
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:block flex flex-col"
            :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            @click.away="mobileSidebarOpen = false"
        >
            <div class="h-16 flex items-center justify-between px-6 border-b border-border-gray flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('assets/images/casms-logo.png') }}" alt="Logo" class="h-10 w-auto">
                </a>
                <button @click="mobileSidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3">
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center px-3 py-2.5 font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-100 bg-opacity-10 text-primary-blue' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-home w-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-primary-blue' : 'text-gray-400' }}"></i>
                        <span>Dashboard</span>
                    </a>

                    @if(auth()->user()->hasPermission('clients.view'))
                    <a href="{{ route('clients.index') }}" 
                       class="flex items-center px-3 py-2.5 font-medium rounded-lg transition-colors {{ request()->routeIs('clients.*') ? 'bg-gray-100 bg-opacity-10 text-primary-blue' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-users w-5 mr-3 {{ request()->routeIs('clients.*') ? 'text-primary-blue' : 'text-gray-400' }}"></i>
                        <span>Clients</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('approvals.view'))
                    <a href="{{ route('approvals.index') }}" 
                       class="flex items-center px-3 py-2.5 font-medium rounded-lg transition-colors {{ request()->routeIs('approvals.*') ? 'bg-gray-100 bg-opacity-10 text-primary-blue' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-check-circle w-5 mr-3 {{ request()->routeIs('approvals.*') ? 'text-primary-blue' : 'text-gray-400' }}"></i>
                        <span>Approval Requests</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('users.view'))
                    <a href="{{ route('users.index') }}" 
                       class="flex items-center px-3 py-2.5 font-medium rounded-lg transition-colors {{ request()->routeIs('users.*') ? 'bg-gray-100 bg-opacity-10 text-primary-blue' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-user-shield w-5 mr-3 {{ request()->routeIs('users.*') ? 'text-primary-blue' : 'text-gray-400' }}"></i>
                        <span>Users</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('roles.view'))
                    <a href="{{ route('roles.index') }}" 
                       class="flex items-center px-3 py-2.5 font-medium rounded-lg transition-colors {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') ? 'bg-gray-100 bg-opacity-10 text-primary-blue' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-user-tag w-5 mr-3 {{ request()->routeIs('roles.*') ? 'text-primary-blue' : 'text-gray-400' }}"></i>
                        <span>Roles & Permissions</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('email-templates.index') }}" 
                       class="flex items-center px-3 py-2.5 font-medium rounded-lg transition-colors {{ request()->routeIs('email-templates.*') ? 'bg-gray-100 bg-opacity-10 text-primary-blue' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-envelope w-5 mr-3 {{ request()->routeIs('email-templates.*') ? 'text-primary-blue' : 'text-gray-400' }}"></i>
                        <span>Email Templates</span>
                    </a>
                    @endif

                    @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('audit-logs.index') }}" 
                       class="flex items-center px-3 py-2.5 font-medium rounded-lg transition-colors {{ request()->routeIs('audit-logs.*') ? 'bg-gray-100 bg-opacity-10 text-primary-blue' : 'text-gray-700 hover:bg-gray-100' }}">
                        <i class="fas fa-history w-5 mr-3 {{ request()->routeIs('audit-logs.*') ? 'text-primary-blue' : 'text-gray-400' }}"></i>
                        <span>Audit Logs</span>
                    </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" 
                       class="flex items-center px-3 py-2.5 font-medium text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-cog w-5 mr-3 text-gray-400"></i>
                        <span>Settings</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile - Fixed at Bottom -->
            <div class="p-4 border-t border-border-gray mt-auto flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <img src="{{ auth()->user()->avatar_url }}" 
                        alt="{{ auth()->user()->full_name }}"
                        class="w-10 h-10 rounded-full object-cover border-2 border-border-gray">
                    <div class="flex-1 min-w-0">
                        <p class=" font-medium text-gray-900 truncate">
                            {{ auth()->user()->full_name ?? auth()->user()->name }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ auth()->user()->role?->display_name ?? ucfirst(auth()->user()->role ?? 'User') }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">
                <div class="flex items-center space-x-4">
                    <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>

                <div class="flex items-center space-x-4">
                    @hasSection('header-actions')
                        @yield('header-actions')
                    @endif

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                            <img src="{{ auth()->user()->avatar_url }}" 
                                alt="{{ auth()->user()->full_name }}"
                                class="w-8 h-8 rounded-full object-cover border-2 border-border-gray">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <div x-show="open" 
                             @click.away="open = false"
                             x-transition
                             x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200">
                            <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2  text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user w-5 mr-2 text-gray-400"></i>
                                <span>My Profile</span>
                            </a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center px-4 py-2  text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt w-5 mr-2"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r" x-data="{ show: true }" x-show="show" x-transition>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <p class="text-green-700">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-green-500 hover:text-green-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r" x-data="{ show: true }" x-show="show" x-transition>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                            <p class="text-red-700">{{ session('error') }}</p>
                        </div>
                        <button @click="show = false" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r" x-data="{ show: true }" x-show="show" x-transition>
                    <div class="flex items-start justify-between">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-circle text-red-500 mr-3 mt-0.5"></i>
                            <div>
                                <p class="text-red-700 font-medium mb-2">Please fix the following errors:</p>
                                <ul class="list-disc list-inside text-red-600  space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button @click="show = false" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                @endif

                @yield('content')
            </main>

            <footer class="bg-white border-t border-gray-200 py-4 px-6">
                <div class="flex items-center justify-between  text-gray-600">
                    <p>&copy; {{ date('Y') }} {{ config('app.name', 'CASMS') }}. All rights reserved.</p>
                    <p>Version {{ config('app.version', '1.0.0') }}</p>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
