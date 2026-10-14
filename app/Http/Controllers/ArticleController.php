<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller halaman web artikel & bacaan RuangTenang.
 *
 * Hanya artikel yang sudah dipublish yang boleh dibaca publik:
 * published_at tidak boleh null dan tidak boleh di masa depan.
 *
 * Keamanan:
 *  - Filter ?kategori= divalidasi sebagai string dan hanya dipakai jika
 *    cocok persis dengan salah satu kategori yang ada di database
 *    (pendekatan whitelist — tidak pernah masuk mentah ke query).
 *  - Parameter slug selalu lewat where() Eloquent yang ter-parameterize
 *    (tidak ada raw SQL dengan interpolasi string).
 *  - Blade auto-escape semua output ({{ }}); isi artikel hanya boleh
 *    di-render dengan {!! !!} di view jika sudah dibersihkan
 *    (bukan tanggung jawab controller ini).
 */
class ArticleController extends Controller
{
    /**
     * Basis query artikel yang sudah dipublish. (published_at terisi
     * dan tidak di masa depan.)
     */
    private function publishedQuery(): Builder
    {
        return Article::query()
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Daftar artikel. Mendukung ?kategori= untuk memfilter berdasarkan
     * kategori; nilai yang tidak cocok daftar kategori diabaikan.
     */
    public function index(Request $request): View
    {
        $data = $request->validate([
            'kategori' => 'nullable|string|max:100',
        ]);

        // Daftar kategori diambil dari artikel published saja,
        // urut abjad untuk opsi filter.
        $categories = $this->publishedQuery()
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->values()
            ->toArray();

        // Whitelist: hanya terima kategori yang benar-benar ada.
        $selectedCategory = $data['kategori'] ?? null;
        if ($selectedCategory !== null && ! in_array($selectedCategory, $categories, true)) {
            $selectedCategory = null;
        }

        $query = $this->publishedQuery();

        if ($selectedCategory !== null) {
            $query->where('category', $selectedCategory);
        }

        $articles = $query
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('artikel.index', [
            'articles'         => $articles,
            'categories'       => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    /**
     * Detail satu artikel berdasarkan slug, plus 3 artikel terkait
     * dari kategori yang sama.
     */
    public function show(string $slug): View
    {
        $article = $this->publishedQuery()
            ->where('slug', $slug)
            ->firstOrFail();

        $related = $this->publishedQuery()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('artikel.show', [
            'article' => $article,
            'related' => $related,
        ]);
    }
}
