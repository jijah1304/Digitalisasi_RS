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

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-rs-green text-green-700 rounded-r-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="glass rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                    <tr>
                        <th class="p-5 font-semibold">Nama Obat</th>
                        <th class="p-5 font-semibold">Tipe</th>
                        <th class="p-5 font-semibold">Stok</th>
                        <th class="p-5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($medicines as $med)
                        <tr class="hover:bg-white/30 transition-colors">
                            <td class="p-5">
                                <div class="font-bold text-rs-navy">{{ $med->name }}</div>
                                <div class="text-xs text-rs-navy/50">{{ Str::limit($med->description, 50) }}</div>
                            </td>
                            <td class="p-5">
                                @if($med->type == 'keras')
                                    <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold border border-red-200">Keras</span>
                                @else
                                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs font-bold border border-blue-200">Biasa</span>
                                @endif
                            </td>
                            <td class="p-5">
                                @if($med->stock > 10)
                                    <span class="text-rs-green font-bold">{{ $med->stock }} unit</span>
                                @elseif($med->stock > 0)
                                    <span class="text-yellow-600 font-bold">{{ $med->stock }} unit (Menipis)</span>
                                @else
                                    <span class="text-red-600 font-bold">Habis</span>
                                @endif
                            </td>
                            <td class="p-5 text-center flex justify-center gap-3">
                                <a href="{{ route('medicines.edit', $med->id) }}" class="p-2 text-rs-teal hover:bg-rs-teal/10 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                </a>
                                <form action="{{ route('medicines.destroy', $med->id) }}" method="POST" onsubmit="return confirm('Hapus obat ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="p-8 text-center text-rs-navy/50">Belum ada data obat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection