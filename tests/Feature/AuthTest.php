<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Test fitur autentikasi web RuangTenang (login, daftar, logout).
 *
 * Mengikuti pola ecc-laravel-tdd: RefreshDatabase untuk state bersih,
 * user dibuat langsung via User::create (tidak ada factory di repo ini),
 * dan setiap test menguji satu perilaku lewat public interface HTTP.
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_masuk_bisa_diakses(): void
    {
        $this->get('/masuk')->assertOk();
    }

    public function test_registrasi_membuat_user_dan_login(): void
    {
        $response = $this->post('/daftar', [
            'name'                  => 'Sinta Pengguna',
            'email'                 => 'sinta@example.com',
            'password'              => 'katasandi123',
            'password_confirmation' => 'katasandi123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name'  => 'Sinta Pengguna',
            'email' => 'sinta@example.com',
        ]);
    }

    public function test_registrasi_gagal_jika_email_duplikat(): void
    {
        User::create([
            'name'     => 'Pemilik Lama',
            'email'    => 'ganda@example.com',
            'password' => Hash::make('katasandi123'),
        ]);

        $response = $this->post('/daftar', [
            'name'                  => 'Peniru Baru',
            'email'                 => 'ganda@example.com',
            'password'              => 'katasandi123',
            'password_confirmation' => 'katasandi123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_login_berhasil_dengan_kredensial_benar(): void
    {
        User::create([
            'name'     => 'Budi Santai',
            'email'    => 'budi@example.com',
            'password' => Hash::make('rahasiaaman'),
        ]);

        $response = $this->post('/masuk', [
            'email'    => 'budi@example.com',
            'password' => 'rahasiaaman',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs(User::where('email', 'budi@example.com')->first());
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        User::create([
            'name'     => 'Citra Tenang',
            'email'    => 'citra@example.com',
            'password' => Hash::make('yangbenar123'),
        ]);

        $response = $this->post('/masuk', [
            'email'    => 'citra@example.com',
            'password' => 'salahbanget',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_logout(): void
    {
        $user = User::create([
            'name'     => 'Dedi Rileks',
            'email'    => 'dedi@example.com',
            'password' => Hash::make('keluaraja1'),
        ]);

        $response = $this->actingAs($user)->post('/keluar');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_tamu_diarahkan_ke_masuk_saat_buka_profil(): void
    {
        // Route GET /profil dibuat terpisah; spek final: tamu diarahkan ke /masuk.
        $this->get('/profil')->assertRedirect('/masuk');
    }
}
