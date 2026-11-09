@extends('layouts.admin')

@section('title', 'Portofolio Buku Terbitan — Super Admin')
@section('page-title', 'Portofolio Buku')

@push('styles')
<style>
    .book-cover-thumb {
        width: 52px;
        height: 74px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
        border: 1px solid var(--admin-card-border);
        flex-shrink: 0;
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
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color: var(--admin-text-main);">
                Portofolio Buku Terbitan 📚
            </h1>
            <p class="text-muted small mb-0">
                Kelola daftar buku portofolio yang ditampilkan pada <strong>Katalog Buku Terbitan</strong> dan <strong>Coverflow Beranda</strong>.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.featuredBooks.create') }}" class="btn btn-brand d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-plus-circle-fill"></i> <span>Tambah Buku Baru</span>
            </a>
            <a href="{{ route('portofolio') }}" target="_blank" class="btn btn-outline-secondary px-3 py-2 rounded-3 small d-flex align-items-center gap-2" style="font-size: 13px;">
                <i class="bi bi-eye"></i> Lihat Katalog
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card card-soft p-3 mb-4 shadow-sm">
        <form action="{{ route('admin.featuredBooks.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="Cari judul buku, penulis, kategori, atau ISBN..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-brand w-100 justify-content-center">
                    <i class="bi bi-filter me-1"></i> Terapkan Filter
                </button>
            </div>
            @if(request('search'))
                <div class="col-md-3">
                    <a href="{{ route('admin.featuredBooks.index') }}" class="btn btn-outline-danger px-3 py-2 rounded-3 w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-x-circle me-1"></i> Reset Pencarian
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Table of Books -->
    <div class="card card-soft p-0 overflow-hidden mb-4 shadow-sm">
        <div class="table-responsive">
            <table class="table table-modern table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 60px;">Cover</th>
                        <th>Judul &amp; Informasi Buku</th>
                        <th>Kategori &amp; ISBN</th>
                        <th>Spesifikasi &amp; Urutan</th>
                        <th class="text-center" style="width: 110px;">Status Web</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr>
                            <!-- Cover Thumbnail -->
                            <td>
                                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="book-cover-thumb">
                            </td>

                            <!-- Book Info -->
                            <td>
                                <div class="fw-bold mb-1" style="color: var(--admin-text-main); font-size: 14px;">
                                    {{ $book->title }}
                                </div>
                                @if($book->subtitle)
                                    <div class="text-muted small mb-1" style="font-size: 11.5px;">
                                        {{ $book->subtitle }}
                                    </div>
                                @endif
                                <div class="d-flex align-items-center gap-2 flex-wrap text-muted small" style="font-size: 11.5px;">
                                    <span><i class="bi bi-person me-1"></i>{{ $book->author }}</span>
                                    @if($book->editor)
                                        <span>&bull; Editor: {{ $book->editor }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Category & ISBN -->
                            <td>
                                <span class="badge-category d-inline-block mb-1">
                                    {{ $book->category ?? 'Karya Terbitan' }}
                                </span>
                                <div class="text-muted font-monospace small" style="font-size: 11px;">
                                    ISBN: {{ $book->isbn ?? '-' }}
                                </div>
                            </td>

                            <!-- Spesifikasi & Urutan -->
                            <td>
                                <div class="d-flex flex-column gap-1 small text-muted" style="font-size: 11.5px;">
                                    <span><i class="bi bi-calendar3 me-1"></i>Tahun: <strong>{{ $book->year ?? '-' }}</strong></span>
                                    <span><i class="bi bi-file-earmark-text me-1"></i>Hlm: <strong>{{ $book->pages ?? '-' }}</strong></span>
                                    <span><i class="bi bi-sort-numeric-down me-1"></i>Urutan: <strong class="badge bg-light text-dark border">#{{ $book->order }}</strong></span>
                                </div>
                            </td>

                            <!-- Active Toggle -->
                            <td class="text-center">
                                <form action="{{ route('admin.featuredBooks.toggleStatus', $book) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm rounded-pill px-3 py-1 fw-bold {{ $book->is_active ? 'btn-success' : 'btn-outline-secondary' }}" style="font-size: 11px;" title="Klik untuk mengubah status tayang">
                                        {{ $book->is_active ? 'Tayang' : 'Draft' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="text-end">
                                <div class="d-inline-flex align-items-center gap-1">
                                    <a href="{{ route('admin.featuredBooks.edit', $book) }}" class="btn btn-sm btn-outline-warning rounded-2 p-1 px-2" title="Edit Buku & Testimoni">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.featuredBooks.destroy', $book) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku \"{{ addslashes($book->title) }}\" dari koleksi pilihan?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-2 p-1 px-2" title="Hapus Buku">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-1 d-block mb-2 text-warning opacity-50"></i>
                                Belum ada buku pilihan yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($books->hasPages())
            <div class="p-3 border-top" style="border-color: var(--admin-card-border) !important;">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
