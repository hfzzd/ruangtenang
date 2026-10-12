<?php

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
