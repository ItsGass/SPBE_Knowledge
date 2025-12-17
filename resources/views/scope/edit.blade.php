@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    
    <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark mb-6 pb-4 border-b border-gray-200 dark:border-poco-900">
        Edit Scope
    </h1>

    <!-- Kartu Formulir -->
    <div class="bg-white dark:bg-base-dark/80 p-6 sm:p-8 rounded-2xl shadow-xl dark:shadow-2xl dark:shadow-poco-900/50 transition-all duration-500">
        
        <form action="{{ route('scope.update', $scope->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Input Name --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-text-light dark:text-text-dark mb-1">
                    Nama Scope
                </label>

                <input type="text" 
                       id="name"
                       name="name" 
                       value="{{ old('name', $scope->name) }}" 
                       required
                       placeholder="Contoh: Tata Kelola"
                       class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-700 
                              bg-gray-50 dark:bg-base-dark text-text-light dark:text-text-dark text-base
                              focus:ring-poco-500 focus:border-poco-500 transition-colors">

                {{-- Error Message --}}
                @error('name')
                    <div class="text-red-500 dark:text-red-400 text-sm mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="pt-4 flex items-center justify-end border-t border-gray-100 dark:border-poco-900">
                
                {{-- Tombol Update (Aksen) --}}
                <button type="submit"
                        class="px-5 py-2.5 font-bold rounded-xl transition duration-300 shadow-lg 
                               bg-poco-500 hover:bg-poco-600 text-black 
                               dark:bg-poco-600 dark:hover:bg-poco-700 
                               focus:outline-none focus:ring-4 focus:ring-poco-300">
                    Update Scope
                </button>

                {{-- Tombol Batal (Outline) --}}
                <a href="{{ route('scope.index') }}"
                   class="ml-3 px-5 py-2.5 font-semibold rounded-xl transition duration-300 
                          border border-muted/50 dark:border-muted/30 
                          text-muted hover:text-black dark:text-gray-400 
                          hover:bg-gray-100 dark:hover:bg-poco-900">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>
@endsection