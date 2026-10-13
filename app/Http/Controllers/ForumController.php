<?php

namespace App\Http\Controllers;

use App\Models\ForumReply;
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
}
