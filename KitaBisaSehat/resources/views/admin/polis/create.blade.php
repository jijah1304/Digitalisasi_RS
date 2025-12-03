@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-2xl mx-auto px-6 pb-12">
    <div class="mb-8">
        <a href="{{ route('polis.index') }}" class="text-sm text-rs-navy/60 hover:text-rs-teal mb-2 inline-flex items-center gap-1 transition-colors">
            &larr; Kembali ke List Poli
        </a>
        <h1 class="text-3xl font-bold text-rs-navy">Tambah Poli Baru</h1>
    </div>

    <div class="glass p-8 rounded-3xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-rs-green to-rs-teal"></div>
        
        <form action="{{ route('polis.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Nama Poli</label>
                <input type="text" name="name" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy placeholder-rs-navy/30" placeholder="Contoh: Poli Mata" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Deskripsi Layanan</label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy placeholder-rs-navy/30" placeholder="Jelaskan layanan apa saja yang tersedia..." required></textarea>
            </div>

            <button type="submit" class="w-full py-3.5 bg-rs-navy text-white font-bold rounded-xl shadow-lg hover:bg-rs-green transition-all transform hover:-translate-y-1">
                Simpan Data Poli
            </button>
        </form>
    </div>
</div>
@endsection