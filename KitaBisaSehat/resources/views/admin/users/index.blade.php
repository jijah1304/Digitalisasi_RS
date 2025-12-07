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

    <!-- Alert Sukses -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-rs-green text-green-700 rounded-r-xl shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Alert Error (Misal coba hapus akun sendiri) -->
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter & Sorting Section -->
    <div class="glass p-6 rounded-2xl mb-8">
        <form action="{{ route('admin.users.index') }}" method="GET">
            <!-- Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                
                <!-- 1. Cari Nama -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Cari Nama/Email</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none shadow-sm" placeholder="Cari dokter, pasien...">
                        <svg class="w-5 h-5 absolute left-3 top-3 text-rs-navy/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                <!-- 2. Filter Role -->
                <div>
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Filter Role</label>
                    <select name="role" class="w-full px-4 py-2.5 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none cursor-pointer">
                        <option value="">Semua Role</option>
                        <option value="dokter" {{ request('role') == 'dokter' ? 'selected' : '' }}>Dokter</option>
                        <option value="pasien" {{ request('role') == 'pasien' ? 'selected' : '' }}>Pasien</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <!-- 3. Sorting -->
                <div>
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Urutkan</label>
                    <select name="sort" class="w-full px-4 py-2.5 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none cursor-pointer">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                    </select>
                </div>

                <!-- 4. Tombol Filter -->
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-rs-navy text-white rounded-xl font-bold hover:bg-rs-green transition-colors shadow-md">
                        Terapkan
                    </button>
                    @if(request()->hasAny(['search', 'role', 'date', 'sort']))
                        <a href="{{ route('admin.users.index') }}" class="px-3 py-2.5 bg-red-100 text-red-600 rounded-xl font-bold hover:bg-red-200 transition-colors flex items-center justify-center" title="Reset Filter">
                            ✕
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel User -->
    <div class="glass rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                    <tr>
                        <th class="p-5 font-semibold">Nama Pengguna</th>
                        <th class="p-5 font-semibold">Email</th>
                        <th class="p-5 font-semibold">Role</th>
                        <th class="p-5 font-semibold">Terdaftar Sejak</th>
                        <th class="p-5 font-semibold text-center">Aksi</th> <!-- Kolom Baru -->
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-white/30 transition-colors">
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-white border border-gray-100 overflow-hidden shrink-0">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <div class="font-bold text-rs-navy">{{ $user->name }}</div>
                                        @if($user->poli)
                                            <span class="text-xs text-rs-teal font-medium bg-rs-teal/10 px-2 py-0.5 rounded">{{ $user->poli->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-5 text-rs-navy/80 text-sm">{{ $user->email }}</td>
                            <td class="p-5">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide border
                                    {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-700 border-purple-200' : '' }}
                                    {{ $user->role == 'dokter' ? 'bg-green-100 text-green-700 border-green-200' : '' }}
                                    {{ $user->role == 'pasien' ? 'bg-blue-100 text-blue-700 border-blue-200' : '' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="p-5 text-sm text-rs-navy/60 font-mono">
                                {{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}
                            </td>
                            
                            <!-- Kolom Tombol Aksi -->
                            <td class="p-5 text-center">
                                <div class="flex justify-center gap-2">
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-2 text-rs-teal hover:bg-rs-teal/10 rounded-lg transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>

                                    <!-- Tombol Hapus -->
                                    @if(Auth::id() !== $user->id)
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini? \n\nPERINGATAN: Semua data terkait (Janji Temu, Rekam Medis) mungkin akan ikut terhapus!');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Akun Sendiri</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-rs-navy/50 italic">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-rs-navy/20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <p>Tidak ada pengguna yang sesuai filter.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-5 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection