<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\View\View;

/**
 * Controller halaman profil pengguna RuangTenang.
 *
 * Keamanan (pola ecc-laravel-security):
 *  - Seluruh rute profil dilindungi middleware 'auth' di routes/web.php;
 *    controller ini mengulanginya via HasMiddleware sebagai defense in
 *    depth (base Controller di Laravel 12 sudah tidak punya
 *    $this->middleware() ala Laravel lama, jadi dipakai interface resmi
 *    penggantinya).
 *  - Update hanya memakai field yang divalidasi; kolom sensitif seperti
 *    'role' tidak pernah bisa diubah lewat form ini.
 *  - Email divalidasi unik dengan pengecualian untuk user itu sendiri,
 *    agar menyimpan tanpa mengganti email tidak dianggap duplikat.
 */
class ProfileController extends Controller implements HasMiddleware
{
    /**
     * Daftarkan middleware controller (defense in depth; rute juga sudah
     * dilindungi group middleware 'auth' di routes/web.php).
     *
     * @return array<int, string>
     */
    public static function middleware(): array
    {
        return ['auth'];
    }

    /**
     * Tampilkan profil pengguna yang sedang login, beserta jumlah
     * booking yang pernah dibuatnya.
     */
    public function show(): View
    {
        $user = auth()->user()->loadCount('bookings');

        return view('profile.show', ['user' => $user]);
    }

    /**
     * Form ubah profil pengguna yang sedang login.
     */
    public function edit(): View
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    /**
     * Simpan perubahan profil (nama & email saja).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'  => 'required|string|max:120',
            'email' => 'required|email|max:190|unique:users,email,' . $user->id,
        ]);

        // Hanya field yang divalidasi yang disimpan
        // (tidak ada $request->all() / mass assignment dari input mentah).
        $user->update($data);

        return redirect()
            ->route('profil.show')
            ->with('sukses', 'Profil berhasil diperbarui 🌱');
    }
}
