@extends('layouts.admin')

@section('title', 'Tinjau Naskah: ' . $submission->title)
@section('page-title', 'Tinjau & Validasi Naskah')

@section('content')
<div class="container-fluid max-w-6xl">
    <div class="mb-4">
        <a href="{{ route('admin.submissions.index') }}" class="btn btn-sm btn-light mb-2"><i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Naskah</a>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="badge bg-light text-dark border font-monospace mb-1">{{ $submission->code }}</span>
                <h2 class="fw-bold font-serif mb-0">{{ $submission->title }}</h2>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge bg-{{ $submission->status_badge }} px-3 py-2 fs-6 rounded-pill">
                    {{ $submission->status_label }}
                </span>

                @if($submission->status === 'ready_to_print')
                    <form action="{{ route('admin.submissions.printing', $submission) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info text-white" onclick="return confirm('Mulai proses percetakan untuk naskah ini?')">
                            <i class="bi bi-printer-fill me-1"></i> Mulai Cetak
                        </button>
                    </form>
                @endif

                @if($submission->status === 'printing')
                    <form action="{{ route('admin.submissions.complete', $submission) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Selesaikan seluruh proses penerbitan dan pengiriman buku ini?')">
                            <i class="bi bi-check2-circle me-1"></i> Tandai Selesai
                        </button>
                    </form>
                @endif
            </div>
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-exclamation-octagon-fill fs-5"></i>
                <strong class="mb-0">Mohon periksa kembali formulir:</strong>
            </div>
            <ul class="mb-0 small ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- Left Column: Details & Files -->
        <div class="col-lg-7">
            <!-- Naskah Info -->
            <div class="card card-soft p-4 mb-4">
                <h5 class="fw-bold font-serif mb-3">Informasi Pengajuan &amp; Layanan</h5>
                <table class="table table-borderless small mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width: 30%;">Jenis Layanan</td>
                            <td>
                                <span class="badge bg-warning text-dark px-3 py-1">
                                    <i class="bi {{ $submission->service_icon ?? 'bi-book' }} me-1"></i>
                                    {{ $submission->service_type_label ?? 'Penerbitan Buku' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Paket Dipilih</td>
                            <td class="fw-bold text-primary">{{ $submission->package_name ?? 'Paket Kustom' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Judul Karya</td>
                            <td class="fw-bold">{{ $submission->title }}</td>
                        </tr>
                        @if($submission->pen_name)
                            <tr>
                                <td class="text-muted">Nama Pena</td>
                                <td>{{ $submission->pen_name }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-muted">Nama Editor</td>
                            <td>{{ $submission->editor_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kategori / Genre</td>
                            <td><span class="badge bg-secondary-subtle text-secondary">{{ $submission->category->name }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Target Pembaca</td>
                            <td>{{ ucfirst($submission->target_reader) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estimasi Halaman</td>
                            <td>{{ $submission->estimated_pages }} Hal (A5)</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jumlah Pesanan</td>
                            <td>{{ $submission->quantity }} Eksemplar</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jenis Cover</td>
                            <td><span class="badge bg-light text-dark border">{{ ucfirst($submission->cover_type) }} Cover</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Waktu Diajukan</td>
                            <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : '-' }}</td>
                        </tr>
                    </tbody>
                </table>

                <hr class="my-3">

                <h6 class="fw-bold small text-muted text-uppercase mb-2">Sinopsis Naskah</h6>
                <div class="p-3 bg-light rounded-3 small text-muted mb-3">
                    {!! nl2br(e($submission->synopsis)) !!}
                </div>

                @if($submission->notes)
                    <h6 class="fw-bold small text-muted text-uppercase mb-2">Catatan Khusus dari Penulis</h6>
                    <div class="p-3 bg-light rounded-3 small text-muted">
                        {{ $submission->notes }}
                    </div>
                @endif
            </div>

            <!-- Berkas Naskah & Layout Penulis -->
            <div class="card card-soft p-4 mb-4">
                <h5 class="fw-bold font-serif mb-3">Unduh Berkas Naskah</h5>
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-word fs-3 text-primary"></i>
                            <div>
                                <span class="d-block fw-semibold small">Berkas Naskah (.docx/.pdf)</span>
                                <small class="text-muted">{{ $submission->manuscript_file }}</small>
                            </div>
                        </div>
                        <a href="{{ route('file.show', ['type' => 'manuscripts', 'filename' => $submission->manuscript_file]) }}" target="_blank" class="btn btn-sm btn-brand">
                            <i class="bi bi-download me-1"></i> Unduh Naskah
                        </a>
                    </div>

                    @if($submission->layout_file)
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-layout-text-window-reverse fs-3 text-secondary"></i>
                                <div>
                                    <span class="d-block fw-semibold small">Berkas Layout / Lampiran</span>
                                    <small class="text-muted">{{ $submission->layout_file }}</small>
                                </div>
                            </div>
                            <a href="{{ route('file.show', ['type' => 'layouts_from_author', 'filename' => $submission->layout_file]) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-download me-1"></i> Unduh Layout
                            </a>
                        </div>
                    @endif

                    @if($submission->originality_statement_file)
                        <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.25);">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-shield-check fs-3 text-success"></i>
                                <div>
                                    <span class="d-block fw-semibold small text-success">Surat Pernyataan Keaslian Karya (Bermaterai)</span>
                                    <small class="text-muted">{{ $submission->originality_statement_file }}</small>
                                </div>
                            </div>
                            <a href="{{ route('file.show', ['type' => 'statements', 'filename' => $submission->originality_statement_file]) }}" target="_blank" class="btn btn-sm btn-success">
                                <i class="bi bi-download me-1"></i> Unduh Surat Keaslian
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Riwayat Catatan Revisi Admin -->
            @if($submission->revisions->isNotEmpty())
                <div class="card card-soft p-4 mb-4">
                    <h5 class="fw-bold font-serif mb-3">Riwayat Catatan Revisi untuk Penulis</h5>
                    <div class="d-flex flex-column gap-2">
                        @foreach($submission->revisions as $rev)
                            <div class="p-3 bg-light rounded-3 border-start border-4 border-warning">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>Direview oleh: <strong>{{ $rev->admin->name }}</strong></span>
                                    <span>{{ $rev->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="small text-dark">{{ $rev->revision_notes }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions (Review, Assign, Deliverables, Payments) -->
        <div class="col-lg-5">
            <!-- Author Profile Card -->
            <div class="card card-soft p-4 mb-4">
                <h5 class="fw-bold font-serif mb-3">Data Penulis</h5>
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light p-3 fs-4 text-secondary">
                        <i class="bi bi-person"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0">{{ $submission->user->name }}</h6>
                        <small class="text-muted d-block">{{ $submission->user->email }}</small>
                        <small class="text-muted d-block"><i class="bi bi-whatsapp text-success me-1"></i> {{ $submission->user->phone }}</small>
                    </div>
                </div>

                @if($submission->user->profile)
                    <div class="p-3 bg-light rounded-3 small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">NIK:</span>
                            <span class="fw-semibold">{{ $submission->user->profile->masked_nik }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted d-block">Alamat:</span>
                            <span>{{ $submission->user->profile->address }}</span>
                        </div>
                        @if($submission->user->profile->ktp_file)
                            <a href="{{ route('file.show', ['type' => 'ktp', 'filename' => basename($submission->user->profile->ktp_file)]) }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100">
                                <i class="bi bi-file-earmark-person me-1"></i> Buka Foto KTP Penulis
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- [TINJAUAN 1] Review Naskah Awal dari Penulis -->
            @if(in_array($submission->status, ['pending_review', 'revision_required', 'waiting_payment']))
                <div class="card card-soft p-4 mb-4 border-warning shadow-sm">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h5 class="fw-bold font-serif mb-0 text-warning d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            <span>[Tinjauan 1] Naskah Masuk dari Penulis</span>
                        </h5>
                        <span class="badge bg-warning text-dark px-2 py-1 small fw-bold">Tahap Awal</span>
                    </div>
                    <p class="text-muted small mb-3">
                        @if($submission->status === 'waiting_payment')
                            <span class="text-success fw-semibold"><i class="bi bi-check2-circle me-1"></i> Naskah telah disetujui &amp; tagihan DP 50% telah dikirim ke Penulis.</span> Anda masih dapat menyesuaikan kembali total biaya jika terdapat perubahan spesifikasi.
                        @else
                            Tinjau kelayakan naskah awal dari penulis, tetapkan total biaya cetak, dan tagihkan DP 50% untuk mulai pengerjaan.
                        @endif
                    </p>

                    <!-- Form Setujui Naskah -->
                    <form action="{{ route('admin.submissions.review', $submission) }}" method="POST" class="mb-3 p-3 bg-light rounded-3">
                        @csrf
                        <input type="hidden" name="action" value="approve">
                        <h6 class="fw-bold small text-success mb-2">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            {{ $submission->status === 'waiting_payment' ? 'Penyesuaian Biaya & Tagihan DP' : 'Setujui Naskah & Tentukan Biaya' }}
                        </h6>

                        @if($submission->package_price)
                            <div class="mb-2 p-2 bg-white rounded border border-success-subtle d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="d-block text-muted" style="font-size: 11px;">Paket Penulis: <strong>{{ $submission->package_name }}</strong></span>
                                    <span class="fw-bold text-success small">Rp {{ number_format($submission->package_price, 0, ',', '.') }}</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success py-0 px-2" style="font-size: 11px;" onclick="document.getElementById('input_total_price').value = {{ $submission->package_price }}; updateLiveCalc();">
                                    <i class="bi bi-arrow-down-circle me-1"></i> Terapkan
                                </button>
                            </div>
                        @endif

                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Total Biaya Layanan / Cetak (Rp) <span class="text-danger">*</span></label>
                            <input type="number" id="input_total_price" name="total_price" step="100" min="0" class="form-control form-control-sm" placeholder="Contoh: 2500000" required value="{{ old('total_price', $submission->total_price ?? $submission->package_price) }}" oninput="updateLiveCalc()">
                            <small class="text-muted" style="font-size: 11px;">Sistem akan otomatis menghitung DP 50% dan Pelunasan 50%.</small>
                        </div>

                        <!-- Live Calculation Preview Box -->
                        <div id="live_calc_box" class="p-2 mb-2 rounded-2 border bg-white small">
                            <div class="d-flex justify-content-between align-items-center mb-1 text-muted" style="font-size: 11px;">
                                <span><i class="bi bi-credit-card-2-front text-primary me-1"></i> DP 50% (Ditagihkan Sekarang):</span>
                                <strong class="text-primary" id="preview_dp">Rp 0</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 11px;">
                                <span><i class="bi bi-check2-circle text-secondary me-1"></i> Pelunasan 50% (Saat Selesai):</span>
                                <strong class="text-secondary" id="preview_final">Rp 0</strong>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Catatan untuk Penulis (Opsional)</label>
                            <textarea name="admin_notes" rows="2" class="form-control form-control-sm" placeholder="Ucapan selamat / instruksi naskah...">{{ old('admin_notes', $submission->admin_notes) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm w-100 fw-bold">
                            <i class="bi bi-send-check-fill me-1"></i>
                            {{ $submission->status === 'waiting_payment' ? 'Simpan & Perbarui Tagihan DP' : 'Setujui & Minta DP 50%' }}
                        </button>
                    </form>

                    <!-- Form Minta Revisi Naskah -->
                    @if($submission->status !== 'waiting_payment')
                        <form action="{{ route('admin.submissions.review', $submission) }}" method="POST" class="p-3 bg-light rounded-3">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <h6 class="fw-bold small text-danger mb-2"><i class="bi bi-x-circle-fill me-1"></i> Minta Revisi Naskah</h6>

                            <div class="mb-2">
                                <label class="form-label small fw-semibold">Catatan Perbaikan <span class="text-danger">*</span></label>
                                <textarea name="revision_notes" rows="3" class="form-control form-control-sm" required placeholder="Jelaskan alasan dan bagian yang harus diperbaiki oleh penulis...">{{ old('revision_notes') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="bi bi-arrow-repeat me-1"></i> Kirim Permintaan Revisi ke Penulis
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            <!-- [TINJAUAN 2] Penugasan & Hasil Tim Desain / Editor -->
            <div class="card card-soft p-4 mb-4 shadow-sm border-primary">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="fw-bold font-serif mb-0 text-primary d-flex align-items-center gap-2">
                        <i class="bi bi-palette-fill"></i>
                        <span>[Tinjauan 2] Hasil Pengerjaan Editor / Desainer</span>
                    </h5>
                    <span class="badge bg-primary px-2 py-1 small fw-bold">Quality Control</span>
                </div>
                <p class="text-muted small mb-3">Tugaskan pengerjaan layout &amp; cover ke editor, tinjau hasil karya yang diunggah, lalu setujui untuk diteruskan ke dashboard Penulis.</p>

                <!-- Tugas Layout & Cover Cards -->
                @foreach(['layout' => 'Layout Isi Buku', 'cover' => 'Desain Cover'] as $type => $label)
                    @php
                        $task = $submission->designTasks->where('task_type', $type)->first();
                    @endphp
                    <div class="p-3 border rounded-3 bg-white mb-3 shadow-none">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold small text-dark">{{ $label }}</span>
                            @if($task)
                                <span class="badge bg-{{ $task->status_badge }} rounded-pill">{{ $task->status_label }}</span>
                            @else
                                <span class="badge bg-secondary-subtle text-muted rounded-pill">Belum Ditugaskan</span>
                            @endif
                        </div>

                        @if($task)
                            <div class="small text-muted mb-2">
                                Editor / Desainer: <strong class="text-dark">{{ $task->designer->name }}</strong>
                            </div>

                            <!-- Desain Deliverable Uploaded by Designer -->
                            @if($task->result_file)
                                <div class="p-3 bg-light rounded-3 mb-2 border" style="background: rgba(13, 110, 253, 0.04); border-color: rgba(13, 110, 253, 0.2) !important;">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-1">
                                        <span class="small fw-bold text-dark"><i class="bi bi-file-earmark-check-fill text-success me-1"></i> Berkas Hasil dari Editor:</span>
                                        <a href="{{ route('file.show', ['type' => $type === 'layout' ? 'design_layouts' : 'design_covers', 'filename' => $task->result_file]) }}" target="_blank" class="btn btn-sm btn-primary px-3 fw-semibold">
                                            <i class="bi bi-eye me-1"></i> Lihat &amp; Unduh Berkas
                                        </a>
                                    </div>
                                    @if($task->designer_notes)
                                        <div class="small text-dark mt-2 p-2 bg-white rounded border">
                                            <strong>Catatan Editor:</strong> "{{ $task->designer_notes }}"
                                        </div>
                                    @endif
                                </div>

                                <!-- Review Deliverable Form -->
                                @if(!$task->admin_approved)
                                    <div class="alert alert-warning py-2 px-3 small rounded-3 mb-2 d-flex align-items-center gap-2">
                                        <i class="bi bi-info-circle-fill text-warning flex-shrink-0"></i>
                                        <div>Menunggu persetujuan Admin untuk diteruskan ke dashboard Penulis.</div>
                                    </div>

                                    <div class="d-flex gap-2 mt-2">
                                        <form action="{{ route('admin.tasks.review', $task) }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-success btn-sm w-100 fw-bold" onclick="return confirm('Setujui karya desain ini dan teruskan ke dashboard Penulis?')">
                                                <i class="bi bi-send-check-fill me-1"></i> Setujui &amp; Teruskan ke Penulis
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="collapse" data-bs-target="#revCollapse-{{ $task->id }}">
                                            Revisi
                                        </button>
                                    </div>

                                    <div class="collapse mt-2" id="revCollapse-{{ $task->id }}">
                                        <form action="{{ route('admin.tasks.review', $task) }}" method="POST" class="p-2 bg-light rounded-2">
                                            @csrf
                                            <input type="hidden" name="action" value="revision">
                                            <textarea name="admin_notes" rows="2" class="form-control form-control-sm mb-2" required placeholder="Catatan revisi untuk desainer..."></textarea>
                                            <button type="submit" class="btn btn-warning btn-sm w-100">Kirim Revisi ke Desainer</button>
                                        </form>
                                    </div>
                                @else
                                    <div class="p-2 bg-success-subtle text-success small rounded-2 fw-semibold mt-1 border border-success-subtle">
                                        <i class="bi bi-patch-check-fill me-1"></i> Telah Disetujui Admin &amp; Diteruskan ke Penulis pada {{ $task->approved_at?->format('d/m/Y H:i') }}
                                    </div>

                                    @if($task->author_approved)
                                        <div class="p-2 bg-success text-white small rounded-2 fw-bold mt-2 d-flex align-items-center justify-content-between shadow-sm">
                                            <span><i class="bi bi-check-all me-1"></i> Telah Disetujui Penulis</span>
                                            <span class="badge bg-white text-success small">{{ $task->author_approved_at?->format('d/m H:i') }}</span>
                                        </div>
                                    @elseif($task->author_approved === false && $task->author_notes)
                                        <div class="p-2 bg-danger-subtle text-danger small rounded-2 fw-semibold mt-2 border border-danger-subtle">
                                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Penulis Meminta Revisi:</strong> "{{ $task->author_notes }}"
                                        </div>
                                    @else
                                        <div class="p-2 bg-warning-subtle text-warning-emphasis small rounded-2 fw-semibold mt-2 border border-warning-subtle">
                                            <i class="bi bi-clock-history me-1"></i> Menunggu persetujuan / konfirmasi dari Penulis
                                        </div>
                                    @endif
                                @endif
                            @endif
                        @endif

                        <!-- Form Assign / Re-assign Desainer -->
                        <button class="btn btn-sm btn-link text-decoration-none p-0 mt-2 text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#assignForm-{{ $type }}">
                            <i class="bi bi-person-plus me-1"></i> {{ $task ? 'Tugaskan Ulang Desainer' : 'Tugaskan ke Desainer' }}
                        </button>

                        <div class="collapse mt-2" id="assignForm-{{ $type }}">
                            <form action="{{ route('admin.tasks.assign', $submission) }}" method="POST" class="p-2 bg-light rounded-2">
                                @csrf
                                <input type="hidden" name="task_type" value="{{ $type }}">
                                <div class="mb-2">
                                    <select name="designer_id" class="form-select form-select-sm" required>
                                        <option value="">-- Pilih Desainer --</option>
                                        @foreach($designers as $des)
                                            <option value="{{ $des->id }}" {{ $task?->designer_id == $des->id ? 'selected' : '' }}>
                                                {{ $des->name }} ({{ $des->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-brand btn-sm w-100">
                                    Tugaskan {{ $label }}
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- [TAHAP 3] Perintah Cetak & Status Percetakan -->
            <div class="card card-soft p-4 mb-4 shadow-sm border-{{ in_array($submission->status, ['ready_to_print', 'printing', 'waiting_final_payment', 'completed']) ? 'success' : 'secondary' }}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="fw-bold font-serif mb-0 text-success d-flex align-items-center gap-2">
                        <i class="bi bi-printer-fill"></i>
                        <span>[Tahap 3] Proses Percetakan Naskah</span>
                    </h5>
                    <span class="badge bg-{{ $submission->status_badge }} px-2 py-1 small fw-bold">{{ $submission->status_label }}</span>
                </div>
                <p class="text-muted small mb-3">Setelah naskah &amp; cover disetujui bersama oleh Admin dan Penulis, kirim perintah cetak ke tim Editor/Percetakan.</p>

                @if($submission->status === 'ready_to_print' || ($submission->isAllDesignApproved() && $submission->status === 'in_design'))
                    <div class="p-3 bg-success-subtle border border-success rounded-3 mb-2">
                        <div class="d-flex align-items-start gap-2 mb-2">
                            <i class="bi bi-check-circle-fill text-success fs-5 mt-0"></i>
                            <div>
                                <strong class="d-block text-success">Seluruh Desain Telah Disetujui!</strong>
                                <span class="small text-muted">Penulis &amp; Admin telah menyetujui layout dan cover. Naskah siap diproses ke mesin percetakan.</span>
                            </div>
                        </div>
                        <form action="{{ route('admin.submissions.printing', $submission) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm d-flex align-items-center justify-content-center gap-2" onclick="return confirm('Mulai proses percetakan naskah ini sekarang dan kirimkan ke antrean Editor/Percetakan?');">
                                <i class="bi bi-printer-fill fs-5"></i>
                                <span>Cetak Sekarang (Kirim ke Editor)</span>
                            </button>
                        </form>
                    </div>
                @elseif($submission->status === 'printing')
                    <div class="p-3 bg-info-subtle border border-info rounded-3 mb-2">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <div class="spinner-border spinner-border-sm text-info" role="status"></div>
                            <strong class="text-info">Sedang Dalam Proses Cetak Mesin</strong>
                        </div>
                        <p class="small text-muted mb-2">
                            Perintah cetak dikirim pada <strong>{{ $submission->printing_started_at?->format('d/m/Y H:i') ?? '-' }}</strong>. Tim Editor/Percetakan sedang memproses cetak sebanyak <strong>{{ $submission->quantity }} eksemplar</strong> ({{ ucfirst($submission->cover_type) }} Cover).
                        </p>
                        <div class="small text-muted fst-italic">
                            <i class="bi bi-clock-history me-1"></i> Menunggu Editor menekan tombol "Selesai Cetak" di dashboard editor untuk membuka tagihan Pelunasan ke Penulis.
                        </div>
                    </div>
                @elseif($submission->status === 'waiting_final_payment')
                    <div class="p-3 bg-warning-subtle border border-warning rounded-3 mb-2">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="bi bi-patch-check-fill text-success fs-5"></i>
                            <strong class="text-dark">Buku Telah Selesai Dicetak!</strong>
                        </div>
                        <p class="small text-muted mb-2">
                            Pencetakan selesai pada <strong>{{ $submission->printed_at?->format('d/m/Y H:i') }}</strong> oleh <strong>{{ $submission->printer?->name ?? 'Editor' }}</strong>.
                        </p>
                        <div class="alert alert-warning py-2 px-3 small rounded-2 mb-0">
                            <i class="bi bi-credit-card-2-front me-1"></i> Sistem telah membuka penagihan <strong>Pelunasan 50% (Rp {{ number_format($submission->final_amount, 0, ',', '.') }})</strong> kepada Penulis sebelum buku dikirimkan.
                        </div>
                    </div>
                @elseif($submission->status === 'completed')
                    <div class="p-3 bg-success-subtle border border-success-subtle rounded-3 text-success small mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-award-fill fs-4"></i>
                        <div>
                            <strong>Penerbitan &amp; Percetakan Selesai Sempurna!</strong>
                            <div class="text-muted" style="font-size: 11px;">Buku selesai diproduksi dan pelunasan telah diverifikasi pada {{ $submission->completed_at?->format('d/m/Y H:i') }}.</div>
                        </div>
                    </div>
                @else
                    <div class="p-3 bg-light border rounded-3 text-muted small">
                        <i class="bi bi-info-circle me-1"></i> Tombol <strong>Cetak Sekarang</strong> akan otomatis aktif setelah hasil pengerjaan layout dan cover disetujui bersama oleh Admin dan Penulis pada [Tinjauan 2] di atas.
                    </div>
                @endif
            </div>

            <!-- Financial & Payment Status -->
            <div class="card card-soft p-4">
                <h5 class="fw-bold font-serif mb-2">Status Pembayaran</h5>
                @if($submission->total_price)
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Total Biaya:</span>
                        <span class="fw-bold">Rp {{ number_format($submission->total_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">DP 50%:</span>
                        <span>Rp {{ number_format($submission->dp_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 small">
                        <span class="text-muted">Pelunasan 50%:</span>
                        <span>Rp {{ number_format($submission->final_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        @foreach($submission->payments as $pay)
                            <div class="p-2 border rounded-2 small d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">{{ $pay->type_label }}</span>
                                    <span class="badge bg-{{ $pay->status_badge }} ms-1">{{ $pay->status_label }}</span>
                                </div>
                                @if($pay->proof_file)
                                    <a href="{{ route('file.show', ['type' => 'payment_proofs_' . $pay->type, 'filename' => $pay->proof_file]) }}" target="_blank" class="btn btn-sm btn-outline-secondary py-0 px-2">
                                        Lihat Bukti
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <span class="text-muted small">Total biaya belum ditentukan oleh admin.</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function formatRupiah(num) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function updateLiveCalc() {
    const input = document.getElementById('input_total_price');
    const previewDp = document.getElementById('preview_dp');
    const previewFinal = document.getElementById('preview_final');
    if (!input || !previewDp || !previewFinal) return;
    
    const val = parseFloat(input.value) || 0;
    const dp = Math.round(val * 0.5);
    const finalAmount = val - dp;
    
    previewDp.textContent = formatRupiah(dp);
    previewFinal.textContent = formatRupiah(finalAmount);
}

document.addEventListener('DOMContentLoaded', function() {
    updateLiveCalc();
});
</script>
@endpush