@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<div class="py-8">
    <div class="mx-auto max-w-md">
        <h1 class="text-2xl font-extrabold text-slate-900 text-center">Daftar Akun 🌱</h1>
        <p class="mt-1 text-sm text-slate-600 text-center">Buat akunmu untuk mulai menulis jurnal dan melacak mood. Gratis, datamu milikmu sendiri.</p>

        @if (session('galat'))
            <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert">
                {{ session('galat') }}
            </div>
        @endif

        <section class="mt-6 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('auth.register.store') }}">
                @csrf

                {{-- ===== Nama ===== --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="120"
                           placeholder="Namamu" autocomplete="name" autofocus
                           class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ===== Email ===== --}}
                <div class="mt-4">
                    <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="190"
                           placeholder="nama@email.com" autocomplete="email"
                           class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ===== Kata sandi ===== --}}
                <div class="mt-4">
                    <label for="password" class="block text-sm font-semibold text-slate-700">Kata Sandi</label>
                    <input id="password" type="password" name="password" required minlength="8"
                           placeholder="Minimal 8 karakter" autocomplete="new-password"
                           class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('password') border-red-400 @enderror">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ===== Konfirmasi kata sandi ===== --}}
                <div class="mt-4">
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Ulangi Kata Sandi</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8"
                           placeholder="Ketik ulang kata sandimu" autocomplete="new-password"
                           class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('password_confirmation') border-red-400 @enderror">
                    @error('password_confirmation')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="w-full rounded-xl bg-tenang-600 px-6 py-2.5 font-semibold text-white shadow hover:bg-tenang-700 transition">
                        Buat Akun
                    </button>
                </div>
            </form>
        </section>

        <p class="mt-4 text-center text-sm text-slate-600">
            Sudah punya akun?
            <a href="{{ route('masuk') }}" class="font-semibold text-tenang-700 hover:text-tenang-800 hover:underline">Masuk</a>
        </p>
    </div>
</div>
@endsection
