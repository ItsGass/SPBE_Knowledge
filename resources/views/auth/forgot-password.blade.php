@extends('layouts.app')

@section('content')
<style>
/* =========================
   BUBBLE BACKGROUND
========================= */
.bubbles-bg {
    position: fixed;
    inset: 0;
    overflow: hidden;
    z-index: -1;
    filter: blur(4px);
    opacity: .85;
}

.bubble {
    position: absolute;
    bottom: -200px;
    background: rgba(255, 212, 0, 0.4);
    border-radius: 50%;
    animation: bubbleMove 25s linear infinite;
    box-shadow: 0 0 15px rgba(255, 212, 0, .8);
}

@keyframes bubbleMove {
    from { transform: translateY(0) rotate(0); opacity: .8; }
    to   { transform: translateY(-1200px) rotate(720deg); opacity: 0; }
}

.bubble:nth-child(1){left:10%;width:40px;height:40px;animation-duration:14s}
.bubble:nth-child(2){left:20%;width:90px;height:90px;animation-duration:22s}
.bubble:nth-child(3){left:35%;width:25px;height:25px;animation-duration:18s}
.bubble:nth-child(4){left:50%;width:120px;height:120px;animation-duration:26s}
.bubble:nth-child(5){left:65%;width:30px;height:30px;animation-duration:20s}
.bubble:nth-child(6){left:75%;width:150px;height:150px;animation-duration:30s}
.bubble:nth-child(7){left:85%;width:50px;height:50px;animation-duration:16s}
</style>

<ul class="bubbles-bg">
    <li class="bubble"></li><li class="bubble"></li><li class="bubble"></li>
    <li class="bubble"></li><li class="bubble"></li><li class="bubble"></li>
    <li class="bubble"></li>
</ul>

<div class="flex items-center justify-center min-h-[calc(100vh-64px-72px)] py-12">
    <div class="w-full max-w-md">

        <!-- CARD -->
        <div class="p-6 sm:p-8
                    bg-white dark:bg-base-dark/80
                    shadow-2xl dark:shadow-poco-900/60
                    rounded-2xl backdrop-blur-sm relative z-10">

            <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark">
                Lupa Password
            </h1>

            <p class="mt-2 text-sm text-muted dark:text-gray-500">
                Masukkan email Anda. Kami akan mengirim link untuk reset password.
            </p>

            {{-- STATUS SUKSES --}}
            @if (session('status'))
                <div class="mt-4 p-3 rounded-lg bg-green-100 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-5">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="block text-sm font-medium text-muted dark:text-gray-400 mb-1">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full p-2.5 rounded-lg
                               border border-gray-300 dark:border-gray-700
                               bg-gray-50 dark:bg-base-dark
                               text-text-light dark:text-text-dark text-sm
                               focus:ring-poco-500 focus:border-poco-500">

                    {{-- ERROR EMAIL (PASTI MUNCUL) --}}
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- BUTTON -->
                <button type="submit"
                        class="w-full px-5 py-2.5 rounded-xl font-bold
                               bg-poco-500 hover:bg-poco-600
                               text-black shadow-lg transition
                               focus:outline-none focus:ring-4 focus:ring-poco-300">
                    Kirim Link Reset
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-muted dark:text-gray-500">
                Ingat password?
                <a href="{{ route('login') }}"
                   class="font-semibold text-poco-600 dark:text-poco-400 hover:underline">
                    Kembali ke login
                </a>
            </p>

        </div>
    </div>
</div>
@endsection
