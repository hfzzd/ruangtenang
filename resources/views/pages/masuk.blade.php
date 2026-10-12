@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="mx-auto max-w-md py-8" x-data="formMasuk()" x-cloak>
    <div class="text-center mb-6">
        <h1 class="text-2xl font-extrabold text-slate-900">Selamat datang kembali 🌿</h1>
        <p class="mt-1 text-sm text-slate-600">Masuk atau buat akun untuk mulai menulis jurnalmu.</p>
    </div>

    {{-- Tab login / register --}}
    <div class="mb-5 grid grid-cols-2 rounded-xl bg-tenang-100 p-1 text-sm font-semibold">
        <button type="button" @click="tab = 'masuk'"
                :class="tab === 'masuk' ? 'bg-white shadow text-tenang-700' : 'text-slate-500'"
                class="rounded-lg py-2 transition">
            Masuk
        </button>
        <button type="button" @click="tab = 'daftar'"
                :class="tab === 'daftar' ? 'bg-white shadow text-tenang-700' : 'text-slate-500'"
                class="rounded-lg py-2 transition">
            Daftar
        </button>
    </div>

    <div class="rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm">
        {{-- Pesan error --}}
        <div x-show="error" class="mb-4 rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800" role="alert">
            <p class="font-semibold">Ups, ada yang perlu diperbaiki.</p>
            <p class="mt-1" x-text="error"></p>
        </div>

        {{-- ===== Form Masuk ===== --}}
        <form x-show="tab === 'masuk'" @submit.prevent="kirimMasuk()" class="space-y-4">
            <div>
                <label for="masuk-email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="masuk-email" type="email" x-model="email" required autocomplete="email"
                       class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500"
                       placeholder="nama@email.com">
            </div>
            <div>
                <label for="masuk-password" class="block text-sm font-medium text-slate-700">Kata sandi</label>
                <input id="masuk-password" type="password" x-model="password" required autocomplete="current-password"
                       class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500"
                       placeholder="••••••••">
            </div>
            <button type="submit" :disabled="memuat"
                    class="w-full rounded-xl bg-tenang-600 px-4 py-2.5 font-semibold text-white shadow hover:bg-tenang-700 transition disabled:opacity-60">
                <span x-show="!memuat">Masuk</span>
                <span x-show="memuat">Sebentar ya…</span>
            </button>
        </form>

        {{-- ===== Form Daftar ===== --}}
        <form x-show="tab === 'daftar'" @submit.prevent="kirimDaftar()" class="space-y-4">
            <div>
                <label for="daftar-nama" class="block text-sm font-medium text-slate-700">Nama panggilan</label>
                <input id="daftar-nama" type="text" x-model="nama" required autocomplete="name"
                       class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500"
                       placeholder="Nama yang ingin disapa">
            </div>
            <div>
                <label for="daftar-email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="daftar-email" type="email" x-model="email" required autocomplete="email"
                       class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500"
                       placeholder="nama@email.com">
            </div>
            <div>
                <label for="daftar-password" class="block text-sm font-medium text-slate-700">Kata sandi</label>
                <input id="daftar-password" type="password" x-model="password" required minlength="8" autocomplete="new-password"
                       class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500"
                       placeholder="Minimal 8 karakter">
            </div>
            <div>
                <label for="daftar-password2" class="block text-sm font-medium text-slate-700">Konfirmasi kata sandi</label>
                <input id="daftar-password2" type="password" x-model="passwordKonfirmasi" required autocomplete="new-password"
                       class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500"
                       placeholder="Ulangi kata sandi">
            </div>
            <button type="submit" :disabled="memuat"
                    class="w-full rounded-xl bg-tenang-600 px-4 py-2.5 font-semibold text-white shadow hover:bg-tenang-700 transition disabled:opacity-60">
                <span x-show="!memuat">Buat Akun</span>
                <span x-show="memuat">Sebentar ya…</span>
            </button>
        </form>
    </div>

    <p class="mt-4 text-center text-xs text-slate-500">
        Datamu tersimpan di mesinmu sendiri — RuangTenang tidak mengirimnya ke mana pun.
    </p>
</div>
@endsection

@push('scripts')
<script>
    function formMasuk() {
        return {
            tab: 'masuk',
            nama: '',
            email: '',
            password: '',
            passwordKonfirmasi: '',
            error: '',
            memuat: false,

            init: function () {
                // Sudah login? Langsung ke jurnal saja.
                if (window.RT.token()) window.location.href = '{{ url('/jurnal') }}';
            },

            async kirimMasuk: function () {
                this.error = '';
                this.memuat = true;
                try {
                    var res = await fetch('/api/auth/login', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ email: this.email, password: this.password }),
                    });
                    if (!res.ok) {
                        if (res.status === 422) {
                            this.error = await window.RT.pesanError(res, 'Email atau kata sandi belum valid.');
                        } else if (res.status === 401) {
                            this.error = 'Email atau kata sandi salah. Coba ingat-ingat lagi ya.';
                        } else {
                            this.error = await window.RT.pesanError(res);
                        }
                        return;
                    }
                    var body = await res.json();
                    this.simpanDanMasuk(body);
                } catch (e) {
                    this.error = 'Koneksi ke server bermasalah. Cek koneksi lalu coba lagi ya.';
                } finally {
                    this.memuat = false;
                }
            },

            async kirimDaftar: function () {
                this.error = '';
                if (this.password !== this.passwordKonfirmasi) {
                    this.error = 'Konfirmasi kata sandi tidak sama. Ketik ulang dengan pelan-pelan ya.';
                    return;
                }
                this.memuat = true;
                try {
                    var res = await fetch('/api/auth/register', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({
                            name: this.nama,
                            email: this.email,
                            password: this.password,
                            password_confirmation: this.passwordKonfirmasi,
                        }),
                    });
                    if (!res.ok) {
                        this.error = await window.RT.pesanError(res, 'Pendaftaran belum berhasil. Periksa lagi isianmu ya.');
                        return;
                    }
                    var body = await res.json();
                    this.simpanDanMasuk(body);
                } catch (e) {
                    this.error = 'Koneksi ke server bermasalah. Cek koneksi lalu coba lagi ya.';
                } finally {
                    this.memuat = false;
                }
            },

            simpanDanMasuk: function (body) {
                // Dukung beberapa bentuk respons: {token, user:{name}} atau {data:{...}}
                var data = body.data || body;
                var token = data.token || data.access_token;
                var nama = (data.user && data.user.name) || data.name || this.nama || 'kamu';
                if (!token) {
                    this.error = 'Server tidak mengembalikan token. Coba lagi ya.';
                    return;
                }
                window.RT.setAuth(token, nama);
                window.location.href = '{{ url('/jurnal') }}';
            },
        };
    }
</script>
@endpush
