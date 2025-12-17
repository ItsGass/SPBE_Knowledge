@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center
                pb-6 border-b border-gray-200 dark:border-poco-900 gap-4">

        <div>
            <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark">
                Knowledge Base
            </h1>

            {{-- FILTER VISIBILITY --}}
            @php
                $curVis = request('visibility');
                $baseUrl = url()->current();
            @endphp

            <div class="mt-3 flex items-center gap-3 text-sm">
                @foreach([null=>'Semua','public'=>'Publik','internal'=>'Internal'] as $v=>$label)
                    <a href="{{ $v ? request()->fullUrlWithQuery(['visibility'=>$v]) : $baseUrl }}"
                       class="px-3 py-1 rounded-md font-semibold
                       {{ $curVis===$v || (is_null($v)&&is_null($curVis))
                            ? 'bg-poco-600 text-black'
                            : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- SEARCH & ADD --}}
        <div class="flex items-center gap-3">
            <form method="get" class="flex items-center">
                <input name="q" value="{{ request('q') }}"
                       placeholder="Cari knowledge..."
                       class="px-3 py-1 rounded-l-md border border-gray-200
                              dark:border-gray-700 bg-white dark:bg-base-dark text-sm">
                <button class="px-3 py-1 rounded-r-md bg-poco-500 font-semibold text-black">
                    Cari
                </button>
            </form>

            @if(auth()->check() && in_array(auth()->user()->role,['admin','super_admin']))
                <a href="{{ route('knowledge.create') }}"
                   class="px-5 py-2.5 font-bold rounded-xl shadow-lg
                          bg-poco-500 hover:bg-poco-600 text-black
                          flex items-center gap-2">
                    ➕ Tambah Knowledge
                </a>
            @endif
        </div>
    </div>

    {{-- ================= LIST ================= --}}
    <div class="mt-8">
        @if($knowledges->count())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($knowledges as $knowledge)
                    @php
                        $type = $knowledge->type ?? 'pdf';
                        $vis  = $knowledge->visibility ?? 'internal';
                        $statusKey = optional($knowledge->status)->key;
                        $statusLabel = optional($knowledge->status)->label ?? 'Draft';
                    @endphp

                    <div class="bg-white dark:bg-base-dark/80 rounded-2xl
                                overflow-hidden shadow-soft dark:shadow-xl
                                flex flex-col">

                        {{-- THUMBNAIL --}}
                        @if($knowledge->thumbnail)
                            <img src="{{ asset('storage/'.$knowledge->thumbnail) }}"
                                 class="h-40 w-full object-cover">
                        @else
                            <div class="h-40 flex items-center justify-center
                                        bg-gray-100 dark:bg-gray-900 text-gray-400 font-bold">
                                {{ strtoupper($type) }}
                            </div>
                        @endif

                        <div class="p-6 flex flex-col justify-between h-full">
                            <div>
                                <h3 class="font-extrabold text-lg text-poco-700 dark:text-poco-400">
                                    {{ $knowledge->title }}
                                </h3>

                                {{-- META --}}
                                <div class="mt-2 flex flex-wrap gap-2 text-xs font-semibold">

                                    {{-- STATUS --}}
                                    <span class="px-2 py-0.5 rounded-full
                                        {{ $statusKey === 'verified'
                                            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                            : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                        {{ $statusLabel }}
                                    </span>

                                    {{-- TYPE --}}
                                    <span class="px-2 py-0.5 rounded-full
                                        {{ $type==='video'
                                            ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300'
                                            : 'bg-gray-100 text-gray-700 dark:bg-gray-800/30 dark:text-gray-300' }}">
                                        {{ strtoupper($type) }}
                                    </span>

                                    {{-- VISIBILITY --}}
                                    <span class="px-2 py-0.5 rounded-full
                                        {{ $vis==='public'
                                            ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                            : 'bg-gray-100 text-gray-700 dark:bg-gray-800/30 dark:text-gray-300' }}">
                                        {{ ucfirst($vis) }}
                                    </span>
                                </div>

                                <p class="mt-3 text-sm text-text-light/70 dark:text-text-dark/70 line-clamp-3">
                                    {{ Str::limit(strip_tags($knowledge->body),120) }}
                                </p>

                                {{-- TAGS --}}
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($knowledge->tags as $t)
                                        <span class="text-xs px-2 py-0.5 rounded-full
                                                     bg-yellow-50 text-yellow-800
                                                     dark:bg-[#1b1b00] dark:text-yellow-300">
                                            #{{ $t->name }}
                                        </span>
                                    @endforeach
                                </div>

                                {{-- STATS --}}
                                <div class="mt-4 flex gap-4 text-xs text-muted">
                                    <span>👁 {{ $knowledge->views_count }}</span>
                                    <span>⭐ {{ number_format($knowledge->ratings_avg_rating ?? 0,1) }}</span>
                                    <span>💬 {{ $knowledge->comments_count }}</span>
                                </div>
                            </div>

                            {{-- ACTIONS --}}
                            <div class="mt-6 pt-4 border-t border-gray-100 dark:border-poco-900
                                        flex justify-between items-center">

                                <a href="{{ route('knowledge.show',$knowledge) }}"
                                   class="px-4 py-1.5 rounded-xl font-semibold
                                          border border-poco-500 text-poco-500
                                          hover:bg-poco-500 hover:text-black">
                                    Buka
                                </a>

                                @if(auth()->check() && in_array(auth()->user()->role,['admin','super_admin']))
                                    <form method="POST"
                                          action="{{ route('knowledge.destroy',$knowledge) }}"
                                          onsubmit="return confirm('Yakin hapus knowledge ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-xs text-red-500 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $knowledges->withQueryString()->links() }}
            </div>
        @else
            <div class="bg-white dark:bg-base-dark/80 p-10 rounded-2xl text-center">
                <p class="text-xl font-medium">Tidak ada Knowledge.</p>
            </div>
        @endif
    </div>
</div>
@endsection
