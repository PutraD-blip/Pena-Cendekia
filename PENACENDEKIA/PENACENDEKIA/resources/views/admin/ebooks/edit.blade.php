@extends('layouts.admin')

@section('title', 'Edit E-Book: ' . $ebook->title . ' — Super Admin')
@section('page-title', 'Edit E-Book')

@section('content')
<div class="container-fluid px-0">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color: var(--admin-text-main);">
                Perbarui E-Book 📝
            </h1>
            <p class="text-muted small mb-0">
                Memperbarui data katalog bibliografi atau mengganti berkas e-book untuk: <strong>{{ $ebook->title }}</strong>
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.ebooks.preview', $ebook) }}" target="_blank" class="btn btn-outline-info px-3 py-2 rounded-3 small">
                <i class="bi bi-eye me-1"></i> Pratinjau
            </a>
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

    <form action="{{ route('admin.ebooks.update', $ebook) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

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
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="Contoh: Metodologi Penelitian Kesehatan Terapan" value="{{ old('title', $ebook->title) }}" required>
                        @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Penulis -->
                        <div class="col-md-7">
                            <label for="author" class="form-label">Penulis / Tim Penulis <span class="text-danger">*</span></label>
                            <input type="text" name="author" id="author" class="form-control @error('author') is-invalid @enderror" placeholder="Contoh: Dr. H. Ahmad Fauzi, M.Kes" value="{{ old('author', $ebook->author) }}" required>
                            @error('author') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Editor -->
                        <div class="col-md-5">
                            <label for="editor" class="form-label">Penyunting / Editor</label>
                            <input type="text" name="editor" id="editor" class="form-control" placeholder="Contoh: Tim Redaksi Pena Cendekia" value="{{ old('editor', $ebook->editor) }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Penerbit -->
                        <div class="col-md-6">
                            <label for="publisher" class="form-label">Penerbit Resmi</label>
                            <input type="text" name="publisher" id="publisher" class="form-control" value="{{ old('publisher', $ebook->publisher) }}">
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6">
                            <label for="category" class="form-label">Kategori / Bidang Ilmu</label>
                            <input type="text" name="category" id="category" class="form-control" placeholder="Contoh: Kesehatan, Pendidikan, Hukum" value="{{ old('category', $ebook->category) }}" list="categoryList">
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
                            <input type="text" name="isbn" id="isbn" class="form-control" placeholder="Contoh: 978-623-1234-56-7" value="{{ old('isbn', $ebook->isbn) }}">
                        </div>

                        <!-- Edisi -->
                        <div class="col-md-6">
                            <label for="edition" class="form-label">Edisi / Seri Terbitan</label>
                            <input type="text" name="edition" id="edition" class="form-control" placeholder="Contoh: Cetakan Ke-1, Edisi Revisi" value="{{ old('edition', $ebook->edition) }}">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Tahun Terbit -->
                        <div class="col-md-4">
                            <label for="publication_year" class="form-label">Tahun Terbit</label>
                            <input type="number" name="publication_year" id="publication_year" class="form-control" min="1950" max="{{ date('Y') + 2 }}" value="{{ old('publication_year', $ebook->publication_year) }}">
                        </div>

                        <!-- Jumlah Halaman -->
                        <div class="col-md-4">
                            <label for="pages" class="form-label">Jumlah Halaman</label>
                            <input type="number" name="pages" id="pages" class="form-control" min="1" placeholder="Contoh: 240" value="{{ old('pages', $ebook->pages) }}">
                        </div>

                        <!-- Bahasa -->
                        <div class="col-md-4">
                            <label for="language" class="form-label">Bahasa</label>
                            <input type="text" name="language" id="language" class="form-control" value="{{ old('language', $ebook->language) }}">
                        </div>
                    </div>

                    <!-- Sinopsis -->
                    <div class="mb-0">
                        <label for="synopsis" class="form-label">Sinopsis / Ringkasan Buku</label>
                        <textarea name="synopsis" id="synopsis" rows="5" class="form-control" placeholder="Uraikan sinopsis buku...">{{ old('synopsis', $ebook->synopsis) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Right Column: Berkas & Akses -->
            <div class="col-lg-5">
                <!-- Berkas Master E-Book -->
                <div class="admin-card p-4 mb-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: var(--admin-text-main);">
                        <i class="bi bi-file-earmark-arrow-up-fill text-warning"></i> Berkas Digital E-Book
                    </h5>

                    <!-- Status Berkas Saat Ini -->
                    <div class="p-3 rounded-3 mb-3" style="background: var(--admin-card-surface); border: 1px solid var(--admin-card-border);">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="small text-muted fw-semibold">Berkas E-Book Aktif:</span>
                            <span class="badge bg-primary-subtle text-primary text-uppercase">{{ $ebook->file_format }} ({{ $ebook->file_size }})</span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.ebooks.preview', $ebook) }}" target="_blank" class="btn btn-sm btn-outline-info flex-grow-1">
                                <i class="bi bi-eye me-1"></i> Pratinjau
                            </a>
                            <a href="{{ route('admin.ebooks.download', $ebook) }}" class="btn btn-sm btn-outline-success flex-grow-1">
                                <i class="bi bi-download me-1"></i> Unduh
                            </a>
                        </div>
                    </div>

                    <!-- Ganti Berkas Utama -->
                    <div class="mb-3">
                        <label for="ebook_file" class="form-label">Ganti Berkas Master E-Book <span class="badge bg-secondary-subtle text-secondary small">Opsional</span></label>
                        <input type="file" name="ebook_file" id="ebook_file" class="form-control @error('ebook_file') is-invalid @enderror" accept=".pdf,.epub">
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Biarkan kosong jika tidak ingin mengubah berkas master. Maks 100 MB.</small>
                        @error('ebook_file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Berkas Pratinjau (Sample) -->
                    <div class="mb-3">
                        <label for="sample_file" class="form-label">Ganti Berkas Bab Contoh (Sample) <span class="badge bg-secondary-subtle text-secondary small">Opsional</span></label>
                        <input type="file" name="sample_file" id="sample_file" class="form-control @error('sample_file') is-invalid @enderror" accept=".pdf">
                        @if($ebook->sample_file_path)
                            <div class="small text-success mt-1">
                                <i class="bi bi-check-circle me-1"></i> Sampel sudah diunggah: 
                                <a href="{{ route('admin.ebooks.preview', [$ebook, 'sample' => 1]) }}" target="_blank" class="text-decoration-underline text-success">Lihat Sample</a>
                            </div>
                        @endif
                        @error('sample_file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- Cover Gambar -->
                    <div class="mb-0">
                        <label for="cover_image" class="form-label">Ganti Gambar Sampul (Cover E-Book)</label>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <img src="{{ $ebook->cover_url }}" alt="Cover" style="width: 50px; height: 70px; object-fit: cover; border-radius: 6px; border: 1px solid var(--admin-card-border);">
                            <span class="small text-muted">Cover saat ini</span>
                        </div>
                        <input type="file" name="cover_image" id="cover_image" class="form-control @error('cover_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Maksimal 5 MB. Biarkan kosong bila tidak ingin mengganti cover.</small>
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
                                <input class="form-check-input" type="radio" name="access_type" id="access_public" value="public" {{ old('access_type', $ebook->access_type) === 'public' ? 'checked' : '' }}>
                                <label class="form-check-label" for="access_public">
                                    <strong>Publik Terbuka</strong>
                                    <small class="text-muted d-block">Dapat diunduh dan dibaca oleh siapa saja tanpa harus login.</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="access_type" id="access_registered" value="registered" {{ old('access_type', $ebook->access_type) === 'registered' ? 'checked' : '' }}>
                                <label class="form-check-label" for="access_registered">
                                    <strong>Pengguna Terdaftar Saja</strong>
                                    <small class="text-muted d-block">Wajib memiliki akun / login terlebih dahulu.</small>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="access_type" id="access_restricted" value="restricted" {{ old('access_type', $ebook->access_type) === 'restricted' ? 'checked' : '' }}>
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
                            <input class="form-check-input" type="checkbox" role="switch" name="is_free" id="is_free" value="1" {{ old('is_free', $ebook->is_free ? '1' : '0') == '1' ? 'checked' : '' }} onchange="togglePriceInput(this)">
                            <label class="form-check-label fw-bold" for="is_free">Gratis (Free E-Book Open Access)</label>
                        </div>

                        <div id="price_wrapper" style="{{ old('is_free', $ebook->is_free ? '1' : '0') == '1' ? 'display: none;' : '' }}">
                            <label for="price" class="form-label small">Harga Penjualan (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="price" id="price" class="form-control" placeholder="Contoh: 45000" min="0" step="1000" value="{{ old('price', (int)$ebook->price) }}">
                            </div>
                        </div>
                    </div>

                    <!-- Status & Urutan -->
                    <div class="row g-2 mb-0 pt-2 border-top" style="border-color: var(--admin-card-border) !important;">
                        <div class="col-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active" value="1" {{ old('is_active', $ebook->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label small fw-semibold" for="is_active">Aktifkan di Katalog</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="order" class="form-label small mb-1">Urutan Prioritas</label>
                            <input type="number" name="order" id="order" class="form-control form-control-sm" value="{{ old('order', $ebook->order) }}" min="0">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-warning py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #F5A623, #D48010); border: none; color: #111827;">
                        <i class="bi bi-save2-fill fs-5"></i> Simpan Perubahan E-Book
                    </button>
                    <a href="{{ route('admin.ebooks.index') }}" class="btn btn-outline-secondary py-2 small">
                        Batal
                    </a>
                    <button type="button" class="btn btn-outline-danger py-2 small fw-semibold d-flex align-items-center justify-content-center gap-2 mt-2" data-bs-toggle="modal" data-bs-target="#deleteEditModal">
                        <i class="bi bi-trash-fill"></i> Hapus E-Book Ini
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal Konfirmasi Hapus E-Book dari Halaman Edit -->
<div class="modal fade" id="deleteEditModal" tabindex="-1" aria-labelledby="deleteEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--admin-card-bg); border: 1px solid var(--admin-card-border); color: var(--admin-text-main);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger d-flex align-items-center gap-2" id="deleteEditModalLabel">
                    <i class="bi bi-exclamation-triangle-fill"></i> Hapus E-Book Ini
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <p class="mb-2">Apakah Anda yakin ingin menghapus e-book ini secara permanen?</p>
                <div class="p-3 rounded-3" style="background: var(--admin-card-surface); border: 1px solid var(--admin-card-border);">
                    <div class="fw-bold">{{ $ebook->title }}</div>
                    <div class="small text-muted">Penulis: {{ $ebook->author }}</div>
                    <div class="small text-muted">Format: {{ $ebook->file_format }} ({{ $ebook->file_size }})</div>
                </div>
                <p class="text-danger small mt-2 mb-0">
                    <i class="bi bi-info-circle me-1"></i> Berkas master file e-book dan gambar cover di server akan langsung dihapus secara permanen.
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
</script>
@endpush
@endsection
