@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-2xl mx-auto px-6 pb-12">
    <div class="mb-8">
        <a href="{{ route('polis.index') }}" class="text-sm text-rs-navy/60 hover:text-rs-teal mb-2 inline-flex items-center gap-1 transition-colors">
            &larr; Kembali ke Daftar Poli
        </a>
        <h1 class="text-3xl font-bold text-rs-navy">Tambah Poli Baru</h1>
    </div>

    <div class="glass p-8 rounded-3xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-rs-green to-rs-teal"></div>
        
        <form action="{{ route('polis.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama Poli -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Nama Poli</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" placeholder="Contoh: Poli Gigi" required>
                @error('name') <span class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Ikon (Teks) -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Nama Ikon / Gambar</label>
                <input type="text" name="image" value="{{ old('image') }}" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" placeholder="Contoh: poli-gigi.png atau fa-tooth" required>
                <p class="text-xs text-rs-navy/50 mt-1 ml-1">*Masukkan nama file gambar atau kode ikon.</p>
                @error('image') <span class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Deskripsi Layanan</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" placeholder="Jelaskan layanan apa saja yang tersedia..." required>{{ old('description') }}</textarea>
                @error('description') <span class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full py-3.5 bg-rs-navy text-white font-bold rounded-xl shadow-lg hover:bg-rs-green transition-all transform hover:-translate-y-1">
                Simpan Poli
            </button>
        </form>
    </div>
</div>
@endsection