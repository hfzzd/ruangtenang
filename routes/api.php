<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JournalController;
use App\Http\Controllers\Api\MoodController;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------------------
// RuangTenang API
//
//   POST /api/auth/register   Pendaftaran akun baru (+ token Sanctum).
//   POST /api/auth/login      Masuk dan dapatkan token Sanctum.
//   POST /api/auth/logout     Keluar (hapus token saat ini, butuh auth).
//
//   GET  /api/journal/prompts Daftar prompt refleksi berbahasa Indonesia.
//   GET  /api/journal         Daftar entri jurnal milik pengguna.
//   POST /api/journal         Buat entri jurnal baru.
//   GET  /api/journal/{id}    Detail satu entri jurnal.
//   PUT  /api/journal/{id}    Ubah entri jurnal.
//   DELETE /api/journal/{id}  Hapus entri jurnal.
//
//   GET  /api/mood/trend      Rata-rata mood harian dalam N hari terakhir.
//   GET  /api/mood            Riwayat check-in mood milik pengguna.
//   POST /api/mood            Check-in mood harian (satu hari satu entri).
//   GET  /api/mood/{id}       Detail satu entri mood.
//   DELETE /api/mood/{id}     Hapus entri mood.
// ---------------------------------------------------------------------------

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/journal/prompts', [JournalController::class, 'prompts']);
    Route::apiResource('journal', JournalController::class);
    Route::get('/mood/trend', [MoodController::class, 'trend']);
    Route::apiResource('mood', MoodController::class)
        ->only(['index', 'store', 'show', 'destroy']);
});
