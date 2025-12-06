@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-rs-navy">Rekam Medis Pasien</h1>
            <p class="text-rs-navy/60">Daftar rekam medis dari janji temu yang telah disetujui</p>
        </div>
    </div>

    <!-- Medical Records List -->
    @if($medicalRecords->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($medicalRecords as $record)
                <div class="glass p-6 rounded-2xl hover:bg-white/60 transition-all group">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-rs-navy">{{ $record->appointment->patient->name }}</h3>
                            <p class="text-xs text-rs-navy/60 font-mono">
                                {{ \Carbon\Carbon::parse($record->created_at)->translatedFormat('d M Y, H:i') }}
                            </p>
                        </div>
                        <span class="px-2 py-1 bg-rs-green/10 text-rs-green text-[10px] uppercase tracking-wide rounded font-bold">Rekam Medis</span>
                    </div>

                    <div class="space-y-3 mb-4">
                        <div>
                            <span class="text-xs font-semibold text-rs-navy/70 uppercase tracking-wide">Diagnosis</span>
                            <p class="text-sm text-rs-navy italic">{{ Str::limit($record->diagnosis, 60) }}</p>
                        </div>

                        <div>
                            <span class="text-xs font-semibold text-rs-navy/70 uppercase tracking-wide">Tindakan</span>
                            <p class="text-sm text-rs-navy">{{ Str::limit($record->medical_action, 60) }}</p>
                        </div>
                    </div>

                    <a href="{{ route('medical_records.show', $record->id) }}" class="block w-full text-center py-2.5 bg-rs-teal text-white rounded-xl font-semibold hover:shadow-lg transition-all transform group-hover:-translate-y-1">
                        Lihat Detail Lengkap
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="glass p-12 rounded-2xl text-center text-rs-navy/60 border-2 border-dashed border-rs-navy/10">
            <svg class="w-16 h-16 mx-auto mb-4 text-rs-navy/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-lg font-medium">Belum ada rekam medis</p>
            <p class="text-sm">Rekam medis akan muncul setelah Anda memeriksa pasien dengan janji temu yang disetujui.</p>
        </div>
    @endif
</div>
@endsection
