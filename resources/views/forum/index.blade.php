@php
    $labelKategori = ['umum' => 'Umum', 'cerita' => 'Cerita', 'tips' => 'Tips', 'tanya' => 'Tanya-Jawab'];
    $warnaKategori = [
        'umum' => 'bg-tenang-100 text-tenang-700',
        'cerita' => 'bg-amber-100 text-amber-700',
        'tips' => 'bg-emerald-100 text-emerald-700',
        'tanya' => 'bg-sky-100 text-sky-700',
    ];
@endphp

@extends('layouts.app')

@section('title', 'Forum Komunitas')

@section('content')
<div class="py-4">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Forum Komunitas 💬</h1>
            <p class="mt-1 text-sm text-slate-600">Berbagi cerita, tips, dan bertanya dengan sesama — dengan hangat dan tanpa menghakimi.</p>
        </div>
        <a href="{{ route('forum.create') }}"
           class="inline-flex shrink-0 items-center justify-center rounded-xl bg-tenang-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-tenang-700 transition">
            ✍️ Buat Thread
        </a>
    </div>

    {{-- Catatan etiket komunitas --}}
    <div class="mt-4 rounded-xl border border-tenang-100 bg-tenang-50 px-4 py-3 text-sm text-slate-600 leading-relaxed">
        💚 Bersikap baik, bukan pengganti bantuan profesional. Forum ini adalah ruang saling mendukung — untuk bantuan darurat, hubungi profesional kesehatan mental.
    </div>

    {{-- Filter kategori --}}
    <div class="mt-5 flex flex-wrap gap-2" role="group" aria-label="Filter kategori forum">
        <a href="{{ route('forum.index') }}"
           class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ is_null($kategoriAktif) ? 'bg-tenang-600 text-white shadow' : 'bg-white border border-tenang-200 text-slate-600 hover:bg-tenang-50' }}">
            Semua
        </a>
        @foreach ($daftarKategori as $kategori)
            <a href="{{ route('forum.index', ['kategori' => $kategori]) }}"
               class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $kategoriAktif === $kategori ? 'bg-tenang-600 text-white shadow' : 'bg-white border border-tenang-200 text-slate-600 hover:bg-tenang-50' }}">
                {{ $labelKategori[$kategori] ?? $kategori }}
            </a>
        @endforeach
    </div>

    {{-- Daftar thread --}}
    @if ($threads->isEmpty())
        <div class="mt-6 rounded-2xl border border-dashed border-tenang-200 bg-white/60 p-10 text-center text-sm text-slate-500">
            Belum ada thread di kategori ini. Jadilah yang pertama memulai percakapan 🌱
        </div>
    @else
        <div class="mt-6 space-y-3">
            @foreach ($threads as $thread)
                <a href="{{ route('forum.show', $thread->id) }}"
                   class="block rounded-2xl border border-tenang-100 bg-white p-5 shadow-sm hover:shadow-md hover:border-tenang-200 transition">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $warnaKategori[$thread->category] ?? $warnaKategori['umum'] }}">
                            {{ $labelKategori[$thread->category] ?? $thread->category }}
                        </span>
                        <span class="text-xs text-slate-400">{{ $thread->created_at?->diffForHumans() }}</span>
                    </div>
                    <h2 class="mt-2 text-lg font-bold text-slate-900 leading-snug">{{ $thread->title }}</h2>
                    <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $thread->body }}</p>
                    <div class="mt-3 flex items-center gap-4 text-xs text-slate-500">
                        <span>👤 {{ $thread->author_name }}</span>
                        <span>💬 {{ $thread->replies_count }} balasan</span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $threads->links() }}
        </div>
    @endif
</div>
@endsection
