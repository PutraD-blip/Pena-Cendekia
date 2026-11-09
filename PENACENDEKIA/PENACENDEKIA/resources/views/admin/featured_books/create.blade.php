@extends('layouts.admin')

@section('title', 'Tambah Buku Pilihan Baru — Super Admin')
@section('page-title', 'Tambah Buku Pilihan')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color: var(--admin-text-main);">
                Tambah Koleksi Buku Pilihan &amp; Testimoni
            </h1>
            <p class="text-muted small mb-0">
                Lengkapi rincian buku terbitan resmi dan kutipan ulasan testimoni untuk ditampilkan di beranda.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.featuredBooks.index') }}" class="btn btn-outline-secondary px-3 py-2 rounded-3 small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-3 mb-4 p-3">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan input:</div>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.featuredBooks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <!-- Left Column: Data Buku -->
            <div class="col-lg-7">
                <div class="admin-card p-4 mb-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--admin-text-main);">
                        <i class="bi bi-book-half text-warning"></i> Informasi Buku Terbitan
                    </h5>

                    <!-- Judul Buku -->
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                            Judul Buku <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Contoh: Administrasi Rekam Medis" value="{{ old('title') }}" required style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                    </div>

                    <!-- Subjudul -->
                    <div class="mb-3">
                        <label for="subtitle" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                            Subjudul / Deskripsi Singkat
                        </label>
                        <input type="text" name="subtitle" id="subtitle" class="form-control" placeholder="Contoh: Tata Kelola Rekam Medis Modern & Digital di Pelayanan Kesehatan" value="{{ old('subtitle') }}" style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Penulis -->
                        <div class="col-md-7">
                            <label for="author" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                                Nama Penulis <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="author" id="author" class="form-control" placeholder="Nama lengkap beserta gelar" value="{{ old('author') }}" required style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                        </div>
                        <!-- Editor -->
                        <div class="col-md-5">
                            <label for="editor" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                                Editor / Penyunting
                            </label>
                            <input type="text" name="editor" id="editor" class="form-control" placeholder="Nama editor" value="{{ old('editor') }}" style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Kategori -->
                        <div class="col-md-6">
                            <label for="category" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                                Kategori Buku
                            </label>
                            <input type="text" name="category" id="category" class="form-control" placeholder="Contoh: Buku Ajar / Kesehatan" value="{{ old('category') }}" style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                        </div>
                        <!-- ISBN -->
                        <div class="col-md-6">
                            <label for="isbn" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                                Nomor ISBN
                            </label>
                            <input type="text" name="isbn" id="isbn" class="form-control font-monospace" placeholder="978-623-..." value="{{ old('isbn') }}" style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Tahun -->
                        <div class="col-md-4">
                            <label for="year" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                                Tahun Terbit
                            </label>
                            <input type="text" name="year" id="year" class="form-control" placeholder="2024" value="{{ old('year', date('Y')) }}" style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                        </div>
                        <!-- Halaman -->
                        <div class="col-md-4">
                            <label for="pages" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                                Jumlah Halaman
                            </label>
                            <input type="text" name="pages" id="pages" class="form-control" placeholder="xii + 214 hlm" value="{{ old('pages') }}" style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                        </div>
                        <!-- Ukuran -->
                        <div class="col-md-4">
                            <label for="size" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                                Ukuran Buku
                            </label>
                            <input type="text" name="size" id="size" class="form-control" placeholder="15.5 x 23 cm" value="{{ old('size', '15.5 x 23 cm (UNESCO)') }}" style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                        </div>
                    </div>

                    <!-- Sinopsis -->
                    <div class="mb-3">
                        <label for="synopsis" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                            Sinopsis Buku
                        </label>
                        <textarea name="synopsis" id="synopsis" rows="4" class="form-control" placeholder="Uraian sinopsis naskah buku untuk pop-up modal saat diklik di beranda..." style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">{{ old('synopsis') }}</textarea>
                    </div>

                    <!-- Cover Image Upload -->
                    <div class="mb-3">
                        <label for="cover_image" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                            File Cover Buku (JPG, PNG, WebP &bull; Maks 5 MB)
                        </label>
                        <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*" onchange="previewImage(this, 'coverPreview')">
                        <div class="mt-2 text-center p-2 rounded-3" style="background: var(--admin-card-surface); border: 1px dashed var(--admin-card-border);">
                            <img id="coverPreview" src="" alt="Preview Cover" style="max-height: 180px; display: none; border-radius: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                            <div id="coverPlaceholder" class="text-muted small py-3">
                                <i class="bi bi-image fs-4 d-block mb-1"></i> Preview cover akan muncul di sini
                            </div>
                        </div>
                    </div>
                </div>
            </div>            <!-- Right Column: Pengaturan Penayangan -->
            <div class="col-lg-5">
                <div class="admin-card p-4 mb-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--admin-text-main); font-size: 15px;">
                        <i class="bi bi-sliders text-warning"></i> Pengaturan Penayangan
                    </h5>

                    <!-- Urutan Tampil -->
                    <div class="mb-3">
                        <label for="order" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                            Nomor Urut Tampil (Prioritas)
                        </label>
                        <input type="number" name="order" id="order" class="form-control" value="{{ old('order', 0) }}" min="0" style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                        <small class="text-muted" style="font-size: 11px;">Angka lebih kecil tampil lebih awal pada katalog buku.</small>
                    </div>

                    <!-- Status Tayang Checkbox -->
                    <div class="form-check form-switch mb-4 p-3 rounded-3" style="background: var(--admin-card-surface); border: 1px solid var(--admin-card-border);">
                        <input class="form-check-input ms-0 me-3" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="cursor: pointer; transform: scale(1.15);">
                        <label class="form-check-label fw-semibold small" for="is_active" style="color: var(--admin-text-main); cursor: pointer;">
                            Tayangkan di Katalog &amp; Beranda
                        </label>
                        <small class="text-muted d-block mt-1">Jika dinonaktifkan (draft), buku akan disembunyikan dari katalog publik.</small>
                    </div>

                    <div class="alert alert-info py-2 px-3 small rounded-3 mb-4" style="font-size: 11.5px; background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.2); color: var(--admin-text-main);">
                        <i class="bi bi-info-circle me-1 text-primary"></i> <strong>Info:</strong> Ulasan &amp; apresiasi penulis kini dikelola secara mandiri pada menu terpisah <a href="{{ route('admin.testimonials.index') }}" class="fw-bold text-primary">Testimoni Penulis</a>.
                    </div>

                    <button type="submit" class="btn btn-warning fw-bold w-100 py-2.5 rounded-3 shadow-sm" style="background: linear-gradient(135deg, #F5A623, #D48010); border: none; color: #111827;">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan Buku Portofolio
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const placeholder = preview.nextElementSibling || preview.previousElementSibling;
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
