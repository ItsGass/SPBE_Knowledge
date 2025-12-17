@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Bagian Utama (Halo Pengguna & Ringkasan) -->
    <div class="bg-white dark:bg-base-dark/80 p-6 sm:p-8 rounded-2xl shadow-xl dark:shadow-2xl dark:shadow-poco-900/50 transition-all duration-500">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            
            <!-- Salam Pengguna -->
            <div>
                <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark">
                    Halo, <span class="text-poco-600 dark:text-poco-400">{{ $user->name }}</span>!
                </h1>
                <div class="text-sm text-muted dark:text-gray-500 mt-1">
                    Role: <strong class="uppercase">{{ ucfirst($user->role) }}</strong>
                </div>
            </div>

            
        </div>

         <!-- Kartu Statistik -->
<div class="mt-8 space-y-6">

    {{-- BARIS 1 — Total Knowledge (full width) --}}
    <div class="grid grid-cols-1">
        <div class="p-5 rounded-xl shadow-soft dark:shadow-xl dark:shadow-poco-900/50 
                    transition-all duration-300 border border-gray-100 dark:border-poco-900 
                    bg-gray-50 dark:bg-base-dark/50">
            <div class="text-lg font-semibold text-text-light dark:text-text-dark">Total Knowledge</div>
            <div class="text-4xl font-extrabold text-poco-600 dark:text-poco-400 mt-2">
                {{ $totalKnowledge ?? \App\Models\Knowledge::count() }}
            </div>
            <div class="text-sm text-muted dark:text-gray-500 mt-3">Semua entri yang tersedia</div>
        </div>
    </div>

    {{-- BARIS 2 — 4 kartu statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">

        {{-- Kartu 2: Terverifikasi --}}
        <div class="p-5 rounded-xl shadow-soft dark:shadow-xl dark:shadow-poco-900/50 
                    transition-all duration-300 border border-gray-100 dark:border-poco-900 
                    bg-gray-50 dark:bg-base-dark/50">
            <div class="text-lg font-semibold text-text-light dark:text-text-dark">Terverifikasi</div>
            <div class="text-4xl font-extrabold text-green-600 dark:text-green-400 mt-2">
                {{ $verifiedCount ?? \App\Models\Knowledge::whereHas('status', fn($q)=> $q->where('key','verified'))->count() }}
            </div>
            <div class="text-sm text-muted dark:text-gray-500 mt-3">Dokumen yang sudah diverifikasi</div>
        </div>

        {{-- Kartu 3: Draft --}}
        <div class="p-5 rounded-xl shadow-soft dark:shadow-xl dark:shadow-poco-900/50 
                    transition-all duration-300 border border-gray-100 dark:border-poco-900 
                    bg-gray-50 dark:bg-base-dark/50">
            <div class="text-lg font-semibold text-text-light dark:text-text-dark">Draft</div>
            <div class="text-4xl font-extrabold text-yellow-600 dark:text-yellow-400 mt-2">
                {{ $draftCount ?? \App\Models\Knowledge::whereHas('status', fn($q)=> $q->where('key','draft'))->count() }}
            </div>
            <div class="text-sm text-muted dark:text-gray-500 mt-3">Dokumen yang belum dipublikasikan</div>
        </div>

        {{-- Kartu 4: Publik --}}
        <div class="p-5 rounded-xl shadow-soft dark:shadow-xl dark:shadow-poco-900/50 
                    transition-all duration-300 border border-gray-100 dark:border-poco-900 
                    bg-gray-50 dark:bg-base-dark/50">
            <div class="text-lg font-semibold text-text-light dark:text-text-dark">Publik</div>
            <div class="text-4xl font-extrabold text-blue-600 dark:text-blue-400 mt-2">
                {{ $publicCount ?? \App\Models\Knowledge::where('visibility','public')->whereHas('status', fn($q)=> $q->where('key','verified'))->count() }}
            </div>
            <div class="text-sm text-muted dark:text-gray-500 mt-3">Tersedia untuk publik</div>
        </div>

        {{-- Kartu 5: Internal --}}
        <div class="p-5 rounded-xl shadow-soft dark:shadow-xl dark:shadow-poco-900/50 
                    transition-all duration-300 border border-gray-100 dark:border-poco-900 
                    bg-gray-50 dark:bg-base-dark/50">
            <div class="text-lg font-semibold text-text-light dark:text-text-dark">Internal</div>
            <div class="text-4xl font-extrabold text-indigo-600 dark:text-indigo-400 mt-2">
                {{ $internalCount ?? \App\Models\Knowledge::where('visibility','internal')->count() }}
            </div>
            <div class="text-sm text-muted dark:text-gray-500 mt-3">Hanya untuk pengguna internal</div>
        </div>

    </div>
</div>


    </div>

    <!-- Bagian Terbaru 
    <div class="mt-10">
        <h3 class="text-2xl font-extrabold text-text-light dark:text-text-dark mb-6">Knowledge Terbaru</h3>
        
        <div class="grid grid-cols-1 gap-4">
            @foreach(\App\Models\Knowledge::latest()->take(6)->get() as $k)
                <div class="bg-white dark:bg-base-dark/80 p-5 rounded-xl shadow-soft dark:shadow-poco-900/50 flex justify-between items-center hover:shadow-lg transition-all duration-300 border border-gray-100 dark:border-poco-900">
                    <div>
                        <div class="font-bold text-lg text-poco-700 dark:text-poco-400">{{ $k->title }}</div>
                        <div class="text-xs mt-1 text-muted dark:text-gray-500">
                            <span class="font-medium text-poco-600 dark:text-poco-400">{{ $k->scope->name ?? 'Uncategorized' }}</span> 
                            <span class="mx-1">•</span> 
                            {{ $k->status->label ?? 'Draft' }}
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('knowledge.show',$k) }}" 
                           class="px-4 py-1.5 font-semibold rounded-lg transition duration-300 
                                  border border-poco-500 text-poco-500 text-sm
                                  hover:bg-poco-500 hover:text-black 
                                  dark:border-poco-600 dark:text-poco-600 dark:hover:bg-poco-600 dark:hover:text-black">
                            Buka
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
    </div>-->

</div>
@endsection