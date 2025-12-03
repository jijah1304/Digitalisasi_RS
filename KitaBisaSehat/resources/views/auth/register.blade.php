@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 my-8">
    
    <div class="glass w-full max-w-lg p-8 rounded-3xl relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-rs-teal via-rs-green to-rs-navy"></div>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-rs-navy">Daftar Pasien Baru</h2>
            <p class="text-sm text-rs-navy/60">Buat akun untuk mulai membuat janji temu.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-rs-navy mb-1 ml-1">Nama Lengkap</label>
                <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                    class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy placeholder-rs-navy/30 backdrop-blur-sm"
                    placeholder="Nama sesuai KTP">
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-xs ml-1" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-rs-navy mb-1 ml-1">Email</label>
                <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                    class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy placeholder-rs-navy/30 backdrop-blur-sm"
                    placeholder="contoh@email.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs ml-1" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-rs-navy mb-1 ml-1">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy placeholder-rs-navy/30 backdrop-blur-sm"
                    placeholder="Minimal 8 karakter">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs ml-1" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-rs-navy mb-1 ml-1">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy placeholder-rs-navy/30 backdrop-blur-sm"
                    placeholder="Ulangi password">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 text-xs ml-1" />
            </div>

            <button type="submit" class="w-full py-3.5 rounded-xl bg-rs-green text-white font-semibold shadow-lg hover:bg-rs-navy hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 mt-2">
                Daftar Sekarang
            </button>

            <div class="text-center mt-6 text-sm text-rs-navy/70">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-rs-teal hover:underline decoration-2 underline-offset-4">
                    Masuk di sini
                </a>
            </div>
        </form>
    </div>
</div>
@endsection