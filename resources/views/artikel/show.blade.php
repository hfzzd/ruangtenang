@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="py-4">
    {{-- ===== Tombol kembali ===== --}}
    <a href="{{ route('artikel.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-semibold text-tenang-600 hover:underline">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12l7.5-7.5M3 12h18"/></svg>
        Kembali ke daftar artikel
    </a>

    {{-- ===== Isi artikel ===== --}}
    <article class="mt-4 overflow-hidden rounded-2xl border border-tenang-100 bg-white shadow-sm">
        {{-- Cover atau placeholder gradasi --}}
        @if ($article->cover_image)
            <img src="{{ $article->cover_image }}" alt="Ilustrasi {{ $article->title }}"
                 class="h-56 w-full object-cover sm:h-72">
        @else
            <div class="flex h-56 w-full items-center justify-center bg-gradient-to-br from-tenang-200 via-tenang-100 to-teal-100 sm:h-72" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-tenang-600/60" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C7 7 4 10 4 14a8 8 0 0016 0c0-4-3-7-8-12zm0 18.5A6.5 6.5 0 015.5 14c0-3.2 2.5-5.7 6.5-10.2 4 4.5 6.5 7 6.5 10.2a6.5 6.5 0 01-6.5 6.5z"/><path d="M12 8c-2.5 2.5-4 4.2-4 6a4 4 0 008 0c0-1.8-1.5-3.5-4-6z"/></svg>
            </div>
        @endif

        <div class="p-6 sm:p-8">
            {{-- Badge kategori --}}
            <span class="inline-flex rounded-full bg-tenang-100 px-3 py-1 text-xs font-semibold text-tenang-700">
                {{ $article->category }}
            </span>

            {{-- Judul --}}
            <h1 class="mt-3 text-2xl font-extrabold leading-snug text-slate-900 sm:text-3xl">{{ $article->title }}</h1>

            {{-- Meta tanggal + kategori --}}
            <p class="mt-2 text-sm text-slate-500">
                Dipublikasikan <strong class="font-semibold text-slate-600">{{ $article->published_at?->translatedFormat('d F Y') }}</strong>
                &middot; Kategori <strong class="font-semibold text-slate-600">{{ $article->category }}</strong>
            </p>

            {{-- Konten: escaped + nl2br. Aman dari XSS — e() meniadakan HTML user dulu,
                 nl2br hanya menambahkan <br>, lalu dirender dengan {!! !!} yang terkontrol. --}}
            <div class="mt-6 space-y-4 text-[15px] leading-relaxed text-slate-700">
                {!! nl2br(e($article->content)) !!}
            </div>
        </div>
    </article>

    {{-- ===== Artikel terkait ===== --}}
    @if ($related->isNotEmpty())
        <section class="mt-10">
            <h2 class="text-lg font-extrabold text-slate-900">Artikel terkait 🌱</h2>
            <p class="mt-1 text-sm text-slate-500">Bacaan lain yang mungkin menenangkanmu.</p>

            <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-3">
                @foreach ($related as $item)
                    <a href="{{ route('artikel.show', $item->slug) }}"
                       class="group rounded-2xl border border-tenang-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <span class="inline-flex rounded-full border border-tenang-100 bg-tenang-50 px-2.5 py-0.5 text-xs font-semibold text-tenang-700">
                            {{ $item->category }}
                        </span>
                        <h3 class="mt-2 line-clamp-3 font-bold leading-snug text-slate-900 group-hover:text-tenang-700">{{ $item->title }}</h3>
                        <p class="mt-2 text-sm font-semibold text-tenang-600 group-hover:underline">Baca →</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
