@extends('layouts.app')

@section('title', 'Reset Password Admin & Editor — PT. Pena Cendekia')

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
        font-size: clamp(1.3rem, 1.8vw, 1.65rem);
        line-height: 1.2;
        margin-bottom: 2px;
    }
    .login-box-subtitle {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        color: #D97706;
        font-size: clamp(1rem, 1.3vw, 1.25rem);
        line-height: 1.2;
        margin-bottom: 6px;
    }
    .login-box-desc {
        color: #71717a;
        font-size: 0.84rem;
        line-height: 1.4;
        margin-bottom: 16px;
    }

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
    .btn-toggle-eye {
        border: none;
        background: transparent;
        padding: 0 4px;
        color: #8e8e93;
        cursor: pointer;
        font-size: 1.05rem;
        transition: color 0.15s ease;
    }

    .btn-masuk-theme {
        background: #F5A623;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.3px;
        border-radius: 14px;
        padding: 12px 20px;
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

    .verified-badge-card {
        background: #F8FAF9;
        border: 1.5px solid #10B981;
        border-radius: 14px;
        padding: 12px 14px;
        margin-bottom: 16px;
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
            <a href="{{ route('admin.login') }}" class="brand-logo-wrap" title="Kembali ke Login Staf">
                <img src="{{ asset('images/pena.png') }}" class="brand-logo-img" alt="Pena Cendikia">
                <span class="brand-logo-text">PenaCendekia<span class="brand-logo-dot">.</span></span>
            </a>
        </div>

        <div class="row align-items-center g-4">
            <!-- Left Illustration -->
            <div class="col-lg-6 illus-col">
                <img src="{{ asset('images/login-illustration.png') }}" class="illus-img" alt="Ilustrasi Reset Kata Sandi">
            </div>

            <!-- Right Reset Box -->
            <div class="col-lg-6">
                <div class="login-box-card">
                    <h2 class="login-box-title">Pemulihan Akun</h2>
                    <h3 class="login-box-subtitle">Super Admin &amp; Editor</h3>

                    @if(session('error'))
                        <div class="alert alert-danger d-flex align-items-start gap-2 py-2 px-3 rounded-3 mb-3 small" style="font-size: 12px;">
                            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 mt-1"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success d-flex align-items-start gap-2 py-2 px-3 rounded-3 mb-3 small" style="font-size: 12px;">
                            <i class="bi bi-check-circle-fill flex-shrink-0 mt-1"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if(!$staffUser)
                        <!-- STEP 1: VERIFIKASI USERNAME ATAU EMAIL TERDAFTAR -->
                        <p class="login-box-desc">
                            Masukkan username (nama) atau email resmi yang terdaftar di sistem. Akses ganti kata sandi hanya diberikan jika identitas tercatat sah sebagai <strong>Super Admin</strong> atau <strong>Editor</strong>.
                        </p>

                        <form action="{{ route('admin.password.verify') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="identifier" class="form-label text-dark fw-bold small mb-1" style="font-size: 12px;">
                                    Username atau Email Terdaftar
                                </label>
                                <div class="soft-input-group @error('identifier') has-error @enderror">
                                    <i class="bi bi-person-badge text-muted fs-5"></i>
                                    <input type="text" name="identifier" id="identifier" class="soft-input-field" placeholder="Contoh: Super Admin atau admin@penacendikia.com" value="{{ old('identifier', $defaultIdentifier) }}" required autofocus>
                                </div>
                                @error('identifier')
                                    <div class="text-danger small mt-1 ms-2" style="font-size: 12px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn-masuk-theme mb-3">
                                <i class="bi bi-shield-check me-2"></i> Verifikasi Identitas Staf
                            </button>

                            <div class="text-center small pt-2">
                                <a href="{{ route('admin.login') }}" class="text-dark fw-semibold text-decoration-none" style="font-size: 0.85rem;">
                                    <i class="bi bi-arrow-left me-1"></i> Batal &amp; Kembali ke Login
                                </a>
                            </div>
                        </form>

                    @else
                        <!-- STEP 2: FORMULIR GANTI PASSWORD BARU -->
                        <div class="verified-badge-card">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                <strong class="text-dark small">Identitas Terverifikasi</strong>
                                <span class="badge {{ $staffUser->role === 'admin' ? 'bg-warning text-dark' : 'bg-primary' }} ms-auto" style="font-size: 10px;">
                                    {{ $staffUser->role === 'admin' ? 'Super Admin' : 'Editor / Desainer' }}
                                </span>
                            </div>
                            <div class="text-muted small ps-4" style="font-size: 11.5px;">
                                Akun: <strong>{{ $staffUser->name }}</strong> &bull; {{ $staffUser->email }}
                            </div>
                        </div>

                        <p class="login-box-desc mb-3">
                            Tentukan kata sandi baru untuk akun Anda. Minimal 6 karakter.
                        </p>

                        <form action="{{ route('admin.password.update') }}" method="POST">
                            @csrf
                            
                            <!-- Password Baru -->
                            <div class="mb-3">
                                <label for="password" class="form-label text-dark fw-bold small mb-1" style="font-size: 12px;">
                                    Kata Sandi Baru
                                </label>
                                <div class="soft-input-group @error('password') has-error @enderror">
                                    <i class="bi bi-lock text-muted fs-5"></i>
                                    <input type="password" name="password" id="password" class="soft-input-field" placeholder="Masukkan kata sandi baru" required autofocus>
                                    <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility('password', 'eye1')">
                                        <i class="bi bi-eye" id="eye1"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1 ms-2" style="font-size: 12px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Konfirmasi Password Baru -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label text-dark fw-bold small mb-1" style="font-size: 12px;">
                                    Konfirmasi Kata Sandi Baru
                                </label>
                                <div class="soft-input-group">
                                    <i class="bi bi-shield-lock text-muted fs-5"></i>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="soft-input-field" placeholder="Ketik ulang kata sandi baru" required>
                                    <button type="button" class="btn-toggle-eye" onclick="togglePasswordVisibility('password_confirmation', 'eye2')">
                                        <i class="bi bi-eye" id="eye2"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="btn-masuk-theme mb-3">
                                <i class="bi bi-key-fill me-2"></i> Simpan &amp; Perbarui Kata Sandi
                            </button>

                            <div class="text-center small pt-1">
                                <a href="{{ route('admin.password.cancel') }}" class="text-muted fw-semibold text-decoration-none" style="font-size: 0.85rem;" onclick="event.preventDefault(); document.getElementById('cancelResetForm').submit();">
                                    Batal &amp; Kembali ke Login
                                </a>
                            </div>
                        </form>

                        <form id="cancelResetForm" action="{{ route('admin.password.cancel') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(fieldId, iconId) {
        const input = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endpush
