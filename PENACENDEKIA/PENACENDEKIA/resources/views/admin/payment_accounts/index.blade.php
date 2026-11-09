@extends('layouts.admin')

@section('title', 'Rekening Bank Penerbit — PT. Pena Cendekia')
@section('page-title', 'Rekening Bank Penerbit')

@section('content')
<div class="container-fluid max-w-5xl">
    <div class="mb-4">
        <h2 class="fw-bold font-serif mb-1">Rekening Bank PT. Pena Cendikia</h2>
        <p class="text-muted mb-0">Kelola nomor rekening resmi perusahaan yang akan ditampilkan kepada penulis saat pembayaran transfer manual.</p>
    </div>

    <div class="row g-4">
        <!-- List Rekening -->
        <div class="col-lg-7">
            <div class="card card-soft p-4">
                <h5 class="fw-bold font-serif mb-3">Daftar Rekening Bank Aktif</h5>

                @if($accounts->isEmpty())
                    <div class="text-center py-4 text-muted">
                        Belum ada rekening bank yang didaftarkan.
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($accounts as $acc)
                            <div class="p-3 border rounded-3 bg-white d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="fw-bold text-dark">{{ $acc->bank_name }}</span>
                                        @if($acc->is_active)
                                            <span class="badge bg-success small">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary small">Nonaktif</span>
                                        @endif
                                    </div>
                                    <div class="fs-5 font-monospace fw-bold text-dark">{{ $acc->account_number }}</div>
                                    <small class="text-muted">a.n. <strong>{{ $acc->account_name }}</strong></small>
                                </div>
                                <div>
                                    <form action="{{ route('admin.paymentAccounts.toggleStatus', $acc) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $acc->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" onclick="return confirm('Ubah status rekening ini?')">
                                            {{ $acc->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Form Tambah Rekening -->
        <div class="col-lg-5">
            <div class="card card-soft p-4">
                <h5 class="fw-bold font-serif mb-3">Tambah Rekening Bank Baru</h5>

                <form action="{{ route('admin.paymentAccounts.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Bank <span class="text-danger">*</span></label>
                        <input type="text" name="bank_name" class="form-control" required placeholder="Contoh: BCA / Mandiri / BNI">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nomor Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_number" class="form-control font-monospace" required placeholder="Contoh: 1234567890">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Nama Pemilik Rekening <span class="text-danger">*</span></label>
                        <input type="text" name="account_name" class="form-control" required placeholder="Contoh: PT. PENA CENDEKIA">
                    </div>

                    <button type="submit" class="btn btn-brand w-100 py-2">
                        <i class="bi bi-plus-circle me-1"></i> Simpan Rekening Bank
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection