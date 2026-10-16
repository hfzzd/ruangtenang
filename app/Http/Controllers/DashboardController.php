<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Controller dasbor "Saya" RuangTenang.
 *
 * Ringkasan personal untuk pengguna yang sedang masuk: booking yang
 * akan datang, jurnal terakhir, dan mood 7 hari terakhir.
 *
 * Catatan: route ke controller ini wajib memakai middleware auth web
 * (didefinisikan di routes/web.php), karena seluruh data diambil dari
 * auth()->user().
 */
class DashboardController extends Controller
{
    /**
     * Tampilkan dasbor personal pengguna.
     */
    public function index(): View
    {
        $user = auth()->user();

        // Booking yang masih akan datang (pending/confirmed), maksimal 3.
        $upcomingBookings = $user->bookings()
            ->with('professional')
            ->where('scheduled_at', '>', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('scheduled_at')
            ->take(3)
            ->get();

        // Tiga entri jurnal terbaru.
        $recentJournals = $user->journalEntries()
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        // Check-in mood 7 hari terakhir, urut dari yang terlama.
        $moodWeek = $user->moodEntries()
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at')
            ->get();

        // Rata-rata mood mingguan (skala 1-5), null bila belum ada data.
        $moodAverage = $moodWeek->isNotEmpty()
            ? round($moodWeek->avg('mood'), 1)
            : null;

        return view('dashboard', compact(
            'user',
            'upcomingBookings',
            'recentJournals',
            'moodWeek',
            'moodAverage',
        ));
    }
}
