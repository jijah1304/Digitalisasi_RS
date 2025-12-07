@extends('layouts.app')

@section('content')
@include('layouts.navigation')

<div class="max-w-7xl mx-auto px-6 pb-12">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-rs-navy">Daftar Feedback Pasien</h1>
                <p class="text-rs-navy/60">Feedback dari pasien.</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <!-- Total Feedback -->
        <div class="glass p-6 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-sm text-rs-navy/60 font-medium">Total Feedback</p>
                <p class="text-3xl font-bold text-rs-navy">{{ $feedbacks->total() }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="glass p-6 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-sm text-rs-navy/60 font-medium">Rata-rata Rating</p>
                <div class="flex items-center gap-2">
                    <div class="flex text-yellow-400 text-xl">
                        @for($i=0; $i<floor($averageRating); $i++) ★ @endfor
                        @if($averageRating - floor($averageRating) >= 0.5) ★ @endif
                        @for($i=ceil($averageRating); $i<5; $i++) <span class="text-gray-300">★</span> @endfor
                    </div>
                    <span class="text-2xl font-bold text-rs-navy">{{ number_format($averageRating, 1) }}</span>
                </div>
            </div>
            <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
            </div>
        </div>

        <!-- Rating Distribution -->
        <div class="glass p-6 rounded-2xl">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm text-rs-navy/60 font-medium">Distribusi Rating</p>
                <div class="w-8 h-8 bg-rs-navy/10 text-rs-navy rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <div class="space-y-1">
                @for($i=5; $i>=1; $i--)
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center gap-1">
                            @for($j=0; $j<$i; $j++) <span class="text-yellow-400">★</span> @endfor
                        </div>
                        <span class="text-rs-navy font-medium">{{ $ratingCounts[$i] ?? 0 }}</span>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Feedback List -->
    <div class="glass rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white/40 text-rs-navy text-sm uppercase tracking-wider">
                    <tr>
                        <th class="p-5 font-semibold">Tanggal</th>
                        <th class="p-5 font-semibold">Pasien</th>
                        <th class="p-5 font-semibold">Dokter</th>
                        <th class="p-5 font-semibold">Rating</th>
                        <th class="p-5 font-semibold">Ulasan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($feedbacks as $feedback)
                        <tr class="hover:bg-white/30 transition-colors">
                            <td class="p-5 text-sm font-medium text-rs-navy">
                                {{ \Carbon\Carbon::parse($feedback->updated_at)->translatedFormat('d M Y') }}
                                <div class="text-xs text-rs-navy/50">{{ \Carbon\Carbon::parse($feedback->updated_at)->format('H:i') }}</div>
                            </td>

                            <td class="p-5">
                                <div class="font-bold text-rs-navy">{{ $feedback->patient->name }}</div>
                                <div class="text-xs text-rs-navy/50">{{ $feedback->patient->email }}</div>
                            </td>

                            <td class="p-5">
                                <div class="font-bold text-rs-navy">{{ $feedback->doctor->name }}</div>
                                <div class="text-xs text-rs-teal">{{ $feedback->doctor->poli->name ?? 'Umum' }}</div>
                            </td>

                            <td class="p-5">
                                <div class="flex text-yellow-400 text-sm">
                                    @for($i=0; $i<$feedback->rating; $i++) ★ @endfor
                                    @for($i=$feedback->rating; $i<5; $i++) <span class="text-gray-300">★</span> @endfor
                                </div>
                                <div class="text-xs font-bold text-rs-navy mt-1">{{ $feedback->rating }}.0</div>
                            </td>

                            <td class="p-5 text-sm text-rs-navy/80 italic max-w-xs">
                                {{ Str::limit($feedback->feedback ?? '-', 100) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-rs-navy/50 italic">
                                Belum ada feedback dari pasien.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($feedbacks->hasPages())
            <div class="px-6 py-4 bg-white/20 border-t border-white/30">
                {{ $feedbacks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
