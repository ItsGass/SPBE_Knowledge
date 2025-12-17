@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Modal Konfirmasi Hapus (Kustom UI) -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center transition-opacity duration-300">
        <div class="bg-white dark:bg-base-dark p-6 rounded-2xl shadow-2xl w-full max-w-lg transition-transform duration-300 transform scale-95" id="modalContent">
            <h3 id="modalTitle" class="text-xl font-bold text-text-light dark:text-text-dark mb-4"></h3>
            <p id="modalMessage" class="text-sm text-text-light/80 dark:text-text-dark/80 mb-6"></p>
            
            <div class="flex justify-end gap-3">
                <button onclick="hideDeleteModal()" 
                        class="px-4 py-2 text-sm font-semibold rounded-lg border border-gray-300 dark:border-gray-700 
                               text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    Batal
                </button>
                <button id="confirmDeleteBtn"
                        class="px-4 py-2 text-sm font-semibold rounded-lg bg-red-600 hover:bg-red-700 text-white transition">
                    Konfirmasi Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        let formToDelete = null;

        function showDeleteModal(form, isForceDelete) {
            formToDelete = form;
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            if (isForceDelete) {
                modalTitle.textContent = 'Hapus Permanen User';
                modalMessage.innerHTML = 'Anda **YAKIN** ingin menghapus pengguna ini **SECARA PERMANEN**? Data tidak bisa dikembalikan!';
                confirmDeleteBtn.classList.remove('bg-red-600');
                confirmDeleteBtn.classList.add('bg-red-700', 'hover:bg-red-800');
            } else {
                modalTitle.textContent = 'Hapus User';
                modalMessage.innerHTML = 'Anda yakin ingin menghapus pengguna ini?';
                confirmDeleteBtn.classList.remove('bg-red-700', 'hover:bg-red-800');
                confirmDeleteBtn.classList.add('bg-red-600', 'hover:bg-red-700');
            }

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
    
    {{-- Header dan Tombol Tambah --}}
    <div class="flex justify-between items-center pb-4 mb-6 border-b border-gray-200 dark:border-poco-900">
        <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark">Manajemen User</h1>
        
        <a class="px-5 py-2.5 font-bold rounded-xl transition duration-300 shadow-lg 
                  bg-poco-500 hover:bg-poco-600 text-black 
                  dark:bg-poco-600 dark:hover:bg-poco-700 
                  focus:outline-none focus:ring-4 focus:ring-poco-300 flex items-center gap-2" 
           href="{{ route('user-management.create') }}">
            <!-- Ikon Plus -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Tambah User
        </a>
        {{-- Tombol Recycle Bin (User Terhapus) 
<a href="{{ route('user-management.trashed') }}"
   class="px-5 py-2.5 font-bold rounded-xl transition duration-300 shadow-lg 
          bg-gray-200 hover:bg-gray-300 text-gray-800 
          dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-100
          flex items-center gap-2 ml-3">

    <!-- Ikon Trash -->
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
         viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
              d="M9 13h6m2 10H7a2 2 0 01-2-2V7h14v14a2 2 0 01-2 2zM9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M4 7h16" />
    </svg>

    Restore 
</a> --}}
    </div>

    <div class="mt-4">
        <!-- Kartu Konten Utama -->
        <div class="bg-white dark:bg-base-dark/80 p-0 rounded-2xl shadow-xl dark:shadow-2xl dark:shadow-poco-900/50 transition-all duration-500 overflow-hidden">
            
            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-poco-900">
                    <thead class="bg-gray-50 dark:bg-poco-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-poco-900">
                        @foreach($users as $u)
                            <tr class="hover:bg-gray-50 dark:hover:bg-poco-900 transition-colors">
                                <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-text-light dark:text-text-dark">{{ $u->name }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-text-light/80 dark:text-text-dark/80">{{ $u->email }}</td>
                                <td class="px-6 py-3 whitespace-nowrap text-sm font-semibold uppercase">
                                    @if($u->role === 'super_admin')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            {{ $u->role }}
                                        </span>
                                    @elseif($u->role === 'admin')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-poco-100 text-poco-800 dark:bg-poco-900 dark:text-poco-300">
                                            {{ $u->role }}
                                        </span>
                                    @elseif($u->role === 'verifikator')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-900 text-blue-100 dark:bg-blue-950 dark:text-blue-200">
                                            {{ $u->role }}
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $u->role }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
        
                                    <div class="flex items-center space-x-2">
                                        {{-- Edit --}}
                                        <a class="px-3 py-1 text-xs font-semibold rounded-lg transition duration-300 
                                                  border border-poco-500 text-poco-500 
                                                  hover:bg-poco-500 hover:text-black 
                                                  dark:border-poco-600 dark:text-poco-600 dark:hover:bg-poco-600 dark:hover:text-black"
                                           href="{{ route('user-management.edit', $u) }}">
                                            Edit
                                        </a>
        
                                        {{-- Soft Delete (default) --}}
                                        <form method="POST" action="{{ route('user-management.destroy', $u) }}" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 text-xs font-semibold rounded-lg transition duration-300 
                                                                         bg-red-500 hover:bg-red-600 text-white dark:bg-red-700 dark:hover:bg-red-600"
                                                    onclick="event.preventDefault(); showDeleteModal(this.closest('form'), false);">
                                                Hapus
                                            </button>
                                        </form>
        
                                        {{-- Force Delete (permanen) 
                                        <form method="POST" action="{{ route('user-management.destroy', $u) }}" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="force" value="1">
                                            <button type="submit" class="px-3 py-1 text-xs font-semibold rounded-lg transition duration-300 
                                                                         border border-red-700 text-red-700 hover:bg-red-50 dark:border-red-500 dark:text-red-500 dark:hover:bg-red-900/50"
                                                    onclick="event.preventDefault(); showDeleteModal(this.closest('form'), true);">
                                                Hapus Permanen
                                            </button>
                                        </form>--}}
                                    </div>
        
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100 dark:border-poco-900">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection