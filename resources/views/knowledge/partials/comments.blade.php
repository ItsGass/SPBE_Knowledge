<div class="mt-8">
    <h4 class="text-sm font-semibold text-text-light dark:text-text-dark leading-tight mb-4">Komentar</h4>

    {{-- Form tambah komentar --}}
    @auth
    <form method="POST" action="{{ route('knowledge.comment.store', $knowledge->id) }}" class="mb-6">
        @csrf
        <textarea name="comment" required
            class="w-full rounded-lg p-2 text-sm
                    bg-gray-50 text-gray-800 border border-gray-300
                    dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600
                    focus:outline-none focus:ring-2 focus:ring-yellow-500"
            placeholder="Tulis komentar..."></textarea>

        <div class="mt-2 text-right">
            <button class="px-3 py-1 rounded text-xs
                        bg-yellow-500 text-black hover:bg-yellow-600">
                Kirim
            </button>
        </div>
    </form>
    @endauth

    {{-- List komentar --}}
    <div class="space-y-4">
@foreach ($knowledge->comments as $comment)
<div class="rounded-xl border p-4 mb-3
            bg-white border-gray-200
            dark:bg-gray-900 dark:border-gray-700
            transition-colors"
     x-data="{ open: false, edit: false }">

    {{-- Header --}}
    <div class="flex justify-between items-start">
        <div>
            <p class="text-sm font-semibold
                    text-gray-800
                    dark:text-gray-100">
                {{ $comment->user->name ?? 'User' }}
            </p>

            <p class="text-xs
                    text-gray-500
                    dark:text-gray-400">
                {{ $comment->created_at->diffForHumans() }}
            </p>
        </div>

        {{-- THREE DOT (HANYA PEMILIK KOMENTAR) --}}
        @if(auth()->id() === $comment->user_id)
        <div class="relative">
            <button @click="open = !open"
                    class="text-gray-400 hover:text-gray-600
                    dark:text-gray-500 dark:hover:text-gray-300">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>

            {{-- DROPDOWN --}}
            <div x-show="open"
                 @click.outside="open = false"
                 class="absolute right-0 mt-2 w-36 rounded-lg shadow-lg
                bg-white border border-gray-200
                dark:bg-gray-800 dark:border-gray-700">

                <button @click="edit = true; open = false"
                        class="w-full text-left px-4 py-2 text-sm
                        text-gray-700 hover:bg-gray-100
                        dark:text-gray-200 dark:hover:bg-gray-700">
                    ✏️ Edit
                </button>

                <form method="POST"
                      action="{{ route('knowledge.comment.destroy', $comment->id) }}"
                      onsubmit="return confirm('Hapus komentar ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="w-full text-left px-4 py-2 text-sm
                            text-red-500 hover:bg-gray-100
                            dark:hover:bg-gray-700">
                        🗑️ Hapus
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    {{-- ISI KOMENTAR --}}
    <div class="mt-3" x-show="!edit">
        <p class="text-sm leading-relaxed
                text-gray-700
                dark:text-gray-200">
            {{ $comment->comment }}
        </p>
    </div>

    {{-- FORM EDIT (INLINE) --}}
    @if(auth()->id() === $comment->user_id)
    <form x-show="edit"
          method="POST"
          action="{{ route('knowledge.comment.update', $comment->id) }}"
          class="mt-3">
        @csrf
        @method('PUT')

        <textarea name="comment"
                  class="w-full rounded-lg p-2 text-sm
                    bg-gray-50 text-gray-800 border border-gray-300
                    dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600
                    focus:outline-none focus:ring-2 focus:ring-yellow-500"
                  required>{{ $comment->comment }}</textarea>

        <div class="flex gap-2 mt-2">
            <button class="px-3 py-1 rounded text-xs
                    bg-yellow-500 text-black hover:bg-yellow-600">
                Simpan
            </button>
            <button type="button"
                    @click="edit = false"
                    class="px-3 py-1 text-xs
                    text-gray-500 hover:text-gray-700
                    dark:text-gray-400 dark:hover:text-gray-200">
                Batal
            </button>
        </div>
    </form>
    @endif
</div>
@endforeach

    </div>
</div>
