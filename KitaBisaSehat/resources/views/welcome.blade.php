@extends('layouts.app')

@section('content')
<div class="relative w-full h-screen overflow-hidden flex flex-col lg:flex-row bg-rs-pale">

    <div class="w-full lg:w-1/2 h-full flex flex-col justify-between px-6 lg:px-16 py-6 lg:py-8 relative z-20">
        <nav class="flex items-center gap-3 shrink-0">
            <div class="w-10 h-10 bg-rs-green rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
                K
            </div>
            <span class="text-2xl font-bold text-rs-navy tracking-wide">KitaBisaSehat</span>
        </nav>

        <div class="flex flex-col justify-center flex-grow py-4">
            <div>
                <span class="inline-block py-1.5 px-4 rounded-full bg-rs-teal/10 text-rs-teal text-xs lg:text-sm font-semibold mb-6 border border-rs-teal/20">
                    Digitalisasi Layanan Kesehatan
                </span>
            </div>

            <h1 class="text-4xl lg:text-6xl xl:text-7xl font-bold leading-tight mb-4 text-rs-navy">
                Sehat Kini <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-rs-green to-rs-teal">Lebih Mudah.</span>
            </h1>

            <p class="text-base lg:text-lg text-rs-navy/80 mb-8 max-w-lg leading-relaxed">
                Akses layanan kesehatan terpadu, janji temu dokter, dan rekam medis digital dalam satu genggaman. Cepat, aman, dan efisien.
            </p>

            <div class="flex flex-wrap gap-4 mb-10">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-rs-navy text-white rounded-xl font-semibold shadow-lg hover:bg-rs-green transition-all duration-300 transform hover:-translate-y-1">
                            Ke Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-3 bg-rs-navy text-white rounded-xl font-semibold shadow-lg hover:bg-rs-green hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 flex items-center gap-2">
                            Masuk Akun
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-3 glass text-rs-navy rounded-xl font-semibold hover:bg-white/60 transition-all duration-300 border border-rs-navy/10">
                                Daftar Pasien
                            </a>
                        @endif

                        <a href="{{ route('guest.index') }}" class="px-6 py-3 glass text-rs-navy rounded-xl font-semibold hover:bg-white/60 transition-all duration-300 border border-rs-navy/10">
                            Lihat Layanan
                        </a>
                    @endauth
                @endif
            </div>

            <div class="flex gap-4">
                <div class="glass px-4 py-2 rounded-xl flex items-center gap-3 border border-white/40">
                    <div class="text-2xl font-bold text-rs-green">4<span class="text-sm align-top">+</span></div>
                    <div class="text-xs font-medium leading-tight text-rs-navy">Dokter<br>Spesialis</div>
                </div>
                <div class="glass px-4 py-2 rounded-xl flex items-center gap-3 border border-white/40">
                    <div class="text-2xl font-bold text-rs-teal">24/7</div>
                    <div class="text-xs font-medium leading-tight text-rs-navy">Layanan<br>Digital</div>
                </div>
            </div>
        </div>

        <div class="shrink-0 text-xs lg:text-sm text-rs-navy/60">
            &copy; 2025 KitaBisaSehat. Project Final Lab.
        </div>
    </div>

    <div class="hidden lg:block lg:w-1/2 h-full relative">
        <img src="https://www.tagmedstaffing.com/wp-content/uploads/2023/12/shutterstock_786971899-scaled.jpg" 
             class="absolute inset-0 w-full h-full object-cover rounded-l-[3rem]" 
             alt="Hospital">
        <div class="absolute inset-0 bg-gradient-to-t from-rs-navy/90 via-rs-navy/20 to-transparent rounded-l-[3rem]"></div>
    </div>
</div>
@endsection