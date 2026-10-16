@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="py-4">
    <h1 class="text-2xl font-extrabold text-slate-900">Profil Saya 🌿</h1>
    <p class="mt-1 text-sm text-slate-600">Ruang kecil untuk mengenal dirimu sendiri.</p>

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

    {{-- ===== Kartu profil ===== --}}
    <section class="mt-6 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center gap-5">
            <span class="inline-flex h-20 w-20 shrink-0 items-center justify-center rounded-full bg-tenang-100 text-3xl font-extrabold text-tenang-700" aria-hidden="true">
                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
            </span>
            <div>
                <h2 class="text-xl font-extrabold text-slate-900">{{ $user->name }}</h2>
                <p class="mt-1 text-sm text-slate-600">{{ $user->email }}</p>
                <p class="mt-1 text-xs text-slate-500">Bergabung sejak {{ $user->created_at->translatedFormat('d F Y') }}</p>
            </div>
        </div>

        {{-- ===== Ringkasan aktivitas ===== --}}
        <dl class="mt-6 grid gap-4 sm:grid-cols-3 border-t border-tenang-100 pt-5">
            <div class="rounded-xl bg-tenang-50 border border-tenang-100 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Booking</dt>
                <dd class="mt-1 text-2xl font-extrabold text-tenang-700">{{ $user->bookings_count ?? $user->bookings->count() }}</dd>
            </div>
            <div class="rounded-xl bg-tenang-50 border border-tenang-100 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Entri Jurnal</dt>
                <dd class="mt-1 text-2xl font-extrabold text-tenang-700">{{ $user->journal_entries_count ?? $user->journalEntries->count() }}</dd>
            </div>
            <div class="rounded-xl bg-tenang-50 border border-tenang-100 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Catatan Mood</dt>
                <dd class="mt-1 text-2xl font-extrabold text-tenang-700">{{ $user->mood_entries_count ?? $user->moodEntries->count() }}</dd>
            </div>
        </dl>

        {{-- ===== Aksi ===== --}}
        <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <a href="{{ route('profil.edit') }}"
               class="rounded-xl bg-tenang-600 px-6 py-2.5 text-center font-semibold text-white shadow hover:bg-tenang-700 transition">
                Ubah Profil
            </a>
            <a href="{{ route('dashboard') }}"
               class="rounded-xl border border-tenang-200 px-6 py-2.5 text-center font-medium hover:bg-tenang-50 transition">
                Dashboard Saya
            </a>
        </div>
    </section>
</div>
@endsection
