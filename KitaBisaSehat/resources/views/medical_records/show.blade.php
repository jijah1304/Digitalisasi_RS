@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-3xl mx-auto px-6 pb-12">
    <div class="mb-6 print:hidden">
        @if(Auth::user()->role === 'dokter')
            <a href="{{ route('dashboard') }}" class="text-sm text-rs-navy/60 hover:text-rs-teal inline-flex items-center gap-1">
                &larr; Kembali ke Dashboard
            </a>
        @else
            <a href="{{ route('dashboard') }}" class="text-sm text-rs-navy/60 hover:text-rs-teal inline-flex items-center gap-1">
                &larr; Kembali ke Riwayat
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-rs-green text-green-700 rounded-r-xl shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white p-8 md:p-12 rounded-3xl shadow-xl relative overflow-hidden border border-gray-100">
        
        <div class="flex justify-between items-start border-b-2 border-rs-navy/10 pb-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-rs-green rounded-2xl flex items-center justify-center text-white font-bold text-3xl print:bg-rs-green print:text-white">
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
                <p class="text-sm text-rs-navy/60">{{ \Carbon\Carbon::parse($medicalRecord->created_at)->translatedFormat('d F Y') }}</p>
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

        <div class="bg-rs-pale/30 rounded-2xl p-6 mb-8 border border-rs-green/10 print:border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-bold text-rs-navy mb-2 flex items-center gap-2">
                        Diagnosis
                    </h4>
                    <p class="text-rs-navy/80 leading-relaxed">{{ $medicalRecord->diagnosis }}</p>
                </div>
                <div>
                    <h4 class="font-bold text-rs-navy mb-2 flex items-center gap-2">
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
    
    <div class="mt-8 flex flex-col md:flex-row justify-center gap-4 print:hidden">
        
        <button onclick="window.print()" class="px-6 py-3 bg-rs-navy text-white rounded-xl font-bold hover:bg-rs-green transition-colors shadow-lg flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" /></svg>
            Cetak Rekam Medis
        </button>

        @if(Auth::user()->role === 'dokter' && $medicalRecord->appointment->doctor_id === Auth::id())
            
            <a href="{{ route('medical_records.edit', $medicalRecord->id) }}" class="px-6 py-3 bg-yellow-100 text-yellow-700 rounded-xl font-bold hover:bg-yellow-200 transition-colors flex items-center justify-center gap-2 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                Edit Data
            </a>
            
            <form action="{{ route('medical_records.destroy', $medicalRecord->id) }}" method="POST" onsubmit="return confirm('PERINGATAN: Menghapus rekam medis akan mengembalikan stok obat ke gudang. Apakah Anda yakin ingin menghapus?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full md:w-auto px-6 py-3 bg-red-100 text-red-600 rounded-xl font-bold hover:bg-red-600 hover:text-white transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                    Hapus
                </button>
            </form>
        @endif
    </div>
</div>
@endsection