<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\PublicController;

/*
|--------------------------------------------------------------------------
| Public Web Routes (Bagian Tampilan Web Utama / Pengunjung)
|--------------------------------------------------------------------------
| Menangani halaman publik: Beranda (Home), Tentang Kami, Kontak,
| Syarat & Ketentuan / Unduhan, dan Portofolio.
*/

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/terms', [PublicController::class, 'terms'])->name('terms');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::get('/portofolio', [PublicController::class, 'portofolio'])->name('portofolio');
