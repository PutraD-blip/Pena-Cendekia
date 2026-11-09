@extends('layouts.app')

@section('title', 'Template Buku & Naskah — PT. Pena Cendekia Pustaka')

@push('styles')
<style>
    .template-hero {
        position: relative;
        background: linear-gradient(135deg, rgba(14, 18, 24, 0.90) 0%, rgba(20, 24, 32, 0.85) 50%, rgba(10, 14, 18, 0.92) 100%),
                    url('{{ asset('images/hero-bg.jpg') }}') center/cover no-repeat;
        padding-top: clamp(100px, 13vh, 140px);
        padding-bottom: clamp(60px, 8vh, 90px);
        text-align: center;
        color: #ffffff;
    }
    .template-hero-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(245, 166, 35, 0.15);
        border: 1px solid rgba(245, 166, 35, 0.45);
        color: #F5A623;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 5px 16px;
        border-radius: 50px;
        margin-bottom: 16px;
    }
    .template-hero-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: clamp(2rem, 3.4vw, 3.1rem);
        color: #ffffff;
        margin-bottom: 1rem;
        line-height: 1.25;
    }
    .template-hero-subtitle {
        color: rgba(255, 255, 255, 0.88);
        max-width: 760px;
        margin: 0 auto 2rem;
        font-size: clamp(0.95rem, 1.05vw, 1.1rem);
        line-height: 1.7;
    }
    .btn-hero-cta {
        background-color: #F5A623;
        color: #111827 !important;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        padding: 12px 32px;
        border-radius: 50px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 4px 18px rgba(245, 166, 35, 0.35);
    }
    .btn-hero-cta:hover {
        background-color: #e09214;
        color: #000000 !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(245, 166, 35, 0.45);
    }
    .btn-hero-secondary {
        background: rgba(255, 255, 255, 0.1);
        border: 1.5px solid rgba(255, 255, 255, 0.3);
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 11px 28px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.25s ease;
        backdrop-filter: blur(8px);
    }
    .btn-hero-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: #ffffff;
        transform: translateY(-2px);
    }

    .content-balanced-container {
        max-width: 1240px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .section-kicker-gold {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #F5A623;
        display: inline-block;
    }
    .section-title-dark {
        font-family: 'Poppins', sans-serif;
        font-size: clamp(1.8rem, 2.6vw, 2.4rem);
        font-weight: 800;
        color: #111827;
        letter-spacing: -0.5px;
    }

    /* Modern Template Card (Matching UI Paket Penerbitan) */
    .template-box-card {
        background: #FFFFFF;
        border: 1.5px solid #E5E7EB;
        border-radius: 20px;
        padding: 32px 26px;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease, border-color 0.3s ease;
        box-shadow: 0 4px 16px rgba(17, 24, 39, 0.03);
        position: relative;
    }
    .template-box-card:hover {
        transform: translateY(-6px);
        border-color: #F5A623;
        box-shadow: 0 16px 36px rgba(245, 166, 35, 0.14);
    }
    .template-box-highlight {
        border: 2px solid #F5A623 !important;
        box-shadow: 0 10px 30px rgba(245, 166, 35, 0.12) !important;
    }
    .template-box-highlight:hover {
        box-shadow: 0 20px 45px rgba(245, 166, 35, 0.22) !important;
    }
    .template-badge-pill {
        background: #FFF8EE;
        color: #D97706;
        border: 1px solid rgba(245, 166, 35, 0.4);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 4px 12px;
        border-radius: 50px;
    }
    .template-badge-format {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 2.2rem;
        font-weight: 800;
        color: #111827;
        line-height: 1.1;
    }
    .template-badge-format span {
        font-size: 1.1rem;
        font-weight: 700;
        color: #D97706;
        margin-left: 4px;
    }
    .template-dimension {
        font-size: 14px;
        font-weight: 600;
        color: #6B7280;
        margin-top: 4px;
        margin-bottom: 16px;
    }
    .template-divider {
        border: 0;
        border-top: 1px dashed #E5E7EB;
        margin: 0 0 18px 0;
    }
    .template-features {
        list-style: none;
        padding: 0;
        margin: 0 0 24px 0;
        display: flex;
        flex-direction: column;
        gap: 11px;
        flex-grow: 1;
    }
    .template-features li {
        font-size: 13.5px;
        color: #374151;
        display: flex;
        align-items: flex-start;
        line-height: 1.45;
    }
    .template-features li i {
        color: #F5A623;
        font-size: 16px;
        margin-right: 10px;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .btn-template-download {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        text-align: center;
        border-radius: 50px;
        padding: 11px 20px;
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 13.5px;
        text-decoration: none;
        transition: all 0.25s ease;
        background: #111827;
        color: #FFFFFF;
        border: 1.5px solid #111827;
    }
    .btn-template-download:hover {
        background: #F5A623;
        color: #111827;
        border-color: #F5A623;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(245, 166, 35, 0.35);
    }
    .btn-download-highlight {
        background: #F5A623;
        color: #111827;
        border-color: #F5A623;
        box-shadow: 0 4px 14px rgba(245, 166, 35, 0.3);
    }
    .btn-gdrive-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        font-size: 12px;
        font-weight: 600;
        color: #6B7280;
        text-decoration: none;
        margin-top: 8px;
        padding: 6px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-gdrive-link:hover {
        color: #111827;
        background: #F3F4F6;
    }

    /* Guidelines Section */
    .guide-box {
        background: #FFFDF8;
        border: 1.5px solid rgba(245, 166, 35, 0.35);
        border-radius: 18px;
        padding: 30px;
        margin-top: 40px;
    }

    /* Exclusive Banner Box */
    .exclusive-banner-box {
        background: linear-gradient(135deg, #1A1F2C 0%, #111827 100%);
        border: 1px solid rgba(245, 166, 35, 0.3);
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        color: #FFFFFF;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        position: relative;
        overflow: hidden;
    }
    .exclusive-banner-box::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(245, 166, 35, 0.08) 0%, transparent 60%);
        pointer-events: none;
    }
    .btn-admin-cta {
        background: #F5A623;
        color: #111827 !important;
        font-weight: 700;
        font-size: 14px;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 12px 32px;
        border-radius: 8px;
        border: none;
        display: inline-block;
        transition: all 0.25s ease;
        box-shadow: 0 4px 15px rgba(245, 166, 35, 0.35);
    }
    .btn-admin-cta:hover {
        background: #e09214;
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(245, 166, 35, 0.45);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="template-hero">
    <div class="content-balanced-container">
        <div class="template-hero-kicker">
            <i class="bi bi-file-earmark-word-fill"></i> Standar Tata Letak &amp; Layout Buku
        </div>
        <h1 class="template-hero-title">
            Unduh Template Buku &amp; Naskah
        </h1>
        <p class="template-hero-subtitle">
            Template Buku ini diperuntukkan bagi penulis yang memilih paket penerbitan non-layout atau ingin menata mandiri naskahnya agar sesuai dengan standar mirror margin dan spesifikasi mesin percetakan Pena Cendekia.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Penerbit Pena Cendekia, saya ingin konsultasi mengenai format template buku.') }}" target="_blank" rel="noopener" class="btn-hero-cta">
                <i class="bi bi-whatsapp"></i> Konsultasi Sekarang
            </a>
            <a href="#daftar-template" class="btn-hero-secondary">
                <i class="bi bi-download"></i> Pilih Ukuran Template
            </a>
        </div>
    </div>
</section>

<!-- Main Cards Section: Template Buku Pena Cendekia -->
<section class="py-5" style="background-color: #FBF9F5;" id="daftar-template">
    <div class="content-balanced-container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-kicker-gold mb-1">DOWNLOAD FORMAT RESMI</span>
            <h2 class="section-title-dark mb-2">Template Buku Pena Cendekia</h2>
            <p class="text-muted mx-auto" style="max-width: 680px; font-size: 15px;">
                File template Microsoft Word (.docx) siap pakai yang telah disetting margin simetris (mirror margins), header &amp; footer genap-ganjil, serta automatic hyphenation.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Card 1: A5 -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="50">
                <div class="template-box-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="template-badge-format">A5</div>
                        <span class="template-badge-pill">Novel &amp; Populer</span>
                    </div>
                    <div class="template-dimension">
                        <i class="bi bi-rulers text-warning me-1"></i> Ukuran Potong: <strong>14 x 20 cm</strong>
                    </div>
                    <hr class="template-divider">
                    <ul class="template-features">
                        <li><i class="bi bi-check2-circle"></i><span>Ukuran standar buku bacaan <strong>14 x 20 cm</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span>File sudah disetting <strong>Mirror Margins</strong> (dalam &amp; luar)</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Setting <strong>Hyphenation ke Automatic</strong> aktif</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Font body proporsional &amp; spasi rapi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Cocok untuk novel, kumpulan puisi, cerpen, &amp; buku motivasi</span></li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ asset('downloads/template-buku-a5.docx') }}" download class="btn-template-download">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i> Download File (.docx)
                        </a>
                        <a href="https://docs.google.com/document/d/1XDiNDTBA-GnK0pflMEC2nEHXTwlmTIN2/edit" target="_blank" rel="noopener" class="btn-gdrive-link">
                            <i class="bi bi-box-arrow-up-right"></i> Buka di Google Docs
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: UNESCO (Highlight) -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="template-box-card template-box-highlight">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="template-badge-format">UNESCO<span>B5</span></div>
                        <span class="template-badge-pill">Standar Akademik</span>
                    </div>
                    <div class="template-dimension">
                        <i class="bi bi-rulers text-warning me-1"></i> Ukuran Potong: <strong>15.5 x 23 cm</strong>
                    </div>
                    <hr class="template-divider">
                    <ul class="template-features">
                        <li><i class="bi bi-check2-circle"></i><span>Standar resmi penerbitan ilmiah <strong>15.5 x 23 cm</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span>File sudah disetting <strong>Mirror Margins</strong> presisi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Setting <strong>Hyphenation ke Automatic</strong> aktif</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Dilengkapi halaman awal: Judul, Prakata, Daftar Isi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Rekomendasi Utama</strong> untuk Monograf, Referensi &amp; Buku Ajar Dosen</span></li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ asset('downloads/template-buku-unesco.docx') }}" download class="btn-template-download btn-download-highlight">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i> Download File (.docx)
                        </a>
                        <a href="https://docs.google.com/document/d/1z19pXPudjaYpD6-BgdSaW76WQv00XsC2/edit" target="_blank" rel="noopener" class="btn-gdrive-link">
                            <i class="bi bi-box-arrow-up-right"></i> Buka di Google Docs
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 3: A4 -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="150">
                <div class="template-box-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="template-badge-format">A4</div>
                        <span class="template-badge-pill">Modul &amp; Laporan</span>
                    </div>
                    <div class="template-dimension">
                        <i class="bi bi-rulers text-warning me-1"></i> Ukuran Potong: <strong>21 x 29.7 cm</strong>
                    </div>
                    <hr class="template-divider">
                    <ul class="template-features">
                        <li><i class="bi bi-check2-circle"></i><span>Ukuran lembar kerja besar <strong>21 x 29.7 cm</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span>File sudah disetting <strong>Mirror Margins</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Setting <strong>Hyphenation ke Automatic</strong> aktif</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Format tabel dan ilustrasi diagram yang leluasa</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Cocok untuk Modul Praktikum, Diktat Kuliah, &amp; Laporan Riset</span></li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ asset('downloads/template-buku-a4.docx') }}" download class="btn-template-download">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i> Download File (.docx)
                        </a>
                        <a href="https://docs.google.com/document/d/1o1Gjiscydsw5QCVNdPwXnHtcJfpxO1Tm/edit" target="_blank" rel="noopener" class="btn-gdrive-link">
                            <i class="bi bi-box-arrow-up-right"></i> Buka di Google Docs
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panduan Pengisian Template -->
        <div class="guide-box" data-aos="fade-up">
            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <h4 class="fw-bold text-dark mb-2 font-serif">
                        <i class="bi bi-info-circle-fill text-warning me-2"></i> Petunjuk Singkat Penggunaan Template
                    </h4>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        1. Gunakan jenis huruf standar seperti <strong>Times New Roman (12pt)</strong> atau <strong>Garamond (11-12pt)</strong> dengan spasi 1.15 atau 1.5.<br>
                        2. Jangan mengubah margin yang telah disetting agar bagian dalam (punggung buku) tidak terpotong saat proses jilid lem panas.<br>
                        3. Pastikan gambar / grafik memiliki resolusi minimal <strong>300 DPI</strong> agar hasil cetak tajam dan tidak pecah.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end text-center">
                    <a href="{{ route('layanan.editing') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-semibold">
                        <i class="bi bi-pencil-square me-1 text-warning"></i> Butuh Bantuan Layout?
                    </a>
                </div>
            </div>
        </div>

        <!-- Banner Layanan Eksklusif Anggota IKAPI -->
        <div class="mt-5 pt-3" data-aos="fade-up">
            <div class="exclusive-banner-box">
                <h3 class="fw-bold mb-2 text-white font-serif" style="font-size: 1.5rem;">
                    Layanan Eksklusif Penerbit Pena Cendekia
                </h3>
                <div class="text-warning fw-semibold mb-3" style="font-size: 1.1rem; letter-spacing: 0.5px;">
                    (Anggota IKAPI No. 379/JTI/2023)
                </div>
                <p class="text-white-50 mx-auto mb-4" style="max-width: 680px; font-size: 15px; line-height: 1.6;">
                    &ldquo;Cetak 1000 eksemplar: Free Layanan Launching buku, tim Pena Cendekia akan menjadi fasilitator, admin, dan host dalam virtual launching buku penulis.&rdquo;
                </p>
                <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Penerbit Pena Cendekia, saya ingin bertanya tentang Layanan Eksklusif Penerbitan (Launching Buku).') }}" target="_blank" rel="noopener" class="btn-admin-cta">
                    <i class="bi bi-whatsapp me-1"></i> HUBUNGI ADMIN
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
