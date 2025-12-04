@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-3xl mx-auto px-6 pb-12">
    <div class="mb-6 print:hidden">
        <a href="{{ route('dashboard') }}" class="text-sm text-rs-navy/60 hover:text-rs-teal inline-flex items-center gap-1">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <div class="bg-white p-8 md:p-12 rounded-3xl shadow-xl relative overflow-hidden border border-gray-100">
        
        <div class="flex justify-between items-start border-b-2 border-rs-navy/10 pb-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-rs-green rounded-2xl flex items-center justify-center text-white font-bold text-3xl">
                    K
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-rs-navy">KitaBisaSehat</h1>
                    <p class="text-sm text-rs-navy/60">Digital Medical Report</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs text-rs-navy/50 uppercase font-bold">ID Rekam Medis</p>
                <p class="text-lg font-mono font-bold text-rs-navy">#MR-{{ str_pad($medicalRecord->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-sm text-rs-navy/60">{{ \Carbon\Carbon::parse($medicalRecord->created_at)->format('d F Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <p class="text-xs text-rs-navy/50 uppercase font-bold mb-1">Pasien</p>
                <h3 class="text-lg font-bold text-rs-navy">{{ $medicalRecord->appointment->patient->name }}</h3>
                <p class="text-sm text-rs-navy/70">{{ $medicalRecord->appointment->patient->email }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-rs-navy/50 uppercase font-bold mb-1">Dokter Pemeriksa</p>
                <h3 class="text-lg font-bold text-rs-navy">{{ $medicalRecord->appointment->doctor->name }}</h3>
                <p class="text-sm text-rs-navy/70">{{ $medicalRecord->appointment->doctor->poli->name ?? 'Dokter Umum' }}</p>
            </div>
        </div>

        <div class="bg-rs-pale/30 rounded-2xl p-6 mb-8 border border-rs-green/10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-bold text-rs-navy mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rs-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Diagnosis
                    </h4>
                    <p class="text-rs-navy/80 leading-relaxed">{{ $medicalRecord->diagnosis }}</p>
                </div>
                <div>
                    <h4 class="font-bold text-rs-navy mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-rs-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        Tindakan Medis
                    </h4>
                    <p class="text-rs-navy/80 leading-relaxed">{{ $medicalRecord->medical_action }}</p>
                </div>
            </div>
            @if($medicalRecord->notes)
                <div class="mt-6 pt-4 border-t border-rs-navy/5">
                    <h4 class="font-bold text-rs-navy mb-1 text-sm">Catatan Dokter:</h4>
                    <p class="text-sm text-rs-navy/70 italic">"{{ $medicalRecord->notes }}"</p>
                </div>
            @endif
        </div>

        <div>
            <h4 class="font-bold text-rs-navy mb-4 text-lg">Resep Obat yang Diberikan</h4>
            <div class="overflow-hidden rounded-xl border border-gray-200">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-rs-navy text-xs uppercase font-bold">
                        <tr>
                            <th class="p-4">Nama Obat</th>
                            <th class="p-4">Tipe</th>
                            <th class="p-4 text-right">Jumlah (Qty)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($medicalRecord->medicines as $med)
                            <tr>
                                <td class="p-4 font-medium text-rs-navy">{{ $med->name }}</td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded text-xs font-bold {{ $med->type == 'keras' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }}">
                                        {{ ucfirst($med->type) }}
                                    </span>
                                </td>
                                <td class="p-4 text-right font-bold">{{ $med->pivot->quantity }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-12 text-center text-xs text-rs-navy/40">
            <p>Dokumen ini diterbitkan secara digital oleh sistem KitaBisaSehat.</p>
        </div>
    </div>
    
    <div class="mt-6 text-center print:hidden">
        <button onclick="window.print()" class="px-6 py-3 bg-rs-navy text-white rounded-xl font-bold hover:bg-rs-green transition-colors shadow-lg">
            Cetak Rekam Medis
        </button>
    </div>
</div>

@endsection