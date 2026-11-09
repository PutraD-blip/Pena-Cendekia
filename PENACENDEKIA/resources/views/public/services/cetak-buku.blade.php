@extends('layouts.app')

@section('title', 'Cetak Buku Berkualitas & Percetakan Modern — PT. Pena Cendekia Pustaka')

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

    /* Machine Showcase Grid */
    .machine-card {
        background: #FFFFFF;
        border: 1.5px solid #E5E7EB;
        border-radius: 18px;
        padding: 24px;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-align: center;
        box-shadow: 0 4px 16px rgba(17, 24, 39, 0.03);
    }
    .machine-card:hover {
        transform: translateY(-6px);
        border-color: #F5A623;
        box-shadow: 0 16px 36px rgba(245, 166, 35, 0.14);
    }
    .machine-img-box {
        background: #F9FAFB;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 18px;
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .machine-img-box img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.35s ease;
    }
    .machine-card:hover .machine-img-box img {
        transform: scale(1.05);
    }
    .machine-title {
        font-size: 1.15rem;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .machine-desc {
        font-size: 13px;
        color: #6B7280;
        line-height: 1.55;
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
        font-size: 2rem;
        font-weight: 800;
        color: #111827;
        line-height: 1.15;
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
            <i class="bi bi-printer-fill"></i> Percetakan Modern &amp; Presisi
        </div>
        <h1 class="service-hero-title">
            Cetak Buku Berkualitas Tinggi &amp; Cepat
        </h1>
        <p class="service-hero-subtitle">
            Kami melayani cetak Buku, Brosur, Majalah, dan Kalender dengan kualitas berstandar tinggi dan waktu yang cepat. Harga ekonomis dan hasil maksimal didukung lini mesin percetakan canggih.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Percetakan Pena Cendekia, saya ingin konsultasi harga cetak buku/brosur/kalender.') }}" target="_blank" rel="noopener" class="btn-hero-cta">
                <i class="bi bi-whatsapp"></i> Konsultasi Sekarang
            </a>
            <a href="#mesin-percetakan" class="btn-hero-secondary">
                <i class="bi bi-gear-wide-connected"></i> Lihat Mesin Cetak Kami
            </a>
        </div>
    </div>
</section>

<!-- Mesin Percetakan Kami (Sesuai SS 2) -->
<section class="py-5 bg-white border-bottom" id="mesin-percetakan">
    <div class="content-balanced-container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-kicker-gold mb-1">TEKNOLOGI MUTAKHIR</span>
            <h2 class="section-title-dark mb-2">Mesin Percetakan Kami</h2>
            <p class="text-muted mx-auto" style="max-width: 680px; font-size: 15px;">
                Kami didukung dengan mesin cetak yang modern, dengan akurasi warna yang bagus untuk menghasilkan cetakan yang tajam, presisi, dan awet.
            </p>
        </div>

        <div class="row g-4">
            <!-- Machine 1: KOMORI LITHRONE 420 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="50">
                <div class="machine-card">
                    <div class="machine-img-box">
                        <img src="{{ asset('images/machines/komori-420.webp') }}" alt="KOMORI LITHRONE 420" loading="lazy">
                    </div>
                    <h3 class="machine-title">KOMORI LITHRONE 420</h3>
                    <p class="machine-desc">Mesin cetak offset 4-warna berkecepatan tinggi dengan registrasi warna super akurat untuk kover dan isi buku berkualitas prima.</p>
                </div>
            </div>

            <!-- Machine 2: HORIZON Perfect Binder BQ-270V -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="machine-card">
                    <div class="machine-img-box">
                        <img src="{{ asset('images/machines/horizon-binder.webp') }}" alt="HORIZON Perfect Binder BQ-270V" loading="lazy">
                    </div>
                    <h3 class="machine-title">HORIZON PERFECT BINDER</h3>
                    <p class="machine-desc">Mesin jilid lem panas (perfect binding) otomatis dengan daya rekat ekstra kuat, menghasilkan punggung buku yang rapi dan tahan lama.</p>
                </div>
            </div>

            <!-- Machine 3: KONICA MINOLTA Digital Press -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="150">
                <div class="machine-card">
                    <div class="machine-img-box">
                        <img src="{{ asset('images/machines/konica-c8000.webp') }}" alt="KONICA MINOLTA Digital Press" loading="lazy">
                    </div>
                    <h3 class="machine-title">DIGITAL PRESS C8000</h3>
                    <p class="machine-desc">Unit cetak digital beresolusi ultra-tinggi untuk pesanan Print-on-Demand (POD), cetak contoh naskah, dan buku kuantitas fleksibel.</p>
                </div>
            </div>

            <!-- Machine 4: POLAR Cutting Machine -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="machine-card">
                    <div class="machine-img-box">
                        <img src="{{ asset('images/machines/polar.webp') }}" alt="POLAR High Speed Cutter" loading="lazy">
                    </div>
                    <h3 class="machine-title">POLAR HIGH-SPEED CUTTER</h3>
                    <p class="machine-desc">Mesin pemotong kertas otomatis presisi tinggi standar industri Jerman untuk menghasilkan potongan buku yang sangat rapi dan presisi.</p>
                </div>
            </div>

            <!-- Machine 5: KOMORI LITHRONE S 429 -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="250">
                <div class="machine-card">
                    <div class="machine-img-box">
                        <img src="{{ asset('images/machines/komori-429.webp') }}" alt="KOMORI LITHRONE S 429" loading="lazy">
                    </div>
                    <h3 class="machine-title">KOMORI LITHRONE S 429</h3>
                    <p class="machine-desc">Mesin offset modern generasi mutakhir untuk cetak oplah besar, majalah full color, dan kalender dengan konsistensi pigmen warna sempurna.</p>
                </div>
            </div>

            <!-- Machine 6: WOHLENBERG Precision Cutter -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="machine-card">
                    <div class="machine-img-box">
                        <img src="{{ asset('images/machines/wohlenberg.webp') }}" alt="WOHLENBERG Heavy Duty Cutter" loading="lazy">
                    </div>
                    <h3 class="machine-title">WOHLENBERG HEAVY DUTY</h3>
                    <p class="machine-desc">Mesin potong hidrolik kelas berat untuk perapihan buku tebal, antologi, monograf, dan dokumen institusi bertiras besar.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pilihan Spesifikasi Cetak Buku (Desain Paket Penerbitan) -->
<section class="pricing-section" id="spesifikasi-cetak">
    <div class="content-balanced-container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="section-kicker-gold mb-2">FORMAT JILID &amp; CETAK</div>
            <h2 class="section-title-dark mb-3">Pilihan Format Cetak &amp; Sampul</h2>
            <p class="text-muted mx-auto" style="max-width: 680px; font-size: 15px; line-height: 1.6;">
                Sesuaikan spesifikasi jilid, ketebalan kertas, dan finishing sampul sesuai peruntukan karya dan segmen pembaca Anda.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Format 1: Soft Cover -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="50">
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Soft Cover Berkualitas</h3>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num">Format Standar</div>
                        <div class="pricing-price-desc">Cocok untuk Buku Ajar, Novel, Puisi, &amp; Bacaan Umum</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Kover <strong>Art Carton 260 gr</strong> Tebal &amp; Eksklusif</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Finishing <strong>Laminasi Doff atau Glossy</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Kertas Isi Bookpaper 57/72 gr atau HVS 70/80 gr</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Jilid Lem Panas (Perfect Binding) Kuat</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Ukuran Fleksibel (UNESCO 15.5x23 cm / A5 / B5)</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Minimal Order Fleksibel (Mulai 10 Eksemplar)</span></li>
                    </ul>
                    <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Percetakan Pena Cendekia, saya ingin konsultasi cetak buku Soft Cover.') }}" target="_blank" rel="noopener" class="btn-pricing-action">
                        Konsultasi Cetak Soft Cover
                    </a>
                </div>
            </div>

            <!-- Format 2: Hard Cover (Highlight) -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="pricing-card pricing-card-highlight">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Hard Cover Mewah</h3>
                        <span class="pricing-badge-gold">Edisi Eksklusif</span>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num">Format Premium</div>
                        <div class="pricing-price-desc">Cocok untuk Buku Referensi, Antologi, Biografi, &amp; Koleksi</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Papan Karton Tebal <strong>Greyboard No. 30/40</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Lapisan Art Paper 150 gr + Foil Emas / Emboss</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Jilid <strong>Jahit Benang + Lem Panas</strong> (Smyth Sewn)</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Dilengkapi <strong>Pita Pembatas Buku</strong> Elegan</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Kertas Isi Bookpaper Premium / Art Paper Mewah</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Punggung Buku Kokoh &amp; Tahan Puluhan Tahun</span></li>
                    </ul>
                    <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Percetakan Pena Cendekia, saya ingin konsultasi cetak buku Hard Cover.') }}" target="_blank" rel="noopener" class="btn-pricing-action btn-pricing-highlight">
                        Konsultasi Cetak Hard Cover
                    </a>
                </div>
            </div>

            <!-- Format 3: Cetak Komersial & Institusi -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="150">
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Brosur, Majalah, Kalender</h3>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num">Cetak Promosi</div>
                        <div class="pricing-price-desc">Cocok untuk Kampus, Lembaga, Sekolah, &amp; Perusahaan</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Cetak Majalah, Buletin, &amp; Jurnal Ilmiah</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Brosur Lipat 2 / Lipat 3 Full Color Tajam</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Kalender Dinding &amp; Kalender Meja Dudukan Linen</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Finishing Spiral Kawat, Klem Seng, atau UV Varnish</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Kapasitas Cetak Hingga Puluhan Ribu Eksemplar</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Harga Khusus Grosir untuk Mitra Institusi</span></li>
                    </ul>
                    <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Percetakan Pena Cendekia, saya ingin konsultasi cetak brosur/majalah/kalender.') }}" target="_blank" rel="noopener" class="btn-pricing-action">
                        Konsultasi Cetak Promosi
                    </a>
                </div>
            </div>
        </div>

        <!-- Banner Layanan Eksklusif Anggota IKAPI (Sesuai SS 2) -->
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
                <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Percetakan Pena Cendekia, saya tertarik dengan promo Layanan Cetak 1000 eksemplar.') }}" target="_blank" rel="noopener" class="btn-admin-cta">
                    <i class="bi bi-whatsapp me-1"></i> HUBUNGI ADMIN
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
