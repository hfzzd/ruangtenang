<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReflectionPrompts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Endpoint jurnal RuangTenang.
 *
 * Semua operasi dibatasi pada data milik pengguna yang sedang login
 * (via $request->user()). Isi jurnal adalah data kesehatan yang sensitif:
 * JANGAN pernah mencatat (log) isi konten jurnal di mana pun.
 *
 *  - GET    /api/journal/prompts : daftar prompt refleksi.
 *  - GET    /api/journal         : daftar entri jurnal milik pengguna.
 *  - POST   /api/journal         : buat entri jurnal baru.
 *  - GET    /api/journal/{id}    : detail satu entri jurnal.
 *  - PUT    /api/journal/{id}    : ubah entri jurnal.
 *  - DELETE /api/journal/{id}    : hapus entri jurnal.
 */
class JournalController extends Controller
{
    /**
     * Daftar prompt refleksi berbahasa Indonesia.
     */
    public function prompts(): JsonResponse
    {
        return response()->json([
            'data' => ReflectionPrompts::all(),
        ]);
    }

    /**
     * Daftar entri jurnal milik pengguna, terbaru dulu.
     */
    public function index(Request $request): JsonResponse
    {
        $entries = $request->user()->journalEntries()
            ->latest()
            ->paginate(15);

        return response()->json($entries);
    }

    /**
     * Buat entri jurnal baru milik pengguna.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'prompt_used' => 'nullable|string|max:255',
            'mood_before' => 'nullable|integer|min:1|max:5',
            'mood_after' => 'nullable|integer|min:1|max:5',
        ]);

        $entry = $request->user()->journalEntries()->create($data);

        return response()->json([
            'message' => 'Entri jurnal berhasil disimpan.',
            'data' => $entry,
        ], 201);
    }

    /**
     * Detail satu entri jurnal milik pengguna.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $entry = $request->user()->journalEntries()->findOrFail($id);

        return response()->json(['data' => $entry]);
    }

    /**
     * Ubah entri jurnal milik pengguna.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $entry = $request->user()->journalEntries()->findOrFail($id);

        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'prompt_used' => 'nullable|string|max:255',
            'mood_before' => 'nullable|integer|min:1|max:5',
            'mood_after' => 'nullable|integer|min:1|max:5',
        ]);

        $entry->update($data);

        return response()->json([
            'message' => 'Entri jurnal berhasil diperbarui.',
            'data' => $entry,
        ]);
    }

    /**
     * Hapus entri jurnal milik pengguna.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $entry = $request->user()->journalEntries()->findOrFail($id);
        $entry->delete();

        return response()->json(null, 204);
    }
}
