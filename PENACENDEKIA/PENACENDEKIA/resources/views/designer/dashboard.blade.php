@extends('layouts.admin')

@section('title', 'Dashboard — PT. Pena Cendekia')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* ==========================================================================
       EDITOR & DESIGNER WORKSTATION — CLEAN ENTERPRISE STYLING
       ========================================================================== */

    /* 1. Header Bar */
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

    /* 2. Metric KPI Cards */
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

    /* 3. Panel Container */
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

    /* 4. Clean Table */
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

    /* 5. Clean Buttons */
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
        background-color: rgba(245, 166, 35, 0.05);
    }

    /* 6. Quick Nav Items & Guidelines */
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

    <!-- 1. HEADER SUMMARY -->
    <div class="dashboard-header-bar">
        <div>
            <h1 class="dashboard-greeting-title">Ruang Kerja Redaksi &amp; Desain</h1>
            <p class="dashboard-greeting-sub">
                Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong> &bull; 
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge py-2 px-3 rounded-pill" style="background: rgba(139, 92, 246, 0.14); color: #8B5CF6; font-size: 12px; font-weight: 700;">
                <i class="bi bi-palette me-1"></i> Editor &amp; Desainer Buku
            </span>
        </div>
    </div>

    <!-- 2. METRIC KPI CARDS -->
    <div class="row g-3 mb-4">
        <!-- Metric 1: Total Tugas -->
        <div class="col-6 col-xl-3">
            <div class="metric-card">
                <div>
                    <div class="metric-header">
                        <span class="metric-label">Total Pekerjaan</span>
                        <div class="metric-icon-box" style="background: rgba(100, 116, 139, 0.12); color: var(--admin-text-sub);">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                    </div>
                    <div class="metric-value">{{ $stats['total'] }}</div>
                </div>
                <div class="metric-footer">
                    <span>Semua tugas yang ditugaskan admin</span>
                </div>
            </div>
        </div>

        <!-- Metric 2: Belum Dimulai -->
        <div class="col-6 col-xl-3">
            <div class="metric-card">
                <div>
                    <div class="metric-header">
                        <span class="metric-label">Belum Dikerjakan</span>
                        <div class="metric-icon-box" style="background: rgba(245, 166, 35, 0.14); color: #F5A623;">
                            <i class="bi bi-clock"></i>
                        </div>
                    </div>
                    <div class="metric-value" style="color: #F5A623;">{{ $stats['pending'] }}</div>
                </div>
                <div class="metric-footer">
                    @if($stats['pending'] > 0)
                        <span class="badge rounded-pill" style="background: rgba(245, 166, 35, 0.2); color: #F5A623; font-weight: 700;">
                            Perlu dimulai segera
                        </span>
                    @else
                        <span class="text-success"><i class="bi bi-check2"></i> Tidak ada antrean</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Metric 3: Sedang Dikerjakan -->
        <div class="col-6 col-xl-3">
            <div class="metric-card">
                <div>
                    <div class="metric-header">
                        <span class="metric-label">Sedang Berjalan</span>
                        <div class="metric-icon-box" style="background: rgba(6, 182, 212, 0.14); color: #06B6D4;">
                            <i class="bi bi-vector-pen"></i>
                        </div>
                    </div>
                    <div class="metric-value" style="color: #06B6D4;">{{ $stats['in_progress'] }}</div>
                </div>
                <div class="metric-footer">
                    <span>Dalam proses penataan &amp; desain</span>
                </div>
            </div>
        </div>

        <!-- Metric 4: Sedang Dicetak -->
        <div class="col-6 col-xl-3">
            <div class="metric-card">
                <div>
                    <div class="metric-header">
                        <span class="metric-label">Proses Percetakan</span>
                        <div class="metric-icon-box" style="background: rgba(16, 185, 129, 0.14); color: #10B981;">
                            <i class="bi bi-printer"></i>
                        </div>
                    </div>
                    <div class="metric-value" style="color: #10B981;">{{ $stats['printing'] }}</div>
                </div>
                <div class="metric-footer">
                    @if($stats['printing'] > 0)
                        <span class="badge rounded-pill bg-success" style="font-size: 11px;">Sedang di mesin cetak</span>
                    @else
                        <span class="text-muted"><i class="bi bi-check2 me-1"></i> Tidak ada antrean cetak</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 3. TWO-COLUMN WORKSTATION LAYOUT (Matching Full Admin Dashboard) -->
    <div class="row g-4">

        <!-- ======================= LEFT COLUMN (Printing Queues & Active Design Tasks) ======================= -->
        <div class="col-lg-7 col-xl-8">

            <!-- 3.1. PRINTING QUEUE SECTION (Jika ada naskah yang sedang dicetak) -->
            @if($printingSubmissions->isNotEmpty())
                <div class="content-panel mb-4 border border-info shadow-sm" style="background: var(--admin-card-bg);">
            <div class="content-panel-header d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: rgba(6, 182, 212, 0.08); border-bottom: 1.5px solid rgba(6, 182, 212, 0.25);">
                <div class="d-flex align-items-center gap-2">
                    <div class="spinner-grow spinner-grow-sm text-info" role="status"></div>
                    <h2 class="content-panel-title mb-0 text-info">
                        <i class="bi bi-printer-fill me-1"></i>
                        <span>Tugas Percetakan Buku (Sedang Dicetak)</span>
                    </h2>
                </div>
                <span class="badge bg-info text-dark fw-bold px-3 py-1.5 rounded-pill">
                    {{ $printingSubmissions->count() }} Naskah Dalam Antrean Mesin
                </span>
            </div>

            <div class="p-3">
                <div class="d-flex flex-column gap-3">
                    @foreach($printingSubmissions as $pSub)
                        <div class="p-3 rounded-3 border" style="background: var(--admin-card-surface, rgba(255,255,255,0.03)); border-color: var(--admin-card-border) !important;">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge font-monospace" style="background: rgba(245, 166, 35, 0.15); color: #F5A623;">
                                            {{ $pSub->code }}
                                        </span>
                                        <span class="badge bg-info text-dark rounded-pill">Sedang Dicetak</span>
                                    </div>
                                    <h5 class="fw-bold mb-1" style="color: var(--admin-text-main);">{{ $pSub->title }}</h5>
                                    <small class="text-muted">
                                        Penulis: <strong>{{ $pSub->user->name }}</strong> &bull; Masuk antrean cetak: {{ $pSub->printing_started_at?->format('d M Y, H:i') ?? '-' }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-sm btn-success fw-bold px-3 py-2 shadow-sm d-flex align-items-center gap-1" data-bs-toggle="collapse" data-bs-target="#confirmFinishPrint-{{ $pSub->id }}">
                                        <i class="bi bi-check2-circle fs-6"></i>
                                        <span>Tandai Selesai Cetak</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Spesifikasi Cetak & File Master Siap Cetak -->
                            <div class="p-3 rounded-3 mb-2" style="background: rgba(0,0,0,0.04); border: 1px dashed var(--admin-card-border);">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-6">
                                        <div class="small fw-semibold text-muted mb-1">Spesifikasi Percetakan:</div>
                                        <div class="small" style="color: var(--admin-text-main);">
                                            <i class="bi bi-book me-1 text-primary"></i> <strong>{{ $pSub->estimated_pages }}</strong> Halaman Isi &bull;
                                            <i class="bi bi-layers me-1 text-success"></i> <strong>{{ $pSub->quantity }}</strong> Eksemplar &bull;
                                            <i class="bi bi-file-earmark-text me-1 text-warning"></i> Cover <strong>{{ ucfirst($pSub->cover_type) }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <div class="small fw-semibold text-muted mb-1">Berkas Siap Cetak:</div>
                                        <div class="d-inline-flex gap-2">
                                            @if($pSub->layoutTask?->result_file)
                                                <a href="{{ route('file.show', ['type' => 'design_layouts', 'filename' => $pSub->layoutTask->result_file]) }}" target="_blank" class="btn btn-xs btn-outline-info py-1 px-2">
                                                    <i class="bi bi-file-earmark-pdf me-1"></i> Layout Final
                                                </a>
                                            @endif
                                            @if($pSub->coverTask?->result_file)
                                                <a href="{{ route('file.show', ['type' => 'design_covers', 'filename' => $pSub->coverTask->result_file]) }}" target="_blank" class="btn btn-xs btn-outline-purple py-1 px-2">
                                                    <i class="bi bi-image me-1"></i> Cover Final
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Inline Confirmation Selesai Cetak (Tanpa Browser Confirm) -->
                            <div class="collapse" id="confirmFinishPrint-{{ $pSub->id }}">
                                <div class="p-3 rounded-3 mt-2 border border-success" style="background: rgba(16, 185, 129, 0.08);">
                                    <div class="d-flex align-items-start gap-2 mb-2">
                                        <i class="bi bi-question-circle-fill text-success fs-5"></i>
                                        <div>
                                            <strong class="d-block text-success">Konfirmasi Selesai Cetak: {{ $pSub->title }}</strong>
                                            <span class="small text-muted">Pastikan seluruh {{ $pSub->quantity }} eksemplar buku fisik telah selesai dicetak, dijilid, dan siap untuk diserahterimakan. Dengan menandai selesai, sistem otomatis membuka penagihan Pelunasan (50%) kepada Penulis.</span>
                                        </div>
                                    </div>
                                    <form action="{{ route('designer.submissions.finish-printing', $pSub) }}" method="POST" class="d-flex justify-content-end gap-2 mt-2">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-toggle="collapse" data-bs-target="#confirmFinishPrint-{{ $pSub->id }}">
                                            Batal
                                        </button>
                                        <button type="submit" class="btn btn-sm btn-success fw-bold px-3">
                                            <i class="bi bi-check2-circle me-1"></i> Ya, Tandai Selesai Cetak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- 3. TASKS TABLE PANEL -->
    <div class="content-panel">
        <div class="content-panel-header">
            <h2 class="content-panel-title">
                <i class="bi bi-list-task text-warning"></i>
                <span>Daftar Pekerjaan Desain &amp; Tata Letak</span>
                @if($tasks->total() > 0)
                    <span class="badge rounded-pill" style="background: rgba(245, 166, 35, 0.15); color: #F5A623; font-size: 11px;">
                        {{ $tasks->total() }} Tugas
                    </span>
                @endif
            </h2>
        </div>

        @if($tasks->isEmpty())
            <div class="text-center py-5 text-muted">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px; background: rgba(100, 116, 139, 0.12); color: var(--admin-text-sub); font-size: 24px;">
                    <i class="bi bi-palette"></i>
                </div>
                <h6 class="fw-bold mb-1" style="color: var(--admin-text-main);">Belum Ada Tugas Ditugaskan</h6>
                <span class="small">Saat admin menugaskan naskah untuk layout isi atau desain cover, tugas akan muncul di sini.</span>
            </div>
        @else
            <div class="table-responsive">
                <table class="clean-table">
                    <thead>
                        <tr>
                            <th>Kode &amp; Naskah</th>
                            <th>Jenis Pekerjaan</th>
                            <th>Spesifikasi</th>
                            <th>Status Pengerjaan</th>
                            <th>Tinjauan Admin</th>
                            <th>Waktu Masuk</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $t)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge font-monospace" style="background: rgba(245, 166, 35, 0.12); color: #F5A623; font-size: 11px;">
                                            {{ $t->submission->code }}
                                        </span>
                                    </div>
                                    <span class="fw-bold d-block text-truncate" style="max-width: 250px; color: var(--admin-text-main);" title="{{ $t->submission->title }}">
                                        {{ $t->submission->title }}
                                    </span>
                                    <small class="text-muted d-block" style="font-size: 11px;">
                                        Penulis: {{ $t->submission->user->name }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge rounded-pill py-2 px-3" style="background: {{ $t->task_type === 'layout' ? 'rgba(6, 182, 212, 0.15)' : 'rgba(139, 92, 246, 0.15)' }}; color: {{ $t->task_type === 'layout' ? '#06B6D4' : '#8B5CF6' }}; font-size: 11.5px; font-weight: 700;">
                                        <i class="bi {{ $t->task_type === 'layout' ? 'bi-layout-text-sidebar' : 'bi-image' }} me-1"></i>
                                        {{ $t->task_type_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="d-block small" style="color: var(--admin-text-main);">
                                        {{ $t->submission->estimated_pages }} Hal &bull; {{ $t->submission->quantity }} Eks
                                    </span>
                                    <small class="text-muted" style="font-size: 11px;">
                                        {{ ucfirst($t->submission->cover_type) }} Cover
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $t->status_badge }} rounded-pill px-3 py-1" style="font-size: 11px;">
                                        {{ $t->status_label }}
                                    </span>
                                </td>
                                <td>
                                    @if($t->admin_approved === true)
                                        <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 11px;">
                                            <i class="bi bi-check2"></i> Disetujui
                                        </span>
                                    @elseif($t->admin_approved === false)
                                        <span class="badge bg-danger rounded-pill px-2 py-1" style="font-size: 11px;">
                                            <i class="bi bi-x"></i> Perlu Revisi
                                        </span>
                                    @elseif($t->status === 'done')
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-1" style="font-size: 11px;">
                                            <i class="bi bi-clock"></i> Menunggu Review
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted" style="font-size: 11.5px;">
                                        {{ $t->assigned_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('designer.tasks.show', $t) }}" class="btn-action-primary py-1 px-3" style="font-size: 12px;">
                                        <span>Buka Tugas</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-3 border-top" style="border-color: var(--admin-border) !important;">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>

        </div>

        <!-- ======================= RIGHT COLUMN (Workload Breakdown, Guidelines & Redaksi Card) ======================= -->
        <div class="col-lg-5 col-xl-4">

            <!-- Widget 1: Ringkasan Beban Kerja Editor -->
            <div class="content-panel">
                <h2 class="content-panel-title mb-3">
                    <i class="bi bi-pie-chart-fill text-warning"></i>
                    <span>Status Beban Kerja Editor</span>
                </h2>
                <p class="text-muted small mb-3" style="font-size: 12.5px;">
                    Distribusi pekerjaan desain &amp; layout yang ditugaskan kepada Anda:
                </p>

                <div class="d-flex flex-column gap-2.5">
                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border" style="background: var(--admin-card-surface); border-color: var(--admin-border) !important;">
                        <span class="small fw-semibold" style="color: var(--admin-text-main);">
                            <i class="bi bi-clock-history text-warning me-2 fs-6"></i>Belum Dikerjakan
                        </span>
                        <span class="badge rounded-pill bg-warning text-dark fw-bold px-3 py-1.5" style="font-size: 12px;">
                            {{ $stats['pending'] }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border" style="background: var(--admin-card-surface); border-color: var(--admin-border) !important;">
                        <span class="small fw-semibold" style="color: var(--admin-text-main);">
                            <i class="bi bi-vector-pen text-info me-2 fs-6"></i>Sedang Dikerjakan
                        </span>
                        <span class="badge rounded-pill bg-info text-dark fw-bold px-3 py-1.5" style="font-size: 12px;">
                            {{ $stats['in_progress'] }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border" style="background: var(--admin-card-surface); border-color: var(--admin-border) !important;">
                        <span class="small fw-semibold" style="color: var(--admin-text-main);">
                            <i class="bi bi-exclamation-diamond text-danger me-2 fs-6"></i>Perlu Revisi Desain
                        </span>
                        <span class="badge rounded-pill bg-danger text-white fw-bold px-3 py-1.5" style="font-size: 12px;">
                            {{ $stats['revision'] ?? 0 }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border" style="background: var(--admin-card-surface); border-color: var(--admin-border) !important;">
                        <span class="small fw-semibold" style="color: var(--admin-text-main);">
                            <i class="bi bi-check2-circle text-success me-2 fs-6"></i>Menunggu Tinjauan Admin
                        </span>
                        <span class="badge rounded-pill bg-success text-white fw-bold px-3 py-1.5" style="font-size: 12px;">
                            {{ $stats['done'] }}
                        </span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-2.5 rounded-3 border" style="background: var(--admin-card-surface); border-color: var(--admin-border) !important;">
                        <span class="small fw-semibold" style="color: var(--admin-text-main);">
                            <i class="bi bi-printer-fill text-primary me-2 fs-6"></i>Proses Mesin Percetakan
                        </span>
                        <span class="badge rounded-pill bg-primary text-white fw-bold px-3 py-1.5" style="font-size: 12px;">
                            {{ $stats['printing'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Widget 2: Standar Format Buku & Cetak -->
            <div class="content-panel">
                <h2 class="content-panel-title mb-3">
                    <i class="bi bi-rulers text-warning"></i>
                    <span>Standar Ukuran &amp; Cetak</span>
                </h2>
                <div class="d-flex flex-column gap-3">
                    <div class="p-3 rounded-3 border shadow-xs" style="background: var(--admin-card-surface); border-left: 4.5px solid #F5A623 !important; border-color: var(--admin-border);">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <strong class="small fw-bold" style="color: var(--admin-text-main); font-size: 13.5px;">
                                <i class="bi bi-book me-1.5 text-warning"></i> Format A5 Standar
                            </strong>
                            <span class="badge font-monospace px-2.5 py-1 rounded" style="background: rgba(245, 166, 35, 0.18); color: #F5A623; font-size: 11px; font-weight: 700; border: 1px solid rgba(245, 166, 35, 0.3);">
                                14.8 &times; 21.0 cm
                            </span>
                        </div>
                        <small class="text-muted d-block" style="font-size: 12px; line-height: 1.4;">Paling umum untuk novel, kumpulan puisi, dan buku populer.</small>
                    </div>

                    <div class="p-3 rounded-3 border shadow-xs" style="background: var(--admin-card-surface); border-left: 4.5px solid #06B6D4 !important; border-color: var(--admin-border);">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <strong class="small fw-bold" style="color: var(--admin-text-main); font-size: 13.5px;">
                                <i class="bi bi-journal-text me-1.5 text-info"></i> Format UNESCO
                            </strong>
                            <span class="badge font-monospace px-2.5 py-1 rounded" style="background: rgba(6, 182, 212, 0.18); color: #06B6D4; font-size: 11px; font-weight: 700; border: 1px solid rgba(6, 182, 212, 0.3);">
                                15.5 &times; 23.0 cm
                            </span>
                        </div>
                        <small class="text-muted d-block" style="font-size: 12px; line-height: 1.4;">Standar buku ajar perguruan tinggi, monograf, dan riset ilmiah.</small>
                    </div>

                    <div class="p-3 rounded-3 border shadow-xs" style="background: var(--admin-card-surface); border-left: 4.5px solid #8B5CF6 !important; border-color: var(--admin-border);">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <strong class="small fw-bold" style="color: var(--admin-text-main); font-size: 13.5px;">
                                <i class="bi bi-file-earmark-ruled me-1.5" style="color: #8B5CF6;"></i> Format B5 / Buku Kerja
                            </strong>
                            <span class="badge font-monospace px-2.5 py-1 rounded" style="background: rgba(139, 92, 246, 0.18); color: #8B5CF6; font-size: 11px; font-weight: 700; border: 1px solid rgba(139, 92, 246, 0.3);">
                                17.6 &times; 25.0 cm
                            </span>
                        </div>
                        <small class="text-muted d-block" style="font-size: 12px; line-height: 1.4;">Modul pelatihan praktis, prosiding seminar, dan workbook.</small>
                    </div>
                </div>
            </div>

            <!-- Widget 3: Pedoman Desain & QC Redaksi -->
            <div class="content-panel">
                <h2 class="content-panel-title mb-3">
                    <i class="bi bi-patch-check-fill text-warning"></i>
                    <span>Pedoman Desain &amp; QC</span>
                </h2>
                <div class="d-flex flex-column gap-2" style="font-size: 12.5px;">
                    <div class="d-flex align-items-start gap-2.5 p-2.5 rounded-3 border" style="background: var(--admin-card-surface); border-color: var(--admin-border) !important;">
                        <i class="bi bi-check-circle-fill text-success fs-6 mt-0.5 flex-shrink-0"></i>
                        <span style="color: var(--admin-text-main); line-height: 1.45;"><strong>Bleed &amp; Margin:</strong> Wajib sisipkan bleed cover minimal <strong>3 mm</strong> di tiap sisi luar.</span>
                    </div>
                    <div class="d-flex align-items-start gap-2.5 p-2.5 rounded-3 border" style="background: var(--admin-card-surface); border-color: var(--admin-border) !important;">
                        <i class="bi bi-check-circle-fill text-success fs-6 mt-0.5 flex-shrink-0"></i>
                        <span style="color: var(--admin-text-main); line-height: 1.45;"><strong>Resolusi Berkas:</strong> Gunakan minimal <strong>300 DPI</strong> (CMYK) untuk berkas cetak cover fisik.</span>
                    </div>
                    <div class="d-flex align-items-start gap-2.5 p-2.5 rounded-3 border" style="background: var(--admin-card-surface); border-color: var(--admin-border) !important;">
                        <i class="bi bi-check-circle-fill text-success fs-6 mt-0.5 flex-shrink-0"></i>
                        <span style="color: var(--admin-text-main); line-height: 1.45;"><strong>Punggung Buku (Spine):</strong> Hitung ketebalan berdasarkan jenis kertas (Bookpaper / HVS) dan jumlah halaman.</span>
                    </div>
                </div>
            </div>

            <!-- Widget 4: Card Profil Redaksi PT. Pena Cendekia -->
            <div class="content-panel text-center p-4">
                <div class="d-inline-flex p-2.5 rounded-3 mb-3" style="background: #FFFFFF; border: 1.5px solid var(--admin-border); box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
                    <img src="{{ asset('images/pena.png') }}" alt="Pena Cendekia" style="height: 48px; width: 48px; object-fit: contain;">
                </div>
                <h6 class="fw-bold mb-1" style="color: var(--admin-text-main); font-size: 15.5px;">PT. Pena Cendekia Pustaka</h6>
                <p class="text-muted small mb-3" style="font-size: 12px;">
                    Divisi Redaksi &amp; Percetakan Resmi Penerbitan Buku
                </p>
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill" style="background: rgba(16, 185, 129, 0.15); color: #10B981; font-size: 12px; font-weight: 700; border: 1.5px solid rgba(16, 185, 129, 0.3);">
                    <i class="bi bi-circle-fill" style="font-size: 8px;"></i> Workstation Online &amp; Terhubung
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
