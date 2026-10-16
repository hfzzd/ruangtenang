<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="RuangTenang — platform kesehatan mental berbahasa Indonesia yang self-hosted. Jurnal terpandu, pelacak mood, dan edukasi. Datamu tersimpan di mesinmu sendiri.">
    <title>@yield('title', 'RuangTenang') — Ruang Aman untuk Kesehatan Mentalmu</title>

    {{-- Tailwind CSS via CDN (tanpa build step) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        tenang: {
                            50: '#f3f8f5', 100: '#e1efe8', 200: '#c4ded3',
                            300: '#9dc6b7', 400: '#74a995', 500: '#548d79',
                            600: '#427063', 700: '#375b52', 800: '#304a45',
                            900: '#293d39', 950: '#152825',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'Segoe UI', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    {{-- Alpine.js via CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.10/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-ruang-tenang text-slate-800 antialiased font-sans min-h-screen flex flex-col">

    {{-- ===== Navbar ===== --}}
    <header class="sticky top-0 z-40 border-b border-tenang-100 bg-white/80 backdrop-blur">
        <nav class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3">
            <a href="{{ url('/') }}" class="flex items-center gap-2 font-extrabold text-xl text-tenang-700">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-tenang-600 text-white shadow">
                    {{-- ikon daun/ruang yang menenangkan --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C7 7 4 10 4 14a8 8 0 0016 0c0-4-3-7-8-12zm0 18.5A6.5 6.5 0 015.5 14c0-3.2 2.5-5.7 6.5-10.2 4 4.5 6.5 7 6.5 10.2a6.5 6.5 0 01-6.5 6.5z"/><path d="M12 8c-2.5 2.5-4 4.2-4 6a4 4 0 008 0c0-1.8-1.5-3.5-4-6z"/></svg>
                </span>
                RuangTenang
            </a>
            <div class="hidden sm:flex items-center gap-1 text-sm font-medium">
                <a href="{{ url('/') }}" class="rounded-lg px-3 py-2 hover:bg-tenang-50">Beranda</a>
                <a href="{{ url('/artikel') }}" class="rounded-lg px-3 py-2 hover:bg-tenang-50">Artikel</a>
                <a href="{{ url('/jurnal') }}" class="rounded-lg px-3 py-2 hover:bg-tenang-50">Jurnal</a>
                <a href="{{ url('/mood') }}" class="rounded-lg px-3 py-2 hover:bg-tenang-50">Mood</a>
                <a href="{{ url('/psikolog') }}" class="rounded-lg px-3 py-2 hover:bg-tenang-50">Psikolog</a>
                <a href="{{ url('/booking') }}" class="rounded-lg px-3 py-2 hover:bg-tenang-50">Booking</a>
                <a href="{{ url('/forum') }}" class="rounded-lg px-3 py-2 hover:bg-tenang-50">Forum</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-lg px-3 py-2 hover:bg-tenang-50">Dashboard</a>
                    <a href="{{ route('profil.show') }}" class="rounded-lg px-3 py-2 hover:bg-tenang-50">Profil</a>
                    <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="rounded-lg px-3 py-2 hover:bg-tenang-50">Keluar</button>
                    </form>
                @endauth
                @guest
                    <a href="{{ route('masuk') }}" class="rounded-lg bg-tenang-600 px-4 py-2 font-semibold text-white shadow hover:bg-tenang-700 transition">Masuk</a>
                @endguest
            </div>
            <div class="flex items-center gap-2">
                {{-- Status login ditangani Alpine: tampilkan tombol Masuk atau menu pengguna --}}
                <div x-data="navAuth()" x-cloak class="flex items-center gap-2">
                    <template x-if="!token">
                        <a href="{{ url('/masuk') }}"
                           class="rounded-xl bg-tenang-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-tenang-700 transition">
                            Masuk
                        </a>
                    </template>
                    <template x-if="token">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-600">Halo, <span class="font-semibold text-tenang-700" x-text="nama"></span></span>
                            <button type="button" @click="keluar()"
                                    class="rounded-xl border border-tenang-200 px-3 py-2 text-sm font-medium hover:bg-tenang-50 transition">
                                Keluar
                            </button>
                        </div>
                    </template>
                </div>
                {{-- Tombol menu mobile --}}
                <button type="button" data-toggle-menu aria-label="Buka menu navigasi" aria-expanded="false"
                        class="sm:hidden rounded-lg p-2 hover:bg-tenang-50" title="Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </nav>
        {{-- Menu mobile --}}
        <div data-menu-mobile class="hidden sm:hidden border-t border-tenang-100 px-4 py-2 text-sm font-medium">
            <a href="{{ url('/') }}" class="block rounded-lg px-3 py-2 hover:bg-tenang-50">Beranda</a>
            <a href="{{ url('/artikel') }}" class="block rounded-lg px-3 py-2 hover:bg-tenang-50">Artikel</a>
            <a href="{{ url('/jurnal') }}" class="block rounded-lg px-3 py-2 hover:bg-tenang-50">Jurnal</a>
            <a href="{{ url('/mood') }}" class="block rounded-lg px-3 py-2 hover:bg-tenang-50">Mood</a>
            <a href="{{ url('/psikolog') }}" class="block rounded-lg px-3 py-2 hover:bg-tenang-50">Psikolog</a>
            <a href="{{ url('/booking') }}" class="block rounded-lg px-3 py-2 hover:bg-tenang-50">Booking</a>
            <a href="{{ url('/forum') }}" class="block rounded-lg px-3 py-2 hover:bg-tenang-50">Forum</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 hover:bg-tenang-50">Dashboard</a>
                <a href="{{ route('profil.show') }}" class="block rounded-lg px-3 py-2 hover:bg-tenang-50">Profil</a>
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left rounded-lg px-3 py-2 hover:bg-tenang-50">Keluar</button>
                </form>
            @endauth
            @guest
                <a href="{{ route('masuk') }}" class="mt-1 block rounded-lg bg-tenang-600 px-4 py-2 text-center font-semibold text-white shadow hover:bg-tenang-700 transition">Masuk</a>
            @endguest
        </div>
    </header>

    {{-- ===== Konten ===== --}}
    <main class="mx-auto max-w-5xl w-full px-4 py-8 flex-1">
        @yield('content')
    </main>

    {{-- ===== Footer ===== --}}
    <footer class="border-t border-tenang-100 bg-white/70">
        <div class="mx-auto max-w-5xl px-4 py-6 text-sm text-slate-500">
            <p class="rounded-xl bg-tenang-50 border border-tenang-100 px-4 py-3 leading-relaxed">
                <span class="font-semibold text-tenang-700">Penting:</span>
                RuangTenang bukan pengganti bantuan profesional. Jika kamu dalam krisis,
                segera hubungi profesional kesehatan mental.
            </p>
            <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p>&copy; {{ date('Y') }} <span class="font-semibold text-slate-700">RuangTenang</span> — open-source, self-hosted, untuk Indonesia.</p>
                <p>Datamu tersimpan di mesinmu sendiri.</p>
            </div>
        </div>
    </footer>

    {{-- ===== Helper autentikasi global (dipakai semua halaman) ===== --}}
    <script>
        window.RT = (function () {
            'use strict';
            var KUNCI_TOKEN = 'ruangtenang-token';
            var KUNCI_NAMA = 'ruangtenang-nama';

            function simpan(key) {
                try { return localStorage.getItem(key); } catch (e) { return null; }
            }

            return {
                token: function () { return simpan(KUNCI_TOKEN); },
                nama: function () { return simpan(KUNCI_NAMA); },
                setAuth: function (token, nama) {
                    try {
                        localStorage.setItem(KUNCI_TOKEN, token);
                        if (nama) localStorage.setItem(KUNCI_NAMA, nama);
                    } catch (e) { /* abaikan */ }
                },
                clearAuth: function () {
                    try {
                        localStorage.removeItem(KUNCI_TOKEN);
                        localStorage.removeItem(KUNCI_NAMA);
                    } catch (e) { /* abaikan */ }
                },
                /** Wajibkan login; lempar ke /masuk bila belum ada token. */
                requireAuth: function () {
                    if (!this.token()) {
                        window.location.href = '{{ url('/masuk') }}';
                        return false;
                    }
                    return true;
                },
                /** fetch dengan header Authorization Bearer otomatis. */
                authFetch: function (path, options) {
                    options = options || {};
                    var headers = Object.assign(
                        { 'Accept': 'application/json' },
                        options.headers || {}
                    );
                    var token = this.token();
                    if (token) headers['Authorization'] = 'Bearer ' + token;
                    options.headers = headers;
                    return fetch('/api' + path, options);
                },
                /** Ambil pesan error validasi (422) dari respons Laravel. */
                pesanError: async function (res, fallback) {
                    if (res.status === 422) {
                        try {
                            var body = await res.json();
                            if (body.errors) {
                                var pertama = Object.keys(body.errors)[0];
                                var arr = body.errors[pertama];
                                if (arr && arr.length) return arr[0];
                            }
                            if (body.message) return body.message;
                        } catch (e) { /* abaikan */ }
                        return fallback || 'Data yang dikirim belum valid. Periksa lagi isianmu ya.';
                    }
                    return fallback || 'Terjadi kesalahan. Coba lagi sebentar lagi ya.';
                },
            };
        })();

        /** Komponen Alpine navbar: status login + tombol keluar. */
        function navAuth() {
            return {
                token: null,
                nama: '',
                init: function () {
                    this.token = window.RT.token();
                    this.nama = window.RT.nama() || 'kamu';
                },
                keluar: async function () {
                    try { await window.RT.authFetch('/auth/logout', { method: 'POST' }); }
                    catch (e) { /* abaikan */ }
                    window.RT.clearAuth();
                    window.location.href = '{{ url('/') }}';
                },
            };
        }
    </script>

    @stack('scripts')
</body>
</html>
