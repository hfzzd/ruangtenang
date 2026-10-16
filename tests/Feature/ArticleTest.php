<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test fitur halaman artikel & bacaan RuangTenang.
 *
 * Cakupan: daftar artikel (hanya yang published), detail via slug,
 * 404 untuk artikel belum published, dan filter kategori.
 */
class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_daftar_artikel_hanya_tampilkan_yang_published(): void
    {
        Article::create([
            'title' => 'Artikel Edukasi Sehat',
            'slug' => 'artikel-edukasi-sehat',
            'excerpt' => 'Ringkasan edukasi.',
            'content' => 'Isi artikel edukasi yang sudah publish.',
            'category' => 'Edukasi',
            'published_at' => now()->subDay(),
        ]);

        Article::create([
            'title' => 'Artikel Draft Rahasia',
            'slug' => 'artikel-draft-rahasia',
            'excerpt' => 'Ringkasan draft.',
            'content' => 'Isi artikel yang belum dipublish.',
            'category' => 'Edukasi',
            'published_at' => null,
        ]);

        $response = $this->get(route('artikel.index'));

        $response->assertOk();
        $response->assertSee('Artikel Edukasi Sehat');
        $response->assertDontSee('Artikel Draft Rahasia');
    }

    public function test_detail_artikel_bisa_diakses_via_slug(): void
    {
        Article::create([
            'title' => 'Teknik Relaksasi Harian',
            'slug' => 'teknik-relaksasi-harian',
            'excerpt' => 'Ringkasan relaksasi.',
            'content' => 'Isi panduan teknik relaksasi harian.',
            'category' => 'Teknik Relaksasi',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('artikel.show', 'teknik-relaksasi-harian'));

        $response->assertOk();
        $response->assertSee('Teknik Relaksasi Harian');
    }

    public function test_artikel_belum_published_404(): void
    {
        Article::create([
            'title' => 'Artikel Draft Rahasia',
            'slug' => 'artikel-draft-rahasia',
            'excerpt' => 'Ringkasan draft.',
            'content' => 'Isi artikel yang belum dipublish.',
            'category' => 'Edukasi',
            'published_at' => null,
        ]);

        $this->get(route('artikel.show', 'artikel-draft-rahasia'))
            ->assertNotFound();
    }

    public function test_filter_kategori(): void
    {
        Article::create([
            'title' => 'Artikel Edukasi Sehat',
            'slug' => 'artikel-edukasi-sehat',
            'excerpt' => 'Ringkasan edukasi.',
            'content' => 'Isi artikel edukasi.',
            'category' => 'Edukasi',
            'published_at' => now()->subDay(),
        ]);

        Article::create([
            'title' => 'Teknik Relaksasi Harian',
            'slug' => 'teknik-relaksasi-harian',
            'excerpt' => 'Ringkasan relaksasi.',
            'content' => 'Isi panduan relaksasi.',
            'category' => 'Teknik Relaksasi',
            'published_at' => now()->subDay(),
        ]);

        $response = $this->get(route('artikel.index', ['kategori' => 'Edukasi']));

        $response->assertOk();
        $response->assertSee('Artikel Edukasi Sehat');
        $response->assertDontSee('Teknik Relaksasi Harian');
    }
}
