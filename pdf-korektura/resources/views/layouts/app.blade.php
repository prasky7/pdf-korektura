<!DOCTYPE html>
<html lang="cs-CZ" style="font-size: 90%;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PDF Korektura')</title>
    <script src="https://cdn.tailwindcss.com" defer></script>
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">
    <nav class="bg-slate-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold tracking-tight">PDF Korektura</a>
                    @auth
                        <div class="hidden md:flex space-x-2 ml-6">
                            <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-slate-700 {{ request()->routeIs('dashboard') ? 'bg-slate-700' : '' }}">Dashboard</a>
                            @if(auth()->user()->isEditor() || auth()->user()->isAdmin())
                                <a href="{{ route('pdf.upload') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-slate-700 {{ request()->routeIs('pdf.upload') ? 'bg-slate-700' : '' }}">Nahrát PDF</a>
                            @endif
                            @if(auth()->user()->isProofreader() || auth()->user()->isAdmin())
                                <a href="{{ route('pdf.pool') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-slate-700 {{ request()->routeIs('pdf.pool') ? 'bg-slate-700' : '' }}">Nepřiřazené PDF</a>
                                <a href="{{ route('pdf.assignments') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-slate-700 {{ request()->routeIs('pdf.assignments') ? 'bg-slate-700' : '' }}">Moje přiřazení</a>
                            @endif
                            @if(auth()->user()->isAdmin())
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-slate-700 flex items-center">
                                        Admin
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 text-gray-800 z-50">
                                        <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Uživatelé</a>
                                        <a href="{{ route('admin.audit-log') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Audit log</a>
                                        <a href="{{ route('admin.titles') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Tituly</a>
                                        <a href="{{ route('admin.archive') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">Archiv</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endauth
                </div>
                @auth
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-slate-300">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-2 rounded-md text-sm font-medium bg-red-600 hover:bg-red-700">Odhlásit</button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <div x-data="{ show: false, message: '', type: 'success' }"
         x-on:notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 4000)"
         class="fixed bottom-4 right-4 z-50">
        <div x-show="show"
             x-transition
             :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'"
             class="text-white px-6 py-3 rounded-lg shadow-lg">
            <span x-text="message"></span>
        </div>
    </div>

    @livewireScripts
</body>
</html>
