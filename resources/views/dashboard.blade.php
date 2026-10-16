@extends('layouts.app')

@section('title', 'Dasbor Saya')

@section('content')
<div class="py-4">
    {{-- ===== Sapaan ===== --}}
    <h1 class="text-2xl font-extrabold text-slate-900">Halo, {{ $user->name }} 🌱</h1>
    <p class="mt-1 text-sm text-slate-600">
        Ini ringkasan ruang pribadimu — booking yang akan datang, jurnal terakhirmu,
        dan perjalanan mood-mu minggu ini.
    </p>

    @php
        $moodLabels = [1 => 'Sangat Buruk', 2 => 'Buruk', 3 => 'Biasa Saja', 4 => 'Baik', 5 => 'Sangat Baik'];
        $moodEmojis = [1 => '😞', 2 => '😔', 3 => '😐', 4 => '🙂', 5 => '😄'];
    @endphp

    <div class="mt-6 grid gap-6 md:grid-cols-2">
        {{-- ===== Booking Mendatang ===== --}}
        <section class="rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm" aria-labelledby="judul-booking">
            <div class="flex items-center justify-between">
                <h2 id="judul-booking" class="font-bold text-slate-800">📅 Booking Mendatang</h2>
                <a href="{{ route('booking.index') }}" class="text-sm font-semibold text-tenang-700 hover:text-tenang-600">
                    Lihat semua →
                </a>
            </div>
            @if ($upcomingBookings->isEmpty())
                <div class="mt-4 rounded-2xl border border-dashed border-tenang-200 bg-tenang-50/50 p-6 text-center">
                    <p class="text-2xl" aria-hidden="true">🗓️</p>
                    <p class="mt-2 text-sm text-slate-600">
                        Belum ada jadwal konsultasi. Kalau butuh teman bicara profesional,
                        yuk intip direktori psikolog.
                    </p>
                    <a href="{{ url('/psikolog') }}"
                       class="mt-3 inline-block rounded-xl bg-tenang-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-tenang-700 transition">
                        Cari Psikolog
                    </a>
                </div>
            @else
                <ul class="mt-4 space-y-3">
                    @foreach ($upcomingBookings as $booking)
                        <li>
                            <a href="{{ route('booking.show', $booking->id) }}"
                               class="block rounded-xl border border-tenang-100 p-4 transition hover:border-tenang-300 hover:bg-tenang-50/50">
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $booking->scheduled_at->translatedFormat('l, d F Y') }}
                                </p>
                                <p class="mt-0.5 text-sm text-slate-600">
                                    {{ $booking->scheduled_at->format('H:i') }} WIB
                                    · {{ $booking->duration_minutes }} menit
                                    · {{ $booking->professional->name ?? 'Psikolog' }}
                                </p>
                                <span class="mt-2 inline-block rounded-full bg-tenang-100 px-2.5 py-0.5 text-xs font-semibold text-tenang-700">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        {{-- ===== Jurnal Terakhir ===== --}}
        <section class="rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm" aria-labelledby="judul-jurnal">
            <div class="flex items-center justify-between">
                <h2 id="judul-jurnal" class="font-bold text-slate-800">📝 Jurnal Terakhir</h2>
                <a href="{{ route('jurnal') }}" class="text-sm font-semibold text-tenang-700 hover:text-tenang-600">
                    Buka jurnal →
                </a>
            </div>
            @if ($recentJournals->isEmpty())
                <div class="mt-4 rounded-2xl border border-dashed border-tenang-200 bg-tenang-50/50 p-6 text-center">
                    <p class="text-2xl" aria-hidden="true">✍️</p>
                    <p class="mt-2 text-sm text-slate-600">
                        Belum ada tulisan. Menulis jurnal beberapa menit saja
                        bisa bikin pikiran lebih lega, lho.
                    </p>
                    <a href="{{ route('jurnal') }}"
                       class="mt-3 inline-block rounded-xl bg-tenang-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-tenang-700 transition">
                        Tulis Jurnal
                    </a>
                </div>
            @else
                <ul class="mt-4 space-y-3">
                    @foreach ($recentJournals as $journal)
                        <li class="rounded-xl border border-tenang-100 p-4">
                            <p class="text-sm font-semibold text-slate-800">{{ $journal->title }}</p>
                            <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $journal->content }}</p>
                            <p class="mt-2 text-xs text-slate-400">
                                {{ $journal->created_at->translatedFormat('d F Y') }}
                            </p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>

    {{-- ===== Mood 7 Hari Terakhir ===== --}}
    <section class="mt-6 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm" aria-labelledby="judul-mood">
        <div class="flex items-center justify-between">
            <h2 id="judul-mood" class="font-bold text-slate-800">💚 Mood 7 Hari Terakhir</h2>
            <a href="{{ route('mood') }}" class="text-sm font-semibold text-tenang-700 hover:text-tenang-600">
                Check-in mood →
            </a>
        </div>
        @if ($moodWeek->isEmpty())
            <div class="mt-4 rounded-2xl border border-dashed border-tenang-200 bg-tenang-50/50 p-6 text-center">
                <p class="text-2xl" aria-hidden="true">🌤️</p>
                <p class="mt-2 text-sm text-slate-600">
                    Belum ada check-in minggu ini. Yuk catat perasaanmu hari ini —
                    cuma butuh beberapa detik.
                </p>
                <a href="{{ route('mood') }}"
                   class="mt-3 inline-block rounded-xl bg-tenang-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-tenang-700 transition">
                    Check-in Sekarang
                </a>
            </div>
        @else
            @if ($moodAverage !== null)
                <p class="mt-3 text-sm text-slate-600">
                    Rata-rata minggu ini:
                    <strong class="text-slate-800">{{ number_format($moodAverage, 1) }}/5</strong>
                    ({{ $moodLabels[(int) round($moodAverage)] ?? '' }})
                </p>
            @endif
            <ul class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-4 lg:grid-cols-7">
                @foreach ($moodWeek as $entry)
                    <li class="rounded-xl border border-tenang-100 bg-tenang-50/50 p-3 text-center"
                        title="{{ $moodLabels[$entry->mood] ?? '' }}">
                        <p class="text-2xl" aria-hidden="true">{{ $moodEmojis[$entry->mood] ?? '😐' }}</p>
                        <p class="mt-1 text-xs font-semibold text-slate-700">{{ $moodLabels[$entry->mood] ?? '' }}</p>
                        <p class="text-[11px] text-slate-400">
                            {{ $entry->created_at->translatedFormat('d M') }}
                        </p>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
</div>
@endsection
