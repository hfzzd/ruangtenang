<?php

namespace App\Http\Controllers;

use App\Models\ForumReply;
use App\Models\ForumReport;
use App\Models\ForumThread;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ForumController extends Controller
{
    /**
     * Tampilkan daftar thread forum, dengan filter kategori opsional.
     */
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'kategori' => ['nullable', 'string', Rule::in(ForumThread::CATEGORIES)],
        ]);

        $threads = ForumThread::query()
            ->withCount('replies')
            ->when($validated['kategori'] ?? null, function ($query, $kategori) {
                return $query->where('category', $kategori);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('forum.index', [
            'threads' => $threads,
            'kategoriAktif' => $validated['kategori'] ?? null,
            'daftarKategori' => ForumThread::CATEGORIES,
        ]);
    }

    /**
     * Tampilkan satu thread beserta semua balasannya.
     */
    public function show(int $id): View
    {
        $thread = ForumThread::with([
            'replies' => fn ($query) => $query->oldest(),
        ])->findOrFail($id);

        return view('forum.show', [
            'thread' => $thread,
        ]);
    }

    /**
     * Tampilkan form pembuatan thread baru.
     */
    public function create(): View
    {
        return view('forum.create', [
            'daftarKategori' => ForumThread::CATEGORIES,
        ]);
    }

    /**
     * Simpan thread baru dari form web.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:5000'],
            'category' => ['required', 'string', Rule::in(ForumThread::CATEGORIES)],
        ]);

        if ($request->user()) {
            $validated['user_id'] = $request->user()->id;
        }

        $thread = ForumThread::create($validated);

        return redirect()
            ->route('forum.show', $thread->id)
            ->with('status', 'Thread berhasil dibuat. Terima kasih sudah berbagi 💚');
    }

    /**
     * Simpan balasan baru untuk sebuah thread.
     */
    public function storeReply(Request $request, int $id): RedirectResponse
    {
        $thread = ForumThread::findOrFail($id);

        $validated = $request->validate([
            'author_name' => ['required', 'string', 'max:50'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $thread->replies()->create($validated);

        return redirect()
            ->route('forum.show', $thread->id)
            ->with('status', 'Balasan terkirim. Terima kasih sudah mendukung sesama 💚');
    }

    /**
     * Simpan laporan terhadap sebuah thread forum.
     */
    public function report(Request $request, int $id): RedirectResponse
    {
        $thread = ForumThread::findOrFail($id);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:300'],
            'reporter_name' => ['nullable', 'string', 'max:80'],
        ]);

        ForumReport::create([
            'forum_thread_id' => $thread->id,
            'reason' => $validated['reason'],
            'reporter_name' => $validated['reporter_name'] ?? null,
        ]);

        return back()
            ->with('sukses', 'Terima kasih atas laporanmu. Tim kami akan meninjaunya. 🌱');
    }

    /**
     * Tampilkan daftar laporan thread forum untuk moderasi.
     *
     * CATATAN: rute admin ini masih "lunak" — belum ada role admin /
     * middleware otorisasi, jadi siapa pun yang tahu URL-nya bisa membuka
     * halaman ini. Ditandai untuk dikeraskan (middleware admin) di
     * iterasi berikut.
     */
    public function reports(): View
    {
        $reports = ForumReport::with('thread')
            ->orderByDesc('created_at')
            ->get();

        return view('forum.laporan', [
            'reports' => $reports,
        ]);
    }

    /**
     * Tindaklanjuti sebuah laporan: ubah statusnya, atau hapus thread
     * yang dilaporkan (cascade menghapus semua laporan di thread itu).
     */
    public function resolveReport(Request $request, int $id): RedirectResponse
    {
        $report = ForumReport::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['ditindak', 'ditolak'])],
            'hapus_thread' => ['nullable', 'boolean'],
        ]);

        $thread = $report->thread;

        if (($validated['hapus_thread'] ?? false) && $thread) {
            // Hapus thread beserta seluruh laporan terkait (cascade).
            $thread->delete();
        } else {
            $report->update(['status' => $validated['status']]);
        }

        return redirect()
            ->route('admin.reports')
            ->with('sukses', 'Laporan berhasil ditindaklanjuti. 🌱');
    }
}
