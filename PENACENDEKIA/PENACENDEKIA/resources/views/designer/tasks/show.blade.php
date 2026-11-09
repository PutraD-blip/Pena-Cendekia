@extends('layouts.admin')

@section('title', 'Tugas: ' . $task->task_type_label . ' — ' . $task->submission->title)
@section('page-title', 'Detail Tugas')

@push('styles')
<style>
    .task-panel {
        background: var(--admin-card-bg);
        border: 1px solid var(--admin-card-border);
        border-radius: 16px;
        padding: 22px;
        margin-bottom: 24px;
        transition: background-color 0.25s ease, border-color 0.25s ease;
    }
    .task-header-bar {
        background: var(--admin-card-bg);
        border: 1px solid var(--admin-card-border);
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 24px;
    }
    .file-item {
        background: var(--admin-card-surface);
        border: 1px solid var(--admin-border);
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .btn-action-primary {
        background-color: #F5A623;
        color: #111827;
        font-size: 13px;
        font-weight: 700;
        padding: 8px 16px;
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
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-0">

    <!-- Top Action Header -->
    <div class="task-header-bar">
        <a href="{{ route('designer.dashboard') }}" class="btn-action-subtle mb-3">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Daftar Tugas</span>
        </a>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge font-monospace" style="background: rgba(245, 166, 35, 0.14); color: #F5A623; font-size: 11px;">
                        {{ $task->submission->code }}
                    </span>
                    <span class="badge rounded-pill" style="background: {{ $task->task_type === 'layout' ? 'rgba(6, 182, 212, 0.15)' : 'rgba(139, 92, 246, 0.15)' }}; color: {{ $task->task_type === 'layout' ? '#06B6D4' : '#8B5CF6' }}; font-weight: 700; font-size: 11.5px;">
                        {{ $task->task_type_label }}
                    </span>
                </div>
                <h1 class="h4 fw-bold mb-1" style="color: var(--admin-text-main);">{{ $task->submission->title }}</h1>
                <p class="text-muted small mb-0">
                    Penulis: <strong>{{ $task->submission->user->name }}</strong> &bull; Ditugaskan oleh: {{ $task->assigner->name ?? 'Admin Redaksi' }}
                </p>
            </div>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <span class="badge bg-{{ $task->status_badge }} px-3 py-2 fs-6 rounded-pill">
                    {{ $task->status_label }}
                </span>

                @if($task->status === 'not_started')
                    <form action="{{ route('designer.tasks.start', $task) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-action-primary">
                            <i class="bi bi-play-fill"></i> Mulai Kerjakan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Alert if Admin requested Revision -->
    @if($task->status === 'revision' && $task->admin_notes)
        <div class="alert alert-warning border-0 rounded-4 shadow-sm p-4 mb-4">
            <div class="d-flex gap-3">
                <i class="bi bi-exclamation-triangle-fill fs-3 text-warning"></i>
                <div>
                    <h6 class="fw-bold mb-1">Catatan Revisi dari Admin Redaksi:</h6>
                    <p class="mb-0 text-dark small">{{ $task->admin_notes }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Download Manuscript & Book Info -->
        <div class="col-lg-6">
            <!-- Berkas Unduhan untuk Desainer -->
            <div class="task-panel">
                <h5 class="fw-bold mb-2 d-flex align-items-center gap-2" style="color: var(--admin-text-main); font-size: 16px;">
                    <i class="bi bi-cloud-arrow-down-fill text-warning"></i>
                    <span>Berkas Acuan dari Penulis</span>
                </h5>
                <p class="text-muted small mb-3">Unduh naskah dan dokumen referensi berikut sebagai acuan utama pengerjaan.</p>

                <div class="d-flex flex-column gap-2">
                    <div class="file-item">
                        <div style="min-width: 0;">
                            <span class="fw-semibold small d-block text-truncate" style="color: var(--admin-text-main);">Naskah Lengkap (.docx/.pdf)</span>
                            <small class="text-muted text-truncate d-block">{{ $task->submission->manuscript_file }}</small>
                        </div>
                        <a href="{{ route('file.show', ['type' => 'manuscripts', 'filename' => $task->submission->manuscript_file]) }}" target="_blank" class="btn-action-primary py-1 px-3" style="font-size: 12px; flex-shrink: 0;">
                            <i class="bi bi-download"></i> Unduh
                        </a>
                    </div>

                    @if($task->submission->layout_file)
                        <div class="file-item">
                            <div style="min-width: 0;">
                                <span class="fw-semibold small d-block text-truncate" style="color: var(--admin-text-main);">Layout Referensi Penulis</span>
                                <small class="text-muted text-truncate d-block">{{ $task->submission->layout_file }}</small>
                            </div>
                            <a href="{{ route('file.show', ['type' => 'layouts_from_author', 'filename' => $task->submission->layout_file]) }}" target="_blank" class="btn-action-subtle py-1 px-3" style="font-size: 12px; flex-shrink: 0;">
                                <i class="bi bi-download"></i> Unduh
                            </a>
                        </div>
                    @endif

                    @if($task->submission->originality_statement_file)
                        <div class="file-item">
                            <div style="min-width: 0;">
                                <span class="fw-semibold small d-block text-truncate" style="color: var(--admin-text-main);">Surat Pernyataan Keaslian Karya</span>
                                <small class="text-muted text-truncate d-block">{{ $task->submission->originality_statement_file }}</small>
                            </div>
                            <a href="{{ route('file.show', ['type' => 'statements', 'filename' => $task->submission->originality_statement_file]) }}" target="_blank" class="btn-action-subtle py-1 px-3" style="font-size: 12px; flex-shrink: 0;">
                                <i class="bi bi-download"></i> Unduh
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Detail Spesifikasi Buku -->
            <div class="task-panel">
                <h5 class="fw-bold mb-3" style="color: var(--admin-text-main); font-size: 16px;">Spesifikasi Cetak Buku</h5>
                <table class="table table-borderless small mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width: 38%;">Penulis</td>
                            <td class="fw-semibold" style="color: var(--admin-text-main);">{{ $task->submission->user->name }}</td>
                        </tr>
                        @if($task->submission->editor_name)
                            <tr>
                                <td class="text-muted">Editor Tambahan</td>
                                <td style="color: var(--admin-text-main);">{{ $task->submission->editor_name }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-muted">Genre / Kategori</td>
                            <td><span class="badge" style="background: var(--admin-card-surface); border: 1px solid var(--admin-border); color: var(--admin-text-main);">{{ $task->submission->category->name ?? 'Umum' }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estimasi Halaman</td>
                            <td style="color: var(--admin-text-main);"><strong>{{ $task->submission->estimated_pages }}</strong> Halaman (A5)</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jumlah Cetak</td>
                            <td style="color: var(--admin-text-main);"><strong>{{ $task->submission->quantity }}</strong> Eksemplar</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jenis Cover</td>
                            <td><span class="badge" style="background: var(--admin-card-surface); border: 1px solid var(--admin-border); color: var(--admin-text-main);">{{ ucfirst($task->submission->cover_type) }} Cover</span></td>
                        </tr>
                    </tbody>
                </table>

                @if($task->submission->synopsis)
                    <hr class="my-3" style="border-color: var(--admin-border);">
                    <h6 class="fw-bold small text-muted text-uppercase mb-2" style="font-size: 11px;">Sinopsis Naskah</h6>
                    <div class="p-3 rounded-3 small" style="background: var(--admin-card-surface); color: var(--admin-text-sub); line-height: 1.6;">
                        {!! nl2br(e($task->submission->synopsis)) !!}
                    </div>
                @endif

                @if($task->submission->notes)
                    <h6 class="fw-bold small text-muted text-uppercase mt-3 mb-2" style="font-size: 11px;">Catatan Penulis</h6>
                    <div class="p-3 rounded-3 small" style="background: var(--admin-card-surface); color: var(--admin-text-sub); line-height: 1.6;">
                        {{ $task->submission->notes }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Upload Deliverable Form -->
        <div class="col-lg-6">
            <div class="task-panel">
                <h5 class="fw-bold mb-2" style="color: var(--admin-text-main); font-size: 16px;">Unggah Hasil Pengerjaan</h5>
                <p class="text-muted small mb-3">
                    @if($task->task_type === 'layout')
                        Unggah berkas layout isi buku (format <strong>PDF / DOCX / ZIP</strong>, maks 100MB).
                    @else
                        Unggah desain cover buku resolusi cetak (format <strong>JPG / PNG / PDF / ZIP</strong>, minimal 300 DPI, maks 100MB).
                    @endif
                </p>

                @if($task->result_file)
                    <div class="p-3 rounded-3 mb-4" style="background: var(--admin-card-surface); border: 1px solid var(--admin-border);">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-semibold" style="color: var(--admin-text-main);"><i class="bi bi-file-earmark-check-fill text-success me-1"></i> Berkas Terakhir Diunggah:</span>
                            <a href="{{ route('file.show', ['type' => $task->task_type === 'layout' ? 'design_layouts' : 'design_covers', 'filename' => $task->result_file]) }}" target="_blank" class="btn-action-subtle py-1 px-2" style="font-size: 11px;">
                                Lihat Berkas
                            </a>
                        </div>
                        <small class="text-muted d-block text-break">{{ $task->result_file }}</small>
                        @if($task->designer_notes)
                            <small class="text-muted d-block mt-1">Catatan Anda: "{{ $task->designer_notes }}"</small>
                        @endif

                        <div class="mt-2 pt-2 border-top small" style="border-color: var(--admin-border) !important;">
                            Status Tinjauan Admin: 
                            @if($task->admin_approved === true)
                                <span class="badge bg-success"><i class="bi bi-check2"></i> Disetujui</span>
                            @elseif($task->admin_approved === false)
                                <span class="badge bg-danger"><i class="bi bi-x"></i> Memerlukan Perbaikan</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Menunggu Tinjauan</span>
                            @endif
                        </div>
                    </div>
                @endif

                <form action="{{ route('designer.tasks.upload', $task) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="result_file" class="form-label fw-semibold small" style="color: var(--admin-text-main);">Pilih Berkas Hasil {{ $task->task_type_label }} <span class="text-danger">*</span></label>
                        <input type="file" name="result_file" id="result_file" class="form-control @error('result_file') is-invalid @enderror" required accept="{{ $task->task_type === 'layout' ? '.pdf,.docx,.zip' : '.jpg,.jpeg,.png,.pdf,.zip' }}">
                        <small class="text-muted d-block mt-1">
                            Format diizinkan: {{ $task->task_type === 'layout' ? 'PDF, DOCX, ZIP' : 'JPG, PNG, PDF, ZIP' }} (Maks. 100 MB).
                        </small>
                        @error('result_file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="designer_notes" class="form-label fw-semibold small" style="color: var(--admin-text-main);">Catatan untuk Admin Redaksi (Opsional)</label>
                        <textarea name="designer_notes" id="designer_notes" rows="3" class="form-control" placeholder="Contoh: Layout menggunakan margin 2cm dan font Garamond 11pt...">{{ old('designer_notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn-action-primary w-100 justify-content-center py-2">
                        <i class="bi bi-cloud-arrow-up-fill"></i> Unggah &amp; Kirim ke Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
