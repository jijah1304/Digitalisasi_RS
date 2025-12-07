@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-4xl mx-auto px-6 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-rs-navy">Edit User</h1>
            <p class="text-rs-navy/60">Perbarui informasi pengguna</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl shadow hover:bg-gray-200 transition-colors text-sm font-medium flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
                Kembali ke Daftar User
            </a>
        </div>
    </div>

    <!-- Form Edit User -->
    <div class="glass rounded-3xl p-8 shadow-sm">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-bold text-rs-navy mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rs-teal focus:border-transparent transition-all @error('name') border-red-500 @enderror"
                       placeholder="Masukkan nama lengkap" required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-bold text-rs-navy mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rs-teal focus:border-transparent transition-all @error('email') border-red-500 @enderror"
                       placeholder="Masukkan email" required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-bold text-rs-navy mb-2">Password Baru (Opsional)</label>
                <input type="password" id="password" name="password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rs-teal focus:border-transparent transition-all @error('password') border-red-500 @enderror"
                       placeholder="Biarkan kosong jika tidak ingin mengubah password">
                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-rs-navy mb-2">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rs-teal focus:border-transparent transition-all @error('password_confirmation') border-red-500 @enderror"
                       placeholder="Konfirmasi password baru">
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-bold text-rs-navy mb-2">Peran</label>
                <select id="role" name="role"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rs-teal focus:border-transparent transition-all @error('role') border-red-500 @enderror" required>
                    <option value="">Pilih Peran</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="dokter" {{ old('role', $user->role) == 'dokter' ? 'selected' : '' }}>Dokter</option>
                    <option value="pasien" {{ old('role', $user->role) == 'pasien' ? 'selected' : '' }}>Pasien</option>
                </select>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Poli (khusus untuk dokter) -->
            <div id="poli-field" class="{{ old('role', $user->role) == 'dokter' ? '' : 'hidden' }}">
                <label for="poli_id" class="block text-sm font-bold text-rs-navy mb-2">Poli</label>
                <select id="poli_id" name="poli_id"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-rs-teal focus:border-transparent transition-all @error('poli_id') border-red-500 @enderror">
                    <option value="">Pilih Poli</option>
                    @foreach($polis as $poli)
                        <option value="{{ $poli->id }}" {{ old('poli_id', $user->poli_id) == $poli->id ? 'selected' : '' }}>{{ $poli->name }}</option>
                    @endforeach
                </select>
                @error('poli_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-6">
                <button type="submit" class="px-8 py-3 bg-rs-teal text-white rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 font-semibold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle poli field berdasarkan role
document.getElementById('role').addEventListener('change', function() {
    const poliField = document.getElementById('poli-field');
    const poliSelect = document.getElementById('poli_id');

    if (this.value === 'dokter') {
        poliField.classList.remove('hidden');
        poliSelect.required = true;
    } else {
        poliField.classList.add('hidden');
        poliSelect.required = false;
        poliSelect.value = '';
    }
});
</script>
@endsection
