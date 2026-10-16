<?php

namespace Tests\Feature;

use App\Models\ForumThread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test fitur forum komunitas RuangTenang.
 *
 * Cakupan: pembuatan thread, balasan, pelaporan thread,
 * dan halaman antrean laporan moderasi.
 */
class ForumTest extends TestCase
{
    use RefreshDatabase;

    public function test_bisa_buat_thread(): void
    {
        $response = $this->post(route('forum.store'), [
            'author_name' => 'Rina',
            'title' => 'Cara mengelola cemas sebelum ujian',
            'body' => 'Aku sering cemas berlebihan setiap mau ujian. Ada tips?',
            'category' => 'tanya',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('forum_threads', [
            'title' => 'Cara mengelola cemas sebelum ujian',
            'author_name' => 'Rina',
            'category' => 'tanya',
        ]);
    }

    public function test_bisa_balas_thread(): void
    {
        $thread = ForumThread::create([
            'author_name' => 'Rina',
            'title' => 'Cara mengelola cemas sebelum ujian',
            'body' => 'Aku sering cemas berlebihan setiap mau ujian.',
            'category' => 'tanya',
        ]);

        $response = $this->post(route('forum.reply', $thread->id), [
            'author_name' => 'Dimas',
            'body' => 'Coba teknik napas 4-7-8, itu membantu banget buatku.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('forum_replies', [
            'thread_id' => $thread->id,
            'author_name' => 'Dimas',
            'body' => 'Coba teknik napas 4-7-8, itu membantu banget buatku.',
        ]);
    }

    public function test_bisa_melaporkan_thread(): void
    {
        $thread = ForumThread::create([
            'author_name' => 'Spammer',
            'title' => 'PROMO OBAT AJAIB',
            'body' => 'Beli sekarang, dijamin sembuh!',
            'category' => 'umum',
        ]);

        $response = $this->post(route('forum.report', $thread->id), [
            'reason' => 'Spam',
            'reporter_name' => 'Rina',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('forum_reports', [
            'forum_thread_id' => $thread->id,
            'reason' => 'Spam',
            'reporter_name' => 'Rina',
            'status' => 'baru',
        ]);
    }

    public function test_antrean_laporan_bisa_diakses(): void
    {
        $this->get(route('admin.reports'))->assertOk();
    }
}
