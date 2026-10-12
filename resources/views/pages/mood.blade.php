@extends('layouts.app')

@section('title', 'Mood')

@section('content')
<div class="py-4" x-data="moodApp()" x-cloak x-init="init()">
    <h1 class="text-2xl font-extrabold text-slate-900">Check-in Mood 💚</h1>
    <p class="mt-1 text-sm text-slate-600">Bagaimana perasaanmu hari ini? Tidak ada jawaban yang salah.</p>

    {{-- Pesan error --}}
    <div x-show="error" class="mt-4 rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800" role="alert">
        <p x-text="error"></p>
    </div>

    {{-- ===== Check-in hari ini ===== --}}
    <section class="mt-6 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm">
        <h2 class="font-bold text-slate-900">Mood hari ini</h2>

        <div x-show="sudahCheckin && !ubahMode" class="konten-masuk mt-4 rounded-xl bg-tenang-50 border border-tenang-100 px-4 py-4 text-sm text-slate-700">
            <p>Kamu sudah check-in hari ini: <strong x-text="emojiPilihan(sudahCheckin) + ' ' + labelPilihan(sudahCheckin)"></strong></p>
            <p x-show="catatanTersimpan" class="mt-1 italic text-slate-500" x-text="'“' + catatanTersimpan + '”'"></p>
            <button type="button" @click="ubahMode = true"
                    class="mt-3 rounded-lg border border-tenang-200 px-3 py-1.5 text-xs font-medium hover:bg-white transition">
                Perbarui check-in
            </button>
        </div>

        <div x-show="!sudahCheckin || ubahMode" class="mt-4">
            <div class="grid grid-cols-5 gap-2 sm:gap-3" role="radiogroup" aria-label="Pilih mood 1 sampai 5">
                <template x-for="n in [1,2,3,4,5]" :key="n">
                    <button type="button" role="radio" :aria-checked="pilihan === n"
                            @click="pilihan = n"
                            :class="pilihan === n
                                ? 'border-tenang-500 bg-tenang-50 ring-2 ring-tenang-300'
                                : 'border-slate-200 hover:border-tenang-300 hover:bg-tenang-50/50'"
                            class="rounded-2xl border-2 px-1 py-3 transition text-center">
                        <span class="block text-2xl sm:text-3xl" x-text="emojiPilihan(n)"></span>
                        <span class="mt-1 block text-[11px] sm:text-xs font-medium text-slate-600" x-text="labelPilihan(n)"></span>
                    </button>
                </template>
            </div>
            <div class="mt-4">
                <label for="mood-catatan" class="block text-sm font-medium text-slate-700">Catatan <span class="text-slate-400">(opsional)</span></label>
                <input id="mood-catatan" type="text" x-model="catatan"
                       class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500"
                       placeholder="Ada yang ingin kamu ceritakan tentang harimu?">
            </div>
            <button type="button" @click="kirimCheckin()" :disabled="menyimpan || !pilihan"
                    class="mt-4 w-full sm:w-auto rounded-xl bg-tenang-600 px-6 py-2.5 font-semibold text-white shadow hover:bg-tenang-700 transition disabled:opacity-60">
                <span x-show="!menyimpan">Simpan Check-in</span>
                <span x-show="menyimpan">Menyimpan…</span>
            </button>
        </div>
    </section>

    {{-- ===== Tren 30 hari ===== --}}
    <section class="mt-8">
        <div class="flex items-baseline justify-between">
            <h2 class="font-bold text-slate-900">Tren 30 hari terakhir</h2>
            <p x-show="rataRata !== null" class="text-sm text-slate-600">
                Rata-rata: <strong class="text-tenang-700" x-text="rataRata"></strong> / 5
            </p>
        </div>

        <div x-show="memuatTren" class="py-10 text-center text-sm text-slate-500">Memuat tren mood…</div>

        <div x-show="!memuatTren && tren.length === 0" class="mt-4 rounded-2xl border border-dashed border-tenang-200 bg-white/60 p-8 text-center text-sm text-slate-500">
            Belum ada data mood. Check-in setiap hari dan polanya akan terlihat di sini. 🌱
        </div>

        <div x-show="!memuatTren && tren.length > 0" class="mt-4 rounded-2xl border border-tenang-100 bg-white p-5 shadow-sm">
            {{-- Bar chart CSS murni --}}
            <div class="flex h-40 items-end gap-1 sm:gap-1.5" aria-hidden="true">
                <template x-for="(t, i) in tren" :key="i">
                    <div class="flex-1 flex flex-col justify-end items-center h-full group relative">
                        <div class="bar-mood w-full max-w-8 rounded-t-md"
                             :class="warnaBar(nilaiTren(t))"
                             :style="'height: ' + (nilaiTren(t) / 5 * 100) + '%'"
                             :title="labelTanggal(t) + ': ' + nilaiTren(t) + '/5'"></div>
                    </div>
                </template>
            </div>
            <div class="mt-2 flex justify-between text-[11px] text-slate-400">
                <span x-text="labelTanggal(tren[0])"></span>
                <span x-text="labelTanggal(tren[tren.length - 1])"></span>
            </div>
            <div class="mt-3 flex flex-wrap gap-3 text-xs text-slate-500">
                <span><span class="inline-block h-2.5 w-2.5 rounded-sm bg-tenang-500"></span> 4–5: Baik</span>
                <span><span class="inline-block h-2.5 w-2.5 rounded-sm bg-tenang-300"></span> 3: Biasa</span>
                <span><span class="inline-block h-2.5 w-2.5 rounded-sm bg-slate-300"></span> 1–2: Kurang baik</span>
            </div>
            <p class="mt-4 text-xs text-slate-500 leading-relaxed">
                Ingat: grafik ini hanya cerminan kasar. Perasaanmu valid apa pun angkanya. 💚
            </p>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    function moodApp() {
        return {
            pilihan: null,
            catatan: '',
            menyimpan: false,
            error: '',
            sudahCheckin: null,
            catatanTersimpan: '',
            ubahMode: false,
            tren: [],
            rataRata: null,
            memuatTren: true,

            LABELS: { 1: 'Sangat Buruk', 2: 'Buruk', 3: 'Biasa Saja', 4: 'Baik', 5: 'Sangat Baik' },
            EMOJI: { 1: '😞', 2: '😟', 3: '😐', 4: '🙂', 5: '😄' },

            labelPilihan: function (n) { return this.LABELS[n] || ''; },
            emojiPilihan: function (n) { return this.EMOJI[n] || '❔'; },

            nilaiTren: function (t) {
                var v = (t && (t.mood !== undefined ? t.mood : t.value)) || 0;
                return Math.max(0, Math.min(5, Number(v)));
            },
            labelTanggal: function (t) {
                if (!t) return '';
                var iso = t.date || t.tanggal || t.created_at;
                if (!iso) return '';
                try {
                    return new Date(iso).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                } catch (e) { return String(iso); }
            },
            warnaBar: function (v) {
                if (v >= 4) return 'bg-tenang-500';
                if (v >= 3) return 'bg-tenang-300';
                return 'bg-slate-300';
            },

            init: async function () {
                if (!window.RT.requireAuth()) return;
                await Promise.all([this.cekCheckinHariIni(), this.muatTren()]);
                this.memuatTren = false;
            },

            /** Cek apakah hari ini sudah check-in (pakai data tren terbaru bila tersedia). */
            cekCheckinHariIni: async function () {
                try {
                    var res = await window.RT.authFetch('/mood');
                    if (res.status === 401) { window.RT.clearAuth(); window.location.href = '{{ url('/masuk') }}'; return; }
                    if (!res.ok) return;
                    var body = await res.json();
                    var daftar = body.data || body.moods || body || [];
                    if (!Array.isArray(daftar) || !daftar.length) return;
                    var hariIni = new Date().toISOString().slice(0, 10);
                    var cocok = daftar.find(function (m) {
                        var iso = m.date || m.tanggal || m.created_at || '';
                        return String(iso).slice(0, 10) === hariIni;
                    });
                    if (cocok) {
                        this.sudahCheckin = this.nilaiTren(cocok);
                        this.catatanTersimpan = cocok.note || cocok.catatan || '';
                        this.pilihan = this.sudahCheckin;
                        this.catatan = this.catatanTersimpan;
                    }
                } catch (e) { /* abaikan — check-in tetap bisa dilakukan */ }
            },

            kirimCheckin: async function () {
                this.error = '';
                if (!this.pilihan) {
                    this.error = 'Pilih dulu mood-mu hari ini ya — ketuk salah satu wajah di atas.';
                    return;
                }
                this.menyimpan = true;
                try {
                    var res = await window.RT.authFetch('/mood', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ mood: this.pilihan, note: this.catatan || null }),
                    });
                    if (!res.ok) {
                        this.error = await window.RT.pesanError(res, 'Check-in belum tersimpan. Coba lagi ya.');
                        return;
                    }
                    this.sudahCheckin = this.pilihan;
                    this.catatanTersimpan = this.catatan;
                    this.ubahMode = false;
                    // Segarkan tren agar bar hari ini ikut muncul
                    this.memuatTren = true;
                    await this.muatTren();
                    this.memuatTren = false;
                } catch (e) {
                    this.error = 'Koneksi ke server bermasalah. Coba lagi sebentar lagi ya.';
                } finally {
                    this.menyimpan = false;
                }
            },

            muatTren: async function () {
                try {
                    var res = await window.RT.authFetch('/mood/trend?days=30');
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    var body = await res.json();
                    var daftar = body.data || body.trend || body || [];
                    this.tren = Array.isArray(daftar) ? daftar : [];
                    if (this.tren.length) {
                        var jumlah = this.tren.reduce(function (acc, t) {
                            var v = (t.mood !== undefined ? t.mood : t.value) || 0;
                            return acc + Number(v);
                        }, 0);
                        this.rataRata = (jumlah / this.tren.length).toFixed(1);
                    } else {
                        this.rataRata = null;
                    }
                } catch (e) {
                    this.tren = [];
                    this.rataRata = null;
                }
            },
        };
    }
</script>
@endpush
