@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    
    <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark pb-4 border-b border-gray-200 dark:border-poco-900">
        Tambah User Baru
    </h1>
    <p class="text-sm text-muted dark:text-gray-500 mt-2">Buat akun baru untuk Admin, User, atau Super Admin.</p>

    <!-- Kartu Formulir -->
    <div class="bg-white dark:bg-base-dark/80 p-6 sm:p-8 rounded-2xl shadow-xl dark:shadow-2xl dark:shadow-poco-900/50 transition-all duration-500 mt-6">
        
        <form action="{{ route('user-management.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- NAMA --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-text-light dark:text-text-dark mb-1">Nama Lengkap</label>
                <input 
                    type="text"
                    id="name"
                    name="name" 
                    value="{{ old('name') }}" 
                    class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-700 
                           bg-gray-50 dark:bg-base-dark text-text-light dark:text-text-dark text-base
                           focus:ring-poco-500 focus:border-poco-500 transition-colors"
                    placeholder="Masukkan nama"
                    required
                />
                @error('name') <div class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label for="email" class="block text-sm font-semibold text-text-light dark:text-text-dark mb-1">Email</label>
                <input 
                    type="email"
                    id="email"
                    name="email" 
                    value="{{ old('email') }}" 
                    class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-700 
                           bg-gray-50 dark:bg-base-dark text-text-light dark:text-text-dark text-base
                           focus:ring-poco-500 focus:border-poco-500 transition-colors"
                    placeholder="email@example.com"
                    required
                />
                @error('email') <div class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- PASSWORD --}}
            <div>
                <label for="password" class="block text-sm font-semibold text-text-light dark:text-text-dark mb-1">Password</label>
                <input 
                    type="password" 
                    id="password"
                    name="password"
                    class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-700 
                           bg-gray-50 dark:bg-base-dark text-text-light dark:text-text-dark text-base
                           focus:ring-poco-500 focus:border-poco-500 transition-colors"
                    placeholder="Minimal 8 karakter"
                    required
                />
                @error('password') <div class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- PASSWORD CONFIRM --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-text-light dark:text-text-dark mb-1">Konfirmasi Password</label>
                <input 
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-700 
                           bg-gray-50 dark:bg-base-dark text-text-light dark:text-text-dark text-base
                           focus:ring-poco-500 focus:border-poco-500 transition-colors"
                    placeholder="Ulangi password"
                    required
                />
            </div>

            {{-- ROLE --}}
            <div>
                <label for="role" class="block text-sm font-semibold text-text-light dark:text-text-dark mb-1">Role</label>
                <select name="role" id="role"
                        class="w-full p-3 rounded-lg border border-gray-300 dark:border-gray-700 
                               bg-gray-50 dark:bg-base-dark text-text-light dark:text-text-dark text-base
                               focus:ring-poco-500 focus:border-poco-500 transition-colors"
                               required>
                    <option value="">-- Pilih Role --</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="verifikator" {{ old('role') == 'verifikator' ? 'selected' : '' }}>Verifikator</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
                @error('role') <div class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- BUTTONS --}}
            <div class="pt-4 flex items-center justify-end border-t border-gray-100 dark:border-poco-900">
                
                {{-- Tombol Simpan (Aksen) --}}
                <button type="submit"
                        class="px-5 py-2.5 font-bold rounded-xl transition duration-300 shadow-lg 
                               bg-poco-500 hover:bg-poco-600 text-black 
                               dark:bg-poco-600 dark:hover:bg-poco-700 
                               focus:outline-none focus:ring-4 focus:ring-poco-300">
                    Simpan User
                </button>
                
                {{-- Tombol Batal (Outline) --}}
                <a href="{{ route('user-management.index') }}"
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