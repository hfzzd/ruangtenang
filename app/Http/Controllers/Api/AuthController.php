<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Endpoint autentikasi RuangTenang.
 *
 *  - POST /api/auth/register : pendaftaran akun baru (+ token Sanctum).
 *  - POST /api/auth/login    : masuk dan dapatkan token Sanctum.
 *  - POST /api/auth/logout  : keluar, menghapus token saat ini.
 */
class AuthController extends Controller
{
    /**
     * Daftarkan akun pengguna baru dengan peran "user".
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            // 'role' tidak di-mass-assign: DB default 'user', hindari MassAssignmentException.
        ]);

        $token = $user->createToken('ruangtenang')->plainTextToken;

        return response()->json([
            'message' => 'Pendaftaran berhasil. Selamat datang di RuangTenang!',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * Masuk dan dapatkan token Sanctum baru.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email atau kata sandi salah.',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('ruangtenang')->plainTextToken;

        return response()->json([
            'message' => 'Berhasil masuk. Selamat datang kembali!',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    /**
     * Keluar dengan menghapus token Sanctum yang sedang dipakai.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Berhasil keluar. Sampai jumpa lagi!',
        ]);
    }
}
