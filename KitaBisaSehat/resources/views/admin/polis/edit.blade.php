@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-2xl mx-auto px-6 pb-12">
    <div class="mb-8">
        <a href="{{ route('polis.index') }}" class="text-sm text-rs-navy/60 hover:text-rs-teal mb-2 inline-flex items-center gap-1 transition-colors">
            &larr; Batal Edit
        </a>
        <h1 class="text-3xl font-bold text-rs-navy">Edit Poli</h1>
        <p class="text-sm text-rs-teal">{{ $poli->name }}</p>
    </div>

    <div class="glass p-8 rounded-3xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-400 to-orange-500"></div>
        
        <form action="{{ route('polis.update', $poli->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Poli -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Nama Poli</label>
                <input type="text" name="name" value="{{ old('name', $poli->name) }}" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" required>
                @error('name') <span class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Ikon (Teks) dengan Preview -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Nama Ikon / Gambar</label>
                
                <!-- PREVIEW VISUAL -->
                <div class="mb-3 flex items-center gap-3 bg-white/40 p-3 rounded-xl border border-white/50">
                    <span class="text-xs text-rs-navy/60 font-bold uppercase">Preview:</span>
                    @if(preg_match('/\.(jpg|jpeg|png|svg|webp)$/i', $poli->image))
                        <img src="{{ asset($poli->image) }}" class="h-10 w-10 object-contain rounded bg-white shadow-sm" alt="Preview">
                    @else
                        <div class="h-10 w-10 flex items-center justify-center bg-rs-teal/20 text-rs-teal rounded">
                            <i class="{{ $poli->image }} text-xl"></i>
                        </div>
                    @endif
                </div>

                <input type="text" name="image" value="{{ old('image', $poli->image) }}" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" required>
                <p class="text-xs text-rs-navy/50 mt-1 ml-1">*Isi dengan nama file (ex: poli-gigi.png) yang ada di folder public, atau class icon.</p>
                @error('image') <span class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Deskripsi Layanan</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" required>{{ old('description', $poli->description) }}</textarea>
                @error('description') <span class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full py-3.5 bg-rs-navy text-white font-bold rounded-xl shadow-lg hover:bg-rs-green transition-all transform hover:-translate-y-1">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection