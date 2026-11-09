@extends('layouts.admin')

@section('title', 'Kelola Seluruh Naskah Masuk — PT. Pena Cendekia')
@section('page-title', 'Kelola Seluruh Naskah Masuk')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold font-serif mb-1">Daftar Pengajuan Naskah</h2>
            <p class="text-muted mb-0">Kelola dan pantau seluruh alur naskah buku dari penulis.</p>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card card-soft p-3 mb-4">
        <form action="{{ route('admin.submissions.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" value="{{ request('search') }}" placeholder="Cari kode naskah, judul buku, atau nama penulis...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="">-- Semua Status Naskah --</option>
                    <option value="pending_review" {{ request('status') == 'pending_review' ? 'selected' : '' }}>Menunggu Review</option>
                    <option value="revision_required" {{ request('status') == 'revision_required' ? 'selected' : '' }}>Perlu Revisi</option>
                    <option value="waiting_payment" {{ request('status') == 'waiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran DP</option>
                    <option value="payment_verification" {{ request('status') == 'payment_verification' ? 'selected' : '' }}>Verifikasi Pembayaran</option>
                    <option value="in_design" {{ request('status') == 'in_design' ? 'selected' : '' }}>Proses Desain</option>
                    <option value="ready_to_print" {{ request('status') == 'ready_to_print' ? 'selected' : '' }}>Siap Cetak (Pelunasan)</option>
                    <option value="printing" {{ request('status') == 'printing' ? 'selected' : '' }}>Sedang Dicetak</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-brand w-100"><i class="bi bi-filter me-1"></i> Terapkan</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.submissions.index') }}" class="btn btn-light" title="Reset Filter"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card card-soft p-4">
        @if($submissions->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                Tidak ditemukan naskah dengan kriteria pencarian yang Anda masukkan.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>Kode</th>
                            <th>Judul & Editor</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Spesifikasi</th>
                            <th>Status</th>
                            <th>Biaya</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $sub)
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace mb-1">{{ $sub->code }}</span>
                                    <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis d-block" style="font-size: 10px;">
                                        <i class="bi {{ $sub->service_icon ?? 'bi-book' }} me-1"></i> {{ $sub->service_type_label ?? 'Penerbitan' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold d-block text-dark">{{ $sub->title }}</span>
                                    <small class="badge bg-light text-dark border">{{ $sub->package_name ?? 'Paket Kustom' }}</small>
                                </td>
                                <td>
                                    <span class="d-block fw-semibold small">{{ $sub->user->name }}</span>
                                    <small class="text-muted">{{ $sub->user->phone }}</small>
                                </td>
                                <td><span class="badge bg-secondary-subtle text-secondary small">{{ $sub->category->name }}</span></td>
                                <td>
                                    <small class="d-block">{{ $sub->estimated_pages }} hal &bull; {{ $sub->quantity }} eks</small>
                                    <small class="text-muted">{{ ucfirst($sub->cover_type) }} Cover</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $sub->status_badge }} rounded-pill px-3 py-2">
                                        {{ $sub->status_label }}
                                    </span>
                                </td>
                                <td>
                                    @if($sub->total_price)
                                        <span class="fw-bold small d-block">Rp {{ number_format($sub->total_price, 0, ',', '.') }}</span>
                                        <small class="text-muted">DP: Rp {{ number_format($sub->dp_amount, 0, ',', '.') }}</small>
                                    @else
                                        <span class="badge bg-light text-muted border">Belum Dihitung</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.submissions.show', $sub) }}" class="btn btn-sm btn-brand">
                                        <i class="bi bi-search me-1"></i> Tinjau & Kelola
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection