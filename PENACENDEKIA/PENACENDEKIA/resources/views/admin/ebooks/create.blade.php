@extends('layouts.admin')

@section('title', 'Unggah E-Book Baru — Super Admin')
@section('page-title', 'Unggah E-Book Baru')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color: var(--admin-text-main);">
                Unggah E-Book Baru 📚
            </h1>
            <p class="text-muted small mb-0">
                Lengkapi rincian bibliografi penerbitan dan unggah berkas master e-book (format PDF atau EPUB).
            </p>
        </div>
        <div>
            <a href="{{ route('admin.ebooks.index') }}" class="btn btn-outline-secondary px-3 py-2 rounded-3 small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-3 mb-4 p-3">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan pengisian:</div>
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.ebooks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <!-- Left Column: Informasi Bibliografi -->
            <div class="col-lg-7">
                <div class="admin-card p-4 mb-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--admin-text-main);">
                        <i class="bi bi-journal-bookmark-fill text-warning"></i> Data Bibliografi E-Book
                    </h5>

                    <!-- Judul -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul E-Book <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: Metodologi Penelitian Kesehatan Terapan" value="{{ old('title') }}" required>
                        @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Penulis -->
                        <div class="col-md-7">
                            <label for="author" class="form-label">Penulis / Tim Penulis <span class="text-danger">*</span></label>
                            <input type="text" name="author" id="author" class="form-control @error('author') is-invalid @enderror" placeholder="Contoh: Dr. H. Ahmad Fauzi, M.Kes" value="{{ old('author') }}" maxlength="500" required>
                            <small class="text-muted" style="font-size: 11px;">Maksimal 500 karakter</small>
                            @error('author') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Editor -->
                        <div class="col-md-5">
                            <label for="editor" class="form-label">Penyunting / Editor</label>
                            <input type="text" name="editor" id="editor" class="form-control" placeholder="Contoh: Tim Redaksi Pena Cendekia" value="{{ old('editor') }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Penerbit -->
                        <div class="col-md-6">
                            <label for="publisher" class="form-label">Penerbit Resmi</label>
                            <input type="text" name="publisher" id="publisher" class="form-control" value="{{ old('publisher', 'PT. Pena Cendekia Pustaka') }}">
                            <small class="text-muted" style="font-size: 11px;">Default penerbit resmi Pena Cendekia</small>
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6">
                            <label for="category" class="form-label">Kategori / Bidang Ilmu</label>
                            <input type="text" name="category" id="category" class="form-control" placeholder="Contoh: Kesehatan, Pendidikan, Hukum" value="{{ old('category') }}" list="categoryList">
                            <datalist id="categoryList">
                                <option value="Kesehatan & Kedokteran">
                                <option value="Pendidikan & Keguruan">
                                <option value="Sosial & Humaniora">
                                <option value="Sains & Teknologi">
                                <option value="Ekonomi & Bisnis">
                                <option value="Hukum & Tata Negara">
                                <option value="Agama & Filsafat">
                                <option value="Sastra & Fiksi">
                            </datalist>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- ISBN -->
                        <div class="col-md-6">
                            <label for="isbn" class="form-label">Nomor ISBN / e-ISBN</label>
                            <input type="text" name="isbn" id="isbn" class="form-control" placeholder="Contoh: 978-623-1234-56-7" value="{{ old('isbn') }}">
                            <small class="text-muted" style="font-size: 11px;">Nomor e-ISBN resmi dari Perpusnas RI jika ada</small>
                        </div>

                        <!-- Edisi -->
                        <div class="col-md-6">
                            <label for="edition" class="form-label">Edisi / Seri Terbitan</label>
                            <input type="text" name="edition" id="edition" class="form-control" placeholder="Contoh: Cetakan Ke-1, Edisi Revisi" value="{{ old('edition') }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Tahun Terbit -->
                        <div class="col-md-4">
                            <label for="publication_year" class="form-label">Tahun Terbit</label>
                            <input type="number" name="publication_year" id="publication_year" class="form-control" min="1950" max="{{ date('Y') + 2 }}" value="{{ old('publication_year', date('Y')) }}">
                        </div>

                        <!-- Jumlah Halaman -->
                        <div class="col-md-4">
                            <label for="pages" class="form-label">Jumlah Halaman</label>
                            <input type="number" name="pages" id="pages" class="form-control" min="1" placeholder="Contoh: 240" value="{{ old('pages') }}">
                        </div>

                        <!-- Bahasa -->
                        <div class="col-md-4">
                            <label for="language" class="form-label">Bahasa</label>
                            <input type="text" name="language" id="language" class="form-control" value="{{ old('language', 'Indonesia') }}">
                        </div>
                    </div>

                    <!-- Sinopsis -->
                    <div class="mb-0">
                        <label for="synopsis" class="form-label">Sinopsis / Ringkasan Buku</label>
                        <textarea name="synopsis" id="synopsis" rows="5" class="form-control" placeholder="Uraikan sinopsis buku, keunggulan, atau poin-poin bab utama...">{{ old('synopsis') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Column: Berkas & Pengaturan Akses -->
            <div class="col-lg-5">
                <!-- Berkas Master E-Book -->
                <div class="admin-card p-4 mb-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--admin-text-main);">
                        <i class="bi bi-file-earmark-arrow-up-fill text-warning"></i> Berkas Digital E-Book
                    </h5>

                    <!-- File Utama -->
                    <div class="mb-3">
                        <label for="ebook_file" class="form-label">Berkas Master E-Book <span class="text-danger">*</span></label>
                        <input type="file" name="ebook_file" id="ebook_file" class="form-control @error('ebook_file') is-invalid @enderror" accept=".pdf,.epub" required>
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Format: <strong>PDF</strong> atau <strong>EPUB</strong>. Ukuran maksimum <strong>100 MB</strong>.</small>
                        @error('ebook_file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Berkas Pratinjau (Sample) -->
                    <div class="mb-3">
                        <label for="sample_file" class="form-label">Berkas Bab Contoh / Sample <span class="badge bg-secondary-subtle text-secondary small">Opsional</span></label>
                        <input type="file" name="sample_file" id="sample_file" class="form-control @error('sample_file') is-invalid @enderror" accept=".pdf">
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Format: PDF (maks 20 MB). Berkas contoh (misal bab 1-2) untuk pratinjau publik.</small>
                        @error('sample_file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Cover Gambar -->
                    <div class="mb-0">
                        <label for="cover_image" class="form-label">Gambar Sampul (Cover E-Book)</label>
                        <input type="file" name="cover_image" id="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Format: JPG, JPEG, PNG, WEBP. Maksimal 5 MB. Rasio ideal 3:4 (tegak).</small>
                        @error('cover_image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Hak Akses & Distribusi -->
                <div class="admin-card p-4 mb-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--admin-text-main);">
                        <i class="bi bi-shield-lock-fill text-warning"></i> Hak Akses &amp; Monetisasi
                    </h5>

                    <!-- Tipe Akses -->
                    <div class="mb-3">
                        <label class="form-label">Hak Akses Pembaca <span class="text-danger">*</span></label>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="access_type" id="access_public" value="public" {{ old('access_type', 'public') === 'public' ? 'checked' : '' }}>
                                <label class="form-check-label" for="access_public">
                                    <strong>Publik Terbuka</strong>
                                    <small class="text-muted d-block">Dapat diunduh dan dibaca oleh siapa saja tanpa harus login.</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="access_type" id="access_registered" value="registered" {{ old('access_type') === 'registered' ? 'checked' : '' }}>
                                <label class="form-check-label" for="access_registered">
                                    <strong>Pengguna Terdaftar Saja</strong>
                                    <small class="text-muted d-block">Wajib memiliki akun / login terlebih dahulu.</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="access_type" id="access_restricted" value="restricted" {{ old('access_type') === 'restricted' ? 'checked' : '' }}>
                                <label class="form-check-label" for="access_restricted">
                                    <strong>Terbatas / Khusus</strong>
                                    <small class="text-muted d-block">Hanya penulis atau pembeli berizin yang berhak akses.</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Skema Harga -->
                    <div class="mb-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" role="switch" name="is_free" id="is_free" value="1" {{ old('is_free', '1') == '1' ? 'checked' : '' }} onchange="togglePriceInput(this)">
                            <label class="form-check-label fw-bold" for="is_free">Gratis (Free E-Book Open Access)</label>
                        </div>

                        <div id="price_wrapper" style="{{ old('is_free', '1') == '1' ? 'display: none;' : '' }}">
                            <label for="price" class="form-label small">Harga Penjualan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="price" id="price" class="form-control" placeholder="Contoh: 45000" min="0" step="1000" value="{{ old('price', 0) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Status & Urutan -->
                    <div class="row g-2 mb-0 pt-2 border-top" style="border-color: var(--admin-card-border) !important;">
                        <div class="col-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label small fw-semibold" for="is_active">Aktifkan di Katalog</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="order" class="form-label small mb-1">Urutan Prioritas</label>
                            <input type="number" name="order" id="order" class="form-control form-control-sm" value="{{ old('order', 0) }}" min="0">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #F5A623, #D48010); border: none; color: #111827;">
                        <i class="bi bi-cloud-arrow-up-fill fs-5"></i> Simpan &amp; Terbitkan E-Book
                    </button>
                    <a href="{{ route('admin.ebooks.index') }}" class="btn btn-outline-secondary py-2 small">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function togglePriceInput(checkbox) {
        const wrapper = document.getElementById('price_wrapper');
        if (checkbox.checked) {
            wrapper.style.display = 'none';
        } else {
            wrapper.style.display = 'block';
        }
    }

    document.querySelectorAll('input, textarea, select').forEach(el => {
        el.addEventListener('input', () => {
            if (el.classList.contains('is-invalid')) {
                el.classList.remove('is-invalid');
                const next = el.nextElementSibling;
                if (next && next.classList.contains('text-danger')) {
                    next.style.display = 'none';
                }
            }
        });
    });
</script>
@endpush
@endsection
