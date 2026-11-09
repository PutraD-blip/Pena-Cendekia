@extends('layouts.author')

@section('title', 'Perbaiki Naskah: ' . $submission->title)
@section('page-title', 'Revisi & Perbaikan Naskah')

@section('content')
<div class="container-fluid max-w-4xl px-0">
    <div class="mb-4">
        <a href="{{ route('author.submissions.show', $submission) }}" class="btn btn-sm btn-outline-light mb-2"><i class="bi bi-arrow-left me-1"></i> Kembali ke Detail Naskah</a>
        <h3 class="fw-bold mb-1 text-white" style="font-family: var(--font-heading);">Perbaiki Naskah: {{ $submission->title }}</h3>
        <p class="text-muted small">Perbarui data atau unggah berkas revisi sesuai catatan yang diberikan oleh Dewan Redaksi.</p>
    </div>

    <!-- Catatan Revisi dari Admin -->
    <div class="alert alert-danger border-0 rounded-4 shadow-sm p-4 mb-4" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(20, 28, 48, 0.95) 100%); border: 1.5px solid rgba(239, 68, 68, 0.45) !important;">
        <div class="d-flex align-items-start gap-3">
            <i class="bi bi-chat-left-dots-fill fs-3 text-danger"></i>
            <div>
                <h6 class="fw-bold mb-1 text-white">Catatan Revisi dari Admin Redaksi:</h6>
                <p class="mb-0 text-white" style="font-size: 14px;">{{ $submission->admin_notes }}</p>
            </div>
        </div>
    </div>

    <form action="{{ route('author.submissions.update', $submission) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="dash-card mb-4">
            <h5 class="fw-bold mb-3 text-white" style="font-family: var(--font-heading);">Data Naskah Buku</h5>

            <div class="row g-3">
                <div class="col-md-8">
                    <label for="title" class="form-label">Judul Buku <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $submission->title) }}" required>
                    @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label for="pen_name" class="form-label">Nama Pena</label>
                    <input type="text" name="pen_name" id="pen_name" class="form-control" value="{{ old('pen_name', $submission->pen_name) }}">
                </div>

                <div class="col-md-6">
                    <label for="editor_name" class="form-label">Nama Editor Naskah <span class="text-danger">*</span></label>
                    <input type="text" name="editor_name" id="editor_name" class="form-control @error('editor_name') is-invalid @enderror" value="{{ old('editor_name', $submission->editor_name) }}" required>
                    @error('editor_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-3">
                    <label for="category_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" class="form-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $submission->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="target_reader" class="form-label">Target Pembaca <span class="text-danger">*</span></label>
                    <select name="target_reader" id="target_reader" class="form-select" required>
                        <option value="general" {{ old('target_reader', $submission->target_reader) == 'general' ? 'selected' : '' }}>Umum</option>
                        <option value="adult" {{ old('target_reader', $submission->target_reader) == 'adult' ? 'selected' : '' }}>Dewasa</option>
                        <option value="teen" {{ old('target_reader', $submission->target_reader) == 'teen' ? 'selected' : '' }}>Remaja</option>
                        <option value="children" {{ old('target_reader', $submission->target_reader) == 'children' ? 'selected' : '' }}>Anak-anak</option>
                    </select>
                </div>

                <div class="col-12">
                    <label for="synopsis" class="form-label">Sinopsis Buku <span class="text-danger">*</span></label>
                    <textarea name="synopsis" id="synopsis" rows="5" class="form-control" required>{{ old('synopsis', $submission->synopsis) }}</textarea>
                </div>
            </div>
        </div>

        <div class="dash-card mb-4">
            <h5 class="fw-bold mb-3 text-white" style="font-family: var(--font-heading);">Unggah Berkas Revisi (Opsional Jika Tidak Ada Perubahan Berkas)</h5>

            <div class="row g-4">
                <div class="col-md-6">
                    <label for="manuscript_file" class="form-label">Unggah Ulang Naskah (DOCX / PDF)</label>
                    <div class="p-3 rounded-3" style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid var(--author-border);">
                        <input type="file" name="manuscript_file" id="manuscript_file" class="form-control" accept=".docx,.pdf">
                        <small class="text-muted d-block mt-2" style="font-size: 11px;">Berkas saat ini: <strong class="text-white">{{ $submission->manuscript_file }}</strong>. Kosongkan jika berkas naskah tidak berubah.</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="layout_file" class="form-label">Unggah Ulang Berkas Layout (Opsional)</label>
                    <div class="p-3 rounded-3" style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid var(--author-border);">
                        <input type="file" name="layout_file" id="layout_file" class="form-control" accept=".docx,.pdf,.cdr,.ai,.zip">
                        <small class="text-muted d-block mt-2" style="font-size: 11px;">Berkas saat ini: <strong class="text-white">{{ $submission->layout_file ?? 'Tidak ada' }}</strong></small>
                    </div>
                </div>

                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label for="originality_statement_file" class="form-label mb-0">Unggah Ulang Surat Pernyataan Keaslian Karya (Opsional)</label>
                        <div class="d-flex align-items-center gap-1">
                            <a href="https://docs.google.com/document/d/1mhQrLKIVESY2vE7pRz9CVXRINmrHkBn4/edit" target="_blank" rel="noopener" class="btn btn-sm btn-outline-info py-0 px-2" style="font-size: 11px;">
                                <i class="bi bi-box-arrow-up-right"></i> Docs
                            </a>
                            <a href="{{ asset('downloads/surat-pernyataan-hak-cipta.docx') }}" download class="btn btn-sm btn-outline-warning py-0 px-2" style="font-size: 11px;">
                                <i class="bi bi-download"></i> Unduh .docx
                            </a>
                        </div>
                    </div>
                    <div class="p-3 rounded-3" style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid var(--author-border);">
                        <input type="file" name="originality_statement_file" id="originality_statement_file" class="form-control" accept=".docx,.doc,.pdf,.jpg,.jpeg,.png">
                        <small class="text-muted d-block mt-2" style="font-size: 11px;">Berkas saat ini: <strong class="text-white">{{ $submission->originality_statement_file ?? 'Tidak ada berkas keaslian' }}</strong>. Kosongkan jika tidak ada perubahan.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="dash-card mb-4">
            <h5 class="fw-bold mb-3 text-white" style="font-family: var(--font-heading);">Preferensi Cetak</h5>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="estimated_pages" class="form-label">Estimasi Halaman <span class="text-danger">*</span></label>
                    <input type="number" name="estimated_pages" id="estimated_pages" min="40" class="form-control" value="{{ old('estimated_pages', $submission->estimated_pages) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="quantity" class="form-label">Jumlah Cetak <span class="text-danger">*</span></label>
                    <input type="number" name="quantity" id="quantity" min="10" class="form-control" value="{{ old('quantity', $submission->quantity) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis Cover <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cover_type" id="cover_soft" value="soft" {{ old('cover_type', $submission->cover_type) == 'soft' ? 'checked' : '' }}>
                            <label class="form-check-label text-white small" for="cover_soft">Soft Cover</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="cover_type" id="cover_hard" value="hard" {{ old('cover_type', $submission->cover_type) == 'hard' ? 'checked' : '' }}>
                            <label class="form-check-label text-white small" for="cover_hard">Hard Cover</label>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <label for="notes" class="form-label">Catatan Tambahan</label>
                    <textarea name="notes" id="notes" rows="2" class="form-control">{{ old('notes', $submission->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mb-5">
            <a href="{{ route('author.submissions.show', $submission) }}" class="btn btn-outline-light px-4">Batal</a>
            <button type="submit" class="btn btn-gold px-5 py-2">
                <i class="bi bi-send-check me-1"></i> Simpan & Ajukan Ulang ke Admin
            </button>
        </div>
    </form>
</div>
@endsection
