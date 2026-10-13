@extends('layouts.app')

@section('title', 'Direktori Psikolog')

@section('content')
<div class="py-4">
    {{-- ===== Header halaman ===== --}}
    <h1 class="text-2xl font-extrabold text-slate-900">Direktori Psikolog 🌿</h1>
    <p class="mt-1 text-sm text-slate-600">
        Temukan profesional yang tepat untuk perjalanan kesehatan mentalmu. Cari berdasarkan nama,
        atau saring menurut spesialisasi dan kota.
    </p>

    {{-- ===== Form pencarian & filter (GET biasa, tanpa JS berat) ===== --}}
    <section class="mt-6 rounded-2xl border border-tenang-100 bg-white p-4 shadow-sm sm:p-5">
        <form method="GET" action="{{ route('psikolog.index') }}"
              class="flex flex-col gap-3 lg:flex-row lg:items-end">
            {{-- Pencarian nama (Alpine kecil untuk tombol hapus) --}}
            <div class="flex-1" x-data="{ cari: @js($q) }" x-cloak>
                <label for="cari-nama" class="block text-sm font-medium text-slate-700">Cari nama</label>
                <div class="relative mt-1">
                    <input id="cari-nama" type="search" name="q" x-model="cari"
                           value="{{ $q }}"
                           class="block w-full rounded-xl border-slate-300 pr-10 shadow-sm focus:border-tenang-500 focus:ring-tenang-500"
                           placeholder="cth. Sari, Budi, dr. …">
                    <button type="button" x-show="cari" @click="cari = ''" aria-label="Hapus pencarian"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Filter spesialisasi --}}
            <div class="lg:w-56">
                <label for="filter-spesialisasi" class="block text-sm font-medium text-slate-700">Spesialisasi</label>
                <select id="filter-spesialisasi" name="spesialisasi"
                        class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500">
                    <option value="">Semua spesialisasi</option>
                    @foreach ($daftarSpesialisasi as $sp)
                        <option value="{{ $sp }}" @selected($spesialisasiDipilih === $sp)>{{ $sp }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter kota --}}
            <div class="lg:w-48">
                <label for="filter-kota" class="block text-sm font-medium text-slate-700">Kota</label>
                <select id="filter-kota" name="kota"
                        class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500">
                    <option value="">Semua kota</option>
                    @foreach ($daftarKota as $kota)
                        <option value="{{ $kota }}" @selected($kotaDipilih === $kota)>{{ $kota }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="rounded-xl bg-tenang-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-tenang-700 transition">
                    Cari
                </button>
                @if ($q !== '' || $spesialisasiDipilih !== '' || $kotaDipilih !== '')
                    <a href="{{ route('psikolog.index') }}"
                       class="rounded-xl border border-tenang-200 px-4 py-2.5 text-sm font-medium text-tenang-700 hover:bg-tenang-50 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </section>

    {{-- ===== Hasil ===== --}}
    <section class="mt-8">
        <p class="text-sm text-slate-500" aria-live="polite">
            @if ($professionals->isEmpty())
                Tidak ada hasil.
            @else
                Menampilkan <strong class="text-slate-700">{{ $professionals->count() }}</strong> profesional
                @if ($q !== '') dengan nama "<strong class="text-slate-700">{{ $q }}</strong>" @endif
                @if ($spesialisasiDipilih !== '') · spesialisasi <strong class="text-slate-700">{{ $spesialisasiDipilih }}</strong> @endif
                @if ($kotaDipilih !== '') · kota <strong class="text-slate-700">{{ $kotaDipilih }}</strong> @endif
            @endif
        </p>

        @if ($professionals->isEmpty())
            {{-- State kosong --}}
            <div class="mt-4 rounded-2xl border border-dashed border-tenang-200 bg-white/60 p-10 text-center">
                <p class="text-3xl" aria-hidden="true">🌱</p>
                <h2 class="mt-2 font-bold text-slate-800">Belum ketemu yang cocok</h2>
                <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">
                    Coba kata kunci lain, atau longgarkan filter spesialisasi dan kotamu.
                </p>
                <a href="{{ route('psikolog.index') }}"
                   class="mt-4 inline-block rounded-xl bg-tenang-600 px-5 py-2.5 text-sm font-semibold text-white shadow hover:bg-tenang-700 transition">
                    Lihat semua psikolog
                </a>
            </div>
        @else
            {{-- Grid kartu yang tenang --}}
            <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($professionals as $p)
                    <a href="{{ route('psikolog.show', $p->id) }}"
                       class="group flex flex-col rounded-2xl border border-tenang-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        {{-- Foto / avatar inisial --}}
                        <div class="flex items-center gap-4">
                            @if ($p->photo)
                                <img src="{{ $p->photo }}" alt="Foto {{ $p->name }}"
                                     class="h-16 w-16 shrink-0 rounded-full border border-tenang-100 object-cover">
                            @else
                                <span class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-tenang-100 text-xl font-extrabold text-tenang-700" aria-hidden="true">
                                    {{ mb_strtoupper(mb_substr($p->name, 0, 1)) }}
                                </span>
                            @endif
                            <div class="min-w-0">
                                <h2 class="truncate font-bold text-slate-900 group-hover:text-tenang-700">{{ $p->name }}</h2>
                                <p class="truncate text-sm text-slate-500">{{ $p->title }}</p>
                                @if ($p->is_verified)
                                    <span class="mt-1 inline-flex items-center gap-1 rounded-full bg-tenang-100 px-2.5 py-0.5 text-xs font-semibold text-tenang-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75"/></svg>
                                        Terverifikasi
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Info ringkas --}}
                        <div class="mt-4 flex flex-wrap gap-2 text-xs">
                            <span class="rounded-full bg-tenang-50 border border-tenang-100 px-2.5 py-1 font-medium text-tenang-700">{{ $p->specialization }}</span>
                            <span class="rounded-full bg-slate-50 border border-slate-200 px-2.5 py-1 font-medium text-slate-600">{{ $p->city }}</span>
                        </div>

                        <div class="mt-4 flex items-center justify-between border-t border-tenang-50 pt-4">
                            <p class="text-sm">
                                <span class="font-extrabold text-slate-900">Rp {{ number_format($p->price_per_session, 0, ',', '.') }}</span>
                                <span class="text-slate-500">/sesi</span>
                            </p>
                            <span class="text-sm font-semibold text-tenang-600 group-hover:underline">Lihat profil →</span>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Paginasi --}}
            <div class="mt-8">
                {{ $professionals->links() }}
            </div>
        @endif
    </section>
</div>
@endsection
