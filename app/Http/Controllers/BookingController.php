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
     * Detail satu booking.
     */
    public function show(int $id): View
    {
        $booking = Booking::with(['professional', 'user'])->findOrFail($id);

        return view('booking.show', ['booking' => $booking]);
    }
}
