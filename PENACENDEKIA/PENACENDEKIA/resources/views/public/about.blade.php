@extends('layouts.app')

@section('title', 'Tentang Kami — PT. Pena Cendekia Pustaka')

@section('content')
<div class="container py-5 max-w-4xl">
    <div class="text-center mb-5">
        <span class="badge badge-soft-primary px-3 py-2 rounded-pill fw-semibold mb-2">Profil Perusahaan</span>
        <h1 class="display-5 fw-bold font-serif mb-3">Mengenal PT. Pena Cendekia Pustaka</h1>
        <p class="text-muted">Wahana penyalur ilmu pengetahuan bagi para cendekia dan penulis Indonesia.</p>
    </div>

    <div class="card card-soft p-4 p-md-5 mb-5">
        <h3 class="fw-bold font-serif mb-3 text-dark">Bermula dari Kebutuhan Para Dosen</h3>
        <p class="text-muted" style="line-height: 1.8;">
            Konsep Penerbit <strong>Pena Cendekia</strong> bermula dari banyaknya kebutuhan dosen untuk menerbitkan naskah sebagai buku teks atau buku referensi. Kami melakukan penelitian, diskusi, dan benchmarking selama dua tahun untuk menemukan proses produksi yang paling cocok memenuhi kebutuhan ini.
        </p>
        <p class="text-muted" style="line-height: 1.8;">
            Pada akhirnya kami menemukan polanya: kami dapat memproses naskah buku hanya dalam waktu <strong>6 (enam) minggu</strong>. Kami tidak hanya berinovasi dalam sistem produksi, tetapi juga mengembangkan sistem lain untuk memudahkan para cendekia.
        </p>

        <div class="row g-4 mt-3">
            <div class="col-md-6">
                <div class="p-4 rounded-4 h-100" style="background-color: #FAF6F0;">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-2 text-white mb-2" style="background-color: #8B5E3C; width: 40px; height: 40px;">
                        <i class="bi bi-journal-bookmark"></i>
                    </div>
                    <h5 class="fw-bold font-serif mb-2">Fokus Penerbitan</h5>
                    <p class="text-muted small mb-0">
                        Buku budaya, sastra, pemikiran, psikologi perkembangan, buku ajar, dan buku referensi. Kami juga menerima fiksi seperti prosa, novel, puisi, dan buku terjemahan.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4 rounded-4 h-100" style="background-color: #FAF6F0;">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle p-2 text-white mb-2" style="background-color: #8B5E3C; width: 40px; height: 40px;">
                        <i class="bi bi-bar-chart-line"></i>
                    </div>
                    <h5 class="fw-bold font-serif mb-2">Pencapaian</h5>
                    <p class="text-muted small mb-0">
                        200 judul buku dari 250 penulis, dengan lebih dari 90.000 buku telah tercetak.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Nilai-nilai Keunggulan -->
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card card-soft p-4 h-100">
                <i class="bi bi-shield-check fs-1 text-success mb-2"></i>
                <h5 class="fw-bold font-serif">Legal &amp; Terpercaya</h5>
                <p class="text-muted small mb-0">Anggota IKAPI No. 379/JTI/2023. Setiap perjanjian dan kerja sama diatur melalui Surat Perjanjian Kerjasama (SPK).</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-soft p-4 h-100">
                <i class="bi bi-globe-asia-australia fs-1 text-primary mb-2"></i>
                <h5 class="fw-bold font-serif">Distribusi Luas</h5>
                <p class="text-muted small mb-0">Buku terbitan kami dapat ditemukan di toko buku offline dan online seluruh Indonesia.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-soft p-4 h-100">
                <i class="bi bi-printer fs-1 text-warning mb-2"></i>
                <h5 class="fw-bold font-serif">Cetak Berkualitas</h5>
                <p class="text-muted small mb-0">Buku dicetak dengan hasil berkualitas, lengkap dengan desain kover dan layout berstandar tinggi.</p>
            </div>
        </div>
    </div>
</div>
@endsection
