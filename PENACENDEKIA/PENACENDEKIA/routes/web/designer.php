<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Designer\DashboardController as DesignerDashboardController;
use App\Http\Controllers\Designer\DesignTaskController as DesignerTaskController;

/*
|--------------------------------------------------------------------------
| Designer Routes (Bagian Khusus Tim Desainer)
|--------------------------------------------------------------------------
| Menangani portal kerja desainer:
| - Dashboard Tugas Layout & Sampul Buku
| - Memulai Pengerjaan Tugas
| - Mengunggah Hasil Desain (Cover / Layout) untuk Direview
*/

Route::middleware(['auth', 'role:designer'])->prefix('designer')->name('designer.')->group(function () {
    Route::get('/dashboard', [DesignerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tasks/{task}', [DesignerTaskController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{task}/start', [DesignerTaskController::class, 'start'])->name('tasks.start');
    Route::post('/tasks/{task}/upload', [DesignerTaskController::class, 'uploadResult'])->name('tasks.upload');
});
