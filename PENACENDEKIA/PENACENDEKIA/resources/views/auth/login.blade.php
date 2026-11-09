@extends('layouts.app')

@section('title', 'Masuk ke Akun Anda — PT. Pena Cendikia')

@php
    $isStaff = request('portal') === 'staff' || ($isStaff ?? false);
@endphp

@push('styles')
<style>
    html, body {
        height: 100vh;
        overflow: hidden;
        background-color: #E8E8E4 !important;
    }

    .login-page-container {
        height: calc(100vh - 68px);
        max-height: calc(100vh - 68px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: clamp(8px, 1.5vh, 18px) 16px;
        box-sizing: border-box;
    }

    /* Main Rounded Outer Card (matching screenshot) */
    .login-main-card {
        width: 100%;
        max-width: 960px;
        max-height: calc(100vh - 90px);
        background-color: #F1F0EC;
        border-radius: 30px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08), 0 4px 16px rgba(0, 0, 0, 0.03);
        padding: clamp(16px, 2.2vw, 28px);
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.7);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* Top Brand Bar */
    .login-top-bar {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin-bottom: clamp(8px, 1.2vh, 16px);
    }

    .brand-logo-wrap {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: #171717;
    }
    .brand-logo-img {
        height: 32px;
        width: auto;
        object-fit: contain;
    }
    .brand-logo-text {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: 1.25rem;
        letter-spacing: -0.4px;
        color: #171717;
    }
    .brand-logo-dot {
        color: #F5A623;
    }

    /* Left Illustration Area */
    .illus-col {
        display: flex;
        align-items: center;
        justify-content: center;
        padding-right: clamp(10px, 2vw, 30px);
    }
    .illus-img {
        width: 100%;
        max-width: 420px;
        max-height: min(340px, 40vh);
        object-fit: contain;
        display: block;
        filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.04));
    }

    /* Right Floating White Login Card */
    .login-box-card {
        background-color: #ffffff;
        border-radius: 24px;
        padding: clamp(18px, 2.5vw, 30px);
        box-shadow: 0 16px 45px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.02);
    }

    .login-box-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        color: #171717;
        font-size: clamp(1.4rem, 1.9vw, 1.85rem);
        line-height: 1.15;
        margin-bottom: 2px;
    }
    .login-box-subtitle {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: #171717;
        font-size: clamp(1.2rem, 1.6vw, 1.5rem);
        line-height: 1.2;
        margin-bottom: 4px;
    }
    .login-box-desc {
        color: #71717a;
        font-size: 0.84rem;
        line-height: 1.4;
        margin-bottom: 16px;
    }

    /* Soft Rounded Input Groups (matching screenshot) */
    .soft-input-group {
        display: flex;
        align-items: center;
        background-color: #EFEFEA;
        border: 1.5px solid transparent;
        border-radius: 14px;
        padding: 4px 16px;
        transition: all 0.2s ease;
    }
    .soft-input-group:focus-within {
        background-color: #ffffff;
        border-color: #F5A623;
        box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.18);
    }
    .soft-input-group.has-error {
        border-color: #dc3545;
        background-color: #fff8f8;
    }
    .soft-input-field {
        width: 100%;
        border: none;
        background: transparent;
        padding: 11px 12px;
        font-size: 0.95rem;
        color: #171717;
        outline: none;
    }
    .soft-input-field::placeholder {
        color: #8e8e93;
        font-size: 0.92rem;
    }
    .btn-toggle-eye {
        border: none;
        background: transparent;
        padding: 0 4px;
        color: #8e8e93;
        cursor: pointer;
        font-size: 1.05rem;
        transition: color 0.15s ease;
    }
    .btn-toggle-eye:hover {
        color: #171717;
    }

    /* Masuk Button (Theme-adapted: #F5A623 / Golden Amber) */
    .btn-masuk-theme {
        background: #F5A623;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.3px;
        border-radius: 14px;
        padding: 13px 20px;
        width: 100%;
        border: none;
        box-shadow: 0 6px 20px rgba(245, 166, 35, 0.3);
        transition: all 0.22s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-masuk-theme:hover {
        background: #e09214;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(245, 166, 35, 0.4);
    }

    /* Google Button */
    .btn-google-soft {
        background-color: #ffffff;
        color: #27272a;
        font-weight: 600;
        font-size: 0.95rem;
        border: 1.5px solid #E4E4DE;
        border-radius: 14px;
        padding: 11px 20px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .btn-google-soft:hover {
        background-color: #F8F8F5;
        border-color: #cfcfc8;
        transform: translateY(-1px);
    }

    @media (max-width: 991.98px) {
        html, body {
            height: auto !important;
            overflow-y: auto !important;
        }
        .login-page-container {
            height: auto !important;
            min-height: 100vh;
            padding: 24px 16px;
        }
        .login-main-card {
            max-width: 480px;
            max-height: none;
            padding: 24px;
        }
    }
</style>
@endpush

@section('content')
<div class="login-page-container">
    <div class="login-main-card">
        
        <!-- Top Bar: Logo -->
        <div class="login-top-bar">
            <a href="{{ $isStaff ? route('login') : route('login', ['portal' => 'staff']) }}" class="brand-logo-wrap" title="{{ $isStaff ? 'Beralih ke Login Penulis (Author)' : 'Beralih ke Login Admin & Editor' }}">
                <img src="{{ asset('images/pena.png') }}" class="brand-logo-img" alt="Pena Cendikia">
                <span class="brand-logo-text">PenaCendekia<span class="brand-logo-dot">.</span></span>
            </a>
        </div>

        <!-- Main 2-Column Content -->
        <div class="row align-items-center g-4">
            <!-- Left Column: Illustration (matching screenshot) -->
            <div class="col-lg-6 illus-col">
                <img src="{{ asset('images/login-illustration.png') }}" class="illus-img" alt="Ilustrasi Penulis Pena Cendekia">
            </div>

            <!-- Right Column: Floating Login Box -->
            <div class="col-lg-6">
                <div class="login-box-card">
                    
                    @if($isStaff)
                        <!-- Heading Portal Admin & Editor -->
                        <h2 class="login-box-title">Halo!</h2>
                        <h3 class="login-box-subtitle">Portal Admin &amp; Editor</h3>
                        <p class="login-box-desc">Khusus tim administrator dan editor / desainer untuk mengelola penerbitan buku</p>
                    @else
                        <!-- Heading Author -->
                        <h2 class="login-box-title">Halo!</h2>
                        <h3 class="login-box-subtitle">Author</h3>
                        <p class="login-box-desc">Khusus penulis untuk mengajukan naskah dan memantau proses penerbitan buku bersama Penerbit Pena Cendekia</p>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-start gap-2 py-2 px-3 rounded-3 mb-3 small" role="alert" style="font-size: 12px;">
                            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                            <div>
                                {{ session('error') }}
                                @if(str_contains(session('error'), 'daftar'))
                                    <a href="{{ route('register') }}" class="fw-bold text-danger text-decoration-underline ms-1">Daftar sekarang</a>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 rounded-3 mb-3 small" role="alert" style="font-size: 12px;">
                            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="alert alert-info d-flex align-items-center gap-2 py-2 px-3 rounded-3 mb-3 small" role="alert" style="font-size: 12px;">
                            <i class="bi bi-info-circle-fill flex-shrink-0"></i>
                            <div>{{ session('info') }}</div>
                        </div>
                    @endif

                    @if($isStaff && (session('staff_lockout_active') || session('staff_lockout_seconds')))
                        @php
                            $lockoutSeconds = (int) (session('staff_lockout_seconds') ?? 180);
                            $showResetNow = $lockoutSeconds <= 60;
                        @endphp
                        <!-- Banner Jeda Keamanan (Salah 3x) -->
                        <div class="alert alert-warning border-0 rounded-4 p-3 mb-3" style="background: rgba(245, 158, 11, 0.12); border: 1.5px solid rgba(245, 158, 11, 0.35) !important;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-shield-exclamation text-warning fs-5"></i>
                                <strong class="text-dark small">Akses Staf Dijeda Demi Keamanan</strong>
                            </div>
                            <div class="small text-muted mb-2 ps-4" style="font-size: 11.5px;">
                                Kata sandi salah 3 kali. Coba lagi dalam: <span id="countdownTimer" class="fw-bold text-danger font-monospace fs-6">03:00</span>
                            </div>

                            <!-- Opsi Reset Password: Hanya Muncul Ketika Sisa Waktu <= 1 Menit (<= 60 Detik) -->
                            <div id="resetPasswordBox" class="pt-2 border-top" style="border-color: rgba(245, 158, 11, 0.25) !important; {{ $showResetNow ? 'display: block;' : 'display: none;' }}">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <span class="small text-muted" style="font-size: 11px;">Waktu tunggu tersisa &le; 1 menit:</span>
                                    <a href="{{ route('admin.password.request', ['email' => old('email', session('locked_email'))]) }}" class="btn btn-sm btn-dark rounded-3 px-3 py-1 fw-bold" style="font-size: 11.5px;">
                                        <i class="bi bi-key-fill text-warning me-1"></i> Reset Password
                                    </a>
                                </div>
                            </div>
                            <div id="resetPasswordPendingNotice" class="ps-4 small text-muted fst-italic pt-1" style="font-size: 11px; {{ $showResetNow ? 'display: none;' : 'display: block;' }}">
                                <i class="bi bi-clock-history me-1"></i> Opsi reset password akan terbuka otomatis saat waktu tunggu tersisa 1 menit.
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('login', [], false) }}" method="POST">
                        @csrf
                        <input type="hidden" name="portal" value="{{ $isStaff ? 'staff' : 'author' }}">
                        
                        <!-- Email / Username Input (Pill Soft-gray with envelope) -->
                        <div class="mb-3">
                            <div class="soft-input-group @error('email') has-error @enderror">
                                <i class="bi bi-person text-muted fs-5"></i>
                                <input type="text" name="email" id="email" class="soft-input-field" placeholder="{{ $isStaff ? 'Username atau Email (admin)' : 'Email atau Username Anda' }}" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1 ms-2" style="font-size: 12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Input (Pill Soft-gray with lock & eye toggle) -->
                        <div class="mb-3">
                            <div class="soft-input-group @error('password') has-error @enderror">
                                <i class="bi bi-lock text-muted fs-5"></i>
                                <input type="password" name="password" id="password" class="soft-input-field" placeholder="Kata Sandi" required>
                                <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility()" aria-label="Toggle password">
                                    <i class="bi bi-eye" id="toggleEyeIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1 ms-2" style="font-size: 12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me & Lupa Password -->
                        <div class="d-flex justify-content-between align-items-center mb-3 px-1 small">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" style="cursor: pointer;">
                                <label class="form-check-label text-muted" for="remember" style="font-size: 0.82rem; cursor: pointer;">
                                    Ingat saya
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-decoration-none fw-semibold" style="color: #F5A623; font-size: 0.82rem;">
                                Lupa kata sandi?
                            </a>
                        </div>

                        <!-- Tombol Masuk (Website brand theme color) -->
                        <button type="submit" class="btn-masuk-theme mb-3">
                            {{ $isStaff ? 'Masuk Portal Admin / Editor' : 'Masuk' }}
                        </button>

                        @if(!$isStaff)
                            <!-- Tombol Google Sign-In untuk Author -->
                            <a href="{{ route('auth.google') }}" class="btn-google-soft mb-4 text-decoration-none">
                                <svg width="18" height="18" viewBox="0 0 18 18" class="me-2" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.616z" fill="#4285F4"/>
                                    <path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/>
                                    <path d="M3.964 10.707c-.18-.54-.282-1.117-.282-1.707s.102-1.167.282-1.707V4.961H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.039l3.007-2.332z" fill="#FBBC05"/>
                                    <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.961L3.964 7.293C4.672 5.166 6.656 3.58 9 3.58z" fill="#EA4335"/>
                                </svg>
                                <span>Masuk dengan Google</span>
                            </a>

                            <!-- Footer Text: Daftar Akun -->
                            <div class="text-center small text-muted mb-0" style="font-size: 0.88rem;">
                                Belum punya akun? <a href="{{ route('register') }}" class="text-dark fw-bold text-decoration-none">Daftar</a>
                            </div>
                        @else
                            <div class="text-center small text-muted mb-0 pt-2" style="font-size: 0.85rem;">
                                <a href="{{ route('login') }}" class="text-dark fw-semibold text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Login Penulis (Author)
                                </a>
                            </div>
                        @endif
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility() {
        const passInput = document.getElementById('password');
        const eyeIcon = document.getElementById('toggleEyeIcon');
        if (passInput.type === 'password') {
            passInput.type = 'text';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            passInput.type = 'password';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    }

    @if(session('staff_lockout_seconds'))
    (function() {
        let remainingSeconds = {{ (int) session('staff_lockout_seconds') }};
        const timerElem = document.getElementById('countdownTimer');
        const resetBox = document.getElementById('resetPasswordBox');
        const pendingNotice = document.getElementById('resetPasswordPendingNotice');
        
        function updateTimer() {
            if (!timerElem) return;
            if (remainingSeconds <= 0) {
                timerElem.textContent = '00:00 (Silakan coba masuk kembali)';
                if (resetBox) resetBox.style.display = 'block';
                if (pendingNotice) pendingNotice.style.display = 'none';
                return;
            }
            const mins = Math.floor(remainingSeconds / 60);
            const secs = remainingSeconds % 60;
            timerElem.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
            
            // Munculkan opsi reset password tepat ketika sisa waktu <= 60 detik (sisa 1 menit)
            if (remainingSeconds <= 60) {
                if (resetBox) resetBox.style.display = 'block';
                if (pendingNotice) pendingNotice.style.display = 'none';
            }

            remainingSeconds--;
            setTimeout(updateTimer, 1000);
        }

        updateTimer();
    })();
    @endif
</script>
@endpush
