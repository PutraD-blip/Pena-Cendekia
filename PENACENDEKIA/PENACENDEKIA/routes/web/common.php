<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Common / Shared Routes (Bagian Bersama & Fitur Pendukung)
|--------------------------------------------------------------------------
| Menangani fitur-fitur yang digunakan lintas role:
| - Akses File Storage Terproteksi (Naskah, Bukti Bayar, File Desain)
| - Notifikasi Real-time Sistem
*/

// File Storage Terproteksi (Dengan Validasi Role & Kepemilikan)
Route::get('/storage/private/{type}/{filename}', [FileController::class, 'show'])
    ->name('file.show')
    ->middleware('auth');

// Notifikasi Sistem
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});
