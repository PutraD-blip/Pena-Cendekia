@extends('layouts.app')

@section('title', 'Jasa Pengurusan HAKI Resmi — PT. Pena Cendekia Pustaka')

@push('styles')
<style>
    .service-hero {
        position: relative;
        background: linear-gradient(135deg, rgba(14, 18, 24, 0.88) 0%, rgba(20, 24, 32, 0.82) 50%, rgba(10, 14, 18, 0.90) 100%),
                    url('{{ asset('images/hero-bg.jpg') }}') center/cover no-repeat;
        padding-top: clamp(100px, 13vh, 140px);
        padding-bottom: clamp(60px, 8vh, 90px);
        text-align: center;
        color: #ffffff;
    }
    .service-hero-kicker {
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
    .service-hero-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: clamp(2rem, 3.4vw, 3.1rem);
        color: #ffffff;
        margin-bottom: 1rem;
        line-height: 1.25;
    }
    .service-hero-subtitle {
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

    /* Manfaat Card */
    .benefit-card {
        background: #FFFFFF;
        border: 1.5px solid #E5E7EB;
        border-radius: 18px;
        padding: 28px 24px;
        height: 100%;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
    }
    .benefit-card:hover {
        border-color: #F5A623;
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(245, 166, 35, 0.12);
    }
    .benefit-num {
        font-size: 2.2rem;
        font-weight: 900;
        color: #F5A623;
        line-height: 1;
        margin-bottom: 12px;
    }
    .benefit-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 10px;
    }
    .benefit-desc {
        font-size: 14px;
        color: #4B5563;
        line-height: 1.6;
        margin-bottom: 0;
    }

    /* Pricing Section (Same as HomePage Paket Penerbitan) */
    .pricing-section {
        background-color: #FBF9F5;
        padding-top: 4.5rem;
        padding-bottom: 5rem;
        position: relative;
    }
    .pricing-card {
        background: #FFFFFF;
        border: 1.5px solid #E5E7EB;
        border-radius: 20px;
        padding: 34px 28px 28px 28px;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease, border-color 0.3s ease;
        box-shadow: 0 4px 16px rgba(17, 24, 39, 0.03);
        position: relative;
    }
    .pricing-card:hover {
        transform: translateY(-6px);
        border-color: #F5A623;
        box-shadow: 0 16px 36px rgba(245, 166, 35, 0.14);
    }
    .pricing-card-highlight {
        border: 2px solid #F5A623 !important;
        box-shadow: 0 10px 30px rgba(245, 166, 35, 0.12) !important;
    }
    .pricing-card-highlight:hover {
        box-shadow: 0 20px 45px rgba(245, 166, 35, 0.22) !important;
    }
    .pricing-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
        min-height: 32px;
    }
    .pricing-tier-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.3rem;
        color: #111827;
        margin-bottom: 0;
    }
    .pricing-badge-gold {
        background: #FFF8EE;
        color: #D97706;
        border: 1px solid rgba(245, 166, 35, 0.4);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 50px;
    }
    .pricing-price-box {
        margin-bottom: 16px;
    }
    .pricing-price-num {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 2.3rem;
        font-weight: 800;
        color: #111827;
        line-height: 1.1;
    }
    .pricing-price-num span {
        font-size: 1.15rem;
        font-weight: 700;
        color: #D97706;
        margin-right: 2px;
    }
    .pricing-price-desc {
        font-size: 12.5px;
        color: #6B7280;
        margin-top: 4px;
        font-weight: 500;
        line-height: 1.45;
    }
    .pricing-clean-divider {
        border: 0;
        border-top: 1px dashed #E5E7EB;
        margin: 0 0 18px 0;
    }
    .pricing-card-highlight .pricing-clean-divider {
        border-top-color: rgba(245, 166, 35, 0.35);
    }
    .pricing-features {
        list-style: none;
        padding: 0;
        margin: 0 0 24px 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex-grow: 1;
    }
    .pricing-features li {
        font-size: 13.5px;
        color: #374151;
        display: flex;
        align-items: flex-start;
        line-height: 1.45;
    }
    .pricing-features li i {
        color: #F5A623;
        font-size: 16px;
        margin-right: 10px;
        flex-shrink: 0;
        margin-top: 1px;
    }
    .pricing-features li strong {
        color: #111827;
    }
    .btn-pricing-action {
        display: block;
        width: 100%;
        text-align: center;
        border-radius: 50px;
        padding: 11px 20px;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: 13.5px;
        text-decoration: none;
        transition: all 0.25s ease;
        background: #111827;
        color: #FFFFFF;
        border: 1.5px solid #111827;
    }
    .btn-pricing-action:hover {
        background: #F5A623;
        color: #111827;
        border-color: #F5A623;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(245, 166, 35, 0.35);
    }
    .btn-pricing-highlight {
        background: #F5A623;
        color: #111827;
        border-color: #F5A623;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(245, 166, 35, 0.3);
    }

    /* Certificate Showcase */
    .certificate-showcase-box {
        background: #FFFFFF;
        border: 1.5px solid #E5E7EB;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
        text-align: center;
    }
    .certificate-img {
        max-width: 100%;
        max-height: 520px;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        border: 1px solid rgba(0, 0, 0, 0.08);
    }

    /* Template Link Box */
    .template-info-box {
        background: #FFFDF8;
        border: 1.5px dashed rgba(245, 166, 35, 0.5);
        border-radius: 16px;
        padding: 24px;
        text-align: center;
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
<section class="service-hero">
    <div class="content-balanced-container">
        <div class="service-hero-kicker">
            <i class="bi bi-shield-check"></i> Perlindungan Hak Cipta Resmi DJKI
        </div>
        <h1 class="service-hero-title">
            Jasa Pengurusan HAKI Resmi (Hak Cipta)
        </h1>
        <p class="service-hero-subtitle">
            Hindari plagiarisme atau klaim pihak lain atas karya intelektual Anda. Amankan dan lindungi setiap buku, karya tulis, modul ajar, dan inovasi Anda dengan mengurus Hak atas Kekayaan Intelektual (HAKI) bersama Penerbit Pena Cendekia.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Penerbit Pena Cendekia, saya ingin konsultasi pengurusan sertifikat HAKI.') }}" target="_blank" rel="noopener" class="btn-hero-cta">
                <i class="bi bi-whatsapp"></i> Konsultasi Sekarang
            </a>
            <a href="{{ route('template.haki') }}" class="btn-hero-secondary">
                <i class="bi bi-download"></i> Unduh Template Berkas HAKI
            </a>
        </div>
    </div>
</section>

<!-- Manfaat HAKI (Hak atas Kekayaan Intelektual) -->
<section class="py-5 bg-white border-bottom">
    <div class="content-balanced-container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-kicker-gold mb-1">LEGALITAS &amp; KREDIT PROFESI</span>
            <h2 class="section-title-dark mb-2">Manfaat HAKI (Hak Atas Kekayaan Intelektual)</h2>
            <p class="text-muted mx-auto" style="max-width: 650px; font-size: 14.5px;">
                Mengapa dosen, peneliti, dan penulis wajib memiliki sertifikat perlindungan Hak Cipta resmi?
            </p>
        </div>

        <div class="row g-4">
            <!-- 1. Poin KUM Dosen -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="50">
                <div class="benefit-card">
                    <div class="benefit-num">1.</div>
                    <h3 class="benefit-title">Tambahan Poin KUM Dosen Hingga 40 Poin</h3>
                    <p class="benefit-desc">
                        Dosen yang memiliki legalitas sertifikat HKI dapat mengajukan tambahan angka kredit poin KUM hingga 40 poin untuk keperluan kenaikan jabatan fungsional akademik (Jafa).
                    </p>
                </div>
            </div>

            <!-- 2. Kepastian Hukum -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="benefit-card">
                    <div class="benefit-num">2.</div>
                    <h3 class="benefit-title">Kepastian Hukum Menerbitkan Karya</h3>
                    <p class="benefit-desc">
                        Memberikan kepastian hukum yang kokoh bagi pemegang hak dalam melakukan usahanya menerbitkan dan mengomersialkan karya tanpa kekhawatiran gangguan klaim sepihak dari pihak lain.
                    </p>
                </div>
            </div>

            <!-- 3. Penegakan Hukum -->
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="150">
                <div class="benefit-card">
                    <div class="benefit-num">3.</div>
                    <h3 class="benefit-title">Upaya Hukum Perdata &amp; Pidana</h3>
                    <p class="benefit-desc">
                        Bagi pemegang hak cipta terdaftar resmi, dapat melakukan upaya perlindungan hukum baik secara perdata maupun pidana bilamana terjadi pelanggaran, plagiarisme, atau pembajakan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Biaya Pengurusan HAKI (2 Paket Sesuai Link & Desain Paket Penerbitan) -->
<section class="pricing-section" id="biaya-haki">
    <div class="content-balanced-container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="section-kicker-gold mb-2">BIAYA PENGURUSAN HAKI</div>
            <h2 class="section-title-dark mb-3">Pilihan Paket Pengurusan HAKI Resmi</h2>
            <p class="text-muted mx-auto" style="max-width: 680px; font-size: 15px; line-height: 1.6;">
                Proses pengurusan cepat dan resmi tercatat di Pangkalan Data Kekayaan Intelektual Kemenkumham RI.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Paket Member -->
            <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="50">
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Paket Member</h3>
                        <span class="pricing-badge-gold">Khusus Penulis Pena</span>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num"><span>Rp</span>350.000</div>
                        <div class="pricing-price-desc">Jika penulis menerbitkan buku bersama Penerbit Pena Cendekia</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Pendaftaran Resmi DJKI Kemenkumham RI</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Sertifikat Elektronik HAKI</strong> Ber-barcode Resmi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Pendampingan Pengisian Surat Pengalihan Hak Cipta</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Tambahan Angka Kredit KUM Dosen Hingga 40 Poin</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Perlindungan Hak Cipta Seumur Hidup Penulis + 70 Tahun</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Proses Cepat &amp; Dokumen Langsung Terbit</span></li>
                    </ul>
                    <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Pena Cendekia, saya ingin memilih Paket Member Pengurusan HAKI (Rp 350.000).') }}" target="_blank" rel="noopener" class="btn-pricing-action">
                        Pilih Paket
                    </a>
                </div>
            </div>

            <!-- Paket Normal (Highlight) -->
            <div class="col-lg-5 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="pricing-card pricing-card-highlight">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Paket Normal</h3>
                        <span class="pricing-badge-gold">Penulis Umum &amp; Luar</span>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num"><span>Rp</span>400.000</div>
                        <div class="pricing-price-desc">Jika penulis di luar Penerbit Pena Cendekia</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Pendaftaran Resmi DJKI Kemenkumham RI</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Sertifikat Elektronik HAKI</strong> Ber-barcode Resmi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Berlaku untuk Buku, Modul Ajar, Jurnal, Artikel &amp; Desain</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Bantuan Format Surat Pernyataan &amp; Verifikasi Dokumen</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Tambahan Angka Kredit KUM Dosen Hingga 40 Poin</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Full Pendampingan Sampai Surat Pencatatan Terbit</span></li>
                    </ul>
                    <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Pena Cendekia, saya ingin memilih Paket Normal Pengurusan HAKI (Rp 400.000).') }}" target="_blank" rel="noopener" class="btn-pricing-action btn-pricing-highlight">
                        Pilih Paket
                    </a>
                </div>
            </div>
        </div>

        <!-- Template Download Note & Certificate Preview -->
        <div class="row g-4 mt-4 align-items-center justify-content-center">
            <div class="col-lg-6" data-aos="fade-up">
                <div class="template-info-box h-100 d-flex flex-column justify-content-center">
                    <div class="mb-3">
                        <i class="bi bi-file-earmark-check-fill text-warning" style="font-size: 44px;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Persyaratan &amp; Template Berkas HAKI</h4>
                    <p class="text-muted small mb-4" style="line-height: 1.6;">
                        Untuk persyaratan, ketentuan, dan kelengkapan administrasi Pengurusan HAKI seperti formulir permohonan dan surat pengalihan hak, dapat Anda unduh langsung secara gratis.
                    </p>
                    <div>
                        <a href="{{ route('template.haki') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-semibold">
                            <i class="bi bi-download me-1 text-warning"></i> Ke Menu Download Template HAKI
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                <div class="certificate-showcase-box">
                    <h5 class="fw-bold mb-3 text-dark">
                        <i class="bi bi-patch-check-fill text-warning me-1"></i> Contoh Sertifikat HAKI Resmi
                    </h5>
                    <img src="{{ asset('images/haki-pena.png') }}" alt="Contoh Surat Pencatatan Ciptaan HAKI Kemenkumham Pena Cendekia" class="certificate-img" loading="lazy">
                    <p class="text-muted small mt-3 mb-0">
                        Surat Pencatatan Ciptaan Kementerian Hukum dan Hak Asasi Manusia Republik Indonesia
                    </p>
                </div>
            </div>
        </div>

        <!-- Banner Layanan Eksklusif Pengurusan HAKI -->
        <div class="mt-5 pt-3" data-aos="fade-up">
            <div class="exclusive-banner-box">
                <h3 class="fw-bold mb-2 text-white font-serif" style="font-size: 1.5rem;">
                    Layanan Eksklusif Pengurusan HAKI
                </h3>
                <div class="text-warning fw-semibold mb-3" style="font-size: 1.1rem; letter-spacing: 0.5px;">
                    (Hak Atas Kekayaan Intelektual)
                </div>
                <p class="text-white-50 mx-auto mb-4" style="max-width: 680px; font-size: 15px; line-height: 1.6;">
                    &ldquo;Untuk persyaratan, ketentuan dan kelengkapan Pengurusan HAKI bisa didownload di Menu Download Template HAKI.&rdquo;
                </p>
                <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Pena Cendekia, saya ingin berkonsultasi mengenai pengurusan HAKI untuk naskah saya.') }}" target="_blank" rel="noopener" class="btn-admin-cta">
                    <i class="bi bi-whatsapp me-1"></i> HUBUNGI ADMIN
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
