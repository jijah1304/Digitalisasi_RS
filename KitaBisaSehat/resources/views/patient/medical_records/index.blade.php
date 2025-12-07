@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-rs-navy">Riwayat Rekam Medis</h1>
                <p class="text-rs-navy/60">Daftar rekam medis pemeriksaan kesehatan Anda.</p>
            </div>
        </div>
    </div>

    <!-- Medical Records List -->
    <div class="glass rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                    <tr>
                        <th class="p-5 font-semibold">Tanggal</th>
                        <th class="p-5 font-semibold">Dokter</th>
                        <th class="p-5 font-semibold">Poli</th>
                        <th class="p-5 font-semibold">Diagnosis</th>
                        <th class="p-5 font-semibold">Tindakan</th>
                        <th class="p-5 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($medicalRecords as $record)
                        <tr class="hover:bg-white/30 transition-colors">
                            <td class="p-5 text-sm font-medium text-rs-navy">
                                {{ \Carbon\Carbon::parse($record->appointment->date)->translatedFormat('d M Y') }}
                                <div class="text-xs text-rs-navy/50">{{ \Carbon\Carbon::parse($record->appointment->date)->format('H:i') }}</div>
                            </td>

                            <td class="p-5">
                                <div class="font-bold text-rs-navy">{{ $record->appointment->doctor->name }}</div>
                            </td>

                            <td class="p-5">
                                <span class="px-3 py-1 bg-rs-teal/10 text-rs-teal rounded-full text-xs font-medium">
                                    {{ $record->appointment->doctor->poli->name ?? 'Umum' }}
                                </span>
                            </td>

                            <td class="p-5 text-sm text-rs-navy/80 max-w-xs">
                                {{ Str::limit($record->diagnosis, 50) }}
                            </td>

                            <td class="p-5 text-sm text-rs-navy/80 max-w-xs">
                                {{ Str::limit($record->medical_action, 50) }}
                            </td>

                            <td class="p-5">
                                <a href="{{ route('medical_records.show', $record->id) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-rs-green text-white text-sm font-medium rounded-xl hover:bg-rs-green/80 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-rs-navy/50 italic">
                                Belum ada rekam medis yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($medicalRecords->hasPages())
            <div class="px-6 py-4 bg-white/20 border-t border-white/30">
                {{ $medicalRecords->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
