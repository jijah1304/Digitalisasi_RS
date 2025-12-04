@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-rs-navy">Manajemen Pengguna</h1>
            <p class="text-rs-navy/60">Kelola data dokter, pasien, dan admin.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="px-6 py-3 bg-rs-green text-white rounded-xl font-bold shadow-lg hover:bg-rs-navy transition-all transform hover:-translate-y-1 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
            Tambah User
        </a>
    </div>

    <!-- Filter Section -->
    <div class="glass p-6 rounded-2xl mb-8">
        <form action="{{ route('admin.users.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            
            <!-- Cari Nama -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Cari Nama/Email</label>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full px-4 py-2.5 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none" placeholder="Ketikan nama...">
            </div>

            <!-- Filter Role -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Filter Role</label>
                <select name="role" class="w-full px-4 py-2.5 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none cursor-pointer">
                    <option value="">Semua Role</option>
                    <option value="dokter" {{ request('role') == 'dokter' ? 'selected' : '' }}>Dokter</option>
                    <option value="pasien" {{ request('role') == 'pasien' ? 'selected' : '' }}>Pasien</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <!-- Filter Tanggal Registrasi -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Tanggal Registrasi</label>
                <input type="date" name="date" value="{{ request('date') }}" class="w-full px-4 py-2.5 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none">
            </div>

            <!-- Tombol Filter -->
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2.5 bg-rs-navy text-white rounded-xl font-bold hover:bg-rs-green transition-colors shadow-md w-full">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'role', 'date']))
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2.5 bg-red-100 text-red-600 rounded-xl font-bold hover:bg-red-200 transition-colors" title="Reset">
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabel User -->
    <div class="glass rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                    <tr>
                        <th class="p-5 font-semibold">Nama</th>
                        <th class="p-5 font-semibold">Email</th>
                        <th class="p-5 font-semibold">Role</th>
                        <th class="p-5 font-semibold">Terdaftar Sejak</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/30 transition-colors">
                            <td class="p-5 font-bold text-rs-navy">
                                {{ $user->name }}
                                @if($user->poli)
                                    <span class="block text-xs font-normal text-rs-teal mt-1">{{ $user->poli->name }}</span>
                                @endif
                            </td>
                            <td class="p-5 text-rs-navy/80">{{ $user->email }}</td>
                            <td class="p-5">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                    {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $user->role == 'dokter' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $user->role == 'pasien' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="p-5 text-sm text-rs-navy/60">
                                {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-rs-navy/50 italic">User tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="p-5">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection