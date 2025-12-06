@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-rs-navy">Halo, {{ Auth::user()->name }}</h1>
            <p class="text-rs-navy/60">Selamat bertugas di {{ Auth::user()->poli->name ?? 'Poli Umum' }}.</p>
        </div>
        
        <div class="flex gap-3">
            <!-- Akses Cepat Pengaturan Jadwal -->
            <a href="{{ route('schedules.index') }}" class="px-5 py-2.5 bg-rs-navy text-white rounded-xl shadow hover:bg-rs-green transition-colors text-sm font-medium flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
                Atur Jadwal Praktik
            </a>
        </div>
    </div>

    <!-- Statistik Kecil (Pending Requests) -->
    @if($pendingRequests > 0)
        <div class="mb-8">
            <a href="{{ route('appointments.index') }}" class="block glass p-4 rounded-xl border-l-4 border-yellow-400 hover:bg-white/60 transition-all group">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-yellow-100 p-2 rounded-lg text-yellow-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-rs-navy">Permintaan Janji Temu Baru</h3>
                            <p class="text-sm text-rs-navy/60">Ada <span class="font-bold text-yellow-600">{{ $pendingRequests }} pasien</span> menunggu konfirmasi Anda.</p>
                        </div>
                    </div>
                    <span class="text-rs-teal group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>
        </div>
    @endif

    <!-- Section 1: Antrean Pasien Hari Ini (Approved) -->
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
                                <p class="text-xs text-rs-navy/60 font-mono">
                                    {{ $appointment->schedule->start_time }} - {{ $appointment->schedule->end_time }}
                                </p>
                            </div>
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] uppercase tracking-wide rounded font-bold">Siap</span>
                        </div>
                        
                        <div class="bg-white/40 p-3 rounded-xl mb-4 text-sm text-rs-navy/80 italic line-clamp-2">
                            "{{ $appointment->complaint }}"
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('medical_records.create', $appointment->id) }}" class="flex-1 text-center py-2.5 bg-rs-green text-white rounded-xl font-semibold hover:shadow-lg transition-all transform group-hover:-translate-y-1">
                                Periksa Sekarang
                            </a>
                            @if($appointment->medicalRecord)
                                <a href="{{ route('medical_records.show', $appointment->medicalRecord->id) }}" class="px-3 py-2.5 bg-rs-teal text-white rounded-xl font-semibold hover:shadow-lg transition-all transform group-hover:-translate-y-1" title="Lihat Rekam Medis">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="glass p-8 rounded-2xl text-center text-rs-navy/60 border-2 border-dashed border-rs-navy/10">
                <p>Belum ada pasien yang disetujui untuk diperiksa hari ini.</p>
            </div>
        @endif
    </div>

    <!-- Section 2: 5 Pasien Terakhir Diperiksa (DENGAN RATING) -->
    <div>
        <h2 class="text-xl font-bold text-rs-navy mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rs-teal" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
            Riwayat Pemeriksaan Terakhir
        </h2>

        <div class="glass rounded-3xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                        <tr>
                            <th class="p-5 font-semibold">Tanggal</th>
                            <th class="p-5 font-semibold">Nama Pasien</th>
                            <th class="p-5 font-semibold">Diagnosis Utama</th>
                            <th class="p-5 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentPatients as $patient)
                            <tr class="hover:bg-white/30 transition-colors">
                                <td class="p-5 text-sm font-medium text-rs-navy">
                                    {{ \Carbon\Carbon::parse($patient->updated_at)->translatedFormat('d M Y') }}
                                    <div class="text-xs text-rs-navy/50">{{ \Carbon\Carbon::parse($patient->updated_at)->format('H:i') }}</div>
                                </td>
                                
                                <td class="p-5">
                                    <div class="font-bold text-rs-navy">{{ $patient->patient->name }}</div>
                                    <div class="text-xs text-rs-navy/50 mb-1">{{ $patient->patient->email }}</div>
                                    
                                    <!-- FITUR BARU: Menampilkan Rating -->
                                    @if($patient->rating)
                                        <div class="inline-flex items-center gap-1 bg-yellow-50 px-2 py-1 rounded border border-yellow-100" title="Ulasan: {{ $patient->feedback }}">
                                            <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            <span class="text-xs font-bold text-rs-navy">{{ $patient->rating }}.0</span>
                                        </div>
                                    @endif
                                </td>

                                <td class="p-5 text-sm text-rs-navy/80 italic">
                                    {{ Str::limit($patient->medicalRecord->diagnosis ?? '-', 50) }}
                                </td>
                                
                                <td class="p-5 text-center">
                                    @if($patient->medicalRecord)
                                        <a href="{{ route('medical_records.show', $patient->medicalRecord->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-rs-teal hover:underline bg-rs-teal/10 px-3 py-1.5 rounded-lg transition-colors">
                                            Lihat Detail
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg">
                                            Belum Ada Rekam Medis
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-rs-navy/50 italic">
                                    Belum ada riwayat pemeriksaan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection