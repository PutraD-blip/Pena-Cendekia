@extends('layouts.app')

@section('title', 'Daftar Akun Penulis — PT. Pena Cendikia')

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
        padding: clamp(8px, 1.4vh, 18px) 16px;
        box-sizing: border-box;
    }

    /* Main Rounded Outer Card (matching login screenshot) */
    .login-main-card {
        width: 100%;
        max-width: 960px;
        max-height: calc(100vh - 86px);
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
        margin-bottom: clamp(6px, 1vh, 14px);
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

    /* Right Floating White Box Card */
    .login-box-card {
        background-color: #ffffff;
        border-radius: 24px;
        padding: clamp(16px, 2.3vw, 28px);
        box-shadow: 0 16px 45px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.02);
        position: relative;
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
        font-size: 0.83rem;
        line-height: 1.4;
        margin-bottom: 14px;
    }

    /* Soft Rounded Input Groups */
    .soft-input-group {
        display: flex;
        align-items: center;
        background-color: #EFEFEA;
        border: 1.5px solid transparent;
        border-radius: 14px;
        padding: 3px 14px;
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
        padding: 9px 10px;
        font-size: 0.9rem;
        color: #171717;
        outline: none;
    }
    .soft-input-field::placeholder {
        color: #8e8e93;
        font-size: 0.86rem;
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

    /* Action Button (Theme-adapted: #F5A623 / Golden Amber) */
    .btn-masuk-theme {
        background: #F5A623;
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.98rem;
        letter-spacing: 0.3px;
        border-radius: 14px;
        padding: 11px 20px;
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
        font-size: 0.94rem;
        border: 1.5px solid #E4E4DE;
        border-radius: 14px;
        padding: 10px 20px;
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

    /* Interactive Floating Password Pop-up Bubble */
    .password-rules-popup {
        position: absolute;
        bottom: calc(100% + 10px);
        left: 0;
        width: 290px;
        background: #ffffff;
        border-radius: 14px;
        padding: 12px 14px;
        box-shadow: 0 14px 35px rgba(0, 0, 0, 0.15), 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid #E2E8F0;
        z-index: 1050;
        display: none;
        animation: popupFadeSlide 0.22s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }
    .password-rules-popup::after {
        content: '';
        position: absolute;
        bottom: -7px;
        left: 28px;
        width: 14px;
        height: 14px;
        background: #ffffff;
        border-right: 1px solid #E2E8F0;
        border-bottom: 1px solid #E2E8F0;
        transform: rotate(45deg);
    }
    @keyframes popupFadeSlide {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .password-rule-item {
        font-size: 11.5px;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
        color: #64748B;
        transition: color 0.18s ease;
    }
    .password-rule-item i {
        font-size: 13px;
        transition: all 0.18s ease;
    }
    .password-rule-item.valid {
        color: #16A34A !important;
        font-weight: 600;
    }
    .password-rule-item.valid i {
        color: #16A34A;
    }

    @media (max-width: 991.98px) {
        html, body {
            height: auto !important;
            overflow-y: auto !important;
        }
        .illus-col {
            display: none;
        }
        .login-page-container {
            height: auto !important;
            min-height: calc(100vh - 68px);
            padding: 24px 16px;
        }
        .login-main-card {
            max-width: 520px;
            max-height: none;
            padding: 24px;
        }
        .password-rules-popup {
            bottom: auto;
            top: calc(100% + 8px);
        }
        .password-rules-popup::after {
            bottom: auto;
            top: -7px;
            border-right: none;
            border-bottom: none;
            border-left: 1px solid #E2E8F0;
            border-top: 1px solid #E2E8F0;
        }
    }
</style>
@endpush

@section('content')
<div class="login-page-container">
    <div class="login-main-card">
        
        <!-- Top Bar: Logo -->
        <div class="login-top-bar">
            <a href="{{ route('login') }}" class="brand-logo-wrap" title="Pena Cendekia">
                <img src="{{ asset('images/pena.png') }}" class="brand-logo-img" alt="Pena Cendikia">
                <span class="brand-logo-text">PenaCendekia<span class="brand-logo-dot">.</span></span>
            </a>
        </div>

        <!-- Main 2-Column Content -->
        <div class="row align-items-center g-4">
            <!-- Left Column: Illustration -->
            <div class="col-lg-6 illus-col">
                <img src="{{ asset('images/login-illustration.png') }}" class="illus-img" alt="Ilustrasi Penulis Pena Cendekia">
            </div>

            <!-- Right Column: Floating Registration Box -->
            <div class="col-lg-6">
                <div class="login-box-card">
                    
                    <h2 class="login-box-title">Halo!</h2>
                    <h3 class="login-box-subtitle">Daftar Author</h3>
                    <p class="login-box-desc">Daftarkan akun penulis Anda untuk mulai menerbitkan karya buku bersama Penerbit Pena Cendekia</p>

                    @if(!empty($googleData))
                        <!-- Info Akun Google Terverifikasi -->
                        <div class="d-flex align-items-center justify-content-between p-2 px-3 rounded-3 mb-3" style="background-color: #F0FDF4; border: 1px solid #BBF7D0;">
                            <div class="d-flex align-items-center gap-2 overflow-hidden">
                                @if(!empty($googleData['avatar']))
                                    <img src="{{ $googleData['avatar'] }}" alt="Avatar" class="rounded-circle shadow-sm" style="width: 28px; height: 28px; object-fit: cover;">
                                @else
                                    <i class="bi bi-google text-success fs-6"></i>
                                @endif
                                <div class="text-truncate">
                                    <span class="d-block text-success fw-bold" style="font-size: 11px;"><i class="bi bi-check-circle-fill me-1"></i> Akun Google Terhubung</span>
                                    <span class="text-secondary small text-truncate d-block" style="font-size: 11px;">{{ $googleData['email'] }}</span>
                                </div>
                            </div>
                            <a href="{{ route('auth.google.cancel') }}" class="btn btn-sm btn-link text-danger text-decoration-none p-0 ms-2" style="font-size: 11px;" title="Batalkan dan gunakan email lain">
                                <i class="bi bi-x-circle me-1"></i>Batal
                            </a>
                        </div>
                    @endif

                    <form action="{{ route('register', [], false) }}" method="POST">
                        @csrf
                        
                        <!-- Email Input (Pill Soft-gray with envelope) -->
                        <div class="mb-3">
                            <div class="soft-input-group @error('email') has-error @enderror">
                                <i class="bi bi-envelope text-muted fs-5"></i>
                                <input type="email" name="email" id="email" class="soft-input-field" placeholder="Email Anda" value="{{ old('email', $googleData['email'] ?? '') }}" {{ !empty($googleData) ? 'readonly' : 'required' }} autofocus>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1 ms-2" style="font-size: 12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Input (Pill Soft-gray with lock & eye toggle) -->
                        <div class="mb-3 position-relative">
                            <div class="soft-input-group @error('password') has-error @enderror">
                                <i class="bi bi-lock text-muted fs-5"></i>
                                <input type="password" name="password" id="password" class="soft-input-field" placeholder="{{ !empty($googleData) ? 'Buat Kata Sandi Manual' : 'Kata Sandi' }}" required autocomplete="new-password">
                                <button type="button" class="btn-toggle-eye" onclick="toggleFieldVisibility('password', this)" aria-label="Toggle password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>

                            <!-- Pop-up Pesan Kombinasi Karakter Sandi Melayang -->
                            <div id="passwordRulesPopup" class="password-rules-popup">
                                <div class="d-flex align-items-center gap-1 mb-2 pb-1 border-bottom" style="font-size: 12px; font-weight: 700; color: #1E293B;">
                                    <i class="bi bi-shield-lock-fill text-warning"></i>
                                    <span>Kombinasi Sandi Aman:</span>
                                </div>
                                <div class="password-rule-item" id="ruleLength">
                                    <i class="bi bi-circle"></i>
                                    <span>Minimal 8 karakter</span>
                                </div>
                                <div class="password-rule-item" id="ruleMixed">
                                    <i class="bi bi-circle"></i>
                                    <span>Huruf besar &amp; kecil (A-z)</span>
                                </div>
                                <div class="password-rule-item" id="ruleNumber">
                                    <i class="bi bi-circle"></i>
                                    <span>Minimal 1 angka (0-9)</span>
                                </div>
                                <div class="password-rule-item" id="ruleSymbol">
                                    <i class="bi bi-circle"></i>
                                    <span>Simbol khusus (@, #, $, !, %, dll)</span>
                                </div>
                            </div>

                            @error('password')
                                <div class="text-danger small mt-1 ms-2" style="font-size: 12px;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Checkbox Persetujuan -->
                        <div class="d-flex justify-content-between align-items-center mb-3 px-1 small">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="agree" id="agree" checked style="cursor: pointer;">
                                <label class="form-check-label text-muted" for="agree" style="font-size: 0.82rem; cursor: pointer;">
                                    Saya menyetujui ketentuan penerbitan
                                </label>
                            </div>
                        </div>

                        <!-- Tombol Buat Akun (Website brand theme color) -->
                        <button type="submit" class="btn-masuk-theme mb-3">
                            {{ !empty($googleData) ? 'Simpan Sandi & Selesaikan Pendaftaran' : 'Daftar' }}
                        </button>

                        @if(empty($googleData))
                            <!-- Tombol Google Sign-Up untuk Author (Sama seperti halaman Login) -->
                            <a href="{{ route('auth.google', ['intent' => 'register']) }}" class="btn-google-soft mb-4 text-decoration-none">
                                <svg width="18" height="18" viewBox="0 0 18 18" class="me-2" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.616z" fill="#4285F4"/>
                                    <path d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z" fill="#34A853"/>
                                    <path d="M3.964 10.707c-.18-.54-.282-1.117-.282-1.707s.102-1.167.282-1.707V4.961H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.039l3.007-2.332z" fill="#FBBC05"/>
                                    <path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.961L3.964 7.293C4.672 5.166 6.656 3.58 9 3.58z" fill="#EA4335"/>
                                </svg>
                                <span>Daftar dengan Google</span>
                            </a>
                        @endif

                        <!-- Footer Text: Kembali ke Login -->
                        <div class="text-center small text-muted mb-0" style="font-size: 0.88rem;">
                            Sudah punya akun? <a href="{{ route('login') }}" class="text-dark fw-bold text-decoration-none">Masuk</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // Eye toggle password visibility
    function toggleFieldVisibility(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
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

    // Interactive Pop-up Kombinasi Password Real-time
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password');
        const popup = document.getElementById('passwordRulesPopup');

        const ruleLength = document.getElementById('ruleLength');
        const ruleMixed = document.getElementById('ruleMixed');
        const ruleNumber = document.getElementById('ruleNumber');
        const ruleSymbol = document.getElementById('ruleSymbol');

        if (!passwordInput || !popup) return;

        // Tampilkan pop-up saat fokus
        passwordInput.addEventListener('focus', function () {
            popup.style.display = 'block';
            checkPasswordRules(passwordInput.value);
        });

        // Sembunyikan pop-up saat keluar dari fokus (dengan delay sedikit)
        passwordInput.addEventListener('blur', function () {
            setTimeout(function () {
                popup.style.display = 'none';
            }, 180);
        });

        // Cek live setiap ketikan
        passwordInput.addEventListener('input', function () {
            popup.style.display = 'block';
            checkPasswordRules(passwordInput.value);
        });

        function checkPasswordRules(val) {
            // 1. Min 8 karakter
            const hasLength = val.length >= 8;
            updateRuleUI(ruleLength, hasLength);

            // 2. Huruf besar dan kecil
            const hasUpper = /[A-Z]/.test(val);
            const hasLower = /[a-z]/.test(val);
            const hasMixed = hasUpper && hasLower;
            updateRuleUI(ruleMixed, hasMixed);

            // 3. Angka
            const hasNumber = /[0-9]/.test(val);
            updateRuleUI(ruleNumber, hasNumber);

            // 4. Simbol / Karakter khusus
            const hasSymbol = /[^A-Za-z0-9]/.test(val);
            updateRuleUI(ruleSymbol, hasSymbol);
        }

        function updateRuleUI(element, isValid) {
            const icon = element.querySelector('i');
            if (isValid) {
                element.classList.add('valid');
                icon.className = 'bi bi-check-circle-fill';
            } else {
                element.classList.remove('valid');
                icon.className = 'bi bi-circle';
            }
        }
    });
</script>
@endpush
