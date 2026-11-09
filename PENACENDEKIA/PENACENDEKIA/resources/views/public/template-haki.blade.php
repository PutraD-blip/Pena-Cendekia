@extends('layouts.app')

@section('title', 'Template Hak Cipta & Dokumen HAKI — PT. Pena Cendekia Pustaka')

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

    /* Syarat Step Card */
    .syarat-step-card {
        background: #FFFFFF;
        border: 1.5px solid #E5E7EB;
        border-radius: 16px;
        padding: 24px 20px;
        height: 100%;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
    }
    .syarat-step-card:hover {
        border-color: #F5A623;
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(245, 166, 35, 0.12);
    }
    .syarat-number {
        color: #F5A623;
        font-weight: 900;
        font-size: 2rem;
        line-height: 1;
        margin-bottom: 10px;
    }
    .syarat-text {
        color: #4B5563;
        font-size: 13.5px;
        line-height: 1.6;
        margin-bottom: 0;
    }
    .syarat-text strong {
        color: #111827;
    }

    /* Download Box Cards (Same as Paket Penerbitan) */
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
    .template-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.25rem;
        font-weight: 800;
        color: #111827;
        margin-top: 10px;
        margin-bottom: 6px;
    }
    .template-format {
        font-size: 13px;
        font-weight: 600;
        color: #6B7280;
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

    /* Info Bar Rekening & Email */
    .account-email-box {
        background: #FFFDF8;
        border: 1.5px solid rgba(245, 166, 35, 0.4);
        border-radius: 18px;
        padding: 28px;
        margin-top: 36px;
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
            <i class="bi bi-shield-check"></i> Hak atas Kekayaan Intelektual (HAKI)
        </div>
        <h1 class="template-hero-title">
            Unduh Template Hak Cipta
        </h1>
        <p class="template-hero-subtitle">
            Untuk mempermudah dan mempercepat permohonan Hak Cipta di Kementerian Hukum dan HAM Republik Indonesia, pemohon dimohon untuk memenuhi 9 persyaratan dan mengisi form-form resmi yang sudah kami sediakan di bawah ini.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Penerbit Pena Cendekia, saya ingin konsultasi mengenai persyaratan permohonan Hak Cipta / HAKI.') }}" target="_blank" rel="noopener" class="btn-hero-cta">
                <i class="bi bi-whatsapp"></i> Konsultasi Sekarang
            </a>
            <a href="{{ route('layanan.hki') }}" class="btn-hero-secondary">
                <i class="bi bi-tags"></i> Lihat Biaya Paket HAKI
            </a>
        </div>
    </div>
</section>

<!-- Section: Syarat Permohonan Hak Cipta (9 Poin) -->
<section class="py-5 bg-white border-bottom">
    <div class="content-balanced-container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-kicker-gold mb-1">KETENTUAN &amp; KELENGKAPAN BERKAS</span>
            <h2 class="section-title-dark mb-2">9 Syarat Permohonan Hak Cipta</h2>
            <p class="text-muted mx-auto" style="max-width: 680px; font-size: 15px;">
                Pastikan dokumen dan data pendukung berikut telah disiapkan sebelum mengajukan permohonan pencatatan ciptaan resmi.
            </p>
        </div>

        <div class="row g-4">
            <!-- 1 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="50">
                <div class="syarat-step-card">
                    <span class="syarat-number">01.</span>
                    <p class="syarat-text">
                        Pemohon menyiapkan/melampirkan <strong>softfile contoh ciptaan</strong> (format PDF, JPEG, MP4, atau MP3). Khusus untuk karya buku, softfile ciptaan berisi Sampul Depan-Belakang, Daftar Isi, dan Isi Konten.
                    </p>
                </div>
            </div>

            <!-- 2 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="syarat-step-card">
                    <span class="syarat-number">02.</span>
                    <p class="syarat-text">
                        Pemohon mengisi <strong>Form Isian Pendaftaran Hak Cipta</strong> dengan mencantumkan Nama Lengkap, NIK KTP, Kewarganegaraan, Alamat Domisili, No. HP aktif, serta alamat e-Mail.
                    </p>
                </div>
            </div>

            <!-- 3 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="150">
                <div class="syarat-step-card">
                    <span class="syarat-number">03.</span>
                    <p class="syarat-text">
                        Melampirkan <strong>bukti kewarganegaraan</strong> pencipta dan pemegang Hak Cipta berupa scan/foto Kartu Tanda Penduduk (KTP) atau paspor yang masih berlaku dalam format (PDF).
                    </p>
                </div>
            </div>

            <!-- 4 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="syarat-step-card">
                    <span class="syarat-number">04.</span>
                    <p class="syarat-text">
                        Apabila permohonan atas nama badan hukum/institusi, maka pada surat permohonannya harus dilampirkan <strong>turunan resmi akta pendirian</strong> badan hukum tersebut.
                    </p>
                </div>
            </div>

            <!-- 5 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="250">
                <div class="syarat-step-card">
                    <span class="syarat-number">05.</span>
                    <p class="syarat-text">
                        Apabila ciptaan tersebut telah dipindahkan, wajib melampirkan <strong>bukti pemindahan hak cipta</strong> yang ditandatangani oleh para pihak di atas meterai Rp 10.000.
                    </p>
                </div>
            </div>

            <!-- 6 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="syarat-step-card">
                    <span class="syarat-number">06.</span>
                    <p class="syarat-text">
                        Semua pemegang Hak Cipta wajib mengisi dan menandatangani <strong>Surat Pernyataan Keaslian Karya</strong> di atas meterai Rp 10.000 yang menyatakan karya bebas dari plagiarisme.
                    </p>
                </div>
            </div>

            <!-- 7 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="350">
                <div class="syarat-step-card">
                    <span class="syarat-number">07.</span>
                    <p class="syarat-text">
                        Form Isian Pendaftaran Hak Cipta, Surat Pernyataan Keaslian Karya, dan Surat Pengalihan Hak Cipta dapat Anda <strong>unduh langsung</strong> pada tautan kartu di bawah ini.
                    </p>
                </div>
            </div>

            <!-- 8 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="syarat-step-card">
                    <span class="syarat-number">08.</span>
                    <p class="syarat-text">
                        Pemohon melakukan pembayaran biaya pengurusan melalui transfer ke rekening resmi: <strong>Bank BCA No. Rek: 0892290375</strong> a.n. <strong>Ibnus Shofi</strong>.
                    </p>
                </div>
            </div>

            <!-- 9 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="450">
                <div class="syarat-step-card">
                    <span class="syarat-number">09.</span>
                    <p class="syarat-text">
                        Semua berkas permohonan dan bukti pembayaran dikirimkan via email: <strong>pena_cendekia@yahoo.com</strong> cc: <strong>penacendekiasby@gmail.com</strong> dengan Subjek: <code>HAKI_Nama Lengkap_Judul</code>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Section: Template Permohonan Hak Cipta (3 Cards) -->
<section class="py-5" style="background-color: #FBF9F5;" id="unduh-berkas">
    <div class="content-balanced-container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-kicker-gold mb-1">BERKAS FORMULIR RESMI</span>
            <h2 class="section-title-dark mb-2">Template Permohonan Hak Cipta</h2>
            <p class="text-muted mx-auto" style="max-width: 680px; font-size: 15px;">
                Unduh formulir resmi di bawah ini, isi data sesuai petunjuk, dan bubuhkan meterai 10.000 pada dokumen pernyataan.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Card 1: Form Isian Hak Cipta -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="50">
                <div class="template-box-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-light text-dark border">Formulir Pendaftaran</span>
                        <span class="template-badge-pill">Format Word</span>
                    </div>
                    <h3 class="template-title">Form Isian Hak Cipta</h3>
                    <div class="template-format">
                        <i class="bi bi-file-earmark-word text-warning me-1"></i> Format Word Document (.docx)
                    </div>
                    <hr class="template-divider">
                    <ul class="template-features">
                        <li><i class="bi bi-check2-circle"></i><span>Kelengkapan biodata pencipta &amp; pemegang hak</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Mencantumkan tempat &amp; tanggal pertama diumumkan</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Uraian ringkas sinopsis / deskripsi ciptaan</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Format baku sesuai standar DJKI Kemenkumham RI</span></li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ asset('downloads/form-isian-hak-cipta.docx') }}" download class="btn-template-download">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i> Download Form (.docx)
                        </a>
                        <a href="https://docs.google.com/document/d/1mhW0iQCF87RybsrBG4nALvQWl57kaleb/edit" target="_blank" rel="noopener" class="btn-gdrive-link">
                            <i class="bi bi-box-arrow-up-right"></i> Buka di Google Docs
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Surat Pernyataan Hak Cipta (Highlight) -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="template-box-card template-box-highlight">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-warning text-dark fw-bold">Wajib Meterai</span>
                        <span class="template-badge-pill">Format Dokumen</span>
                    </div>
                    <h3 class="template-title">Surat Pernyataan Hak Cipta</h3>
                    <div class="template-format">
                        <i class="bi bi-patch-check text-warning me-1"></i> Keaslian Karya &bull; Meterai 10.000
                    </div>
                    <hr class="template-divider">
                    <ul class="template-features">
                        <li><i class="bi bi-check2-circle"></i><span>Pernyataan keaslian dan orisinalitas ciptaan</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Jaminan tidak melanggar hak cipta pihak ketiga</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Diisi dan ditandatangani <strong>semua pencipta</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Ditandatangani di atas meterai <strong>Rp 10.000</strong></span></li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ asset('downloads/surat-pernyataan-hak-cipta.docx') }}" download class="btn-template-download btn-download-highlight">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i> Download Surat (.docx)
                        </a>
                        <a href="https://docs.google.com/document/d/1mhQrLKIVESY2vE7pRz9CVXRINmrHkBn4/edit" target="_blank" rel="noopener" class="btn-gdrive-link">
                            <i class="bi bi-box-arrow-up-right"></i> Buka di Google Docs
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 3: Surat Pengalihan Hak Cipta -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="150">
                <div class="template-box-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-light text-dark border">Opsional Pengalihan</span>
                        <span class="template-badge-pill">Format Dokumen</span>
                    </div>
                    <h3 class="template-title">Surat Pengalihan Hak Cipta</h3>
                    <div class="template-format">
                        <i class="bi bi-arrow-left-right text-warning me-1"></i> Pengalihan Individu / Lembaga
                    </div>
                    <hr class="template-divider">
                    <ul class="template-features">
                        <li><i class="bi bi-check2-circle"></i><span>Diisi apabila hak cipta dialihkan ke institusi/pihak lain</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Mencakup data pihak pertama (pencipta) &amp; pihak kedua</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Ketentuan klausul pengalihan hak komersial/ekonomi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Ditandatangani kedua belah pihak di atas meterai 10.000</span></li>
                    </ul>
                    <div class="mt-auto">
                        <a href="{{ asset('downloads/surat-pengalihan-hak-cipta.docx') }}" download class="btn-template-download">
                            <i class="bi bi-file-earmark-arrow-down-fill"></i> Download Surat (.docx)
                        </a>
                        <a href="https://docs.google.com/document/d/1mgdAeH0-M01DUAKMnYFMnDlG8hNYNMdn/edit" target="_blank" rel="noopener" class="btn-gdrive-link">
                            <i class="bi bi-box-arrow-up-right"></i> Buka di Google Docs
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terhubung dengan Halaman Pengurusan HAKI -->
        <div class="account-email-box" data-aos="fade-up">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-warning text-dark fw-bold">Jasa Pengurusan HAKI</span>
                        <span class="text-muted small">Pena Cendekia Official</span>
                    </div>
                    <h4 class="fw-bold text-dark font-serif mb-2">
                        Ingin Pengurusan Hak Cipta yang Praktis Tanpa Repot?
                    </h4>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        Tim Pena Cendekia siap mendampingi verifikasi kelengkapan berkas, pendaftaran ke sistem DJKI Kemenkumham RI, hingga <strong>Surat Pencatatan Hak Cipta resmi diterbitkan</strong>. Mulai Rp 350.000 untuk Penulis Member dan Rp 400.000 untuk Penulis Umum.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end text-center">
                    <a href="{{ route('layanan.hki') }}" class="btn btn-dark rounded-pill px-4 py-2 fw-semibold">
                        <i class="bi bi-shield-check text-warning me-1"></i> Ke Jasa Pengurusan HAKI
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
                <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Penerbit Pena Cendekia, saya ingin bertanya tentang pengurusan HAKI & Layanan Eksklusif.') }}" target="_blank" rel="noopener" class="btn-admin-cta">
                    <i class="bi bi-whatsapp me-1"></i> HUBUNGI ADMIN
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
