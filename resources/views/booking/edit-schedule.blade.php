@extends('layouts.app')

@section('title', 'Ubah Jadwal Booking')

@section('content')
<div class="py-4">
    <h1 class="text-2xl font-extrabold text-slate-900">Ubah Jadwal Booking 🗓️</h1>
    <p class="mt-1 text-sm text-slate-600">Rencana boleh berubah, tidak apa-apa. Pilih jadwal baru yang lebih nyaman buatmu.</p>

    @if (session('sukses'))
        <div class="mt-4 rounded-xl border border-tenang-200 bg-tenang-50 px-4 py-3 text-sm text-tenang-800" role="status">
            {{ session('sukses') }}
        </div>
    @endif

    @if (session('galat'))
        <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">
            {{ session('galat') }}
        </div>
    @endif

    {{-- ===== Ringkasan booking ===== --}}
    <section class="mt-6 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm">
        <p class="text-sm text-slate-500">Kode booking: <span class="font-mono font-semibold text-slate-700">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span></p>
        <dl class="mt-4 grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Psikolog</dt>
                <dd class="mt-1 font-semibold text-slate-900">
                    {{ $booking->professional?->name ?? '—' }}
                    <span class="block text-sm font-normal text-slate-500">
                        {{ $booking->professional?->title }} · {{ $booking->professional?->specialization }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Jadwal Saat Ini</dt>
                <dd class="mt-1 font-semibold text-tenang-700">
                    {{ $booking->scheduled_at?->translatedFormat('l, d F Y · H:i') ?? $booking->scheduled_at }}
                    <span class="block text-sm font-normal text-slate-500">{{ $booking->duration_minutes }} menit</span>
                </dd>
            </div>
        </dl>
    </section>

    {{-- ===== Form jadwal baru ===== --}}
    <section class="mt-6 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm">
        <h2 class="font-bold text-slate-900">Jadwal Baru</h2>
        <p class="mt-1 text-sm text-slate-600">Pilih tanggal, jam, dan durasi yang baru. Kami akan memberitahu psikologmu tentang perubahan ini. 💚</p>

        <form method="POST" action="{{ route('booking.updateSchedule', $booking->id) }}" class="mt-5">
            @csrf
            @method('PATCH')

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="scheduled_at" class="block text-sm font-semibold text-slate-700">Tanggal &amp; Jam Baru</label>
                    <input id="scheduled_at" type="datetime-local" name="scheduled_at"
                           value="{{ old('scheduled_at', optional($booking->scheduled_at)->format('Y-m-d\TH:i')) }}"
                           min="{{ now()->addDay()->format('Y-m-d\TH:i') }}" required
                           class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('scheduled_at') border-red-400 @enderror">
                    <p class="mt-1 text-xs text-slate-500">Minimal besok — supaya psikolog punya waktu menyiapkan diri.</p>
                    @error('scheduled_at')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="duration_minutes" class="block text-sm font-semibold text-slate-700">Durasi Sesi</label>
                    <select id="duration_minutes" name="duration_minutes" required
                            class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('duration_minutes') border-red-400 @enderror">
                        @foreach ([30 => '30 menit', 45 => '45 menit', 60 => '60 menit (disarankan)', 90 => '90 menit'] as $menit => $label)
                            <option value="{{ $menit }}" @selected(old('duration_minutes', $booking->duration_minutes) == $menit)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('duration_minutes')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <button type="submit"
                        class="rounded-xl bg-tenang-600 px-6 py-2.5 font-semibold text-white shadow hover:bg-tenang-700 transition">
                    Simpan Jadwal Baru
                </button>
                <a href="{{ route('booking.show', $booking->id) }}"
                   class="rounded-xl border border-tenang-200 px-6 py-2.5 text-center font-medium hover:bg-tenang-50 transition">
                    Kembali ke Detail
                </a>
            </div>
        </form>
    </section>
</div>
@endsection
