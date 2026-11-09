@extends('layouts.app')

@section('title', 'Printing & Offset — Tarif Cetak PoD Buku A5 & B5 — PT. Pena Cendekia Pustaka')

@push('styles')
<style>
    .printing-hero {
        position: relative;
        background: linear-gradient(135deg, rgba(14, 18, 24, 0.90) 0%, rgba(20, 24, 32, 0.85) 50%, rgba(10, 14, 18, 0.92) 100%),
                    url('{{ asset('images/hero-bg.jpg') }}') center/cover no-repeat;
        padding-top: clamp(100px, 13vh, 140px);
        padding-bottom: clamp(60px, 8vh, 90px);
        text-align: center;
        color: #ffffff;
    }
    .printing-hero-kicker {
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
    .printing-hero-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: clamp(2rem, 3.4vw, 3.1rem);
        color: #ffffff;
        margin-bottom: 1rem;
        line-height: 1.25;
    }
    .printing-hero-subtitle {
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

    /* Calculator Box (Modern Dark & Gold Card) */
    .calc-card {
        background: linear-gradient(135deg, #1A1F2C 0%, #111827 100%);
        border: 1.5px solid rgba(245, 166, 35, 0.35);
        border-radius: 20px;
        padding: 32px;
        color: #FFFFFF;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.18);
        position: relative;
        overflow: hidden;
    }
    .calc-card::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -40%;
        width: 180%;
        height: 180%;
        background: radial-gradient(circle, rgba(245, 166, 35, 0.08) 0%, transparent 60%);
        pointer-events: none;
    }
    .calc-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.35rem;
        color: #FFFFFF;
        margin-bottom: 6px;
    }
    .calc-label {
        font-size: 13px;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 6px;
        display: block;
    }
    .calc-select, .calc-input {
        background-color: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #FFFFFF;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        width: 100%;
        transition: all 0.2s ease;
    }
    .calc-select:focus, .calc-input:focus {
        background-color: rgba(255, 255, 255, 0.14);
        border-color: #F5A623;
        outline: none;
        box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.2);
        color: #FFFFFF;
    }
    .calc-select option {
        background: #1A1F2C;
        color: #FFFFFF;
    }
    .calc-result-box {
        background: rgba(245, 166, 35, 0.12);
        border: 1.5px dashed rgba(245, 166, 35, 0.5);
        border-radius: 14px;
        padding: 22px;
        text-align: center;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .calc-result-price {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 2.2rem;
        font-weight: 800;
        color: #F5A623;
        line-height: 1.1;
    }
    .calc-result-price span {
        font-size: 1.2rem;
        font-weight: 700;
        color: #FFFFFF;
        margin-right: 2px;
    }
    .calc-result-total {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.8);
        margin-top: 6px;
    }

    /* Custom Nav Tabs for A5 & B5 */
    .nav-tabs-custom {
        display: flex;
        justify-content: center;
        gap: 12px;
        border-bottom: none;
        margin-bottom: 30px;
    }
    .nav-tabs-custom .nav-link {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 15px;
        padding: 12px 28px;
        border-radius: 50px !important;
        border: 1.5px solid #E5E7EB;
        background: #FFFFFF;
        color: #4B5563;
        transition: all 0.25s ease;
    }
    .nav-tabs-custom .nav-link:hover {
        border-color: #F5A623;
        color: #111827;
    }
    .nav-tabs-custom .nav-link.active {
        background: #F5A623;
        border-color: #F5A623;
        color: #111827 !important;
        box-shadow: 0 4px 16px rgba(245, 166, 35, 0.35);
    }

    /* Modern Responsive Data Table */
    .table-container-card {
        background: #FFFFFF;
        border: 1.5px solid #E5E7EB;
        border-radius: 20px;
        padding: 28px;
        box-shadow: 0 4px 20px rgba(17, 24, 39, 0.04);
        position: relative;
    }
    .table-responsive-custom {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #E5E7EB;
    }
    .table-price {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13.5px;
    }
    .table-price thead th {
        background: #111827;
        color: #FFFFFF;
        font-weight: 700;
        text-align: center;
        padding: 14px 10px;
        border: none;
        white-space: nowrap;
        font-size: 13px;
        letter-spacing: 0.5px;
    }
    .table-price thead th:first-child {
        background: #F5A623;
        color: #111827;
        font-weight: 800;
        position: sticky;
        left: 0;
        z-index: 2;
    }
    .table-price tbody td {
        padding: 10px 12px;
        text-align: center;
        border-bottom: 1px solid #F3F4F6;
        border-right: 1px solid #F3F4F6;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 600;
        color: #374151;
        white-space: nowrap;
        transition: background-color 0.15s ease;
    }
    .table-price tbody td:first-child {
        background: #F9FAFB;
        font-weight: 700;
        color: #111827;
        text-align: center;
        position: sticky;
        left: 0;
        z-index: 1;
        border-right: 1.5px solid #E5E7EB;
    }
    .table-price tbody tr:nth-child(even) td:not(:first-child) {
        background-color: #FBFBFD;
    }
    .table-price tbody tr:hover td {
        background-color: rgba(245, 166, 35, 0.12) !important;
        color: #111827;
    }
    .table-price tbody tr:hover td:first-child {
        background-color: #FFF3D6 !important;
        color: #D97706;
    }

    /* Spec & Feature Cards */
    .spec-clean-card {
        background: #FFFFFF;
        border: 1.5px solid #E5E7EB;
        border-radius: 18px;
        padding: 26px 22px;
        height: 100%;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
    }
    .spec-clean-card:hover {
        border-color: #F5A623;
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(245, 166, 35, 0.12);
    }
    .spec-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #FFF8EE;
        color: #F5A623;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 16px;
    }
    .spec-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }
    .spec-desc {
        font-size: 13.5px;
        color: #6B7280;
        line-height: 1.55;
        margin-bottom: 0;
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
<section class="printing-hero">
    <div class="content-balanced-container">
        <div class="printing-hero-kicker">
            <i class="bi bi-printer-fill"></i> Publishing, Printing &amp; Offset
        </div>
        <h1 class="printing-hero-title">
            Daftar Tarif Cetak Print On Demand (PoD)
        </h1>
        <p class="printing-hero-subtitle">
            Transparansi tarif cetak buku tanpa biaya tersembunyi. Layanan cetak presisi untuk Ukuran A5 dan B5 dengan pilihan kertas Bookpaper 57 Gram atau HVS 70 Gram berstandar penerbitan nasional.
        </p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="#tabel-harga" class="btn-hero-cta">
                <i class="bi bi-table"></i> Lihat Tabel Harga Cetak
            </a>
            <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Percetakan Pena Cendekia, saya ingin konsultasi harga cetak buku PoD / Offset.') }}" target="_blank" rel="noopener" class="btn-hero-secondary">
                <i class="bi bi-whatsapp"></i> Konsultasi Admin
            </a>
        </div>
    </div>
</section>

<!-- Tabel Lengkap Daftar Tarif Resmi PoD (A5 & B5) -->
<section class="py-5" id="tabel-harga" style="background-color: #FBF9F5;">
    <div class="content-balanced-container">
        <div class="text-center mb-4" data-aos="fade-up">
            <span class="section-kicker-gold mb-1">TABEL TARIF RESMI</span>
            <h2 class="section-title-dark mb-2">Tabel Harga Cetak Buku PoD</h2>
            <p class="text-muted mx-auto" style="max-width: 680px; font-size: 15px;">
                Daftar harga cetak buku Print On Demand (PoD) per eksemplar berdasarkan range halaman dan oplah pemesanan.
            </p>
        </div>

        <!-- Tab Switcher A5 & B5 -->
        <ul class="nav nav-tabs nav-tabs-custom" id="priceTab" role="tablist" data-aos="fade-up">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="a5-tab" data-bs-toggle="tab" data-bs-target="#a5-pane" type="button" role="tab">
                    <i class="bi bi-file-earmark-ruled me-1"></i> Ukuran A5 (13x19, 14x20, 14.5x20.5 cm)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="b5-tab" data-bs-toggle="tab" data-bs-target="#b5-pane" type="button" role="tab">
                    <i class="bi bi-file-earmark-break me-1"></i> Ukuran B5 (15.5x23, 18x25 cm UNESCO)
                </button>
            </li>
        </ul>

        <div class="tab-content" id="priceTabContent">
            <!-- PANE 1: TABEL A5 -->
            <div class="tab-pane fade show active" id="a5-pane" role="tabpanel">
                <div class="table-container-card" data-aos="fade-up">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                        <div>
                            <h4 class="fw-bold mb-1 font-serif text-dark" style="font-size: 1.25rem;">
                                Daftar Harga Cetak PoD Ukuran A5
                            </h4>
                            <span class="badge bg-light text-dark border">
                                Kertas: Bookpaper 57 Gram / HVS 70 Gram &bull; Sampul: Art Carton 260 Gram Laminasi
                            </span>
                        </div>
                        <a href="{{ asset('images/harga-cetak-a5.jpg') }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                            <i class="bi bi-image me-1 text-warning"></i> Lihat Format Brosur Asli
                        </a>
                    </div>

                    <div class="table-responsive-custom">
                        <table class="table-price">
                            <thead>
                                <tr>
                                    <th>Range Hal \ Oplah</th>
                                    <th>&gt; 1 Eks</th>
                                    <th>&gt; 20 Eks</th>
                                    <th>&gt; 50 Eks</th>
                                    <th>&gt; 100 Eks</th>
                                    <th>&gt; 300 Eks</th>
                                    <th>&gt; 500 Eks</th>
                                    <th>&gt; 1000 Eks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>20 - 40</td><td>Rp 13.000</td><td>Rp 12.500</td><td>Rp 12.000</td><td>Rp 11.800</td><td>Rp 10.700</td><td>Rp 10.200</td><td>Rp 9.300</td></tr>
                                <tr><td>41 - 60</td><td>Rp 15.500</td><td>Rp 14.750</td><td>Rp 14.000</td><td>Rp 13.700</td><td>Rp 12.550</td><td>Rp 12.050</td><td>Rp 10.450</td></tr>
                                <tr><td>61 - 80</td><td>Rp 18.000</td><td>Rp 17.000</td><td>Rp 16.000</td><td>Rp 15.600</td><td>Rp 14.400</td><td>Rp 13.900</td><td>Rp 11.600</td></tr>
                                <tr><td>81 - 100</td><td>Rp 20.500</td><td>Rp 19.250</td><td>Rp 18.000</td><td>Rp 17.500</td><td>Rp 16.250</td><td>Rp 15.750</td><td>Rp 12.750</td></tr>
                                <tr><td>101 - 120</td><td>Rp 23.000</td><td>Rp 21.500</td><td>Rp 20.000</td><td>Rp 19.400</td><td>Rp 18.100</td><td>Rp 17.600</td><td>Rp 13.900</td></tr>
                                <tr><td>121 - 140</td><td>Rp 25.500</td><td>Rp 23.750</td><td>Rp 22.000</td><td>Rp 21.300</td><td>Rp 19.950</td><td>Rp 19.450</td><td>Rp 15.050</td></tr>
                                <tr><td>141 - 160</td><td>Rp 28.000</td><td>Rp 26.000</td><td>Rp 24.000</td><td>Rp 23.200</td><td>Rp 21.800</td><td>Rp 21.300</td><td>Rp 16.200</td></tr>
                                <tr><td>161 - 180</td><td>Rp 29.250</td><td>Rp 27.125</td><td>Rp 25.000</td><td>Rp 24.150</td><td>Rp 22.725</td><td>Rp 22.225</td><td>Rp 16.775</td></tr>
                                <tr><td>181 - 200</td><td>Rp 33.000</td><td>Rp 30.500</td><td>Rp 28.000</td><td>Rp 27.000</td><td>Rp 25.500</td><td>Rp 25.000</td><td>Rp 18.500</td></tr>
                                <tr><td>201 - 220</td><td>Rp 35.500</td><td>Rp 32.750</td><td>Rp 30.000</td><td>Rp 28.900</td><td>Rp 27.350</td><td>Rp 26.850</td><td>Rp 19.650</td></tr>
                                <tr><td>221 - 240</td><td>Rp 38.000</td><td>Rp 35.000</td><td>Rp 32.000</td><td>Rp 30.800</td><td>Rp 29.200</td><td>Rp 28.700</td><td>Rp 20.800</td></tr>
                                <tr><td>241 - 260</td><td>Rp 40.500</td><td>Rp 37.250</td><td>Rp 34.000</td><td>Rp 32.700</td><td>Rp 31.050</td><td>Rp 30.550</td><td>Rp 21.950</td></tr>
                                <tr><td>261 - 280</td><td>Rp 43.000</td><td>Rp 39.500</td><td>Rp 36.000</td><td>Rp 34.600</td><td>Rp 32.900</td><td>Rp 32.400</td><td>Rp 23.100</td></tr>
                                <tr><td>281 - 300</td><td>Rp 45.500</td><td>Rp 41.750</td><td>Rp 38.000</td><td>Rp 36.500</td><td>Rp 34.750</td><td>Rp 34.250</td><td>Rp 24.250</td></tr>
                                <tr><td>301 - 320</td><td>Rp 48.000</td><td>Rp 44.000</td><td>Rp 40.000</td><td>Rp 38.400</td><td>Rp 36.600</td><td>Rp 36.100</td><td>Rp 25.400</td></tr>
                                <tr><td>321 - 340</td><td>Rp 50.500</td><td>Rp 46.250</td><td>Rp 42.000</td><td>Rp 40.300</td><td>Rp 38.450</td><td>Rp 37.950</td><td>Rp 26.550</td></tr>
                                <tr><td>341 - 360</td><td>Rp 53.000</td><td>Rp 48.500</td><td>Rp 44.000</td><td>Rp 42.200</td><td>Rp 40.300</td><td>Rp 39.800</td><td>Rp 27.700</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PANE 2: TABEL B5 -->
            <div class="tab-pane fade" id="b5-pane" role="tabpanel">
                <div class="table-container-card">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                        <div>
                            <h4 class="fw-bold mb-1 font-serif text-dark" style="font-size: 1.25rem;">
                                Daftar Harga Cetak PoD Ukuran B5
                            </h4>
                            <span class="badge bg-light text-dark border">
                                Kertas: Bookpaper 57 Gram / HVS 70 Gram &bull; Standar UNESCO (15.5 x 23 cm) &amp; (18 x 25 cm)
                            </span>
                        </div>
                        <a href="{{ asset('images/harga-cetak-b5.jpg') }}" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                            <i class="bi bi-image me-1 text-warning"></i> Lihat Format Brosur Asli
                        </a>
                    </div>

                    <div class="table-responsive-custom">
                        <table class="table-price">
                            <thead>
                                <tr>
                                    <th>Range Hal \ Oplah</th>
                                    <th>&gt; 1 Eks</th>
                                    <th>&gt; 20 Eks</th>
                                    <th>&gt; 50 Eks</th>
                                    <th>&gt; 100 Eks</th>
                                    <th>&gt; 300 Eks</th>
                                    <th>&gt; 500 Eks</th>
                                    <th>&gt; 1000 Eks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>20 - 40</td><td>Rp 18.500</td><td>Rp 16.500</td><td>Rp 16.100</td><td>Rp 15.400</td><td>Rp 15.000</td><td>Rp 14.000</td><td>Rp 11.900</td></tr>
                                <tr><td>41 - 60</td><td>Rp 23.500</td><td>Rp 20.500</td><td>Rp 19.900</td><td>Rp 19.100</td><td>Rp 18.500</td><td>Rp 17.000</td><td>Rp 14.100</td></tr>
                                <tr><td>61 - 80</td><td>Rp 28.500</td><td>Rp 24.500</td><td>Rp 23.700</td><td>Rp 22.800</td><td>Rp 22.000</td><td>Rp 20.000</td><td>Rp 16.300</td></tr>
                                <tr><td>81 - 100</td><td>Rp 33.500</td><td>Rp 28.500</td><td>Rp 27.500</td><td>Rp 26.500</td><td>Rp 25.500</td><td>Rp 23.000</td><td>Rp 18.500</td></tr>
                                <tr><td>101 - 120</td><td>Rp 38.500</td><td>Rp 32.500</td><td>Rp 31.300</td><td>Rp 30.200</td><td>Rp 29.000</td><td>Rp 26.000</td><td>Rp 20.700</td></tr>
                                <tr><td>121 - 140</td><td>Rp 43.500</td><td>Rp 36.500</td><td>Rp 35.100</td><td>Rp 33.900</td><td>Rp 32.500</td><td>Rp 29.000</td><td>Rp 22.900</td></tr>
                                <tr><td>141 - 160</td><td>Rp 48.500</td><td>Rp 40.500</td><td>Rp 38.900</td><td>Rp 37.600</td><td>Rp 36.000</td><td>Rp 32.000</td><td>Rp 25.100</td></tr>
                                <tr><td>161 - 180</td><td>Rp 53.500</td><td>Rp 44.500</td><td>Rp 42.700</td><td>Rp 41.300</td><td>Rp 39.500</td><td>Rp 35.000</td><td>Rp 27.300</td></tr>
                                <tr><td>181 - 200</td><td>Rp 58.500</td><td>Rp 48.500</td><td>Rp 46.500</td><td>Rp 45.000</td><td>Rp 43.000</td><td>Rp 38.000</td><td>Rp 29.500</td></tr>
                                <tr><td>201 - 220</td><td>Rp 63.500</td><td>Rp 52.500</td><td>Rp 50.300</td><td>Rp 48.700</td><td>Rp 46.500</td><td>Rp 41.000</td><td>Rp 31.700</td></tr>
                                <tr><td>221 - 240</td><td>Rp 68.500</td><td>Rp 56.500</td><td>Rp 54.100</td><td>Rp 52.400</td><td>Rp 50.000</td><td>Rp 44.000</td><td>Rp 33.900</td></tr>
                                <tr><td>241 - 260</td><td>Rp 73.500</td><td>Rp 60.500</td><td>Rp 57.900</td><td>Rp 56.100</td><td>Rp 53.500</td><td>Rp 47.000</td><td>Rp 36.100</td></tr>
                                <tr><td>261 - 280</td><td>Rp 78.500</td><td>Rp 64.500</td><td>Rp 61.700</td><td>Rp 59.800</td><td>Rp 57.000</td><td>Rp 50.000</td><td>Rp 38.300</td></tr>
                                <tr><td>281 - 300</td><td>Rp 83.500</td><td>Rp 68.500</td><td>Rp 65.500</td><td>Rp 63.500</td><td>Rp 60.500</td><td>Rp 53.000</td><td>Rp 40.500</td></tr>
                                <tr><td>301 - 320</td><td>Rp 88.500</td><td>Rp 72.500</td><td>Rp 69.300</td><td>Rp 67.200</td><td>Rp 64.000</td><td>Rp 56.000</td><td>Rp 42.700</td></tr>
                                <tr><td>321 - 340</td><td>Rp 93.500</td><td>Rp 76.500</td><td>Rp 73.100</td><td>Rp 70.900</td><td>Rp 67.500</td><td>Rp 59.000</td><td>Rp 44.900</td></tr>
                                <tr><td>341 - 360</td><td>Rp 98.500</td><td>Rp 80.500</td><td>Rp 76.900</td><td>Rp 74.600</td><td>Rp 71.000</td><td>Rp 62.000</td><td>Rp 47.100</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Spesifikasi Standar Kualitas Cetak -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="content-balanced-container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-kicker-gold mb-1">STANDAR MUTU</span>
            <h2 class="section-title-dark mb-2">Spesifikasi Kualitas Cetak Pena Cendekia</h2>
            <p class="text-muted mx-auto" style="max-width: 680px; font-size: 15px;">
                Setiap buku dicetak dengan presisi mesin modern dan bahan baku berstandar industri penerbitan nasional.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="50">
                <div class="spec-clean-card">
                    <div class="spec-icon-box">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <h3 class="spec-title">Kertas Isi Berkualitas</h3>
                    <p class="spec-desc">Pilihan Bookpaper 57gr impor bernuansa krem yang nyaman di mata pembaca, atau HVS 70gr putih bersih untuk naskah ajar.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="spec-clean-card">
                    <div class="spec-icon-box">
                        <i class="bi bi-palette-fill"></i>
                    </div>
                    <h3 class="spec-title">Kover Art Carton 260gr</h3>
                    <p class="spec-desc">Kover tebal full color dengan laminasi Doff halus elegan atau Glossy mengkilap, tahan cipratan air dan anti pudar.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="150">
                <div class="spec-clean-card">
                    <div class="spec-icon-box">
                        <i class="bi bi-book-half"></i>
                    </div>
                    <h3 class="spec-title">Jilid Lem Panas Kuat</h3>
                    <p class="spec-desc">Finishing binding lem panas otomatis dengan lem PUR bermutu tinggi sehingga punggung buku rata, kokoh, dan anti copot.</p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="spec-clean-card">
                    <div class="spec-icon-box">
                        <i class="bi bi-scissors"></i>
                    </div>
                    <h3 class="spec-title">Potongan Presisi Tinggi</h3>
                    <p class="spec-desc">Menggunakan mesin potong otomatis Polar &amp; Wohlenberg berpresisi tinggi untuk tepian buku yang halus dan presisi 90 derajat.</p>
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
                <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Percetakan Pena Cendekia, saya ingin konsultasi promo Cetak 1000 eksemplar.') }}" target="_blank" rel="noopener" class="btn-admin-cta">
                    <i class="bi bi-whatsapp me-1"></i> HUBUNGI ADMIN
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Data Matrix A5 & B5
    const priceTableData = {
        A5: {
            "20-40":   { ">1": 13000, ">20": 12500, ">50": 12000, ">100": 11800, ">300": 10700, ">500": 10200, ">1000": 9300 },
            "41-60":   { ">1": 15500, ">20": 14750, ">50": 14000, ">100": 13700, ">300": 12550, ">500": 12050, ">1000": 10450 },
            "61-80":   { ">1": 18000, ">20": 17000, ">50": 16000, ">100": 15600, ">300": 14400, ">500": 13900, ">1000": 11600 },
            "81-100":  { ">1": 20500, ">20": 19250, ">50": 18000, ">100": 17500, ">300": 16250, ">500": 15750, ">1000": 12750 },
            "101-120": { ">1": 23000, ">20": 21500, ">50": 20000, ">100": 19400, ">300": 18100, ">500": 17600, ">1000": 13900 },
            "121-140": { ">1": 25500, ">20": 23750, ">50": 22000, ">100": 21300, ">300": 19950, ">500": 19450, ">1000": 15050 },
            "141-160": { ">1": 28000, ">20": 26000, ">50": 24000, ">100": 23200, ">300": 21800, ">500": 21300, ">1000": 16200 },
            "161-180": { ">1": 29250, ">20": 27125, ">50": 25000, ">100": 24150, ">300": 22725, ">500": 22225, ">1000": 16775 },
            "181-200": { ">1": 33000, ">20": 30500, ">50": 28000, ">100": 27000, ">300": 25500, ">500": 25000, ">1000": 18500 },
            "201-220": { ">1": 35500, ">20": 32750, ">50": 30000, ">100": 28900, ">300": 27350, ">500": 26850, ">1000": 19650 },
            "221-240": { ">1": 38000, ">20": 35000, ">50": 32000, ">100": 30800, ">300": 29200, ">500": 28700, ">1000": 20800 },
            "241-260": { ">1": 40500, ">20": 37250, ">50": 34000, ">100": 32700, ">300": 31050, ">500": 30550, ">1000": 21950 },
            "261-280": { ">1": 43000, ">20": 39500, ">50": 36000, ">100": 34600, ">300": 32900, ">500": 32400, ">1000": 23100 },
            "281-300": { ">1": 45500, ">20": 41750, ">50": 38000, ">100": 36500, ">300": 34750, ">500": 34250, ">1000": 24250 },
            "301-320": { ">1": 48000, ">20": 44000, ">50": 40000, ">100": 38400, ">300": 36600, ">500": 36100, ">1000": 25400 },
            "321-340": { ">1": 50500, ">20": 46250, ">50": 42000, ">100": 40300, ">300": 38450, ">500": 37950, ">1000": 26550 },
            "341-360": { ">1": 53000, ">20": 48500, ">50": 44000, ">100": 42200, ">300": 40300, ">500": 39800, ">1000": 27700 }
        },
        B5: {
            "20-40":   { ">1": 18500, ">20": 16500, ">50": 16100, ">100": 15400, ">300": 15000, ">500": 14000, ">1000": 11900 },
            "41-60":   { ">1": 23500, ">20": 20500, ">50": 19900, ">100": 19100, ">300": 18500, ">500": 17000, ">1000": 14100 },
            "61-80":   { ">1": 28500, ">20": 24500, ">50": 23700, ">100": 22800, ">300": 22000, ">500": 20000, ">1000": 16300 },
            "81-100":  { ">1": 33500, ">20": 28500, ">50": 27500, ">100": 26500, ">300": 25500, ">500": 23000, ">1000": 18500 },
            "101-120": { ">1": 38500, ">20": 32500, ">50": 31300, ">100": 30200, ">300": 29000, ">500": 26000, ">1000": 20700 },
            "121-140": { ">1": 43500, ">20": 36500, ">50": 35100, ">100": 33900, ">300": 32500, ">500": 29000, ">1000": 22900 },
            "141-160": { ">1": 48500, ">20": 40500, ">50": 38900, ">100": 37600, ">300": 36000, ">500": 32000, ">1000": 25100 },
            "161-180": { ">1": 53500, ">20": 44500, ">50": 42700, ">100": 41300, ">300": 39500, ">500": 35000, ">1000": 27300 },
            "181-200": { ">1": 58500, ">20": 48500, ">50": 46500, ">100": 45000, ">300": 43000, ">500": 38000, ">1000": 29500 },
            "201-220": { ">1": 63500, ">20": 52500, ">50": 50300, ">100": 48700, ">300": 46500, ">500": 41000, ">1000": 31700 },
            "221-240": { ">1": 68500, ">20": 56500, ">50": 54100, ">100": 52400, ">300": 50000, ">500": 44000, ">1000": 33900 },
            "241-260": { ">1": 73500, ">20": 60500, ">50": 57900, ">100": 56100, ">300": 53500, ">500": 47000, ">1000": 36100 },
            "261-280": { ">1": 78500, ">20": 64500, ">50": 61700, ">100": 59800, ">300": 57000, ">500": 50000, ">1000": 38300 },
            "281-300": { ">1": 83500, ">20": 68500, ">50": 65500, ">100": 63500, ">300": 60500, ">500": 53000, ">1000": 40500 },
            "301-320": { ">1": 88500, ">20": 72500, ">50": 69300, ">100": 67200, ">300": 64000, ">500": 56000, ">1000": 42700 },
            "321-340": { ">1": 93500, ">20": 76500, ">50": 73100, ">100": 70900, ">300": 67500, ">500": 59000, ">1000": 44900 },
            "341-360": { ">1": 98500, ">20": 80500, ">50": 76900, ">100": 74600, ">300": 71000, ">500": 62000, ">1000": 47100 }
        }
    };

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    function calculatePrice() {
        const size = document.getElementById('calcSize').value;
        const pages = document.getElementById('calcPages').value;
        const qtyKey = document.getElementById('calcQuantity').value;
        const paper = document.getElementById('calcPaper').options[document.getElementById('calcPaper').selectedIndex].text;

        const table = priceTableData[size];
        if (!table || !table[pages] || !table[pages][qtyKey]) {
            return;
        }

        const unitPrice = table[pages][qtyKey];
        // Parse minimum qty number
        let qtyNum = parseInt(qtyKey.replace('>', '')) || 1;
        if (qtyNum === 1) qtyNum = 1;
        
        const total = unitPrice * qtyNum;

        document.getElementById('calcPriceDisplay').innerHTML = `<span>Rp</span>${formatRupiah(unitPrice)}`;
        
        let totalText = '';
        if (qtyNum > 1) {
            totalText = `Estimasi Oplah ${qtyNum} Eks: <strong>Rp ${formatRupiah(total)}</strong>`;
        } else {
            totalText = `Estimasi Cetak Satuan: <strong>Rp ${formatRupiah(unitPrice)}</strong>`;
        }
        document.getElementById('calcTotalDisplay').innerHTML = totalText;

        // WhatsApp Order Link
        const message = `Halo Admin Percetakan Pena Cendekia, saya ingin pesan cetak PoD dengan rincian:\n- Ukuran: ${size}\n- Tebal: ${pages} halaman\n- Kertas: ${paper}\n- Oplah: ${qtyKey} eksemplar\n- Estimasi Harga: Rp ${formatRupiah(unitPrice)}/eks\nMohon informasi pemesanan lebih lanjut. Terima kasih!`;
        const waUrl = `https://wa.me/6285785522283?text=${encodeURIComponent(message)}`;
        document.getElementById('calcOrderBtn').setAttribute('href', waUrl);
    }

    // Run once on load
    document.addEventListener('DOMContentLoaded', function () {
        calculatePrice();
    });
</script>
@endpush
