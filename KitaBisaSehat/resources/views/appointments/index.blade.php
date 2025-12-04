@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-rs-navy">Daftar Janji Temu</h1>
            <p class="text-rs-navy/60">
                @if(Auth::user()->role == 'pasien')
                    Riwayat permintaan konsultasi Anda.
                @else
                    Kelola permintaan masuk dari pasien.
                @endif
            </p>
        </div>
        
        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-rs-teal hover:underline flex items-center gap-1">
            &larr; Kembali ke Dashboard
        </a>
    </div>

    <!-- Alert Success -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-rs-green text-green-700 rounded-r-xl shadow-sm flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Alert Error (Validasi Alasan) -->
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-xl">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabel Glass -->
    <div class="glass rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                    <tr>
                        <th class="p-5 font-semibold">Tanggal & Waktu</th>
                        <th class="p-5 font-semibold">
                            {{ Auth::user()->role == 'pasien' ? 'Dokter Tujuan' : 'Nama Pasien' }}
                        </th>
                        <th class="p-5 font-semibold">Keluhan</th>
                        <th class="p-5 font-semibold text-center">Status</th>
                        <th class="p-5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($appointments as $apt)
                        <tr class="hover:bg-white/30 transition-colors">
                            
                            <!-- 1. TANGGAL -->
                            <td class="p-5">
                                <div class="font-bold text-rs-navy">{{ \Carbon\Carbon::parse($apt->date)->translatedFormat('d M Y') }}</div>
                                <div class="text-xs text-rs-teal font-mono mt-1">
                                    {{ $apt->schedule->start_time }} - {{ $apt->schedule->end_time }}
                                </div>
                            </td>

                            <!-- 2. NAMA -->
                            <td class="p-5">
                                @if(Auth::user()->role == 'pasien')
                                    <div class="font-bold text-rs-navy">{{ $apt->doctor->name }}</div>
                                    <div class="text-xs text-rs-navy/60">{{ $apt->doctor->poli->name ?? '-' }}</div>
                                @else
                                    <div class="font-bold text-rs-navy">{{ $apt->patient->name }}</div>
                                    <div class="text-xs text-rs-navy/60">{{ $apt->patient->email }}</div>
                                @endif
                            </td>

                            <!-- 3. KELUHAN -->
                            <td class="p-5">
                                <p class="text-sm text-rs-navy/80 italic max-w-xs truncate" title="{{ $apt->complaint }}">
                                    "{{ $apt->complaint }}"
                                </p>
                            </td>

                            <!-- 4. STATUS -->
                            <td class="p-5 text-center">
                                @if($apt->status == 'pending')
                                    <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold border border-yellow-200">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span> Pending
                                    </span>
                                @elseif($apt->status == 'approved')
                                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold border border-blue-200">
                                        Approved
                                    </span>
                                @elseif($apt->status == 'selesai')
                                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold border border-green-200">
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold border border-red-200 cursor-help" title="Alasan: {{ $apt->rejection_reason }}">
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            <!-- 5. AKSI -->
                            <td class="p-5 text-center">
                                
                                <!-- OPSI A: Jika PENDING & User bukan Pasien (Admin/Dokter Approve) -->
                                @if($apt->status == 'pending' && Auth::user()->role !== 'pasien')
                                    <div class="flex justify-center gap-2">
                                        
                                        <!-- Tombol Approve -->
                                        <form action="{{ Auth::user()->role == 'admin' ? route('admin.appointments.status', $apt->id) : route('doctor.appointments.status', $apt->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-600 hover:text-white transition-all shadow-sm" title="Terima">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                            </button>
                                        </form>

                                        <!-- Tombol Reject (Pakai JS Prompt) -->
                                        <form id="reject-form-{{ $apt->id }}" action="{{ Auth::user()->role == 'admin' ? route('admin.appointments.status', $apt->id) : route('doctor.appointments.status', $apt->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <!-- Hidden Input untuk menyimpan alasan dari JS -->
                                            <input type="hidden" name="rejection_reason" id="reason-{{ $apt->id }}">
                                            
                                            <button type="button" onclick="rejectAppointment({{ $apt->id }})" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Tolak">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                
                                <!-- OPSI B: Jika SELESAI (Ada Medical Record) -->
                                @elseif($apt->status == 'selesai' && $apt->medicalRecord)
                                    <a href="{{ route('medical_records.show', $apt->medicalRecord->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-rs-teal hover:underline bg-rs-teal/10 px-3 py-1.5 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                        Lihat Rekam Medis
                                    </a>

                                <!-- OPSI C: Jika REJECTED (Tampilkan Alasan) -->
                                @elseif($apt->status == 'rejected')
                                    <span class="text-xs text-red-500 italic max-w-[150px] inline-block truncate" title="{{ $apt->rejection_reason }}">
                                        "{{ $apt->rejection_reason }}"
                                    </span>

                                @else
                                    <span class="text-xs text-rs-navy/40 italic">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-rs-navy/50">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-rs-navy/20 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    <p>Tidak ada data janji temu saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SCRIPT UNTUK REJECT REASON -->
<script>
    function rejectAppointment(id) {
        // 1. Munculkan Prompt Input
        let reason = prompt("Masukkan alasan penolakan janji temu ini:");
        
        // 2. Jika user klik Cancel (null) -> Batal
        if (reason === null) return;

        // 3. Jika user klik OK tapi kosong -> Error
        if (reason.trim() === "") {
            alert("Alasan penolakan wajib diisi!");
            return;
        }

        // 4. Isi hidden input dan submit form
        document.getElementById('reason-' + id).value = reason;
        document.getElementById('reject-form-' + id).submit();
    }
</script>
@endsection