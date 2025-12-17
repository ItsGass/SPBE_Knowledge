<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" class="h-full antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>{{ config('app.name','SPBE') }}</title>

    @vite(['resources/css/app.css'])

    <script>
        try {
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        } catch (e) {
            // ignore in older browsers
        }

        // Fungsi toggle tema
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
</head>

<body class="font-sans antialiased h-full min-h-screen
             bg-base-light text-text-light
             dark:bg-base-dark dark:text-text-dark
             transition-colors duration-500">

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

            {{-- CENTER: nav links (hidden on small screens) --}}
            <nav class="hidden md:flex items-center space-x-6 text-sm" aria-label="Top Navigation">
                
                @auth
                    <a href="{{ route('knowledge.index') }}" class="text-muted dark:text-gray-400 hover:text-poco-600 dark:hover:text-poco-400 transition-colors font-medium">Knowledge</a>
                    @if(in_array(auth()->user()->role, ['admin','super_admin','verifikator']))
                        <a href="{{ route('knowledge.create') }}" class="text-muted dark:text-gray-400 hover:text-poco-600 dark:hover:text-poco-400 transition-colors font-medium">Buat</a>
                    @endif
                    @if(in_array(auth()->user()->role, ['admin','super_admin']))
                        <a href="{{ route('scope.index') }}" class="text-muted dark:text-gray-400 hover:text-poco-600 dark:hover:text-poco-400 transition-colors font-medium">Scope</a>
                    @endif
                @endauth
            </nav>

            {{-- RIGHT: theme toggle + auth --}}
            <div class="flex items-center justify-end gap-3 min-w-[240px]">
                <button onclick="toggleTheme()"
                        aria-label="Toggle Dark Mode"
                        class="p-2 rounded-full text-text-light/70 dark:text-text-dark/70
                               hover:bg-gray-100 dark:hover:bg-poco-900
                               transition duration-300 focus:outline-none focus:ring-2 focus:ring-poco-500">
                    <svg class="h-6 w-6 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg class="h-6 w-6 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                {{-- Auth buttons --}}
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-medium text-text-light/80 dark:text-text-dark/80 hover:text-poco-600 dark:hover:text-poco-400">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-1.5 font-semibold rounded-xl transition duration-300
                                           bg-red-500 hover:bg-red-600 text-white dark:bg-red-600 dark:hover:bg-red-700 shadow-sm">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="px-4 py-2 font-semibold rounded-xl transition duration-300 shadow-lg
                                  bg-poco-500 hover:bg-poco-600 text-black dark:bg-poco-600 dark:hover:bg-poco-700">
                            Login
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="px-4 py-2 font-semibold rounded-xl border border-transparent hover:border-gray-200 dark:hover:border-gray-700 text-sm">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <main class="py-16">
        <div class="max-w-3xl mx-auto px-4">
            <div class="text-center">
                {{-- Big badge + title --}}
                <div class="mx-auto w-28 h-28 rounded-full bg-gradient-to-br from-poco-500 to-poco-600 flex items-center justify-center text-black font-extrabold text-4xl shadow-xl">
                    KS
                </div>

                <h1 class="mt-6 text-3xl font-extrabold text-text-light dark:text-text-dark">Knowledge System</h1>
                {{-- <p class="mt-2 text-sm text-muted dark:text-gray-400">Cari dokumen, simpan informasi — publik & internal</p>--}}
            </div>

            {{-- Unified Search form: single input accepts keywords, tags (#tag or tag:tag), scope (scope:name or scope:id), or free text --}}
            <form action="{{ route('welcome') }}" method="GET" class="mt-8">
                <div class="flex items-center gap-3 w-full bg-white dark:bg-[#0b1220] border border-gray-200 dark:border-gray-800 rounded-full px-4 py-3 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
                    </svg>

                    <input
                        name="q"
                        value="{{ request('q') }}"
                        type="search"
                        placeholder="Ketik: judul, kata kunci, #tag, scope:desa, ..."
                        class="flex-1 bg-transparent outline-none text-lg text-text-light dark:text-text-dark"
                        autocomplete="off"
                        autofocus
                    />

                    <button type="submit" class="ml-3 px-5 py-2 rounded-full bg-poco-500 hover:bg-poco-600 text-black font-semibold">Search</button>
                </div>

                
            </form>

            {{-- SERVER-SIDE: parsing input q menjadi keywords, tags, scope --}}
            @php
                $raw = trim((string) request('q'));
                $keywords = [];
                $tokens = [];
                $tags = [];
                $scopeId = null;
                $scopeName = null;

                if ($raw !== '') {
                    // split by spaces or commas but keep tokens like "tag:foo" intact
                    $rawParts = preg_split('/[\s,]+/', $raw, -1, PREG_SPLIT_NO_EMPTY);

                    foreach ($rawParts as $part) {
                        $p = trim($part);
                        // tag formats: #tag, tag:tag
                        if (preg_match('/^#(.+)$/u', $p, $m)) {
                            $tags[] = mb_strtolower($m[1]);
                            continue;
                        }

                        if (preg_match('/^tag:(.+)$/i', $p, $m)) {
                            $tags[] = mb_strtolower(trim($m[1]));
                            continue;
                        }

                        // scope formats: scope:id or scope:name or s:id
                        if (preg_match('/^(?:scope|s):(.+)$/i', $p, $m)) {
                            $s = trim($m[1]);
                            if (is_numeric($s)) {
                                $scopeId = (int)$s;
                            } else {
                                $scopeName = $s;
                            }
                            continue;
                        }

                        // fallback: treat as keyword
                        $keywords[] = $p;
                    }

                    // normalize tags -> unique, slugify-like
                    $tokens = array_values(array_unique(array_filter(array_map(function($t) {
                        return mb_strtolower(trim(preg_replace('/^[#]+/','', $t)));
                    }, $tags))));
                }

                $searchResults = collect();
            @endphp

            @if($raw !== '')
                @php
                    $query = \App\Models\Knowledge::with(['scope','status','tags'])
                        ->where('visibility', 'public')
                        ->whereHas('status', fn($qq) => $qq->where('key', 'verified'));

                    // apply scope filter if present
                    if ($scopeId) {
                        $query->where('scope_id', $scopeId);
                    } elseif ($scopeName) {
                        $query->whereHas('scope', function($qs) use ($scopeName) {
                            $qs->where('name', 'like', "%{$scopeName}%");
                        });
                    }

                    // apply tag filters
                    if (!empty($tokens)) {
                        $slugs = array_map(function($t) {
                            return \Illuminate\Support\Str::slug($t);
                        }, $tokens);

                        $query->whereHas('tags', function($qtags) use ($tokens, $slugs) {
                            $qtags->where(function($q2) use ($tokens, $slugs) {
                                $q2->whereIn('slug', $slugs)
                                   ->orWhereIn('name', $tokens);
                            });
                        });
                    }

                    // apply keywords to title/body (each keyword must appear in either title or body)
                    if (!empty($keywords)) {
                        $query->where(function($qk) use ($keywords) {
                            foreach ($keywords as $kw) {
                                $qk->where(function($sub) use ($kw) {
                                    $sub->where('title', 'like', "%{$kw}%")
                                        ->orWhere('body', 'like', "%{$kw}%");
                                });
                            }
                        });
                    }

                    $searchResults = $query->latest()->take(24)->get();
                @endphp

                <div class="mt-8">
                    <h2 class="text-xl font-semibold text-text-light dark:text-text-dark mb-4">
                        Hasil pencarian untuk: <span class="font-bold">"{{ e($raw) }}"</span>
                        <span class="text-sm text-muted dark:text-gray-400">({{ $searchResults->count() }} hasil)</span>
                    </h2>

                    @if($searchResults->isEmpty())
                        <div class="bg-white dark:bg-base-dark/80 p-6 rounded-xl text-center text-muted dark:text-gray-400">
                            Tidak ditemukan knowledge publik yang cocok.
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($searchResults as $k)
                                @php
                                    $type = $k->type ?? 'pdf';
                                @endphp

                                <div class="bg-white dark:bg-base-dark/80 rounded-2xl overflow-hidden
                                            shadow-soft dark:shadow-xl flex flex-col">

                                    {{-- THUMBNAIL (HANYA DARI DATABASE) --}}
                                    @if($k->thumbnail)
                                        <img src="{{ asset('storage/'.$k->thumbnail) }}"
                                             class="h-40 w-full object-cover">
                                    @else
                                        <div class="h-40 flex items-center justify-center
                                                    bg-gray-100 dark:bg-gray-900
                                                    text-gray-400 font-extrabold tracking-widest">
                                            {{ strtoupper($type) }}
                                        </div>
                                    @endif

                                    <div class="p-6 flex flex-col justify-between h-full">
                                        <div>
                                            {{-- TITLE --}}
                                            <h3 class="font-extrabold text-lg text-poco-700 dark:text-poco-400">
                                                {{ $k->title }}
                                            </h3>

                                            {{-- BADGES --}}
                                            <div class="mt-2 flex gap-2 text-xs font-semibold">
                                                <span class="px-2 py-0.5 rounded-full
                                                    {{ $type === 'video'
                                                        ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'
                                                        : 'bg-gray-100 text-gray-700 dark:bg-gray-800/30 dark:text-gray-300' }}">
                                                    {{ strtoupper($type) }}
                                                </span>

                                                <span class="px-2 py-0.5 rounded-full
                                                    bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                                    VERIFIED
                                                </span>
                                            </div>

                                            {{-- SNIPPET --}}
                                            <p class="mt-3 text-sm text-text-light/70 dark:text-text-dark/70 line-clamp-3">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($k->body), 120) }}
                                            </p>
                                        </div>

                                        {{-- ACTION --}}
                                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-poco-900
                                                    flex justify-between items-center">
                                            <a href="{{ auth()->check()
                                                        ? route('knowledge.show',$k)
                                                        : route('knowledge.public.show',$k) }}"
                                               class="px-4 py-1.5 rounded-xl font-semibold
                                                      border border-poco-500 text-poco-500
                                                      hover:bg-poco-500 hover:text-black">
                                                Buka
                                            </a>

                                            <small class="text-xs text-muted">
                                                {{ optional($k->created_at)->format('d M Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            {{-- Knowledge Publik Terbaru (default ketika tidak mencari) --}}
            @if(empty($raw))
                @if(isset($publicKnowledges) && $publicKnowledges->count())
                    <div class="mt-12">
                        <h2 class="text-2xl font-extrabold text-text-light dark:text-text-dark mb-6">
                            Knowledge 
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($publicKnowledges as $k)
                                @php
                                    $type = $k->type ?? 'pdf';
                                @endphp

                                <div class="bg-white dark:bg-base-dark/80 rounded-2xl overflow-hidden
                                            shadow-soft dark:shadow-xl flex flex-col">

                                    {{-- THUMBNAIL (HANYA DARI DATABASE) --}}
                                    @if($k->thumbnail)
                                        <img src="{{ asset('storage/'.$k->thumbnail) }}"
                                             class="h-40 w-full object-cover">
                                    @else
                                        <div class="h-40 flex items-center justify-center
                                                    bg-gray-100 dark:bg-gray-900
                                                    text-gray-400 font-extrabold tracking-widest">
                                            {{ strtoupper($type) }}
                                        </div>
                                    @endif

                                    <div class="p-6 flex flex-col justify-between h-full">
                                        <div>
                                            {{-- TITLE --}}
                                            <h3 class="font-extrabold text-lg text-poco-700 dark:text-poco-400">
                                                {{ $k->title }}
                                            </h3>

                                            {{-- BADGES --}}
                                            <div class="mt-2 flex gap-2 text-xs font-semibold">
                                                <span class="px-2 py-0.5 rounded-full
                                                    {{ $type === 'video'
                                                        ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'
                                                        : 'bg-gray-100 text-gray-700 dark:bg-gray-800/30 dark:text-gray-300' }}">
                                                    {{ strtoupper($type) }}
                                                </span>

                                                <span class="px-2 py-0.5 rounded-full
                                                    bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                                    VERIFIED
                                                </span>
                                            </div>

                                            {{-- SNIPPET --}}
                                            <p class="mt-3 text-sm text-text-light/70 dark:text-text-dark/70 line-clamp-3">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($k->body), 120) }}
                                            </p>
                                        </div>

                                        {{-- ACTION --}}
                                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-poco-900
                                                    flex justify-between items-center">
                                            <a href="{{ auth()->check()
                                                        ? route('knowledge.show',$k)
                                                        : route('knowledge.public.show',$k) }}"
                                               class="px-4 py-1.5 rounded-xl font-semibold
                                                      border border-poco-500 text-poco-500
                                                      hover:bg-poco-500 hover:text-black">
                                                Buka
                                            </a>

                                            <small class="text-xs text-muted">
                                                {{ optional($k->created_at)->format('d M Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="mt-12 text-center text-muted dark:text-gray-500">
                        Belum ada knowledge publik.
                    </div>
                @endif
            @endif

            {{-- Tips / small content --}}
            <div class="mt-10 bg-white dark:bg-base-dark/90 rounded-xl p-6 shadow-sm">
                <h3 class="text-sm font-semibold text-text-light dark:text-text-dark mb-2">Catatan</h3>
                <ul class="list-disc list-inside text-sm text-muted dark:text-gray-400 space-y-1">
                    <li>Informasi publik hanya terlihat jika sudah diverifikasi.</li>
                    <li>Jika ingin melihat lebih banyak informasi, lakukan registrasi.</li>
                    <li>Gunakan kata kunci, #tag, tag:..., atau scope:... untuk hasil terbaik.</li>
                </ul>
            </div>

            <footer class="mt-8 text-center text-xs text-muted dark:text-gray-500">
                © {{ date('Y') }} {{ config('app.name','SPBE') }}
            </footer>
        </div>
    </main>

    <footer class="py-6 border-t border-gray-100 dark:border-poco-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-muted dark:text-gray-600">
            Built with Laravel · Knowledge System
        </div>
    </footer>
</body>
</html>