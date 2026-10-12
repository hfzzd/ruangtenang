@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
{{-- ===== Hero ===== --}}
<section class="text-center py-10 sm:py-16">
    <p class="inline-flex items-center gap-2 rounded-full bg-tenang-100 px-4 py-1.5 text-xs font-semibold text-tenang-700">
        🌿 Self-hosted — datamu tetap milikmu
    </p>
    <h1 class="mt-4 text-3xl sm:text-5xl font-extrabold tracking-tight text-slate-900">
        Ruang aman untuk <span class="text-tenang-600">kesehatan mentalmu.</span>
    </h1>
    <p class="mt-4 max-w-2xl mx-auto text-slate-600 text-base sm:text-lg leading-relaxed">
        RuangTenang menemanimu menulis jurnal harian, memantau suasana hati, dan belajar
        tentang kesehatan mental — semuanya dalam Bahasa Indonesia, di mesinmu sendiri.
        Tanpa pelacakan, tanpa iklan.
    </p>
    <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ url('/masuk') }}"
           class="rounded-xl bg-tenang-600 px-6 py-3 font-semibold text-white shadow hover:bg-tenang-700 transition">
            Mulai Menulis Jurnal
        </a>
        <a href="#cara-memakai"
           class="rounded-xl border border-tenang-200 bg-white px-6 py-3 font-semibold hover:bg-tenang-50 transition">
            Cara Memakai
        </a>
    </div>
</section>

{{-- ===== Fitur ===== --}}
<section class="py-8">
    <h2 class="text-xl font-bold mb-4 text-slate-900">Fitur utama</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-tenang-100 bg-white p-5 shadow-sm">
            <div class="text-2xl mb-2">📝</div>
            <h3 class="font-semibold text-slate-900">Jurnal Terpandu</h3>
            <p class="mt-1 text-sm text-slate-600">
                Menulis dengan panduan pertanyaan reflektif, plus catatan mood sebelum
                dan sesudah menulis.
            </p>
        </div>
        <div class="rounded-2xl border border-tenang-100 bg-white p-5 shadow-sm">
            <div class="text-2xl mb-2">💚</div>
            <h3 class="font-semibold text-slate-900">Mood Tracking</h3>
            <p class="mt-1 text-sm text-slate-600">
                Check-in suasana hati harian dan lihat trennya selama 30 hari
                dalam grafik yang sederhana.
            </p>
        </div>
        <div class="rounded-2xl border border-tenang-100 bg-white p-5 shadow-sm">
            <div class="mb-2 flex items-center gap-2">
                <span class="text-2xl">🧭</span>
                <span class="badge-segera">Segera</span>
            </div>
            <h3 class="font-semibold text-slate-900">Direktori Psikolog</h3>
            <p class="mt-1 text-sm text-slate-600">
                Temukan psikolog dan layanan konseling profesional di sekitarmu
                saat kamu butuh bantuan lebih.
            </p>
        </div>
        <div class="rounded-2xl border border-tenang-100 bg-white p-5 shadow-sm">
            <div class="mb-2 flex items-center gap-2">
                <span class="text-2xl">📚</span>
                <span class="badge-segera">Segera</span>
            </div>
            <h3 class="font-semibold text-slate-900">Artikel Edukasi</h3>
            <p class="mt-1 text-sm text-slate-600">
                Bacaan ringan berbahasa Indonesia tentang mengelola stres,
                kecemasan, dan merawat diri.
            </p>
        </div>
    </div>
</section>

{{-- ===== Cara memakai (self-hosted) ===== --}}
<section id="cara-memakai" class="py-8">
    <h2 class="text-xl font-bold mb-2 text-slate-900">Cara memakai</h2>
    <p class="text-sm text-slate-600 mb-4">
        RuangTenang berjalan sepenuhnya di mesinmu sendiri — tidak ada server pusat,
        tidak ada data yang dikirim ke mana pun. Privasi jurnalmu benar-benar terjaga.
    </p>
    <ol class="space-y-3">
        <li class="rounded-2xl border border-tenang-100 bg-white p-4 shadow-sm flex gap-3">
            <span class="font-extrabold text-tenang-600">1.</span>
            <p class="text-sm text-slate-600">
                <strong class="text-slate-800">Clone repositorinya.</strong>
                <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs">git clone https://github.com/hfzzd/ruangtenang.git</code>
            </p>
        </li>
        <li class="rounded-2xl border border-tenang-100 bg-white p-4 shadow-sm flex gap-3">
            <span class="font-extrabold text-tenang-600">2.</span>
            <p class="text-sm text-slate-600">
                <strong class="text-slate-800">Jalankan dengan Docker.</strong>
                Cukup <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs">docker compose up -d</code> — database dan aplikasinya menyala otomatis.
            </p>
        </li>
        <li class="rounded-2xl border border-tenang-100 bg-white p-4 shadow-sm flex gap-3">
            <span class="font-extrabold text-tenang-600">3.</span>
            <p class="text-sm text-slate-600">
                <strong class="text-slate-800">Buka di browser.</strong>
                Kunjungi <code class="rounded bg-slate-100 px-1.5 py-0.5 text-xs">http://localhost:8000</code>,
                daftar akun, dan mulai menulis. Semua data tersimpan lokal di mesinmu.
            </p>
        </li>
    </ol>
</section>

{{-- ===== Disclaimer ===== --}}
<section class="py-8">
    <div class="rounded-2xl border border-tenang-200 bg-tenang-50 p-5">
        <h2 class="font-bold text-tenang-800">Catatan penting</h2>
        <p class="mt-2 text-sm text-slate-600 leading-relaxed">
            RuangTenang adalah alat bantu refleksi diri, <strong>bukan pengganti bantuan profesional</strong>.
            Jika kamu sedang dalam krisis atau merasa tidak aman, segera hubungi profesional
            kesehatan mental atau layanan darurat terdekat. Kamu tidak harus menghadapi ini sendirian. 💚
        </p>
    </div>
</section>
@endsection
