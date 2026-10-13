@extends('layouts.app')

@section('title', 'Buat Booking')

@section('content')
<div class="py-4" x-data="bookingForm()" x-init="perbaruiHarga()">
    <h1 class="text-2xl font-extrabold text-slate-900">Booking Sesi Konsultasi 🌱</h1>
    <p class="mt-1 text-sm text-slate-600">Pilih psikolog dan jadwal yang nyaman buatmu. Ambil napas dulu, tidak perlu terburu-buru.</p>

    <section class="mt-6 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('booking.store') }}">
            @csrf

            {{-- ===== Psikolog ===== --}}
            <div>
                <label for="professional_id" class="block text-sm font-semibold text-slate-700">Pilih Psikolog</label>
                <select id="professional_id" name="professional_id" x-model="psikologId" @change="perbaruiHarga()" required
                        class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('professional_id') border-red-400 @enderror">
                    <option value="">— Pilih psikolog —</option>
                    @foreach ($professionals as $pro)
                        <option value="{{ $pro->id }}"
                                data-harga="{{ $pro->price_per_session }}"
                                @selected(old('professional_id', $selectedId) == $pro->id)>
                            {{ $pro->name }} — {{ $pro->title }}
                        </option>
                    @endforeach
                </select>
                @error('professional_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
                @if ($professionals->isEmpty())
                    <p class="mt-1 text-xs text-amber-700">Belum ada psikolog terdaftar saat ini. Coba lagi nanti ya.</p>
                @endif
            </div>

            {{-- ===== Harga sesi otomatis ===== --}}
            <div x-show="harga" x-cloak class="mt-3 flex items-center justify-between rounded-xl bg-tenang-50 border border-tenang-100 px-4 py-3">
                <span class="text-sm text-slate-600">Biaya sesi</span>
                <span class="text-lg font-extrabold text-tenang-700" x-text="formatRupiah(harga)"></span>
            </div>

            {{-- ===== Tanggal & durasi ===== --}}
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="scheduled_at" class="block text-sm font-semibold text-slate-700">Tanggal &amp; Jam</label>
                    <input id="scheduled_at" type="datetime-local" name="scheduled_at"
                           value="{{ old('scheduled_at') }}"
                           min="{{ now()->addDay()->format('Y-m-d\TH:i') }}" required
                           class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('scheduled_at') border-red-400 @enderror">
                    <p class="mt-1 text-xs text-slate-500">Minimal besok — supaya psikolog punya waktu menyiapkan diri.</p>
                    @error('scheduled_at')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="duration_minutes" class="block text-sm font-semibold text-slate-700">Durasi Sesi</label>
                    <select id="duration_minutes" name="duration_minutes" required
                            class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('duration_minutes') border-red-400 @enderror">
                        @foreach ([30 => '30 menit', 45 => '45 menit', 60 => '60 menit (disarankan)', 90 => '90 menit'] as $menit => $label)
                            <option value="{{ $menit }}" @selected(old('duration_minutes', 60) == $menit)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('duration_minutes')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ===== Data pemesan ===== --}}
            <div class="mt-6 border-t border-tenang-100 pt-5">
                <h2 class="font-bold text-slate-900">Data Dirimu</h2>
                <p class="mt-1 text-xs text-slate-500">Kami memakai email untuk menampilkan daftar booking-mu nanti. Tenang, datamu tersimpan di server sendiri.</p>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="120"
                               placeholder="Namamu"
                               class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('name') border-red-400 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required maxlength="190"
                               placeholder="nama@email.com"
                               class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('email') border-red-400 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-700">Nomor Telepon <span class="font-normal text-slate-400">(opsional)</span></label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" maxlength="30"
                               placeholder="08xxxxxxxxxx"
                               class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('phone') border-red-400 @enderror">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-slate-700">Catatan <span class="font-normal text-slate-400">(opsional)</span></label>
                        <textarea id="notes" name="notes" rows="3" maxlength="500"
                                  placeholder="Hal yang ingin kamu ceritakan sebelum sesi…"
                                  class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500 @error('notes') border-red-400 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <button type="submit"
                        class="rounded-xl bg-tenang-600 px-6 py-2.5 font-semibold text-white shadow hover:bg-tenang-700 transition">
                    Buat Booking
                </button>
                <a href="{{ route('booking.index') }}" class="rounded-xl border border-tenang-200 px-6 py-2.5 text-center font-medium hover:bg-tenang-50 transition">
                    Lihat Booking Saya
                </a>
            </div>
        </form>
    </section>
</div>
@endsection

@push('scripts')
<script>
    function bookingForm() {
        return {
            psikologId: '{{ old('professional_id', $selectedId) }}',
            harga: null,

            perbaruiHarga: function () {
                var select = document.getElementById('professional_id');
                var opt = select ? select.options[select.selectedIndex] : null;
                this.harga = opt && opt.dataset.harga ? Number(opt.dataset.harga) : null;
            },
            formatRupiah: function (nilai) {
                if (nilai === null || nilai === undefined) return '';
                return 'Rp ' + Number(nilai).toLocaleString('id-ID');
            },
        };
    }
</script>
@endpush
