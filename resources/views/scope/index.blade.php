@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Modal Konfirmasi Hapus (Kustom UI) -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center transition-opacity duration-300">
        <div class="bg-white dark:bg-base-dark p-6 rounded-xl shadow-2xl w-full max-w-sm">
            <h3 class="text-lg font-bold text-text-light dark:text-text-dark mb-4">Konfirmasi Hapus</h3>
            <p class="text-sm text-text-light/80 dark:text-text-dark/80 mb-6">
                Anda yakin ingin menghapus Scope ini? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-end gap-3">
                <button onclick="hideDeleteModal()" 
                        class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-300 dark:border-gray-700 
                               text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    Batal
                </button>
                <button id="confirmDeleteBtn"
                        class="px-4 py-2 text-sm font-semibold rounded-lg bg-red-600 hover:bg-red-700 text-white transition">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        let formToDelete = null;

        function showDeleteModal(form) {
            formToDelete = form;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
            document.getElementById('confirmDeleteBtn').onclick = function() {
                formToDelete.submit();
            };
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.remove('flex');
            document.getElementById('deleteModal').classList.add('hidden');
            formToDelete = null;
        }
    </script>
    
    <!-- Kartu Konten Utama -->
    <div class="bg-white dark:bg-base-dark/80 p-6 sm:p-8 rounded-2xl shadow-xl dark:shadow-2xl dark:shadow-poco-900/50 transition-all duration-500">
    
        {{-- Header dan Tombol Tambah --}}
        <div class="flex justify-between items-center pb-4 mb-6 border-b border-gray-100 dark:border-poco-900">
            <h2 class="text-2xl font-extrabold text-text-light dark:text-text-dark">Daftar Scope</h2>

            <a href="{{ route('scope.create') }}" 
               class="px-5 py-2.5 font-bold rounded-xl transition duration-300 shadow-lg 
                      bg-poco-500 hover:bg-poco-600 text-black 
                      dark:bg-poco-600 dark:hover:bg-poco-700 
                      focus:outline-none focus:ring-4 focus:ring-poco-300 flex items-center gap-2">
                <!-- Ikon Plus -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Scope
            </a>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-poco-900">
                <thead class="bg-gray-50 dark:bg-poco-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300 rounded-tl-lg">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">Nama Scope</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300 rounded-tr-lg">Aksi</th>
                    </tr>
                </thead>
        
                <tbody class="divide-y divide-gray-100 dark:divide-poco-900">
                    @forelse ($scopes as $scope)
                        <tr class="hover:bg-gray-50 dark:hover:bg-poco-900 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-text-light dark:text-text-dark">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-light/80 dark:text-text-dark/80">{{ $scope->name }}</td>
        
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex gap-3">
                                    {{-- Tombol Edit (Outline Biru/Accent) --}}
                                    <a href="{{ route('scope.edit', $scope->id) }}" 
                                       class="px-3 py-1 text-xs font-semibold rounded-lg transition duration-300 
                                              border border-poco-500 text-poco-500 
                                              hover:bg-poco-500 hover:text-black 
                                              dark:border-poco-600 dark:text-poco-600 dark:hover:bg-poco-600 dark:hover:text-black">
                                        Edit
                                    </a>
        
                                    {{-- Tombol Hapus (Merah) --}}
                                    <form action="{{ route('scope.destroy', $scope->id) }}" method="POST" 
                                          onsubmit="event.preventDefault(); showDeleteModal(this);">
                                        @csrf
                                        @method('DELETE')
        
                                        <button type="submit" 
                                                class="px-3 py-1 text-xs font-semibold rounded-lg transition duration-300 
                                                       bg-red-500 hover:bg-red-600 text-white 
                                                       dark:bg-red-700 dark:hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-muted dark:text-gray-500">
                                Belum ada scope yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
</div>
@endsection