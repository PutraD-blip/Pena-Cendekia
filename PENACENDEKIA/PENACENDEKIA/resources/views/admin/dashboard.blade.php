@extends('layouts.admin')

@section('title', 'Dashboard — PT. Pena Cendekia')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* ==========================================================================
       ENTERPRISE EDITORIAL DASHBOARD — CLEAN, PRODUCTION-GRADE SAAS STYLING
       ========================================================================== */

    /* 1. Header Greeting Section */
    .dashboard-header-bar {
        background: var(--admin-card-bg);
        border: 1px solid var(--admin-card-border);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        transition: background-color 0.25s ease, border-color 0.25s ease;
    }
    @media (min-width: 768px) {
        .dashboard-header-bar {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    .dashboard-greeting-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--admin-text-main);
        margin-bottom: 4px;
        letter-spacing: -0.3px;
    }
    .dashboard-greeting-sub {
        font-size: 13px;
        color: var(--admin-text-sub);
        margin: 0;
    }

    /* 2. Modern Metric KPI Cards */
    .metric-card {
        background: var(--admin-card-bg);
        border: 1px solid var(--admin-card-border);
        border-radius: 14px;
        padding: 18px 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        border-color: rgba(245, 166, 35, 0.45);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
    }
    [data-theme="dark"] .metric-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
    }
    .metric-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .metric-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--admin-text-muted);
    }
    .metric-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }
    .metric-value {
        font-size: 28px;
        font-weight: 800;
        line-height: 1.1;
        color: var(--admin-text-main);
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }
    .metric-footer {
        font-size: 12px;
        color: var(--admin-text-sub);
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    /* 3. Content Panel Cards */
    .content-panel {
        background: var(--admin-card-bg);
        border: 1px solid var(--admin-card-border);
        border-radius: 16px;
        padding: 22px;
        margin-bottom: 24px;
        transition: background-color 0.25s ease, border-color 0.25s ease;
    }
    .content-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 14px;
        margin-bottom: 18px;
        border-bottom: 1px solid var(--admin-border);
    }
    .content-panel-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--admin-text-main);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* 4. Clean Data Table */
    .clean-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .clean-table thead th {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--admin-text-muted);
        background: var(--admin-card-surface);
        padding: 10px 14px;
        border-bottom: 1px solid var(--admin-border);
        border-top: none;
    }
    .clean-table thead th:first-child {
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
    }
    .clean-table thead th:last-child {
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    .clean-table tbody tr {
        transition: background-color 0.15s ease;
    }
    .clean-table tbody tr:hover {
        background-color: var(--admin-card-surface);
    }
    .clean-table tbody td {
        padding: 14px;
        font-size: 13px;
        border-bottom: 1px solid var(--admin-border);
        vertical-align: middle;
        color: var(--admin-text-main);
    }
    .clean-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* 5. Clean Action Buttons */
    .btn-action-primary {
        background-color: #F5A623;
        color: #111827;
        font-size: 12.5px;
        font-weight: 700;
        padding: 7px 14px;
        border-radius: 8px;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.18s ease;
    }
    .btn-action-primary:hover {
        background-color: #E09210;
        color: #111827;
        transform: translateY(-1px);
    }
    .btn-action-subtle {
        background-color: var(--admin-card-surface);
        border: 1px solid var(--admin-border);
        color: var(--admin-text-main);
        font-size: 12.5px;
        font-weight: 600;
        padding: 7px 14px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.18s ease;
    }
    .btn-action-subtle:hover {
        border-color: rgba(245, 166, 35, 0.4);
        color: #F5A623;
        background-color: rgba(245, 166, 35, 0.06);
    }

    /* 6. Payment Row Card */
    .payment-item {
        background: var(--admin-card-surface);
        border: 1px solid var(--admin-border);
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        transition: border-color 0.18s ease, transform 0.18s ease;
    }
    @media (min-width: 576px) {
        .payment-item {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    .payment-item:hover {
        border-color: rgba(6, 182, 212, 0.45);
        transform: translateY(-1px);
    }

    /* 7. Quick Link List in Sidebar */
    .quick-nav-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 14px;
        border-radius: 10px;
        background: var(--admin-card-surface);
        border: 1px solid var(--admin-border);
        color: var(--admin-text-main);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.18s ease;
    }
    .quick-nav-item:hover {
        border-color: rgba(245, 166, 35, 0.4);
        color: #F5A623;
        background-color: rgba(245, 166, 35, 0.05);
    }
    .quick-nav-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- 1. HEADER SUMMARY & GREETING -->
    <div class="dashboard-header-bar">
        <div>
            <h1 class="dashboard-greeting-title">Ringkasan Operasional Redaksi</h1>
            <p class="dashboard-greeting-sub">
                Selamat datang kembali, <strong>{{ Auth::user()->name ?? 'Administrator' }}</strong> &bull; 
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('admin.submissions.index') }}" class="btn-action-subtle">
                <i class="bi bi-journal-text"></i>
                <span>Kelola Naskah</span>
            </a>
            <a href="{{ route('admin.payments.index') }}" class="btn-action-primary">
                <i class="bi bi-credit-card-2-front"></i>
                <span>Verifikasi Pembayaran</span>
            </a>
        </div>
    </div>

    <!-- 2. METRIC KPI CARDS (4 Columns) -->
    <div class="row g-3 mb-4">
        <!-- Metric 1: Naskah Masuk -->
        <div class="col-sm-6 col-xl-3">
            <div class="metric-card">
                <div>
                    <div class="metric-header">
                        <span class="metric-label">Total Naskah Masuk</span>
                        <div class="metric-icon-box" style="background: rgba(245, 166, 35, 0.14); color: #F5A623;">
                            <i class="bi bi-journals"></i>
                        </div>
                    </div>
                    <div class="metric-value">{{ $stats['total_submissions'] }}</div>
                </div>
                <div class="metric-footer">
                    @if($stats['pending_review'] > 0)
                        <span class="badge rounded-pill" style="background: rgba(245, 166, 35, 0.2); color: #F5A623; font-weight: 700;">
                            {{ $stats['pending_review'] }} Perlu Ditinjau
                        </span>
                    @else
                        <span class="text-success"><i class="bi bi-check2-circle me-1"></i> Semua telah ditinjau</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Metric 2: Verifikasi Pembayaran -->
        <div class="col-sm-6 col-xl-3">
            <div class="metric-card">
                <div>
                    <div class="metric-header">
                        <span class="metric-label">Menunggu Verifikasi Bayar</span>
                        <div class="metric-icon-box" style="background: rgba(6, 182, 212, 0.14); color: #06B6D4;">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                    <div class="metric-value">{{ $stats['pending_payments'] }}</div>
                </div>
                <div class="metric-footer">
                    @if($stats['pending_payments'] > 0)
                        <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="text-decoration-none fw-semibold" style="color: #06B6D4;">
                            Periksa bukti transfer &rarr;
                        </a>
                    @else
                        <span class="text-success"><i class="bi bi-check2-circle me-1"></i> Tidak ada antrean</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Metric 3: Produksi Berjalan -->
        <div class="col-sm-6 col-xl-3">
            <div class="metric-card">
                <div>
                    <div class="metric-header">
                        <span class="metric-label">Naskah Dalam Proses</span>
                        <div class="metric-icon-box" style="background: rgba(139, 92, 246, 0.14); color: #8B5CF6;">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                    </div>
                    <div class="metric-value">{{ $stats['in_progress'] }}</div>
                </div>
                <div class="metric-footer">
                    <span class="text-muted">
                        <i class="bi bi-vector-pen me-1"></i> {{ $stats['in_design'] }} dalam tata letak &bull; {{ $stats['total_designers'] }} editor
                    </span>
                </div>
            </div>
        </div>

        <!-- Metric 4: Buku Selesai -->
        <div class="col-sm-6 col-xl-3">
            <div class="metric-card">
                <div>
                    <div class="metric-header">
                        <span class="metric-label">Buku Telah Terbit</span>
                        <div class="metric-icon-box" style="background: rgba(16, 185, 129, 0.14); color: #10B981;">
                            <i class="bi bi-book-half"></i>
                        </div>
                    </div>
                    <div class="metric-value text-success">{{ $stats['completed'] }}</div>
                </div>
                <div class="metric-footer">
                    <span class="text-muted">
                        <i class="bi bi-people me-1"></i> Dari {{ $stats['total_authors'] }} penulis terdaftar
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. MAIN DASHBOARD CONTENT (Row 1: Primary Queue & Status Alur Produksi) -->
    <div class="row g-4 mb-4">
        
        <!-- Panel 1: Naskah Masuk Menunggu Tinjauan -->
        <div class="col-lg-7 col-xl-8">
            <div class="content-panel h-100 mb-0 d-flex flex-column">
                <div class="content-panel-header">
                    <h2 class="content-panel-title">
                        <i class="bi bi-inbox-fill text-warning"></i>
                        <span>Naskah Masuk Perlu Tindakan</span>
                        @if($pendingSubmissions->count() > 0)
                            <span class="badge rounded-pill" style="background: rgba(245, 166, 35, 0.15); color: #F5A623; font-size: 11px;">
                                {{ $pendingSubmissions->count() }}
                            </span>
                        @endif
                    </h2>
                    <a href="{{ route('admin.submissions.index', ['status' => 'pending_review']) }}" class="btn-action-subtle py-1 px-3" style="font-size: 12px;">
                        Lihat Semua
                    </a>
                </div>

                @if($pendingSubmissions->isEmpty())
                    <div class="text-center py-5 text-muted flex-grow-1 d-flex flex-column justify-content-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 52px; height: 52px; background: rgba(16, 185, 129, 0.12); color: #10B981; font-size: 24px;">
                            <i class="bi bi-check2"></i>
                        </div>
                        <h6 class="fw-bold mb-1" style="color: var(--admin-text-main);">Seluruh Naskah Telah Ditinjau</h6>
                        <span class="small">Tidak ada naskah dengan status menunggu tinjauan saat ini.</span>
                    </div>
                @else
                    <div class="table-responsive flex-grow-1">
                        <table class="clean-table">
                            <thead>
                                <tr>
                                    <th>Kode &amp; Naskah</th>
                                    <th>Penulis</th>
                                    <th>Kategori &amp; Spek</th>
                                    <th class="text-end">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingSubmissions as $sub)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="badge font-monospace" style="background: rgba(245, 166, 35, 0.12); color: #F5A623; font-size: 11px;">
                                                    {{ $sub->code ?? $sub->tracking_code }}
                                                </span>
                                                <span class="small text-muted" style="font-size: 11px;">
                                                    {{ $sub->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <span class="fw-bold d-block text-truncate" style="max-width: 270px; color: var(--admin-text-main);" title="{{ $sub->title }}">
                                                {{ $sub->title }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold d-block text-truncate" style="max-width: 150px; color: var(--admin-text-main); font-size: 13px;">
                                                {{ $sub->user->name ?? 'Penulis' }}
                                            </span>
                                            <small class="text-muted d-block" style="font-size: 11px;">
                                                {{ $sub->user->phone ?? 'Belum ada No HP' }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill" style="background: var(--admin-card-surface); border: 1px solid var(--admin-border); color: var(--admin-text-sub); font-size: 11px;">
                                                {{ $sub->category->name ?? 'Umum' }}
                                            </span>
                                            <small class="text-muted d-block mt-1" style="font-size: 11px;">
                                                {{ $sub->estimated_pages }} hal &bull; {{ $sub->quantity }} eks
                                            </small>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.submissions.show', $sub) }}" class="btn-action-primary py-1 px-3" style="font-size: 12px;">
                                                <span>Tinjau</span>
                                                <i class="bi bi-arrow-right"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Widget 1: Status Produksi Penerbitan -->
        <div class="col-lg-5 col-xl-4">
            <div class="content-panel h-100 mb-0 d-flex flex-column">
                <h2 class="content-panel-title mb-3">
                    <i class="bi bi-pie-chart-fill text-warning"></i>
                    <span>Status Alur Produksi</span>
                </h2>
                <p class="text-muted small mb-3" style="font-size: 12px;">
                    Distribusi naskah yang saat ini aktif dalam tahapan operasional penerbitan:
                </p>

                <div class="d-flex flex-column gap-2 flex-grow-1 justify-content-between">
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3" style="background: var(--admin-card-surface);">
                        <span class="small" style="color: var(--admin-text-main);">
                            <i class="bi bi-inbox text-warning me-2"></i>Menunggu Tinjauan Redaksi
                        </span>
                        <span class="badge rounded-pill" style="background: rgba(245, 166, 35, 0.2); color: #F5A623; font-weight: 700;">
                            {{ $stats['pending_review'] }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3" style="background: var(--admin-card-surface);">
                        <span class="small" style="color: var(--admin-text-main);">
                            <i class="bi bi-wallet2 text-info me-2"></i>Verifikasi Bukti Transfer
                        </span>
                        <span class="badge rounded-pill" style="background: rgba(6, 182, 212, 0.2); color: #06B6D4; font-weight: 700;">
                            {{ $stats['pending_payments'] }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3" style="background: var(--admin-card-surface);">
                        <span class="small" style="color: var(--admin-text-main);">
                            <i class="bi bi-vector-pen text-primary me-2"></i>Tata Letak &amp; Desain Sampul
                        </span>
                        <span class="badge rounded-pill" style="background: rgba(139, 92, 246, 0.2); color: #8B5CF6; font-weight: 700;">
                            {{ $stats['in_design'] }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3" style="background: var(--admin-card-surface);">
                        <span class="small" style="color: var(--admin-text-main);">
                            <i class="bi bi-check2-circle text-success me-2"></i>Naskah Selesai / Terbit
                        </span>
                        <span class="badge rounded-pill" style="background: rgba(16, 185, 129, 0.2); color: #10B981; font-weight: 700;">
                            {{ $stats['completed'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- MAIN DASHBOARD CONTENT (Row 2: Payments Queue & Pintasan Manajemen) -->
    <div class="row g-4 mb-4">
        
        <!-- Panel 2: Antrean Verifikasi Transfer Bank -->
        <div class="col-lg-7 col-xl-8">
            <div class="content-panel h-100 mb-0 d-flex flex-column">
                <div class="content-panel-header">
                    <h2 class="content-panel-title">
                        <i class="bi bi-credit-card-2-front-fill text-info"></i>
                        <span>Bukti Transfer Menunggu Verifikasi</span>
                    </h2>
                    <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="btn-action-subtle py-1 px-3" style="font-size: 12px;">
                        Semua Pembayaran
                    </a>
                </div>

                @if($pendingPayments->isEmpty())
                    <div class="text-center py-5 text-muted flex-grow-1 d-flex flex-column justify-content-center">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 52px; height: 52px; background: rgba(6, 182, 212, 0.12); color: #06B6D4; font-size: 24px;">
                            <i class="bi bi-check2"></i>
                        </div>
                        <h6 class="fw-bold mb-1" style="color: var(--admin-text-main);">Tidak Ada Pembayaran Menunggu</h6>
                        <span class="small">Seluruh bukti transfer yang diunggah penulis telah selesai divalidasi.</span>
                    </div>
                @else
                    <div class="d-flex flex-column gap-2 flex-grow-1">
                        @foreach($pendingPayments as $pay)
                            <div class="payment-item">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="metric-icon-box" style="background: rgba(6, 182, 212, 0.12); color: #06B6D4; font-size: 19px;">
                                        <i class="bi bi-bank"></i>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge" style="background: rgba(245, 166, 35, 0.15); color: #F5A623; font-weight: 700; font-size: 10px;">
                                                {{ $pay->type_label ?? 'Tagihan Penerbitan' }}
                                            </span>
                                            <span class="fw-bold" style="color: var(--admin-text-main); font-size: 14px;">
                                                Rp {{ number_format($pay->amount_due, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="d-block text-truncate" style="max-width: 340px; color: var(--admin-text-sub); font-size: 12.5px;">
                                            <strong>{{ $pay->submission->title ?? 'Naskah' }}</strong> &bull; {{ $pay->submission->user->name ?? 'Penulis' }}
                                        </span>
                                        <small class="text-muted" style="font-size: 11px;">
                                            Tujuan: {{ $pay->account->bank_name ?? 'Bank Penerbit' }} ({{ $pay->account->account_number ?? '-' }})
                                        </small>
                                    </div>
                                </div>
                                <div class="mt-2 mt-sm-0">
                                    <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="btn-action-primary py-1 px-3" style="font-size: 12px;">
                                        <i class="bi bi-search"></i>
                                        <span>Periksa Transfer</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Widget 2: Pintasan Manajemen -->
        <div class="col-lg-5 col-xl-4">
            <div class="content-panel h-100 mb-0 d-flex flex-column">
                <h2 class="content-panel-title mb-3">
                    <i class="bi bi-link-45deg text-warning"></i>
                    <span>Pintasan Manajemen</span>
                </h2>
                
                <div class="d-flex flex-column gap-2 flex-grow-1 justify-content-between">
                    <a href="{{ route('admin.featuredBooks.index') }}" class="quick-nav-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="quick-nav-icon" style="background: rgba(245, 166, 35, 0.15); color: #F5A623;">
                                <i class="bi bi-collection-play-fill"></i>
                            </div>
                            <div>
                                <span class="d-block">Buku Pilihan &amp; Testimoni</span>
                                <small class="text-muted fw-normal" style="font-size: 11px;">Kelola coverflow &amp; ulasan beranda</small>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted" style="font-size: 11px;"></i>
                    </a>

                    <a href="{{ route('admin.paymentAccounts.index') }}" class="quick-nav-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="quick-nav-icon" style="background: rgba(6, 182, 212, 0.15); color: #06B6D4;">
                                <i class="bi bi-bank"></i>
                            </div>
                            <div>
                                <span class="d-block">Rekening Bank Resmi</span>
                                <small class="text-muted fw-normal" style="font-size: 11px;">Kelola rekening transfer penerbit</small>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted" style="font-size: 11px;"></i>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="quick-nav-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="quick-nav-icon" style="background: rgba(139, 92, 246, 0.15); color: #8B5CF6;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <span class="d-block">Manajemen Pengguna</span>
                                <small class="text-muted fw-normal" style="font-size: 11px;">Kelola akun penulis, editor, &amp; admin</small>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted" style="font-size: 11px;"></i>
                    </a>

                    <a href="{{ route('admin.activityLogs.index') }}" class="quick-nav-item">
                        <div class="d-flex align-items-center gap-3">
                            <div class="quick-nav-icon" style="background: rgba(16, 185, 129, 0.15); color: #10B981;">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <span class="d-block">Log Aktivitas Administrator</span>
                                <small class="text-muted fw-normal" style="font-size: 11px;">Riwayat login &amp; tindakan sistem</small>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-muted" style="font-size: 11px;"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- 4. FULL-WIDTH: LOG AKTIVITAS (Super Admin) -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="content-panel">
                <div class="content-panel-header">
                    <h2 class="content-panel-title">
                        <i class="bi bi-shield-check text-warning"></i>
                        <span>Log Aktivitas Administrator</span>
                    </h2>
                    <a href="{{ route('admin.activityLogs.index') }}" class="btn-action-subtle py-1 px-3" style="font-size: 12px;">
                        <span>Lihat Semua Log</span>
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="clean-table">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Administrator</th>
                                <th>Peran</th>
                                <th>Aksi</th>
                                <th>IP Address</th>
                                <th>Perangkat / Browser</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $act)
                                <tr>
                                    <td class="text-muted small" style="white-space: nowrap; font-size: 12px;">
                                        <i class="bi bi-clock me-1 text-muted"></i>
                                        {{ $act->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        @if($act->user)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold" style="width: 28px; height: 28px; font-size: 11px; background: rgba(245, 166, 35, 0.15); color: #F5A623;">
                                                    {{ strtoupper(substr($act->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="fw-semibold d-block" style="color: var(--admin-text-main); font-size: 12.5px;">{{ $act->user->name }}</span>
                                                    <small class="text-muted" style="font-size: 11px;">{{ $act->user->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">Admin Terhapus</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge" style="background: rgba(245, 166, 35, 0.15); color: #F5A623; font-size: 11px; font-weight: 700;">
                                            <i class="bi bi-shield-check me-1"></i>Admin
                                        </span>
                                    </td>
                                    <td>
                                        @if(str_contains($act->action, 'login'))
                                            <span class="badge font-monospace" style="background: rgba(16, 185, 129, 0.15); color: #10B981; font-size: 11px; border: 1px solid rgba(16, 185, 129, 0.3);">
                                                <i class="bi bi-box-arrow-in-right me-1"></i>{{ $act->action_label }}
                                            </span>
                                        @elseif(str_contains($act->action, 'logout'))
                                            <span class="badge font-monospace" style="background: rgba(239, 68, 68, 0.12); color: #EF4444; font-size: 11px; border: 1px solid rgba(239, 68, 68, 0.25);">
                                                <i class="bi bi-box-arrow-right me-1"></i>{{ $act->action_label }}
                                            </span>
                                        @else
                                            <span class="badge border font-monospace" style="background: var(--admin-card-surface); color: var(--admin-text-main); font-size: 11px;">
                                                {{ $act->action }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="small font-monospace text-muted" style="font-size: 12px; white-space: nowrap;">
                                        <i class="bi bi-globe me-1"></i>{{ $act->ip_address ?: '-' }}
                                    </td>
                                    <td class="small text-muted" style="font-size: 12px; white-space: nowrap;">
                                        {!! $act->device !!}
                                    </td>
                                    <td class="small" style="color: var(--admin-text-sub); max-width: 320px;">
                                        {{ $act->description }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted small">
                                        <i class="bi bi-info-circle me-1"></i>Belum ada aktivitas tercatat hari ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
