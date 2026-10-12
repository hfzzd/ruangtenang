@extends('layouts.app')

@section('title', 'Jurnal')

@section('content')
<div class="py-4" x-data="jurnalApp()" x-cloak x-init="init()">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Jurnal Harian 📝</h1>
            <p class="mt-1 text-sm text-slate-600">Tulis apa pun yang ada di pikiranmu. Tidak ada yang menilai di sini.</p>
        </div>
        <button type="button" @click="formTerbuka = !formTerbuka"
                class="rounded-xl bg-tenang-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-tenang-700 transition">
            <span x-show="!formTerbuka">✍️ Tulis Entri Baru</span>
            <span x-show="formTerbuka">Tutup Form</span>
        </button>
    </div>

    {{-- Pesan error global --}}
    <div x-show="error" class="mb-4 rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800" role="alert">
        <p x-text="error"></p>
    </div>

    {{-- ===== Form entri baru ===== --}}
    <div x-show="formTerbuka" class="konten-masuk mb-8 rounded-2xl border border-tenang-100 bg-white p-6 shadow-sm">
        <h2 class="font-bold text-slate-900 mb-4">Entri baru</h2>
        <div class="space-y-4">
            <div>
                <label for="jurnal-judul" class="block text-sm font-medium text-slate-700">Judul <span class="text-slate-400">(opsional)</span></label>
                <input id="jurnal-judul" type="text" x-model="form.judul"
                       class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500"
                       placeholder="Beri judul harimu…">
            </div>
            <div>
                <label for="jurnal-prompt" class="block text-sm font-medium text-slate-700">Butuh inspirasi? Pilih pertanyaan panduan</label>
                <select id="jurnal-prompt" x-model="form.promptId"
                        class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500">
                    <option value="">— Tulis bebas, tanpa panduan —</option>
                    <template x-for="p in prompts" :key="idPrompt(p)">
                        <option :value="idPrompt(p)" x-text="teksPrompt(p)"></option>
                    </template>
                </select>
            </div>
            <div>
                <label for="jurnal-konten" class="block text-sm font-medium text-slate-700">Ceritamu</label>
                <textarea id="jurnal-konten" x-model="form.konten" rows="6" required
                          class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500"
                          placeholder="Hari ini aku merasa…"></textarea>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="jurnal-mood-sebelum" class="block text-sm font-medium text-slate-700">Mood sebelum menulis (1–5)</label>
                    <select id="jurnal-mood-sebelum" x-model="form.moodSebelum"
                            class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500">
                        <option value="">— pilih —</option>
                        <template x-for="n in [1,2,3,4,5]" :key="'sb'+n">
                            <option :value="n" x-text="n + ' — ' + labelMood(n)"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label for="jurnal-mood-sesudah" class="block text-sm font-medium text-slate-700">Mood sesudah menulis (1–5)</label>
                    <select id="jurnal-mood-sesudah" x-model="form.moodSesudah"
                            class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-tenang-500 focus:ring-tenang-500">
                        <option value="">— pilih —</option>
                        <template x-for="n in [1,2,3,4,5]" :key="'ss'+n">
                            <option :value="n" x-text="n + ' — ' + labelMood(n)"></option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" @click="resetForm()"
                        class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50 transition">
                    Bersihkan
                </button>
                <button type="button" @click="simpan()" :disabled="menyimpan"
                        class="rounded-xl bg-tenang-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-tenang-700 transition disabled:opacity-60">
                    <span x-show="!menyimpan">Simpan Entri</span>
                    <span x-show="menyimpan">Menyimpan…</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== Daftar entri ===== --}}
    <div x-show="memuat" class="py-12 text-center text-sm text-slate-500">
        Memuat jurnalmu…
    </div>

    <div x-show="!memuat && entri.length === 0" class="rounded-2xl border border-dashed border-tenang-200 bg-white/60 p-10 text-center">
        <p class="text-3xl mb-2">🌱</p>
        <p class="font-semibold text-slate-700">Belum ada entri jurnal.</p>
        <p class="mt-1 text-sm text-slate-500">Mulai dengan satu kalimat kecil hari ini — itu sudah cukup.</p>
    </div>

    <div class="space-y-4">
        <template x-for="e in entri" :key="e.id">
            <article class="konten-masuk rounded-2xl border border-tenang-100 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-slate-900" x-text="e.title || e.judul || 'Tanpa judul'"></h3>
                        <p class="mt-0.5 text-xs text-slate-400" x-text="formatTanggal(e.created_at || e.tanggal)"></p>
                    </div>
                    <button type="button" @click="hapus(e.id)"
                            class="rounded-lg px-2 py-1 text-xs font-medium text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition"
                            title="Hapus entri">
                        🗑️ Hapus
                    </button>
                </div>
                <p x-show="teksPromptEntri(e)" class="mt-2 inline-block rounded-lg bg-tenang-50 px-3 py-1 text-xs italic text-tenang-700"
                   x-text="'💭 ' + teksPromptEntri(e)"></p>
                <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-slate-700" x-text="e.content || e.konten || ''"></p>
                <div x-show="moodEntri(e, 'before') || moodEntri(e, 'after')" class="mt-3 flex flex-wrap gap-2 text-xs">
                    <span x-show="moodEntri(e, 'before')" class="rounded-full bg-slate-100 px-3 py-1 text-slate-600">
                        Sebelum: <strong x-text="moodEntri(e, 'before') + ' ' + emojiMood(moodEntri(e, 'before'))"></strong>
                    </span>
                    <span x-show="moodEntri(e, 'after')" class="rounded-full bg-slate-100 px-3 py-1 text-slate-600">
                        Sesudah: <strong x-text="moodEntri(e, 'after') + ' ' + emojiMood(moodEntri(e, 'after'))"></strong>
                    </span>
                </div>
            </article>
        </template>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function jurnalApp() {
        return {
            entri: [],
            prompts: [],
            memuat: true,
            menyimpan: false,
            error: '',
            formTerbuka: false,
            form: { judul: '', konten: '', promptId: '', moodSebelum: '', moodSesudah: '' },

            labelMood: function (n) {
                return ['', 'Sangat Buruk', 'Buruk', 'Biasa Saja', 'Baik', 'Sangat Baik'][n] || '';
            },
            emojiMood: function (n) {
                return ['', '😞', '😟', '😐', '🙂', '😄'][Number(n)] || '';
            },
            idPrompt: function (p) { return (p && p.id !== undefined) ? p.id : p; },
            teksPrompt: function (p) { return (p && p.text !== undefined) ? p.text : String(p); },
            teksPromptEntri: function (e) {
                var p = e.prompt || e.prompt_text;
                if (typeof p === 'object' && p) return p.text || '';
                return p || '';
            },
            moodEntri: function (e, kapan) {
                if (kapan === 'before') return e.mood_before || e.moodSebelum || null;
                return e.mood_after || e.moodSesudah || null;
            },
            formatTanggal: function (iso) {
                if (!iso) return '';
                try {
                    return new Date(iso).toLocaleString('id-ID', {
                        day: 'numeric', month: 'long', year: 'numeric',
                        hour: '2-digit', minute: '2-digit',
                    });
                } catch (e) { return String(iso); }
            },

            init: async function () {
                if (!window.RT.requireAuth()) return;
                await Promise.all([this.muatEntri(), this.muatPrompts()]);
                this.memuat = false;
            },

            muatEntri: async function () {
                try {
                    var res = await window.RT.authFetch('/journal');
                    if (res.status === 401) { window.RT.clearAuth(); window.location.href = '{{ url('/masuk') }}'; return; }
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    var body = await res.json();
                    this.entri = body.data || body.entries || body || [];
                } catch (e) {
                    this.error = 'Jurnalmu belum bisa dimuat. Cek koneksi lalu muat ulang halaman ya.';
                }
            },

            muatPrompts: async function () {
                try {
                    var res = await window.RT.authFetch('/journal/prompts');
                    if (!res.ok) return;
                    var body = await res.json();
                    this.prompts = body.data || body.prompts || body || [];
                } catch (e) { /* prompt opsional — abaikan bila gagal */ }
            },

            resetForm: function () {
                this.form = { judul: '', konten: '', promptId: '', moodSebelum: '', moodSesudah: '' };
            },

            simpan: async function () {
                this.error = '';
                if (!this.form.konten.trim()) {
                    this.error = 'Ceritamu masih kosong. Tulis satu kalimat dulu ya.';
                    return;
                }
                this.menyimpan = true;
                try {
                    var payload = {
                        title: this.form.judul || null,
                        content: this.form.konten,
                    };
                    if (this.form.promptId !== '') payload.prompt_id = this.form.promptId;
                    if (this.form.moodSebelum !== '') payload.mood_before = Number(this.form.moodSebelum);
                    if (this.form.moodSesudah !== '') payload.mood_after = Number(this.form.moodSesudah);

                    var res = await window.RT.authFetch('/journal', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(payload),
                    });
                    if (!res.ok) {
                        this.error = await window.RT.pesanError(res, 'Entrinya belum tersimpan. Coba lagi ya.');
                        return;
                    }
                    var body = await res.json();
                    var baru = body.data || body.entry || body;
                    if (baru && baru.id) this.entri.unshift(baru);
                    else await this.muatEntri();
                    this.resetForm();
                    this.formTerbuka = false;
                } catch (e) {
                    this.error = 'Koneksi ke server bermasalah. Entrinya belum tersimpan — coba lagi ya.';
                } finally {
                    this.menyimpan = false;
                }
            },

            hapus: async function (id) {
                if (!confirm('Hapus entri ini? Tindakan ini tidak bisa dibatalkan.')) return;
                this.error = '';
                try {
                    var res = await window.RT.authFetch('/journal/' + id, { method: 'DELETE' });
                    if (!res.ok) {
                        this.error = await window.RT.pesanError(res, 'Entrinya belum terhapus. Coba lagi ya.');
                        return;
                    }
                    this.entri = this.entri.filter(function (e) { return e.id !== id; });
                } catch (e) {
                    this.error = 'Koneksi ke server bermasalah. Coba lagi ya.';
                }
            },
        };
    }
</script>
@endpush
