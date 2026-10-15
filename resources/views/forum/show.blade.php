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

@section('title', $thread->title)

@section('content')
<div class="py-4 max-w-3xl mx-auto">
    <a href="{{ route('forum.index') }}" class="text-sm font-medium text-tenang-600 hover:text-tenang-700 transition">
        ← Kembali ke forum
    </a>

    @if (session('status'))
        <div class="mt-4 rounded-xl border border-tenang-200 bg-tenang-50 px-4 py-3 text-sm text-tenang-800" role="status">
            {{ session('status') }}
        </div>
    @endif

    {{-- ===== Isi thread ===== --}}
    <article class="mt-4 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-center gap-2">
            <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $warnaKategori[$thread->category] ?? $warnaKategori['umum'] }}">
                {{ $labelKategori[$thread->category] ?? $thread->category }}
            </span>
            <span class="text-xs text-slate-400">{{ $thread->created_at?->diffForHumans() }}</span>
        </div>
        <h1 class="mt-3 text-2xl font-extrabold text-slate-900 leading-snug">{{ $thread->title }}</h1>
        <p class="mt-1 text-sm text-slate-500">oleh <span class="font-medium text-tenang-700">{{ $thread->author_name }}</span></p>
        <div class="mt-4 text-slate-700 leading-relaxed whitespace-pre-line">{{ $thread->body }}</div>
    </article>

    {{-- ===== Laporkan thread ===== --}}
    <details class="mt-4 rounded-2xl border border-tenang-100 bg-white/70 px-5 py-4 shadow-sm">
        <summary class="cursor-pointer list-none text-sm font-medium text-slate-500 hover:text-tenang-700 transition flex items-center gap-2">
            <span aria-hidden="true">🚩</span> Laporkan thread ini
        </summary>
        <form method="POST" action="{{ route('forum.report', $thread->id) }}" class="mt-4 space-y-4">
            @csrf
            <p class="text-xs text-slate-500 leading-relaxed">
                Kalau thread ini melanggar etiket komunitas, kasih tahu kami yaa. Laporanmu membantu menjaga forum tetap aman dan hangat 🌱
            </p>
            <div>
                <label for="report_reason" class="block text-sm font-medium text-slate-700">Alasan laporan</label>
                <select id="report_reason" name="reason" required
                        class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('reason') border-red-400 @enderror">
                    <option value="" disabled {{ old('reason') ? '' : 'selected' }}>Pilih alasan…</option>
                    <option value="Spam" {{ old('reason') === 'Spam' ? 'selected' : '' }}>Spam</option>
                    <option value="Ujaran kebencian" {{ old('reason') === 'Ujaran kebencian' ? 'selected' : '' }}>Ujaran kebencian</option>
                    <option value="Konten tidak pantas" {{ old('reason') === 'Konten tidak pantas' ? 'selected' : '' }}>Konten tidak pantas</option>
                    <option value="Informasi menyesatkan" {{ old('reason') === 'Informasi menyesatkan' ? 'selected' : '' }}>Informasi menyesatkan</option>
                    <option value="Lainnya" {{ old('reason') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('reason')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="reporter_name" class="block text-sm font-medium text-slate-700">Nama <span class="font-normal text-slate-400">(opsional)</span></label>
                <input id="reporter_name" name="reporter_name" type="text" maxlength="50"
                       value="{{ old('reporter_name') }}"
                       class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('reporter_name') border-red-400 @enderror"
                       placeholder="Boleh dikosongkan untuk anonim">
                @error('reporter_name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit"
                    class="rounded-xl border border-tenang-300 bg-tenang-50 px-5 py-2 text-sm font-semibold text-tenang-700 hover:bg-tenang-100 transition">
                Kirim Laporan
            </button>
        </form>
    </details>

    {{-- ===== Daftar balasan ===== --}}
    <section class="mt-8" aria-label="Balasan">
        <h2 class="font-bold text-slate-900">Balasan ({{ $thread->replies->count() }})</h2>

        @if ($thread->replies->isEmpty())
            <div class="mt-4 rounded-2xl border border-dashed border-tenang-200 bg-white/60 p-8 text-center text-sm text-slate-500">
                Belum ada balasan. Jadilah yang pertama memberi dukungan 🌱
            </div>
        @else
            <div class="mt-4 space-y-3">
                @foreach ($thread->replies as $reply)
                    <article class="rounded-2xl border border-tenang-100 bg-white p-5 shadow-sm">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-semibold text-tenang-700">{{ $reply->author_name }}</p>
                            <time class="text-xs text-slate-400">{{ $reply->created_at?->diffForHumans() }}</time>
                        </div>
                        <div class="mt-2 text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $reply->body }}</div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ===== Form balas ===== --}}
    <section class="mt-8 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm" aria-label="Formulir balasan">
        <h2 class="font-bold text-slate-900">Kirim Balasan 💬</h2>
        <p class="mt-1 text-xs text-slate-500">Bersikap baik, bukan pengganti bantuan profesional — balaslah dengan empati.</p>

        <form method="POST" action="{{ route('forum.reply', $thread->id) }}" class="mt-4 space-y-4">
            @csrf

            @if ($errors->any())
                <div class="rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800" role="alert">
                    <p class="font-semibold">Ada yang perlu diperbaiki:</p>
                    <ul class="mt-1 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="author_name" class="block text-sm font-medium text-slate-700">Nama tampilan</label>
                <input id="author_name" name="author_name" type="text" maxlength="50" required
                       value="{{ old('author_name') }}"
                       class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('author_name') border-red-400 @enderror"
                       placeholder="Mis. Teman yang Mendengarkan">
                @error('author_name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="body" class="block text-sm font-medium text-slate-700">Balasanmu</label>
                <textarea id="body" name="body" rows="4" maxlength="2000" required
                          class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('body') border-red-400 @enderror"
                          placeholder="Tulis dukungan atau jawaban yang hangat…">{{ old('body') }}</textarea>
                @error('body')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full sm:w-auto rounded-xl bg-tenang-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-tenang-700 transition">
                Kirim Balasan
            </button>
        </form>
    </section>
</div>
@endsection
