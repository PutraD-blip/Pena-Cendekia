<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmissionController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\DesignTaskController as AdminDesignTaskController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PaymentAccountController as AdminPaymentAccountController;

/*
|--------------------------------------------------------------------------
| Admin Routes (Bagian Khusus Administrator)
|--------------------------------------------------------------------------
| Menangani seluruh alur kerja operasional penerbitan:
| - Dashboard Admin & Statistik
| - Pengelolaan Naskah & Review
| - Penugasan Desain ke Tim Desainer
| - Verifikasi Pembayaran (DP & Pelunasan)
| - Manajemen Pengguna (Author, Designer, Admin)
| - Manajemen Rekening Bank Penerbit
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Pengelolaan Naskah (Submissions)
    Route::get('/submissions', [AdminSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [AdminSubmissionController::class, 'show'])->name('submissions.show');
    Route::post('/submissions/{submission}/review', [AdminSubmissionController::class, 'review'])->name('submissions.review');
    Route::post('/submissions/{submission}/printing', [AdminSubmissionController::class, 'markPrinting'])->name('submissions.printing');
    Route::post('/submissions/{submission}/complete', [AdminSubmissionController::class, 'markCompleted'])->name('submissions.complete');

    // Penugasan & Review Tugas Desain
    Route::post('/submissions/{submission}/assign-task', [AdminDesignTaskController::class, 'assign'])->name('tasks.assign');
    Route::post('/design-tasks/{task}/review', [AdminDesignTaskController::class, 'review'])->name('tasks.review');

    // Verifikasi Pembayaran
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');

    // Manajemen Pengguna
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // Rekening Pembayaran Penerbit
    Route::get('/payment-accounts', [AdminPaymentAccountController::class, 'index'])->name('paymentAccounts.index');
    Route::post('/payment-accounts', [AdminPaymentAccountController::class, 'store'])->name('paymentAccounts.store');
    Route::post('/payment-accounts/{account}/toggle-status', [AdminPaymentAccountController::class, 'toggleStatus'])->name('paymentAccounts.toggleStatus');
});
