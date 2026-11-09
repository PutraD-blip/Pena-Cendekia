@extends('layouts.app')

@section('title', $book['title'] . ' — Portofolio Buku PT. Pena Cendekia Pustaka')

@section('content')
<style>
    /* =========================================================
       DETAIL BUKU - PREMIUM EDITORIAL UI/UX
       Pena Cendekia Theme: Warm Gold (#F5A623) & Deep Navy (#10151D)
       ========================================================= */
    .buku-detail-page {
        font-family: 'Poppins', sans-serif;
        background-color: #FAF8F5;
        color: #1F2937;
        min-height: 100vh;
    }

    /* Top Breadcrumb & Navigation Bar */
    .detail-nav-bar {
        background-color: #FFFFFF;
        border-bottom: 1px solid #EAE6DF;
        padding: 16px 0;
        margin-bottom: 30px;
    }

    .detail-breadcrumb {
        margin-bottom: 0;
        font-size: 0.88rem;
    }

    .detail-breadcrumb .breadcrumb-item a {
        color: #6B7280;
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .detail-breadcrumb .breadcrumb-item a:hover {
        color: #F5A623;
    }

    .detail-breadcrumb .breadcrumb-item.active {
        color: #111827;
        font-weight: 600;
        max-width: 320px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .detail-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
        content: "»";
        color: #9CA3AF;
        padding: 0 8px;
    }

    .btn-back-catalog {
        color: #4B5563;
        font-size: 0.86rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 16px;
        border-radius: 50px;
        background: #F3F4F6;
        border: 1px solid #E5E7EB;
        transition: all 0.2s ease;
    }

    .btn-back-catalog:hover {
        background: #F5A623;
        color: #17120a;
        border-color: #F5A623;
        transform: translateX(-3px);
    }

    /* Left Column: Studio Card & Cover Showcase */
    .book-studio-card {
        background: #FFFFFF;
        border-radius: 18px;
        border: 1px solid #EAE6DF;
        box-shadow: 0 10px 30px rgba(16, 21, 29, 0.05);
        overflow: hidden;
        position: sticky;
        top: 90px;
    }

    .book-studio-stage {
        background: linear-gradient(180deg, #F8F5EE 0%, #EDE6D8 100%);
        padding: 40px 24px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 380px;
        border-bottom: 1px solid #E8E3D7;
        position: relative;
    }

    /* 3D Book Presentation */
    .book-3d-master {
        width: 215px;
        height: 320px;
        border-radius: 4px 14px 14px 4px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 20px 42px rgba(43, 33, 24, 0.28), -4px 0 12px rgba(43, 33, 24, 0.12);
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease;
        background-color: #FFFFFF;
        cursor: pointer;
    }

    .book-3d-master:hover {
        transform: translateY(-8px) scale(1.03) rotate(-1deg);
        box-shadow: 0 26px 52px rgba(43, 33, 24, 0.35), -6px 0 16px rgba(43, 33, 24, 0.16);
    }

    .book-3d-master img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Spine effect */
    .book-3d-master::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 8px;
        background: linear-gradient(to right, rgba(0,0,0,0.45), rgba(255,255,255,0.25) 35%, rgba(0,0,0,0.2) 65%, transparent);
        z-index: 2;
        pointer-events: none;
    }

    /* Zoom indicator overlay */
    .book-zoom-indicator {
        position: absolute;
        bottom: 14px;
        right: 14px;
        background: rgba(16, 21, 29, 0.75);
        color: #ffffff;
        backdrop-filter: blur(8px);
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 50px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        opacity: 0.85;
        transition: all 0.2s ease;
    }

    .book-3d-master:hover .book-zoom-indicator {
        opacity: 1;
        background: #F5A623;
        color: #17120a;
    }

    .book-studio-body {
        padding: 24px;
    }

    /* Button Konsultasi Web Color (Gold #F5A623, No WA Logo) */
    .btn-konsultasi-gold {
        background-color: #F5A623;
        color: #17120a !important;
        font-weight: 700;
        font-size: 0.98rem;
        padding: 13px 24px;
        border-radius: 12px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        box-shadow: 0 4px 14px rgba(245, 166, 35, 0.35);
        transition: all 0.2s ease;
        border: none;
        letter-spacing: 0.3px;
    }

    .btn-konsultasi-gold:hover {
        background-color: #E09419;
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(245, 166, 35, 0.45);
        color: #17120a !important;
    }

    /* Trust Badge Items */
    .trust-badge-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 14px;
        background: #FAF8F5;
        border: 1px solid #EFECE6;
        border-radius: 12px;
        margin-bottom: 10px;
        font-size: 0.82rem;
        color: #4B5563;
    }

    .trust-badge-item i {
        font-size: 1.15rem;
        color: #F5A623;
        flex-shrink: 0;
    }

    .trust-badge-item strong {
        color: #1F2937;
    }

    .btn-share-pill {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #E5E7EB;
        background: #FFFFFF;
        color: #4B5563;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-share-pill:hover {
        background: #F9FAFB;
        border-color: #D1D5DB;
        color: #111827;
    }

    /* Right Column: Book Content & Editorial */
    .book-detail-main-card {
        background: #FFFFFF;
        border-radius: 18px;
        border: 1px solid #EAE6DF;
        box-shadow: 0 10px 30px rgba(16, 21, 29, 0.05);
        padding: 36px clamp(20px, 3.5vw, 42px);
    }

    .book-tags-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
    }

    .tag-category-pill {
        background: rgba(245, 166, 35, 0.14);
        color: #B47407;
        font-size: 0.82rem;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 50px;
        border: 1px solid rgba(245, 166, 35, 0.35);
        letter-spacing: 0.3px;
    }

    .tag-year-pill {
        background: #F3F4F6;
        color: #4B5563;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 50px;
        border: 1px solid #E5E7EB;
    }

    .tag-status-pill {
        background: #ECFDF5;
        color: #065F46;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 50px;
        border: 1px solid #A7F3D0;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .book-main-title {
        font-family: 'Plus Jakarta Sans', 'Poppins', sans-serif;
        font-weight: 800;
        font-size: clamp(1.7rem, 2.7vw, 2.35rem);
        color: #111827;
        line-height: 1.25;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }

    .book-main-sub {
        font-size: 1.05rem;
        color: #64748B;
        line-height: 1.6;
        margin-bottom: 26px;
        font-style: italic;
    }

    /* Author Profile Card */
    .author-card-panel {
        background: #FAF8F5;
        border: 1px solid #EAE6DF;
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 28px;
    }

    .author-role-title {
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #B47407;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .author-names-text {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1F2937;
        line-height: 1.5;
    }

    /* Key Specs Matrix */
    .specs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 12px;
        margin-bottom: 30px;
    }

    .spec-card-box {
        background: #FAF8F5;
        border: 1px solid #EFECE6;
        border-radius: 12px;
        padding: 14px 16px;
        transition: border-color 0.2s ease, transform 0.2s ease;
    }

    .spec-card-box:hover {
        border-color: #F5A623;
        transform: translateY(-2px);
    }

    .spec-card-lbl {
        font-size: 0.74rem;
        color: #6B7280;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .spec-card-lbl i {
        color: #F5A623;
        font-size: 0.95rem;
    }

    .spec-card-val {
        font-size: 0.92rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.35;
    }

    /* Synopsis Section */
    .synopsis-block {
        margin-bottom: 32px;
    }

    .section-editorial-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 1.25rem;
        color: #111827;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-editorial-title i {
        color: #F5A623;
    }

    .synopsis-content-box {
        font-size: 0.98rem;
        line-height: 1.85;
        color: #374151;
        text-align: justify;
        padding: 22px 26px;
        background: #FAF8F5;
        border-radius: 14px;
        border-left: 4px solid #F5A623;
        border-top: 1px solid #EFECE6;
        border-right: 1px solid #EFECE6;
        border-bottom: 1px solid #EFECE6;
    }

    /* Highlights Bullet List */
    .highlights-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 10px;
    }

    .highlight-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        background: #FAF8F5;
        border: 1px solid #EFECE6;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 0.88rem;
        color: #374151;
        line-height: 1.5;
    }

    .highlight-item i {
        color: #F5A623;
        font-size: 1.05rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* Action CTA Box (Mewah & Harmonis Sesuai Palet Web) */
    .order-cta-box {
        background: linear-gradient(135deg, #10151D 0%, #1A222E 100%);
        border-radius: 18px;
        padding: 30px clamp(20px, 3.5vw, 36px);
        color: #FFFFFF;
        border: 1.5px solid rgba(245, 166, 35, 0.4);
        box-shadow: 0 16px 36px rgba(16, 21, 29, 0.15);
        margin-top: 36px;
    }

    .btn-cta-konsultasi-banner {
        background-color: #F5A623;
        color: #17120a !important;
        font-weight: 700;
        font-size: 0.96rem;
        padding: 12px 28px;
        border-radius: 12px;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(245, 166, 35, 0.35);
        white-space: nowrap;
    }

    .btn-cta-konsultasi-banner:hover {
        background-color: #E09419;
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(245, 166, 35, 0.45);
        color: #17120a !important;
    }
        background: rgba(255, 255, 255, 0.1);
        border-color: #F5A623;
        color: #F5A623 !important;
    }

    /* Related Books Section */
    .related-books-section {
        background-color: #F4F1EA;
        padding: 60px 0;
        border-top: 1px solid #EAE6DF;
        margin-top: 50px;
    }

    .related-book-card {
        background: #FFFFFF;
        border-radius: 16px;
        border: 1px solid #EAE6DF;
        box-shadow: 0 6px 20px rgba(16, 21, 29, 0.04);
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .related-book-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 36px rgba(16, 21, 29, 0.1);
        border-color: #F5A623;
    }

    .related-cover-wrap {
        background: linear-gradient(180deg, #F8F6F2 0%, #EFEAE0 100%);
        padding: 24px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 220px;
    }

    .related-3d-box {
        width: 130px;
        height: 195px;
        border-radius: 3px 8px 8px 3px;
        overflow: hidden;
        box-shadow: 0 10px 22px rgba(43, 33, 24, 0.25);
        transition: transform 0.3s ease;
    }

    .related-book-card:hover .related-3d-box {
        transform: scale(1.05) rotate(-1deg);
    }

    .related-3d-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-info-body {
        padding: 18px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .btn-related-konsultasi {
        background-color: #F5A623;
        color: #17120a !important;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 7px 16px;
        border-radius: 8px;
        text-decoration: none;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-related-konsultasi:hover {
        background-color: #E09419;
    }

    .btn-related-detail {
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
        background-color: #F3F4F6;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 7px 14px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-related-detail:hover {
        background-color: #E5E7EB;
        color: #111827;
    }
</style>

<div class="buku-detail-page">
    <!-- Top Breadcrumb & Navigation Bar -->
    <div class="detail-nav-bar">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb detail-breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('portofolio') }}">Portofolio Buku</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $book['title'] }}</li>
                    </ol>
                </nav>

                <a href="{{ route('portofolio') }}" class="btn-back-catalog">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Portofolio</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Detail Content Stage -->
    <section class="pb-5">
        <div class="container">
            <div class="row g-4 g-xl-5 align-items-start">
                <!-- Left Column: 3D Cover Studio Showcase & Trust Badges -->
                <div class="col-lg-5 col-xl-4">
                    <div class="book-studio-card">
                        <!-- 3D Studio Stage -->
                        <div class="book-studio-stage">
                            <div class="book-3d-master" 
                                 onclick="openCoverLightbox('{{ asset($book['cover']) }}', '{{ addslashes($book['title']) }}')"
                                 title="Klik untuk memperbesar sampul buku">
                                <img src="{{ asset($book['cover']) }}" alt="Cover Buku {{ $book['title'] }}">
                                <span class="book-zoom-indicator">
                                    <i class="bi bi-zoom-in"></i> Perbesar Sampul
                                </span>
                            </div>
                        </div>

                        <!-- Direct Consultation Button (Warna Web #F5A623 & Tanpa Logo WA) -->
                        <div class="book-studio-body">
                            <a href="https://wa.me/6285785522283?text={{ urlencode("Halo Admin PT. Pena Cendekia Pustaka, saya ingin konsultasi mengenai buku '" . $book['title'] . "' (ISBN: " . $book['isbn'] . ").") }}" 
                               target="_blank" 
                               rel="noopener" 
                               class="btn-konsultasi-gold mb-3">
                                Konsultasi
                            </a>

                            <!-- Trust Badges -->
                            <div class="trust-badge-item">
                                <i class="bi bi-upc-scan"></i>
                                <div>
                                    <strong>ISBN &amp; KDT Resmi</strong>
                                    <div class="small text-muted">{{ $book['isbn'] }}</div>
                                </div>
                            </div>

                            <div class="trust-badge-item">
                                <i class="bi bi-award-fill"></i>
                                <div>
                                    <strong>Perpustakaan Nasional RI</strong>
                                    <div class="small text-muted">Katalog Terbitan Resmi Berstandar Nasional</div>
                                </div>
                            </div>

                            <div class="trust-badge-item">
                                <i class="bi bi-patch-check-fill"></i>
                                <div>
                                    <strong>Penerbit Anggota IKAPI</strong>
                                    <div class="small text-muted">No. Anggota Resmi 379/JTI/2023</div>
                                </div>
                            </div>

                            <!-- Share Row -->
                            <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top">
                                <button type="button" class="btn-share-pill" onclick="copyBookLink()" id="btnCopyLink">
                                    <i class="bi bi-link-45deg fs-6"></i>
                                    <span id="copyLinkText">Salin Tautan</span>
                                </button>
                                <button type="button" class="btn-share-pill" onclick="openCoverLightbox('{{ asset($book['cover']) }}', '{{ addslashes($book['title']) }}')">
                                    <i class="bi bi-zoom-in fs-6"></i>
                                    <span>Lihat Sampul</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Metadata, Specs, Synopsis & Consultation -->
                <div class="col-lg-7 col-xl-8">
                    <div class="book-detail-main-card">
                        <!-- Category & Status Row -->
                        <div class="book-tags-row">
                            <span class="tag-category-pill">{{ $book['category'] }}</span>
                            <span class="tag-year-pill"><i class="bi bi-calendar3 me-1"></i> Tahun {{ $book['year'] }}</span>
                            <span class="tag-status-pill"><i class="bi bi-check-circle-fill"></i> {{ $book['availability'] ?? 'Tersedia di Penerbit' }}</span>
                        </div>

                        <!-- Main Title & Subtitle -->
                        <h1 class="book-main-title">{{ $book['title'] }}</h1>
                        @if(!empty($book['sub']))
                            <p class="book-main-sub">{{ $book['sub'] }}</p>
                        @endif

                        <!-- Author & Editor Panel -->
                        <div class="author-card-panel">
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <div class="author-role-title">Penulis Utama &amp; Kontributor</div>
                                    <div class="author-names-text">{{ $book['author'] }}</div>
                                </div>
                                <div class="col-md-5 border-start-md">
                                    <div class="author-role-title">Editor Naskah</div>
                                    <div class="author-names-text">{{ $book['editor'] ?? '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Key Specifications Matrix -->
                        <div class="specs-grid">
                            <div class="spec-card-box">
                                <div class="spec-card-lbl"><i class="bi bi-upc"></i> No. ISBN</div>
                                <div class="spec-card-val">{{ $book['isbn'] }}</div>
                            </div>
                            <div class="spec-card-box">
                                <div class="spec-card-lbl"><i class="bi bi-aspect-ratio"></i> Dimensi Ukuran</div>
                                <div class="spec-card-val">{{ $book['size'] }}</div>
                            </div>
                            <div class="spec-card-box">
                                <div class="spec-card-lbl"><i class="bi bi-book"></i> Jumlah Halaman</div>
                                <div class="spec-card-val">{{ $book['pages'] }}</div>
                            </div>
                            <div class="spec-card-box">
                                <div class="spec-card-lbl"><i class="bi bi-file-earmark-text"></i> Jenis Kertas</div>
                                <div class="spec-card-val">{{ $book['paper'] ?? 'Bookpaper 72 gr' }}</div>
                            </div>
                            <div class="spec-card-box">
                                <div class="spec-card-lbl"><i class="bi bi-brush"></i> Finishing Cover</div>
                                <div class="spec-card-val">{{ $book['cover_type'] ?? 'Soft Cover Doff Spot UV' }}</div>
                            </div>
                            <div class="spec-card-box">
                                <div class="spec-card-lbl"><i class="bi bi-tag"></i> Bidang Ilmu</div>
                                <div class="spec-card-val">{{ $book['category'] }}</div>
                            </div>
                        </div>

                        <!-- Synopsis Section -->
                        <div class="synopsis-block">
                            <h3 class="section-editorial-title">
                                <i class="bi bi-justify-left"></i>
                                <span>Sinopsis &amp; Deskripsi Buku</span>
                            </h3>
                            <div class="synopsis-content-box">
                                {{ $book['synopsis'] }}
                            </div>
                        </div>

                        <!-- Key Highlights / Focus Areas -->
                        @if(!empty($book['highlights']))
                        <div class="mb-4">
                            <h4 class="section-editorial-title" style="font-size: 1.15rem;">
                                <i class="bi bi-stars"></i>
                                <span>Fokus &amp; Pokok Bahasan Utama</span>
                            </h4>
                            <ul class="highlights-list">
                                @foreach($book['highlights'] as $highlight)
                                    <li class="highlight-item">
                                        <i class="bi bi-check2-circle"></i>
                                        <span>{{ $highlight }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Action Consultation Banner (Harmonis Warna Emas & Midnight Web) -->
                        <div class="order-cta-box">
                            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                                <div>
                                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2" style="font-size: 11px; letter-spacing: 0.5px;">
                                        LAYANAN KONSULTASI RESMI
                                    </span>
                                    <h4 class="fw-bold mb-2 text-white" style="font-size: clamp(1.2rem, 2vw, 1.45rem);">
                                        Ingin Konsultasi Buku Ini?
                                    </h4>
                                    <p class="text-white-50 mb-0 small" style="max-width: 520px; line-height: 1.6;">
                                        Hubungi tim redaksi kami untuk konsultasi ketersediaan buku, bedah karya, atau penerbitan naskah serupa.
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="https://wa.me/6285785522283?text={{ urlencode("Halo Admin PT. Pena Cendekia Pustaka, saya ingin konsultasi mengenai buku '" . $book['title'] . "' (ISBN: " . $book['isbn'] . ").") }}" 
                                       target="_blank" 
                                       rel="noopener" 
                                       class="btn-cta-konsultasi-banner">
                                        Konsultasi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section: Koleksi Buku Terkait Lainnya -->
    @if(!empty($relatedBooks) && count($relatedBooks) > 0)
    <section class="related-books-section">
        <div class="container">
            <div class="d-flex flex-wrap align-items-end justify-content-between mb-4 pb-2 border-bottom">
                <div>
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-1" style="font-size: 11px;">
                        REKOMENDASI TERBITAN
                    </span>
                    <h3 class="fw-bold text-dark mb-0" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                        Koleksi Buku Terbitan Lainnya
                    </h3>
                </div>
                <a href="{{ route('portofolio') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-semibold mt-2 mt-sm-0">
                    <span>Lihat Semua Katalog</span>
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @foreach($relatedBooks as $rel)
                <div class="col-md-6 col-lg-4">
                    <div class="related-book-card">
                        <div class="related-cover-wrap">
                            <div class="related-3d-box">
                                <img src="{{ asset($rel['cover']) }}" alt="Cover {{ $rel['title'] }}">
                            </div>
                        </div>
                        <div class="related-info-body">
                            <span class="tag-category-pill align-self-start mb-2" style="font-size: 0.72rem; padding: 3px 10px;">
                                {{ $rel['category'] }}
                            </span>
                            <h5 class="fw-bold text-dark mb-1" style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.05rem; line-height: 1.4;">
                                {{ $rel['title'] }}
                            </h5>
                            <p class="text-muted small mb-3 text-truncate" title="{{ $rel['author'] }}">
                                <i class="bi bi-person me-1 text-warning"></i> {{ $rel['author'] }}
                            </p>
                            <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                                <a href="{{ route('buku.detail', $rel['id']) }}" class="btn-related-detail">
                                    Detail
                                </a>
                                <a href="https://wa.me/6285785522283?text={{ urlencode("Halo Admin PT. Pena Cendekia Pustaka, saya ingin konsultasi mengenai buku '" . $rel['title'] . "' (ISBN: " . $rel['isbn'] . ").") }}" 
                                   target="_blank" 
                                   rel="noopener" 
                                   class="btn-related-konsultasi">
                                    Konsultasi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>

<!-- Lightbox Modal untuk Cover Full -->
<div class="modal fade" id="coverLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 bg-transparent shadow-none text-center">
            <div class="position-relative d-inline-block mx-auto">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Tutup" style="z-index: 10;"></button>
                <img id="lightboxImg" src="" alt="Cover Full" class="img-fluid rounded-3 shadow-lg" style="max-height: 82vh; border: 2px solid rgba(255,255,255,0.25);">
                <div class="mt-2 text-white small fw-semibold" id="lightboxCaption"></div>
            </div>
        </div>
    </div>
</div>

<script>
    function openCoverLightbox(imgSrc, title) {
        document.getElementById('lightboxImg').src = imgSrc;
        document.getElementById('lightboxCaption').textContent = title || '';
        const modal = new bootstrap.Modal(document.getElementById('coverLightboxModal'));
        modal.show();
    }

    function copyBookLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            const txt = document.getElementById('copyLinkText');
            txt.textContent = 'Tersalin!';
            setTimeout(() => {
                txt.textContent = 'Salin Tautan';
            }, 2000);
        }).catch(err => {
            alert('Tautan buku: ' + url);
        });
    }
</script>
@endsection
