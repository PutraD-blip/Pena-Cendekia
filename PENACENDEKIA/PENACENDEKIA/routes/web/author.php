<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Author\DashboardController as AuthorDashboardController;
use App\Http\Controllers\Author\ProfileController as AuthorProfileController;
use App\Http\Controllers\Author\SubmissionController as AuthorSubmissionController;
use App\Http\Controllers\Author\PaymentController as AuthorPaymentController;

/*
|--------------------------------------------------------------------------
| Author Routes (Bagian Khusus Penulis / Author)
|--------------------------------------------------------------------------
| Menangani portal mandiri bagi penulis:
| - Dashboard Penulis & Status Penerbitan
| - Pengisian Biodata Penulis / NIK
| - Pengajuan Naskah Baru & Revisi
| - Pembayaran DP & Pelunasan serta Upload Bukti Transfer
*/

Route::middleware(['auth', 'role:author'])->prefix('author')->name('author.')->group(function () {
    Route::get('/dashboard', [AuthorDashboardController::class, 'index'])->name('dashboard');

    // Profil Penulis & NIK
    Route::get('/profile', [AuthorProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [AuthorProfileController::class, 'update'])->name('profile.update');

    // Pengajuan & Pemantauan Naskah
    Route::get('/submissions/create', [AuthorSubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/submissions', [AuthorSubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/submissions/{submission}', [AuthorSubmissionController::class, 'show'])->name('submissions.show');
    Route::get('/submissions/{submission}/edit', [AuthorSubmissionController::class, 'edit'])->name('submissions.edit');
    Route::put('/submissions/{submission}', [AuthorSubmissionController::class, 'update'])->name('submissions.update');

    // Pembayaran & Bukti Transfer
    Route::get('/submissions/{submission}/payment', [AuthorPaymentController::class, 'show'])->name('payments.show');
    Route::post('/submissions/{submission}/payment', [AuthorPaymentController::class, 'uploadProof'])->name('payments.upload');
});
