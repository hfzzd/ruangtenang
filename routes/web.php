<?php

use App\Http\Controllers\ForumController;
use Illuminate\Support\Facades\Route;

// ---------------------------------------------------------------------------
// Halaman web RuangTenang.
//
// View Blade (resources/views/pages/*.blade.php) dibangun di commit frontend
// berikutnya. Route didefinisikan di sini agar struktur URL sudah final.
// ---------------------------------------------------------------------------

Route::view('/', 'pages.beranda')->name('beranda');
Route::view('/masuk', 'pages.masuk')->name('masuk');
Route::view('/jurnal', 'pages.jurnal')->name('jurnal');
Route::view('/mood', 'pages.mood')->name('mood');

// === Day-2: Forum ===
Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
Route::get('/forum/buat', [ForumController::class, 'create'])->name('forum.create');
Route::post('/forum', [ForumController::class, 'store'])->name('forum.store');
Route::get('/forum/{id}', [ForumController::class, 'show'])->name('forum.show')->whereNumber('id');
Route::post('/forum/{id}/balas', [ForumController::class, 'storeReply'])->name('forum.reply')->whereNumber('id');
