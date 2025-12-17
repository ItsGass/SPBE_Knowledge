@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <h1 class="text-3xl font-extrabold text-text-light dark:text-text-dark pb-4 border-b border-gray-200 dark:border-poco-900">
        Riwayat Aktivitas
    </h1>

    <div class="mt-6 bg-white dark:bg-base-dark/80 p-6 rounded-2xl shadow-xl dark:shadow-2xl dark:shadow-poco-900/50 transition-all duration-500">
        
        {{-- Top actions (quick filter + export) --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-4">
            <div class="flex items-center gap-2">
                <form method="GET" action="{{ route('activity.logs') }}">
                    <input type="hidden" name="actor_role" value="verifikator">
                    <button type="submit" class="px-3 py-1 rounded-lg bg-poco-500 text-black font-semibold">Filter</button>
                </form>

                
            </div>

            <div class="text-sm text-muted dark:text-gray-400 mt-2 sm:mt-0">
                Menampilkan <strong>{{ $logs->total() }}</strong> item — halaman {{ $logs->currentPage() }}.
            </div>
        </div>

        {{-- Form Filter --}}
        <form method="GET" action="{{ route('activity.logs') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-3">
            
            {{-- Select User --}}
            <div>
                <label class="text-xs font-semibold mb-1 block">User</label>
                <select name="user_id" 
                        class="p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-base-dark text-sm
                               focus:ring-poco-500 focus:border-poco-500 transition-colors w-full">
                    <option value="">Semua User</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ (request('user_id') == $u->id) ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->role }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- Input Action 
            <div>
                <label class="text-xs font-semibold mb-1 block">Aksi</label>
                <input name="action" 
                       value="{{ request('action') }}" 
                       placeholder="e.g. verify_knowledge" 
                       class="p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-base-dark text-sm
                              focus:ring-poco-500 focus:border-poco-500 transition-colors w-full" />
            </div>--}}

            {{-- Input q (search in action/meta) 
            <div>
                <label class="text-xs font-semibold mb-1 block">Cari (action/meta)</label>
                <input name="q" 
                       value="{{ request('q') }}" 
                       placeholder="kata kunci" 
                       class="p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-base-dark text-sm
                              focus:ring-poco-500 focus:border-poco-500 transition-colors w-full" />
            </div>--}}

            {{-- Actor role --}}
            <div>
                <label class="text-xs font-semibold mb-1 block">Peran Aktor</label>
                <select name="actor_role" class="p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-base-dark text-sm w-full">
                    <option value="">Semua</option>
                    <option value="verifikator" {{ request('actor_role') === 'verifikator' ? 'selected' : '' }}>Verifikator</option>
                    <option value="admin" {{ request('actor_role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="super_admin" {{ request('actor_role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                </select>
            </div>

            {{-- Date from --}}
            <div>
                <label class="text-xs font-semibold mb-1 block">Dari</label>
                <input name="date_from" type="date" value="{{ request('date_from') }}" class="p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-base-dark text-sm w-full" />
            </div>

            {{-- Date to --}}
            <div>
                <label class="text-xs font-semibold mb-1 block">Sampai</label>
                <input name="date_to" type="date" value="{{ request('date_to') }}" class="p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-base-dark text-sm w-full" />
            </div>

            {{-- Per page 
            <div>
                <label class="text-xs font-semibold mb-1 block">Per Halaman</label>
                <input type="number" min="1" name="per_page" value="{{ request('per_page') ?? 20 }}" class="p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-base-dark text-sm w-full" />
            </div>--}}

            {{-- Buttons --}}
            <div class="md:col-span-4 flex items-center gap-3 mt-2">
                <button type="submit" class="px-5 py-2.5 font-bold rounded-xl transition duration-300 shadow-md
                                               bg-poco-500 hover:bg-poco-600 text-black dark:bg-poco-600 dark:hover:bg-poco-700">
                    Apply
                </button>
                <a href="{{ route('activity.logs') }}" class="px-4 py-2 rounded-xl border">Reset</a>
            </div>
        </form>

        {{-- Table Log --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-poco-900">
                <thead class="bg-gray-50 dark:bg-poco-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">User</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-300">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-poco-900">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-poco-900 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-muted dark:text-gray-500">{{ optional($log->created_at)->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-text-light dark:text-text-dark">{{ optional($log->user)->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-muted dark:text-gray-400">{{ optional($log->user)->role ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-poco-600 dark:text-poco-400">{{ $log->action }}</td>
                            <td class="px-6 py-4 text-sm text-text-light/80 dark:text-text-dark/80">
                                {{-- tampilkan meta_human jika ada, jika tidak tampilkan JSON pretty --}}
                                @if(!empty($log->meta_human))
                                    {{ $log->meta_human }}
                                @else
                                    <pre class="text-xs bg-gray-100 dark:bg-gray-900 p-2 rounded-lg overflow-x-auto max-h-28 border border-gray-200 dark:border-gray-800">{{ is_string($log->meta) ? $log->meta : json_encode($log->meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-muted dark:text-gray-500">
                                Tidak ada log aktivitas yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6 p-4 border-t border-gray-100 dark:border-poco-900">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
