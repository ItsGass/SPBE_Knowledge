@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark">
                Tags
            </h1>
            <p class="mt-1 text-sm text-muted dark:text-gray-400">
                Kumpulan tag populer. Klik tag untuk melihat semua knowledge terkait.
            </p>
        </div>

        {{-- SEARCH (DESKTOP) --}}
        <form method="get" action="{{ route('tags.index') }}" class="hidden sm:flex items-center">
            <input
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari tag..."
                class="px-4 py-2 rounded-l-xl border border-gray-200 dark:border-gray-700
                       bg-white dark:bg-base-dark
                       text-text-light dark:text-text-dark
                       focus:outline-none focus:ring-2 focus:ring-poco-400"
            >
            <button
                type="submit"
                class="px-4 py-2 rounded-r-xl bg-poco-500 hover:bg-poco-600
                       text-black font-semibold">
                Cari
            </button>
        </form>
    </div>

    {{-- SEARCH (MOBILE) --}}
    <div class="sm:hidden mb-6">
        <form method="get" action="{{ route('tags.index') }}">
            <div class="flex">
                <input
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="Cari tag..."
                    class="flex-1 px-4 py-2 rounded-l-xl border border-gray-200 dark:border-gray-700
                           bg-white dark:bg-base-dark
                           text-text-light dark:text-text-dark"
                >
                <button
                    type="submit"
                    class="px-4 py-2 rounded-r-xl bg-poco-500 hover:bg-poco-600
                           text-black font-semibold">
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- ================= TAG LIST ================= --}}
    <div class="bg-white dark:bg-base-dark/80
                p-6 sm:p-8 rounded-2xl
                shadow-soft dark:shadow-xl
                border border-gray-100 dark:border-poco-900">

        @if($tags->count())
            <div class="flex flex-wrap gap-3">
                @foreach($tags as $tag)
                    <a href="{{ route('tags.show', $tag->slug) }}"
                       class="group inline-flex items-center gap-2
                              px-4 py-2 rounded-full
                              bg-gray-100 dark:bg-gray-900
                              border border-gray-200 dark:border-gray-800
                              hover:bg-poco-500 dark:hover:bg-poco-600
                              hover:text-black
                              transition">

                        {{-- HASH --}}
                        <span class="font-extrabold
                                     text-poco-500 group-hover:text-black">
                            #
                        </span>

                        {{-- TAG NAME --}}
                        <span class="font-semibold text-text-light dark:text-text-dark
                                     group-hover:text-black">
                            {{ $tag->name }}
                        </span>

                        {{-- COUNT --}}
                        <span class="ml-1 text-xs font-bold
                                     px-2 py-0.5 rounded-full
                                     bg-poco-100 text-poco-700
                                     group-hover:bg-black group-hover:text-poco-400">
                            {{ $tag->count }}
                        </span>
                    </a>
                @endforeach
            </div>

            {{-- PAGINATION --}}
            <div class="mt-8">
                {{ $tags->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-10 text-muted dark:text-gray-400">
                Tidak ada tag ditemukan.
            </div>
        @endif
    </div>

</div>
@endsection
