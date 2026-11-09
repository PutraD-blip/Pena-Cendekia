@extends('layouts.app')

@section('title', 'Penerbitan Buku Resmi & Ber-ISBN — PT. Pena Cendekia Pustaka')

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

    /* Content & Balanced Containers */
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
        font-size: 1.25rem;
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
        font-size: 13px;
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

    /* Step Cards */
    .flow-step-card {
        background: #FFFFFF;
        border: 1.5px solid #E5E7EB;
        border-radius: 16px;
        padding: 24px;
        height: 100%;
        transition: all 0.25s ease;
    }
    .flow-step-card:hover {
        border-color: #F5A623;
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(245, 166, 35, 0.1);
    }
    .flow-step-num {
        font-size: 2.2rem;
        font-weight: 900;
        color: #F5A623;
        line-height: 1;
        margin-bottom: 12px;
    }
    .flow-step-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }
    .flow-step-desc {
        font-size: 13px;
        color: #6B7280;
        line-height: 1.6;
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="service-hero">
    <div class="content-balanced-container">
        <div class="service-hero-kicker">
            <i class="bi bi-patch-check-fill"></i> Layanan Resmi Penerbitan Buku
        </div>
        <h1 class="service-hero-title">
            Paket Penerbitan Buku Resmi &amp; Ber-ISBN
        </h1>
        <p class="service-hero-subtitle">
            Abadikan gagasan berharga Anda menjadi buku cetak berkualitas tinggi. Terdaftar resmi di Perpustakaan Nasional RI (ISBN &amp; Barcode) dan didukung garansi 99% naskah pasti terbit.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Penerbit Pena Cendekia, saya ingin konsultasi mengenai Paket Penerbitan Buku.') }}" target="_blank" rel="noopener" class="btn-hero-cta">
                <i class="bi bi-whatsapp"></i> Konsultasi Sekarang
            </a>
            <a href="{{ route('login') }}" class="btn-hero-secondary">
                <i class="bi bi-upload"></i> Ajukan Naskah Anda
            </a>
        </div>
    </div>
</section>

<!-- Alur Penerbitan -->
<section class="py-5 bg-white border-bottom">
    <div class="content-balanced-container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-kicker-gold mb-1">PROSES MUDAH &amp; TRANSPARAN</span>
            <h2 class="section-title-dark mb-2">Alur Penerbitan di Pena Cendekia</h2>
            <p class="text-muted mx-auto" style="max-width: 650px; font-size: 14.5px;">
                Pendampingan menyeluruh dari naskah mentah hingga menjadi buku fisik dan terdistribusi resmi.
            </p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="50">
                <div class="flow-step-card">
                    <div class="flow-step-num">1.</div>
                    <h3 class="flow-step-title">Pengiriman Naskah</h3>
                    <p class="flow-step-desc">Penulis mengirimkan naskah lengkap melalui sistem registrasi web atau email ke redaksi Penerbit Pena Cendekia.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="flow-step-card">
                    <div class="flow-step-num">2.</div>
                    <h3 class="flow-step-title">Review &amp; MoU Penerbitan</h3>
                    <p class="flow-step-desc">Tim editor mereview kelayakan naskah dan mengirimkan draft Memorandum of Understanding (MoU) sebagai kontrak resmi.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="150">
                <div class="flow-step-card">
                    <div class="flow-step-num">3.</div>
                    <h3 class="flow-step-title">Desain Kover &amp; Tata Letak</h3>
                    <p class="flow-step-desc">Desainer grafis merancang kover estetik serta melayout isi buku sesuai standar UNESCO / A5 yang nyaman dibaca.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="flow-step-card">
                    <div class="flow-step-num">4.</div>
                    <h3 class="flow-step-title">Pengurusan ISBN Perpusnas</h3>
                    <p class="flow-step-desc">Pena Cendekia mengajukan ISBN resmi dan barcode barcode ke Perpustakaan Nasional Republik Indonesia.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="250">
                <div class="flow-step-card">
                    <div class="flow-step-num">5.</div>
                    <h3 class="flow-step-title">Pencetakan Presisi Tinggi</h3>
                    <p class="flow-step-desc">Buku dicetak menggunakan mesin cetak modern offset &amp; digital press dengan kertas bookpaper premium dan jilid lem panas.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="flow-step-card">
                    <div class="flow-step-num">6.</div>
                    <h3 class="flow-step-title">Serah Simpan &amp; Pengiriman</h3>
                    <p class="flow-step-desc">Karya cetak dikirimkan langsung ke alamat penulis dan 3 eksemplar diserahkan ke Perpusnas RI &amp; Dispusipda sesuai UU No. 13 Tahun 2018.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pilihan Paket Penerbitan Buku (6 Paket Resmi) -->
<section class="pricing-section" id="paket">
    <div class="content-balanced-container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="section-kicker-gold mb-2">BIAYA PAKET PENERBITAN</div>
            <h2 class="section-title-dark mb-3">Pilihan Paket Penerbitan Resmi</h2>
            <p class="text-muted mx-auto" style="max-width: 680px; font-size: 15px; line-height: 1.6;">
                Pilih paket yang paling sesuai dengan kebutuhan akademis, promosi kepangkatan, maupun publikasi karya sastra Anda.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- 1. Paket 3 Buku -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="50">
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Paket 3 Buku</h3>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num"><span>Rp</span>750.000</div>
                        <div class="pricing-price-desc">Maksimal 150 Halaman &bull; Standar UNESCO / A5</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Free ISBN &amp; Barcode Resmi Perpusnas</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Desain Kover Menarik &amp; Elegan</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Layout Berstandar Tinggi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>e-Sertifikat Penulis</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 3 Eksemplar</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Bukti Serah Simpan Karya Cetak</span></li>
                    </ul>
                    <a href="{{ route('author.submissions.create', ['service' => 'penerbitan', 'package' => 'paket-3']) }}" class="btn-pricing-action">
                        Pilih Paket
                    </a>
                </div>
            </div>

            <!-- 2. Paket 5 Buku -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Paket 5 Buku</h3>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num"><span>Rp</span>800.000</div>
                        <div class="pricing-price-desc">Maksimal 150 Halaman &bull; Standar UNESCO / A5</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Free ISBN &amp; Barcode Resmi Perpusnas</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Desain Kover Menarik &amp; Elegan</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Layout Berstandar Tinggi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>e-Sertifikat Penulis</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 5 Eksemplar</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Bukti Serah Simpan Karya Cetak</span></li>
                    </ul>
                    <a href="{{ route('author.submissions.create', ['service' => 'penerbitan', 'package' => 'paket-5']) }}" class="btn-pricing-action">
                        Pilih Paket
                    </a>
                </div>
            </div>

            <!-- 3. Paket 10 Buku (Highlight / Paling Populer) -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="150">
                <div class="pricing-card pricing-card-highlight">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Paket 10 Buku</h3>
                        <span class="pricing-badge-gold">Paling Populer</span>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num"><span>Rp</span>1.000.000</div>
                        <div class="pricing-price-desc">Maksimal 150 Halaman &bull; Standar UNESCO / A5</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Free ISBN &amp; Barcode Resmi Perpusnas</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Desain Kover Kreatif &amp; Eksklusif</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Layout Berstandar Tinggi &amp; Proofing</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>e-Sertifikat Penulis</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 10 Eksemplar</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Bukti Serah Simpan Karya Cetak</span></li>
                    </ul>
                    <a href="{{ route('author.submissions.create', ['service' => 'penerbitan', 'package' => 'paket-10']) }}" class="btn-pricing-action btn-pricing-highlight">
                        Pilih Paket Populer
                    </a>
                </div>
            </div>

            <!-- 4. Paket 25 Buku (Rekomendasi) -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Paket 25 Buku</h3>
                        <span class="pricing-badge-gold">Rekomendasi</span>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num"><span>Rp</span>1.650.000</div>
                        <div class="pricing-price-desc">Maksimal 150 Halaman &bull; Standar UNESCO / A5</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Free ISBN &amp; Barcode Resmi Perpusnas</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Desain Kover Profesional</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Layout Berstandar Tinggi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>e-Sertifikat Penulis</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 25 Eksemplar</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Diskon Pengurusan HAKI Rp 50.000</span></li>
                    </ul>
                    <a href="{{ route('author.submissions.create', ['service' => 'penerbitan', 'package' => 'paket-25']) }}" class="btn-pricing-action">
                        Pilih Paket
                    </a>
                </div>
            </div>

            <!-- 5. Paket 50 Buku -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="250">
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Paket 50 Buku</h3>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num"><span>Rp</span>2.650.000</div>
                        <div class="pricing-price-desc">Maksimal 150 Halaman &bull; Standar UNESCO / A5</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Free ISBN &amp; Barcode Resmi Perpusnas</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Desain Kover Eksklusif</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Layout Berstandar Tinggi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>e-Sertifikat Penulis</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 50 Eksemplar</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Diskon Pengurusan HAKI Rp 100.000</span></li>
                    </ul>
                    <a href="{{ route('author.submissions.create', ['service' => 'penerbitan', 'package' => 'paket-50']) }}" class="btn-pricing-action">
                        Pilih Paket
                    </a>
                </div>
            </div>

            <!-- 6. Paket 100 Buku -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Paket 100 Buku</h3>
                        <span class="pricing-badge-gold">Free HAKI</span>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num"><span>Rp</span>4.500.000</div>
                        <div class="pricing-price-desc">Maksimal 150 Halaman &bull; Standar UNESCO / A5</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Free ISBN &amp; Barcode Resmi Perpusnas</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Desain Kover (2 Konsep Pilihan)</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Layout Berstandar Tinggi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>e-Sertifikat Penulis</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 100 Eksemplar</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Free HAKI</strong> Kemenkumham Resmi</span></li>
                    </ul>
                    <a href="{{ route('author.submissions.create', ['service' => 'penerbitan', 'package' => 'paket-100']) }}" class="btn-pricing-action">
                        Pilih Paket 100 Buku
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
                <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Pena Cendekia, saya tertarik dengan Layanan Eksklusif Penerbitan Cetak 1000 eksemplar.') }}" target="_blank" rel="noopener" class="btn-admin-cta">
                    <i class="bi bi-whatsapp me-1"></i> HUBUNGI ADMIN
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
