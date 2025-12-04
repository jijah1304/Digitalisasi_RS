@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-xl mx-auto px-6 pb-12">
    <div class="mb-8">
        <a href="{{ route('schedules.index') }}" class="text-sm text-rs-navy/60 hover:text-rs-teal mb-2 inline-flex items-center gap-1 transition-colors">
            &larr; Kembali ke Jadwal
        </a>
        <h1 class="text-3xl font-bold text-rs-navy">Tambah Jadwal</h1>
    </div>

    <div class="glass p-8 rounded-3xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-rs-green to-rs-teal"></div>
        
        <form action="{{ route('schedules.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Hari Praktik</label>
                <div class="relative">
                    <select name="day" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none appearance-none cursor-pointer" required>
                        <option value="" disabled selected>-- Pilih Hari --</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-rs-navy/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Jam Mulai Praktik</label>
                <input type="time" name="start_time" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none cursor-pointer" required>
                <p class="mt-2 text-xs text-rs-teal font-medium">
                    * Durasi konsultasi otomatis diatur 30 menit per sesi.
                </p>
            </div>

            <button type="submit" class="w-full py-3.5 bg-rs-navy text-white font-bold rounded-xl shadow-lg hover:bg-rs-green transition-all transform hover:-translate-y-1">
                Simpan Jadwal
            </button>
        </form>
    </div>
</div>
@endsection