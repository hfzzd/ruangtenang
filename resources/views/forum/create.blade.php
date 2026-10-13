@php
    $labelKategori = ['umum' => 'Umum', 'cerita' => 'Cerita', 'tips' => 'Tips', 'tanya' => 'Tanya-Jawab'];
@endphp

@extends('layouts.app')

@section('title', 'Buat Thread Baru')

@section('content')
<div class="py-4 max-w-2xl mx-auto">
    <a href="{{ route('forum.index') }}" class="text-sm font-medium text-tenang-600 hover:text-tenang-700 transition">
        ← Kembali ke forum
    </a>

    <h1 class="mt-2 text-2xl font-extrabold text-slate-900">Buat Thread Baru ✍️</h1>
    <p class="mt-1 text-sm text-slate-600">Ceritakan yang ada di pikiranmu. Tidak perlu sempurna — yang penting jujur.</p>

    {{-- Catatan etiket komunitas --}}
    <div class="mt-4 rounded-xl border border-tenang-100 bg-tenang-50 px-4 py-3 text-sm text-slate-600 leading-relaxed">
        💚 Bersikap baik, bukan pengganti bantuan profesional. Tulis dengan bahasa yang suportif, ya.
    </div>

    <form method="POST" action="{{ route('forum.store') }}" class="mt-5 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm space-y-5">
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
                   placeholder="Mis. Anonim yang Peduli">
            @error('author_name')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="title" class="block text-sm font-medium text-slate-700">Judul thread</label>
            <input id="title" name="title" type="text" maxlength="150" required
                   value="{{ old('title') }}"
                   class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('title') border-red-400 @enderror"
                   placeholder="Mis. Cara menghadapi overthinking sebelum tidur">
            @error('title')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="category" class="block text-sm font-medium text-slate-700">Kategori</label>
            <select id="category" name="category" required
                    class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('category') border-red-400 @enderror">
                @foreach ($daftarKategori as $kategori)
                    <option value="{{ $kategori }}" @selected(old('category') === $kategori)>
                        {{ $labelKategori[$kategori] ?? $kategori }}
                    </option>
                @endforeach
            </select>
            @error('category')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="body" class="block text-sm font-medium text-slate-700">Isi thread</label>
            <textarea id="body" name="body" rows="7" maxlength="5000" required
                      class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('body') border-red-400 @enderror"
                      placeholder="Ceritakan lebih lengkap di sini…">{{ old('body') }}</textarea>
            @error('body')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
            <a href="{{ route('forum.index') }}"
               class="rounded-xl border border-tenang-200 px-5 py-2.5 text-sm font-medium text-slate-600 text-center hover:bg-tenang-50 transition">
                Batal
            </a>
            <button type="submit"
                    class="rounded-xl bg-tenang-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-tenang-700 transition">
                Kirim Thread
            </button>
        </div>
    </form>
</div>
@endsection
