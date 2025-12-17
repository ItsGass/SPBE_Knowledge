@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            Edit User
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Perbarui informasi user.
        </p>
    </div>

    {{-- Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

        <form action="{{ route('user-management.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- NAMA --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Nama Lengkap
                </label>
                <input 
                    name="name" 
                    value="{{ old('name', $user->name) }}" 
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100
                           focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                    placeholder="Masukkan nama"
                />
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Email
                </label>
                <input 
                    type="email"
                    name="email" 
                    value="{{ old('email', $user->email) }}" 
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100
                           focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                />
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ROLE --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Role
                </label>
                <select 
                    name="role" 
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100
                           focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                >
                    <option value="user" {{ $user->role==='user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ $user->role==='admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ $user->role==='super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
                @error('role')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Password Baru 
                    <span class="text-xs text-gray-500">(opsional)</span>
                </label>
                <input 
                    type="password" 
                    name="password"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100
                           focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                    placeholder="Isi jika ingin mengganti password"
                />
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- CONFIRM --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Konfirmasi Password Baru
                </label>
                <input 
                    type="password"
                    name="password_confirmation"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100
                           focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                />
            </div>

            {{-- BUTTON --}}
            <div class="flex items-center gap-3">
                <button 
                    type="submit" 
                    class="px-5 py-2.5 rounded-lg bg-yellow-400 hover:bg-yellow-500 
                           text-gray-900 font-semibold transition"
                >
                    Simpan
                </button>

                <a 
                    href="{{ route('user-management.index') }}" 
                    class="px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600
                           text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                >
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
