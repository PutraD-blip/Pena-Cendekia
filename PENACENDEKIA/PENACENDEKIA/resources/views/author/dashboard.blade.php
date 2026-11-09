@extends('layouts.author')

@section('title', 'Dashboard Penulis &mdash; PT. Pena Cendekia')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* ========================================================= */
    /* Art Academy Style for Author Dashboard (Clean & Spacious) */
    /* ========================================================= */

    /* 1. Hero Greeting Banner (Literary Midnight Ink & Warm Amber Foil) */
    .art-hero-banner {
        background: linear-gradient(135deg, #0F172A 0%, #1E293B 55%, #182236 100%);
        border-radius: 20px;
        padding: 24px 28px;
        margin-bottom: 24px;
        color: #FFFFFF;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(217, 119, 6, 0.35);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
    }
    .art-hero-banner::after {
        content: '';
        position: absolute;
        top: -60px;
        right: -30px;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(217, 119, 6, 0.22) 0%, transparent 70%);
        pointer-events: none;
    }
    .art-hero-badge {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #FBBF24;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(217, 119, 6, 0.15);
        border: 1px solid rgba(217, 119, 6, 0.35);
        padding: 4px 14px;
        border-radius: 50px;
        margin-bottom: 10px;
    }
    .art-hero-title {
        font-family: var(--font-heading);
        font-weight: 800;
        font-size: 24px;
        color: #FFFFFF;
        margin-bottom: 6px;
        line-height: 1.3;
    }
    .art-hero-desc {
        color: #CBD5E1;
        font-size: 13.5px;
        line-height: 1.6;
        max-width: 720px;
        margin-bottom: 18px;
        font-weight: 400;
    }
    .art-hero-desc strong {
        color: #F8FAFC;
    }
    .art-hero-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #D97706 0%, #B45309 100%);
        color: #FFFFFF !important;
        font-weight: 700;
        font-size: 13px;
        padding: 9px 22px;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(217, 119, 6, 0.35);
    }
    .art-hero-cta:hover {
        background: linear-gradient(135deg, #B45309 0%, #92400E 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(217, 119, 6, 0.45);
    }
    .art-hero-secondary-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: rgba(255, 255, 255, 0.08);
        color: #F1F5F9 !important;
        font-weight: 600;
        font-size: 13px;
        padding: 9px 20px;
        border-radius: 50px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .art-hero-secondary-btn:hover {
        background-color: rgba(255, 255, 255, 0.16);
        color: #FFFFFF !important;
        transform: translateY(-1px);
    }

    /* 2. Art Modern Cards */
    .art-card {
        background-color: #FFFFFF;
        border: 1px solid #ECEEF3;
        border-radius: 18px;
        padding: 22px 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }
    .art-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }
    .art-card-title {
        font-family: var(--font-heading);
        font-weight: 800;
        font-size: 16px;
        color: #0F172A;
        margin: 0;
    }
    .btn-art-pill-yellow {
        background: linear-gradient(135deg, #D97706 0%, #B45309 100%);
        color: #FFFFFF !important;
        font-weight: 700;
        font-size: 12.5px;
        padding: 7px 18px;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 3px 10px rgba(217, 119, 6, 0.28);
    }
    .btn-art-pill-yellow:hover {
        background: linear-gradient(135deg, #B45309 0%, #92400E 100%);
        transform: translateY(-1px);
        color: #FFFFFF !important;
        box-shadow: 0 4px 14px rgba(217, 119, 6, 0.38);
    }

    /* Modern Clean Table */
    .art-table {
        margin-bottom: 0;
        vertical-align: middle;
        width: 100%;
    }
    .art-table thead th {
        font-size: 11.5px;
        font-weight: 700;
        color: #64748B;
        border-bottom: 1.5px solid #F1F5F9;
        padding: 12px 16px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #F8FAFC;
    }
    .art-table tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #F1F5F9;
        font-size: 13px;
        color: #334155;
        background: transparent;
    }
    .art-table tbody tr:last-child td {
        border-bottom: none;
    }
    .art-table tbody tr:hover td {
        background-color: #F8FAFC;
    }

    /* Avatar icon in table */
    .art-book-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background-color: #FFFBEB;
        border: 1.5px solid #FDE68A;
        color: #D97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 18px;
        flex-shrink: 0;
    }

    /* Status dot badge */
    .art-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 12px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 50px;
        background-color: #F8FAFC;
        border: 1px solid #E2E8F0;
        white-space: nowrap;
    }
    .art-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    /* Action Notice Card (Dark Navy Art Academy Style) */
    .art-notice-card {
        background: #1B1D2A;
        border-radius: 18px;
        padding: 22px 26px;
        margin-bottom: 24px;
        color: #FFFFFF;
        position: relative;
        border: 1px solid rgba(245, 166, 35, 0.3);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    /* Stepper Alur Proses Penerbitan (Art Academy Connected Flow) */
    .art-stepper-track {
        position: relative;
        padding: 12px 0 6px 0;
    }
    .art-step-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        margin-bottom: 8px;
        transition: all 0.25s ease;
        border: 2px solid #E2E8F0;
        background-color: #F8FAFC;
        color: #94A3B8;
    }
    .art-step-circle.active {
        background: linear-gradient(135deg, #D97706 0%, #B45309 100%);
        border-color: #D97706;
        color: #FFFFFF;
        font-weight: 800;
        box-shadow: 0 6px 16px rgba(217, 119, 6, 0.35);
        transform: scale(1.08);
    }
    .art-step-circle.passed {
        background-color: #10B981;
        border-color: #10B981;
        color: #FFFFFF;
        font-weight: 700;
    }
    .art-step-circle.upcoming {
        background-color: #F1F5F9;
        color: #475569;
        border: 2px solid #CBD5E1;
    }

    /* Stat Metric Cards */
    .art-stat-metric-card {
        background-color: #FFFFFF;
        border: 1px solid #ECEEF3;
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.2s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        height: 100%;
    }
    .art-stat-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    }
    .art-stat-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .art-stat-number {
        font-family: var(--font-heading);
        font-weight: 800;
        font-size: 24px;
        color: #0F172A;
        line-height: 1.1;
        margin-bottom: 2px;
    }
    .art-stat-label {
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748B;
        display: block;
        margin-bottom: 2px;
    }
    .art-stat-sub {
        font-size: 11px;
        color: #94A3B8;
        display: block;
    }

    /* Toolkit Items (Template & Layanan Pintasan) */
    .art-toolkit-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-radius: 14px;
        background-color: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        margin-bottom: 10px;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .art-toolkit-item:hover {
        background-color: #FFFBEB;
        border-color: #F59E0B;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(245, 158, 11, 0.12);
    }
    .art-toolkit-item:last-child {
        margin-bottom: 0;
    }
    .art-toolkit-icon {
        width: 44px;
        height: 44px;
        min-width: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        margin-right: 14px !important;
        transition: transform 0.2s ease;
    }
    .art-toolkit-icon.icon-amber {
        background-color: rgba(245, 158, 11, 0.14);
        color: #D97706;
        border: 1.5px solid rgba(245, 158, 11, 0.35);
    }
    .art-toolkit-icon.icon-emerald {
        background-color: rgba(16, 185, 129, 0.14);
        color: #059669;
        border: 1.5px solid rgba(16, 185, 129, 0.35);
    }
    .art-toolkit-icon.icon-cyan {
        background-color: rgba(14, 165, 233, 0.14);
        color: #0284C7;
        border: 1.5px solid rgba(14, 165, 233, 0.35);
    }
    .art-toolkit-icon.icon-purple {
        background-color: rgba(139, 92, 246, 0.14);
        color: #7C3AED;
        border: 1.5px solid rgba(139, 92, 246, 0.35);
    }
    .art-toolkit-icon.icon-green {
        background-color: rgba(34, 197, 94, 0.14);
        color: #16A34A;
        border: 1.5px solid rgba(34, 197, 94, 0.35);
    }
    .art-toolkit-item:hover .art-toolkit-icon {
        transform: scale(1.06);
    }
    .art-toolkit-body {
        flex-grow: 1;
        min-width: 0;
    }
    .art-toolkit-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 2px;
        display: block;
        line-height: 1.35;
    }
    .art-toolkit-desc {
        font-size: 12px;
        color: #64748B;
        display: block;
        line-height: 1.35;
    }
    .art-item-badge-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 700;
        color: #0F172A;
        background-color: #F1F5F9;
        border: 1.5px solid #CBD5E1;
        padding: 6px 14px;
        border-radius: 50px;
        transition: all 0.2s ease;
        flex-shrink: 0;
        margin-left: 10px;
    }
    .art-toolkit-item:hover .art-item-badge-btn {
        background-color: #D97706;
        border-color: #D97706;
        color: #FFFFFF;
    }
    .art-item-action-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #F1F5F9;
        border: 1.5px solid #CBD5E1;
        color: #64748B;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        transition: all 0.2s ease;
        flex-shrink: 0;
        margin-left: 10px;
    }
    .art-toolkit-item:hover .art-item-action-icon {
        background-color: #D97706;
        border-color: #D97706;
        color: #FFFFFF;
        transform: translateX(2px);
    }

    @media (max-width: 767.98px) {
        .art-hero-banner {
            padding: 20px 18px !important;
        }
        .art-card {
            padding: 18px 16px !important;
        }
        .art-card-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px !important;
        }
    }

    /* Dark Mode Dashboard Overrides */
    [data-theme="dark"] .art-stat-metric-card {
        background-color: #121820 !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
    }
    [data-theme="dark"] .art-stat-number {
        color: #F8FAFC !important;
    }
    [data-theme="dark"] .art-stat-label,
    [data-theme="dark"] .art-stat-sub {
        color: #94A3B8 !important;
    }
    [data-theme="dark"] .art-toolkit-item {
        background-color: #16202C !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    [data-theme="dark"] .art-toolkit-item:hover {
        background-color: #1E2B3A !important;
        border-color: #F5A623 !important;
    }
    [data-theme="dark"] .art-toolkit-title {
        color: #F8FAFC !important;
    }
    [data-theme="dark"] .art-toolkit-desc {
        color: #94A3B8 !important;
    }
    [data-theme="dark"] .art-toolkit-icon.icon-amber {
        background-color: rgba(245, 158, 11, 0.22) !important;
        color: #FBBF24 !important;
        border-color: rgba(245, 158, 11, 0.45) !important;
    }
    [data-theme="dark"] .art-toolkit-icon.icon-emerald {
        background-color: rgba(16, 185, 129, 0.22) !important;
        color: #34D399 !important;
        border-color: rgba(16, 185, 129, 0.45) !important;
    }
    [data-theme="dark"] .art-toolkit-icon.icon-cyan {
        background-color: rgba(14, 165, 233, 0.22) !important;
        color: #38BDF8 !important;
        border-color: rgba(14, 165, 233, 0.45) !important;
    }
    [data-theme="dark"] .art-toolkit-icon.icon-purple {
        background-color: rgba(139, 92, 246, 0.22) !important;
        color: #A78BFA !important;
        border-color: rgba(139, 92, 246, 0.45) !important;
    }
    [data-theme="dark"] .art-toolkit-icon.icon-green {
        background-color: rgba(34, 197, 94, 0.22) !important;
        color: #4ADE80 !important;
        border-color: rgba(34, 197, 94, 0.45) !important;
    }
    [data-theme="dark"] .art-item-badge-btn {
        background-color: rgba(255, 255, 255, 0.08) !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
        color: #F8FAFC !important;
    }
    [data-theme="dark"] .art-toolkit-item:hover .art-item-badge-btn {
        background-color: #F5A623 !important;
        border-color: #F5A623 !important;
        color: #111827 !important;
    }
    [data-theme="dark"] .art-item-action-icon {
        background-color: rgba(255, 255, 255, 0.08) !important;
        border-color: rgba(255, 255, 255, 0.12) !important;
        color: #94A3B8 !important;
    }
    [data-theme="dark"] .art-toolkit-item:hover .art-item-action-icon {
        background-color: #F5A623 !important;
        border-color: #F5A623 !important;
        color: #111827 !important;
    }
    [data-theme="dark"] .art-card {
        background-color: var(--author-card-bg) !important;
        border-color: var(--author-card-border) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
    }
    [data-theme="dark"] .art-card-title,
    [data-theme="dark"] h1.text-dark,
    [data-theme="dark"] h2.text-dark,
    [data-theme="dark"] h3.text-dark,
    [data-theme="dark"] h4.text-dark,
    [data-theme="dark"] h5.text-dark,
    [data-theme="dark"] h6.text-dark,
    [data-theme="dark"] .fw-bold.text-dark {
        color: #F8FAFC !important;
    }
    [data-theme="dark"] .text-muted {
        color: #94A3B8 !important;
    }
    [data-theme="dark"] .art-table {
        color: #F8FAFC !important;
    }
    [data-theme="dark"] .art-table thead th {
        background-color: #161F2A !important;
        color: #94A3B8 !important;
        border-bottom: 1.5px solid rgba(255, 255, 255, 0.08) !important;
    }
    [data-theme="dark"] .art-table tbody td {
        border-bottom: 1px solid rgba(255, 255, 255, 0.06) !important;
        color: #F8FAFC !important;
    }
    [data-theme="dark"] .art-table tbody tr:hover td {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }
    [data-theme="dark"] .art-book-avatar {
        background-color: #161F2A !important;
        border-color: rgba(245, 166, 35, 0.4) !important;
        color: #F5A623 !important;
    }
    [data-theme="dark"] .art-table tbody td a.text-dark {
        color: #F8FAFC !important;
    }
    [data-theme="dark"] .dropdown-menu {
        background-color: #161F2A !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4) !important;
    }
    [data-theme="dark"] .dropdown-item {
        color: #E2E8F0 !important;
    }
    [data-theme="dark"] .dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: #FFFFFF !important;
    }
    [data-theme="dark"] .alert-warning {
        background-color: #161F2A !important;
        border-color: #F5A623 !important;
        color: #F8FAFC !important;
    }
    [data-theme="dark"] .art-step-circle.upcoming {
        background-color: #161F2A !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        color: #64748B !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0" style="max-width: 100%;">

    <!-- ========================================================= -->
    <!-- 1. HERO GREETING BANNER                                   -->
    <!-- ========================================================= -->
    <div class="art-hero-banner">
        <div class="row align-items-center mx-0">
            <div class="col-12">
                <div class="art-hero-badge">
                    <i class="bi bi-patch-check-fill text-warning"></i> PT. PENA CENDEKIA PUSTAKA &bull; PENERBIT RESMI IKAPI &amp; PERPUSNAS
                </div>
                
                <h2 class="art-hero-title">
                    Halo, <span style="color: #FBBF24;">{{ $user->name }}</span>! 👋
                </h2>
                
                <p class="art-hero-desc">
                    Selamat datang di ruang kerja penerbitan Anda. Saat ini terdapat <strong>{{ $stats['total'] }} naskah</strong> terdaftar. Pantau status telaah dewan redaksi, verifikasi pembayaran, atau ajukan naskah buku baru ber-ISBN hari ini.
                </p>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    @if($isProfileComplete)
                        <a href="{{ route('author.submissions.create', ['service' => 'penerbitan']) }}" class="art-hero-cta">
                            <i class="bi bi-box-seam-fill"></i>
                            <span>Pilih Paket &amp; Terbitkan Buku</span>
                        </a>
                    @else
                        <a href="{{ route('author.profile.edit') }}" class="art-hero-cta" style="background-color: #DC2626;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>Lengkapi NIK &amp; Data Diri</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <!-- Peringatan jika data profil / NIK belum lengkap -->
    @if(!$isProfileComplete)
        <div class="alert alert-warning border-0 rounded-4 shadow-sm p-3 mb-4 bg-white" style="border-left: 5px solid #F5A623 !important;">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="background-color: #FFF8EE; color: #111827; width: 40px; height: 40px; font-weight: 800; font-size: 18px;">
                        <i class="bi bi-person-vcard"></i>
                    </span>
                    <div>
                        <h6 class="fw-bold mb-1 text-dark" style="font-size: 14px;">Identitas Penulis Belum Lengkap</h6>
                        <p class="mb-0 text-muted" style="font-size: 12px;">Lengkapi <strong>NIK</strong>, alamat domisili, dan scan <strong>KTP</strong> sebelum mengajukan naskah untuk administrasi ISBN.</p>
                    </div>
                </div>
                <a href="{{ route('author.profile.edit') }}" class="btn-art-pill-yellow text-nowrap px-4 py-2" style="font-size: 12.5px;">
                    Lengkapi Sekarang <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    @endif


    <!-- ========================================================= -->
    <!-- 2. QUICK METRIC STATS OVERVIEW                            -->
    <!-- ========================================================= -->
    <div class="row g-3 mb-4 mx-0">
        <!-- 1. Total Naskah -->
        <div class="col-6 col-xl-3">
            <div class="art-stat-metric-card">
                <div class="art-stat-icon-wrap" style="background-color: rgba(59, 130, 246, 0.12); color: #3B82F6;">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div>
                    <span class="art-stat-label">Total Naskah</span>
                    <div class="art-stat-number">{{ $stats['total'] }}</div>
                    <span class="art-stat-sub">Karya Terdaftar</span>
                </div>
            </div>
        </div>

        <!-- 2. Telaah Redaksi -->
        <div class="col-6 col-xl-3">
            <div class="art-stat-metric-card">
                <div class="art-stat-icon-wrap" style="background-color: rgba(6, 182, 212, 0.12); color: #06B6D4;">
                    <i class="bi bi-search"></i>
                </div>
                <div>
                    <span class="art-stat-label">Telaah Redaksi</span>
                    <div class="art-stat-number">{{ $stats['review'] }}</div>
                    <span class="art-stat-sub">Review &amp; Kurasi</span>
                </div>
            </div>
        </div>

        <!-- 3. Desain & ISBN -->
        <div class="col-6 col-xl-3">
            <div class="art-stat-metric-card">
                <div class="art-stat-icon-wrap" style="background-color: rgba(245, 158, 11, 0.12); color: #F59E0B;">
                    <i class="bi bi-palette-fill"></i>
                </div>
                <div>
                    <span class="art-stat-label">Desain &amp; ISBN</span>
                    <div class="art-stat-number">{{ $stats['payment'] + $stats['design'] }}</div>
                    <span class="art-stat-sub">Tata Letak &amp; Barcode</span>
                </div>
            </div>
        </div>

        <!-- 4. Buku Selesai / Terbit -->
        <div class="col-6 col-xl-3">
            <div class="art-stat-metric-card">
                <div class="art-stat-icon-wrap" style="background-color: rgba(16, 185, 129, 0.12); color: #10B981;">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div>
                    <span class="art-stat-label">Buku Terbit</span>
                    <div class="art-stat-number">{{ $stats['completed'] }}</div>
                    <span class="art-stat-sub">Ber-ISBN Resmi</span>
                </div>
            </div>
        </div>
    </div>


    <!-- ========================================================= -->
    <!-- NOTIFIKASI TINDAKAN (JIKA ADA TAGIHAN / REVISI)           -->
    <!-- ========================================================= -->
    @if($paymentSubmission && in_array($paymentSubmission->status, ['waiting_payment', 'waiting_final_payment', 'ready_to_print']))
        @php
            $isDp = $paymentSubmission->status === 'waiting_payment';
            $amountDue = $isDp ? $paymentSubmission->dp_amount : $paymentSubmission->final_amount;
            $stepLabel = $isDp ? 'Down Payment (DP 50%) - Tahap Desain & Layout' : 'Pelunasan Biaya Cetak (50%) - Buku Telah Selesai Dicetak';
        @endphp
        <div class="art-notice-card">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <span class="badge px-3 py-1.5 rounded-pill mb-2" style="background-color: rgba(245, 166, 35, 0.2); color: #F5A623; font-size: 11.5px; border: 1px solid rgba(245, 166, 35, 0.4);">
                        <i class="bi bi-clock-history me-1"></i> Tindakan Diperlukan
                    </span>
                    <h5 class="fw-bold mb-1 text-white">{{ $stepLabel }}</h5>
                    <p class="text-white-50 small mb-2">
                        Naskah: <strong class="text-white">{{ $paymentSubmission->title }}</strong> (Kode: <span class="text-warning font-monospace">{{ $paymentSubmission->code }}</span>)
                    </p>
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="text-white-50 small">Nominal Tagihan:</span>
                        <span class="fs-4 fw-bold text-warning" style="font-family: var(--font-heading);">Rp {{ number_format($amountDue, 0, ',', '.') }}</span>
                    </div>
                </div>

                <a href="{{ route('author.payments.show', $paymentSubmission) }}" class="btn-art-pill-yellow text-nowrap py-2.5 px-4">
                    <i class="bi bi-credit-card me-1"></i> Bayar Sekarang
                </a>
            </div>
        </div>

    @elseif($latestSubmission && $latestSubmission->status === 'revision_required')
        <div class="art-notice-card" style="border-color: rgba(239, 68, 68, 0.5);">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <span class="badge bg-danger px-3 py-1.5 rounded-pill mb-2" style="font-size: 11.5px;">
                        <i class="bi bi-pencil-fill me-1"></i> Perlu Revisi Naskah
                    </span>
                    <h5 class="fw-bold mb-1 text-white">Catatan Perbaikan: "{{ $latestSubmission->title }}"</h5>
                    <p class="text-white-50 small mb-0">{{ $latestSubmission->admin_notes ?? 'Silakan tinjau kembali berkas naskah Anda sesuai arahan redaksi.' }}</p>
                </div>

                <a href="{{ route('author.submissions.edit', $latestSubmission) }}" class="btn btn-danger rounded-pill px-4 py-2.5 text-nowrap fw-bold" style="font-size: 13px;">
                    <i class="bi bi-upload me-1"></i> Unggah Revisi
                </a>
            </div>
        </div>
    @endif


    <!-- ========================================================= -->
    <!-- ROW 1: PENGAJUAN NASKAH & PUSAT UNDUHAN TEMPLATE          -->
    <!-- ========================================================= -->
    <div class="row g-3 mx-0 mb-4">
        
        <!-- LEFT: Pengajuan Naskah Table -->
        <div class="col-xl-8 col-lg-7">
            <div class="art-card h-100 mb-0">
                <div class="art-card-header pb-2 border-bottom">
                    <div>
                        <h4 class="art-card-title">Pengajuan Naskah</h4>
                        <span class="text-muted small">Daftar naskah buku yang Anda ajukan ke dewan redaksi</span>
                    </div>

                    <a href="{{ route('author.submissions.create', ['service' => 'penerbitan']) }}" class="btn-art-pill-yellow border-0">
                        <i class="bi bi-plus-lg"></i>
                        <span>Ajukan Naskah</span>
                    </a>
                </div>

                @if($submissions->isEmpty())
                    <!-- Clean Spacious Empty State -->
                    <div class="text-center py-5 my-2">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 54px; height: 54px; background-color: #FFF8EE; color: #111827; font-size: 22px; border: 1.5px solid #F5A623;">
                            <i class="bi bi-journal-plus"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1" style="font-size: 15px;">Belum Ada Pengajuan Naskah</h5>
                        <p class="text-muted small mx-auto mb-3" style="max-width: 420px; font-size: 12.5px; line-height: 1.5;">
                            Mulai terbitkan karya buku Anda ber-ISBN resmi Perpustakaan Nasional bersama PT. Pena Cendekia Pustaka.
                        </p>
                        <a href="{{ route('author.submissions.create', ['service' => 'penerbitan']) }}" class="btn-art-pill-yellow px-4 py-2 border-0">
                            <i class="bi bi-plus-lg me-1"></i> Mulai Ajukan Naskah
                        </a>
                    </div>
                @else
                    <!-- Clean Art Table -->
                    <div class="table-responsive">
                        <table class="table art-table align-middle">
                            <thead>
                                <tr>
                                    <th>Judul &amp; Penulis</th>
                                    <th>Layanan / Kategori</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($submissions as $sub)
                                    @php
                                        $dotColor = '#94A3B8';
                                        if($sub->status === 'pending_review') $dotColor = '#06B6D4';
                                        elseif($sub->status === 'waiting_payment') $dotColor = '#F5A623';
                                        elseif($sub->status === 'payment_verification') $dotColor = '#3B82F6';
                                        elseif($sub->status === 'in_design') $dotColor = '#8B5CF6';
                                        elseif($sub->status === 'ready_to_print') $dotColor = '#06B6D4';
                                        elseif($sub->status === 'printing') $dotColor = '#2563EB';
                                        elseif($sub->status === 'completed') $dotColor = '#10B981';
                                        elseif($sub->status === 'revision_required') $dotColor = '#EF4444';
                                    @endphp
                                    <tr>
                                        <!-- Judul & Penulis -->
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="art-book-avatar">
                                                    <i class="bi bi-book"></i>
                                                </div>
                                                <div>
                                                    <a href="{{ route('author.submissions.show', $sub) }}" class="fw-bold text-dark text-decoration-none d-block" style="font-size: 13.5px; line-height: 1.35;">
                                                        {{ Str::limit($sub->title, 42) }}
                                                    </a>
                                                    <span class="text-muted" style="font-size: 11.5px;">
                                                        Kode: <span class="font-monospace text-dark fw-semibold">{{ $sub->code }}</span> &bull; {{ $sub->created_at->format('d M Y') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Kategori & Layanan -->
                                        <td>
                                            <span class="d-block fw-semibold text-dark" style="font-size: 12.5px;">
                                                {{ $sub->package_name ?? ($sub->category->name ?? 'Penerbitan Buku') }}
                                            </span>
                                            <span class="text-muted" style="font-size: 11.5px;">
                                                {{ $sub->category->name ?? 'Kategori Umum' }} &bull; {{ $sub->estimated_pages ?? '-' }} Halaman
                                            </span>
                                        </td>

                                        <!-- Status Dot Pill -->
                                        <td>
                                            <div class="art-status-pill">
                                                <span class="art-status-dot" style="background-color: {{ $dotColor }};"></span>
                                                <span style="color: {{ $dotColor }};">{{ $sub->status_label }}</span>
                                            </div>
                                        </td>

                                        <!-- Aksi: Tombol Detail Langsung + Dropdown -->
                                        <td class="text-end">
                                            <div class="d-inline-flex align-items-center gap-1.5">
                                                <a href="{{ route('author.submissions.show', $sub) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1 fw-bold text-nowrap" style="font-size: 12px;">
                                                    Detail <i class="bi bi-chevron-right ms-0.5"></i>
                                                </a>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light rounded-circle p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center;">
                                                        <i class="bi bi-three-dots-vertical text-muted"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 py-1" style="font-size: 12.5px;">
                                                        <li>
                                                            <a class="dropdown-item py-1.5 d-flex align-items-center gap-2" href="{{ route('author.submissions.show', $sub) }}">
                                                                <i class="bi bi-eye text-primary"></i> Detail &amp; Lacak
                                                            </a>
                                                        </li>
                                                        @if(in_array($sub->status, ['waiting_payment', 'waiting_final_payment', 'ready_to_print']))
                                                            <li>
                                                                <a class="dropdown-item py-1.5 d-flex align-items-center gap-2 text-warning fw-semibold" href="{{ route('author.payments.show', $sub) }}">
                                                                    <i class="bi bi-credit-card"></i> Bayar Tagihan
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if($sub->status === 'revision_required')
                                                            <li>
                                                                <a class="dropdown-item py-1.5 d-flex align-items-center gap-2 text-danger fw-semibold" href="{{ route('author.submissions.edit', $sub) }}">
                                                                    <i class="bi bi-pencil-square"></i> Unggah Revisi
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($submissions, 'hasPages') && $submissions->hasPages())
                        <div class="mt-3 pt-2 border-top">
                            {{ $submissions->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- RIGHT: Pusat Unduhan Template -->
        <div class="col-xl-4 col-lg-5">
            <div class="art-card h-100 mb-0">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <div>
                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 14.5px;">
                            <i class="bi bi-file-earmark-arrow-down-fill text-warning"></i>
                            <span>Pusat Unduhan Template &amp; Dokumen</span>
                        </h6>
                        <span class="text-muted small" style="font-size: 11.5px;">Format baku naskah, HAKI, keaslian &amp; cetak</span>
                    </div>
                    <span class="badge rounded-pill" style="background: rgba(245, 166, 35, 0.12); color: #F5A623; font-size: 10.5px; font-weight: 700; border: 1px solid rgba(245, 166, 35, 0.25);">
                        6 Berkas Resmi
                    </span>
                </div>

                <div class="d-flex flex-column gap-2">
                    <!-- 1. Template Buku UNESCO -->
                    <div class="art-toolkit-item d-flex align-items-center justify-content-between p-2 rounded-3" style="background: rgba(0,0,0,0.02); border: 1px solid var(--author-border, #E2E8F0);">
                        <div class="d-flex align-items-center gap-2">
                            <div class="art-toolkit-icon icon-amber">
                                <i class="bi bi-book-half"></i>
                            </div>
                            <div class="art-toolkit-body">
                                <strong class="art-toolkit-title d-block" style="font-size: 13px;">Template Buku UNESCO (15.5x23)</strong>
                                <span class="art-toolkit-desc text-muted" style="font-size: 11px;">Standar monograf, referensi &amp; ajar</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <a href="https://docs.google.com/document/d/1z19pXPudjaYpD6-BgdSaW76WQv00XsC2/edit" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 11px;" title="Buka di Google Docs">
                                <i class="bi bi-box-arrow-up-right"></i> Docs
                            </a>
                            <a href="{{ asset('downloads/template-buku-unesco.docx') }}" download class="btn btn-sm btn-outline-dark py-1 px-2" style="font-size: 11px;" title="Unduh .docx">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 2. Template Buku A5 -->
                    <div class="art-toolkit-item d-flex align-items-center justify-content-between p-2 rounded-3" style="background: rgba(0,0,0,0.02); border: 1px solid var(--author-border, #E2E8F0);">
                        <div class="d-flex align-items-center gap-2">
                            <div class="art-toolkit-icon icon-purple">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <div class="art-toolkit-body">
                                <strong class="art-toolkit-title d-block" style="font-size: 13px;">Template Buku A5 (14x20)</strong>
                                <span class="art-toolkit-desc text-muted" style="font-size: 11px;">Format novel, populer &amp; motivasi</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <a href="https://docs.google.com/document/d/1XDiNDTBA-GnK0pflMEC2nEHXTwlmTIN2/edit" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 11px;" title="Buka di Google Docs">
                                <i class="bi bi-box-arrow-up-right"></i> Docs
                            </a>
                            <a href="{{ asset('downloads/template-buku-a5.docx') }}" download class="btn btn-sm btn-outline-dark py-1 px-2" style="font-size: 11px;" title="Unduh .docx">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 3. Template Buku A4 -->
                    <div class="art-toolkit-item d-flex align-items-center justify-content-between p-2 rounded-3" style="background: rgba(0,0,0,0.02); border: 1px solid var(--author-border, #E2E8F0);">
                        <div class="d-flex align-items-center gap-2">
                            <div class="art-toolkit-icon icon-cyan">
                                <i class="bi bi-file-earmark-text"></i>
                            </div>
                            <div class="art-toolkit-body">
                                <strong class="art-toolkit-title d-block" style="font-size: 13px;">Template Buku A4 (21x29.7)</strong>
                                <span class="art-toolkit-desc text-muted" style="font-size: 11px;">Format modul praktikum &amp; laporan</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <a href="https://docs.google.com/document/d/1o1Gjiscydsw5QCVNdPwXnHtcJfpxO1Tm/edit" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 11px;" title="Buka di Google Docs">
                                <i class="bi bi-box-arrow-up-right"></i> Docs
                            </a>
                            <a href="{{ asset('downloads/template-buku-a4.docx') }}" download class="btn btn-sm btn-outline-dark py-1 px-2" style="font-size: 11px;" title="Unduh .docx">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 4. Form Isian Hak Cipta (HAKI) -->
                    <div class="art-toolkit-item d-flex align-items-center justify-content-between p-2 rounded-3" style="background: rgba(0,0,0,0.02); border: 1px solid var(--author-border, #E2E8F0);">
                        <div class="d-flex align-items-center gap-2">
                            <div class="art-toolkit-icon icon-emerald">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="art-toolkit-body">
                                <strong class="art-toolkit-title d-block" style="font-size: 13px;">Form Isian Hak Cipta</strong>
                                <span class="art-toolkit-desc text-muted" style="font-size: 11px;">Formulir pendaftaran resmi DJKI</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <a href="https://docs.google.com/document/d/1mhW0iQCF87RybsrBG4nALvQWl57kaleb/edit" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 11px;" title="Buka di Google Docs">
                                <i class="bi bi-box-arrow-up-right"></i> Docs
                            </a>
                            <a href="{{ asset('downloads/form-isian-hak-cipta.docx') }}" download class="btn btn-sm btn-outline-dark py-1 px-2" style="font-size: 11px;" title="Unduh .docx">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 5. Surat Pernyataan Hak Cipta / Keaslian -->
                    <div class="art-toolkit-item d-flex align-items-center justify-content-between p-2 rounded-3" style="background: rgba(0,0,0,0.02); border: 1px solid var(--author-border, #E2E8F0);">
                        <div class="d-flex align-items-center gap-2">
                            <div class="art-toolkit-icon icon-amber">
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                            <div class="art-toolkit-body">
                                <strong class="art-toolkit-title d-block" style="font-size: 13px;">Surat Pernyataan Hak Cipta</strong>
                                <span class="art-toolkit-desc text-muted" style="font-size: 11px;">Pernyataan keaslian (Meterai 10rb)</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <a href="https://docs.google.com/document/d/1mhQrLKIVESY2vE7pRz9CVXRINmrHkBn4/edit" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 11px;" title="Buka di Google Docs">
                                <i class="bi bi-box-arrow-up-right"></i> Docs
                            </a>
                            <a href="{{ asset('downloads/surat-pernyataan-hak-cipta.docx') }}" download class="btn btn-sm btn-outline-dark py-1 px-2" style="font-size: 11px;" title="Unduh .docx">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 6. Surat Pengalihan Hak Cipta -->
                    <div class="art-toolkit-item d-flex align-items-center justify-content-between p-2 rounded-3" style="background: rgba(0,0,0,0.02); border: 1px solid var(--author-border, #E2E8F0);">
                        <div class="d-flex align-items-center gap-2">
                            <div class="art-toolkit-icon icon-cyan">
                                <i class="bi bi-arrow-left-right"></i>
                            </div>
                            <div class="art-toolkit-body">
                                <strong class="art-toolkit-title d-block" style="font-size: 13px;">Surat Pengalihan Hak Cipta</strong>
                                <span class="art-toolkit-desc text-muted" style="font-size: 11px;">Pengalihan hak individu / lembaga</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <a href="https://docs.google.com/document/d/1mgdAeH0-M01DUAKMnYFMnDlG8hNYNMdn/edit" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 11px;" title="Buka di Google Docs">
                                <i class="bi bi-box-arrow-up-right"></i> Docs
                            </a>
                            <a href="{{ asset('downloads/surat-pengalihan-hak-cipta.docx') }}" download class="btn btn-sm btn-outline-dark py-1 px-2" style="font-size: 11px;" title="Unduh .docx">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <!-- ========================================================= -->
    <!-- ROW 2: TAHAPAN PROSES & LAYANAN PINTASAN ISBN             -->
    <!-- ========================================================= -->
    <div class="row g-3 mx-0">

        <!-- LEFT: Tahapan Proses Penerbitan (SOP Stepper) / CTA Belum Ada Naskah -->
        <div class="col-xl-8 col-lg-7">
            @if($submissions->isNotEmpty())
                @php
                    $isEditing = $latestSubmission && $latestSubmission->service_type === 'editing';

                    if ($isEditing) {
                        $steps = [
                            ['key' => 'pending_review', 'label' => 'Tinjauan', 'desc' => 'Review Naskah', 'icon' => 'bi-search'],
                            ['key' => 'waiting_payment', 'label' => 'DP 50%', 'desc' => 'Administrasi', 'icon' => 'bi-credit-card'],
                            ['key' => 'in_design', 'label' => 'Editing', 'desc' => 'Tata Letak', 'icon' => 'bi-pencil-square'],
                            ['key' => 'waiting_final_payment', 'label' => 'Pelunasan', 'desc' => 'Pelunasan 50%', 'icon' => 'bi-cash-coin'],
                            ['key' => 'completed', 'label' => 'Selesai', 'desc' => 'Kirim Berkas', 'icon' => 'bi-check2-all'],
                        ];

                        $statusWeights = [
                            'pending_review' => 1,
                            'revision_required' => 1,
                            'waiting_payment' => 2,
                            'payment_verification' => ($latestSubmission && $latestSubmission->dpPayment?->status === 'verified' ? 4 : 2),
                            'in_design' => 3,
                            'waiting_final_payment' => 4,
                            'completed' => 5,
                            'cancelled' => 0,
                        ];
                    } else {
                        $steps = [
                            ['key' => 'pending_review', 'label' => 'Tinjauan', 'desc' => 'Review Naskah', 'icon' => 'bi-search'],
                            ['key' => 'waiting_payment', 'label' => 'DP 50%', 'desc' => 'Administrasi', 'icon' => 'bi-credit-card'],
                            ['key' => 'in_design', 'label' => 'Desain', 'desc' => 'Tata Letak', 'icon' => 'bi-palette'],
                            ['key' => 'printing', 'label' => 'Cetak', 'desc' => 'Percetakan', 'icon' => 'bi-printer'],
                            ['key' => 'waiting_final_payment', 'label' => 'Pelunasan', 'desc' => 'Pelunasan 50%', 'icon' => 'bi-cash-coin'],
                            ['key' => 'completed', 'label' => 'Selesai', 'desc' => 'Kirim Buku', 'icon' => 'bi-check2-all'],
                        ];

                        $statusWeights = [
                            'pending_review' => 1,
                            'revision_required' => 1,
                            'waiting_payment' => 2,
                            'payment_verification' => ($latestSubmission && $latestSubmission->dpPayment?->status === 'verified' ? 5 : 2),
                            'in_design' => 3,
                            'ready_to_print' => 3,
                            'printing' => 4,
                            'waiting_final_payment' => 5,
                            'completed' => 6,
                            'cancelled' => 0,
                        ];
                    }

                    $currentWeight = $latestSubmission ? ($statusWeights[$latestSubmission->status] ?? 1) : 0;
                @endphp

                <div class="art-card h-100 mb-0">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                        <div>
                            <h5 class="fw-bold text-dark mb-0" style="font-family: var(--font-heading); font-size: 15px;">
                                {{ $isEditing ? 'Tahapan Proses Editing & Layout' : 'Tahapan Proses Penerbitan & Cetak' }}
                            </h5>
                            <small class="text-muted" style="font-size: 11.5px;">Alur kerja standar operasional resmi PT. Pena Cendekia</small>
                        </div>
                        @if($latestSubmission)
                            <span class="badge rounded-pill px-3 py-1.5" style="background-color: #FFF8EE; color: #111827; font-size: 11px; border: 1px solid #F5A623;">
                                Fokus: {{ Str::limit($latestSubmission->title, 28) }}
                            </span>
                        @endif
                    </div>

                    <div class="art-stepper-track">
                        <div class="row g-2 justify-content-between text-center">
                            @foreach($steps as $index => $st)
                                @php
                                    $stepNumber = $index + 1;
                                    if ($latestSubmission && $latestSubmission->status === 'completed') {
                                        $isActive = false;
                                        $isPassed = true;
                                    } else {
                                        $isActive = $currentWeight === $stepNumber;
                                        $isPassed = $currentWeight > $stepNumber;
                                    }
                                @endphp
                                <div class="col mb-2 mb-md-0">
                                    <div class="art-step-circle {{ $isActive ? 'active' : ($isPassed ? 'passed' : 'upcoming') }}">
                                        <i class="bi {{ $isPassed ? 'bi-check-lg' : $st['icon'] }}"></i>
                                    </div>
                                    <span class="d-block fw-bold {{ $isActive ? 'text-dark' : ($isPassed ? 'text-success' : 'text-muted') }}" style="font-size: 12px; line-height: 1.25;">
                                        {{ $st['label'] }}
                                    </span>
                                    <span class="d-block text-muted" style="font-size: 10.5px;">
                                        {{ $st['desc'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="art-card h-100 mb-0 p-4" style="background: linear-gradient(135deg, #FFF8EE 0%, #FFFFFF 100%); border: 1.5px dashed #F5A623;">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 h-100">
                        <div class="d-flex align-items-center gap-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 48px; height: 48px; background-color: #F5A623; color: #111827; font-size: 20px;">
                                <i class="bi bi-send-check-fill"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 15px;">Ajukan Naskah Buku Anda</h6>
                                <p class="text-muted small mb-0" style="font-size: 12px; line-height: 1.45;">
                                    Tahapan alur proses penerbitan otomatis aktif setelah mengajukan naskah pertama. Silakan pilih paket layanan!
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('author.submissions.create', ['service' => 'penerbitan']) }}" class="btn-art-pill-yellow border-0 flex-shrink-0 px-4 py-2">
                            <i class="bi bi-box-seam-fill me-1"></i> Pilih Paket Buku
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- RIGHT: Layanan Pintasan & ISBN -->
        <div class="col-xl-4 col-lg-5">
            <div class="art-card h-100 mb-0">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <div>
                        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 14.5px;">
                            <i class="bi bi-grid-fill text-warning"></i>
                            <span>Layanan Pintasan &amp; ISBN</span>
                        </h6>
                        <span class="text-muted small" style="font-size: 11.5px;">Pintasan integrasi resmi penerbit</span>
                    </div>
                    <span class="badge rounded-pill" style="background: rgba(245, 166, 35, 0.12); color: #F5A623; font-size: 10.5px; font-weight: 700; border: 1px solid rgba(245, 166, 35, 0.25);">
                        Akses Cepat
                    </span>
                </div>

                <div class="d-flex flex-column gap-2">
                    <!-- Cek ISBN di Database Perpusnas -->
                    <a href="https://isbn.perpusnas.go.id/" target="_blank" rel="noopener" class="art-toolkit-item">
                        <div class="art-toolkit-icon icon-cyan">
                            <i class="bi bi-upc-scan"></i>
                        </div>
                        <div class="art-toolkit-body">
                            <strong class="art-toolkit-title">Cek ISBN di Database Perpusnas</strong>
                            <span class="art-toolkit-desc">Portal katalog resmi Perpusnas RI</span>
                        </div>
                        <span class="art-item-action-icon">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </span>
                    </a>

                    <!-- Pilihan Paket Cetak (Konsultasi Oplah & Cetak Tambahan) -->
                    <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Redaksi Percetakan Pena Cendekia, saya ingin konsultasi cetak ulang naskah atau cetak tambahan oplah buku.') }}" target="_blank" rel="noopener" class="art-toolkit-item">
                        <div class="art-toolkit-icon icon-purple">
                            <i class="bi bi-printer-fill"></i>
                        </div>
                        <div class="art-toolkit-body">
                            <strong class="art-toolkit-title">Cetak Tambahan &amp; Cetak Ulang</strong>
                            <span class="art-toolkit-desc">Konsultasi oplah POD &amp; Offset via WA</span>
                        </div>
                        <span class="art-item-action-icon">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </span>
                    </a>

                    <!-- Konsultasi Dewan Redaksi via WhatsApp -->
                    <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Dewan Redaksi Pena Cendekia, saya ingin konsultasi mengenai penerbitan naskah buku saya.') }}" target="_blank" rel="noopener" class="art-toolkit-item">
                        <div class="art-toolkit-icon icon-green">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div class="art-toolkit-body">
                            <strong class="art-toolkit-title">Konsultasi Redaksi Langsung</strong>
                            <span class="art-toolkit-desc">Pendampingan intensif via WhatsApp</span>
                        </div>
                        <span class="art-item-action-icon">
                            <i class="bi bi-arrow-right"></i>
                        </span>
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
