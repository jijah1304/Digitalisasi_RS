@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-rs-navy">Riwayat Resep Obat</h1>
                <p class="text-rs-navy/60">Daftar resep obat yang telah diberikan dokter.</p>
            </div>
        </div>
    </div>

    <!-- Prescriptions List -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($prescriptions as $prescription)
            <div class="glass rounded-2xl p-6 shadow-sm">
                <!-- Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-rs-green/10 text-rs-green rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-rs-navy">
                                Resep - {{ \Carbon\Carbon::parse($prescription->appointment->date)->translatedFormat('d M Y') }}
                            </h3>
                            <p class="text-sm text-rs-navy/60">
                                Dokter: {{ $prescription->appointment->doctor->name }} •
                                Poli: {{ $prescription->appointment->doctor->poli->name ?? 'Umum' }}
                            </p>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    @if($prescription->appointment->status === 'selesai')
                        <span class="px-3 py-2 text-green-100 bg-green-800 rounded-full text-sm font-medium text-center">
                            Sudah Diambil
                        </span>
                    @else
                        <span class="px-3 py-2 text-red-100 bg-red-800 rounded-full text-sm font-medium text-center">
                            Belum Diambil
                        </span>
                    @endif
                </div>

                <!-- Medicines List -->
                <div class="bg-white/30 rounded-xl p-4">
                    <h4 class="font-semibold text-rs-navy mb-3">Daftar Obat:</h4>
                    <div class="space-y-2">
                        @forelse($prescription->medicines as $medicine)
                            <div class="flex items-center justify-between py-2 px-3 bg-white/50 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm font-bold">
                                        💊
                                    </div>
                                    <div>
                                        <span class="font-medium text-rs-navy">{{ $medicine->name }}</span>
                                        <div class="text-xs text-rs-navy/60">
                                            {{ $medicine->description ?? 'Tidak ada deskripsi' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-rs-navy">{{ $medicine->pivot->quantity }} {{ $medicine->unit }}</div>
                                    <div class="text-xs text-rs-navy/60">Jumlah</div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-rs-navy/50 italic">Tidak ada obat dalam resep ini.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Notes -->
                @if($prescription->notes)
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-yellow-800">Catatan Dokter:</p>
                                <p class="text-sm text-yellow-700">{{ $prescription->notes }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Button -->
                <div class="mt-4 flex justify-end">
                    <a href="{{ route('medical_records.show', $prescription->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-rs-green text-white text-sm font-medium rounded-xl hover:bg-rs-green/80 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat Rekam Medis Lengkap
                    </a>
                </div>
            </div>
        @empty
            <div class="glass rounded-2xl p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-rs-navy mb-2">Belum Ada Resep Obat</h3>
                <p class="text-rs-navy/60">Anda belum memiliki resep obat dari dokter.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($prescriptions->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $prescriptions->links() }}
        </div>
    @endif
</div>
@endsection
