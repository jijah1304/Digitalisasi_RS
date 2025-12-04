@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-2xl mx-auto px-6 pb-12">
    <div class="mb-8">
        <a href="{{ route('medicines.index') }}" class="text-sm text-rs-navy/60 hover:text-rs-teal mb-2 inline-flex items-center gap-1 transition-colors">
            &larr; Batal & Kembali
        </a>
        <h1 class="text-3xl font-bold text-rs-navy">Edit Data Obat</h1>
    </div>

    <div class="glass p-8 rounded-3xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-400 to-orange-500"></div>
        
        <form action="{{ route('medicines.update', $medicine->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT') <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Nama Obat</label>
                <input type="text" name="name" value="{{ old('name', $medicine->name) }}" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy placeholder-rs-navy/30" required>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Tipe Obat</label>
                    <select name="type" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none cursor-pointer">
                        <option value="biasa" {{ $medicine->type == 'biasa' ? 'selected' : '' }}>Biasa (OTC)</option>
                        <option value="keras" {{ $medicine->type == 'keras' ? 'selected' : '' }}>Keras (Resep)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Stok Saat Ini</label>
                    <input type="number" name="stock" value="{{ old('stock', $medicine->stock) }}" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" min="0" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Deskripsi / Kegunaan</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" required>{{ old('description', $medicine->description) }}</textarea>
            </div>

            <button type="submit" class="w-full py-3.5 bg-rs-navy text-white font-bold rounded-xl shadow-lg hover:bg-rs-green transition-all transform hover:-translate-y-1">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection