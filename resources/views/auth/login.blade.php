@extends('layouts.app')

@section('content')
<style>
    .bubbles-bg {
        position: fixed;
        inset: 0;
        overflow: hidden;
        z-index: -1;
        filter: blur(4px);
        opacity: 0.8;
    }

    .bubble {
        position: absolute;
        bottom: -200px;
        background: rgba(255, 212, 0, 0.4);
        border-radius: 50%;
        box-shadow: 0 0 15px rgba(255, 212, 0, 0.8);
        animation: animateBubble 25s linear infinite;
    }

    @keyframes animateBubble {
        from { transform: translateY(0) rotate(0deg); opacity: .8; }
        to { transform: translateY(-1000px) rotate(720deg); opacity: 0; }
    }

    .bubble:nth-child(1){left:25%;width:80px;height:80px}
    .bubble:nth-child(2){left:10%;width:20px;height:20px;animation-duration:12s}
    .bubble:nth-child(3){left:70%;width:20px;height:20px}
    .bubble:nth-child(4){left:40%;width:60px;height:60px;animation-duration:18s}
    .bubble:nth-child(5){left:65%;width:20px;height:20px}
</style>

<ul class="bubbles-bg">
    <li class="bubble"></li>
    <li class="bubble"></li>
    <li class="bubble"></li>
    <li class="bubble"></li>
    <li class="bubble"></li>
</ul>

<div class="flex items-center justify-center min-h-[calc(100vh-64px-72px)] py-12">
    <div class="w-full max-w-md">

        <div class="p-6 sm:p-8 bg-white dark:bg-base-dark/80
                    shadow-2xl dark:shadow-poco-900/50
                    rounded-2xl backdrop-blur-sm">

            <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark">
                Masuk
            </h1>
            <p class="text-sm text-muted dark:text-gray-500 mt-2">
                Masuk untuk mengelola knowledge Anda.
            </p>

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-muted dark:text-gray-400 mb-1">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           required autofocus
                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-700
                                  bg-gray-50 dark:bg-base-dark
                                  text-text-light dark:text-text-dark text-sm
                                  focus:ring-poco-500 focus:border-poco-500
                                  transition
                                  @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-muted dark:text-gray-400 mb-1">
                        Password
                    </label>
                    <input id="password" type="password" name="password" required
                           class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-700
                                  bg-gray-50 dark:bg-base-dark
                                  text-text-light dark:text-text-dark text-sm
                                  focus:ring-poco-500 focus:border-poco-500
                                  transition
                                  @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember | Eye | Forgot -->
                <div class="flex items-center justify-between pt-2">

                    <!-- Remember -->
                    <label class="flex items-center gap-2 text-sm text-muted dark:text-gray-400">
                        <input type="checkbox" name="remember"
                               class="h-4 w-4 rounded border-gray-300
                                      text-poco-600 focus:ring-poco-500
                                      bg-gray-50 dark:bg-gray-700">
                        Remember me
                    </label>

                    <!-- Eye Toggle (SVG VECTOR) -->
                    <button type="button" id="togglePassword"
                        class="flex items-center justify-center
                               w-9 h-9 rounded-full
                               text-gray-500 dark:text-gray-400
                               hover:text-poco-600 dark:hover:text-poco-400
                               hover:bg-gray-100 dark:hover:bg-gray-800
                               transition"
                        title="Tampilkan / Sembunyikan password">

                        <!-- EYE CLOSED (default) -->
                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5" fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3 3l18 18"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M10.94 6.08A6.9 6.9 0 0112 6
                                     c4.48 0 8.27 2.94 9.54 7
                                     -.53 1.68-1.53 3.17-2.82 4.31"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M6.61 6.61C4.98 7.86 3.78 9.8 3 12
                                     c1.27 4.06 5.06 7 9.54 7
                                     1.3 0 2.55-.25 3.71-.7"/>
                        </svg>

                        <!-- EYE OPEN -->
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 hidden" fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5
                                     c4.478 0 8.268 2.943 9.542 7
                                     -1.274 4.057-5.064 7-9.542 7
                                     -4.477 0-8.268-2.943-9.542-7z"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>

                    <!-- Forgot -->
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-poco-600 dark:text-poco-400 hover:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full px-5 py-2.5 font-bold rounded-xl
                               bg-poco-500 hover:bg-poco-600
                               dark:bg-poco-600 dark:hover:bg-poco-700
                               text-black shadow-lg
                               focus:ring-4 focus:ring-poco-300
                               transition">
                    Masuk
                </button>
            </form>

            <p class="text-muted dark:text-gray-500 mt-6 text-sm text-center">
                Belum punya akun?
                <a href="{{ route('register') }}"
                   class="text-poco-600 dark:text-poco-400 hover:underline font-semibold">
                    Daftar di sini
                </a>
            </p>
        </div>
    </div>
</div>

<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    toggle.addEventListener('click', () => {
        const isHidden = password.type === 'password';

        password.type = isHidden ? 'text' : 'password';
        eyeOpen.classList.toggle('hidden', !isHidden);
        eyeClosed.classList.toggle('hidden', isHidden);
    });
</script>
@endsection
