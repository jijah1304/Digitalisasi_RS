@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <!-- Hero Section -->
    <div class="glass rounded-3xl p-8 mb-10 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-4xl font-bold text-rs-navy mb-2">Apa kabar, {{ Auth::user()->name }}?</h1>
            <p class="text-rs-navy/70 mb-6 max-w-lg">Jangan lupa jaga kesehatanmu hari ini. Jika merasa kurang sehat, segera konsultasikan dengan dokter kami.</p>
            
            <a href="{{ route('appointments.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-rs-navy text-white rounded-xl font-semibold shadow-lg hover:bg-rs-green transition-all transform hover:-translate-y-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                Buat Janji Temu Baru
            </a>
        </div>
        <!-- Ilustrasi sederhana -->
        <div class="hidden md:block text-rs-teal/20">
            <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 11h-4v4h-4v-4H6v-4h4V6h4v4h4v4z"/></svg>
        </div>
    </div>

    <!-- Status Janji Temu Terakhir -->
    <h2 class="text-xl font-bold text-rs-navy mb-5">Riwayat & Status Janji Temu</h2>

    <div class="glass rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                    <tr>
                        <th class="p-4 font-semibold">Tanggal</th>
                        <th class="p-4 font-semibold">Dokter & Poli</th>
                        <th class="p-4 font-semibold">Keluhan</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold">Aksi / Keterangan</th> <!-- Judul Kolom Diubah -->
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($appointments as $apt)
                        <tr class="hover:bg-white/30 transition-colors">
                            <td class="p-4 text-sm text-rs-navy font-medium">
                                {{ \Carbon\Carbon::parse($apt->date)->translatedFormat('d M Y') }}<br>
                                <span class="text-xs text-rs-navy/60">{{ $apt->schedule->start_time }}</span>
                            </td>
                            <td class="p-4">
                                <div class="text-sm font-bold text-rs-navy">{{ $apt->doctor->name }}</div>
                                <div class="text-xs text-rs-teal">{{ $apt->doctor->poli->name ?? '-' }}</div>
                            </td>
                            <td class="p-4 text-sm text-rs-navy/70 italic max-w-xs truncate">
                                "{{ $apt->complaint }}"
                            </td>
                            <td class="p-4">
                                @if($apt->status == 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">Menunggu</span>
                                @elseif($apt->status == 'approved')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Disetujui</span>
                                @elseif($apt->status == 'selesai')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Selesai</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Ditolak</span>
                                @endif
                            </td>
                            
                            <!-- LOGIKA KOLOM AKSI DIPERBARUI -->
                            <td class="p-4">
                                @if($apt->status == 'selesai' && $apt->medicalRecord)
                                    <a href="{{ route('medical_records.show', $apt->medicalRecord->id) }}" class="text-xs font-bold text-rs-teal hover:underline inline-flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                        Lihat Hasil
                                    </a>
                                
                                {{-- Jika DITOLAK, Tampilkan Alasannya --}}
                                @elseif($apt->status == 'rejected')
                                    <div class="text-xs text-red-500 font-medium">
                                        <span class="font-bold">Alasan:</span> 
                                        <span class="italic">"{{ $apt->rejection_reason }}"</span>
                                    </div>

                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-rs-navy/60">
                                Belum ada riwayat janji temu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection