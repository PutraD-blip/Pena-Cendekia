@extends('layouts.author')

@section('title', 'Pembayaran ' . ($paymentType === 'dp' ? 'DP 50%' : 'Pelunasan 50%') . ' — ' . $submission->title)
@section('page-title', 'Pembayaran ' . ($paymentType === 'dp' ? 'DP 50%' : 'Pelunasan 50%'))

@section('content')
<div class="container-fluid max-w-4xl px-0">
    <div class="mb-4">
        <a href="{{ route('author.dashboard') }}" class="btn btn-sm btn-outline-secondary mb-2"><i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard</a>
        <h3 class="fw-bold mb-1" style="font-family: var(--font-heading); color: var(--author-text-main);">
            Pembayaran {{ $paymentType === 'dp' ? 'Down Payment (DP 50%)' : 'Pelunasan Biaya Cetak (50%)' }}
        </h3>
        <p class="text-muted small">Naskah: <strong style="color: var(--author-text-main);">{{ $submission->title }}</strong> (<span class="font-monospace text-warning">{{ $submission->code }}</span>)</p>
    </div>

    <div class="row g-4">
        <!-- Bank Accounts & Total -->
        <div class="col-lg-5">
            <div class="dash-card p-4 mb-4">
                <span class="text-muted small text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Jumlah yang Harus Ditransfer</span>
                <div class="my-2" style="font-family: var(--font-heading); font-size: 32px; font-weight: 800; color: var(--author-gold);">
                    Rp {{ number_format($amountDue, 0, ',', '.') }}
                </div>
                <div class="p-2.5 rounded-3 small text-muted" style="background-color: var(--author-card-surface, #F1F5F9); border: 1px solid var(--author-card-border, #CBD5E1);">
                    Total Estimasi Biaya Cetak: <strong style="color: var(--author-text-main);">Rp {{ number_format($submission->total_price, 0, ',', '.') }}</strong>
                </div>
            </div>

            <div class="dash-card p-4">
                <h6 class="fw-bold mb-3 d-flex align-items-center gap-2 text-warning" style="font-family: var(--font-heading);">
                    <i class="bi bi-bank fs-5"></i>
                    <span>Rekening Tujuan Transfer</span>
                </h6>
                <p class="text-muted small mb-3">Silakan transfer secara manual ke salah satu rekening resmi PT. Pena Cendikia berikut:</p>

                <div class="d-flex flex-column gap-3">
                    @foreach($accounts as $acc)
                        <div class="p-3 rounded-3 position-relative" style="background-color: var(--author-card-surface, #F1F5F9); border: 1px solid var(--author-card-border, #CBD5E1);">
                            <span class="badge bg-warning text-dark small mb-1">{{ $acc->bank_name }}</span>
                            <div class="fw-bold fs-5 font-monospace d-flex align-items-center justify-content-between" style="color: var(--author-text-main);">
                                <span id="acc-{{ $acc->id }}">{{ $acc->account_number }}</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 rounded-pill copy-btn-{{ $acc->id }}" onclick="copyAcc('{{ $acc->account_number }}', {{ $acc->id }})" title="Salin No. Rekening">
                                    <i class="bi bi-clipboard"></i> <span class="copy-label">Salin</span>
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1">a.n. <strong style="color: var(--author-text-main);">{{ $acc->account_name }}</strong></small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="col-lg-7">
            <div class="dash-card p-4">
                <h5 class="fw-bold mb-2" style="font-family: var(--font-heading); color: var(--author-text-main);">Unggah Bukti Transfer Manual</h5>
                <p class="text-muted small mb-4">Setelah melakukan transfer melalui ATM, m-Banking, atau Internet Banking, silakan unggah screenshot atau foto struk bukti pembayaran Anda di bawah ini:</p>

                @if($existingPayment && $existingPayment->status === 'rejected')
                    <div class="alert alert-danger border-0 rounded-3 small mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Bukti sebelumnya ditolak: 
                        <strong>{{ $existingPayment->rejection_notes }}</strong>. Silakan unggah bukti transfer yang valid.
                    </div>
                @endif

                <form action="{{ route('author.payments.upload', $submission) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Bank Tujuan yang Dipilih -->
                    <div class="mb-3">
                        <label for="payment_account_id" class="form-label">Bank Tujuan yang Anda Transfer <span class="text-danger">*</span></label>
                        <select name="payment_account_id" id="payment_account_id" class="form-select @error('payment_account_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Rekening Bank Tujuan --</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" {{ old('payment_account_id', $existingPayment?->payment_account_id) == $acc->id ? 'selected' : '' }}>
                                    {{ $acc->bank_name }} - {{ $acc->account_number }} (a.n. {{ $acc->account_name }})
                                </option>
                            @endforeach
                        </select>
                        @error('payment_account_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <!-- File Bukti -->
                    <div class="mb-4">
                        <label for="proof_file" class="form-label">Foto / Screenshot Bukti Transfer <span class="text-danger">*</span></label>
                        <div class="p-3 rounded-3" style="background-color: var(--author-card-surface, #F1F5F9); border: 1.5px solid var(--author-card-border, #CBD5E1);">
                            <input type="file" name="proof_file" id="proof_file" class="form-control @error('proof_file') is-invalid @enderror" required accept="image/jpeg,image/png,application/pdf">
                            <small class="text-muted d-block mt-2" style="font-size: 11px;">
                                <i class="bi bi-info-circle me-1"></i> Format berkas: <strong>JPG, PNG, atau PDF</strong>. Ukuran maksimal <strong>5 MB</strong>. Pastikan nominal, tanggal, dan nomor referensi terlihat jelas.
                            </small>
                        </div>
                        @error('proof_file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="p-3 rounded-3 small text-muted mb-4" style="background-color: var(--author-card-surface, #F1F5F9); border: 1px solid var(--author-card-border, #CBD5E1);">
                        <i class="bi bi-shield-lock text-success me-1"></i> Pembayaran Anda akan diverifikasi oleh Admin PT. Pena Cendikia secara manual. Setelah diverifikasi, naskah Anda akan langsung diteruskan ke tim desainer (untuk DP) atau percetakan (untuk pelunasan).
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('author.dashboard') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-gold px-4 py-2">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i> Kirim Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyAcc(text, accId) {
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.querySelector('.copy-btn-' + accId);
            if (btn) {
                const label = btn.querySelector('.copy-label');
                if (label) {
                    label.textContent = 'Tersalin!';
                    btn.classList.add('btn-success');
                    btn.classList.remove('btn-outline-secondary');
                    setTimeout(() => {
                        label.textContent = 'Salin';
                        btn.classList.remove('btn-success');
                        btn.classList.add('btn-outline-secondary');
                    }, 2000);
                }
            }
        });
    }
</script>
@endpush
@endsection
