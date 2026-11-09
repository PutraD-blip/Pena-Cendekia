@extends('layouts.admin')

@section('title', 'Edit Testimoni Penulis — Super Admin')
@section('page-title', 'Edit Testimoni')

@section('content')
<div class="container-fluid px-0" style="max-width: 900px;">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1" style="color: var(--admin-text-main);">Edit Testimoni Penulis ✍️</h1>
            <p class="text-muted small mb-0">Perbarui ulasan, gelar, foto, atau status tayang testimoni dari <strong>{{ $testimonial->name }}</strong>.</p>
        </div>
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3 d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <!-- Form Card -->
    <div class="admin-card p-4">
        <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Nama Penulis -->
                <div class="col-md-7">
                    <label for="name" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                        Nama Lengkap Penulis &amp; Gelar <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $testimonial->name) }}" required style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Profesi / Role -->
                <div class="col-md-5">
                    <label for="role" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                        Profesi / Asal Institusi <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="role" id="role" class="form-control @error('role') is-invalid @enderror" value="{{ old('role', $testimonial->role) }}" required style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Foto Penulis -->
                <div class="col-md-6">
                    <label for="photo" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                        Foto Profil Penulis (Portrait)
                    </label>
                    @if($testimonial->photo)
                        <div class="d-flex align-items-center gap-3 mb-2 p-2 rounded-3" style="background: var(--admin-card-surface); border: 1px solid var(--admin-card-border);">
                            <img src="{{ $testimonial->photo_url }}" alt="{{ $testimonial->name }}" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;">
                            <small class="text-muted" style="font-size: 11.5px;">Foto saat ini terpasang. Unggah berkas baru di bawah jika ingin menggantinya.</small>
                        </div>
                    @endif
                    <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/webp" style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                    <small class="text-muted d-block mt-1" style="font-size: 11.5px;">
                        Gunakan foto portrait rasio 1:1 (persegi) agar tampil proporsional di slider. Maks. 5 MB.
                    </small>
                    @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Rating Bintang -->
                <div class="col-md-3">
                    <label for="rating" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                        Rating Bintang <span class="text-danger">*</span>
                    </label>
                    <select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror" style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                        <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Bintang)</option>
                        <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4 Bintang)</option>
                        <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>⭐⭐⭐ (3 Bintang)</option>
                    </select>
                    @error('rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Urutan Tampil -->
                <div class="col-md-3">
                    <label for="order" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                        Urutan Tampil
                    </label>
                    <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $testimonial->order) }}" min="0" style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">
                    <small class="text-muted d-block mt-1" style="font-size: 11px;">Angka lebih kecil tampil lebih awal.</small>
                    @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Isi Testimoni -->
                <div class="col-12">
                    <label for="quote" class="form-label fw-semibold small" style="color: var(--admin-text-main);">
                        Kutipan Ulasan / Testimoni Penulis <span class="text-danger">*</span>
                    </label>
                    <textarea name="quote" id="quote" rows="4" class="form-control @error('quote') is-invalid @enderror" placeholder="Tuliskan ulasan kepuasan penulis..." required style="background: var(--admin-card-surface); border-color: var(--admin-card-border); color: var(--admin-text-main);">{{ old('quote', $testimonial->quote) }}</textarea>
                    @error('quote') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Status Aktif / Tayang -->
                <div class="col-12">
                    <div class="form-check form-switch p-3 rounded-3" style="background: var(--admin-card-surface); border: 1px solid var(--admin-card-border);">
                        <input class="form-check-input ms-0 me-3" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }} style="cursor: pointer; transform: scale(1.2);">
                        <label class="form-check-label fw-semibold" for="is_active" style="color: var(--admin-text-main); cursor: pointer;">
                            Tayangkan di Beranda Website
                        </label>
                        <small class="text-muted d-block mt-1">Jika dinonaktifkan (draft), ulasan testimoni ini akan disembunyikan sementara dari publik.</small>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="col-12 d-flex justify-content-end gap-2 pt-3 border-top" style="border-color: var(--admin-card-border) !important;">
                    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary px-4 py-2 rounded-3">Batal</a>
                    <button type="submit" class="btn btn-warning px-4 py-2 rounded-3 fw-bold shadow-sm" style="background: linear-gradient(135deg, #F5A623, #D48010); border: none; color: #111827;">
                        <i class="bi bi-save2-fill me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
