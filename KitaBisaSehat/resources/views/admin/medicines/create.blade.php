@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-2xl mx-auto px-6 pb-12">
    <div class="mb-8">
        <a href="{{ route('medicines.index') }}" class="text-sm text-rs-navy/60 hover:text-rs-teal mb-2 inline-flex items-center gap-1">&larr; Kembali</a>
        <h1 class="text-3xl font-bold text-rs-navy">Tambah Obat</h1>
    </div>

    <div class="glass p-8 rounded-3xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-rs-green to-rs-teal"></div>
        
        <form action="{{ route('medicines.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Nama Obat -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Nama Obat</label>
                <input type="text" name="name" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal outline-none" placeholder="Contoh: Paracetamol 500mg" required>
            </div>

            <!-- Link Gambar (FITUR BARU) -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Link Gambar / Ikon</label>
                <input type="text" name="image" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal outline-none" placeholder="https://i.pinimg.com/..." required>
                <p class="text-xs text-rs-navy/50 mt-1 ml-1">*Paste link gambar dari internet (Google/Pinterest) di sini.</p>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <!-- Tipe -->
                <div>
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Tipe Obat</label>
                    <select name="type" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal outline-none cursor-pointer">
                        <option value="biasa">Biasa (OTC)</option>
                        <option value="keras">Keras (Resep)</option>
                    </select>
                </div>
                <!-- Stok -->
                <div>
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Stok Awal</label>
                    <input type="number" name="stock" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal outline-none" min="0" required>
                </div>
            </div>

            <!-- Tanggal Kadaluarsa -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Tanggal Kedaluwarsa</label>
                <input type="date" name="expiry_date" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal outline-none" required>
                <p class="text-xs text-rs-navy/50 mt-1 ml-1">*Pilih tanggal kadaluarsa obat untuk notifikasi otomatis.</p>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Deskripsi / Kegunaan</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal outline-none" required></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 bg-rs-navy text-white font-bold rounded-xl shadow-lg hover:bg-rs-green transition-all transform hover:-translate-y-1">Simpan Obat</button>
        </form>
    </div>
</div>
@endsection