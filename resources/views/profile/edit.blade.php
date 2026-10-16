@extends('layouts.app')

@section('title', 'Ubah Profil')

@section('content')
<div class="py-4">
    <h1 class="text-2xl font-extrabold text-slate-900">Ubah Profil ✏️</h1>
    <p class="mt-1 text-sm text-slate-600">Perbarui namamu atau emailmu di sini, santai saja.</p>

    <section class="mt-6 max-w-xl rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('profil.update') }}">
            @csrf
            @method('PATCH')

            {{-- ===== Nama ===== --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required maxlength="120"
                       placeholder="Namamu"
                       class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('name') border-red-400 @enderror">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- ===== Email ===== --}}
            <div class="mt-4">
                <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="190"
                       placeholder="nama@email.com"
                       class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('email') border-red-400 @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <button type="submit"
                        class="rounded-xl bg-tenang-600 px-6 py-2.5 font-semibold text-white shadow hover:bg-tenang-700 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('profil.show') }}"
                   class="rounded-xl border border-tenang-200 px-6 py-2.5 text-center font-medium hover:bg-tenang-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </section>
</div>
@endsection
