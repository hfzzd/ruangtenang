<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Direktori psikolog RuangTenang.
 *
 * Halaman publik (tanpa auth) agar siapa pun bisa menjelajah profil
 * profesional sebelum memutuskan booking sesi.
 *
 *  - GET /psikolog      : daftar profesional, dengan pencarian nama (?q=)
 *                         serta filter spesialisasi (?spesialisasi=)
 *                         dan kota (?kota=). Paginasi sederhana.
 *  - GET /psikolog/{id} : profil lengkap satu psikolog (404 bila tidak ada).
 */
class PsikologController extends Controller
{
    /**
     * Tampilkan direktori psikolog dengan pencarian dan filter opsional.
     *
     * Query string ?q= mencari sebagian nama (tidak case-sensitive),
     * ?spesialisasi= dan ?kota= memfilter dari dropdown yang tersedia.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Professional::query()->orderBy('name');

        // Pencarian nama: sebagian kata, case-insensitive.
        $q = trim((string) $request->query('q', ''));
        if ($q !== '') {
            $query->where('name', 'like', '%' . $q . '%');
        }

        // Filter spesialisasi dan kota dari dropdown.
        $spesialisasiDipilih = trim((string) $request->query('spesialisasi', ''));
        if ($spesialisasiDipilih !== '') {
            $query->where('specialization', $spesialisasiDipilih);
        }

        $kotaDipilih = trim((string) $request->query('kota', ''));
        if ($kotaDipilih !== '') {
            $query->where('city', $kotaDipilih);
        }

        $professionals = $query->simplePaginate(12)->withQueryString();

        // Nilai unik untuk mengisi dropdown filter.
        $daftarSpesialisasi = Professional::query()
            ->select('specialization')
            ->distinct()
            ->orderBy('specialization')
            ->pluck('specialization');

        $daftarKota = Professional::query()
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('psikolog.index', [
            'professionals' => $professionals,
            'daftarSpesialisasi' => $daftarSpesialisasi,
            'daftarKota' => $daftarKota,
            'q' => $q,
            'spesialisasiDipilih' => $spesialisasiDipilih,
            'kotaDipilih' => $kotaDipilih,
        ]);
    }

    /**
     * Tampilkan profil lengkap satu psikolog.
     *
     * @param int $id
     * @return View
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function show(int $id): View
    {
        $professional = Professional::findOrFail($id);

        return view('psikolog.show', compact('professional'));
    }
}
