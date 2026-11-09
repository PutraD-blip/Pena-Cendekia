@extends('layouts.admin')

@section('title', 'Manajemen Pengguna — PT. Pena Cendekia')
@section('page-title', 'Manajemen Pengguna')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold font-serif mb-1">Manajemen Pengguna</h2>
            <p class="text-muted mb-0">Kelola akun Admin, Penulis, dan Tim Desain PT. Pena Cendikia.</p>
        </div>
        <div>
            <button type="button" class="btn btn-brand d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-person-plus-fill"></i>
                <span>Tambah Akun Tim / Admin</span>
            </button>
        </div>
    </div>

    <!-- Filter -->
    <div class="card card-soft p-3 mb-4">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" value="{{ request('search') }}" placeholder="Cari nama, email, atau nomor HP pengguna...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="role" class="form-select bg-light" onchange="this.form.submit()">
                    <option value="">-- Semua Peran (Role) --</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="designer" {{ request('role') == 'designer' ? 'selected' : '' }}>Tim Desain (Designer)</option>
                    <option value="author" {{ request('role') == 'author' ? 'selected' : '' }}>Penulis (Author)</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-brand w-100">Filter</button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="card card-soft p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="small text-muted">
                        <th>Pengguna</th>
                        <th>Peran (Role)</th>
                        <th>Kontak</th>
                        <th>Status Profil</th>
                        <th>Status Akun</th>
                        <th>Terdaftar Sejak</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary-subtle text-dark fw-bold" style="width: 36px; height: 36px; font-size: 13px;">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="fw-bold d-block text-dark">{{ $u->name }}</span>
                                        <small class="text-muted">{{ $u->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-role badge-role-{{ $u->role }}">{{ ucfirst($u->role) }}</span>
                            </td>
                            <td><small>{{ $u->phone }}</small></td>
                            <td>
                                @if($u->role === 'author')
                                    @if($u->profile && $u->profile->isComplete())
                                        <span class="badge bg-success-subtle text-success small"><i class="bi bi-check2"></i> Lengkap</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning-emphasis small"><i class="bi bi-clock"></i> Belum Lengkap</span>
                                    @endif
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($u->is_active)
                                    <span class="badge bg-success small">Aktif</span>
                                @else
                                    <span class="badge bg-danger small">Nonaktif</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $u->created_at->format('d M Y') }}</small></td>
                            <td class="text-end">
                                @if($u->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggleStatus', $u) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" onclick="return confirm('Ubah status aktif akun ini?')">
                                            {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Modal Tambah Akun -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title font-serif fw-bold">Tambah Akun Tim / Administrator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Peran (Role) <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            <option value="designer">Tim Desain (Designer Layout / Cover)</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="Nama anggota tim">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required placeholder="email@penacendikia.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" required placeholder="08123456789">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Kata Sandi Awal <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" minlength="8" required placeholder="Minimal 8 karakter">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-brand btn-sm">Simpan Akun Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection