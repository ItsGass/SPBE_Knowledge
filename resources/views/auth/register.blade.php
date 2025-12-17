@extends('layouts.app')

@section('content')
<style>
    /* ======================================
    CSS Kustom untuk Animasi Latar Belakang
    ======================================
    */
    .bubbles-bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1; 
        filter: blur(4px);
        opacity: 0.8;
    }

    .bubble {
        position: absolute;
        list-style: none;
        display: block;
        /* Warna latar belakang poco-500 dengan transparansi */
        background: rgba(255, 212, 0, 0.4); 
        animation: animateBubble 25s linear infinite;
        bottom: -200px;
        border-radius: 50%;
        /* Bayangan kuning untuk efek bersinar */
        box-shadow: 0 0 15px rgba(255, 212, 0, 0.8);
    }

    /* Keyframes Animasi */
    @keyframes animateBubble {
        0% {
            transform: translateY(0) rotate(0deg);
            opacity: 0.8;
        }
        100% {
            transform: translateY(-1000px) rotate(720deg);
            opacity: 0;
        }
    }

    /* Variasi ukuran, posisi, dan kecepatan gelembung (Sama dengan Login) */
    .bubble:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
    .bubble:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
    .bubble:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
    .bubble:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
    .bubble:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
    .bubble:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
    .bubble:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
    .bubble:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
    .bubble:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
    .bubble:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }
    .bubble:nth-child(11) { left: 5%; width: 50px; height: 50px; animation-delay: 10s; animation-duration: 20s; }
    .bubble:nth-child(12) { left: 90%; width: 90px; height: 90px; animation-delay: 8s; animation-duration: 22s; }
</style>

{{-- Latar Belakang Animasi Gelembung --}}
<ul class="bubbles-bg">
    <li class="bubble"></li>
    <li class="bubble"></li>
    <li class="bubble"></li>
    <li class="bubble"></li>
    <li class="bubble"></li>
    <li class="bubble"></li>
    <li class="bubble"></li>
    <li class="bubble"></li>
    <li class="bubble"></li>
    <li class="bubble"></li>
    <li class="bubble"></li>
    <li class="bubble"></li>
</ul>

<div class="flex items-center justify-center min-h-[calc(100vh-64px-72px)] py-12">
    <div class="w-full max-w-lg">
        
        <!-- Kartu Formulir Daftar -->
        <div class="p-6 sm:p-8 
                    bg-white dark:bg-base-dark/80 
                    shadow-2xl dark:shadow-2xl dark:shadow-poco-900/50 
                    rounded-2xl transition-all duration-500 
                    backdrop-blur-sm relative z-10">
            
            <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark">Daftar</h1>
            <p class="text-sm text-muted dark:text-gray-500 mt-2">Buat akun untuk mengakses sistem.</p>

            <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
                @csrf
                
                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-muted dark:text-gray-400 mb-1">Nama</label>
                    <input name="name" 
                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 
                                  bg-gray-50 dark:bg-base-dark text-text-light dark:text-text-dark text-sm
                                  focus:ring-poco-500 focus:border-poco-500 transition-colors" 
                           value="{{ old('name') }}" 
                           required autofocus>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-muted dark:text-gray-400 mb-1">Email</label>
                    <input name="email" type="email"
                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 
                                  bg-gray-50 dark:bg-base-dark text-text-light dark:text-text-dark text-sm
                                  focus:ring-poco-500 focus:border-poco-500 transition-colors" 
                           value="{{ old('email') }}" 
                           required>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-muted dark:text-gray-400 mb-1">Password</label>
                    <input name="password" type="password" 
                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 
                                  bg-gray-50 dark:bg-base-dark text-text-light dark:text-text-dark text-sm
                                  focus:ring-poco-500 focus:border-poco-500 transition-colors" 
                           required>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-medium text-muted dark:text-gray-400 mb-1">Konfirmasi Password</label>
                    <input name="password_confirmation" type="password" 
                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 
                                  bg-gray-50 dark:bg-base-dark text-text-light dark:text-text-dark text-sm
                                  focus:ring-poco-500 focus:border-poco-500 transition-colors" 
                           required>
                </div>

                {{-- Tombol Daftar --}}
                <div>
                    <button class="w-full px-5 py-2.5 font-bold rounded-xl transition duration-300 shadow-lg 
                                   bg-poco-500 hover:bg-poco-600 text-black 
                                   dark:bg-poco-600 dark:hover:bg-poco-700 
                                   focus:outline-none focus:ring-4 focus:ring-poco-300" 
                            type="submit">
                        Daftar
                    </button>
                </div>
            </form>

            <p class="text-muted dark:text-gray-500 mt-6 text-sm text-center">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-poco-600 dark:text-poco-400 hover:underline font-semibold">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>
</div>
@endsection