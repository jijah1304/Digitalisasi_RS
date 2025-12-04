@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-5xl mx-auto px-6 pb-12">
    <div class="mb-8">
        <a href="{{ route('dashboard') }}" class="text-sm text-rs-navy/60 hover:text-rs-teal mb-2 inline-flex items-center gap-1">
            &larr; Kembali ke Dashboard
        </a>
        <h1 class="text-3xl font-bold text-rs-navy">Pemeriksaan Medis</h1>
        <p class="text-rs-navy/60">Isi rekam medis dan resep obat untuk pasien.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <div class="w-full lg:w-1/3">
            <div class="glass p-6 rounded-3xl sticky top-24">
                <h3 class="font-bold text-xl text-rs-navy mb-4 border-b border-rs-navy/10 pb-2">Data Pasien</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-xs text-rs-navy/50 uppercase font-bold">Nama Pasien</label>
                        <p class="text-lg font-bold text-rs-navy">{{ $appointment->patient->name }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-rs-navy/50 uppercase font-bold">Keluhan Awal [cite: 70]</label>
                        <div class="bg-white/40 p-3 rounded-xl text-sm italic text-rs-navy mt-1">
                            "{{ $appointment->complaint }}"
                        </div>
                    </div>
                    <div>
                        <label class="text-xs text-rs-navy/50 uppercase font-bold">Tanggal & Waktu</label>
                        <p class="text-sm text-rs-navy font-medium">
                            {{ \Carbon\Carbon::parse($appointment->date)->format('d M Y') }} <br>
                            <span class="text-rs-teal">{{ $appointment->schedule->start_time }} - {{ $appointment->schedule->end_time }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-2/3">
            <form action="{{ route('medical_records.store', $appointment->id) }}" method="POST">
                @csrf
                
                <div class="glass p-8 rounded-3xl mb-6">
                    <h3 class="font-bold text-xl text-rs-navy mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 bg-rs-navy text-white rounded-lg flex items-center justify-center text-sm">1</span>
                        Diagnosa & Tindakan
                    </h3>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Diagnosis Dokter</label>
                            <textarea name="diagnosis" rows="2" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 outline-none" placeholder="Contoh: Infeksi Saluran Pernapasan Akut" required></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Tindakan Medis</label>
                            <textarea name="medical_action" rows="2" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 outline-none" placeholder="Contoh: Pemeriksaan fisik, cek tekanan darah" required></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Catatan Tambahan (Opsional)</label>
                            <input type="text" name="notes" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 outline-none" placeholder="Saran istirahat, pantangan makan, dll">
                        </div>
                    </div>
                </div>

                <div class="glass p-8 rounded-3xl mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-xl text-rs-navy flex items-center gap-2">
                            <span class="w-8 h-8 bg-rs-green text-white rounded-lg flex items-center justify-center text-sm">2</span>
                            Resep Obat
                        </h3>
                        <button type="button" id="add-medicine" class="text-xs font-bold text-rs-teal bg-rs-teal/10 px-3 py-2 rounded-lg hover:bg-rs-teal hover:text-white transition-colors">
                            + Tambah Obat Lain
                        </button>
                    </div>

                    <div id="medicine-container" class="space-y-4">
                        <div class="medicine-row grid grid-cols-12 gap-4 items-end">
                            <div class="col-span-8">
                                <label class="block text-xs font-bold text-rs-navy mb-1 ml-1">Nama Obat</label>
                                <select name="medicines[]" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 outline-none appearance-none" required>
                                    <option value="" disabled selected>-- Pilih Obat --</option>
                                    @foreach($medicines as $med)
                                        <option value="{{ $med->id }}">
                                            {{ $med->name }} (Stok: {{ $med->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-3">
                                <label class="block text-xs font-bold text-rs-navy mb-1 ml-1">Jumlah</label>
                                <input type="number" name="quantities[]" value="1" min="1" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 outline-none" required>
                            </div>
                            <div class="col-span-1">
                                </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-rs-navy text-white font-bold rounded-2xl shadow-xl hover:bg-rs-green transition-all transform hover:-translate-y-1 text-lg">
                    Simpan & Selesaikan Pemeriksaan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-medicine').addEventListener('click', function() {
        const container = document.getElementById('medicine-container');
        const firstRow = container.querySelector('.medicine-row');
        
        // Clone baris pertama
        const newRow = firstRow.cloneNode(true);
        
        // Reset nilai input di baris baru
        newRow.querySelector('select').value = "";
        newRow.querySelector('input').value = "1";
        
        // Tambahkan tombol hapus di kolom terakhir
        const deleteCol = newRow.children[2];
        deleteCol.innerHTML = `
            <button type="button" class="w-full h-[46px] flex items-center justify-center bg-red-100 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-colors" onclick="this.closest('.medicine-row').remove()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            </button>
        `;

        container.appendChild(newRow);
    });
</script>
@endsection