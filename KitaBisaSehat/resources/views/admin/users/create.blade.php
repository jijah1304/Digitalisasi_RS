@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-2xl mx-auto px-6 pb-12">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-rs-navy/60 hover:text-rs-teal mb-2 inline-flex items-center gap-1 transition-colors">
            &larr; Kembali ke Daftar User
        </a>
        <h1 class="text-3xl font-bold text-rs-navy">Tambah User Baru</h1>
        <p class="text-rs-navy/60">Daftarkan Admin, Dokter, atau Pasien baru ke dalam sistem.</p>
    </div>

    <!-- Form Container -->
    <div class="glass p-8 rounded-3xl relative overflow-hidden">
        <!-- Hiasan Garis Atas -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-rs-green to-rs-teal"></div>
        
        <!-- PERBAIKAN: Menggunakan route 'admin.users.store' sesuai definisi di web.php -->
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy placeholder-rs-navy/30" placeholder="Contoh: Dr. Budi Santoso" required>
                @error('name') <span class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy placeholder-rs-navy/30" placeholder="nama@email.com" required>
                @error('email') <span class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Password</label>
                    <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" required>
                    @error('password') <span class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" required>
                </div>
            </div>

            <!-- Role Selection -->
            <div>
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Peran Pengguna (Role)</label>
                <div class="relative">
                    <select id="role" name="role" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy appearance-none cursor-pointer" required>
                        <option value="" disabled selected>-- Pilih Role --</option>
                        <option value="pasien" {{ old('role') == 'pasien' ? 'selected' : '' }}>Pasien</option>
                        <option value="dokter" {{ old('role') == 'dokter' ? 'selected' : '' }}>Dokter</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-rs-navy/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                @error('role') <span class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Poli Selection (Hanya muncul jika Role = Dokter) -->
            <div id="poli-container" class="hidden">
                <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Pilih Poli (Wajib untuk Dokter)</label>
                <div class="relative">
                    <select name="poli_id" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy appearance-none cursor-pointer">
                        <option value="">-- Pilih Poli --</option>
                        @foreach($polis as $poli)
                            <option value="{{ $poli->id }}" {{ old('poli_id') == $poli->id ? 'selected' : '' }}>{{ $poli->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-rs-navy/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <p class="text-xs text-rs-teal mt-1 ml-1">*Dokter harus ditugaskan ke salah satu poli.</p>
                @error('poli_id') <span class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3.5 bg-rs-navy text-white font-bold rounded-xl shadow-lg hover:bg-rs-green transition-all transform hover:-translate-y-1">
                Simpan User Baru
            </button>
        </form>
    </div>
</div>

<!-- JavaScript untuk Show/Hide Dropdown Poli -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const poliContainer = document.getElementById('poli-container');

        function togglePoli() {
            if (roleSelect.value === 'dokter') {
                poliContainer.classList.remove('hidden');
                poliContainer.querySelector('select').setAttribute('required', 'required');
            } else {
                poliContainer.classList.add('hidden');
                poliContainer.querySelector('select').removeAttribute('required');
                poliContainer.querySelector('select').value = ""; // Reset jika bukan dokter
            }
        }

        // Jalankan saat load (jika old input ada) dan saat change
        togglePoli();
        roleSelect.addEventListener('change', togglePoli);
    });
</script>
@endsection