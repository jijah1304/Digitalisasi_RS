@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-4xl mx-auto px-6 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-rs-navy">Jadwal Praktik Saya</h1>
            <p class="text-rs-navy/60">Kelola slot waktu ketersediaan Anda untuk pasien.</p>
        </div>
        <a href="{{ route('schedules.create') }}" class="px-6 py-3 bg-rs-green text-white rounded-xl font-bold shadow-lg hover:bg-rs-navy transition-all transform hover:-translate-y-1 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
            Tambah Jadwal Baru
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-rs-green text-green-700 rounded-r-xl shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="glass rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                    <tr>
                        <th class="p-5 font-semibold">Hari</th>
                        <th class="p-5 font-semibold">Jam Mulai</th>
                        <th class="p-5 font-semibold">Jam Selesai</th>
                        <th class="p-5 font-semibold">Durasi</th>
                        <th class="p-5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($schedules as $schedule)
                        <tr class="hover:bg-white/30 transition-colors">
                            <td class="p-5 font-bold text-rs-navy">{{ $schedule->day }}</td>
                            <td class="p-5 font-mono text-rs-teal">{{ $schedule->start_time }}</td>
                            <td class="p-5 font-mono text-rs-teal">{{ $schedule->end_time }}</td>
                            <td class="p-5 text-sm text-rs-navy/60">30 Menit</td>
                            <td class="p-5 text-center flex justify-center gap-3">
                                <a href="{{ route('schedules.edit', $schedule->id) }}" class="p-2 text-rs-teal hover:bg-rs-teal/10 rounded-lg transition-colors" title="Edit Jadwal">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                </a>
                                <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus Jadwal">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-rs-navy/50 italic">
                                Anda belum membuat jadwal praktik.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection