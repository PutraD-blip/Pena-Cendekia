@extends('layouts.admin')

@section('title', 'Manajemen Testimoni Penulis — Super Admin')
@section('page-title', 'Testimoni Penulis')

@push('styles')
<style>
    .author-photo-thumb {
        width: 52px;
        height: 52px;
        object-fit: cover;
        border-radius: 50%;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        border: 2px solid var(--admin-card-border);
        flex-shrink: 0;
    }
    .testi-quote-box {
        font-size: 13px;
        color: var(--admin-text-main);
        font-style: italic;
        line-height: 1.5;
        max-width: 460px;
    }
    .star-gold {
        color: #F5A623;
        font-size: 13px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color: var(--admin-text-main);">
                Manajemen Testimoni Penulis ⭐
            </h1>
            <p class="text-muted small mb-0">
                Kelola ulasan, apresiasi, dan foto profil para penulis yang ditampilkan pada <strong>Slider Testimoni Beranda</strong>.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-brand d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="bi bi-plus-circle-fill"></i> <span>Tambah Testimoni Baru</span>
            </a>
            <a href="{{ route('home') }}#testimoni" target="_blank" class="btn btn-outline-secondary px-3 py-2 rounded-3 small d-flex align-items-center gap-2" style="font-size: 13px;">
                <i class="bi bi-eye"></i> Pratinjau di Web
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="card card-soft p-3 mb-4 shadow-sm">
        <form action="{{ route('admin.testimonials.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama penulis, profesi, atau kutipan testimoni..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-brand w-100 justify-content-center">
                    <i class="bi bi-filter me-1"></i> Cari Testimoni
                </button>
            </div>
            @if(request('search'))
                <div class="col-md-3">
                    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-danger px-3 py-2 rounded-3 w-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-x-circle me-1"></i> Reset Pencarian
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Table of Testimonials -->
    <div class="card card-soft p-0 overflow-hidden mb-4 shadow-sm">
        <div class="table-responsive">
            <table class="table table-modern table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;">Foto</th>
                        <th>Penulis &amp; Profesi</th>
                        <th>Kutipan Testimoni</th>
                        <th style="width: 100px;">Rating</th>
                        <th style="width: 80px;">Urutan</th>
                        <th style="width: 130px;">Status Tayang</th>
                        <th style="width: 110px;" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $t)
                        <tr>
                            <td>
                                <img src="{{ $t->photo_url }}" alt="{{ $t->name }}" class="author-photo-thumb" onerror="this.onerror=null;this.src='{{ asset('images/about-author.png') }}';">
                            </td>
                            <td>
                                <span class="fw-bold d-block" style="color: var(--admin-text-main); font-size: 14px;">
                                    {{ $t->name }}
                                </span>
                                <small class="text-muted d-block">{{ $t->role }}</small>
                            </td>
                            <td>
                                <div class="testi-quote-box">
                                    “{{ $t->quote }}”
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1 star-gold">
                                    @for($i = 0; $i < $t->rating; $i++)
                                        <i class="bi bi-star-fill"></i>
                                    @endfor
                                </div>
                                <small class="text-muted" style="font-size: 11px;">{{ $t->rating }} / 5</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-semibold" style="font-size: 12px;">#{{ $t->order }}</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.testimonials.toggleStatus', $t) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm py-1 px-2.5 rounded-pill d-inline-flex align-items-center gap-1 fw-semibold {{ $t->is_active ? 'btn-success' : 'btn-outline-secondary' }}" style="font-size: 11.5px;" title="Klik untuk mengubah status tayang">
                                        <i class="bi {{ $t->is_active ? 'bi-eye-fill' : 'bi-eye-slash-fill' }}"></i>
                                        <span>{{ $t->is_active ? 'Tayang' : 'Draft / Nonaktif' }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('admin.testimonials.edit', $t) }}" class="btn btn-sm btn-outline-primary py-1 px-2" title="Edit Testimoni">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus testimoni dari \'{{ addslashes($t->name) }}\'?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hapus Testimoni">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-chat-quote fs-1 d-block mb-2 text-warning opacity-50"></i>
                                <span class="fw-semibold">Belum ada testimoni penulis yang didaftarkan.</span>
                                <p class="small mb-0">Klik tombol "Tambah Testimoni Baru" untuk menambahkan ulasan penulis.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($testimonials->hasPages())
            <div class="p-3 border-top d-flex justify-content-end" style="border-color: var(--admin-card-border) !important;">
                {{ $testimonials->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
