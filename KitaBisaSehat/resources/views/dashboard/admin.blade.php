@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-rs-navy">Dashboard Admin</h1>
        <p class="text-rs-navy/60">Ringkasan operasional Rumah Sakit KitaBisaSehat.</p>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        
        <!-- Card 1: Pending Approval (Prioritas) -->
        <div class="glass p-6 rounded-2xl flex items-center justify-between border-l-4 border-yellow-400">
            <div>
                <p class="text-sm text-rs-navy/60 font-medium">Menunggu Approval</p>
                <p class="text-3xl font-bold text-rs-navy">{{ $pendingAppointments }}</p>
                <a href="{{ route('appointments.index') }}" class="text-xs text-yellow-600 font-bold hover:underline mt-1 block">Lihat Permintaan &rarr;</a>
            </div>
            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Total Dokter -->
        <div class="glass p-6 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-sm text-rs-navy/60 font-medium">Total Dokter</p>
                <p class="text-3xl font-bold text-rs-navy">{{ $totalDoctors }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>

        <!-- Card 3: Total Pasien -->
        <div class="glass p-6 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-sm text-rs-navy/60 font-medium">Total Pasien</p>
                <p class="text-3xl font-bold text-rs-navy">{{ $totalPatients }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Card 4: Total Obat -->
        <div class="glass p-6 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-sm text-rs-navy/60 font-medium">Jenis Obat</p>
                <p class="text-3xl font-bold text-rs-navy">{{ $totalMedicines }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Section Baru: Notifikasi Obat -->
    @if($medicinesNeedingNotification->count() > 0)
    <div class="mb-10">
        <h2 class="text-xl font-bold text-rs-navy mb-4 flex items-center gap-2">
            <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
            Notifikasi Obat ({{ $medicinesNeedingNotification->count() }} item)
        </h2>

        <div class="glass rounded-3xl overflow-hidden shadow-sm border-l-4 border-red-400">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-red-50 text-rs-navy text-sm uppercase tracking-wider">
                        <tr>
                            <th class="p-4 font-semibold">Nama Obat</th>
                            <th class="p-4 font-semibold">Stok</th>
                            <th class="p-4 font-semibold">Tanggal Kedaluwarsa</th>
                            <th class="p-4 font-semibold">Status</th>
                            <th class="p-4 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($medicinesNeedingNotification as $medicine)
                            <tr class="hover:bg-red-50/50 transition-colors">
                                <td class="p-4">
                                    <div class="font-bold text-rs-navy">{{ $medicine->name }}</div>
                                    <div class="text-xs text-rs-navy/60">{{ $medicine->description }}</div>
                                </td>
                                <td class="p-4">
                                    <span class="px-3 py-1 {{ $medicine->stock <= 20 ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }} rounded-full text-xs font-bold">
                                        {{ $medicine->stock }} unit
                                    </span>
                                </td>
                                <td class="p-4 text-sm font-mono text-rs-navy">
                                    @if($medicine->expiry_date)
                                        {{ \Carbon\Carbon::parse($medicine->expiry_date)->translatedFormat('d M Y') }}
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($medicine->stock <= 20)
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Stok Rendah</span>
                                    @elseif($medicine->expiry_date && $medicine->expiry_date <= now()->addDays(30))
                                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-bold">Mendekati Kedaluwarsa</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <a href="{{ route('medicines.edit', $medicine->id) }}" class="text-xs font-bold text-rs-teal hover:underline">
                                        Edit Obat
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Section Baru: Dokter Bertugas Hari Ini -->
    <div class="mb-10">
        <h2 class="text-xl font-bold text-rs-navy mb-4 flex items-center gap-2">
            <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
            Dokter Bertugas Hari Ini ({{ $todayIndo }})
        </h2>

        <div class="glass rounded-3xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                        <tr>
                            <th class="p-5 font-semibold">Nama Dokter</th>
                            <th class="p-5 font-semibold">Poli</th>
                            <th class="p-5 font-semibold">Jam Praktik</th>
                            <th class="p-5 font-semibold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($doctorsOnDuty as $doctor)
                            <tr class="hover:bg-white/30 transition-colors">
                                <td class="p-5">
                                    <div class="font-bold text-rs-navy">{{ $doctor->name }}</div>
                                    <div class="text-xs text-rs-navy/50">{{ $doctor->email }}</div>
                                </td>
                                <td class="p-5">
                                    <span class="px-3 py-1 bg-rs-teal/10 text-rs-teal rounded-full text-xs font-bold">
                                        {{ $doctor->poli->name ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="p-5 text-sm font-mono text-rs-navy">
                                    @foreach($doctor->schedules as $schedule)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-rs-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $schedule->start_time }} - {{ $schedule->end_time }}
                                        </div>
                                    @endforeach
                                </td>
                                <td class="p-5 text-center">
                                    <span class="inline-flex items-center gap-1 text-xs font-bold text-green-600 bg-green-100 px-3 py-1 rounded-full">
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span> Aktif
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-rs-navy/50 italic">
                                    Tidak ada dokter yang memiliki jadwal hari ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Section Baru: Feedback Pasien Terbaru -->
    <div class="mb-10">
        <h2 class="text-xl font-bold text-rs-navy mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            Feedback Pasien Terbaru
        </h2>

        <div class="glass rounded-3xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                        <tr>
                            <th class="p-5 font-semibold">Tanggal</th>
                            <th class="p-5 font-semibold">Pasien</th>
                            <th class="p-5 font-semibold">Dokter</th>
                            <th class="p-5 font-semibold">Rating</th>
                            <th class="p-5 font-semibold">Ulasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse(\App\Models\Appointment::with(['patient', 'doctor'])->whereNotNull('rating')->latest()->take(5)->get() as $feedback)
                            <tr class="hover:bg-white/30 transition-colors">
                                <td class="p-5 text-sm font-medium text-rs-navy">
                                    {{ \Carbon\Carbon::parse($feedback->updated_at)->translatedFormat('d M Y') }}
                                    <div class="text-xs text-rs-navy/50">{{ \Carbon\Carbon::parse($feedback->updated_at)->format('H:i') }}</div>
                                </td>

                                <td class="p-5">
                                    <div class="font-bold text-rs-navy">{{ $feedback->patient->name }}</div>
                                    <div class="text-xs text-rs-navy/50">{{ $feedback->patient->email }}</div>
                                </td>

                                <td class="p-5">
                                    <div class="font-bold text-rs-navy">{{ $feedback->doctor->name }}</div>
                                    <div class="text-xs text-rs-teal">{{ $feedback->doctor->poli->name ?? 'Umum' }}</div>
                                </td>

                                <td class="p-5">
                                    <div class="flex text-yellow-400 text-sm">
                                        @for($i=0; $i<$feedback->rating; $i++) ★ @endfor
                                        @for($i=$feedback->rating; $i<5; $i++) <span class="text-gray-300">★</span> @endfor
                                    </div>
                                    <div class="text-xs font-bold text-rs-navy mt-1">{{ $feedback->rating }}.0</div>
                                </td>

                                <td class="p-5 text-sm text-rs-navy/80 italic max-w-xs">
                                    {{ Str::limit($feedback->feedback ?? '-', 80) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-rs-navy/50 italic">
                                    Belum ada feedback dari pasien.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Menu Grid (Biarkan tetap ada untuk akses cepat) -->
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