@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-rs-navy">Dashboard Admin</h1>
        <p class="text-rs-navy/60">Kelola operasional rumah sakit dalam satu panel.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="glass p-6 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-sm text-rs-navy/60 font-medium">Total Pasien</p>
                <p class="text-3xl font-bold text-rs-navy">{{ $totalPatients }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        
        <div class="glass p-6 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-sm text-rs-navy/60 font-medium">Total Dokter</p>
                <p class="text-3xl font-bold text-rs-navy">{{ $totalDoctors }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>

        <div class="glass p-6 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-sm text-rs-navy/60 font-medium">Jenis Obat</p>
                <p class="text-3xl font-bold text-rs-navy">{{ $totalMedicines }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
        </div>

        <div class="glass p-6 rounded-2xl flex items-center justify-between border-l-4 border-yellow-400">
            <div>
                <p class="text-sm text-rs-navy/60 font-medium">Butuh Approval</p>
                <p class="text-3xl font-bold text-rs-navy">{{ $pendingAppointments }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <h2 class="text-xl font-bold text-rs-navy mb-4">Menu Manajemen</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <a href="{{ route('polis.index') }}" class="group glass p-6 rounded-2xl hover:bg-white/60 transition-all duration-300 flex items-center gap-4 border border-white/40">
            <div class="w-14 h-14 bg-rs-teal/10 text-rs-teal rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg text-rs-navy">Data Poli</h3>
                <p class="text-sm text-rs-navy/60">Tambah/Edit Poli</p>
            </div>
        </a>

        <a href="{{ route('medicines.index') }}" class="group glass p-6 rounded-2xl hover:bg-white/60 transition-all duration-300 flex items-center gap-4 border border-white/40">
            <div class="w-14 h-14 bg-rs-green/10 text-rs-green rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg text-rs-navy">Data Obat</h3>
                <p class="text-sm text-rs-navy/60">Stok & Jenis Obat</p>
            </div>
        </a>

        <a href="{{ route('appointments.index') }}" class="group glass p-6 rounded-2xl hover:bg-white/60 transition-all duration-300 flex items-center gap-4 border border-white/40">
            <div class="w-14 h-14 bg-yellow-400/10 text-yellow-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg text-rs-navy">Validasi Janji</h3>
                <p class="text-sm text-rs-navy/60">Approve/Reject Pasien</p>
            </div>
        </a>

    </div>
</div>
@endsection