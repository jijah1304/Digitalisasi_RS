@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-rs-navy">Halo, {{ Auth::user()->name }}</h1>
            <p class="text-rs-navy/60">Selamat bertugas di {{ Auth::user()->poli->name ?? 'Poli' }}.</p>
        </div>
        <a href="{{ route('schedules.index') }}" class="px-5 py-2.5 bg-rs-navy text-white rounded-xl shadow hover:bg-rs-green transition-colors text-sm font-medium">
            Atur Jadwal Praktik
        </a>
    </div>

    <div class="mb-10">
        <h2 class="text-xl font-bold text-rs-navy mb-4 flex items-center gap-2">
            <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
            Antrean Pasien Hari Ini (Approved)
        </h2>
        
        @if($todayAppointments->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($todayAppointments as $appointment)
                    <div class="glass p-6 rounded-2xl border-l-4 border-rs-green relative group hover:bg-white/60 transition-all">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-rs-navy">{{ $appointment->patient->name }}</h3>
                                <p class="text-xs text-rs-navy/60">Jam: {{ $appointment->schedule->start_time }} - {{ $appointment->schedule->end_time }}</p>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-bold">Siap</span>
                        </div>
                        
                        <div class="bg-white/40 p-3 rounded-xl mb-4 text-sm text-rs-navy/80 italic">
                            "{{ $appointment->complaint }}"
                        </div>

                        <a href="{{ route('medical_records.create', $appointment->id) }}" class="block w-full text-center py-2.5 bg-rs-green text-white rounded-xl font-semibold hover:shadow-lg transition-all transform group-hover:-translate-y-1">
                            Periksa Sekarang
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="glass p-8 rounded-2xl text-center text-rs-navy/60 border-2 border-dashed border-rs-navy/10">
                <p>Belum ada pasien yang disetujui untuk hari ini.</p>
            </div>
        @endif
    </div>

    <div class="glass p-6 rounded-2xl flex items-center justify-between">
        <div>
            <h3 class="font-bold text-lg text-rs-navy">Permintaan Janji Temu Baru</h3>
            <p class="text-sm text-rs-navy/60">Cek daftar pasien yang menunggu konfirmasi jadwal.</p>
        </div>
        <a href="{{ route('appointments.index') }}" class="px-5 py-2.5 border border-rs-navy/20 text-rs-navy rounded-xl hover:bg-rs-navy hover:text-white transition-all font-medium">
            Lihat Semua Permintaan
        </a>
    </div>
</div>
@endsection