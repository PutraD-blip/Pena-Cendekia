@extends('layouts.author')

@section('title', 'Profil Penulis')
@section('page-title', 'Profil Penulis')

@section('content')
<div class="container-fluid max-w-4xl px-0">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="fw-bold mb-1" style="color: var(--author-text-main, #0F172A); font-family: var(--font-heading);">Identitas & Data Resmi Penulis</h4>
                    <p class="text-muted small mb-0">Lengkapi NIK dan scan KTP Anda untuk keperluan legalitas hak cipta, kontrak penerbitan, dan pendaftaran ISBN Perpusnas.</p>
                </div>
                <div>
                    @if($profile && $profile->isComplete())
                        <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-patch-check-fill me-1"></i> Profil Lengkap</span>
                    @else
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-exclamation-circle-fill me-1"></i> Belum Lengkap</span>
                    @endif
                </div>
            </div>

            @if(!$profile || !$profile->isComplete())
                <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4" style="background: rgba(245, 166, 35, 0.12); border: 1.5px solid rgba(245, 166, 35, 0.45) !important;">
                    <div class="d-flex align-items-start gap-3">
                        <i class="bi bi-shield-exclamation fs-3 text-warning"></i>
                        <div>
                            <strong class="d-block mb-1" style="color: var(--author-text-main, #0F172A);">Perhatian Penting:</strong>
                            <span class="text-muted small">Anda wajib melengkapi data NIK, alamat domisili lengkap, dan mengunggah foto KTP sebelum dapat mengajukan naskah buku baru ke sistem PT. Pena Cendikia.</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="dash-card">
                <form action="{{ route('author.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Akun & Identitas Kontak -->
                    <div class="row g-3 mb-4 pb-3 border-bottom" style="border-color: var(--author-border) !important;">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Lengkap Sesuai KTP <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required placeholder="Nama lengkap sesuai KTP">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Email Terdaftar</label>
                            <input type="text" class="form-control" value="{{ $user->email }}" readonly title="Email terdaftar">
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">Nomor HP / WhatsApp Aktif <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" required placeholder="Contoh: 08123456789">
                            <small class="text-muted" style="font-size: 11px;">Digunakan untuk koordinasi proses penerbitan naskah buku Anda.</small>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Penautan Akun Google</label>
                            <div>
                                @if($user->google_id)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-google"></i> Akun Google Tertaut
                                    </span>
                                @else
                                    <a href="{{ route('auth.google') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1 d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-google text-danger"></i> Tautkan Akun Google
                                    </a>
                                    <small class="text-muted d-block mt-1" style="font-size: 11px;">Tautkan agar Anda bisa masuk dengan 1 klik Google.</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- NIK -->
                    <div class="mb-3">
                        <label for="nik" class="form-label">Nomor Induk Kependudukan (NIK) <span class="text-danger">*</span></label>
                        <input type="text" name="nik" id="nik" maxlength="16" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $profile?->nik) }}" required placeholder="16 digit nomor KTP Anda">
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">NIK digunakan secara rahasia dan terlindungi sesuai kebijakan privasi kami.</small>
                        @if($profile?->nik)
                            <div class="small text-success mt-1"><i class="bi bi-shield-check me-1"></i> NIK tersimpan: <strong>{{ $profile->masked_nik }}</strong></div>
                        @endif
                        @error('nik')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="mb-4">
                        <label for="address" class="form-label">Alamat Domisili Lengkap <span class="text-danger">*</span></label>
                        <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" required placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos">{{ old('address', $profile?->address) }}</textarea>
                        @error('address')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('author.dashboard') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-gold px-4">
                            <i class="bi bi-save me-1"></i> Simpan Data Diri
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection