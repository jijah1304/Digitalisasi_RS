@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6">
    
    <div class="glass w-full max-w-md p-8 rounded-3xl relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-rs-green via-rs-teal to-rs-navy"></div>

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-rs-green text-white font-bold text-2xl mb-4 shadow-lg">
                K
            </div>
            <h2 class="text-2xl font-bold text-rs-navy">Selamat Datang</h2>
            <p class="text-sm text-rs-navy/60">Silakan masuk untuk mengakses layanan.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-rs-navy mb-1 ml-1">Email</label>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus 
                    class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy placeholder-rs-navy/30 backdrop-blur-sm"
                    placeholder="nama@email.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs ml-1" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-rs-navy mb-1 ml-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy placeholder-rs-navy/30 backdrop-blur-sm"
                    placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs ml-1" />
            </div>

            <div class="flex items-center justify-between text-sm">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-rs-teal text-rs-green focus:ring-rs-green/50" name="remember">
                    <span class="ml-2 text-rs-navy/70">Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-rs-teal hover:text-rs-green font-medium transition-colors" href="{{ route('password.request') }}">
                        Lupa Password?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl bg-rs-navy text-white font-semibold shadow-lg hover:bg-rs-green hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                Masuk Sekarang
            </button>

            <div class="text-center mt-6 text-sm text-rs-navy/70">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-bold text-rs-teal hover:underline decoration-2 underline-offset-4">
                    Daftar Pasien
                </a>
            </div>
        </form>
    </div>
</div>
@endsection