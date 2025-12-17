@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Restore</h1>

    @if(session('success'))<div class="mb-3 text-green-600">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-3 text-red-600">{{ session('error') }}</div>@endif

    <form class="mb-4" method="get">
        <input name="q" value="{{ request('q') }}" placeholder="cari nama/email..." class="px-3 py-2 border rounded" />
        <button class="px-3 py-2 bg-yellow-400 rounded ml-2">Cari</button>
    </form>

    <div class="bg-white dark:bg-[#0b0b0b] p-4 rounded">
        <table class="w-full table-auto">
            <thead>
                <tr class="text-left">
                    <th>Name</th>
                    <th>Email</th>
                    <th>Dihapus Pada</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr class="border-t">
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->deleted_at->format('Y-m-d H:i') }}</td>
                    <td class="text-right">
                        <form action="{{ route('user-management.restore', $u->id) }}" method="POST" class="inline-block">
                            @csrf
                            <button class="px-3 py-1 bg-green-500 text-white rounded">Restore</button>
                        </form>

                        <form action="{{ route('user-management.forceDelete', $u->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus permanen user ini? Tindakan tidak dapat dikembalikan!');">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 bg-red-600 text-white rounded ml-2">Hapus Permanen</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-4">Tidak ada user terhapus.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
