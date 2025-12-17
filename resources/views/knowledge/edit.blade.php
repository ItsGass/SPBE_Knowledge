@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">

    <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark mb-6 pb-4
               border-b border-gray-200 dark:border-poco-900">
        Edit Knowledge
    </h1>

    <div class="bg-white dark:bg-base-dark/80 p-6 sm:p-8 rounded-2xl
                shadow-xl dark:shadow-2xl dark:shadow-poco-900/50 transition-all duration-500">

        {{-- ================= ERROR ================= --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 text-red-400">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('knowledge.update', $knowledge) }}" method="POST"
              enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- ================= JUDUL ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Judul</label>
                <input name="title" type="text"
                       value="{{ old('title', $knowledge->title) }}"
                       class="w-full p-3 rounded-lg
                              border border-gray-300 dark:border-gray-700
                              bg-gray-50 dark:bg-base-dark
                              focus:ring-2 focus:ring-poco-500 focus:border-poco-500">
            </div>

            {{-- ================= SCOPE ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Scope</label>
                <select name="scope_id"
                        class="w-full p-3 rounded-lg
                               border border-gray-300 dark:border-gray-700
                               bg-gray-50 dark:bg-base-dark
                               focus:ring-2 focus:ring-poco-500 focus:border-poco-500">
                    <option value="">-- Pilih Scope --</option>
                    @foreach($scopes as $s)
                        <option value="{{ $s->id }}"
                            {{ old('scope_id', $knowledge->scope_id) == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ================= TAGS ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Tags</label>
                <input name="tags" type="text"
                       value="{{ old('tags', $knowledge->tags->pluck('name')->join(',')) }}"
                       placeholder="magang,pelatihan,spbe"
                       class="w-full p-3 rounded-lg
                              border border-gray-300 dark:border-gray-700
                              bg-gray-50 dark:bg-base-dark
                              focus:ring-2 focus:ring-poco-500 focus:border-poco-500">
                <p class="text-xs text-muted mt-1">Pisahkan dengan koma</p>
            </div>

            {{-- ================= TYPE ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Tipe Knowledge</label>
                <select id="type" name="type"
                        class="w-full p-3 rounded-lg
                               border border-gray-300 dark:border-gray-700
                               bg-gray-50 dark:bg-base-dark
                               focus:ring-2 focus:ring-poco-500 focus:border-poco-500">
                    <option value="pdf" {{ old('type',$knowledge->type)=='pdf'?'selected':'' }}>
                        📄 PDF / Dokumen
                    </option>
                    <option value="video" {{ old('type',$knowledge->type)=='video'?'selected':'' }}>
                        🎥 Video (YouTube)
                    </option>
                </select>
            </div>

            {{-- ================= YOUTUBE ================= --}}
            <div id="youtube-field" class="hidden">
                <label class="block text-sm font-semibold mb-1">Link YouTube</label>
                <input name="youtube_url" type="url"
                       value="{{ old('youtube_url',$knowledge->youtube_url) }}"
                       placeholder="https://youtube.com/watch?v=xxxx"
                       class="w-full p-3 rounded-lg
                              border border-gray-300 dark:border-gray-700
                              bg-gray-50 dark:bg-base-dark
                              focus:ring-2 focus:ring-poco-500 focus:border-poco-500">
            </div>

            {{-- ================= ISI ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Isi Konten</label>
                <textarea name="body" rows="10"
                          class="w-full p-3 rounded-lg
                                 border border-gray-300 dark:border-gray-700
                                 bg-gray-50 dark:bg-base-dark
                                 focus:ring-2 focus:ring-poco-500 focus:border-poco-500">{{ old('body',$knowledge->body) }}</textarea>
            </div>

            {{-- ================= ATTACHMENT ================= --}}
            <div id="attachment-field">
                <label class="block text-sm font-semibold mb-1">Lampiran PDF</label>

                @if($knowledge->attachment_path)
                    <div class="mb-2 text-sm">
                        <a href="{{ asset('storage/'.$knowledge->attachment_path) }}"
                           target="_blank"
                           class="text-poco-600 hover:underline">
                            📎 Lihat file saat ini
                        </a>
                    </div>
                @endif

                <input type="file" name="attachment" accept="application/pdf"
                       class="block w-full text-sm">
            </div>

            {{-- ================= ACTION ================= --}}
            <div class="flex justify-end gap-3 pt-6
                        border-t border-gray-100 dark:border-poco-900">
                <a href="{{ route('knowledge.show',$knowledge) }}"
                   class="px-5 py-2.5 rounded-xl font-semibold
                          border border-muted/50 dark:border-muted/30
                          text-muted hover:text-black dark:text-gray-400
                          hover:bg-gray-100 dark:hover:bg-poco-900">
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-2.5 rounded-xl font-bold
                               bg-poco-500 hover:bg-poco-600 text-black
                               dark:bg-poco-600 dark:hover:bg-poco-700
                               shadow-lg focus:ring-4 focus:ring-poco-300">
                    Update Knowledge
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const type = document.getElementById('type');
    const yt = document.getElementById('youtube-field');
    const att = document.getElementById('attachment-field');

    function toggle() {
        yt.classList.toggle('hidden', type.value !== 'video');
        att.classList.toggle('hidden', type.value !== 'pdf');
    }

    toggle();
    type.addEventListener('change', toggle);
});
</script>
@endsection
