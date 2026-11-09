@extends('layouts.admin')

@section('title', 'Katalog E-Book Penerbit — Super Admin')
@section('page-title', 'Katalog E-Book')

@push('styles')
<style>
    .ebook-cover-thumb {
        width: 52px;
        height: 74px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
        border: 1px solid var(--admin-card-border);
        flex-shrink: 0;
    }
    .badge-format {
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: uppercase;
    }
    .badge-format-pdf {
        background: rgba(239, 68, 68, 0.15);
        color: #EF4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    .badge-format-epub {
        background: rgba(59, 130, 246, 0.15);
        color: #3B82F6;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }
    .badge-category {
        font-size: 10.5px;
        background: rgba(245, 166, 35, 0.12);
        color: #F5A623;
        border: 1px solid rgba(245, 166, 35, 0.25);
        border-radius: 50px;
        padding: 2px 10px;
        font-weight: 600;
    }
    .badge-access {
        font-size: 10.5px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 50px;
    }
    .stat-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .stat-hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .stat-hover-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
        border-color: rgba(245, 166, 35, 0.5) !important;
    }
    [data-theme="dark"] .stat-hover-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold font-serif mb-1" style="color: var(--admin-text-main);">
                Manajemen E-Book Resmi 📖
            </h2>
            <p class="text-muted small mb-0">
                Kelola koleksi e-book digital, berkas PDF/EPUB berstandar penerbit, akses unduhan, serta hak cipta penerbitan.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.ebooks.create') }}" class="btn btn-brand d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-cloud-arrow-up-fill"></i>
                <span>Unggah E-Book Baru</span>
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Overview Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card card-soft p-3 h-100 stat-hover-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-card-icon" style="background: rgba(245, 166, 35, 0.15); color: #F5A623;">
                        <i class="bi bi-book"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Koleksi</div>
                        <div class="h4 fw-bold mb-0" style="color: var(--admin-text-main);">{{ number_format($stats['total']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-soft p-3 h-100 stat-hover-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.15); color: #10B981;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">E-Book Aktif</div>
                        <div class="h4 fw-bold mb-0 text-success">{{ number_format($stats['active']) }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-soft p-3 h-100 stat-hover-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-card-icon" style="background: rgba(59, 130, 246, 0.15); color: #3B82F6;">
                        <i class="bi bi-download"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Diunduh</div>
                        <div class="h4 fw-bold mb-0" style="color: var(--admin-text-main);">{{ number_format($stats['downloads']) }} <span class="small fw-normal text-muted" style="font-size: 12px;">kali</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card card-soft p-3 h-100 stat-hover-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-card-icon" style="background: rgba(139, 92, 246, 0.15); color: #8B5CF6;">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Format Berkas</div>
                        <div class="h6 fw-bold mb-0" style="color: var(--admin-text-main);">
                            <span class="text-danger">{{ $stats['pdf_count'] }} PDF</span>
                            <span class="text-muted mx-1">/</span>
                            <span class="text-primary">{{ $stats['epub_count'] }} EPUB</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card card-soft p-3 mb-4 shadow-sm">
        <form action="{{ route('admin.ebooks.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="Cari judul, penulis, ISBN, atau kategori..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="format" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Format</option>
                    <option value="pdf" {{ request('format') === 'pdf' ? 'selected' : '' }}>PDF Document</option>
                    <option value="epub" {{ request('format') === 'epub' ? 'selected' : '' }}>EPUB Publication</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif (Tampil)</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-aktif (Draft)</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-brand px-3 py-2 flex-grow-1 justify-content-center">
                    <i class="bi bi-funnel-fill me-1"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'category', 'format', 'status']))
                    <a href="{{ route('admin.ebooks.index') }}" class="btn btn-outline-danger px-3 py-2 rounded-3 d-flex align-items-center justify-content-center" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table of E-Books -->
    <div class="card card-soft p-0 overflow-hidden mb-4 shadow-sm">
        <div class="table-responsive">
            <table class="table table-modern table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">Cover</th>
                        <th>Judul &amp; Informasi E-Book</th>
                        <th>Kategori &amp; ISBN</th>
                        <th>Berkas &amp; Akses</th>
                        <th class="text-center" style="width: 100px;">Unduhan</th>
                        <th class="text-center" style="width: 100px;">Status</th>
                        <th class="text-end" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ebooks as $ebook)
                        <tr>
                            <!-- Cover -->
                            <td>
                                <img src="{{ $ebook->cover_url }}" alt="{{ $ebook->title }}" class="ebook-cover-thumb">
                            </td>

                            <!-- Title & Info -->
                            <td>
                                <div class="fw-bold" style="color: var(--admin-text-main); font-size: 14px;">
                                    {{ $ebook->title }}
                                </div>
                                <div class="small text-muted mt-1 d-flex flex-wrap align-items-center gap-2">
                                    <span><i class="bi bi-person me-1"></i><strong>{{ $ebook->author }}</strong></span>
                                    @if($ebook->editor)
                                        <span class="text-muted">• Editor: {{ $ebook->editor }}</span>
                                    @endif
                                </div>
                                <div class="small text-muted mt-1" style="font-size: 11.5px;">
                                    <span><i class="bi bi-building me-1"></i>{{ $ebook->publisher }}</span>
                                    @if($ebook->publication_year)
                                        <span class="mx-1">•</span>
                                        <span>Tahun {{ $ebook->publication_year }}</span>
                                    @endif
                                    @if($ebook->pages)
                                        <span class="mx-1">•</span>
                                        <span>{{ $ebook->pages }} Hlm</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Category & ISBN -->
                            <td>
                                @if($ebook->category)
                                    <div class="mb-1">
                                        <span class="badge-category">{{ $ebook->category }}</span>
                                    </div>
                                @endif
                                <div class="small text-muted" style="font-size: 11.5px;">
                                    @if($ebook->isbn)
                                        <div><span class="text-muted">ISBN:</span> <code class="text-warning">{{ $ebook->isbn }}</code></div>
                                    @else
                                        <span class="text-muted fst-italic">Tanpa ISBN</span>
                                    @endif
                                    @if($ebook->edition)
                                        <div class="text-muted">{{ $ebook->edition }}</div>
                                    @endif
                                </div>
                            </td>

                            <!-- File & Access -->
                            <td>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    @if(strtolower($ebook->file_format) === 'pdf')
                                        <span class="badge-format badge-format-pdf"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</span>
                                    @else
                                        <span class="badge-format badge-format-epub"><i class="bi bi-journal-bookmark me-1"></i>EPUB</span>
                                    @endif
                                    <span class="small text-muted fw-semibold">{{ $ebook->file_size ?? '-' }}</span>
                                </div>
                                <div class="small">
                                    @if($ebook->access_type === 'public')
                                        <span class="badge-access bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-globe me-1"></i>Publik
                                        </span>
                                    @elseif($ebook->access_type === 'registered')
                                        <span class="badge-access bg-info-subtle text-info border border-info-subtle">
                                            <i class="bi bi-person-check me-1"></i>Pengguna Terdaftar
                                        </span>
                                    @else
                                        <span class="badge-access bg-warning-subtle text-warning border border-warning-subtle">
                                            <i class="bi bi-lock me-1"></i>Terbatas
                                        </span>
                                    @endif

                                    <span class="ms-1 fw-semibold {{ $ebook->is_free ? 'text-success' : 'text-warning' }}">
                                        {{ $ebook->formatted_price }}
                                    </span>
                                </div>
                            </td>

                            <!-- Downloads & Views -->
                            <td class="text-center">
                                <div class="fw-bold" style="color: var(--admin-text-main);">
                                    {{ number_format($ebook->download_count) }}
                                </div>
                                <div class="text-muted" style="font-size: 11px;">
                                    {{ number_format($ebook->view_count) }} tayang
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="text-center">
                                <form action="{{ route('admin.ebooks.toggleStatus', $ebook) }}" method="POST" class="d-inline">
                                    @csrf
                                    @if($ebook->is_active)
                                        <button type="submit" class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill text-decoration-none" style="cursor: pointer;" title="Klik untuk nonaktifkan">
                                            <i class="bi bi-check-circle-fill me-1"></i> Aktif
                                        </button>
                                    @else
                                        <button type="submit" class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1 rounded-pill text-decoration-none" style="cursor: pointer;" title="Klik untuk aktifkan">
                                            <i class="bi bi-dash-circle me-1"></i> Non-aktif
                                        </button>
                                    @endif
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="text-end">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <!-- Preview Button -->
                                    <a href="{{ route('admin.ebooks.preview', $ebook) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-2 px-2 py-1" title="Pratinjau E-Book">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <!-- Download Button -->
                                    <a href="{{ route('admin.ebooks.download', $ebook) }}" class="btn btn-sm btn-outline-success rounded-2 px-2 py-1" title="Unduh Berkas Asli">
                                        <i class="bi bi-download"></i>
                                    </a>

                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.ebooks.edit', $ebook) }}" class="btn btn-sm btn-outline-warning rounded-2 px-2 py-1" title="Edit Data E-Book">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-2 px-2 py-1" title="Hapus E-Book" data-bs-toggle="modal" data-bs-target="#deleteEbookModal{{ $ebook->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-4">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm" style="width: 72px; height: 72px; background: rgba(245, 166, 35, 0.15); color: #F5A623;">
                                        <i class="bi bi-journal-bookmark-fill fs-1"></i>
                                    </div>
                                    <h5 class="fw-bold mb-1" style="color: var(--admin-text-main);">Belum Ada Koleksi E-Book</h5>
                                    <p class="text-muted small mx-auto mb-4" style="max-width: 440px;">
                                        Mulai publikasikan dan kelola koleksi e-book digital resmi PT. Pena Cendekia Pustaka untuk pembaca dan penulis.
                                    </p>
                                    <a href="{{ route('admin.ebooks.create') }}" class="btn btn-brand px-4 py-2">
                                        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Unggah E-Book Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ebooks->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center flex-wrap gap-2" style="border-color: var(--admin-card-border) !important;">
                <div class="small text-muted">
                    Menampilkan {{ $ebooks->firstItem() }} – {{ $ebooks->lastItem() }} dari {{ $ebooks->total() }} e-book
                </div>
                <div>
                    {{ $ebooks->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Hapus untuk Semua E-Book -->
@foreach($ebooks as $ebook)
<div class="modal fade" id="deleteEbookModal{{ $ebook->id }}" tabindex="-1" aria-labelledby="deleteEbookLabel{{ $ebook->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--admin-card-bg); border: 1px solid var(--admin-card-border); color: var(--admin-text-main);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2" id="deleteEbookLabel{{ $ebook->id }}">
                    <i class="bi bi-exclamation-triangle-fill"></i> Hapus E-Book
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <p class="mb-2">Apakah Anda yakin ingin menghapus e-book berikut dari katalog dan server?</p>
                <div class="p-3 rounded-3" style="background: var(--admin-card-surface); border: 1px solid var(--admin-card-border);">
                    <div class="fw-bold">{{ $ebook->title }}</div>
                    <div class="small text-muted">Penulis: {{ $ebook->author }}</div>
                    <div class="small text-muted">Format: {{ $ebook->file_format }} ({{ $ebook->file_size }})</div>
                </div>
                <p class="text-danger small mt-2 mb-0">
                    <i class="bi bi-info-circle me-1"></i> Berkas master e-book dan gambar cover di server akan turut dihapus secara permanen.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.ebooks.destroy', $ebook) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger px-3">
                        <i class="bi bi-trash me-1"></i> Ya, Hapus Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
