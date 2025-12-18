@extends('layouts.app')

@section('content')
<style>
    .bubbles-bg {
        position: fixed;
        inset: 0;
        overflow: hidden;
        z-index: -1;
        
        opacity: 0.8;
    }

    .bubble {
        position: absolute;
        bottom: -200px;
        background: rgba(255, 212, 0, 0.4);
        border-radius: 50%;
        box-shadow: 0 0 15px rgba(255, 212, 0, 0.8);
        animation: animateBubble 10s linear infinite;
    }
    .dark .bubble {
        background: rgba(59, 130, 246, 0.25);
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.6);
    }
    @keyframes animateBubble {
        from { transform: translateY(0) rotate(0deg); opacity: .8; }
        to { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
    }

.bubble:nth-child(1)  { left: 5%;  width: 45px;  height: 45px;  animation-duration: 16s; animation-delay: -2s; }
.bubble:nth-child(2)  { left: 12%; width: 80px;  height: 80px;  animation-duration: 20s; animation-delay: -8s; }
.bubble:nth-child(3)  { left: 20%; width: 60px;  height: 60px;  animation-duration: 14s; animation-delay: -4s; }
.bubble:nth-child(4)  { left: 28%; width: 130px; height: 130px; animation-duration: 26s; animation-delay: -12s; }
.bubble:nth-child(5)  { left: 36%; width: 75px;  height: 75px;  animation-duration: 18s; animation-delay: -6s; }

.bubble:nth-child(6)  { left: 44%; width: 55px;  height: 55px;  animation-duration: 15s; animation-delay: -9s; }
.bubble:nth-child(7)  { left: 52%; width: 100px; height: 100px; animation-duration: 22s; animation-delay: -3s; }
.bubble:nth-child(8)  { left: 60%; width: 65px;  height: 65px;  animation-duration: 17s; animation-delay: -11s; }
.bubble:nth-child(9)  { left: 68%; width: 160px; height: 160px; animation-duration: 30s; animation-delay: -7s; }
.bubble:nth-child(10) { left: 75%; width: 85px;  height: 85px;  animation-duration: 19s; animation-delay: -14s; }

.bubble:nth-child(11) { left: 82%; width: 60px;  height: 60px;  animation-duration: 16s; animation-delay: -5s; }
.bubble:nth-child(12) { left: 88%; width: 115px; height: 115px; animation-duration: 24s; animation-delay: -10s; }
.bubble:nth-child(13) { left: 92%; width: 50px;  height: 50px;  animation-duration: 14s; animation-delay: -1s; }
.bubble:nth-child(14) { left: 15%; width: 140px; height: 140px; animation-duration: 28s; animation-delay: -16s; }
.bubble:nth-child(15) { left: 33%; width: 90px;  height: 90px;  animation-duration: 20s; animation-delay: -13s; }

.bubble:nth-child(16) { left: 47%; width: 65px;  height: 65px;  animation-duration: 16s; animation-delay: -6s; }
.bubble:nth-child(17) { left: 58%; width: 95px;  height: 95px;  animation-duration: 21s; animation-delay: -9s; }
.bubble:nth-child(18) { left: 66%; width: 75px;  height: 75px;  animation-duration: 18s; animation-delay: -4s; }
.bubble:nth-child(19) { left: 73%; width: 145px; height: 145px; animation-duration: 26s; animation-delay: -11s; }
.bubble:nth-child(20) { left: 90%; width: 58px;  height: 58px;  animation-duration: 15s; animation-delay: -8s; }



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