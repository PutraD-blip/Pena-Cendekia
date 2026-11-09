@extends('layouts.app')

@section('title', 'Lupa Kata Sandi — PT. Pena Cendekia Pustaka')

@push('styles')
<style>
    html, body {
        min-height: 100vh;
        background-color: #E8E8E4 !important;
    }

    .forgot-page-container {
        min-height: calc(100vh - 68px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px 16px;
        box-sizing: border-box;
    }

    .forgot-main-card {
        width: 100%;
        max-width: 920px;
        background-color: #F1F0EC;
        border-radius: 30px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08), 0 4px 16px rgba(0, 0, 0, 0.03);
        padding: clamp(20px, 2.5vw, 36px);
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.7);
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

    .illus-col {
        display: flex;
        align-items: center;
        justify-content: center;
        padding-right: clamp(10px, 2vw, 24px);
    }
    .illus-img {
        width: 100%;
        max-width: 380px;
        max-height: 320px;
        object-fit: contain;
        display: block;
        filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.04));
    }

    .forgot-box-card {
        background-color: #ffffff;
        border-radius: 24px;
        padding: clamp(20px, 2.8vw, 32px);
        box-shadow: 0 16px 45px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.02);
    }

    .soft-input-group {
        display: flex;
        align-items: center;
        background-color: #ECEAE5;
        border-radius: 999px;
        padding: 6px 16px;
        transition: all 0.2s ease;
        border: 1.5px solid transparent;
    }
    .soft-input-group:focus-within {
        background-color: #ffffff;
        border-color: #F5A623;
        box-shadow: 0 0 0 4px rgba(245, 166, 35, 0.15);
    }
    .soft-input-group.has-error {
        border-color: #ef4444;
        background-color: #fef2f2;
    }
    .soft-input-field {
        border: none;
        background: transparent;
        outline: none;
        width: 100%;
        padding: 8px 12px;
        font-size: 0.92rem;
        color: #1f2937;
        font-family: 'Poppins', sans-serif;
    }
    .soft-input-field::placeholder {
        color: #8c8c88;
        font-size: 0.9rem;
    }

    .btn-toggle-eye {
        border: none;
        background: transparent;
        color: #8c8c88;
        padding: 4px 6px;
        cursor: pointer;
        outline: none;
    }
    .btn-toggle-eye:hover {
        color: #F5A623;
    }

    .btn-submit-theme {
        width: 100%;
        padding: 12px;
        border-radius: 999px;
        border: none;
        background: linear-gradient(135deg, #F5A623 0%, #E09015 100%);
        color: #111827;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 15px rgba(245, 166, 35, 0.35);
    }
    .btn-submit-theme:hover {
        background: linear-gradient(135deg, #e5971a 0%, #cb7f0d 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(245, 166, 35, 0.45);
    }
</style>
@endpush

@section('content')
<div class="forgot-page-container">
    <div class="forgot-main-card">
        <!-- Top Brand Bar -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <a href="{{ route('home') }}" class="brand-logo-wrap">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Pena Cendekia" class="brand-logo-img" onerror="this.style.display='none'">
                <span class="brand-logo-text">Pena Cendekia<span class="brand-logo-dot">.</span></span>
            </a>
            <a href="{{ route('login') }}" class="text-decoration-none small text-muted fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
            </a>
        </div>

        <div class="row align-items-center g-4">
            <!-- Left Illustration -->
            <div class="col-lg-5 d-none d-lg-flex illus-col">
                <img src="{{ asset('images/login-illus.png') }}" alt="Ilustrasi Pena Cendekia" class="illus-img" onerror="this.src='{{ asset('images/about-team.png') }}'">
            </div>

            <!-- Right Reset Form -->
            <div class="col-lg-7 col-12">
                <div class="forgot-box-card">
                    <div class="mb-3">
                        <span class="badge px-3 py-1 rounded-pill mb-2" style="background: rgba(245, 166, 35, 0.15); color: #B45309; font-weight: 600; font-size: 12px;">
                            <i class="bi bi-shield-lock-fill me-1"></i> Pemulihan Kata Sandi
                        </span>
                        <h4 class="fw-bold mb-1" style="font-family: 'Poppins', sans-serif; color: #111827;">Lupa Kata Sandi?</h4>
                        <p class="text-muted small mb-0">
                            Masukkan email terdaftar dan kata sandi baru untuk memperbarui kredensial akun Anda.
                        </p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success border-0 rounded-3 small py-2 px-3 mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill fs-5"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-3 small py-2 px-3 mb-3 d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    <form action="{{ route('password.update.general') }}" method="POST">
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary mb-1">Email Terdaftar</label>
                            <div class="soft-input-group @error('email') has-error @enderror">
                                <i class="bi bi-envelope text-muted fs-5"></i>
                                <input type="email" name="email" id="email" class="soft-input-field" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1 ms-2" style="font-size: 12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password Input -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary mb-1">Kata Sandi Baru</label>
                            <div class="soft-input-group @error('password') has-error @enderror">
                                <i class="bi bi-lock text-muted fs-5"></i>
                                <input type="password" name="password" id="password" class="soft-input-field" placeholder="Minimal 6 karakter" required>
                                <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility('password', 'toggleEyeIcon1')" aria-label="Toggle password">
                                    <i class="bi bi-eye" id="toggleEyeIcon1"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1 ms-2" style="font-size: 12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary mb-1">Konfirmasi Kata Sandi Baru</label>
                            <div class="soft-input-group">
                                <i class="bi bi-shield-check text-muted fs-5"></i>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="soft-input-field" placeholder="Ulangi kata sandi baru" required>
                                <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility('password_confirmation', 'toggleEyeIcon2')" aria-label="Toggle password">
                                    <i class="bi bi-eye" id="toggleEyeIcon2"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn-submit-theme mb-3">
                            <i class="bi bi-key-fill me-1"></i> Perbarui Kata Sandi
                        </button>

                        <div class="text-center small text-muted">
                            Ingat kata sandi Anda? <a href="{{ route('login') }}" class="text-dark fw-bold text-decoration-none">Masuk</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, iconId) {
        const passInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);
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
</script>
@endsection
