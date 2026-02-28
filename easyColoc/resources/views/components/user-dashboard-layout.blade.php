<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'EasyColoc') }} - Dashboard</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Mono:wght@300;400;500&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="min-h-screen bg-gray-100">
    <nav class="bg-gray-900 border-b border-gray-800 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ auth()->user()->is_global_admin ? route('admin.dashboard') : route('dashboard') }}" class="text-white font-bold text-lg">
                    EasyColoc
                </a>
                
                <div class="flex gap-4">
                    <a href="{{ auth()->user()->is_global_admin ? route('admin.dashboard') : route('dashboard') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white' : '' }}">
                        Dashboard
                    </a>
                    @if(!auth()->user()->is_global_admin)
                    <a href="{{ route('colocation') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('colocation') ? 'bg-gray-800 text-white' : '' }}">
                        Ma Colocation
                    </a>
                    @endif
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="text-white text-sm font-medium">
                        {{ auth()->user()->name }}
                    </button>
                    
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-50" style="display: none;">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Profile
                        </a>
                        
                        <div class="border-t border-gray-100"></div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    @stack('scripts')
</body>
</html>
