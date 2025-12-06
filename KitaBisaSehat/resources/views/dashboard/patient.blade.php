@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <!-- Hero Section -->
    <div class="glass rounded-3xl p-8 mb-10 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-4xl font-bold text-rs-navy mb-2">Apa kabar, {{ Auth::user()->name }}?</h1>
            <p class="text-rs-navy/70 mb-6 max-w-lg">Jangan lupa jaga kesehatanmu hari ini. Jika merasa kurang sehat, segera konsultasikan dengan dokter kami.</p>
            
            <a href="{{ route('appointments.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-rs-navy text-white rounded-xl font-semibold shadow-lg hover:bg-rs-green transition-all transform hover:-translate-y-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                Buat Janji Temu Baru
            </a>
        </div>
        <!-- Ilustrasi sederhana -->
        <div class="hidden md:block text-rs-teal/20">
            <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 11h-4v4h-4v-4H6v-4h4V6h4v4h4v4z"/></svg>
        </div>
    </div>

    <!-- Notifikasi untuk Pasien -->
    @if($approvedAppointments > 0)
        <div class="mb-6">
            <div class="glass p-4 rounded-xl border-l-4 border-blue-400 bg-blue-50/50">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-rs-navy">Janji Temu Telah Disetujui</h3>
                        <p class="text-sm text-rs-navy/60">Ada <span class="font-bold text-blue-600">{{ $approvedAppointments }} janji temu</span> yang telah disetujui oleh dokter.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($medicineReadyAppointments > 0)
        <div class="mb-6">
            <div class="glass p-4 rounded-xl border-l-4 border-green-400 bg-green-50/50">
                <div class="flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-lg text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-rs-navy">Resep Obat Siap Diambil</h3>
                        <p class="text-sm text-rs-navy/60">Ada <span class="font-bold text-green-600">{{ $medicineReadyAppointments }} resep obat</span> yang siap diambil sesuai rekam medis Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Status Janji Temu Terakhir -->
    <h2 class="text-xl font-bold text-rs-navy mb-5">Riwayat & Status Janji Temu</h2>

    <!-- Alert Sukses Feedback -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-rs-green text-green-700 rounded-r-xl shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-xl shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="glass rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                    <tr>
                        <th class="p-4 font-semibold">Tanggal</th>
                        <th class="p-4 font-semibold">Dokter & Poli</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold">Ulasan / Feedback</th>
                        <th class="p-4 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($appointments as $apt)
                        <tr class="hover:bg-white/30 transition-colors">
                            <td class="p-4 text-sm text-rs-navy font-medium">
                                {{ \Carbon\Carbon::parse($apt->date)->translatedFormat('d M Y') }}<br>
                                <span class="text-xs text-rs-navy/60">{{ $apt->schedule->start_time }}</span>
                            </td>
                            <td class="p-4">
                                <div class="text-sm font-bold text-rs-navy">{{ $apt->doctor->name }}</div>
                                <div class="text-xs text-rs-teal">{{ $apt->doctor->poli->name ?? '-' }}</div>
                            </td>
                            <td class="p-4">
                                @if($apt->status == 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">Menunggu</span>
                                @elseif($apt->status == 'approved')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Disetujui</span>
                                @elseif($apt->status == 'obat_diterima')
                                    <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-bold">Obat Siap</span>
                                @elseif($apt->status == 'selesai')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Selesai</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Ditolak</span>
                                @endif
                            </td>
                            
                            <!-- KOLOM ULASAN (FEEDBACK) -->
                            <td class="p-4">
                                @if($apt->status == 'selesai')
                                    @if($apt->rating)
                                        <!-- Tampilkan Bintang Rating (Sudah diulas) -->
                                        <div class="flex flex-col">
                                            <div class="flex text-yellow-400 text-sm cursor-help" title="{{ $apt->feedback }}">
                                                @for($i=0; $i<$apt->rating; $i++) ★ @endfor
                                                @for($i=$apt->rating; $i<5; $i++) <span class="text-gray-300">★</span> @endfor
                                            </div>
                                            @if($apt->feedback)
                                                <span class="text-[10px] text-rs-navy/50 italic truncate max-w-[150px] mt-1">
                                                    "{{ $apt->feedback }}"
                                                </span>
                                            @endif
                                            <span class="text-[10px] text-green-600 font-bold mt-1">✔ Terkirim</span>
                                        </div>
                                    @else
                                        <!-- Tombol Beri Nilai (Belum diulas) -->
                                        <button onclick="openFeedbackModal({{ $apt->id }}, '{{ $apt->doctor->name }}')" 
                                                class="text-xs bg-rs-navy text-white px-3 py-1.5 rounded-lg hover:bg-rs-teal transition-colors shadow-sm font-medium animate-pulse">
                                            Beri Nilai
                                        </button>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>

                            <!-- KOLOM AKSI -->
                            <td class="p-4">
                                @if($apt->status == 'obat_diterima')
                                    <form method="POST" action="{{ route('appointments.confirm-medicine', $apt->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs bg-orange-500 text-white px-3 py-1.5 rounded-lg hover:bg-orange-600 transition-colors shadow-sm font-medium inline-flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            Konfirmasi Obat
                                        </button>
                                    </form>

                                @elseif($apt->status == 'selesai' && $apt->medicalRecord)
                                    <a href="{{ route('medical_records.show', $apt->medicalRecord->id) }}" class="text-xs font-bold text-rs-teal hover:underline inline-flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                        Lihat Hasil
                                    </a>

                                @elseif($apt->status == 'rejected')
                                    <div class="text-xs text-red-500 font-medium" title="{{ $apt->rejection_reason }}">
                                        <span class="font-bold">Alasan:</span>
                                        <span class="italic">"{{ Str::limit($apt->rejection_reason, 20) }}"</span>
                                    </div>

                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-rs-navy/60">
                                Belum ada riwayat janji temu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL POPUP FEEDBACK -->
<div id="feedbackModal" class="fixed inset-0 bg-black/50 z-[999] hidden flex items-center justify-center backdrop-blur-sm p-4 transition-opacity duration-300">
    <div class="bg-white rounded-3xl p-8 max-w-md w-full shadow-2xl relative overflow-hidden transform scale-95 transition-transform duration-300" id="feedbackContent">
        <!-- Hiasan Atas -->
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-rs-green to-rs-teal"></div>

        <h3 class="text-xl font-bold text-rs-navy mb-2 text-center">Beri Ulasan Pelayanan</h3>
        <p class="text-sm text-rs-navy/60 mb-6 text-center">Bagaimana pengalaman Anda dengan <span id="modalDoctorName" class="font-bold text-rs-teal"></span>?</p>
        
        <form id="feedbackForm" method="POST">
            @csrf
            
            <!-- Input Bintang (CSS Hack flex-row-reverse) -->
            <div class="flex justify-center gap-2 mb-6 flex-row-reverse group">
                <input type="radio" name="rating" value="5" id="star5" class="peer/5 hidden" required>
                <label for="star5" class="text-4xl text-gray-300 peer-checked/5:text-yellow-400 hover:text-yellow-400 hover:scale-110 cursor-pointer transition-all">★</label>
                
                <input type="radio" name="rating" value="4" id="star4" class="peer/4 hidden">
                <label for="star4" class="text-4xl text-gray-300 peer-checked/4:text-yellow-400 hover:text-yellow-400 hover:scale-110 cursor-pointer transition-all">★</label>
                
                <input type="radio" name="rating" value="3" id="star3" class="peer/3 hidden">
                <label for="star3" class="text-4xl text-gray-300 peer-checked/3:text-yellow-400 hover:text-yellow-400 hover:scale-110 cursor-pointer transition-all">★</label>
                
                <input type="radio" name="rating" value="2" id="star2" class="peer/2 hidden">
                <label for="star2" class="text-4xl text-gray-300 peer-checked/2:text-yellow-400 hover:text-yellow-400 hover:scale-110 cursor-pointer transition-all">★</label>
                
                <input type="radio" name="rating" value="1" id="star1" class="peer/1 hidden">
                <label for="star1" class="text-4xl text-gray-300 peer-checked/1:text-yellow-400 hover:text-yellow-400 hover:scale-110 cursor-pointer transition-all">★</label>
            </div>

            <!-- Input Ulasan -->
            <div class="mb-6">
                <label class="block text-xs font-bold text-rs-navy mb-2 ml-1">Kritik & Saran (Opsional)</label>
                <textarea name="feedback" rows="3" class="w-full p-4 rounded-xl bg-gray-50 border border-gray-200 focus:ring-2 focus:ring-rs-teal focus:border-transparent outline-none text-sm placeholder-gray-400 transition-all" placeholder="Dokternya ramah, pelayanannya cepat..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeFeedbackModal()" class="flex-1 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200 transition-colors">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-rs-navy text-white rounded-xl font-bold hover:bg-rs-green transition-colors shadow-lg transform hover:-translate-y-0.5">Kirim Ulasan</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* CSS Logic untuk Bintang: 
       Karena flex-row-reverse, sibling selector (~) memilih elemen visual di kirinya (yang sebenarnya adalah bintang nilai lebih rendah).
       Jadi hover bintang 5 akan menyorot bintang 4, 3, 2, 1 juga.
    */
    
    /* Warna kuning saat di-hover */
    #feedbackForm .group:hover label { color: #fbbf24; } 
    #feedbackForm .group label:hover ~ label { color: #fbbf24; } 
    
    /* Reset warna abu untuk bintang yang LEBIH TINGGI dari yang di-hover */
    #feedbackForm .group label:hover { color: #fbbf24; } 
    #feedbackForm .group label:hover ~ label { color: #fbbf24; } 
    
    /* Logika Checked: Tetap kuning setelah diklik */
    input:checked ~ label { color: #fbbf24 !important; }
    input:checked ~ label ~ label { color: #fbbf24 !important; }
</style>

<script>
    function openFeedbackModal(id, doctorName) {
        const modal = document.getElementById('feedbackModal');
        const content = document.getElementById('feedbackContent');
        
        // Set nama dokter & action form
        document.getElementById('modalDoctorName').innerText = doctorName;
        document.getElementById('feedbackForm').action = `/patient/appointments/${id}/feedback`; // Sesuai route web.php
        
        // Tampilkan dengan animasi fade in
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function closeFeedbackModal() {
        const modal = document.getElementById('feedbackModal');
        const content = document.getElementById('feedbackContent');
        
        // Animasi fade out
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200); // Tunggu animasi selesai
    }

    // Tutup modal jika klik di luar area putih
    document.getElementById('feedbackModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeFeedbackModal();
        }
    });
</script>
@endsection