<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PsikologController;
use App\Http\Controllers\BookingController;
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

// === Day-2: Direktori Psikolog ===
Route::get('/psikolog', [PsikologController::class, 'index'])->name('psikolog.index');
Route::get('/psikolog/{id}', [PsikologController::class, 'show'])->name('psikolog.show');

// === Day-2: Booking ===
Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
Route::get('/booking/buat', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/{id}', [BookingController::class, 'show'])->name('booking.show');

// === Day-3: Artikel ===
Route::get('/artikel', [ArticleController::class, 'index'])->name('artikel.index');
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('artikel.show');

// === Day-3: Aksi booking ===
Route::post('/booking/{id}/batal', [BookingController::class, 'cancel'])->name('booking.cancel')->whereNumber('id');
Route::get('/booking/{id}/ubah-jadwal', [BookingController::class, 'editSchedule'])->name('booking.editSchedule')->whereNumber('id');
Route::patch('/booking/{id}/ubah-jadwal', [BookingController::class, 'updateSchedule'])->name('booking.updateSchedule')->whereNumber('id');
