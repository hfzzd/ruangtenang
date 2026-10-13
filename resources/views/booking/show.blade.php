@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')
<div class="py-4">
    <h1 class="text-2xl font-extrabold text-slate-900">Detail Booking 📝</h1>

    @if (session('sukses'))
        <div class="mt-4 rounded-xl border border-tenang-200 bg-tenang-50 px-4 py-3 text-sm text-tenang-800" role="status">
            {{ session('sukses') }}
        </div>
    @endif

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

    <section class="mt-6 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <p class="text-sm text-slate-500">Kode booking: <span class="font-mono font-semibold text-slate-700">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span></p>
            <span class="inline-flex self-start rounded-full border px-3 py-1 text-xs font-semibold {{ $badge }}">
                {{ $labelStatus }}
            </span>
        </div>

        <dl class="mt-5 grid gap-4 sm:grid-cols-2">
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
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Lokasi</dt>
                <dd class="mt-1 text-slate-700">{{ $booking->professional?->city ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Jadwal</dt>
                <dd class="mt-1 font-semibold text-tenang-700">
                    {{ $booking->scheduled_at?->translatedFormat('l, d F Y · H:i') ?? $booking->scheduled_at }}
                    <span class="block text-sm font-normal text-slate-500">{{ $booking->duration_minutes }} menit</span>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Biaya Sesi</dt>
                <dd class="mt-1 font-semibold text-slate-900">Rp {{ number_format($booking->price, 0, ',', '.') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Pemesan</dt>
                <dd class="mt-1 text-slate-700">
                    {{ $booking->user?->name ?? '—' }}
                    <span class="block text-sm text-slate-500">{{ $booking->user?->email }}</span>
                </dd>
            </div>
            @if ($booking->notes)
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Catatan</dt>
                    <dd class="mt-1 text-sm text-slate-700 whitespace-pre-line">{{ $booking->notes }}</dd>
                </div>
            @endif
        </dl>

        @if ($booking->status === 'pending')
            <p class="mt-5 rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-800">
                Status <strong>menunggu konfirmasi</strong> — kami akan menghubungimu lewat email untuk memastikan jadwal sebelum sesi dimulai. Terima kasih sudah berani mengambil langkah ini. 💚
            </p>
        @elseif ($booking->status === 'confirmed')
            <p class="mt-5 rounded-xl bg-tenang-50 border border-tenang-200 px-4 py-3 text-sm text-tenang-800">
                Jadwalmu sudah <strong>dikonfirmasi</strong>. Datanglah beberapa menit lebih awal dan bawa dirimu yang apa adanya. Kami menunggumu. 💚
            </p>
        @elseif ($booking->status === 'cancelled')
            <p class="mt-5 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-800">
                Booking ini <strong>dibatalkan</strong>. Tidak apa-apa — kamu bisa membuat booking baru kapan pun kamu siap.
            </p>
        @endif

        <div class="mt-6 border-t border-tenang-100 pt-5">
            <p class="text-xs leading-relaxed text-slate-500">
                <span class="font-semibold text-slate-600">Pengingat lembut:</span>
                RuangTenang adalah ruang pendukung dan <strong>bukan pengganti bantuan darurat atau layanan kesehatan profesional</strong>.
                Jika kamu berada dalam krisis atau membahayakan dirimu sendiri, segera hubungi layanan darurat (119 ext. 8) atau profesional kesehatan mental terdekat.
            </p>
        </div>

        <div class="mt-5 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('booking.index') }}" class="rounded-xl border border-tenang-200 px-6 py-2.5 text-center font-medium hover:bg-tenang-50 transition">
                Kembali ke Booking Saya
            </a>
            <a href="{{ route('booking.create') }}" class="rounded-xl bg-tenang-600 px-6 py-2.5 text-center font-semibold text-white shadow hover:bg-tenang-700 transition">
                Buat Booking Baru
            </a>
        </div>
    </section>
</div>
@endsection
