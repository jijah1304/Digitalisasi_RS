@extends('layouts.app')

@section('content')
<div class="relative w-full h-screen overflow-hidden flex flex-col lg:flex-row">

    <div class="w-full lg:w-1/2 h-full flex flex-col justify-center px-8 lg:px-20 relative z-20">
        
        <nav class="absolute top-8 left-8 lg:left-20 flex items-center gap-3">
            <div class="w-10 h-10 bg-rs-green rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                K
            </div>
            <span class="text-2xl font-bold text-rs-navy tracking-wide">KitaBisaSehat</span>
        </nav>

        <div class="mt-10 lg:mt-0">
            <span class="inline-block py-1 px-3 rounded-full bg-rs-teal/10 text-rs-teal text-sm font-semibold mb-4 border border-rs-teal/20">
                Digitalisasi Layanan Kesehatan
            </span>
            <h1 class="text-5xl lg:text-7xl font-bold leading-tight mb-6 text-rs-navy">
                Sehat Kini <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-rs-green to-rs-teal">Lebih Mudah.</span>
            </h1>
            <p class="text-lg text-rs-navy/80 mb-8 max-w-md leading-relaxed">
                Akses layanan kesehatan terpadu, janji temu dokter, dan rekam medis digital dalam satu genggaman. Cepat, aman, dan efisien.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-rs-navy text-white rounded-2xl font-semibold shadow-lg hover:bg-rs-green transition-all duration-300 transform hover:-translate-y-1 text-center">
                            Kembali ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-rs-navy text-white rounded-2xl font-semibold shadow-lg hover:shadow-xl hover:bg-rs-green transition-all duration-300 transform hover:-translate-y-1 text-center flex items-center justify-center gap-2">
                            Masuk Akun
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-8 py-4 glass text-rs-navy rounded-2xl font-semibold hover:bg-white/50 transition-all duration-300 text-center">
                                Daftar Pasien
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <div class="mt-12 flex gap-6">
                <div class="glass px-5 py-3 rounded-2xl flex items-center gap-3">
                    <div class="text-3xl font-bold text-rs-green">24<span class="text-sm align-top">+</span></div>
                    <div class="text-xs font-medium leading-tight">Dokter<br>Spesialis</div>
                </div>
                <div class="glass px-5 py-3 rounded-2xl flex items-center gap-3">
                    <div class="text-3xl font-bold text-rs-teal">24/7</div>
                    <div class="text-xs font-medium leading-tight">Layanan<br>Digital</div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 text-sm text-rs-navy/60">
            &copy; 2025 KitaBisaSehat. Project Lab S1 SI.
        </div>
    </div>

    <div class="hidden lg:block lg:w-1/2 h-full relative">
        <img src="https://images.unsplash.com/photo-1538108149393-fbbd81895907?q=80&w=2828&auto=format&fit=crop" 
             class="absolute inset-0 w-full h-full object-cover rounded-l-[3rem]" 
             alt="Hospital Building">
        
        <div class="absolute inset-0 bg-gradient-to-t from-rs-navy/80 to-transparent rounded-l-[3rem]"></div>

        <div class="absolute bottom-20 left-10 right-20 glass-dark p-6 rounded-3xl text-white shadow-2xl transform transition hover:scale-105 duration-500 cursor-default border border-white/10">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-rs-mint overflow-hidden border-2 border-white">
                    <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&q=80&w=100&h=100" class="w-full h-full object-cover">
                </div>
                <div>
                    <h3 class="font-semibold text-lg">dr. Budi Santoso</h3>
                    <p class="text-rs-mint text-sm">Poli Umum</p>
                    <div class="mt-3 flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-lg w-fit">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        <span class="text-xs font-medium">Sedang Praktik (08:00 - 12:00)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection