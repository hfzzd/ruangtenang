<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Controller halaman web booking sesi konsultasi RuangTenang.
 *
 * DESAIN DAY-2 (hemat commit, tanpa sistem auth web baru):
 *  - Form booking meminta nama + email + telepon (opsional), lalu data
 *    pemesan dikaitkan ke User via firstOrCreate() berdasarkan email.
 *  - Halaman "Booking Saya" (index) memakai lookup by ?email=.
 *
 * Keamanan:
 *  - Validasi ketat di store(); harga sesi SELALU diambil dari
 *    professional->price_per_session, JANGAN PERNAH dari input user.
 *  - Hanya field yang divalidasi yang dipakai membuat record
 *    (tidak ada $request->all() / mass assignment dari input mentah).
 *  - Blade auto-escape semua output user ({{ }}), tidak ada {!! !!} untuk
 *    data input pengguna.
 *  - Proteksi kepemilikan (Day-4, pola ecc-laravel-security: pemilik =
 *    user_id sama): detail booking hanya boleh dilihat pemilik yang
 *    login atau pembuatnya via session; aksi batal/ubah jadwal hanya
 *    untuk pemilik yang login.
 *
 * Catatan: kata sandi acak dipakai saat membuat User baru karena kolom
 * password NOT NULL; akun ini dimaksudkan dikelola saat auth web
 * dipasang nanti (Day-2 belum ada reset password web).
 */
class BookingController extends Controller
{
    /**
     * Form buat booking. Mendukung ?psikolog=<id> untuk preselect
     * profesional dari halaman direktori psikolog.
     */
    public function create(Request $request): View
    {
        $professionals = Professional::query()
            ->orderBy('name')
            ->get();

        $selectedId = $request->integer('psikolog');

        if ($selectedId > 0 && ! $professionals->contains('id', $selectedId)) {
            $selectedId = 0;
        }

        return view('booking.create', [
            'professionals' => $professionals,
            'selectedId' => $selectedId,
        ]);
    }

    /**
     * Simpan booking baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'professional_id'  => 'required|integer|exists:professionals,id',
            'scheduled_at'     => 'required|date|after:today',
            'duration_minutes' => 'required|integer|in:30,45,60,90',
            'name'             => 'required|string|max:120',
            'email'            => 'required|email|max:190',
            'phone'            => 'nullable|string|max:30',
            'notes'            => 'nullable|string|max:500',
        ]);

        $professional = Professional::findOrFail($data['professional_id']);

        // Harga SELALU dari database, bukan dari input form.
        $price = (int) $professional->price_per_session;

        // Kaitkan pemesan ke User berdasarkan email. User baru dibuat dengan
        // kata sandi acak (tidak dipakai untuk login web saat ini).
        $user = User::firstOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'password' => Hash::make(Str::random(40)),
            ]
        );

        // Telepon bersifat opsional; tabel users belum punya kolom phone,
        // jadi disimpan di awal catatan agar info kontak tidak hilang.
        $notes = $data['notes'] ?? null;
        if (! empty($data['phone'])) {
            $notes = 'Telp: ' . trim($data['phone']) . ($notes ? "\n\n" . $notes : '');
            $notes = mb_substr($notes, 0, 500);
        }

        $booking = Booking::create([
            'user_id'          => $user->id,
            'professional_id'  => $professional->id,
            'scheduled_at'     => $data['scheduled_at'],
            'duration_minutes' => $data['duration_minutes'],
            'status'           => 'pending',
            'notes'            => $notes,
            'price'            => $price,
        ]);

        // Catat ID booking di session agar pembuatnya bisa langsung
        // melihat detail tanpa login dulu (dihapus saat logout/session habis).
        $request->session()->push('booking_baru', $booking->id);

        return redirect()
            ->route('booking.show', $booking->id)
            ->with('sukses', 'Booking berhasil dibuat. Kami akan menghubungimu untuk konfirmasi jadwal. 🌱');
    }

    /**
     * Halaman "Booking Saya": form cari by email + daftar booking
     * milik email tersebut (dari terbaru).
     */
    public function index(Request $request): View
    {
        $data = $request->validate([
            'email' => 'nullable|email|max:190',
        ]);

        $email = $data['email'] ?? null;
        $bookings = collect();

        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $bookings = $user->bookings()
                    ->with('professional')
                    ->orderByDesc('scheduled_at')
                    ->get();
            }
        }

        return view('booking.index', [
            'email' => $email,
            'bookings' => $bookings,
            'searched' => $email !== null,
        ]);
    }

    /**
     * Detail satu booking. Terproteksi kepemilikan: hanya pemilik yang
     * login atau pembuat via session yang boleh melihat.
     */
    public function show(Request $request, int $id): View|RedirectResponse
    {
        $booking = Booking::with(['professional', 'user'])->findOrFail($id);

        if (! $this->bolehKelola($booking, $request)) {
            return redirect()->route('masuk')->with('galat', 'Silakan masuk untuk melihat booking ini.');
        }

        return view('booking.show', ['booking' => $booking]);
    }

    /**
     * Batalkan booking. Hanya boleh untuk status 'pending'/'confirmed'.
     * Booking yang sudah completed/cancelled tidak bisa diubah lagi.
     */
    public function cancel(int $id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);

        // Hanya pemilik yang login boleh membatalkan (tamu diarahkan
        // daftar/masuk dulu).
        if (! (auth()->check() && $booking->user_id === auth()->id())) {
            return redirect()->route('masuk')->with('galat', 'Silakan masuk untuk mengelola booking ini.');
        }

        if (! $this->isCancellable($booking)) {
            return back()->with('galat', 'Booking ini sudah tidak bisa dibatalkan lagi.');
        }

        $booking->status = 'cancelled';
        $booking->save();

        return redirect()
            ->route('booking.show', $booking->id)
            ->with('sukses', 'Booking berhasil dibatalkan. Jaga dirimu baik-baik ya 🌱');
    }

    /**
     * Form ubah jadwal booking. Hanya boleh untuk status
     * 'pending'/'confirmed' dan jadwal yang masih di masa depan.
     */
    public function editSchedule(int $id): View|RedirectResponse
    {
        $booking = Booking::with('professional')->findOrFail($id);

        // Hanya pemilik yang login boleh mengubah jadwal.
        if (! (auth()->check() && $booking->user_id === auth()->id())) {
            return redirect()->route('masuk')->with('galat', 'Silakan masuk untuk mengelola booking ini.');
        }

        if (! $this->isReschedulable($booking)) {
            return back()->with('galat', 'Jadwal booking ini sudah tidak bisa diubah lagi.');
        }

        return view('booking.edit-schedule', ['booking' => $booking]);
    }

    /**
     * Simpan perubahan jadwal booking.
     */
    public function updateSchedule(Request $request, int $id): RedirectResponse
    {
        $data = $request->validate([
            'scheduled_at'     => 'required|date|after:today',
            'duration_minutes' => 'required|integer|in:30,45,60,90',
        ]);

        $booking = Booking::findOrFail($id);

        // Hanya pemilik yang login boleh menyimpan perubahan jadwal.
        if (! (auth()->check() && $booking->user_id === auth()->id())) {
            return redirect()->route('masuk')->with('galat', 'Silakan masuk untuk mengelola booking ini.');
        }

        if (! $this->isReschedulable($booking)) {
            return back()->with('galat', 'Jadwal booking ini sudah tidak bisa diubah lagi.');
        }

        // Hanya dua kolom tervalidasi yang diupdate (ecc-laravel-security:
        // tidak ada mass assignment dari input mentah).
        $booking->update($data);

        return redirect()
            ->route('booking.show', $booking->id)
            ->with('sukses', 'Jadwal booking berhasil diubah 🌱');
    }

    /**
     * Cek kepemilikan booking (otorisasi level record, pola
     * ecc-laravel-security: pemilik = user_id sama).
     *
     * Booking boleh dilihat jika:
     *  - pengguna sudah login DAN booking miliknya, ATAU
     *  - ID booking tercatat di session 'booking_baru' (baru dibuat
     *    dari form booking di browser ini, sebelum login).
     */
    private function bolehKelola(Booking $booking, Request $request): bool
    {
        if (auth()->check() && $booking->user_id === auth()->id()) {
            return true;
        }

        return in_array($booking->id, $request->session()->get('booking_baru', []), true);
    }

    /**
     * Guard: booking bisa dibatalkan hanya jika statusnya
     * 'pending' atau 'confirmed'.
     */
    private function isCancellable(Booking $booking): bool
    {
        return in_array($booking->status, ['pending', 'confirmed'], true);
    }

    /**
     * Guard: jadwal booking bisa diubah hanya jika statusnya
     * 'pending'/'confirmed' DAN scheduled_at masih di masa depan.
     */
    private function isReschedulable(Booking $booking): bool
    {
        return $this->isCancellable($booking)
            && $booking->scheduled_at !== null
            && $booking->scheduled_at->isFuture();
    }
}
