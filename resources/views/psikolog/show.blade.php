@extends('layouts.app')

@section('title', $professional->name . ' — Profil Psikolog')

@section('content')
<div class="py-4">
    {{-- Tautan kembali --}}
    <a href="{{ route('psikolog.index') }}"
       class="inline-flex items-center gap-1 text-sm font-medium text-tenang-700 hover:underline">
        ← Kembali ke direktori
    </a>

    {{-- ===== Kartu profil ===== --}}
    <section class="mt-4 overflow-hidden rounded-2xl border border-tenang-100 bg-white shadow-sm">
        {{-- Pita atas yang menenangkan --}}
        <div class="h-24 bg-tenang-100" aria-hidden="true"></div>

        <div class="px-6 pb-6 sm:px-8">
            {{-- Foto & identitas --}}
            <div class="-mt-12 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div class="flex items-end gap-4">
                    @if ($professional->photo)
                        <img src="{{ $professional->photo }}" alt="Foto {{ $professional->name }}"
                             class="h-24 w-24 rounded-2xl border-4 border-white object-cover shadow">
                    @else
                        <span class="flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-white bg-tenang-200 text-3xl font-extrabold text-tenang-800 shadow" aria-hidden="true">
                            {{ mb_strtoupper(mb_substr($professional->name, 0, 1)) }}
                        </span>
                    @endif
                    <div class="pb-1">
                        <h1 class="text-xl font-extrabold text-slate-900 sm:text-2xl">{{ $professional->name }}</h1>
                        <p class="text-sm text-slate-500">{{ $professional->title }}</p>
                    </div>
                </div>

                @if ($professional->is_verified)
                    <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-tenang-100 px-3 py-1.5 text-xs font-semibold text-tenang-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75"/></svg>
                        Terverifikasi RuangTenang
                    </span>
                @endif
            </div>

            {{-- Fakta ringkas --}}
            <dl class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
                <div class="rounded-xl bg-tenang-50 border border-tenang-100 px-4 py-3">
                    <dt class="text-xs font-medium uppercase tracking-wide text-tenang-600">Spesialisasi</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $professional->specialization }}</dd>
                </div>
                <div class="rounded-xl bg-tenang-50 border border-tenang-100 px-4 py-3">
                    <dt class="text-xs font-medium uppercase tracking-wide text-tenang-600">Kota</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ $professional->city }}</dd>
                </div>
                <div class="rounded-xl bg-tenang-50 border border-tenang-100 px-4 py-3">
                    <dt class="text-xs font-medium uppercase tracking-wide text-tenang-600">Biaya per sesi</dt>
                    <dd class="mt-1 font-semibold text-slate-900">Rp {{ number_format($professional->price_per_session, 0, ',', '.') }}</dd>
                </div>
            </dl>

            {{-- Bio --}}
            <div class="mt-6">
                <h2 class="font-bold text-slate-900">Tentang {{ $professional->name }}</h2>
                @if ($professional->bio)
                    <div class="mt-2 text-sm leading-relaxed text-slate-600">
                        {!! nl2br(e($professional->bio)) !!}
                    </div>
                @else
                    <p class="mt-2 text-sm italic text-slate-400">
                        Psikolog ini belum menambahkan bio. Kamu tetap bisa mengenali pendekatannya dari sesi perkenalan pertama.
                    </p>
                @endif
            </div>

            {{-- CTA booking --}}
            <div class="mt-8 flex flex-col gap-3 rounded-2xl border border-tenang-100 bg-tenang-50/60 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-semibold text-slate-900">Siap memulai sesi pertamamu?</p>
                    <p class="mt-0.5 text-sm text-slate-500">
                        Pilih jadwal yang cocok — Rp {{ number_format($professional->price_per_session, 0, ',', '.') }} per sesi.
                    </p>
                </div>
                <a href="{{ route('booking.create', ['psikolog' => $professional->id]) }}"
                   class="inline-flex shrink-0 items-center justify-center rounded-xl bg-tenang-600 px-6 py-3 font-semibold text-white shadow hover:bg-tenang-700 transition">
                    Booking Sesi 📅
                </a>
            </div>

            <p class="mt-4 text-xs leading-relaxed text-slate-400">
                Catatan: profil ini adalah informasi direktori. RuangTenang tidak menjamin ketersediaan
                jadwal — jadwal akhir dikonfirmasi melalui proses booking.
            </p>
        </div>
    </section>
</div>
@endsection
