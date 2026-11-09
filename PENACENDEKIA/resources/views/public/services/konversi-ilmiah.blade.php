@extends('layouts.app')

@section('title', 'Konversi Karya Tulis Ilmiah — PT. Pena Cendekia Pustaka')

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

    /* Alur Flow Step Cards */
    .flow-step-card {
        background: #FFFFFF;
        border: 1.5px solid #E5E7EB;
        border-radius: 16px;
        padding: 26px 24px;
        height: 100%;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
    }
    .flow-step-card:hover {
        border-color: #F5A623;
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(245, 166, 35, 0.12);
    }
    .flow-step-num {
        font-size: 2.2rem;
        font-weight: 900;
        color: #F5A623;
        line-height: 1;
        margin-bottom: 12px;
    }
    .flow-step-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 10px;
    }
    .flow-step-desc {
        font-size: 13.5px;
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
        font-size: 2.2rem;
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
        font-size: 12px;
        color: #6B7280;
        margin-top: 4px;
        font-weight: 500;
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
        gap: 11px;
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
    .pricing-spec-box {
        background: #F9FAFB;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 12px;
        color: #4B5563;
        margin-top: 6px;
        line-height: 1.5;
        border: 1px solid #E5E7EB;
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
            <i class="bi bi-mortarboard-fill"></i> Konversi Karya Tulis Ilmiah
        </div>
        <h1 class="service-hero-title">
            Konversi Karya Tulis Ilmiah Jadi Buku Ber-ISBN
        </h1>
        <p class="service-hero-subtitle">
            Prosesnya sangat cepat dan mudah. Penulis cukup mengirimkan filenya saja (Skripsi, Tesis, Disertasi, atau Laporan Penelitian), selebihnya untuk konversi, editing, desain kover, layout, dan pengurusan ISBN dari Tim Pena Cendekia yang memproses sampai menjadi buku dicetak dalam waktu 1 - 6 Minggu.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Penerbit Pena Cendekia, saya ingin konsultasi konversi karya ilmiah menjadi buku.') }}" target="_blank" rel="noopener" class="btn-hero-cta">
                <i class="bi bi-whatsapp"></i> Konsultasi Sekarang
            </a>
            <a href="{{ route('login') }}" class="btn-hero-secondary">
                <i class="bi bi-upload"></i> Ajukan Naskah Konversi
            </a>
        </div>
    </div>
</section>

<!-- Alur Konversi Karya Tulis Ilmiah -->
<section class="py-5 bg-white border-bottom">
    <div class="content-balanced-container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-kicker-gold mb-1">LANGKAH SISTEMATIS</span>
            <h2 class="section-title-dark mb-2">Alur Konversi Karya Tulis Ilmiah di Penerbit Pena Cendekia</h2>
            <p class="text-muted mx-auto" style="max-width: 650px; font-size: 14.5px;">
                Tahapan profesional untuk mengubah laporan penelitian dan artikel ilmiah menjadi monograf atau buku referensi berbobot tinggi.
            </p>
        </div>

        <div class="row g-4">
            <!-- Step 1 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="50">
                <div class="flow-step-card">
                    <div class="flow-step-num">1.</div>
                    <h3 class="flow-step-title">Reorganisasi Kerangka Naskah</h3>
                    <p class="flow-step-desc">
                        Ubah struktur artikel ilmiah, seperti direktori, kalimat, paragraf, dan logika kalimat, tanpa harus mengubah makna asli menjadi monograf atau buku referensi.
                    </p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="flow-step-card">
                    <div class="flow-step-num">2.</div>
                    <h3 class="flow-step-title">Edit Naskah</h3>
                    <p class="flow-step-desc">
                        Merubah tatanan bahasa, perbaikan typo, dan penyelarasan bahasa agar sesuai kaidah bahasa Indonesia yang benar.
                    </p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="150">
                <div class="flow-step-card">
                    <div class="flow-step-num">3.</div>
                    <h3 class="flow-step-title">Proofreading</h3>
                    <p class="flow-step-desc">
                        Pengeditan akhir naskah supaya selaras dengan PUEBI (Penulisan Umum Ejaan Bahasa Indonesia) atau dulu disebut dengan EYD (Ejaan Yang Disempurnakan).
                    </p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="flow-step-card">
                    <div class="flow-step-num">4.</div>
                    <h3 class="flow-step-title">Feedback</h3>
                    <p class="flow-step-desc">
                        Setelah selesai diedit, penulis memiliki hak untuk memberikan masukan terhadap naskah yang telah diproses oleh Editor Pena Cendekia.
                    </p>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="250">
                <div class="flow-step-card">
                    <div class="flow-step-num">5.</div>
                    <h3 class="flow-step-title">Penerbitan Buku</h3>
                    <p class="flow-step-desc">
                        Jika penulis setuju dengan naskah yang sudah diedit, naskah akan dilayout, dibuatkan kover, dan diajukan ISBN ke Perpustakaan Nasional Republik Indonesia.
                    </p>
                </div>
            </div>

            <!-- Step 6 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="flow-step-card">
                    <div class="flow-step-num">6.</div>
                    <h3 class="flow-step-title">Pendaftaran Google Books</h3>
                    <p class="flow-step-desc">
                        Naskah yang sudah terbit atau keluar ISBN dari perpusnas akan didaftarkan ke Google Books, agar buku dan penulis dapat diindeks oleh Google Scholars.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Biaya Konversi Karya Tulis Ilmiah (3 Paket Sesuai SS 1 & Desain Paket Penerbitan) -->
<section class="pricing-section" id="biaya-konversi">
    <div class="content-balanced-container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="section-kicker-gold mb-2">BIAYA PAKET KONVERSI</div>
            <h2 class="section-title-dark mb-3">Biaya Konversi Karya Tulis Ilmiah</h2>
            <p class="text-muted mx-auto" style="max-width: 680px; font-size: 15px; line-height: 1.6;">
                Paket all-in-one lengkap mulai dari penataan kerangka, editing, layout, ISBN, hingga buku cetak dan sertifikat legalitas HAKI.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Paket Premium -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="50">
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Paket Premium</h3>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num"><span>Rp</span>2.500.000</div>
                        <div class="pricing-price-desc">Solusi Cepat Publikasi Hasil Penelitian</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Konversi Artikel Ilmiah</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Editing Ringan &amp; PUEBI</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Free ISBN</strong> Resmi Perpusnas RI</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Layout Berstandar Tinggi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>E-Sertifikat Penulis</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 10 eksemplar</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Sertifikat HAKI</strong> Resmi</span></li>
                    </ul>
                    <div class="pricing-spec-box mb-4">
                        <i class="bi bi-info-circle text-warning me-1"></i> <strong>Spesifikasi:</strong> Standar Halaman 150, Ukuran A4, Margin 3433, Spasi 1,5 Times New Roman 12.
                    </div>
                    <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Pena Cendekia, saya ingin memilih Paket Konversi Karya Ilmiah: Paket Premium (Rp 2.500.000).') }}" target="_blank" rel="noopener" class="btn-pricing-action">
                        Pilih Paket
                    </a>
                </div>
            </div>

            <!-- Paket Pertalite (Popular / Rekomendasi) -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="pricing-card pricing-card-highlight">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Paket Pertalite</h3>
                        <span class="pricing-badge-gold">Paling Populer</span>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num"><span>Rp</span>3.800.000</div>
                        <div class="pricing-price-desc">Pilihan Favorit Dosen &amp; Mahasiswa Pascasarjana</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Konversi Artikel Ilmiah</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Editing Sedang</strong> &amp; Struktur Kalimat</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Free ISBN</strong> Resmi Perpusnas RI</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Layout Berstandar Tinggi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>E-Sertifikat Penulis</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 25 eksemplar</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Sertifikat HAKI</strong> Resmi</span></li>
                    </ul>
                    <div class="pricing-spec-box mb-4">
                        <i class="bi bi-info-circle text-warning me-1"></i> <strong>Spesifikasi:</strong> Standar Halaman 200, Ukuran A4, Margin 3433, Spasi 1,5 Times New Roman 12.
                    </div>
                    <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Pena Cendekia, saya ingin memilih Paket Konversi Karya Ilmiah: Paket Pertalite (Rp 3.800.000).') }}" target="_blank" rel="noopener" class="btn-pricing-action btn-pricing-highlight">
                        Pilih Paket
                    </a>
                </div>
            </div>

            <!-- Paket Pertamax -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="150">
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Paket Pertamax</h3>
                        <span class="pricing-badge-gold">Edisi Lengkap</span>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num"><span>Rp</span>5.000.000</div>
                        <div class="pricing-price-desc">Pendampingan Intensif &amp; Disertasi / Riset Besar</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Konversi Artikel Ilmiah</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Editing Berat</strong> &amp; Restrukturisasi Bahasa</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Free ISBN</strong> Resmi Perpusnas RI</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Layout Berstandar Tinggi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>E-Sertifikat Penulis</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 50 eksemplar</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Sertifikat HAKI</strong> Resmi</span></li>
                    </ul>
                    <div class="pricing-spec-box mb-4">
                        <i class="bi bi-info-circle text-warning me-1"></i> <strong>Spesifikasi:</strong> Standar Halaman 250, Ukuran A4, Margin 3433, Spasi 1,5 Times New Roman 12.
                    </div>
                    <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Pena Cendekia, saya ingin memilih Paket Konversi Karya Ilmiah: Paket Pertamax (Rp 5.000.000).') }}" target="_blank" rel="noopener" class="btn-pricing-action">
                        Pilih Paket
                    </a>
                </div>
            </div>
        </div>

        <!-- Banner Layanan Eksklusif Anggota IKAPI (Sesuai SS 1) -->
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
                <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Pena Cendekia, saya tertarik dengan Layanan Eksklusif Konversi Karya Ilmiah.') }}" target="_blank" rel="noopener" class="btn-admin-cta">
                    <i class="bi bi-whatsapp me-1"></i> HUBUNGI ADMIN
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
