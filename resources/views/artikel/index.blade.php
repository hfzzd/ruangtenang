@extends('layouts.app')

@section('title', 'Artikel & Bacaan')

@section('content')
<div class="py-4">
    {{-- ===== Header halaman ===== --}}
    <h1 class="text-2xl font-extrabold text-slate-900">Artikel &amp; Bacaan 📚</h1>
    <p class="mt-1 text-sm text-slate-600">
        Bacaan ringan tentang kesehatan mental, ditulis dengan bahasa yang hangat dan mudah dipahami.
        Luangkan waktu sejenak untuk dirimu sendiri ya.
    </p>

    {{-- ===== Chip filter kategori (GET biasa, tanpa JS berat) ===== --}}
    <div class="mt-6 flex flex-wrap gap-2" role="group" aria-label="Saring artikel berdasarkan kategori">
        <a href="{{ route('artikel.index') }}"
           class="rounded-full px-4 py-1.5 text-sm font-semibold shadow-sm transition
                  {{ ! $selectedCategory ? 'bg-tenang-600 text-white shadow' : 'border border-tenang-200 bg-white text-tenang-700 hover:bg-tenang-50' }}">
            Semua
        </a>
        @foreach ($categories as $category)
            <a href="{{ route('artikel.index', ['kategori' => $category]) }}"
               class="rounded-full px-4 py-1.5 text-sm font-semibold shadow-sm transition
                      {{ $selectedCategory === $category ? 'bg-tenang-600 text-white shadow' : 'border border-tenang-200 bg-white text-tenang-700 hover:bg-tenang-50' }}">
                {{ $category }}
            </a>
        @endforeach
    </div>

    {{-- ===== Daftar artikel ===== --}}
    <section class="mt-8">
        @if ($articles->isEmpty())
            {{-- State kosong --}}
            <div class="rounded-2xl border border-dashed border-tenang-200 bg-white/60 p-10 text-center">
                <p class="text-3xl" aria-hidden="true">📖</p>
                <h2 class="mt-2 font-bold text-slate-800">Belum ada artikel di sini</h2>
                <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">
                    Kami sedang menyiapkan bacaan yang hangat untukmu. Cek lagi nanti ya,
                    atau jelajahi kategori lainnya dulu.
                </p>
                @if ($selectedCategory)
                    <a href="{{ route('artikel.index') }}"
                       class="mt-4 inline-block rounded-xl bg-tenang-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-tenang-700 transition">
                        Lihat semua artikel
                    </a>
                @endif
            </div>
        @else
            <p class="text-sm text-slate-500" aria-live="polite">
                Menampilkan <strong class="text-slate-700">{{ $articles->count() }}</strong> artikel
                @if ($selectedCategory)
                    dalam kategori "<strong class="text-slate-700">{{ $selectedCategory }}</strong>"
                @endif
            </p>

            {{-- Grid kartu yang tenang --}}
            <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($articles as $article)
                    <a href="{{ route('artikel.show', $article->slug) }}"
                       class="group flex flex-col overflow-hidden rounded-2xl border border-tenang-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        {{-- Cover atau placeholder gradasi --}}
                        @if ($article->cover_image)
                            <img src="{{ $article->cover_image }}" alt="Ilustrasi {{ $article->title }}"
                                 class="h-40 w-full object-cover">
                        @else
                            <div class="flex h-40 w-full items-center justify-center bg-gradient-to-br from-tenang-200 via-tenang-100 to-teal-100" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-tenang-600/60" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C7 7 4 10 4 14a8 8 0 0016 0c0-4-3-7-8-12zm0 18.5A6.5 6.5 0 015.5 14c0-3.2 2.5-5.7 6.5-10.2 4 4.5 6.5 7 6.5 10.2a6.5 6.5 0 01-6.5 6.5z"/><path d="M12 8c-2.5 2.5-4 4.2-4 6a4 4 0 008 0c0-1.8-1.5-3.5-4-6z"/></svg>
                            </div>
                        @endif

                        <div class="flex flex-1 flex-col p-5">
                            {{-- Badge kategori --}}
                            <span class="inline-flex w-fit rounded-full bg-tenang-100 px-2.5 py-0.5 text-xs font-semibold text-tenang-700">
                                {{ $article->category }}
                            </span>

                            <h2 class="mt-2 font-bold leading-snug text-slate-900 group-hover:text-tenang-700">{{ $article->title }}</h2>
                            <p class="mt-1.5 line-clamp-3 text-sm text-slate-500">{{ $article->excerpt }}</p>

                            <div class="mt-4 flex items-center justify-between border-t border-tenang-50 pt-4 mt-auto">
                                <p class="text-xs text-slate-400">
                                    {{ $article->published_at?->translatedFormat('d F Y') }}
                                </p>
                                <span class="text-sm font-semibold text-tenang-600 group-hover:underline">Baca selengkapnya →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Paginasi (query filter ikut terbawa) --}}
            <div class="mt-8">
                {{ $articles->appends(request()->query())->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
