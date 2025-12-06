@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-rs-pale relative overflow-hidden">
    <!-- Header -->
    <div class="relative z-10 pt-8 pb-4">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center">
                <nav class="flex items-center gap-3 shrink-0">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-rs-green rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            K
                        </div>
                        <span class="text-2xl font-bold text-rs-navy tracking-wide">KitaBisaSehat</span>
                    </a>
                </nav>
                <a href="{{ route('register') }}" class="px-6 py-3 bg-rs-navy text-white rounded-xl font-semibold shadow-lg hover:bg-rs-green transition-all duration-300 transform hover:-translate-y-1">
                    Daftar Pasien
                </a>
            </div>
        </div>
    </div>


    <!-- Main Content Grid -->
    <div class="relative z-10 max-w-7xl mx-auto px-6 pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 h-[calc(100vh-200px)]">

            <!-- Polyclinics Section -->
            <div class="glass rounded-3xl p-8 hover:bg-white/60 transition-all duration-300 group overflow-hidden">
                <div class="flex items-center mb-6">
                    <div class="p-3 bg-rs-green/10 rounded-xl mr-4 group-hover:bg-rs-green/20 transition-colors">
                        <svg class="w-8 h-8 text-rs-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-rs-navy">Poliklinik</h2>
                </div>

                <div class="space-y-6 overflow-y-auto max-h-[calc(100%-120px)] scrollbar-thin scrollbar-thumb-rs-mint scrollbar-track-transparent">
                    @forelse($polis as $poli)
                        <div class="glass p-6 rounded-xl border border-rs-mint/30 hover:bg-rs-mint/20 transition-all duration-300 group/item">
                            <div class="flex items-start gap-4">
                                <!-- Large Image/Icon -->
                                <div class="flex-shrink-0">
                                    @if($poli->image)
                                        @if(str_starts_with($poli->image, 'http'))
                                            <img src="{{ $poli->image }}"
                                                 alt="{{ $poli->name }}"
                                                 class="w-20 h-20 rounded-xl object-cover border-2 border-rs-mint/30 shadow-lg">
                                        @else
                                            <img src="{{ asset('storage/' . $poli->image) }}"
                                                 alt="{{ $poli->name }}"
                                                 class="w-20 h-20 rounded-xl object-cover border-2 border-rs-mint/30 shadow-lg">
                                        @endif
                                    @else
                                        <div class="w-20 h-20 bg-rs-green/10 rounded-xl flex items-center justify-center border-2 border-rs-mint/30">
                                            <svg class="w-10 h-10 text-rs-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-rs-navy mb-2 group-hover/item:text-rs-green transition-colors text-lg">{{ $poli->name }}</h3>
                                    <p class="text-sm text-rs-navy/70 leading-relaxed">{{ $poli->description ?? 'Poliklinik spesialis kesehatan' }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto mb-3 text-rs-navy/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <p class="text-rs-navy/50">Belum ada poliklinik tersedia</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Doctors Section -->
            <div class="glass rounded-3xl p-8 hover:bg-white/60 transition-all duration-300 group overflow-hidden">
                <div class="flex items-center mb-6">
                    <div class="p-3 bg-rs-teal/10 rounded-xl mr-4 group-hover:bg-rs-teal/20 transition-colors">
                        <svg class="w-8 h-8 text-rs-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-rs-navy">Dokter Spesialis</h2>
                </div>

                <div class="space-y-4 overflow-y-auto max-h-[calc(100%-120px)] scrollbar-thin scrollbar-thumb-rs-mint scrollbar-track-transparent">
                    @forelse($doctors as $doctor)
                        <div class="glass p-4 rounded-xl border border-rs-mint/30 hover:bg-rs-teal/10 transition-all duration-300 group/item">
                            <h3 class="font-bold text-rs-navy mb-1 group-hover/item:text-rs-teal transition-colors">{{ $doctor->name }}</h3>
                            <p class="text-sm font-medium text-rs-green mb-1">{{ $doctor->poli->name ?? 'Dokter Umum' }}</p>
                            <p class="text-xs text-rs-navy/60">{{ $doctor->email }}</p>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto mb-3 text-rs-navy/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <p class="text-rs-navy/50">Belum ada dokter tersedia</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Schedules Section -->
            <div class="glass rounded-3xl p-8 hover:bg-white/60 transition-all duration-300 group overflow-hidden">
                <div class="flex items-center mb-6">
                    <div class="p-3 bg-rs-navy/10 rounded-xl mr-4 group-hover:bg-rs-navy/20 transition-colors">
                        <svg class="w-8 h-8 text-rs-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-rs-navy">Jadwal Praktik</h2>
                </div>

                <div class="space-y-4 overflow-y-auto max-h-[calc(100%-120px)] scrollbar-thin scrollbar-thumb-rs-mint scrollbar-track-transparent">
                    @forelse($schedules as $doctorId => $doctorSchedules)
                        @php $doctor = $doctorSchedules->first()->doctor; @endphp
                        <div class="glass p-4 rounded-xl border border-rs-mint/30 hover:bg-rs-navy/10 transition-all duration-300 group/item">
                            <h3 class="font-bold text-rs-navy mb-1 group-hover/item:text-rs-navy transition-colors">{{ $doctor->name }}</h3>
                            <p class="text-sm font-medium text-rs-green mb-3">{{ $doctor->poli->name ?? 'Dokter Umum' }}</p>
                            <div class="space-y-2">
                                @foreach($doctorSchedules as $schedule)
                                    <div class="glass px-3 py-2 rounded-lg bg-white/50 border border-rs-pale">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-rs-navy">{{ $schedule->day }}</span>
                                            <span class="text-xs text-rs-navy/70 font-mono">{{ $schedule->start_time }} - {{ $schedule->end_time }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 mx-auto mb-3 text-rs-navy/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-rs-navy/50">Belum ada jadwal praktik</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
.scrollbar-thin {
    scrollbar-width: thin;
}
.scrollbar-thumb-rs-mint {
    scrollbar-color: #a9d3c5 transparent;
}
</style>
@endsection
