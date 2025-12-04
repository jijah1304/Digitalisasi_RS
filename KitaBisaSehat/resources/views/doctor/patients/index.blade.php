@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-rs-navy">Riwayat Pasien</h1>
            <p class="text-rs-navy/60">Daftar pasien yang pernah Anda tangani.</p>
        </div>
        
        <!-- Search Bar -->
        <form action="{{ route('doctor.patients.index') }}" method="GET" class="w-full md:w-1/3 relative">
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none shadow-sm text-rs-navy placeholder-rs-navy/40 transition-all focus:border-rs-teal" 
                   placeholder="Cari nama pasien atau diagnosis...">
            <svg class="w-5 h-5 absolute left-3 top-3 text-rs-navy/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </form>
    </div>

    <!-- Tabel Riwayat -->
    <div class="glass rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                    <tr>
                        <th class="p-5 font-semibold">Tanggal Periksa</th>
                        <th class="p-5 font-semibold">Nama Pasien</th>
                        <th class="p-5 font-semibold">Diagnosis</th>
                        <th class="p-5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($histories as $history)
                        <tr class="hover:bg-white/30 transition-colors">
                            <td class="p-5 text-sm font-medium text-rs-navy">
                                {{ \Carbon\Carbon::parse($history->date)->translatedFormat('d M Y') }}
                                <div class="text-xs text-rs-navy/50 mt-1">
                                    {{ $history->schedule->start_time }} - {{ $history->schedule->end_time }}
                                </div>
                            </td>
                            <td class="p-5">
                                <div class="font-bold text-rs-navy">{{ $history->patient->name }}</div>
                                <div class="text-xs text-rs-navy/50">{{ $history->patient->email }}</div>
                            </td>
                            <td class="p-5">
                                <span class="italic text-rs-navy/80 block max-w-xs truncate" title="{{ $history->medicalRecord->diagnosis ?? '' }}">
                                    {{ $history->medicalRecord->diagnosis ?? '(Belum ada data)' }}
                                </span>
                            </td>
                            <td class="p-5 text-center">
                                @if($history->medicalRecord)
                                    <a href="{{ route('medical_records.show', $history->medicalRecord->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-rs-teal hover:underline bg-rs-teal/10 px-3 py-1.5 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                        Lihat Rekam Medis
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Data Medis Kosong</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-10 text-center text-rs-navy/50 italic">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-rs-navy/20 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p>Belum ada riwayat pasien yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        <div class="p-5 border-t border-gray-100">
            {{ $histories->links() }}
        </div>
    </div>
</div>
@endsection