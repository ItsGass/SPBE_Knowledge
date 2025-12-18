@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">

    <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark mb-6 pb-4
               border-b border-gray-200 dark:border-poco-900">
        Buat Knowledge Baru
    </h1>

    <div class="bg-white dark:bg-base-dark p-6 sm:p-8 rounded-2xl
                shadow-xl dark:shadow-2xl dark:shadow-poco-900/50 transition-all duration-500">

        {{-- ================= ERROR GLOBAL ================= --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 text-red-400">
                <ul class="list-disc list-inside text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('knowledge.store') }}" method="POST"
              enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- ================= JUDUL ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Judul</label>
                <input name="title" type="text"
                       value="{{ old('title') }}"
                       placeholder="Masukkan judul knowledge"
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
                            {{ old('scope_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- ================= TAGS ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Tags</label>
                <input name="tags" type="text"
                       value="{{ old('tags') }}"
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
                    <option value="pdf" {{ old('type','pdf')=='pdf'?'selected':'' }}>
                        📄 PDF / Dokumen
                    </option>
                    <option value="video" {{ old('type')=='video'?'selected':'' }}>
                        🎥 Video (YouTube)
                    </option>
                </select>
            </div>

            {{-- ================= YOUTUBE ================= --}}
            <div id="youtube-field" class="hidden">
                <label class="block text-sm font-semibold mb-1">Link YouTube</label>
                <input name="youtube_url" type="url"
                       value="{{ old('youtube_url') }}"
                       placeholder="https://youtube.com/watch?v=xxxx"
                       class="w-full p-3 rounded-lg
                              border border-gray-300 dark:border-gray-700
                              bg-gray-50 dark:bg-base-dark
                              focus:ring-2 focus:ring-poco-500 focus:border-poco-500">
                @error('youtube_url')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ================= THUMBNAIL ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Thumbnail</label>
                <input type="file" name="thumbnail" accept="image/*"
                       class="block w-full text-sm
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:bg-poco-100 file:text-poco-700
                              dark:file:bg-poco-900 dark:file:text-poco-300">
            </div>

            {{-- ================= ISI ================= --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Isi Konten</label>
                <textarea name="body" rows="10"
                          placeholder="Tuliskan isi knowledge di sini..."
                          class="w-full p-3 rounded-lg
                                 border border-gray-300 dark:border-gray-700
                                 bg-gray-50 dark:bg-base-dark
                                 focus:ring-2 focus:ring-poco-500 focus:border-poco-500">{{ old('body') }}</textarea>
            </div>

            {{-- ================= ATTACHMENT ================= --}}
            <div id="attachment-field">
                <label class="block text-sm font-semibold mb-1">Lampiran PDF</label>
                <input type="file" name="attachment" accept="application/pdf"
                       class="block w-full text-sm">
            </div>

            {{-- ================= BUTTON ================= --}}
            <div class="flex justify-end gap-3 pt-6
                        border-t border-gray-100 dark:border-poco-900">
                <a href="{{ route('knowledge.index') }}"
                   class="px-5 py-2.5 rounded-xl border text-muted hover:bg-gray-100 dark:hover:bg-poco-900">
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-2.5 rounded-xl font-bold
                               bg-poco-500 hover:bg-poco-600 text-black
                               focus:ring-4 focus:ring-poco-300">
                    Simpan Knowledge
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
