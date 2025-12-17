@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark">
                #{{ $tag->name }}
            </h1>
            <p class="mt-1 text-sm text-muted dark:text-gray-400">
                {{ $tag->count }} knowledge terkait
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('tags.index') }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold
                      border border-gray-200 dark:border-gray-700
                      text-text-light dark:text-text-dark
                      hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                ← Kembali ke Tags
            </a>
        </div>
    </div>

    {{-- ================= SEARCH ================= --}}
    <form method="get" class="mb-8">
        <div class="flex gap-3 max-w-xl">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari dalam tag ini..."
                class="flex-1 px-4 py-2 rounded-xl
                       border border-gray-200 dark:border-gray-700
                       bg-white dark:bg-base-dark
                       text-text-light dark:text-text-dark
                       focus:outline-none focus:ring-2 focus:ring-poco-400"
            >
            <button
                type="submit"
                class="px-5 py-2 rounded-xl bg-poco-500 hover:bg-poco-600
                       text-black font-semibold">
                Cari
            </button>
        </div>
    </form>

    {{-- ================= LIST KNOWLEDGE ================= --}}
    @if($knowledges->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($knowledges as $k)
                @php
                    $type = $k->type ?? 'pdf';
                    $vis  = $k->visibility ?? 'internal';
                @endphp

                <div class="bg-white dark:bg-base-dark/80 rounded-2xl overflow-hidden
                            shadow-soft dark:shadow-xl
                            border border-gray-100 dark:border-poco-900
                            flex flex-col">

                    {{-- THUMBNAIL (DATABASE ONLY) --}}
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
                            <div class="mt-2 flex flex-wrap gap-2 text-xs font-semibold">

                                {{-- TYPE --}}
                                <span class="px-2 py-0.5 rounded-full
                                    {{ $type === 'video'
                                        ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'
                                        : 'bg-gray-100 text-gray-700 dark:bg-gray-800/30 dark:text-gray-300' }}">
                                    {{ strtoupper($type) }}
                                </span>

                                {{-- VISIBILITY --}}
                                <span class="px-2 py-0.5 rounded-full
                                    {{ $vis === 'public'
                                        ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                        : 'bg-gray-100 text-gray-700 dark:bg-gray-800/30 dark:text-gray-300' }}">
                                    {{ ucfirst($vis) }}
                                </span>

                                {{-- VERIFIED --}}
                                <span class="px-2 py-0.5 rounded-full
                                             bg-green-100 text-green-700
                                             dark:bg-green-900/30 dark:text-green-300">
                                    VERIFIED
                                </span>
                            </div>

                            {{-- SNIPPET --}}
                            <p class="mt-3 text-sm text-text-light/70 dark:text-text-dark/70 line-clamp-3">
                                {{ \Illuminate\Support\Str::limit(strip_tags($k->body), 140) }}
                            </p>

                            {{-- TAGS --}}
                            @if($k->tags->isNotEmpty())
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($k->tags as $t)
                                        <a href="{{ route('tags.show', $t->slug) }}"
                                           class="text-xs px-2 py-0.5 rounded-full
                                                  bg-yellow-50 text-yellow-800
                                                  dark:bg-[#1b1b00] dark:text-yellow-300">
                                            #{{ $t->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- FOOTER --}}
                        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-poco-900
                                    flex justify-between items-center">
                            <a href="{{ auth()->check()
                                        ? route('knowledge.show', $k)
                                        : route('knowledge.public.show', $k) }}"
                               class="px-4 py-1.5 rounded-xl font-semibold
                                      border border-poco-500 text-poco-500
                                      hover:bg-poco-500 hover:text-black transition">
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

        {{-- PAGINATION --}}
        <div class="mt-10">
            {{ $knowledges->withQueryString()->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-base-dark/80
                    p-10 rounded-2xl text-center
                    text-muted dark:text-gray-400
                    shadow-soft dark:shadow-xl">
            Tidak ada knowledge untuk tag ini.
        </div>
    @endif

</div>
@endsection
