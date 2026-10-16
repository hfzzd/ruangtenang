<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Test fitur booking sesi konsultasi RuangTenang.
 *
 * Mencakup alur tamu (buat booking tanpa login), keamanan harga
 * (harga selalu dari database, bukan input user), dan proteksi
 * kepemilikan (hanya pemilik yang boleh lihat/batalkan).
 *
 * Pola ecc-laravel-tdd: data dibuat langsung via Model::create()
 * (belum ada factory), tiap test punya satu alur logis yang jelas.
 */
class BookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tamu (belum login) bisa membuat booking baru.
     */
    public function test_tamu_bisa_buat_booking(): void
    {
        $professional = $this->buatProfessional();

        $response = $this->post(route('booking.store'), [
            'professional_id'  => $professional->id,
            'scheduled_at'     => now()->addDay()->format('Y-m-d H:i'),
            'duration_minutes' => 60,
            'name'             => 'Budi Santoso',
            'email'            => 'budi@example.com',
        ]);

        $booking = Booking::first();
        $this->assertNotNull($booking);

        $response->assertRedirect(route('booking.show', $booking->id));
        $this->assertDatabaseHas('bookings', [
            'id'     => $booking->id,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('users', ['email' => 'budi@example.com']);
    }

    /**
     * Harga booking SELALU diambil dari database profesional,
     * bukan dari input user (anti manipulasi harga).
     */
    public function test_harga_diambil_dari_database(): void
    {
        $professional = $this->buatProfessional(['price_per_session' => 150000]);

        $this->post(route('booking.store'), [
            'professional_id'  => $professional->id,
            'scheduled_at'     => now()->addDay()->format('Y-m-d H:i'),
            'duration_minutes' => 60,
            'name'             => 'Siti Aminah',
            'email'            => 'siti@example.com',
            'price'            => 1, // dicoba dimanipulasi, harus diabaikan
        ]);

        $booking = Booking::first();
        $this->assertNotNull($booking);
        $this->assertSame(150000, (int) $booking->price);
    }

    /**
     * Pemilik booking yang login bisa melihat detail bookingnya.
     */
    public function test_pemilik_bisa_lihat_detail(): void
    {
        $owner = $this->buatUser();
        $booking = $this->buatBooking($owner);

        $this->actingAs($owner)
            ->get(route('booking.show', $booking->id))
            ->assertOk();
    }

    /**
     * Pengguna lain yang login tapi bukan pemilik diarahkan
     * ke halaman masuk saat membuka detail booking.
     */
    public function test_bukan_pemilik_diarahkan_ke_masuk(): void
    {
        $owner = $this->buatUser(['email' => 'owner@example.com']);
        $lain = $this->buatUser(['email' => 'lain@example.com']);
        $booking = $this->buatBooking($owner);

        $this->actingAs($lain)
            ->get(route('booking.show', $booking->id))
            ->assertRedirect(route('masuk'));
    }

    /**
     * Pemilik bisa membatalkan bookingnya (status → cancelled).
     */
    public function test_pemilik_bisa_batalkan(): void
    {
        $owner = $this->buatUser();
        $booking = $this->buatBooking($owner);

        $response = $this->actingAs($owner)
            ->post(route('booking.cancel', $booking->id));

        $response->assertRedirect(route('booking.show', $booking->id));
        $this->assertDatabaseHas('bookings', [
            'id'     => $booking->id,
            'status' => 'cancelled',
        ]);
    }

    /**
     * Bukan pemilik tidak bisa membatalkan: diarahkan ke halaman
     * masuk dan status booking tidak berubah.
     */
    public function test_bukan_pemilik_tidak_bisa_batalkan(): void
    {
        $owner = $this->buatUser(['email' => 'owner@example.com']);
        $lain = $this->buatUser(['email' => 'lain@example.com']);
        $booking = $this->buatBooking($owner);

        $this->actingAs($lain)
            ->post(route('booking.cancel', $booking->id))
            ->assertRedirect(route('masuk'));

        $this->assertDatabaseHas('bookings', [
            'id'     => $booking->id,
            'status' => 'pending',
        ]);
    }

    // ------------------------------------------------------------------
    // Helper data (belum ada factory, pakai Model::create langsung).
    // ------------------------------------------------------------------

    /**
     * @param array<string, mixed> $override
     */
    private function buatUser(array $override = []): User
    {
        return User::create(array_merge([
            'name'     => 'Pengguna Uji',
            'email'    => 'uji-'.uniqid().'@example.com',
            'password' => Hash::make('password'),
        ], $override));
    }

    /**
     * @param array<string, mixed> $override
     */
    private function buatProfessional(array $override = []): Professional
    {
        return Professional::create(array_merge([
            'name'              => 'Dr. Rina Wijaya',
            'title'             => 'Psikolog Klinis',
            'specialization'    => 'Kecemasan',
            'city'              => 'Jakarta',
            'price_per_session' => 200000,
        ], $override));
    }

    private function buatBooking(User $owner, ?Professional $professional = null): Booking
    {
        return Booking::create([
            'user_id'          => $owner->id,
            'professional_id'  => ($professional ?? $this->buatProfessional())->id,
            'scheduled_at'     => now()->addDay(),
            'duration_minutes' => 60,
            'status'           => 'pending',
            'price'            => 200000,
        ]);
    }
}
