@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-rs-navy">Manajemen Obat</h1>
            <p class="text-rs-navy/60">Stok obat dan farmasi rumah sakit.</p>
        </div>
        <a href="{{ route('medicines.create') }}" class="px-6 py-3 bg-rs-green text-white rounded-xl font-bold shadow-lg hover:bg-rs-navy transition-all transform hover:-translate-y-1 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
            Tambah Obat
        </a>
    </div>

    <!-- Filter Section -->
    <div class="glass p-6 rounded-2xl mb-8">
        <form action="{{ route('medicines.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <!-- Cari Nama -->
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Cari Nama Obat</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none shadow-sm" placeholder="Contoh: Paracetamol...">
                    <svg class="w-5 h-5 absolute left-3 top-3 text-rs-navy/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            <!-- Filter Status -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Status Stok</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia (> 0)</option>
                    <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>Habis (0)</option>
                </select>
            </div>
            <!-- Filter Tipe -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Tipe Obat</label>
                <select name="type" class="w-full px-4 py-2.5 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none cursor-pointer">
                    <option value="">Semua Tipe</option>
                    <option value="biasa" {{ request('type') == 'biasa' ? 'selected' : '' }}>Obat Biasa</option>
                    <option value="keras" {{ request('type') == 'keras' ? 'selected' : '' }}>Obat Keras</option>
                </select>
            </div>
            <!-- Tombol -->
            <div class="md:col-span-4 flex justify-end gap-2 mt-2">
                @if(request()->hasAny(['search', 'status', 'type']))
                    <a href="{{ route('medicines.index') }}" class="px-4 py-2 text-red-500 hover:bg-red-50 rounded-lg font-bold transition-colors">Reset</a>
                @endif
                <button type="submit" class="px-6 py-2 bg-rs-navy text-white rounded-xl font-bold hover:bg-rs-green transition-colors shadow-md">Terapkan Filter</button>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-rs-green text-green-700 rounded-r-xl shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="glass rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                    <tr>
                        <th class="p-5 font-semibold">Nama Obat</th>
                        <th class="p-5 font-semibold text-center">Tipe</th>
                        <th class="p-5 font-semibold text-center">Stok</th>
                        <th class="p-5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($medicines as $med)
                        <tr class="hover:bg-white/30 transition-colors">
                            
                            <!-- NAMA & GAMBAR OBAT (DENGAN LOGIKA LINK) -->
                            <td class="p-5">
                                <div class="flex items-center gap-4">
                                    <!-- Container Gambar -->
                                    <div class="w-12 h-12 rounded-xl bg-white border border-gray-100 shrink-0 overflow-hidden shadow-sm flex items-center justify-center">
                                        
                                        {{-- LOGIKA 1: Link Eksternal (Pinterest/Google) --}}
                                        @if(Str::startsWith($med->image, ['http://', 'https://']))
                                            <img src="{{ $med->image }}" class="w-full h-full object-cover" alt="{{ $med->name }}"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <!-- Fallback jika link rusak -->
                                            <div class="hidden w-full h-full items-center justify-center bg-gray-50 text-gray-300 text-[10px]">Img Error</div>
                                        
                                        {{-- LOGIKA 2: File Lokal (namafile.jpg) --}}
                                        @elseif(Str::contains($med->image, ['.jpg', '.png', '.jpeg', '.svg']))
                                            <img src="{{ asset('images/' . $med->image) }}" class="w-full h-full object-cover" alt="{{ $med->name }}"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="hidden w-full h-full items-center justify-center bg-gray-50 text-gray-300 text-[10px]">No Img</div>
                                        
                                        {{-- LOGIKA 3: Emoji atau Teks --}}
                                        @else
                                            <div class="text-xl">{{ $med->image }}</div>
                                        @endif
                                    </div>

                                    <div>
                                        <div class="font-bold text-rs-navy text-lg">{{ $med->name }}</div>
                                        <div class="text-sm text-rs-navy/60 mt-0.5 line-clamp-1 max-w-xs" title="{{ $med->description }}">
                                            {{ $med->description }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="p-5 text-center">
                                @if($med->type == 'keras')
                                    <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold border border-red-200">Keras</span>
                                @else
                                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200">Biasa</span>
                                @endif
                            </td>

                            <td class="p-5 text-center">
                                @if($med->stock > 10)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm font-bold">
                                        {{ $med->stock }} Unit
                                    </span>
                                @elseif($med->stock > 0)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-sm font-bold animate-pulse">
                                        {{ $med->stock }} (Menipis)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-200 text-gray-500 rounded-lg text-sm font-bold">
                                        Habis
                                    </span>
                                @endif
                            </td>

                            <td class="p-5 text-center flex justify-center gap-3">
                                <a href="{{ route('medicines.edit', $med->id) }}" class="p-2 text-rs-teal hover:bg-rs-teal/10 rounded-lg transition-colors" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                </a>
                                <form action="{{ route('medicines.destroy', $med->id) }}" method="POST" onsubmit="return confirm('Hapus obat ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-8 text-center text-rs-navy/50 italic">Belum ada data obat yang sesuai filter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-5 border-t border-gray-100">
            {{ $medicines->links() }}
        </div>
    </div>
</div>
@endsection