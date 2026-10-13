@extends('layouts.app')

@section('title', 'Booking Saya')

@section('content')
<div class="py-4">
    <h1 class="text-2xl font-extrabold text-slate-900">Booking Saya 📅</h1>
    <p class="mt-1 text-sm text-slate-600">Masukkan email yang kamu pakai saat booking untuk melihat jadwalmu.</p>

    {{-- ===== Form cari ===== --}}
    <section class="mt-6 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm">
        <form method="GET" action="{{ route('booking.index') }}" class="flex flex-col sm:flex-row gap-3">
            <label for="email" class="sr-only">Email booking</label>
            <input id="email" type="email" name="email" value="{{ $email ?? '' }}" required
                   placeholder="nama@email.com"
                   class="flex-1 rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500">
            <button type="submit"
                    class="rounded-xl bg-tenang-600 px-6 py-2.5 font-semibold text-white shadow hover:bg-tenang-700 transition">
                Cari
            </button>
        </form>
        @error('email')
            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </section>

    {{-- ===== Hasil ===== --}}
    @if ($searched)
        <section class="mt-6">
            @if ($bookings->isEmpty())
                <div class="rounded-2xl border border-dashed border-tenang-200 bg-white/60 p-8 text-center text-sm text-slate-500">
                    Belum ada booking untuk email <strong>{{ $email }}</strong>.<br>
                    <a href="{{ route('booking.create') }}" class="mt-2 inline-block font-semibold text-tenang-700 hover:underline">
                        Buat booking pertama → ambil langkah beranimu hari ini. 🌱
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($bookings as $booking)
                        @php
                            $badge = [
                                'pending'   => 'bg-amber-100 text-amber-800 border-amber-200',
                                'confirmed' => 'bg-tenang-100 text-tenang-800 border-tenang-200',
                                'completed' => 'bg-sky-100 text-sky-800 border-sky-200',
                                'cancelled' => 'bg-rose-100 text-rose-800 border-rose-200',
                            ][$booking->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                            $labelStatus = [
                                'pending'   => 'Menunggu konfirmasi',
                                'confirmed' => 'Dikonfirmasi',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ][$booking->status] ?? ucfirst($booking->status);
                        @endphp
                        <a href="{{ route('booking.show', $booking->id) }}"
                           class="block rounded-2xl border border-tenang-100 bg-white p-5 shadow-sm hover:shadow transition">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <p class="font-bold text-slate-900">
                                        {{ $booking->scheduled_at?->translatedFormat('l, d M Y · H:i') ?? $booking->scheduled_at }}
                                        <span class="font-normal text-slate-500">· {{ $booking->duration_minutes }} menit</span>
                                    </p>
                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ $booking->professional?->name ?? '—' }}
                                        <span class="text-slate-400">· {{ $booking->professional?->title }}</span>
                                    </p>
                                </div>
                                <span class="inline-flex self-start sm:self-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badge }}">
                                    {{ $labelStatus }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </section>
    @endif
</div>
@endsection
