<?php

namespace Tests\Feature;

use App\Models\ForumReport;
use App\Models\ForumThread;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Test proteksi admin untuk halaman laporan forum.
 *
 * Kontrak yang diharapkan (Day-6):
 *  - Tamu diarahkan ke /masuk.
 *  - User biasa (role 'user') ditolak dengan 403.
 *  - Admin (role 'admin') bisa mengakses dan menindaklanjuti.
 */
class AdminTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Buat user dengan role tertentu. 'role' tidak masuk $fillable User,
     * jadi diisi langsung lewat assignment, bukan mass-assignment.
     */
    protected function buatUserDenganRole(string $role, string $email): User
    {
        $user = User::create([
            'name'     => 'Pengguna ' . $role,
            'email'    => $email,
            'password' => Hash::make('password'),
        ]);
        $user->role = $role;
        $user->save();

        return $user;
    }

    /**
     * Tamu (belum login) diarahkan ke halaman masuk saat membuka
     * antrean laporan forum.
     */
    public function test_tamu_diarahkan_ke_masuk(): void
    {
        $this->get('/admin/laporan-forum')
            ->assertRedirect('/masuk');
    }

    /**
     * User biasa bukan admin ditolak dengan 403.
     */
    public function test_user_biasa_ditolak_403(): void
    {
        $user = $this->buatUserDenganRole('user', 'biasa@example.com');

        $this->actingAs($user)
            ->get('/admin/laporan-forum')
            ->assertForbidden();
    }

    /**
     * Admin bisa mengakses antrean laporan forum.
     */
    public function test_admin_bisa_akses_antrean(): void
    {
        $admin = $this->buatUserDenganRole('admin', 'admin@example.com');

        $this->actingAs($admin)
            ->get('/admin/laporan-forum')
            ->assertOk();
    }

    /**
     * User biasa tidak bisa menindaklanjuti laporan: dapat 403 dan
     * status laporan tetap 'baru'.
     */
    public function test_bukan_admin_tidak_bisa_resolve(): void
    {
        $user = $this->buatUserDenganRole('user', 'biasa@example.com');

        $thread = ForumThread::create([
            'author_name' => 'Penulis',
            'title'       => 'Thread contoh',
            'body'        => 'Isi thread.',
            'category'    => 'umum',
        ]);
        $report = ForumReport::create([
            'forum_thread_id' => $thread->id,
            'reporter_name'   => 'Pelapor',
            'reason'          => 'Spam',
        ]);

        $this->actingAs($user)
            ->post("/admin/laporan-forum/{$report->id}", ['status' => 'ditindak'])
            ->assertForbidden();

        $this->assertDatabaseHas('forum_reports', [
            'id'     => $report->id,
            'status' => 'baru',
        ]);
    }

    /**
     * Admin bisa menindaklanjuti laporan: redirect + status berubah.
     */
    public function test_admin_bisa_resolve_laporan(): void
    {
        $admin = $this->buatUserDenganRole('admin', 'admin@example.com');

        $thread = ForumThread::create([
            'author_name' => 'Penulis',
            'title'       => 'Thread contoh',
            'body'        => 'Isi thread.',
            'category'    => 'umum',
        ]);
        $report = ForumReport::create([
            'forum_thread_id' => $thread->id,
            'reporter_name'   => 'Pelapor',
            'reason'          => 'Spam',
        ]);

        $this->actingAs($admin)
            ->post("/admin/laporan-forum/{$report->id}", ['status' => 'ditindak'])
            ->assertRedirect();

        $this->assertDatabaseHas('forum_reports', [
            'id'     => $report->id,
            'status' => 'ditindak',
        ]);
    }
}
