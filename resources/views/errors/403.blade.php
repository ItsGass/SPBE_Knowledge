{{-- resources/views/errors/403.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    /* ======================================
       Latar Gelembung (mirip contoh)
       ====================================== */
    .bubbles-bg {
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1;
        filter: blur(4px);
        opacity: 0.9;
        background: linear-gradient(120deg, rgba(7,101,255,0.06), rgba(255,212,0,0.03));
    }
    .bubble {
        position: absolute;
        list-style: none;
        background: rgba(255, 212, 0, 0.35);
        animation: animateBubble 25s linear infinite;
        bottom: -220px;
        border-radius: 50%;
        box-shadow: 0 0 20px rgba(255, 212, 0, 0.85);
        backdrop-filter: blur(2px);
    }
    @keyframes animateBubble {
        0% {
            transform: translateY(0) rotate(0deg) scale(1);
            opacity: 0.9;
        }
        100% {
            transform: translateY(-1200px) rotate(720deg) scale(0.9);
            opacity: 0;
        }
    }
    .bubble:nth-child(1)  { left: 10%;  width: 90px;  height: 90px;  animation-delay: 0s;  }
    .bubble:nth-child(2)  { left: 25%;  width: 40px;  height: 40px;  animation-delay: 2s;  animation-duration: 15s; }
    .bubble:nth-child(3)  { left: 40%;  width: 60px;  height: 60px;  animation-delay: 4s;  }
    .bubble:nth-child(4)  { left: 55%;  width: 150px; height: 150px; animation-delay: 1s;  animation-duration: 18s; }
    .bubble:nth-child(5)  { left: 70%;  width: 30px;  height: 30px;  animation-delay: 6s;  }
    .bubble:nth-child(6)  { left: 82%;  width: 120px; height: 120px; animation-delay: 3s;  animation-duration: 22s; }
    .bubble:nth-child(7)  { left: 5%;   width: 50px;  height: 50px;  animation-delay: 10s; animation-duration: 20s; }
    .bubble:nth-child(8)  { left: 90%;  width: 80px;  height: 80px;  animation-delay: 8s;  animation-duration: 26s; }
    .bubble:nth-child(9)  { left: 60%;  width: 18px;  height: 18px;  animation-delay: 12s; animation-duration: 35s; }
    .bubble:nth-child(10) { left: 35%;  width: 22px;  height: 22px;  animation-delay: 14s; animation-duration: 30s; }

    /* Card & Typography */
    .error-card {
        background: rgba(255,255,255,0.96);
        border-radius: 1rem;
        padding: 2.25rem;
        box-shadow: 0 10px 30px rgba(16,24,40,0.12);
        max-width: 900px;
        margin: 1rem;
    }
    .error-code {
        font-weight: 800;
        font-size: 5rem;
        line-height: 1;
        letter-spacing: -4px;
        color: #0f172a; /* slate-900 */
    }
    .error-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0b1220;
    }
    .error-desc {
        color: #475569; /* slate-500 */
        margin-top: 0.5rem;
    }

    /* tombol */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .6rem 1rem;
        border-radius: .75rem;
        font-weight: 700;
        background: linear-gradient(90deg,#ffdd57,#f59e0b);
        color: #09101a;
        box-shadow: 0 8px 20px rgba(245,158,11,0.18);
        border: none;
        cursor: pointer;
    }
    .btn-outline {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .55rem .95rem;
        border-radius: .65rem;
        font-weight: 600;
        background: transparent;
        color: #0b1220;
        border: 1px solid rgba(11,18,32,0.08);
        cursor: pointer;
    }

    /* responsive */
    @media (max-width: 640px) {
        .error-code { font-size: 3.25rem; }
        .error-card { padding: 1.25rem; }
    }
</style>

{{-- Latar Gelembung --}}
<ul class="bubbles-bg" aria-hidden="true">
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
    <div class="w-full max-w-4xl px-6">
        <div class="error-card mx-auto relative z-10 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
            {{-- Kiri: Kode error --}}
            <div class="text-center md:text-left">
                <div class="inline-flex items-center justify-center w-28 h-28 rounded-full bg-gradient-to-br from-poco-50 to-poco-100 shadow-inner mb-4">
                    {{-- Icon simple --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="#0b1220" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10 14l2-2 2 2"></path>
                        <path d="M12 7v6"></path>
                        <path d="M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0z"></path>
                    </svg>
                </div>

                <div class="error-code">403</div>
                <div class="error-title mt-2">Akses Ditolak — Tidak Diizinkan</div>
                <p class="error-desc mt-3">
                    Maaf, kamu tidak memiliki izin untuk mengakses halaman ini. Mungkin kamu perlu masuk atau memiliki hak akses yang sesuai.
                </p>
            </div>

            {{-- Kanan: Aksi --}}
            <div class="text-center md:text-left">
                <div class="mb-4">
                    <p class="text-sm text-muted" style="color:#64748b;">
                        Ini informasi yang belum valid atau belum terverifikasi
                    </p>
                    <ul class="mt-3 text-sm" style="color:#475569; list-style:disc; padding-left:1rem;">
                        <li>Periksa kembali akun dan perizinanmu.</li>
                        
                    </ul>
                </div>

                <div class="flex flex-wrap gap-3 mt-4">
                    {{-- Tombol Back: pakai history.back() --}}
                    <button type="button" class="btn-primary" onclick="history.back()" aria-label="Kembali">
                        <!-- icon back -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#09101a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"></polyline>
                        </svg>
                        Kembali
                    </button>

                    

                    {{-- Link kontak / help (opsional) --}}
                    @if (Route::has('contact'))
                        <a href="{{ route('contact') }}" class="btn-outline" aria-label="Kontak Admin">Hubungi Admin</a>
                    @endif
                </div>

                {{-- Info teknis kecil --}}
                <p class="text-xs mt-6" style="color:#94a3b8;">
                    Kode error: <strong>403</strong> • Jika masalah berlanjut, lampirkan tautan & waktu kejadian saat menghubungi admin.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
