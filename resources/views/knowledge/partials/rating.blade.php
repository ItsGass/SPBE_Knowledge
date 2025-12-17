{{-- ================== RATING ================== --}}
<div class="mt-6">
    <h4 class="text-sm font-semibold text-black-300 mb-2">
        Berikan Penilaian
    </h4>
    
    @php
        $userRating = auth()->check()
            ? $knowledge->ratings->where('user_id', auth()->id())->first()
            : null;
    @endphp

    @if($userRating)
        <div class="text-sm text-gray-400 mt-2">
            <i class="fa-solid fa-star text-yellow-400"></i>
            Rating Anda: <strong>{{ $userRating->rating }}</strong>  
            <br>
            <span class="text-xs text-gray-500">
                Anda sudah memberikan rating untuk knowledge ini.
            </span>
        </div>
    @else
        @auth
        <form method="POST" action="{{ route('knowledge.rate', $knowledge->id) }}">
            @csrf
            <div class="flex gap-1">
                @for ($i = 1; $i <= 5; $i++)
                    <button
                        type="submit"
                        name="rating"
                        value="{{ $i }}"
                        class="text-2xl transition
                        {{ $i <= round($knowledge->average_rating) 
                            ? 'text-yellow-400' 
                            : 'text-gray-500 hover:text-yellow-300' }}">
                        ★
                    </button>
                @endfor
            </div>
        </form>
        @else
            <p class="text-sm text-gray-500">
                Login untuk memberikan rating dan komentar.
            </p>
        @endauth
    @endif

</div>
{{-- ================== END RATING ================== --}}