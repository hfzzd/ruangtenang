<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Endpoint check-in mood harian RuangTenang.
 *
 * Semua operasi dibatasi pada data milik pengguna yang sedang login
 * (via $request->user()). Berlaku aturan satu hari satu entri:
 * POST untuk tanggal yang sudah ada akan memperbarui entri tersebut.
 *
 *  - GET    /api/mood/trend : rata-rata mood harian dalam N hari terakhir.
 *  - GET    /api/mood       : riwayat check-in mood milik pengguna.
 *  - POST   /api/mood       : check-in mood harian (satu hari satu entri).
 *  - GET    /api/mood/{id}  : detail satu entri mood.
 *  - DELETE /api/mood/{id}  : hapus entri mood.
 */
class MoodController extends Controller
{
    /**
     * Riwayat check-in mood milik pengguna, dari yang terbaru.
     */
    public function index(Request $request): JsonResponse
    {
        $entries = $request->user()->moodEntries()
            ->orderByDesc('logged_at')
            ->paginate(30);

        return response()->json($entries);
    }

    /**
     * Check-in mood harian. Satu tanggal hanya punya satu entri;
     * check-in kedua di hari yang sama memperbarui entri yang ada.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'mood' => 'required|integer|min:1|max:5',
            'note' => 'nullable|string|max:500',
            'logged_at' => 'nullable|date',
        ]);

        $loggedAt = $data['logged_at'] ?? now()->toDateString();

        $entry = $request->user()->moodEntries()->updateOrCreate(
            ['logged_at' => $loggedAt],
            [
                'mood' => $data['mood'],
                'note' => $data['note'] ?? null,
            ]
        );

        return response()->json([
            'message' => $entry->wasRecentlyCreated
                ? 'Check-in mood hari ini berhasil disimpan.'
                : 'Check-in mood hari ini berhasil diperbarui.',
            'data' => $entry,
        ], $entry->wasRecentlyCreated ? 201 : 200);
    }

    /**
     * Detail satu entri mood milik pengguna.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $entry = $request->user()->moodEntries()->findOrFail($id);

        return response()->json(['data' => $entry]);
    }

    /**
     * Hapus entri mood milik pengguna.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $entry = $request->user()->moodEntries()->findOrFail($id);
        $entry->delete();

        return response()->json(null, 204);
    }

    /**
     * Tren mood: rata-rata mood per hari dalam N hari terakhir.
     *
     * GET /api/mood/trend?days=30
     */
    public function trend(Request $request): JsonResponse
    {
        $data = $request->validate([
            'days' => 'nullable|integer|min:1|max:90',
        ]);

        $days = (int) ($data['days'] ?? 30);
        $start = now()->subDays($days - 1)->startOfDay()->toDateString();

        $rows = $request->user()->moodEntries()
            ->where('logged_at', '>=', $start)
            ->selectRaw('logged_at, AVG(mood) as avg_mood, COUNT(*) as entries')
            ->groupBy('logged_at')
            ->orderBy('logged_at')
            ->get();

        $trend = $rows->map(fn ($row) => [
            'logged_at' => $row->logged_at->toDateString(),
            'avg_mood' => round((float) $row->avg_mood, 2),
            'entries' => (int) $row->entries,
        ])->values();

        return response()->json([
            'data' => $trend,
            'days' => $days,
        ]);
    }
}
