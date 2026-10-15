@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="py-8">
    <div class="mx-auto max-w-md">
        <h1 class="text-2xl font-extrabold text-slate-900 text-center">Masuk 🌱</h1>
        <p class="mt-1 text-sm text-slate-600 text-center">Selamat datang kembali. Pelan-pelan saja, tidak perlu terburu-buru.</p>

        @if (session('galat'))
            <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">
                {{ session('galat') }}
            </div>
        @endif

        @if (session('sukses'))
            <div class="mt-4 rounded-xl border border-tenang-200 bg-tenang-50 px-4 py-3 text-sm text-tenang-800" role="status">
                {{ session('sukses') }}
            </div>
        @endif

        <section class="mt-6 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('auth.login') }}">
                @csrf

                {{-- ===== Email ===== --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="190"
                           placeholder="nama@email.com" autocomplete="email" autofocus
                           class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ===== Kata sandi ===== --}}
                <div class="mt-4">
                    <label for="password" class="block text-sm font-semibold text-slate-700">Kata Sandi</label>
                    <input id="password" type="password" name="password" required
                           placeholder="••••••••" autocomplete="current-password"
                           class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('password') border-red-400 @enderror">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="w-full rounded-xl bg-tenang-600 px-6 py-2.5 font-semibold text-white shadow hover:bg-tenang-700 transition">
                        Masuk
                    </button>
                </div>
            </form>
        </section>

        <p class="mt-4 text-center text-sm text-slate-600">
            Belum punya akun?
            <a href="{{ route('auth.register') }}" class="font-semibold text-tenang-700 hover:text-tenang-800 hover:underline">Daftar</a>
        </p>
    </div>
</div>
@endsection
