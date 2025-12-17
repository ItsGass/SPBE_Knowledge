<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>{{ config('app.name','SPBE') }}</title>
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-..."
      crossorigin="anonymous"
      referrerpolicy="no-referrer" />

    @vite(['resources/css/app.css'])

    <!-- Logika untuk mencegah Flash of Unstyled Content (FOUC) di Dark Mode -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Fungsi yang digunakan untuk toggle tema
        window.toggleTheme = function() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<!-- Terapkan tema dasar: Light Mode (Putih-Hitam) vs Dark Mode (Hitam-Putih) -->
<body class="font-sans antialiased h-full min-h-screen 
             bg-base-light text-text-light 
             dark:bg-base-dark dark:text-text-dark 
             transition-colors duration-500">

    <!-- HEADER (NAVIGASI ATAS) -->
    <header class="sticky top-0 z-40 
                   bg-white/95 dark:bg-base-dark/95 
                   backdrop-blur-md 
                   border-b border-gray-100 dark:border-poco-900 
                   shadow-soft dark:shadow-none">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">

            {{-- LEFT: brand --}}
            <div class="flex items-center gap-6 min-w-[240px]">
<a href="{{ route('welcome') }}" class="flex items-center text-sm font-semibold transition-colors">
                    {{-- Brand Badge (Kuning Aksen) --}}
                    <div class="w-8 h-8 flex items-center justify-center rounded-lg 
                                bg-poco-500 text-black font-extrabold text-lg">KS</div>
                    
                    <div class="ml-3">
                        <div class="font-extrabold text-text-light dark:text-text-dark">{{ config('app.name','SPBE') }}</div>
                        <div class="text-xs text-muted dark:text-gray-400">Knowledge System</div>
                    </div>
                </a>
            </div>

            {{-- CENTER: nav links --}}
            <nav class="hidden md:flex items-center space-x-6 text-sm" aria-label="Top Navigation">
                <a href="{{ route('dashboard') }}" class="text-muted dark:text-gray-400 hover:text-poco-600 dark:hover:text-poco-400 transition-colors font-medium">Dashboard</a>
                <a href="{{ route('knowledge.index') }}" class="text-muted dark:text-gray-400 hover:text-poco-600 dark:hover:text-poco-400 transition-colors font-medium">Knowledge</a>
                @auth
                    @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
                        <a href="{{ route('scope.index') }}" class="text-muted dark:text-gray-400 hover:text-poco-600 dark:hover:text-poco-400 transition-colors font-medium">Scope</a>
                        <a href="{{ route('tags.index') }}" class="text-muted dark:text-gray-400 hover:text-poco-600 dark:hover:text-poco-400 transition-colors font-medium">Tags</a>

                     @endif
                    @if(auth()->user()->role === 'super_admin')
                        <a href="{{ route('user-management.index') }}" class="text-muted dark:text-gray-400 hover:text-poco-600 dark:hover:text-poco-400 transition-colors font-medium">User Management</a>
                        <a href="{{ route('activity.logs') }}" class="text-muted dark:text-gray-400 hover:text-poco-600 dark:hover:text-poco-400 transition-colors font-medium">Activity Logs</a>
                    @endif
                @endauth
            </nav>

            {{-- RIGHT: profile / logout / Dark Mode Toggle --}}
            <div class="flex items-center justify-end gap-3 min-w-[240px]">

                <!-- 1. DARK MODE TOGGLE -->
                <button onclick="toggleTheme()" 
                        aria-label="Toggle Dark Mode"
                        class="p-2 rounded-full text-text-light/70 dark:text-text-dark/70 
                               hover:bg-gray-100 dark:hover:bg-poco-900 
                               transition duration-300 focus:outline-none focus:ring-2 focus:ring-poco-500">
                    
                    <!-- Ikon Matahari (Light Mode) -->
                    <svg class="h-6 w-6 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <!-- Ikon Bulan (Dark Mode) -->
                    <svg class="h-6 w-6 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <!-- 2. Profile/Logout/Login -->
                @guest
                    <a href="{{ route('login') }}" 
                       class="px-4 py-2 font-semibold rounded-xl transition duration-300 shadow-lg 
                              bg-poco-500 hover:bg-poco-600 text-black 
                              dark:bg-poco-600 dark:hover:bg-poco-700">
                        Login
                    </a>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('profile.edit') }}" 
                           class="text-sm font-medium text-text-light/80 dark:text-text-dark/80 
                                  hover:text-poco-600 dark:hover:text-poco-400 transition-colors">
                            {{ auth()->user()->name }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <!-- TOMBOL LOGOUT DENGAN WARNA MERAH -->
                            <button type="submit" 
                                    class="px-3 py-1.5 font-semibold rounded-xl transition duration-300 
                                           bg-red-500 hover:bg-red-600 text-white 
                                           dark:bg-red-600 dark:hover:bg-red-700 shadow-md">
                                Logout
                            </button>
                        </form>
                    </div>
                @endguest
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @isset($slot) {{ $slot }} @endisset
            @yield('content')
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="py-6 border-t border-gray-100 dark:border-poco-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-muted dark:text-gray-600">
            © {{ date('Y') }} {{ config('app.name','SPBE') }}
        </div>
    </footer>
</body>
</html>