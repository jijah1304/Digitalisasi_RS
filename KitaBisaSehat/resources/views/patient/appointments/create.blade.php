@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-6xl mx-auto px-6 pb-12">
    
    <div class="mb-8">
        <a href="{{ route('dashboard') }}" class="text-sm text-rs-navy/60 hover:text-rs-teal mb-2 inline-flex items-center gap-1 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" /></svg>
            Kembali ke Dashboard
        </a>
        <h1 class="text-3xl font-bold text-rs-navy">Buat Janji Temu</h1>
        <p class="text-rs-navy/60">Isi formulir di bawah untuk menjadwalkan konsultasi dengan dokter kami.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8">
        
        <div class="w-full lg:w-2/3">
            <div class="glass p-8 rounded-3xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-rs-green to-rs-teal"></div>

                <form action="{{ route('appointments.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Pilih Poliklinik</label>
                        <div class="relative">
                            <select id="poli_id" name="poli_id" class="w-full px-4 py-3.5 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy appearance-none cursor-pointer" required>
                                <option value="" disabled selected>-- Pilih Poli Tujuan --</option>
                                @foreach($polis as $poli)
                                    <option value="{{ $poli->id }}">{{ $poli->name }}</option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-rs-navy/50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Pilih Dokter</label>
                        <div class="relative">
                            <select id="doctor_id" name="doctor_id" class="w-full px-4 py-3.5 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy appearance-none cursor-pointer disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed" disabled required>
                                <option value="">-- Pilih Poli Terlebih Dahulu --</option>
                            </select>
                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-rs-navy/50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                        <p id="loading-doctors" class="hidden text-xs text-rs-teal mt-1 italic">Mengambil data dokter...</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Tanggal Rencana</label>
                            <input type="date" name="date" min="{{ date('Y-m-d') }}" class="w-full px-4 py-3.5 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Slot Waktu</label>
                            <div class="relative">
                                <select id="schedule_id" name="schedule_id" class="w-full px-4 py-3.5 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy appearance-none cursor-pointer disabled:bg-gray-100 disabled:text-gray-400" disabled required>
                                    <option value="">-- Pilih Dokter Dahulu --</option>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-rs-navy/50">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            </div>
                            <p id="loading-schedules" class="hidden text-xs text-rs-teal mt-1 italic">Mencari jadwal praktik...</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-rs-navy mb-2 ml-1">Keluhan Singkat</label>
                        <textarea name="complaint" rows="3" class="w-full px-4 py-3 rounded-xl bg-white/50 border border-white/60 focus:border-rs-teal focus:ring-2 focus:ring-rs-teal/20 outline-none transition-all text-rs-navy placeholder-rs-navy/30" placeholder="Contoh: Demam sudah 3 hari, pusing, dan mual." required></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-rs-navy text-white font-bold rounded-xl shadow-lg hover:bg-rs-green transition-all transform hover:-translate-y-1 flex justify-center items-center gap-2">
                        <span>Konfirmasi Janji Temu</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="w-full lg:w-1/3 space-y-6">
            <div class="glass p-6 rounded-2xl border border-white/40">
                <div class="w-12 h-12 bg-rs-pale rounded-full flex items-center justify-center text-rs-green mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="font-bold text-rs-navy text-lg mb-2">Panduan Booking</h3>
                <ul class="text-sm text-rs-navy/70 space-y-3 list-disc list-inside">
                    <li>Pilih Poli yang sesuai dengan keluhan Anda.</li>
                    <li>Pastikan tanggal yang dipilih sesuai dengan hari praktik dokter (Senin-Jumat).</li>
                    <li>Jelaskan keluhan secara singkat agar dokter memiliki gambaran awal.</li>
                    <li>Status janji temu akan menjadi <strong>Pending</strong> sampai disetujui Admin/Dokter.</li>
                </ul>
            </div>

            <div class="hidden lg:block relative h-48 rounded-2xl overflow-hidden shadow-lg group">
                <img src="https://images.unsplash.com/photo-1631217868264-e5b90bb7e133?auto=format&fit=crop&q=80&w=600" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <div class="absolute inset-0 bg-rs-navy/40 flex items-center justify-center">
                    <p class="text-white font-bold text-center px-4">Kami siap melayani kesehatan Anda.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const poliSelect = document.getElementById('poli_id');
        const doctorSelect = document.getElementById('doctor_id');
        const scheduleSelect = document.getElementById('schedule_id');
        
        const loadingDoctors = document.getElementById('loading-doctors');
        const loadingSchedules = document.getElementById('loading-schedules');

        // 1. Saat Poli Berubah -> Ambil Data Dokter
        poliSelect.addEventListener('change', function() {
            const poliId = this.value;
            
            // Reset Dropdown Dokter & Jadwal
            doctorSelect.innerHTML = '<option value="">-- Pilih Dokter --</option>';
            doctorSelect.disabled = true;
            scheduleSelect.innerHTML = '<option value="">-- Pilih Dokter Dahulu --</option>';
            scheduleSelect.disabled = true;

            if(poliId) {
                loadingDoctors.classList.remove('hidden'); // Show loading
                
                // Panggil API Route yang sudah kita buat di web.php
                fetch(`/patient/get-doctors/${poliId}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingDoctors.classList.add('hidden'); // Hide loading
                        
                        if(data.length > 0) {
                            doctorSelect.disabled = false;
                            data.forEach(doctor => {
                                doctorSelect.innerHTML += `<option value="${doctor.id}">${doctor.name}</option>`;
                            });
                        } else {
                            doctorSelect.innerHTML = '<option value="">Tidak ada dokter tersedia</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        loadingDoctors.classList.add('hidden');
                    });
            }
        });

        // 2. Saat Dokter Berubah -> Ambil Data Jadwal
        doctorSelect.addEventListener('change', function() {
            const doctorId = this.value;

            // Reset Jadwal
            scheduleSelect.innerHTML = '<option value="">-- Pilih Slot Waktu --</option>';
            scheduleSelect.disabled = true;

            if(doctorId) {
                loadingSchedules.classList.remove('hidden');

                fetch(`/patient/get-schedules/${doctorId}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingSchedules.classList.add('hidden');

                        if(data.length > 0) {
                            scheduleSelect.disabled = false;
                            data.forEach(schedule => {
                                // Format tampilan: Hari (Jam Mulai - Jam Selesai)
                                scheduleSelect.innerHTML += `<option value="${schedule.id}">${schedule.day} (${schedule.start_time} - ${schedule.end_time})</option>`;
                            });
                        } else {
                            scheduleSelect.innerHTML = '<option value="">Dokter ini belum mengatur jadwal</option>';
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    });
</script>

@endsection