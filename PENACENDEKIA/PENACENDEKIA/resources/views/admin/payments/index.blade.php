@extends('layouts.admin')

@section('title', 'Verifikasi Pembayaran Manual — PT. Pena Cendekia')
@section('page-title', 'Verifikasi Pembayaran')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold font-serif mb-1">Verifikasi Pembayaran Manual</h2>
        <p class="text-muted mb-0">Periksa bukti screenshot / struk transfer rekening bank dari penulis sebelum menyetujui tahapan berikutnya.</p>
    </div>

    <!-- Filter -->
    <div class="card card-soft p-3 mb-4">
        <form action="{{ route('admin.payments.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <select name="status" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="">-- Semua Status Verifikasi --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi (Pending)</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi (Verified)</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                </select>
            </div>
            <div class="col-md-4">
                <select name="type" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="">-- Semua Jenis Termin --</option>
                    <option value="dp" {{ request('type') == 'dp' ? 'selected' : '' }}>Down Payment (DP 50%)</option>
                    <option value="final" {{ request('type') == 'final' ? 'selected' : '' }}>Pelunasan (50%)</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-brand w-100"><i class="bi bi-filter me-1"></i> Filter</button>
                @if(request()->hasAny(['status', 'type']))
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-light" title="Reset Filter"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="card card-soft p-4">
        @if($payments->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-credit-card-2-front fs-1 d-block mb-2"></i>
                Tidak ada data pembayaran yang sesuai dengan kriteria filter.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>Tanggal Unggah</th>
                            <th>Naskah & Penulis</th>
                            <th>Termin</th>
                            <th>Nominal</th>
                            <th>Rekening Tujuan</th>
                            <th>Bukti Transfer</th>
                            <th>Status</th>
                            <th class="text-end">Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $pay)
                            <tr>
                                <td class="small text-muted">
                                    {{ $pay->proof_uploaded_at ? $pay->proof_uploaded_at->format('d M Y, H:i') : $pay->created_at->format('d M Y, H:i') }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace mb-1">{{ $pay->submission->code }}</span>
                                    <span class="fw-bold d-block text-dark">{{ $pay->submission->title }}</span>
                                    <small class="text-muted">{{ $pay->submission->user->name }} ({{ $pay->submission->user->phone }})</small>
                                </td>
                                <td><span class="badge bg-primary-subtle text-primary">{{ $pay->type_label }}</span></td>
                                <td class="fw-bold">Rp {{ number_format($pay->amount_due, 0, ',', '.') }}</td>
                                <td>
                                    <small class="d-block fw-semibold">{{ $pay->account->bank_name }}</small>
                                    <small class="text-muted">{{ $pay->account->account_number }}</small>
                                </td>
                                <td>
                                    @if($pay->proof_file)
                                        <a href="{{ route('file.show', ['type' => 'payment_proofs_' . $pay->type, 'filename' => $pay->proof_file]) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye me-1"></i> Buka Bukti
                                        </a>
                                    @else
                                        <span class="text-muted small">Belum diunggah</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $pay->status_badge }} rounded-pill px-3 py-2">
                                        {{ $pay->status_label }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if($pay->status === 'pending' && $pay->proof_file)
                                        <div class="d-flex justify-content-end gap-1">
                                            <!-- Approve Form -->
                                            <form action="{{ route('admin.payments.verify', $pay) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Verifikasi dan terima pembayaran ini?')">
                                                    <i class="bi bi-check-lg"></i> Terima
                                                </button>
                                            </form>

                                            <!-- Reject Modal Trigger -->
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $pay->id }}">
                                                <i class="bi bi-x-lg"></i> Tolak
                                            </button>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade text-start" id="rejectModal-{{ $pay->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content border-0 shadow">
                                                    <form action="{{ route('admin.payments.verify', $pay) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="action" value="reject">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title font-serif fw-bold text-danger">Tolak Bukti Transfer</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p class="small text-muted mb-2">Penulis: <strong>{{ $pay->submission->user->name }}</strong> — Naskah: <strong>{{ $pay->submission->title }}</strong></p>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                                                                <textarea name="rejection_notes" rows="3" class="form-control" required placeholder="Contoh: Nominal transfer tidak sesuai, gambar buram tidak terbaca, atau mutasi belum masuk di rekening..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-danger btn-sm">Konfirmasi Tolak Bukti</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($pay->status === 'verified')
                                        <small class="text-success d-block">
                                            <i class="bi bi-check-circle me-1"></i> Terverifikasi oleh {{ $pay->verifier->name ?? 'Admin' }}
                                        </small>
                                    @elseif($pay->status === 'rejected')
                                        <small class="text-danger d-block text-truncate" style="max-width: 150px;" title="{{ $pay->rejection_notes }}">
                                            <i class="bi bi-x-circle me-1"></i> {{ $pay->rejection_notes }}
                                        </small>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection