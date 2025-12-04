@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-5xl mx-auto px-6 pb-12">
    <div class="mb-8">
        <a href="{{ route('medical_records.show', $medicalRecord->id) }}" class="text-sm text-rs-navy/60 hover:text-rs-teal mb-2 inline-flex items-center gap-1">
            &larr; Batal Edit
        </a>
        <h1 class="text-3xl font-bold text-rs-navy">Edit Rekam Medis</h1>
        <p class="text-sm text-rs-teal">Pasien: {{ $medicalRecord->appointment->patient->name }}</p>
    </div>

    <form action="{{ route('medical_records.update', $medicalRecord->id) }}" method="POST">
        @csrf
        @method('PUT') <div class="flex flex-col lg:flex-row gap-8">
            <div class="w-full">
                <div class="glass p-8 rounded-3xl mb-6">
                    <h3 class="font-bold text-xl text-rs-navy mb-6">Diagnosa & Tindakan</h3>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-rs-navy mb-2">Diagnosis</label>
                            <textarea name="diagnosis" rows="2" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none" required>{{ $medicalRecord->diagnosis }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-rs-navy mb-2">Tindakan Medis</label>
                            <textarea name="medical_action" rows="2" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none" required>{{ $medicalRecord->medical_action }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-rs-navy mb-2">Catatan</label>
                            <input type="text" name="notes" value="{{ $medicalRecord->notes }}" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none">
                        </div>
                    </div>
                </div>

                <div class="glass p-8 rounded-3xl mb-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-xl text-rs-navy">Resep Obat</h3>
                        <button type="button" id="add-medicine" class="text-xs font-bold text-rs-teal bg-rs-teal/10 px-3 py-2 rounded-lg hover:bg-rs-teal hover:text-white transition-colors">
                            + Tambah Obat
                        </button>
                    </div>

                    <div id="medicine-container" class="space-y-4">
                        @foreach($medicalRecord->medicines as $oldMed)
                        <div class="medicine-row grid grid-cols-12 gap-4 items-end">
                            <div class="col-span-8">
                                <label class="block text-xs font-bold text-rs-navy mb-1">Nama Obat</label>
                                <select name="medicines[]" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none" required>
                                    @foreach($medicines as $med)
                                        <option value="{{ $med->id }}" {{ $oldMed->id == $med->id ? 'selected' : '' }}>
                                            {{ $med->name }} (Sisa Stok: {{ $med->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-3">
                                <label class="block text-xs font-bold text-rs-navy mb-1">Qty</label>
                                <input type="number" name="quantities[]" value="{{ $oldMed->pivot->quantity }}" min="1" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none" required>
                            </div>
                            <div class="col-span-1">
                                <button type="button" class="w-full h-[46px] flex items-center justify-center bg-red-100 text-red-500 rounded-xl hover:bg-red-500 hover:text-white" onclick="this.closest('.medicine-row').remove()">
                                    X
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-rs-navy text-white font-bold rounded-2xl shadow-xl hover:bg-rs-green transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('add-medicine').addEventListener('click', function() {
        const container = document.getElementById('medicine-container');
        // Buat elemen baru manual karena cloneNode di edit agak tricky dengan value
        const newRow = document.createElement('div');
        newRow.className = 'medicine-row grid grid-cols-12 gap-4 items-end';
        newRow.innerHTML = `
            <div class="col-span-8">
                <select name="medicines[]" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none" required>
                    <option value="" disabled selected>-- Pilih Obat --</option>
                    @foreach($medicines as $med)
                        <option value="{{ $med->id }}">{{ $med->name }} (Stok: {{ $med->stock }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-3">
                <input type="number" name="quantities[]" value="1" min="1" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:ring-2 outline-none" required>
            </div>
            <div class="col-span-1">
                <button type="button" class="w-full h-[46px] flex items-center justify-center bg-red-100 text-red-500 rounded-xl hover:bg-red-500 hover:text-white" onclick="this.closest('.medicine-row').remove()">X</button>
            </div>
        `;
        container.appendChild(newRow);
    });
</script>
@endsection