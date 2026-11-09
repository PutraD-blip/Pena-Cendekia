@extends('layouts.author')

@section('title', 'Detail Naskah: ' . $submission->title)
@section('page-title', 'Detail & Progres Naskah')

@section('content')
<div class="container-fluid max-w-5xl px-0">
    <div class="mb-4">
        <a href="{{ route('author.dashboard') }}" class="btn btn-sm btn-outline-secondary mb-2" style="border-color: var(--author-border); color: var(--author-text-main);"><i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard</a>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="badge font-monospace mb-1" style="background-color: var(--author-card-surface, #F1F5F9); color: var(--author-gold, #D97706); border: 1.5px solid var(--author-border, #CBD5E1);">
                    {{ $submission->code }}
                </span>
                <h3 class="fw-bold mb-0" style="font-family: var(--font-heading); color: var(--author-text-main);">{{ $submission->title }}</h3>
            </div>
            <div>
                <span class="badge bg-{{ $submission->status_badge }} px-3 py-2 fs-6 rounded-pill">
                    {{ $submission->status_label }}
                </span>
            </div>
        </div>
    </div>

    <!-- Visual Stepper Progress -->
    @php
        $isEditing = $submission->service_type === 'editing';

        if ($isEditing) {
            $steps = [
                ['key' => 'pending_review', 'label' => 'Tinjauan Naskah', 'icon' => 'bi-search'],
                ['key' => 'waiting_payment', 'label' => 'DP 50%', 'icon' => 'bi-credit-card'],
                ['key' => 'in_design', 'label' => 'Proses Editing', 'icon' => 'bi-pencil-square'],
                ['key' => 'waiting_final_payment', 'label' => 'Pelunasan 50%', 'icon' => 'bi-cash-coin'],
                ['key' => 'completed', 'label' => 'Selesai', 'icon' => 'bi-check2-all'],
            ];

            $statusWeights = [
                'pending_review' => 1,
                'revision_required' => 1,
                'waiting_payment' => 2,
                'payment_verification' => ($submission->dpPayment?->status === 'verified' ? 4 : 2),
                'in_design' => 3,
                'waiting_final_payment' => 4,
                'completed' => 5,
                'cancelled' => 0,
            ];
        } else {
            $steps = [
                ['key' => 'pending_review', 'label' => 'Tinjauan Naskah', 'icon' => 'bi-search'],
                ['key' => 'waiting_payment', 'label' => 'DP 50%', 'icon' => 'bi-credit-card'],
                ['key' => 'in_design', 'label' => 'Proses Desain', 'icon' => 'bi-palette'],
                ['key' => 'printing', 'label' => 'Pencetakan', 'icon' => 'bi-printer'],
                ['key' => 'waiting_final_payment', 'label' => 'Pelunasan 50%', 'icon' => 'bi-cash-coin'],
                ['key' => 'completed', 'label' => 'Selesai', 'icon' => 'bi-check2-all'],
            ];

            $statusWeights = [
                'pending_review' => 1,
                'revision_required' => 1,
                'waiting_payment' => 2,
                'payment_verification' => ($submission->dpPayment?->status === 'verified' ? 5 : 2),
                'in_design' => 3,
                'ready_to_print' => 3,
                'printing' => 4,
                'waiting_final_payment' => 5,
                'completed' => 6,
                'cancelled' => 0,
            ];
        }

        $currentWeight = $statusWeights[$submission->status] ?? 1;
    @endphp

    <div class="dash-card p-4 mb-4">
        <h6 class="fw-bold text-muted small text-uppercase mb-3" style="letter-spacing: 0.5px;">{{ $isEditing ? 'Alur Tahapan Layanan Editing' : 'Alur Tahapan Penerbitan & Cetak' }}</h6>
        <div class="row text-center g-2">
            @foreach($steps as $index => $step)
                @php
                    $stepNum = $index + 1;
                    if ($submission->status === 'completed') {
                        $isActive = false;
                        $isPassed = true;
                    } else {
                        $isActive = $stepNum === $currentWeight;
                        $isPassed = $stepNum < $currentWeight;
                    }
                @endphp
                <div class="col">
                    <div class="p-2 rounded-3" style="background-color: {{ $isActive ? 'rgba(245, 166, 35, 0.15)' : ($isPassed ? 'rgba(16, 185, 129, 0.12)' : 'var(--author-card-surface, #F8FAFC)') }}; border: 1.5px solid {{ $isActive ? 'var(--author-gold, #D97706)' : ($isPassed ? '#10B981' : 'var(--author-border, #CBD5E1)') }};">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-1" style="width: 32px; height: 32px; background-color: {{ $isActive ? 'var(--author-gold, #D97706)' : ($isPassed ? '#10B981' : 'var(--author-card-bg, #FFFFFF)') }}; color: {{ $isActive ? '#111827' : ($isPassed ? '#ffffff' : 'var(--author-text-sub, #475569)') }}; border: 1.5px solid {{ $isActive ? 'var(--author-gold, #D97706)' : ($isPassed ? '#10B981' : 'var(--author-border, #CBD5E1)') }}; font-size: 13px;">
                            <i class="bi {{ $isPassed ? 'bi-check-lg' : $step['icon'] }}"></i>
                        </div>
                        <div class="fw-bold small d-block {{ $isActive ? 'text-warning' : ($isPassed ? 'text-success' : '') }}" style="font-size: 11px; color: {{ !$isActive && !$isPassed ? 'var(--author-text-sub, #475569)' : '' }};">
                            {{ $step['label'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Alert for Revision -->
    @if($submission->status === 'revision_required')
        <div class="alert alert-danger border-0 rounded-4 shadow-sm p-4 mb-4" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(20, 28, 48, 0.95) 100%); border: 1px solid rgba(239, 68, 68, 0.4) !important;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex gap-3">
                    <i class="bi bi-exclamation-octagon-fill fs-2 text-danger"></i>
                    <div>
                        <h5 class="fw-bold mb-1 text-white">Naskah Memerlukan Revisi</h5>
                        <p class="mb-0 small text-muted"><strong>Catatan dari Admin:</strong> {{ $submission->admin_notes }}</p>
                    </div>
                </div>
                <a href="{{ route('author.submissions.edit', $submission) }}" class="btn btn-danger text-nowrap px-4 rounded-pill">
                    <i class="bi bi-pencil-square me-1"></i> Perbaiki & Kirim Ulang
                </a>
            </div>
        </div>
    @endif

    <!-- Alert for DP Payment Required -->
    @if($submission->status === 'waiting_payment')
        <div class="alert alert-warning border-0 rounded-4 shadow-sm p-4 mb-4" style="background: linear-gradient(135deg, rgba(245, 166, 35, 0.15) 0%, rgba(20, 28, 48, 0.95) 100%); border: 1px solid rgba(245, 166, 35, 0.4) !important;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex gap-3">
                    <i class="bi bi-wallet2 fs-2 text-warning"></i>
                    <div>
                        <h5 class="fw-bold mb-1 text-white">Menunggu Pembayaran: Down Payment (DP 50%)</h5>
                        <p class="mb-1 text-muted small">
                            Silakan lakukan transfer manual sejumlah <strong class="text-warning">Rp {{ number_format($submission->dp_amount, 0, ',', '.') }}</strong> ke rekening resmi PT. Pena Cendikia untuk memulai proses tata letak dan desain cover.
                        </p>
                    </div>
                </div>
                <a href="{{ route('author.payments.show', $submission) }}" class="btn btn-gold text-nowrap px-4 rounded-pill">
                    <i class="bi bi-upload me-1"></i> Unggah Bukti Transfer DP
                </a>
            </div>
        </div>
    @endif

    <!-- Alert for Ready to Print (Menunggu Perintah Cetak Admin) -->
    @if($submission->status === 'ready_to_print')
        <div class="alert border-0 rounded-4 shadow-sm p-4 mb-4" style="background: var(--author-card-bg, #FFFFFF); border: 2px solid #10B981 !important;">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-patch-check-fill fs-2 text-success"></i>
                <div>
                    <h5 class="fw-bold mb-1" style="color: var(--author-text-main, #0F172A);">Desain Selesai &amp; Disetujui!</h5>
                    <p class="mb-0 text-muted small">
                        Anda dan Admin telah menyetujui seluruh hasil desain layout dan cover. Naskah Anda saat ini sedang disiapkan dalam antrean mesin percetakan oleh Admin Redaksi.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Alert for Printing in Progress -->
    @if($submission->status === 'printing')
        <div class="alert border-0 rounded-4 shadow-sm p-4 mb-4" style="background: var(--author-card-bg, #FFFFFF); border: 2px solid #0284C7 !important;">
            <div class="d-flex align-items-center gap-3">
                <div class="spinner-border text-info flex-shrink-0" style="width: 2.2rem; height: 2.2rem;" role="status"></div>
                <div>
                    <h5 class="fw-bold mb-1" style="color: var(--author-text-main, #0F172A);">Buku Fisik Anda Sedang Dalam Proses Cetak Mesin</h5>
                    <p class="mb-0 text-muted small">
                        Tim percetakan PT. Pena Cendekia saat ini sedang memproses cetak buku fisik Anda (<strong>{{ $submission->quantity }} eksemplar</strong>, Cover {{ ucfirst($submission->cover_type) }}). Setelah percetakan selesai, Anda akan menerima pemberitahuan untuk melakukan pelunasan sisa 50%.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Alert for Final Payment Required -->
    @if($submission->status === 'waiting_final_payment')
        <div class="alert border-0 rounded-4 shadow-sm p-4 mb-4" style="background: var(--author-card-bg, #FFFFFF); border: 2px solid #F5A623 !important;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex gap-3">
                    <i class="bi bi-award-fill fs-2 text-warning"></i>
                    <div>
                        @if($submission->service_type === 'editing')
                            <h5 class="fw-bold mb-1" style="color: var(--author-text-main, #0F172A);">Hasil Editing Selesai &amp; Disetujui! Menunggu Pelunasan (50%)</h5>
                            <p class="mb-1 text-muted small">
                                Alhamdulillah! Seluruh proses penyuntingan dan tata letak naskah Anda telah selesai dan disetujui. Silakan lakukan transfer pelunasan sebesar <strong class="text-warning">Rp {{ number_format($submission->final_amount, 0, ',', '.') }}</strong> untuk serah terima berkas master final.
                            </p>
                        @else
                            <h5 class="fw-bold mb-1" style="color: var(--author-text-main, #0F172A);">Buku Telah Selesai Dicetak! Menunggu Pelunasan (50%)</h5>
                            <p class="mb-1 text-muted small">
                                Alhamdulillah! Seluruh <strong>{{ $submission->quantity }} eksemplar</strong> buku fisik Anda telah selesai diproduksi oleh tim percetakan. Silakan lakukan transfer pelunasan sebesar <strong class="text-warning">Rp {{ number_format($submission->final_amount, 0, ',', '.') }}</strong> agar pesanan buku Anda dapat segera dikirimkan.
                            </p>
                        @endif
                    </div>
                </div>
                <a href="{{ route('author.payments.show', $submission) }}" class="btn btn-gold text-nowrap px-4 rounded-pill">
                    <i class="bi bi-wallet2 me-1"></i> Bayar Pelunasan Sekarang
                </a>
            </div>
        </div>
    @endif

    <!-- Payment Verification Pending -->
    @if($submission->status === 'payment_verification')
        <div class="alert border-0 rounded-4 shadow-sm p-4 mb-4" style="background: var(--author-card-bg, #FFFFFF); border: 2px solid #0284C7 !important;">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-clock-history fs-2 text-info"></i>
                <div>
                    <h5 class="fw-bold mb-1" style="color: var(--author-text-main, #0F172A);">Bukti Pembayaran Sedang Diverifikasi Admin</h5>
                    <p class="mb-0 text-muted small">Anda telah mengunggah bukti transfer. Admin PT. Pena Cendikia akan segera memverifikasi rekening koran dalam waktu 1x24 jam kerja.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left Column: Book Details -->
        <div class="col-lg-7">
            <div class="dash-card p-4 mb-4">
                <h5 class="fw-bold mb-3 text-white" style="font-family: var(--font-heading);">Informasi Naskah & Spesifikasi</h5>
                
                <table class="table table-borderless small mb-0 text-white">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width: 35%;">Jenis Layanan</td>
                            <td>
                                <span class="badge rounded-pill" style="background: rgba(245, 166, 35, 0.15); color: #F5A623; font-weight: 700;">
                                    <i class="bi {{ $submission->service_icon ?? 'bi-book' }} me-1"></i>
                                    {{ $submission->service_type_label ?? 'Penerbitan Buku' }}
                                </span>
                            </td>
                        </tr>
                        @if($submission->package_name)
                            <tr>
                                <td class="text-muted">Pilihan Paket</td>
                                <td>
                                    <span class="badge bg-warning-subtle text-warning-emphasis fw-bold px-3 py-1 rounded-pill">
                                        <i class="bi bi-box-seam-fill me-1"></i>{{ $submission->package_name }}
                                    </span>
                                    @if($submission->package_price)
                                        <span class="text-warning font-monospace fw-bold ms-2">Rp {{ number_format($submission->package_price, 0, ',', '.') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-muted" style="width: 35%;">Judul Buku</td>
                            <td class="fw-bold">{{ $submission->title }}</td>
                        </tr>
                        @if($submission->pen_name)
                            <tr>
                                <td class="text-muted">Nama Pena</td>
                                <td>{{ $submission->pen_name }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="text-muted">Editor Naskah</td>
                            <td>{{ $submission->editor_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kategori / Genre</td>
                            <td><span class="badge" style="background-color: rgba(255, 255, 255, 0.08); color: var(--author-text-sub);">{{ $submission->category->name }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Target Pembaca</td>
                            <td>{{ ucfirst($submission->target_reader) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estimasi Halaman</td>
                            <td>{{ $submission->estimated_pages }} Halaman (A5)</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jumlah Cetak</td>
                            <td>{{ $submission->quantity }} Eksemplar</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Jenis Cover</td>
                            <td><span class="badge" style="background-color: rgba(255, 255, 255, 0.08); color: var(--author-gold);">{{ ucfirst($submission->cover_type) }} Cover</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Diajukan</td>
                            <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d M Y, H:i') : '-' }}</td>
                        </tr>
                    </tbody>
                </table>

                <hr class="my-3" style="border-color: var(--author-border);">

                <h6 class="fw-bold small text-muted text-uppercase mb-2">Sinopsis Buku</h6>
                <div class="p-3 rounded-3 small text-muted" style="background-color: rgba(255, 255, 255, 0.04); border: 1px solid var(--author-border);">
                    {!! nl2br(e($submission->synopsis)) !!}
                </div>

                @if($submission->notes)
                    <h6 class="fw-bold small text-muted text-uppercase mt-3 mb-2">Catatan Tambahan</h6>
                    <div class="p-3 rounded-3 small text-muted" style="background-color: rgba(255, 255, 255, 0.04); border: 1px solid var(--author-border);">
                        {{ $submission->notes }}
                    </div>
                @endif
            </div>

            <!-- Uploaded Files Section -->
            <div class="dash-card p-4">
                <h5 class="fw-bold mb-3 text-white" style="font-family: var(--font-heading);">Berkas yang Anda Unggah</h5>
                
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid var(--author-border);">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-file-earmark-text fs-4 text-warning"></i>
                            <div>
                                <span class="d-block text-white fw-semibold small">Berkas Naskah Utama</span>
                                <small class="text-muted">{{ $submission->manuscript_file }}</small>
                            </div>
                        </div>
                        <a href="{{ route('file.show', ['type' => 'manuscripts', 'filename' => $submission->manuscript_file]) }}" target="_blank" class="btn btn-sm btn-outline-light">
                            <i class="bi bi-download me-1"></i> Unduh
                        </a>
                    </div>

                    @if($submission->layout_file)
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid var(--author-border);">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-layout-text-window-reverse fs-4 text-info"></i>
                                <div>
                                    <span class="d-block text-white fw-semibold small">Berkas Layout / Lampiran</span>
                                    <small class="text-muted">{{ $submission->layout_file }}</small>
                                </div>
                            </div>
                            <a href="{{ route('file.show', ['type' => 'layouts_from_author', 'filename' => $submission->layout_file]) }}" target="_blank" class="btn btn-sm btn-outline-light">
                                <i class="bi bi-download me-1"></i> Unduh
                            </a>
                        </div>
                    @endif

                    @if($submission->originality_statement_file)
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid var(--author-border);">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-shield-check fs-4 text-success"></i>
                                <div>
                                    <span class="d-block text-white fw-semibold small">Surat Pernyataan Keaslian Karya</span>
                                    <small class="text-muted">{{ $submission->originality_statement_file }}</small>
                                </div>
                            </div>
                            <a href="{{ route('file.show', ['type' => 'statements', 'filename' => $submission->originality_statement_file]) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                <i class="bi bi-download me-1"></i> Unduh
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Financial & Design Progress -->
        <div class="col-lg-5">
            <!-- Financial Card -->
            <div class="dash-card p-4 mb-4">
                <h5 class="fw-bold mb-3 text-white" style="font-family: var(--font-heading);">Rincian Biaya & Pembayaran</h5>

                @if($submission->total_price)
                    <div class="p-3 rounded-3 mb-3" style="background-color: rgba(255, 255, 255, 0.05); border: 1px solid var(--author-border);">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total Biaya Cetak:</span>
                            <span class="fw-bold fs-5 text-warning">Rp {{ number_format($submission->total_price, 0, ',', '.') }}</span>
                        </div>
                        <hr class="my-2" style="border-color: var(--author-border);">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">DP 50%:</span>
                            <span class="fw-semibold text-white">Rp {{ number_format($submission->dp_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Pelunasan 50%:</span>
                            <span class="fw-semibold text-white">Rp {{ number_format($submission->final_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Payment Records -->
                    <h6 class="fw-bold small text-muted text-uppercase mb-2">Riwayat Pembayaran</h6>
                    <div class="d-flex flex-column gap-2">
                        @foreach(['dp' => 'DP 50%', 'final' => 'Pelunasan 50%'] as $type => $label)
                            @php
                                $pay = $submission->payments->where('type', $type)->first();
                            @endphp
                            <div class="p-3 rounded-3" style="background-color: rgba(255, 255, 255, 0.04); border: 1px solid var(--author-border);">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold small text-white">{{ $label }}</span>
                                    @if($pay)
                                        <span class="badge bg-{{ $pay->status_badge }} rounded-pill">{{ $pay->status_label }}</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">Belum Dibayar</span>
                                    @endif
                                </div>
                                @if($pay)
                                    <div class="small text-muted mb-2">
                                        Rekening: {{ $pay->account->bank_name ?? '-' }} &bull; Rp {{ number_format($pay->amount_due, 0, ',', '.') }}
                                    </div>
                                    <div class="d-flex flex-column gap-1.5">
                                        @if($pay->proof_file)
                                            <a href="{{ route('file.show', ['type' => 'payment_proofs_' . $type, 'filename' => $pay->proof_file]) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-receipt me-1"></i> Lihat Bukti Transfer
                                            </a>
                                        @endif
                                        @if($pay->status === 'rejected')
                                            <a href="{{ route('author.payments.show', $submission) }}" class="btn btn-sm btn-danger fw-bold mt-1">
                                                <i class="bi bi-arrow-repeat me-1"></i> Unggah Ulang Bukti (Ditolak)
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    @if($type === 'dp' && ($submission->status === 'waiting_payment' || !$submission->isDpVerified()))
                                        <a href="{{ route('author.payments.show', $submission) }}" class="btn btn-sm btn-gold mt-2 w-100 fw-bold">
                                            <i class="bi bi-wallet2 me-1"></i> Bayar DP 50% Sekarang
                                        </a>
                                    @elseif($type === 'final' && ($submission->status === 'waiting_final_payment' || ($submission->isDpVerified() && !$submission->isFinalVerified())))
                                        <a href="{{ route('author.payments.show', $submission) }}" class="btn btn-sm btn-gold mt-2 w-100 fw-bold">
                                            <i class="bi bi-wallet2 me-1"></i> Bayar Pelunasan 50% Sekarang
                                        </a>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 rounded-3 text-center text-muted" style="background-color: rgba(255, 255, 255, 0.03); border: 1px solid var(--author-border);">
                        <i class="bi bi-calculator fs-2 d-block mb-2 text-warning"></i>
                        <span class="small d-block">Admin sedang menghitung dan menentukan total biaya cetak berdasarkan estimasi halaman dan eksemplar.</span>
                    </div>
                @endif
            </div>

            <!-- Design Deliverables Card -->
            <div class="dash-card p-4">
                <h5 class="fw-bold mb-3 text-white" style="font-family: var(--font-heading);">Proses Tim Desain</h5>

                @if($submission->designTasks->isEmpty())
                    <div class="p-3 rounded-3 text-center text-muted small" style="background-color: rgba(255, 255, 255, 0.03); border: 1px solid var(--author-border);">
                        <i class="bi bi-palette fs-2 d-block mb-1 text-warning"></i>
                        Naskah akan di-assign ke Tim Layout & Cover setelah pembayaran DP 50% diverifikasi Admin.
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($submission->designTasks as $task)
                            <div id="taskCard-{{ $task->id }}" class="p-3 rounded-3" style="background-color: rgba(255, 255, 255, 0.04); border: 1px solid var(--author-border); scroll-margin-top: 90px;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold small text-white">{{ $task->task_type_label }}</span>
                                    <span class="badge bg-{{ $task->status_badge }} rounded-pill">{{ $task->status_label }}</span>
                                </div>
                                <div class="small text-muted mb-2">
                                    Desainer: <strong class="text-white">{{ $task->designer->name }}</strong>
                                </div>

                                @if($task->result_file && $task->admin_approved)
                                    <div class="p-2 bg-success-subtle rounded-3 small text-success mb-2 border border-success-subtle">
                                        <i class="bi bi-patch-check-fill me-1"></i> Desain telah diverifikasi &amp; disetujui Admin Redaksi
                                    </div>
                                    @if($task->designer_notes)
                                        <div class="small text-muted mb-2 fst-italic p-2 rounded-2" style="background: rgba(255,255,255,0.03); border-left: 2px solid #F5A623;">
                                            <strong class="text-white">Catatan Editor:</strong> "{{ $task->designer_notes }}"
                                        </div>
                                    @endif
                                    @if($submission->status === 'completed')
                                        <a href="{{ route('file.show', ['type' => $task->task_type === 'layout' ? 'design_layouts' : 'design_covers', 'filename' => $task->result_file]) }}" target="_blank" class="btn btn-sm btn-gold w-100 fw-bold">
                                            <i class="bi bi-download me-1"></i> Unduh File Master {{ $task->task_type_label }}
                                        </a>
                                    @else
                                        <!-- Tombol Buka Pratinjau Inline -->
                                        <button type="button" class="btn btn-sm btn-gold w-100 fw-bold d-flex align-items-center justify-content-center gap-2 mb-2 shadow-sm py-2" onclick="openInlineViewer('{{ $task->task_type_label }}', '{{ route('file.show', ['type' => $task->task_type === 'layout' ? 'design_layouts' : 'design_covers', 'filename' => $task->result_file]) }}', '{{ strtolower(pathinfo($task->result_file, PATHINFO_EXTENSION)) }}', '{{ $task->id }}')">
                                            <i class="bi bi-eye-fill"></i> Pratinjau Hasil {{ $task->task_type_label }} (Halaman Penuh)
                                        </button>

                                        @if($task->author_approved)
                                            <div class="p-2 bg-success-subtle rounded-3 small text-success border border-success-subtle d-flex align-items-center justify-content-between mb-2">
                                                <div>
                                                    <i class="bi bi-check-circle-fill me-1"></i> <strong>Desain Telah Anda Setujui</strong>
                                                    @if($task->author_approved_at)
                                                        <span class="text-muted d-block" style="font-size: 10.5px;">Disetujui pada {{ $task->author_approved_at->translatedFormat('d M Y, H:i') }}</span>
                                                    @endif
                                                </div>
                                                <span class="badge bg-success rounded-pill px-2 py-1">Disetujui Penulis</span>
                                            </div>
                                        @elseif($task->author_approved === false && $task->author_notes)
                                            <div class="p-2 bg-warning-subtle rounded-3 small text-warning-emphasis border border-warning-subtle mb-2">
                                                <div class="fw-bold mb-1"><i class="bi bi-pencil-square text-warning me-1"></i> Catatan Revisi Anda:</div>
                                                <div class="fst-italic text-break">"{{ $task->author_notes }}"</div>
                                            </div>
                                            <div class="d-flex gap-2 mb-2">
                                                <button type="button" class="btn btn-sm btn-success flex-grow-1 fw-bold d-flex align-items-center justify-content-center gap-1 shadow-sm" data-bs-toggle="collapse" data-bs-target="#confirmApprove-{{ $task->id }}" aria-expanded="false">
                                                    <i class="bi bi-check2-circle fs-6"></i> Setujui Desain
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-toggle="collapse" data-bs-target="#collapseRevision-{{ $task->id }}" aria-expanded="false">
                                                    Ubah Catatan
                                                </button>
                                            </div>
                                        @else
                                            <!-- Tombol Setujui Desain & Ajukan Catatan Revisi (Inline Accordion/Collapse) -->
                                            <div class="d-flex gap-2 mb-2">
                                                <button type="button" class="btn btn-sm btn-success flex-grow-1 fw-bold d-flex align-items-center justify-content-center gap-1 shadow-sm py-2" data-bs-toggle="collapse" data-bs-target="#confirmApprove-{{ $task->id }}" aria-expanded="false">
                                                    <i class="bi bi-check2-circle fs-6"></i> Setujui Desain
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary px-3 d-flex align-items-center gap-1" data-bs-toggle="collapse" data-bs-target="#collapseRevision-{{ $task->id }}" aria-expanded="false" title="Minta perbaikan jika ada kesalahan">
                                                    <i class="bi bi-pencil-square"></i> Revisi
                                                </button>
                                            </div>
                                        @endif

                                        <!-- Panel Konfirmasi Persetujuan Inline (Tanpa Browser Confirm / Alert) -->
                                        <div class="collapse mb-2" id="confirmApprove-{{ $task->id }}">
                                            <div class="p-3 rounded-3" style="background: rgba(16, 185, 129, 0.08); border: 1.5px solid rgba(16, 185, 129, 0.35);">
                                                <div class="d-flex align-items-start gap-2 mb-2">
                                                    <i class="bi bi-question-circle-fill text-success fs-5 mt-0 flex-shrink-0"></i>
                                                    <div>
                                                        <strong class="d-block small" style="color: var(--author-text-main, #0F172A);">Konfirmasi Persetujuan {{ $task->task_type_label }}</strong>
                                                        <span class="text-muted" style="font-size: 12px;">Pastikan Anda sudah meninjau dokumen ini. Dengan menyetujui, hasil pengerjaan akan dinyatakan final dan dilanjutkan ke {{ $submission->service_type === 'editing' ? 'tahap pelunasan dan serah terima file naskah final' : 'proses percetakan fisik buku' }}.</span>
                                                    </div>
                                                </div>
                                                <form action="{{ route('author.tasks.approve', $task) }}" method="POST" class="d-flex gap-2 justify-content-end mt-2">
                                                    @csrf
                                                    <button type="button" class="btn btn-xs btn-outline-secondary px-3 py-1.5 rounded-2" data-bs-toggle="collapse" data-bs-target="#confirmApprove-{{ $task->id }}" style="font-size: 12px;">
                                                        Batal
                                                    </button>
                                                    <button type="submit" class="btn btn-xs btn-success fw-bold px-3 py-1.5 rounded-2" style="font-size: 12px;">
                                                        <i class="bi bi-check2-circle me-1"></i> Ya, Setujui Sekarang
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Panel Catatan Revisi Inline (Tanpa Floating Modal) -->
                                        <div class="collapse mb-2" id="collapseRevision-{{ $task->id }}">
                                            <div class="p-3 rounded-3" style="background: rgba(245, 166, 35, 0.08); border: 1.5px solid rgba(245, 166, 35, 0.35);">
                                                <div class="d-flex align-items-center justify-content-between mb-2 pb-2" style="border-bottom: 1px solid rgba(245, 166, 35, 0.2);">
                                                    <h6 class="fw-bold mb-0 small" style="color: var(--author-text-main, #0F172A);">
                                                        <i class="bi bi-pencil-square text-warning me-1"></i> Catatan Revisi {{ $task->task_type_label }}
                                                    </h6>
                                                    <button type="button" class="btn-close small" data-bs-toggle="collapse" data-bs-target="#collapseRevision-{{ $task->id }}" aria-label="Tutup"></button>
                                                </div>
                                                <form action="{{ route('author.tasks.revision', $task) }}" method="POST">
                                                    @csrf
                                                    <p class="text-muted mb-2" style="font-size: 12px;">
                                                        Tuliskan bagian atau nomor halaman dari <strong>{{ $task->task_type_label }}</strong> yang perlu diperbaiki:
                                                    </p>
                                                    <div class="mb-2">
                                                        <textarea name="notes" rows="3" class="form-control form-control-sm" placeholder="Contoh: Pada halaman 14 terdapat salah ketik judul bab..." required minlength="5" style="border-color: rgba(245, 166, 35, 0.4);">{{ old('notes', $task->author_notes) }}</textarea>
                                                    </div>
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <button type="button" class="btn btn-xs btn-outline-secondary px-3 py-1.5 rounded-2" data-bs-toggle="collapse" data-bs-target="#collapseRevision-{{ $task->id }}" style="font-size: 12px;">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="btn btn-xs btn-gold fw-bold px-3 py-1.5 rounded-2" style="font-size: 12px;">
                                                            <i class="bi bi-send me-1"></i> Kirim Catatan Revisi
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="mt-1 text-center text-muted" style="font-size: 11px;">
                                            <i class="bi bi-shield-lock text-warning me-1"></i> Mode Pratinjau Terproteksi. File master asli resolusi cetak dibuka setelah pelunasan selesai.
                                        </div>
                                    @endif
                                @elseif($task->result_file)
                                    <div class="p-2 bg-info-subtle rounded-3 small text-info mb-1 border border-info-subtle">
                                        <i class="bi bi-clock-history me-1"></i> Editor telah mengunggah hasil. Sedang ditinjau oleh Admin Redaksi sebelum diteruskan kepada Anda.
                                    </div>
                                    @if($task->designer_notes)
                                        <div class="small text-muted mb-2 fst-italic p-2 rounded-2" style="background: rgba(255,255,255,0.03); border-left: 2px solid rgba(13, 202, 240, 0.4);">
                                            <strong class="text-white">Catatan Editor:</strong> "{{ $task->designer_notes }}"
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- SECTION PRATINJAU DOKUMEN INLINE (Menyatu dalam Halaman, Continuous Vertical Scroll Tanpa Modal) -->
<div id="inlineViewerSection" class="mt-4" style="display: none; scroll-margin-top: 80px;">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background-color: var(--author-card-bg); border: 1.5px solid var(--author-border) !important;">
        
        <!-- Header Bar -->
        <div class="py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: var(--author-card-surface, #1E293B); border-bottom: 1.5px solid var(--author-border) !important;">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning text-dark px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                    <i class="bi bi-shield-lock-fill me-1"></i> Pratinjau Terproteksi
                </span>
                <h5 class="fw-bold mb-0 text-white fs-6" id="inlineViewerTitle">Pratinjau Hasil Desain</h5>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 rounded-pill px-3 py-1.5" onclick="closeInlineViewer()">
                    <i class="bi bi-x-lg"></i> <span>Tutup Pratinjau</span>
                </button>
            </div>
        </div>

        <!-- Protective Information Banner -->
        <div class="py-2.5 px-4 d-flex align-items-center justify-content-between small flex-wrap gap-2" style="background: rgba(245, 166, 35, 0.08); border-bottom: 1px solid rgba(245, 166, 35, 0.2);">
            <div class="d-flex align-items-center gap-2 text-warning fw-semibold" style="font-size: 12px;">
                <i class="bi bi-info-circle-fill"></i>
                <span>Tampilan naskah continuous-scroll vertikal (seperti Microsoft Word). Scroll ke bawah untuk memeriksa seluruh halaman.</span>
            </div>
            <span class="badge bg-secondary-subtle text-secondary small px-2 py-1">View Only (Anti-Download)</span>
        </div>

        <!-- Zoom & Pages Controls Bar -->
        <div id="inlinePdfControls" class="py-2.5 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: rgba(0,0,0,0.15); border-bottom: 1px solid var(--author-border); display: none;">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-pill" style="font-size: 11px;">
                    <i class="bi bi-files me-1"></i> <span id="pdfTotalPages">0</span> Halaman Total
                </span>
                <small class="text-muted d-none d-md-inline" style="font-size: 12px;">Gunakan mouse wheel atau scroll layar untuk membaca halaman berikutnya</small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary px-2.5 py-1" onclick="zoomContinuousPdf(-0.15)" title="Perkecil Zoom">
                    <i class="bi bi-zoom-out"></i>
                </button>
                <span class="small fw-bold text-white px-2" id="zoomPercentage" style="min-width: 50px; text-align: center;">100%</span>
                <button type="button" class="btn btn-sm btn-outline-secondary px-2.5 py-1" onclick="zoomContinuousPdf(0.15)" title="Perbesar Zoom">
                    <i class="bi bi-zoom-in"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary px-2.5 py-1" onclick="resetContinuousZoom()" title="Ukuran Normal">
                    <i class="bi bi-aspect-ratio me-1"></i> Normal
                </button>
            </div>
        </div>

        <!-- Document Continuous Scroll Viewport -->
        <div class="p-0 position-relative" style="min-height: 580px; max-height: 82vh; overflow-y: auto; background-color: #1e293b;" id="inlineViewerWrapper" oncontextmenu="return false;">
            <div id="inlineMediaContainer" class="py-4 px-2 d-flex flex-column align-items-center" style="user-select: none;">
                <!-- Canvas pages dynamically appended here -->
            </div>
        </div>

        <!-- Footer Bar with Quick Action -->
        <div class="card-footer py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: var(--author-card-surface, #1E293B); border-top: 1.5px solid var(--author-border) !important;">
            <small class="text-muted">
                <i class="bi bi-shield-check text-success me-1"></i> Jika hasil sudah sesuai, Anda dapat menyetujui desain di kartu tugas di atas.
            </small>
            <button type="button" class="btn btn-sm btn-secondary px-4 rounded-pill" onclick="closeInlineViewer()">
                <i class="bi bi-arrow-up-circle me-1"></i> Selesai &amp; Kembali ke Kartu Tugas
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/pdfjs/pdf.min.js') }}"></script>
<script>
    if (typeof pdfjsLib !== 'undefined') {
        pdfjsLib.GlobalWorkerOptions.workerSrc = "{{ asset('js/pdfjs/pdf.worker.min.js') }}";
    }

    let loadedPdfDoc = null;
    let continuousScale = 1.15;
    let isRenderingDoc = false;
    let currentActiveTaskId = null;

    async function renderAllPages(pdfDoc, scale) {
        const container = document.getElementById('inlineMediaContainer');
        container.innerHTML = '';
        isRenderingDoc = true;

        document.getElementById('pdfTotalPages').textContent = pdfDoc.numPages;
        document.getElementById('zoomPercentage').textContent = Math.round((scale / 1.15) * 100) + '%';

        for (let num = 1; num <= pdfDoc.numPages; num++) {
            const pageWrapper = document.createElement('div');
            pageWrapper.className = 'pdf-page-card shadow mb-4 mx-auto position-relative text-center';
            pageWrapper.style.backgroundColor = '#FFFFFF';
            pageWrapper.style.borderRadius = '3px';
            pageWrapper.style.maxWidth = 'fit-content';
            pageWrapper.style.boxShadow = '0 6px 20px rgba(0,0,0,0.35)';

            const canvas = document.createElement('canvas');
            canvas.id = 'pdf-canvas-' + num;
            canvas.className = 'd-block mx-auto';
            canvas.addEventListener('contextmenu', function(e) { e.preventDefault(); return false; });
            canvas.addEventListener('selectstart', function(e) { e.preventDefault(); return false; });
            canvas.addEventListener('dragstart', function(e) { e.preventDefault(); return false; });

            // Transparent protective shield over each page
            const shield = document.createElement('div');
            shield.style.position = 'absolute';
            shield.style.inset = '0';
            shield.style.zIndex = '5';
            shield.style.cursor = 'default';
            shield.addEventListener('contextmenu', function(e) { e.preventDefault(); return false; });
            shield.addEventListener('selectstart', function(e) { e.preventDefault(); return false; });
            shield.addEventListener('dragstart', function(e) { e.preventDefault(); return false; });

            // Page number badge at bottom right
            const pageBadge = document.createElement('div');
            pageBadge.className = 'position-absolute bottom-0 end-0 m-2 badge bg-dark bg-opacity-75 text-white small';
            pageBadge.style.zIndex = '6';
            pageBadge.style.fontSize = '10px';
            pageBadge.textContent = 'Hal. ' + num + ' / ' + pdfDoc.numPages;

            pageWrapper.appendChild(canvas);
            pageWrapper.appendChild(shield);
            pageWrapper.appendChild(pageBadge);
            container.appendChild(pageWrapper);

            // Fetch and render single page
            try {
                const page = await pdfDoc.getPage(num);
                const viewport = page.getViewport({ scale: scale });
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                const renderContext = {
                    canvasContext: canvas.getContext('2d'),
                    viewport: viewport
                };
                await page.render(renderContext).promise;
            } catch (err) {
                console.error('Error rendering page ' + num, err);
            }
        }
        isRenderingDoc = false;
    }

    function zoomContinuousPdf(delta) {
        if (isRenderingDoc || !loadedPdfDoc) return;
        const newScale = continuousScale + delta;
        if (newScale >= 0.6 && newScale <= 2.2) {
            continuousScale = newScale;
            renderAllPages(loadedPdfDoc, continuousScale);
        }
    }

    function resetContinuousZoom() {
        if (isRenderingDoc || !loadedPdfDoc) return;
        continuousScale = 1.15;
        renderAllPages(loadedPdfDoc, continuousScale);
    }

    function openInlineViewer(title, fileUrl, ext, taskId) {
        currentActiveTaskId = taskId;
        const section = document.getElementById('inlineViewerSection');
        const container = document.getElementById('inlineMediaContainer');
        const controlsBar = document.getElementById('inlinePdfControls');
        
        document.getElementById('inlineViewerTitle').innerText = 'Pratinjau Hasil ' + title;
        controlsBar.style.display = 'none';
        container.innerHTML = '<div class="text-center py-5 text-white"><div class="spinner-border text-warning mb-3" style="width: 3rem; height: 3rem;" role="status"></div><div class="fw-semibold fs-6">Menyiapkan halaman naskah...</div><div class="small text-white-50 mt-1">Harap tunggu sebentar</div></div>';

        section.style.display = 'block';
        section.scrollIntoView({ behavior: 'smooth', block: 'start' });

        if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = function() {
                container.innerHTML = '';
                const pageCard = document.createElement('div');
                pageCard.className = 'shadow mb-4 mx-auto position-relative text-center';
                pageCard.style.backgroundColor = '#FFFFFF';
                pageCard.style.borderRadius = '4px';
                pageCard.style.maxWidth = 'fit-content';
                pageCard.style.boxShadow = '0 8px 24px rgba(0,0,0,0.4)';

                const canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;
                canvas.className = 'd-block mx-auto';
                canvas.style.maxHeight = '75vh';
                canvas.style.maxWidth = '100%';
                canvas.style.objectFit = 'contain';

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);

                const shield = document.createElement('div');
                shield.style.position = 'absolute';
                shield.style.inset = '0';
                shield.style.zIndex = '5';
                shield.addEventListener('contextmenu', function(e) { e.preventDefault(); return false; });
                shield.addEventListener('selectstart', function(e) { e.preventDefault(); return false; });
                shield.addEventListener('dragstart', function(e) { e.preventDefault(); return false; });

                pageCard.appendChild(canvas);
                pageCard.appendChild(shield);
                container.appendChild(pageCard);
            };
            img.onerror = function() {
                container.innerHTML = '<div class="text-danger p-4 text-center">Gagal memuat berkas cover.</div>';
            };
            img.src = fileUrl;
        } else if (ext === 'pdf') {
            controlsBar.style.display = 'flex';
            continuousScale = 1.15;

            pdfjsLib.getDocument(fileUrl).promise.then(function(pdfDoc) {
                loadedPdfDoc = pdfDoc;
                renderAllPages(pdfDoc, continuousScale);
            }).catch(function(err) {
                console.error(err);
                container.innerHTML = '<div class="text-danger p-5 text-center"><i class="bi bi-exclamation-triangle fs-1 d-block mb-2"></i>Gagal memuat pratinjau dokumen PDF.</div>';
            });
        } else {
            container.innerHTML = '<div class="text-center py-5 text-white"><i class="bi bi-file-earmark-check fs-1 text-warning d-block mb-2"></i><h6 class="fw-bold">Berkas (' + ext.toUpperCase() + ') Siap Ditinjau</h6><p class="small text-white-50 mb-0">Format berkas ini siap dikelola oleh redaksi.</p></div>';
        }
    }

    function closeInlineViewer() {
        const section = document.getElementById('inlineViewerSection');
        section.style.display = 'none';
        if (currentActiveTaskId) {
            const targetCard = document.getElementById('taskCard-' + currentActiveTaskId);
            if (targetCard) {
                targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    // Disable all right-click & key combinations inside viewer wrapper
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('inlineViewerWrapper');
        if (wrapper) {
            wrapper.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }, true);
        }

        window.addEventListener('keydown', function(e) {
            const section = document.getElementById('inlineViewerSection');
            if (section && section.style.display !== 'none') {
                if ((e.ctrlKey || e.metaKey) && ['s', 'p', 'u'].includes(e.key.toLowerCase())) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            }
        }, true);
    });
</script>
@endpush
