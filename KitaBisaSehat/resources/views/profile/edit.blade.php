@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-4xl mx-auto px-6 pb-12">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-rs-navy">Profil Saya</h1>
        <p class="text-rs-navy/60">Kelola informasi akun dan keamanan Anda.</p>
    </div>

    <!-- Alert Status -->
    @if (session('status') === 'profile-updated')
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-rs-green text-green-700 rounded-r-xl shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Profil berhasil diperbarui.
        </div>
    @endif

    <div class="space-y-8">
        
        <!-- 1. Update Profile Information -->
        <div class="glass p-8 rounded-3xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-rs-teal"></div>
            
            <header class="mb-6">
                <h2 class="text-xl font-bold text-rs-navy">Informasi Profil</h2>
                <p class="text-sm text-rs-navy/60">Perbarui nama dan alamat email akun Anda.</p>
            </header>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                <!-- Nama -->
                <div>
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" required autofocus autocomplete="name">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" required autocomplete="username">
                    @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="px-6 py-2.5 bg-rs-navy text-white rounded-xl font-bold hover:bg-rs-green transition-all shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- 2. Update Password -->
        <div class="glass p-8 rounded-3xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1 h-full bg-yellow-400"></div>

            <header class="mb-6">
                <h2 class="text-xl font-bold text-rs-navy">Ganti Password</h2>
                <p class="text-sm text-rs-navy/60">Pastikan akun Anda aman dengan menggunakan password yang kuat.</p>
            </header>

            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <!-- Current Password -->
                <div>
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Password Saat Ini</label>
                    <input type="password" name="current_password" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" autocomplete="current-password">
                    @error('current_password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Password Baru</label>
                    <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" autocomplete="new-password">
                    @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" autocomplete="new-password">
                    @error('password_confirmation') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center gap-4">
                    <button type="submit" class="px-6 py-2.5 bg-rs-navy text-white rounded-xl font-bold hover:bg-rs-green transition-all shadow-md">
                        Update Password
                    </button>
                    @if (session('status') === 'password-updated')
                        <p class="text-sm text-green-600 font-bold fade-out">Tersimpan.</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- 3. Delete Account (Opsional, tapi ada di Breeze) -->
        <div class="glass p-8 rounded-3xl relative overflow-hidden bg-red-50/30 border border-red-100">
            <div class="absolute top-0 left-0 w-1 h-full bg-red-500"></div>

            <header class="mb-6">
                <h2 class="text-xl font-bold text-red-600">Hapus Akun</h2>
                <p class="text-sm text-rs-navy/60">Setelah akun dihapus, semua data akan hilang permanen.</p>
            </header>

            <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini secara permanen?');">
                @csrf
                @method('delete')

                <div class="mb-6">
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Password untuk Konfirmasi</label>
                    <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-red-500 focus:ring-2 focus:ring-red-200 outline-none transition-all text-rs-navy" placeholder="Masukkan password Anda">
                    @error('password', 'userDeletion') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="px-6 py-2.5 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition-all shadow-md">
                    Hapus Akun Saya
                </button>
            </form>
        </div>

    </div>
</div>
@endsection