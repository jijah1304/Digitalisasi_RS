@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-rs-navy">Laporan & Analitik</h1>
            <p class="text-rs-navy/60">Data operasional harian Rumah Sakit KitaBisaSehat.</p>
        </div>
        <div class="text-right">
            <span class="text-sm font-bold text-rs-teal bg-rs-teal/10 px-4 py-2 rounded-lg">
                Tanggal: {{ now()->translatedFormat('d F Y') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- 1. LAPORAN PASIEN PER POLI -->
        <div class="glass p-8 rounded-3xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600"></div>
            <h2 class="text-xl font-bold text-rs-navy mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Total Pasien per Poli
            </h2>
            
            <div class="space-y-4">
                @foreach($patientsPerPoli as $poli)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xs">
                                {{ substr($poli->name, 0, 1) }}
                            </div>
                            <span class="font-medium text-rs-navy">{{ $poli->name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-32 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <!-- Bar Chart Sederhana (Persentase visual dummy agar cantik) -->
                                <div class="h-full bg-blue-500" style="width: {{ $poli->total_patients > 0 ? '70%' : '5%' }}"></div>
                            </div>
                            <span class="font-bold text-rs-navy w-8 text-right">{{ $poli->total_patients }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 2. LAPORAN PENGGUNAAN OBAT (HARI INI) -->
        <div class="glass p-8 rounded-3xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-400 to-purple-600"></div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-rs-navy flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    Obat Keluar Hari Ini
                </h2>
                <span class="text-xs font-bold bg-purple-100 text-purple-700 px-3 py-1 rounded-full">Total: {{ $totalMedicineItemsOut }} Unit</span>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-100">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs font-bold text-rs-navy/60 uppercase">
                        <tr>
                            <th class="p-3">Nama Obat</th>
                            <th class="p-3 text-right">Jumlah Terpakai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($medicineUsageToday as $med)
                            <tr>
                                <td class="p-3 text-sm font-medium text-rs-navy">{{ $med->name }}</td>
                                <td class="p-3 text-sm text-right font-bold text-purple-600">{{ $med->total_usage }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="p-6 text-center text-sm text-gray-400 italic">Belum ada penggunaan obat hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 3. KINERJA DOKTER (FULL WIDTH) -->
        <div class="lg:col-span-2 glass p-8 rounded-3xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-400 to-green-600"></div>
            <h2 class="text-xl font-bold text-rs-navy mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Kinerja Dokter (Top Performance)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($doctorPerformance as $index => $doctor)
                    <div class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:shadow-md transition-shadow bg-white/50">
                        <!-- Ranking Badge -->
                        <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $index < 3 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500' }} flex items-center justify-center font-bold text-sm">
                            #{{ $index + 1 }}
                        </div>
                        
                        <!-- Info Dokter -->
                        <div class="flex-grow">
                            <h3 class="font-bold text-rs-navy text-sm">{{ $doctor->name }}</h3>
                            <p class="text-xs text-rs-navy/60">{{ $doctor->poli->name ?? 'Dokter Umum' }}</p>
                        </div>

                        <!-- Total Pasien -->
                        <div class="text-right">
                            <span class="block text-xl font-bold text-rs-green">{{ $doctor->finished_appointments }}</span>
                            <span class="text-[10px] uppercase text-rs-navy/40 font-bold">Pasien</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection