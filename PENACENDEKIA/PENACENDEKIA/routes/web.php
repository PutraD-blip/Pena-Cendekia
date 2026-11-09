<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Author\DashboardController as AuthorDashboardController;
use App\Http\Controllers\Author\ProfileController as AuthorProfileController;
use App\Http\Controllers\Author\SubmissionController as AuthorSubmissionController;
use App\Http\Controllers\Author\PaymentController as AuthorPaymentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmissionController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\DesignTaskController as AdminDesignTaskController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PaymentAccountController as AdminPaymentAccountController;
use App\Http\Controllers\Admin\FeaturedBookController as AdminFeaturedBookController;
use App\Http\Controllers\Admin\EbookController as AdminEbookController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Designer\DashboardController as DesignerDashboardController;
use App\Http\Controllers\Designer\DesignTaskController as DesignerTaskController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/terms', [PublicController::class, 'terms'])->name('terms');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::get('/portofolio', [PublicController::class, 'portofolio'])->name('portofolio');
Route::get('/buku/{id}', [PublicController::class, 'detailBuku'])->name('buku.detail');
Route::get('/portofolio/{id}', [PublicController::class, 'detailBuku'])->name('portofolio.detail');
Route::get('/template-buku', [PublicController::class, 'templateBuku'])->name('template.buku');
Route::get('/template-haki', [PublicController::class, 'templateHaki'])->name('template.haki');
Route::get('/printing-dan-offset', [PublicController::class, 'printingOffset'])->name('printing.offset');
Route::get('/cetak-buku-pod-a5.html', [PublicController::class, 'printingOffset']);

/*
| Layanan Routes (Dropdown Service)
*/
Route::prefix('layanan')->name('layanan.')->group(function () {
    Route::get('/penerbitan-buku', [PublicController::class, 'layananPenerbitan'])->name('penerbitan');
    Route::get('/konversi-karya-ilmiah', [PublicController::class, 'layananKonversi'])->name('konversi');
    Route::get('/cetak-buku', [PublicController::class, 'layananCetak'])->name('cetak');
    Route::get('/editing-naskah', [PublicController::class, 'layananEditing'])->name('editing');
    Route::get('/pengurusan-hki', [PublicController::class, 'layananHki'])->name('hki');
});

// Friendly aliases
Route::get('/penerbitan-buku', [PublicController::class, 'layananPenerbitan']);
Route::get('/konversi-karya-ilmiah', [PublicController::class, 'layananKonversi']);
Route::get('/cetak-buku', [PublicController::class, 'layananCetak']);
Route::get('/editing-naskah', [PublicController::class, 'layananEditing']);
Route::get('/pengurusan-hki', [PublicController::class, 'layananHki']);

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Staff (Admin & Editor) Password Reset Routes
Route::get('/admin/reset-password', [AuthController::class, 'showStaffResetPasswordForm'])->name('admin.password.request');
Route::post('/admin/reset-password/verify', [AuthController::class, 'verifyStaffResetIdentifier'])->name('admin.password.verify');
Route::post('/admin/reset-password/update', [AuthController::class, 'updateStaffPassword'])->name('admin.password.update');
Route::post('/admin/reset-password/cancel', [AuthController::class, 'cancelStaffReset'])->name('admin.password.cancel');

// General Password Reset (Lupa Password untuk Penulis dan Pengguna Umum)
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'handleForgotPassword'])->name('password.update.general');

// Google OAuth (Login, Register & Link Akun)
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/auth/google/cancel', [AuthController::class, 'cancelGoogleRegistration'])->name('auth.google.cancel');

// Friendly Aliases for Admin, Designer, and Author Portals
Route::get('/admin/login', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        auth()->logout();
    }
    return redirect()->route('login', ['portal' => 'staff']);
})->name('admin.login');

Route::get('/admin', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        auth()->logout();
    }
    return redirect()->route('login', ['portal' => 'staff']);
});

Route::get('/admin-login', function () {
    return redirect()->route('admin.login');
});

Route::get('/login/admin', function () {
    return redirect()->route('admin.login');
});

Route::get('/designer/login', function () {
    if (auth()->check()) {
        if (auth()->user()->isDesigner()) {
            return redirect()->route('designer.dashboard');
        }
        auth()->logout();
    }
    return redirect()->route('login', ['portal' => 'staff']);
})->name('designer.login');

Route::get('/designer', function () {
    if (auth()->check()) {
        if (auth()->user()->isDesigner()) {
            return redirect()->route('designer.dashboard');
        }
        auth()->logout();
    }
    return redirect()->route('login', ['portal' => 'staff']);
});

Route::get('/author/login', function () {
    return redirect()->route('login');
})->name('author.login');

Route::get('/author', function () {
    if (auth()->check()) {
        if (auth()->user()->isAuthor()) {
            return redirect()->route('author.dashboard');
        }
        auth()->logout();
    }
    return redirect()->route('login');
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Protected File Serving (Authenticated Users with role-based checks)
|--------------------------------------------------------------------------
*/
Route::get('/storage/private/{type}/{filename}', [FileController::class, 'show'])
    ->name('file.show')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Common Notifications (All Authenticated Roles)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/clear-read', [NotificationController::class, 'clearRead'])->name('notifications.clearRead');
});

/*
|--------------------------------------------------------------------------
| Author Routes (Role: author)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:author'])->prefix('author')->name('author.')->group(function () {
    Route::get('/dashboard', [AuthorDashboardController::class, 'index'])->name('dashboard');

    // Profile & NIK Completion
    Route::get('/profile', [AuthorProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [AuthorProfileController::class, 'update'])->name('profile.update');

    // Submissions
    Route::get('/submissions/create', [AuthorSubmissionController::class, 'create'])->name('submissions.create');
    Route::post('/submissions', [AuthorSubmissionController::class, 'store'])->name('submissions.store');
    Route::get('/submissions/{submission}', [AuthorSubmissionController::class, 'show'])->name('submissions.show');
    Route::get('/submissions/{submission}/edit', [AuthorSubmissionController::class, 'edit'])->name('submissions.edit');
    Route::put('/submissions/{submission}', [AuthorSubmissionController::class, 'update'])->name('submissions.update');

    // Payments
    Route::get('/submissions/{submission}/payment', [AuthorPaymentController::class, 'show'])->name('payments.show');
    Route::post('/submissions/{submission}/payment', [AuthorPaymentController::class, 'uploadProof'])->name('payments.upload');

    // Design Task Approval & Revision by Author
    Route::post('/design-tasks/{task}/approve', [AuthorSubmissionController::class, 'approveDesign'])->name('tasks.approve');
    Route::post('/design-tasks/{task}/revision', [AuthorSubmissionController::class, 'revisionDesign'])->name('tasks.revision');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Submissions
    Route::get('/submissions', [AdminSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [AdminSubmissionController::class, 'show'])->name('submissions.show');
    Route::post('/submissions/{submission}/review', [AdminSubmissionController::class, 'review'])->name('submissions.review');
    Route::post('/submissions/{submission}/printing', [AdminSubmissionController::class, 'markPrinting'])->name('submissions.printing');
    Route::post('/submissions/{submission}/complete', [AdminSubmissionController::class, 'markCompleted'])->name('submissions.complete');

    // Design Task Assignment & Approval
    Route::post('/submissions/{submission}/assign-task', [AdminDesignTaskController::class, 'assign'])->name('tasks.assign');
    Route::post('/design-tasks/{task}/review', [AdminDesignTaskController::class, 'review'])->name('tasks.review');

    // Payments Verification
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/verify', [AdminPaymentController::class, 'verify'])->name('payments.verify');

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // Bank Account Management
    Route::get('/payment-accounts', [AdminPaymentAccountController::class, 'index'])->name('paymentAccounts.index');
    Route::post('/payment-accounts', [AdminPaymentAccountController::class, 'store'])->name('paymentAccounts.store');
    Route::post('/payment-accounts/{account}/toggle-status', [AdminPaymentAccountController::class, 'toggleStatus'])->name('paymentAccounts.toggleStatus');

    // Portofolio Buku Pilihan CRUD
    Route::get('/featured-books', [AdminFeaturedBookController::class, 'index'])->name('featuredBooks.index');
    Route::get('/featured-books/create', [AdminFeaturedBookController::class, 'create'])->name('featuredBooks.create');
    Route::post('/featured-books', [AdminFeaturedBookController::class, 'store'])->name('featuredBooks.store');
    Route::get('/featured-books/{featuredBook}/edit', [AdminFeaturedBookController::class, 'edit'])->name('featuredBooks.edit');
    Route::put('/featured-books/{featuredBook}', [AdminFeaturedBookController::class, 'update'])->name('featuredBooks.update');
    Route::delete('/featured-books/{featuredBook}', [AdminFeaturedBookController::class, 'destroy'])->name('featuredBooks.destroy');
    Route::post('/featured-books/{featuredBook}/toggle-status', [AdminFeaturedBookController::class, 'toggleStatus'])->name('featuredBooks.toggleStatus');

    // Katalog E-Book CRUD & Manajemen File
    Route::get('/ebooks', [AdminEbookController::class, 'index'])->name('ebooks.index');
    Route::get('/ebooks/create', [AdminEbookController::class, 'create'])->name('ebooks.create');
    Route::post('/ebooks', [AdminEbookController::class, 'store'])->name('ebooks.store');
    Route::get('/ebooks/{ebook}/edit', [AdminEbookController::class, 'edit'])->name('ebooks.edit');
    Route::put('/ebooks/{ebook}', [AdminEbookController::class, 'update'])->name('ebooks.update');
    Route::delete('/ebooks/{ebook}', [AdminEbookController::class, 'destroy'])->name('ebooks.destroy');
    Route::post('/ebooks/{ebook}/toggle-status', [AdminEbookController::class, 'toggleStatus'])->name('ebooks.toggleStatus');
    Route::get('/ebooks/{ebook}/download', [AdminEbookController::class, 'download'])->name('ebooks.download');
    Route::get('/ebooks/{ebook}/preview', [AdminEbookController::class, 'preview'])->name('ebooks.preview');

    // Manajemen Testimoni Penulis CRUD (Terpisah)
    Route::get('/testimonials', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('/testimonials/create', [AdminTestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [AdminTestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/{testimonial}/edit', [AdminTestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::put('/testimonials/{testimonial}', [AdminTestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');
    Route::post('/testimonials/{testimonial}/toggle-status', [AdminTestimonialController::class, 'toggleStatus'])->name('testimonials.toggleStatus');

    // Log Aktivitas Login & Keamanan (Admin & Author)
    Route::get('/activity-logs', [AdminActivityLogController::class, 'index'])->name('activityLogs.index');
});

/*
|--------------------------------------------------------------------------
| Designer Routes (Role: designer)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:designer'])->prefix('designer')->name('designer.')->group(function () {
    Route::get('/dashboard', [DesignerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tasks/{task}', [DesignerTaskController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{task}/start', [DesignerTaskController::class, 'start'])->name('tasks.start');
    Route::post('/tasks/{task}/upload', [DesignerTaskController::class, 'uploadResult'])->name('tasks.upload');
    Route::post('/submissions/{submission}/finish-printing', [DesignerTaskController::class, 'finishPrinting'])->name('submissions.finish-printing');
});
