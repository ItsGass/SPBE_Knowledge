@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- ================= BACK ================= --}}
<a href="{{ request()->routeIs('knowledge.public.show')
            ? route('welcome')
            : route('knowledge.index') }}"
   class="flex items-center text-sm font-medium text-muted dark:text-gray-500
          hover:text-poco-600 dark:hover:text-poco-400 transition mb-6">
    ← Kembali
</a>


    {{-- ================= CARD ================= --}}
    <div class="bg-white dark:bg-base-dark/80 p-6 sm:p-8 rounded-2xl
                shadow-xl dark:shadow-2xl dark:shadow-poco-900/50 transition-all">

        {{-- ================= TITLE & META ================= --}}
        @php
    $statusKey = optional($knowledge->status)->key;
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
    {{ $statusKey === 'verified'
        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
    {{ optional($knowledge->status)->label ?? 'Draft' }}
</span>

        <div class="pb-4 border-b border-gray-100 dark:border-poco-900">
            <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark">
                {{ $knowledge->title }}
            </h1>

            @php
                $vis = $knowledge->visibility ?? 'internal';
                $type = $knowledge->type ?? 'pdf';
                $statusKey = optional($knowledge->status)->key;
            @endphp

            <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-muted dark:text-gray-500">
                <span class="font-semibold text-poco-600 dark:text-poco-400">
                    {{ $knowledge->scope->name ?? 'Uncategorized' }}
                </span>

                <span>•</span>

                <span class="font-semibold
                    {{ $statusKey === 'verified'
                        ? 'text-green-600 dark:text-green-400'
                        : 'text-yellow-600 dark:text-yellow-400' }}">
                    {{ optional($knowledge->status)->label ?? 'Draft' }}
                </span>

                <span>•</span>

                <span class="px-2 py-0.5 rounded-full text-xs font-bold
                    {{ $vis === 'public'
                        ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                        : 'bg-gray-100 text-gray-700 dark:bg-gray-800/30 dark:text-gray-300' }}">
                    {{ ucfirst($vis) }}
                </span>

                <span>•</span>

                <span class="px-2 py-0.5 rounded-full text-xs font-bold
                    {{ $type === 'video'
                        ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'
                        : 'bg-gray-100 text-gray-700 dark:bg-gray-800/30 dark:text-gray-300' }}">
                    {{ strtoupper($type) }}
                </span>

                <span>•</span>
                <span>{{ optional($knowledge->created_at)->format('d M Y') }}</span>
            </div>

            {{-- TAGS --}}
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach($knowledge->tags as $tag)
                    <span class="text-xs px-2 py-0.5 rounded-full
                                 bg-yellow-50 text-yellow-800
                                 dark:bg-[#1b1b00] dark:text-yellow-300">
                        #{{ $tag->name }}
                    </span>
                @endforeach
            </div>
        </div>

        

        {{-- ================= CONTENT ================= --}}
        <div class="mt-6 prose dark:prose-invert max-w-none
                    text-text-light/90 dark:text-text-dark/90">
            {!! nl2br(e($knowledge->body)) !!}
        </div>

        {{-- ================= RENDER BY TYPE ================= --}}
        <div class="mt-8">

            {{-- VIDEO --}}
            @if($knowledge->type === 'video' && $knowledge->youtube_url)
    @php
        preg_match('/(youtu\.be\/|v=)([^&]+)/', $knowledge->youtube_url, $m);
        $ytId = $m[2] ?? null;
    @endphp

    @if($ytId)
        <iframe
            class="w-full aspect-video rounded-xl shadow"
            src="https://www.youtube.com/embed/{{ $ytId }}"
            title="YouTube video"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
        </iframe>
    @else
        <div class="p-4 rounded-xl bg-gray-100 dark:bg-gray-800 text-sm text-muted">
            Link YouTube tidak valid
        </div>
    @endif
@endif


            {{-- PDF --}}
            @if($type === 'pdf' && $knowledge->attachment_path)
                <a href="{{ asset('storage/'.$knowledge->attachment_path) }}"
                   target="_blank"
                   class="inline-flex items-center mt-4 px-5 py-2.5 rounded-xl
                          font-bold bg-poco-500 hover:bg-poco-600 text-black transition">
                    📄 Download Dokumen
                </a>
            @endif
        </div>

        {{-- ================= RATING & COMMENTS ================= --}}
        @include('knowledge.partials.rating')
        @include('knowledge.partials.comments')

        {{-- ================= VERIFY & TOGGLE VISIBILITY ================= --}}
        @can('verify', $knowledge)
    <div class="flex gap-2">
        <form method="POST" action="{{ route('knowledge.verify', $knowledge) }}">
            @csrf
            <button class="px-4 py-2 rounded-xl bg-green-600 text-white">
                Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('knowledge.toggleVisibility', $knowledge) }}">
            @csrf
            <button class="px-4 py-2 rounded-xl bg-blue-600 text-white">
                {{ $knowledge->visibility === 'public' ? 'Jadikan Internal' : 'Publikasikan' }}
            </button>
        </form>
    </div>
@endcan


        {{-- ================= ACTION ================= --}}
        <div class="mt-8 pt-6 border-t border-gray-100 dark:border-poco-900 flex justify-end">
            @can('update', $knowledge)
                <a href="{{ route('knowledge.edit', $knowledge) }}"
                   class="px-4 py-2 rounded-xl font-semibold
                          bg-gray-200 dark:bg-gray-700
                          hover:bg-gray-300 dark:hover:bg-gray-600">
                    Edit
                </a>
            @endcan
        </div>

    </div>
</div>
@endsection
