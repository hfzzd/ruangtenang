<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Controller autentikasi web RuangTenang (login, daftar, logout).
 *
 * DESAIN DAY-4:
 *  - Menggunakan guard session web bawaan Laravel (Auth::attempt /
 *    Auth::login / Auth::logout); terpisah dari token API Sanctum
 *    yang dipakai endpoint jurnal & mood.
 *  - Setelah login/daftar, booking milik user bisa dikaitkan ke
 *    Auth::id() (bukan lagi hanya lookup by email).
 *
 * Keamanan:
 *  - Validasi ketat di login()/register(); hanya field tervalidasi
 *    yang dipakai (tidak ada $request->all() / mass assignment
 *    dari input mentah).
 *  - Kata sandi SELALU di-hash dengan Hash::make, tidak pernah
 *    disimpan atau dibandingkan dalam bentuk plaintext.
 *  - Session di-regenerate setelah login berhasil (mencegah
 *    session fixation); session di-invalidate + token CSRF
 *    di-regenerate saat logout.
 *  - Pesan error login dibuat generik ("Email atau kata sandi salah")
 *    agar tidak membocorkan email mana yang terdaftar (mencegah
 *    user enumeration).
 */
class AuthController extends Controller
{
    /**
     * Form masuk. Pengguna yang sudah login diarahkan ke beranda.
     */
    public function showLogin(): View
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.masuk');
    }

    /**
     * Proses masuk.
     */
    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email'    => 'required|email|max:190',
            'password' => 'required|string',
        ]);

        // Tanpa "remember me": sesi berakhir mengikuti lifetime session.
        if (! Auth::attempt($data)) {
            return back()
                ->withErrors(['email' => 'Email atau kata sandi salah.'])
                ->withInput();
        }

        // Cegah session fixation: buat ulang ID session setelah login.
        $request->session()->regenerate();

        return redirect()->intended('/');
    }

    /**
     * Proses keluar.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Form daftar akun baru. Pengguna yang sudah login diarahkan
     * ke beranda.
     */
    public function showRegister(): View
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.daftar');
    }

    /**
     * Proses pendaftaran akun baru, lalu langsung masuk.
     */
    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => 'required|string|max:120',
            'email'    => 'required|email|max:190|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return redirect('/')
            ->with('sukses', 'Selamat datang di RuangTenang! 🌱');
    }
}
