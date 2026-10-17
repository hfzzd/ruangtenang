<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware penjaga area admin RuangTenang.
 *
 * Hanya pengguna yang sudah login DAN memiliki kolom `role = 'admin'`
 * yang boleh melewati middleware ini. Pengguna lain diperlakukan
 * secara tegas:
 *
 *  - Belum login → dialihkan ke halaman masuk (route 'masuk').
 *  - Sudah login tetapi bukan admin → respons 403 (abort), tanpa
 *    membocorkan alasan atau data apa pun ke pengguna yang
 *    tidak berhak.
 *
 * Nilai role dibandingkan dengan perbandingan ketat (`!==`) dan
 * dibandingkan persis dengan string 'admin' — tidak ada parameter
 * role dinamis di rute supaya tidak bisa dimanipulasi dari URL.
 */
class IsAdmin
{
    /**
     * Tangani permintaan masuk.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Belum login: arahkan ke halaman masuk, jangan bocorkan
        // bahwa rute admin ini ada atau tidak.
        if ($user === null) {
            return redirect()->route('masuk');
        }

        // Bukan admin: tolak dengan 403, tanpa pesan detail.
        if ($user->role !== 'admin') {
            abort(403);
        }

        return $next($request);
    }
}
