@extends('layouts.admin')

@section('title', 'Log Aktivitas Administrator — PT. Pena Cendekia')
@section('page-title', 'Log Aktivitas Login')

@push('styles')
<style>
    .log-stat-card {
        background: var(--admin-card-bg);
        border: 1.5px solid var(--admin-card-border);
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .log-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    }
    .log-stat-value {
        font-size: 24px;
        font-weight: 800;
        font-family: var(--font-heading);
        color: var(--admin-text-main);
        line-height: 1.1;
    }
    .log-stat-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--admin-text-sub);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .log-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    /* Clean Custom Pagination Styling */
    .pagination {
        margin-bottom: 0;
        gap: 4px;
        display: flex;
        flex-wrap: wrap;
    }
    .page-item .page-link {
        color: var(--admin-text-main);
        background-color: var(--admin-card-bg);
        border: 1px solid var(--admin-border);
        border-radius: 8px !important;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
    }
    .page-item .page-link:hover {
        background-color: rgba(245, 166, 35, 0.15);
        border-color: #F5A623;
        color: #F5A623;
    }
    .page-item.active .page-link {
        background-color: #F5A623 !important;
        border-color: #F5A623 !important;
        color: #111827 !important;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(245, 166, 35, 0.35);
    }
    .page-item.disabled .page-link {
        color: #94A3B8;
        background-color: transparent;
        border-color: var(--admin-border);
        opacity: 0.6;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge" style="background: rgba(245, 166, 35, 0.15); color: #F5A623; font-weight: 700; font-size: 11px;">
                    <i class="bi bi-shield-lock-fill me-1"></i>Keamanan Administrator
                </span>
                <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #10B981; font-weight: 700; font-size: 11px;">
                    <i class="bi bi-database-check me-1"></i>Maksimal 100 Log Terbaru
                </span>
            </div>
            <h2 class="fw-bold font-serif mb-1" style="color: var(--admin-text-main);">Log Aktivitas Administrator</h2>
            <p class="text-muted small mb-0">Riwayat sesi masuk, keluar, dan tindakan penting tim Super Admin (dibatasi 100 data terbaru untuk optimalisasi database).</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.activityLogs.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 py-2 d-inline-flex align-items-center gap-2">
                <i class="bi bi-arrow-clockwise"></i>
                <span>Muat Ulang</span>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-brand btn-sm px-3 py-2">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Quick Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="log-stat-card">
                <div>
                    <div class="log-stat-value text-success">{{ number_format($adminLoginToday, 0, ',', '.') }}</div>
                    <div class="log-stat-label mt-1">Login Admin Hari Ini</div>
                </div>
                <div class="log-stat-icon" style="background: rgba(16, 185, 129, 0.15); color: #10B981;">
                    <i class="bi bi-box-arrow-in-right"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="log-stat-card">
                <div>
                    <div class="log-stat-value" style="color: #F5A623;">{{ number_format($totalStoredLogs, 0, ',', '.') }} / 100</div>
                    <div class="log-stat-label mt-1">Kapasitas Log Disimpan</div>
                </div>
                <div class="log-stat-icon" style="background: rgba(245, 166, 35, 0.15); color: #F5A623;">
                    <i class="bi bi-database"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="log-stat-card">
                <div>
                    <div class="log-stat-value text-info" style="font-size: 18px;">
                        @if($lastAdminLogin)
                            {{ $lastAdminLogin->created_at->format('H:i') }} <span style="font-size: 13px; font-weight: 600;">({{ $lastAdminLogin->created_at->format('d/m') }})</span>
                        @else
                            -
                        @endif
                    </div>
                    <div class="log-stat-label mt-1">Sesi Masuk Terakhir</div>
                </div>
                <div class="log-stat-icon" style="background: rgba(6, 182, 212, 0.15); color: #06B6D4;">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="log-stat-card">
                <div>
                    <div class="log-stat-value text-primary">{{ number_format($activeAdminSessions, 0, ',', '.') }}</div>
                    <div class="log-stat-label mt-1">Admin Aktif (24 Jam)</div>
                </div>
                <div class="log-stat-icon" style="background: rgba(59, 130, 246, 0.15); color: #3B82F6;">
                    <i class="bi bi-person-check-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card (Khusus Admin) -->
    <div class="card card-soft p-3 mb-4">
        <form action="{{ route('admin.activityLogs.index') }}" method="GET" class="row g-2 align-items-center">
            
            <!-- Pencarian Teks -->
            <div class="col-lg-6 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" value="{{ request('search') }}" placeholder="Cari nama admin, email, IP address, atau aksi...">
                </div>
            </div>

            <!-- Filter Tipe Aksi -->
            <div class="col-lg-3 col-md-3">
                <select name="action_type" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="">-- Semua Aksi Administrator --</option>
                    <option value="login" {{ request('action_type') == 'login' ? 'selected' : '' }}>Hanya Login (Masuk)</option>
                    <option value="logout" {{ request('action_type') == 'logout' ? 'selected' : '' }}>Hanya Logout (Keluar)</option>
                    <option value="auth" {{ request('action_type') == 'auth' ? 'selected' : '' }}>Semua Aktivitas Auth</option>
                    <option value="system" {{ request('action_type') == 'system' ? 'selected' : '' }}>Aktivitas Sistem / Naskah</option>
                </select>
            </div>

            <!-- Filter Tanggal -->
            <div class="col-lg-2 col-md-3">
                <input type="date" name="date" class="form-control bg-light" value="{{ request('date') }}" onchange="this.form.submit()">
            </div>

            <!-- Tombol Filter / Reset -->
            <div class="col-lg-1 d-flex gap-1">
                <button type="submit" class="btn btn-brand w-100" title="Terapkan Filter">
                    <i class="bi bi-funnel"></i>
                </button>
                @if(request()->anyFilled(['search', 'action_type', 'date']))
                    <a href="{{ route('admin.activityLogs.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Main Full-Width Table Card -->
    <div class="card card-soft p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="fw-bold mb-0" style="color: var(--admin-text-main);">
                <i class="bi bi-shield-check me-2 text-warning"></i>Riwayat Log Administrator
            </h5>
            <span class="badge bg-light text-muted border" style="font-size: 12px;">
                Menampilkan {{ $logs->total() }} entri log
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-muted" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <th style="min-width: 140px;">Waktu</th>
                        <th style="min-width: 220px;">Administrator</th>
                        <th style="min-width: 110px;">Peran</th>
                        <th style="min-width: 170px;">Aksi</th>
                        <th style="min-width: 130px;">Alamat IP</th>
                        <th style="min-width: 160px;">Perangkat &amp; Browser</th>
                        <th style="min-width: 260px;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <!-- Waktu -->
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark" style="font-size: 13px;">{{ $log->created_at->format('d/m/Y') }}</span>
                                    <span class="text-muted small" style="font-size: 11.5px;">
                                        <i class="bi bi-clock me-1"></i>{{ $log->created_at->format('H:i:s') }} WIB
                                    </span>
                                </div>
                            </td>

                            <!-- Administrator -->
                            <td>
                                @if($log->user)
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle fw-bold" style="width: 34px; height: 34px; font-size: 13px; background: rgba(245, 166, 35, 0.15); color: #F5A623;">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div style="min-width: 0;">
                                            <span class="fw-bold d-block text-truncate" style="max-width: 190px; color: var(--admin-text-main); font-size: 13px;">{{ $log->user->name }}</span>
                                            <small class="text-muted d-block text-truncate" style="max-width: 190px; font-size: 11.5px;">{{ $log->user->email }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small font-italic">Admin tidak ditemukan</span>
                                @endif
                            </td>

                            <!-- Peran -->
                            <td>
                                <span class="badge" style="background: rgba(245, 166, 35, 0.15); color: #F5A623; font-size: 11px; font-weight: 700;">
                                    <i class="bi bi-shield-check me-1"></i>Admin
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td>
                                @if($log->action === 'auth.login' || $log->action === 'auth.login.google')
                                    <span class="badge font-monospace" style="background: rgba(16, 185, 129, 0.15); color: #10B981; font-size: 11.5px; border: 1px solid rgba(16, 185, 129, 0.3);">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>{{ $log->action_label }}
                                    </span>
                                @elseif($log->action === 'auth.logout')
                                    <span class="badge font-monospace" style="background: rgba(239, 68, 68, 0.12); color: #EF4444; font-size: 11.5px; border: 1px solid rgba(239, 68, 68, 0.25);">
                                        <i class="bi bi-box-arrow-right me-1"></i>{{ $log->action_label }}
                                    </span>
                                @else
                                    <span class="badge border font-monospace text-dark" style="background: var(--admin-card-surface); font-size: 11px;">
                                        {{ $log->action }}
                                    </span>
                                @endif
                            </td>

                            <!-- Alamat IP -->
                            <td>
                                <span class="badge bg-light border text-dark font-monospace" style="font-size: 11.5px;">
                                    <i class="bi bi-globe2 me-1 text-muted"></i>{{ $log->ip_address ?: '-' }}
                                </span>
                            </td>

                            <!-- Perangkat / Browser -->
                            <td class="small text-muted" style="font-size: 12px;">
                                {!! $log->device !!}
                            </td>

                            <!-- Keterangan -->
                            <td class="small" style="color: var(--admin-text-sub); font-size: 12.5px;">
                                {{ $log->description ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-check d-block fs-1 mb-2 text-secondary opacity-50"></i>
                                <h6 class="fw-bold mb-1">Tidak Ada Data Log Aktivitas Administrator</h6>
                                <p class="small mb-0">Belum ada riwayat aktivitas yang cocok dengan parameter filter saat ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="mt-4 pt-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <span class="small text-muted">
                    Menampilkan <strong>{{ $logs->firstItem() ?? 0 }}</strong> &ndash; <strong>{{ $logs->lastItem() ?? 0 }}</strong> dari total <strong>{{ $logs->total() }}</strong> entri
                </span>
                <div>
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
