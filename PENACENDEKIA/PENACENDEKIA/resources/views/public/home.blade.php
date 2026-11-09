@extends('layouts.app')

@section('title', 'Penerbit Pena Cendekia — Wahana Penyalur Ilmu Pengetahuan')

@push('styles')
<style>
    html { scroll-behavior: smooth; }
    .section-alt { background-color: #F8F5EF; }
    .icon-circle { width: 52px; height: 52px; background-color: var(--brand-soft-peach); color: var(--brand-primary); }
    .stat-num { color: var(--brand-primary); }
    .step-num { width: 34px; height: 34px; background-color: var(--brand-primary); }
    .book-card { border-left: 4px solid var(--brand-secondary) !important; }
    .wa-float {
    position: fixed; right: 20px; bottom: 20px; z-index: 1030;
        width: 58px; height: 58px; border-radius: 50%;
        background-color: #25D366; color: #fff; font-size: 30px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
        .wa-float:hover { color: #fff; transform: translateY(-3px); box-shadow: 0 10px 24px rgba(0, 0, 0, 0.3); }

    .testimonial-card {
        position: relative;
    }

    .testimonial-photo {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        position: absolute;
        top: 18px;
        right: 18px;
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .service-card {
        background: #ffffff;
        border: 1px solid rgba(220, 210, 198, 0.45);
        border-radius: 18px;
        box-shadow: 0 4px 20px rgba(70, 50, 30, 0.04);
        transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s ease, border-color 0.28s ease;
    }
    .service-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 35px rgba(70, 50, 30, 0.1);
        border-color: rgba(245, 166, 35, 0.4);
    }
    .service-card .icon-circle {
        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), background-color 0.25s ease, color 0.25s ease;
    }
    .service-card:hover .icon-circle {
        animation: floatUpDown 0.8s ease-in-out infinite alternate;
        background-color: var(--brand-primary);
        color: #ffffff;
    }

    @keyframes floatUpDown {
        0% { transform: translateY(0); }
        100% { transform: translateY(-5px); }
    }

    /* Hero Banner matching screenshot */
    /* Hero Banner Full Screen for all Laptop & Mobile */
    .hero-banner {
        position: relative;
        background: linear-gradient(135deg, rgba(14, 18, 24, 0.88) 0%, rgba(18, 22, 28, 0.82) 45%, rgba(10, 14, 18, 0.78) 100%),
                    url('{{ asset('images/hero-bg.jpg') }}') center/cover no-repeat;
        padding-top: clamp(75px, 9vh, 110px);
        padding-bottom: clamp(25px, 4vh, 45px);
        min-height: 100vh;
        min-height: 100dvh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow-x: hidden;
        box-sizing: border-box;
    }

    .hero-top-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(35, 42, 50, 0.65);
        border: 1px solid rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 50px;
        padding: clamp(5px, 0.42vw, 7px) clamp(12px, 1.05vw, 18px);
        font-size: clamp(10.5px, 0.7vw, 12.5px);
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
        max-width: 100%;
    }
    .hero-top-badge i {
        color: #F5A623;
        font-size: 13.5px;
    }

    .hero-kicker {
        color: #F5A623;
        font-size: clamp(11.5px, 0.85vw, 15px);
        font-weight: 800;
        letter-spacing: 1.8px;
        text-transform: uppercase;
    }

    .hero-main-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #ffffff;
        text-transform: uppercase;
        line-height: 1.02;
    }

    .hero-title-top {
        display: block;
        font-weight: 800;
        font-size: clamp(1.85rem, 3.15vw, 4.4rem);
        letter-spacing: 0.5px;
        color: #ffffff;
    }

    .hero-title-bottom {
        display: inline-block;
        font-weight: 800;
        font-size: clamp(2.25rem, 3.85vw, 5.4rem);
        letter-spacing: clamp(1px, 0.1vw, 1.8px);
        color: #ffffff;
    }

    .hero-lead {
        color: rgba(255, 255, 255, 0.9);
        font-size: clamp(14px, 0.95vw, 17.5px);
        line-height: 1.6;
        max-width: clamp(390px, 29vw, 500px);
    }

    .btn-hero-gold {
        background-color: #F5A623;
        color: #17120a;
        font-weight: 700;
        font-size: clamp(13px, 0.88vw, 15.5px);
        border-radius: 50px;
        padding: clamp(9px, 0.68vw, 12px) clamp(20px, 1.7vw, 32px);
        border: none;
        transition: all 0.2s ease;
        box-shadow: 0 4px 15px rgba(245, 166, 35, 0.3);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }
    .btn-hero-gold:hover {
        background-color: #e09419;
        color: #17120a;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 166, 35, 0.4);
    }

    .btn-hero-translucent {
        background: rgba(30, 25, 20, 0.55);
        color: #F5A623;
        border: 1.5px solid #F5A623;
        font-weight: 700;
        font-size: clamp(13px, 0.88vw, 15.5px);
        border-radius: 50px;
        padding: clamp(9px, 0.68vw, 12px) clamp(20px, 1.7vw, 32px);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }
    .btn-hero-translucent:hover {
        background-color: #F5A623;
        color: #17120a;
        transform: translateY(-2px);
    }

    .hero-feature-pill {
        display: inline-flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 50px;
        padding: clamp(3.5px, 0.32vw, 5.5px) clamp(8px, 0.65vw, 13px);
        font-size: clamp(8px, 0.58vw, 11px);
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 0.4px;
        white-space: nowrap;
    }
    .hero-feature-pill i {
        color: #F5A623;
        font-size: clamp(10.5px, 0.68vw, 13px);
        margin-right: 4px;
    }

    /* Glass Card on Hero Right - Translucent matching feature pills */
    .hero-stats-card {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: clamp(18px, 1.7vw, 30px) clamp(16px, 1.6vw, 28px);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.25);
        max-width: 760px;
        margin-left: auto;
        width: 100%;
        transform: translateX(-4px);
    }

    .stats-sub-kicker {
        color: #F5A623;
        font-size: clamp(10px, 0.65vw, 12px);
        font-weight: 800;
        letter-spacing: 1.3px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .stats-card-heading {
        color: #ffffff;
        font-weight: 800;
        font-size: clamp(1.15rem, 1.35vw, 1.65rem);
        font-family: 'Plus Jakarta Sans', sans-serif;
        margin-bottom: 0;
    }

    .stats-card-icon {
        width: clamp(34px, 2.1vw, 40px);
        height: clamp(34px, 2.1vw, 40px);
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.16);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #F5A623;
        font-size: clamp(15px, 0.95vw, 18px);
        flex-shrink: 0;
    }

    .stat-mini-box {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: clamp(11px, 0.9vw, 16px) clamp(12px, 1vw, 18px);
        height: 100%;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        transition: transform 0.2s ease, background 0.2s ease;
    }
    .stat-mini-box:hover {
        background: rgba(255, 255, 255, 0.09);
        transform: translateY(-2px);
    }

    .stat-mini-label {
        display: block;
        color: rgba(255, 255, 255, 0.78);
        font-size: clamp(9.5px, 0.62vw, 11px);
        font-weight: 800;
        letter-spacing: 0.7px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .stat-mini-val {
        color: #ffffff;
        font-size: clamp(1.45rem, 1.85vw, 2.35rem);
        font-weight: 800;
        font-family: 'Plus Jakarta Sans', sans-serif;
        line-height: 1.05;
        margin-bottom: 4px;
    }

    .stat-mini-desc {
        display: block;
        color: rgba(255, 255, 255, 0.9);
        font-size: clamp(10.5px, 0.7vw, 12.5px);
        font-weight: 500;
        line-height: 1.35;
    }

    .stats-card-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        color: rgba(255, 255, 255, 0.92);
        font-size: clamp(11.5px, 0.72vw, 13.5px);
        font-weight: 600;
        padding-top: 14px;
        margin-top: 8px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Laptop screens with shorter viewport height (e.g. 1366x768, 1440x900, 1600x900) */
    @media (min-width: 1200px) and (max-height: 850px) {
        .hero-banner {
            padding-top: 75px;
            padding-bottom: 20px;
        }
        .hero-title-top {
            font-size: clamp(1.7rem, 2.7vw, 3.6rem);
        }
        .hero-title-bottom {
            font-size: clamp(2.1rem, 3.4vw, 4.4rem);
        }
        .hero-lead {
            font-size: 13.5px;
            line-height: 1.5;
            margin-bottom: 14px !important;
        }
        .hero-stats-card {
            padding: 16px 20px;
        }
        .stat-mini-val {
            font-size: 1.8rem;
        }
        .stat-mini-box {
            padding: 9px 12px;
        }
        .stats-card-heading {
            font-size: 1.25rem;
        }
    }

    /* Tablet Breakpoint (768px - 1199.98px) */
    @media (max-width: 1199.98px) {
        .hero-banner {
            min-height: 100vh;
            min-height: 100dvh;
            padding-top: 100px;
            padding-bottom: 45px;
        }
        .hero-title-top {
            font-size: clamp(2.0rem, 4.6vw, 2.9rem);
        }
        .hero-title-bottom {
            font-size: clamp(2.4rem, 5.5vw, 3.5rem);
        }
        .hero-lead {
            max-width: 580px;
            font-size: 15.5px;
        }
        .hero-stats-card {
            max-width: 100%;
            transform: none;
            margin-left: 0;
            padding: 22px 20px;
        }
        .stats-card-heading {
            font-size: 1.4rem;
        }
        .stat-mini-val {
            font-size: 1.85rem;
        }
    }

    /* Mobile / HP Breakpoint (< 767.98px) */
    @media (max-width: 767.98px) {
        .hero-banner {
            min-height: 100vh;
            min-height: 100dvh;
            padding-top: 85px;
            padding-bottom: 35px;
        }
        .hero-top-badge {
            font-size: 10px;
            padding: 5px 12px;
            letter-spacing: 0.5px;
        }
        .hero-kicker {
            font-size: 11.5px;
            letter-spacing: 1.2px;
        }
        .hero-title-top {
            font-size: clamp(1.45rem, 5.8vw, 1.95rem);
        }
        .hero-title-bottom {
            font-size: clamp(1.75rem, 7.2vw, 2.35rem);
            letter-spacing: 0.8px;
        }
        .hero-lead {
            font-size: 13.5px;
            line-height: 1.55;
        }
        .hero-stats-card {
            padding: 16px 14px;
            border-radius: 16px;
        }
        .stats-card-heading {
            font-size: 1.15rem;
        }
        .stat-mini-box {
            padding: 12px 10px;
        }
        .stat-mini-val {
            font-size: clamp(1.25rem, 5vw, 1.55rem);
        }
        .stat-mini-desc {
            font-size: 11px;
        }
    }

    /* =========================================================
       Section: Tentang Kami (matching design mockup)
       ========================================================= */
    .about-kicker {
        color: #d97706;
        font-weight: 700;
        font-size: 1.05rem;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .about-main-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: clamp(1.85rem, 2.7vw, 2.65rem);
        line-height: 1.2;
        color: #111827;
        letter-spacing: -0.5px;
    }

    .about-desc {
        color: #4b5563;
        font-size: 0.98rem;
        line-height: 1.75;
        text-align: justify;
    }

    .about-features-list {
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
    }

    .about-feature-item {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .about-icon-badge {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 50%;
        background-color: #F5A623;
        color: #111827;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(245, 166, 35, 0.3);
    }

    .about-feature-text {
        font-weight: 700;
        font-size: 1rem;
        color: #111827;
        line-height: 1.4;
    }

    /* Wide Container with balanced margins left & right (shifted to match Image 1) */
    .about-wide-container {
        max-width: 1560px;
        width: 95%;
        margin-left: auto;
        margin-right: auto;
        padding-left: clamp(16px, 1.8vw, 32px);
        padding-right: clamp(16px, 1.8vw, 32px);
        box-sizing: border-box;
    }

    /* Arched Photo Frame shifted to the right on desktop */
    .about-arch-frame {
        position: relative;
        width: 100%;
        max-width: 480px;
        margin: 0 0 0 auto;
        padding: 24px 20px 20px 20px;
    }

    /* Golden yellow decorative background arch on the left */
    .about-arch-backdrop {
        position: absolute;
        bottom: 15px;
        left: -10px;
        width: 210px;
        height: 310px;
        background: linear-gradient(145deg, #F5A623 0%, #D97706 100%);
        border-radius: 120px 120px 18px 18px;
        z-index: 1;
        opacity: 0.95;
    }

    /* Botanical / organic decorative accent */
    .about-arch-deco {
        position: absolute;
        bottom: 35px;
        left: -22px;
        z-index: 3;
        color: #D97706;
        font-size: 26px;
        pointer-events: none;
        opacity: 0.9;
    }

    /* Main arched dome photo container */
    .about-arch-card {
        position: relative;
        width: 100%;
        height: 480px;
        background-color: #faf5ee;
        border-radius: 240px 240px 28px 28px;
        overflow: hidden;
        box-shadow: 0 20px 48px rgba(0, 0, 0, 0.14);
        z-index: 2;
        border: 4px solid #ffffff;
    }

    .about-arch-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        display: block;
        transition: transform 0.4s ease;
    }

    .about-arch-card:hover .about-arch-img {
        transform: scale(1.03);
    }

    /* Golden yellow floating experience/speed badge */
    .about-arch-badge {
        position: absolute;
        bottom: 24px;
        right: -8px;
        background: linear-gradient(135deg, #F5A623 0%, #D97706 100%);
        color: #111827;
        padding: 16px 22px;
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(245, 166, 35, 0.42);
        border: 3.5px solid #ffffff;
        z-index: 4;
        text-align: center;
        min-width: 140px;
        animation: floatBadge 3.5s ease-in-out infinite alternate;
    }

    @keyframes floatBadge {
        0% { transform: translateY(0); }
        100% { transform: translateY(-6px); }
    }

    .about-arch-badge-val {
        font-size: 2.1rem;
        font-weight: 800;
        line-height: 1;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #111827;
        margin-bottom: 2px;
    }

    .about-arch-badge-lbl {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.4px;
        line-height: 1.25;
        color: #1f2937;
        text-transform: uppercase;
    }

    @media (max-width: 991.98px) {
        .about-arch-frame {
            max-width: 420px;
            padding: 16px 12px;
        }
        .about-arch-card {
            height: 420px;
            border-radius: 200px 200px 24px 24px;
        }
        .about-arch-backdrop {
            width: 180px;
            height: 270px;
            left: -6px;
        }
        .about-arch-badge {
            bottom: 16px;
            right: 0;
            padding: 12px 18px;
        }
        .about-arch-badge-val {
            font-size: 1.75rem;
        }
    }

    @media (max-width: 575.98px) {
        .about-arch-frame {
            max-width: 320px;
        }
        .about-arch-card {
            height: 350px;
            border-radius: 160px 160px 20px 20px;
        }
        .about-arch-backdrop {
            width: 140px;
            height: 210px;
            left: -4px;
        }
        .about-arch-badge {
            bottom: 12px;
            right: -4px;
            padding: 10px 14px;
            min-width: 110px;
        }
        .about-arch-badge-val {
            font-size: 1.5rem;
        }
        .about-arch-badge-lbl {
            font-size: 9.5px;
        }
    }
        .about-feature-text {
            font-size: 0.92rem;
        }
        .about-icon-badge {
            width: 32px;
            height: 32px;
            min-width: 32px;
            font-size: 15px;
        }
    }

    /* =========================================================
       Section: Mengapa Harus Memilih Kami (Image 1 matching)
       ========================================================= */
    .why-us-section {
        background-color: #FAF6F0;
        padding-top: 4.8rem;
        padding-bottom: 5rem;
    }

    .section-kicker-gold {
        color: #d97706;
        font-weight: 700;
        font-size: 1.05rem;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .section-title-dark {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: clamp(1.85rem, 2.7vw, 2.65rem);
        line-height: 1.25;
        color: #111827;
        letter-spacing: -0.5px;
    }

    .why-us-kicker {
        color: #d97706;
        font-size: 1.05rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .why-us-heading {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: clamp(2rem, 3.2vw, 2.85rem);
        color: #111827;
        letter-spacing: -0.5px;
    }

    .why-us-card {
        background: #ffffff;
        border-radius: 24px;
        padding: 34px 26px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(230, 225, 215, 0.45);
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .why-us-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    }

    .why-us-icon-badge {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 50%;
        background-color: #FBBF24;
        color: #111827;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 22px;
        box-shadow: 0 4px 14px rgba(251, 191, 36, 0.4);
        transition: transform 0.3s ease;
    }

    .why-us-card:hover .why-us-icon-badge {
        transform: scale(1.1) rotate(5deg);
    }

    .why-us-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 800;
        font-size: 1.25rem;
        color: #111827;
        margin-bottom: 12px;
        line-height: 1.3;
    }

    .why-us-desc {
        color: #4B5563;
        font-size: 0.94rem;
        line-height: 1.68;
        margin-bottom: 0;
    }

    /* =========================================================
       Section: Service / Layanan (Image 2 design + Image 3 content)
       ========================================================= */
    .service-section {
        background-color: #F8FAFC;
        padding-top: 5rem;
        padding-bottom: 5.5rem;
        position: relative;
    }

    .service-clean-card {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid rgba(226, 232, 240, 0.95);
        padding: clamp(24px, 2.3vw, 36px);
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .service-clean-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 45px rgba(245, 166, 35, 0.12), 0 8px 24px rgba(15, 23, 42, 0.05);
        border-color: #F5A623;
    }

    .service-clean-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 22px;
    }

    .service-clean-num {
        font-family: 'Poppins', sans-serif;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.2px;
        color: #D97706;
        text-transform: uppercase;
        background: #FFF8EE;
        padding: 5px 12px;
        border-radius: 50px;
        border: 1px solid rgba(245, 166, 35, 0.35);
    }

    .service-clean-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: #1E293B;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        transition: all 0.32s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .service-clean-card:hover .service-clean-icon {
        background-color: #F5A623;
        border-color: #F5A623;
        color: #17120a;
        transform: scale(1.08) rotate(-4deg);
    }

    .service-clean-title {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.25rem;
        color: #111827;
        margin-bottom: 12px;
        line-height: 1.35;
    }

    .service-clean-desc {
        color: #475569;
        font-size: 0.92rem;
        line-height: 1.7;
        margin-bottom: 24px;
        flex-grow: 1;
    }

    .service-clean-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 16px;
        border-top: 1px solid #F1F5F9;
        margin-top: auto;
    }

    .service-clean-tag {
        font-size: 11.5px;
        font-weight: 600;
        color: #64748B;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .service-clean-link {
        font-size: 12px;
        font-weight: 700;
        color: #111827;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .service-clean-card:hover .service-clean-link {
        color: #D97706;
        transform: translateX(3px);
    }

    /* Proportional & Balanced Centered Container */
    .content-balanced-container {
        max-width: 1240px;
        width: 90%;
        margin-left: auto;
        margin-right: auto;
        padding-left: 15px;
        padding-right: 15px;
    }

    .service-stats-bar {
        background-color: #111827;
        border-radius: 20px;
        padding: 24px 34px;
        box-shadow: 0 12px 30px rgba(17, 24, 39, 0.1);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.08);
        overflow: hidden;
    }

    .service-stat-card {
        display: flex;
        align-items: center;
        gap: 12px;
        height: 100%;
        width: 100%;
        min-width: 0;
    }

    .service-stat-icon {
        width: 48px;
        height: 48px;
        min-width: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        background: rgba(245, 166, 35, 0.14);
        color: #F5A623;
        flex-shrink: 0;
    }

    .service-stat-info {
        min-width: 0;
        flex: 1;
    }

    .service-stat-val {
        font-family: 'Poppins', sans-serif;
        font-size: 1.55rem;
        font-weight: 800;
        line-height: 1.15;
        color: #ffffff;
        word-break: normal;
        white-space: nowrap;
    }

    .service-stat-lbl {
        font-size: 12.5px;
        color: #94A3B8;
        font-weight: 500;
        line-height: 1.25;
        margin-top: 2px;
    }

    /* Responsive Stats Bar for Mobile & Tablet */
    @media (max-width: 991.98px) {
        .service-stats-bar {
            padding: 16px 14px;
            border-radius: 18px;
            margin-top: 2rem !important;
        }

        .service-stat-card {
            background: rgba(255, 255, 255, 0.035);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 14px;
            padding: 12px 10px;
            gap: 10px;
        }

        .service-stat-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            font-size: 18px;
            border-radius: 11px;
        }

        .service-stat-val {
            font-size: 1.25rem;
            line-height: 1.1;
        }

        .service-stat-lbl {
            font-size: 11.5px;
            line-height: 1.25;
        }
    }

    @media (max-width: 575.98px) {
        .service-stats-bar {
            padding: 12px 10px;
            border-radius: 16px;
        }

        .service-stat-card {
            padding: 10px 8px;
            gap: 8px;
            border-radius: 12px;
        }

        .service-stat-icon {
            width: 36px;
            height: 36px;
            min-width: 36px;
            font-size: 16px;
            border-radius: 10px;
        }

        .service-stat-val {
            font-size: 1.12rem;
            letter-spacing: -0.2px;
        }

        .service-stat-lbl {
            font-size: 10.5px;
            line-height: 1.2;
        }
    }

    @media (max-width: 375.98px) {
        .service-stat-card {
            padding: 8px 6px;
            gap: 6px;
        }

        .service-stat-icon {
            width: 32px;
            height: 32px;
            min-width: 32px;
            font-size: 14px;
            border-radius: 8px;
        }

        .service-stat-val {
            font-size: 1.02rem;
        }

        .service-stat-lbl {
            font-size: 9.5px;
        }
    }

    /* =========================================================
       Section: Portofolio 3D Coverflow Carousel (Image 1 matching)
       ========================================================= */
    .portfolio-section {
        background: radial-gradient(circle at 50% 25%, #1e293b 0%, #0f172a 100%);
        padding-top: 2.2rem;
        padding-bottom: 2.4rem;
        position: relative;
        overflow: hidden;
    }

    .portfolio-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(245, 166, 35, 0.4), transparent);
    }

    .portfolio-kicker {
        color: #d97706;
        font-weight: 700;
        font-size: 0.92rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 0.35rem;
    }

    .portfolio-title {
        color: #ffffff;
        font-weight: 800;
        font-size: clamp(1.85rem, 3vw, 2.75rem);
    }

    .portfolio-desc-wrapper {
        max-width: 820px;
        margin: 0 auto;
    }

    .portfolio-sub {
        color: #CBD5E1;
        font-size: 0.90rem;
        line-height: 1.55;
        max-width: 780px;
        margin: 0 auto;
        font-weight: 400;
    }

    .book-coverflow-wrapper {
        position: relative;
        padding: 15px clamp(48px, 4.5vw, 80px) 10px clamp(48px, 4.5vw, 80px);
        max-width: 1400px;
        margin: 0 auto;
        overflow: hidden;
    }

    .book-coverflow-swiper {
        width: 100%;
        max-width: 1220px;
        margin: 0 auto;
        padding-top: 15px;
        padding-bottom: 25px;
        overflow: visible !important;
    }

    .book-slide {
        width: 260px !important;
        height: 385px !important;
        position: relative;
        transition: transform 0.4s ease, filter 0.4s ease;
        cursor: grab;
    }

    .book-slide:active {
        cursor: grabbing;
    }

    .book-3d-item {
        width: 100%;
        height: 100%;
        position: relative;
        border-radius: 4px 14px 14px 4px;
        overflow: hidden;
        background-color: #1e293b;
        box-shadow: 0 16px 38px rgba(0, 0, 0, 0.65),
                    inset 8px 0 12px rgba(0, 0, 0, 0.45);
        border-right: 5px solid #ece7dc;
        border-top: 1px solid rgba(255, 255, 255, 0.18);
        border-bottom: 3px solid rgba(0, 0, 0, 0.7);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .book-3d-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* Realistic Spine overlay */
    .book-3d-item::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 14px;
        background: linear-gradient(90deg, rgba(0,0,0,0.5) 0%, rgba(255,255,255,0.18) 50%, rgba(0,0,0,0.3) 100%);
        z-index: 2;
        pointer-events: none;
    }

    /* Hover overlay */
    .book-overlay-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 30px 14px 16px 14px;
        background: linear-gradient(0deg, rgba(15, 23, 42, 0.96) 0%, rgba(15, 23, 42, 0.72) 65%, transparent 100%);
        color: #ffffff;
        opacity: 0;
        transform: translateY(12px);
        transition: all 0.3s ease;
        z-index: 3;
        text-align: center;
    }

    .swiper-slide-active .book-3d-item {
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.8), 0 0 35px rgba(245, 166, 35, 0.3);
    }

    .book-slide:hover .book-overlay-info,
    .swiper-slide-active .book-overlay-info {
        opacity: 1;
        transform: translateY(0);
    }

    .book-mini-title {
        font-size: 13px;
        font-weight: 700;
        line-height: 1.25;
        margin-bottom: 8px;
        color: #ffffff;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .btn-book-inspect {
        background-color: #F5A623;
        color: #17120a;
        font-weight: 700;
        font-size: 11.5px;
        padding: 6px 14px;
        border-radius: 50px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(245, 166, 35, 0.4);
    }

    .btn-book-inspect:hover {
        background-color: #e09419;
        color: #17120a;
        transform: scale(1.05);
    }

    /* Swiper Navigation Controls */
    .swiper-nav-btn {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.25s ease;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        font-size: 18px;
    }

    .swiper-nav-btn:hover {
        background: #F5A623;
        color: #17120a;
        border-color: #F5A623;
        box-shadow: 0 4px 18px rgba(245, 166, 35, 0.45);
        transform: translateY(-50%) scale(1.08);
    }

    .swiper-btn-prev { left: 14px; }
    .swiper-btn-next { right: 14px; }

    .swiper-pagination-books {
        position: static !important;
        margin-top: 30px !important;
        margin-bottom: 24px !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 8px !important;
        z-index: 5;
    }

    .swiper-pagination-books .swiper-pagination-bullet {
        background: rgba(255, 255, 255, 0.4);
        opacity: 1;
        width: 10px;
        height: 10px;
        transition: all 0.25s ease;
    }

    .swiper-pagination-books .swiper-pagination-bullet-active {
        background: #F5A623;
        width: 28px;
        border-radius: 6px;
    }

    /* Clean White Show More Button - polos putih elegan */
    .btn-show-more-books {
        background: #ffffff;
        color: #0f172a !important;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        font-size: clamp(13px, 0.85vw, 15px);
        padding: 10px 32px;
        border-radius: 50px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
        text-decoration: none;
        cursor: pointer;
    }

    .btn-show-more-books:hover {
        background: #f1f5f9;
        color: #0f172a !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
    }

    @media (max-width: 767.98px) {
        .book-slide {
            width: 205px !important;
            height: 305px !important;
        }
        .swiper-btn-prev { left: 4px; }
        .swiper-btn-next { right: 4px; }
        .swiper-nav-btn { width: 38px; height: 38px; font-size: 15px; }
    }

    /* =========================================================
       Section: Paket Penerbitan (Clean, Proportional, Centered & Human)
       Tema: Kuning (#F5A623) - Putih (#FFFFFF) - Hitam (#111827)
       ========================================================= */
    .pricing-section {
        background-color: #FFFFFF;
        padding-top: 5rem;
        padding-bottom: 5.5rem;
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

    /* Featured Card Subtle Highlight (Paket 10 Buku) */
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

    .btn-pricing-highlight:hover {
        background: #D97706;
        color: #FFFFFF;
        border-color: #D97706;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(217, 119, 6, 0.35);
    }

    /* =========================================================
       Section: Testimoni Clean Showcase (Matching Image 1)
       ========================================================= */
    .testimonial-header-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 2.2rem;
        flex-wrap: wrap;
        gap: 16px;
    }

    .testimonial-nav-btns {
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
        z-index: 10;
    }

    .btn-testimonial-nav {
        background: #ffffff;
        border: 1.5px solid rgba(245, 166, 35, 0.5);
        color: #1F2937;
        border-radius: 50px;
        padding: 7px 18px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.22s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        user-select: none;
        position: relative;
        z-index: 10;
        pointer-events: auto;
    }

    .btn-testimonial-nav:hover {
        background: #F5A623;
        border-color: #F5A623;
        color: #17120a;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(245, 166, 35, 0.3);
    }

    .testimonial-dots-wrap {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin: 0 6px;
    }

    .testimonial-dot {
        width: 10px;
        height: 10px;
        border-radius: 50px;
        background: #E5E7EB;
        border: none;
        padding: 0;
        cursor: pointer;
        transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .testimonial-dot:hover {
        background: #F5A623;
        transform: scale(1.15);
    }

    .testimonial-dot.active {
        width: 28px;
        background: #F5A623;
        border-radius: 50px;
    }

    .testimonial-showcase-card {
        background: #ffffff;
        border: 1px solid rgba(245, 166, 35, 0.28);
        border-radius: 24px;
        padding: clamp(24px, 3.5vw, 44px);
        box-shadow: 0 12px 36px rgba(16, 21, 29, 0.05);
        position: relative;
        overflow: hidden;
    }

    .testimonial-slide {
        display: none;
        animation: fadeInTestimonial 0.45s ease forwards;
    }

    .testimonial-slide.active {
        display: block;
    }

    @keyframes fadeInTestimonial {
        from {
            opacity: 0;
            transform: translateX(18px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .testimonial-slide-row {
        display: flex;
        align-items: center;
        gap: clamp(24px, 3.5vw, 54px);
    }

    .testimonial-slide-text {
        flex: 1 1 58%;
    }

    .testimonial-slide-photo {
        flex: 0 0 290px;
        max-width: 290px;
    }

    .testimonial-slide-img-box {
        width: 270px;
        height: 270px;
        max-width: 100%;
        border-radius: 22px;
        overflow: hidden;
        border: 4px solid #ffffff;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.08);
        background: #f1f5f9;
        position: relative;
        margin: 0 auto;
    }

    .testimonial-slide-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        display: block;
        transition: transform 0.4s ease;
    }

    .testimonial-showcase-card:hover .testimonial-slide-img {
        transform: scale(1.03);
    }

    .testimonial-stars-gold {
        color: #FBBF24;
        font-size: 20px;
        letter-spacing: 3px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
    }

    .testimonial-large-quote {
        font-family: 'Poppins', sans-serif;
        font-weight: 500;
        font-size: clamp(16px, 1.35vw, 22px);
        line-height: 1.62;
        color: #111827;
        margin-bottom: 26px;
    }

    .testimonial-author-name {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        font-size: 1.15rem;
        color: #111827;
        line-height: 1.25;
        margin-bottom: 3px;
    }

    .testimonial-author-title {
        font-size: 0.92rem;
        color: #64748B;
        font-weight: 500;
    }

    /* Mobile Responsive for Testimonial: Proportional 1:1 square photo, prominent stars and comments */
    @media (max-width: 991.98px) {
        .testimonial-slide-row {
            flex-direction: column;
            gap: 22px;
        }
        .testimonial-slide-photo {
            order: -1;
            flex: 0 0 100%;
            max-width: 100%;
            width: 100%;
        }
        .testimonial-slide-img-box {
            width: 230px;
            height: 230px;
            max-width: 100%;
            border-radius: 18px;
            border: 3px solid #ffffff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            margin: 0 auto;
        }
        .testimonial-slide-text {
            flex: 0 0 100%;
            width: 100%;
        }
        .testimonial-large-quote {
            font-size: 16px;
            margin-bottom: 18px;
        }
    }

    @media (max-width: 575.98px) {
        .testimonial-showcase-card {
            padding: 20px 18px;
            border-radius: 20px;
        }
        .testimonial-slide-img-box {
            width: 210px;
            height: 210px;
        }
        .testimonial-stars-gold {
            font-size: 18px;
            margin-bottom: 12px;
        }
        .testimonial-large-quote {
            font-size: 14.5px;
            line-height: 1.58;
        }
        .testimonial-author-name {
            font-size: 1rem;
        }
        .testimonial-author-title {
            font-size: 0.82rem;
        }
    }

    @media (max-width: 767.98px) {
        .testimonial-photo-col {
            min-height: 240px;
            max-height: 270px;
        }
        .testimonial-author-img {
            min-height: 240px;
        }
        .testimonial-content-col {
            padding: 20px 18px;
        }
        .rating-summary-card {
            padding: 18px 16px;
        }
    }

    /* =========================================================
       UI/UX Pro Max System: Accessibility, Focus & Motion Standards
       ========================================================= */
    :focus-visible {
        outline: 2.5px solid #F5A623 !important;
        outline-offset: 2.5px !important;
        border-radius: 4px;
    }

    button:focus:not(:focus-visible),
    a:focus:not(:focus-visible) {
        outline: none !important;
    }

    .btn,
    .btn-testimonial-nav,
    .testimonial-dot,
    .review-filter-tab,
    .btn-show-more-books,
    .open-book-modal {
        cursor: pointer;
    }

    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
    }

    /* Mobile Ergonomics & Minimum 44px Touch Targets */
    @media (max-width: 767.98px) {
        .btn-testimonial-nav {
            min-height: 44px;
            padding: 9px 20px;
        }
        .btn-pricing-action {
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .testimonial-dot {
            min-height: 24px;
            min-width: 16px;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section matching screenshot -->
<section class="hero-banner">
    <div class="container-fluid site-wide-container">
        <div class="row align-items-center g-4 g-xl-5">
            <!-- Left Column -->
            <div class="col-xl-6" data-aos="fade-right" data-aos-duration="850">
                <div class="hero-top-badge mb-3">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="flex-shrink-0" style="display: inline-block; vertical-align: middle;">
                        <path d="M12 1.5L14.7 3.7L18.1 4L19.5 7.1L22.2 9.2L21.7 12.6L23 15.7L20.5 18.1L19.8 21.5L16.4 21.6L14.2 24.1L12 22.8L9.8 24.1L7.6 21.6L4.2 21.5L3.5 18.1L1 15.7L2.3 12.6L1.8 9.2L4.5 7.1L5.9 4L9.3 3.7L12 1.5Z" fill="#F5A623"/>
                        <path d="M8 12.5L10.5 15L16 9" stroke="#10151D" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>PENERBIT BUKU AKADEMIK &amp; KREATIF</span>
                </div>
                <div class="hero-kicker mb-2">WELCOME TO PENA CENDEKIA</div>
                
                <h1 class="hero-main-title mb-3">
                    <span class="hero-title-top">PENYALUR ILMU</span>
                    <span class="hero-title-bottom">PENGETAHUAN</span>
                </h1>
                
                <p class="hero-lead mb-4">
                    Kami menyediakan beragam paket penerbitan profesional untuk mendukung publikasi karya ilmiah dan buku ajar Anda secara mudah.
                </p>
                
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="#tentang" class="btn btn-hero-gold">Tentang Kami</a>
                    <a href="{{ route('login') }}" class="btn btn-hero-translucent">Terbitkan Buku</a>
                </div>
                
                <div class="d-flex flex-wrap flex-xxl-nowrap gap-2 pt-2">
                    <span class="hero-feature-pill"><i class="bi bi-check2"></i> BER-ISBN RESMI</span>
                    <span class="hero-feature-pill"><i class="bi bi-check2"></i> PROSES 6 MINGGU</span>
                    <span class="hero-feature-pill"><i class="bi bi-check2"></i> PASOKAN STABIL</span>
                    <span class="hero-feature-pill"><i class="bi bi-check2"></i> ROYALTI TRANSPARAN</span>
                </div>
            </div>

            <!-- Right Column (Stats Glass Card) -->
            <div class="col-xl-6" data-aos="fade-left" data-aos-duration="850">
                <div class="hero-stats-card">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                        <div>
                            <div class="stats-sub-kicker">GAMBARAN PENERBIT</div>
                            <h3 class="stats-card-heading">Operasional yang Siap Bertumbuh</h3>
                        </div>
                        <div class="stats-card-icon">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="stat-mini-box">
                                <span class="stat-mini-label">JUDUL BUKU</span>
                                <div class="stat-mini-val">200+</div>
                                <span class="stat-mini-desc">Koleksi buku terbit ber-ISBN</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-mini-box">
                                <span class="stat-mini-label">JUMLAH PENULIS</span>
                                <div class="stat-mini-val">250+</div>
                                <span class="stat-mini-desc">Dosen, guru &amp; akademisi aktif</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-mini-box">
                                <span class="stat-mini-label">BUKU TERCETAK</span>
                                <div class="stat-mini-val">90.000+</div>
                                <span class="stat-mini-desc">Eksemplar terdistribusi</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-mini-box">
                                <span class="stat-mini-label">DISTRIBUTOR</span>
                                <div class="stat-mini-val">1+</div>
                                <span class="stat-mini-desc">Mitra jaringan distribusi nasional</span>
                            </div>
                        </div>
                    </div>

                    <div class="stats-card-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-geo-alt-fill text-warning fs-6"></i>
                            <span class="ms-1">Surabaya, Jawa Timur</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill text-warning fs-6"></i>
                            <span class="ms-1">Siap Produksi &amp; Terbit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tentang (Wide Container & Arched Photo Frame) -->
<section class="py-5" id="tentang">
    <div class="about-wide-container py-lg-4">
        <div class="row align-items-center gx-lg-4 gy-5 justify-content-between">
            <div class="col-lg-6 pe-lg-4" data-aos="fade-right" data-aos-duration="800">
                <div class="about-kicker">Tentang Kami</div>
                <h2 class="about-main-title mb-4">Penerbit Pena Cendekia: Wahana Penyalur Ilmu Pengetahuan</h2>
                <p class="about-desc mb-4">
                    Penerbit Pena Cendekia bermula dari banyaknya kebutuhan dosen untuk menerbitkan naskah buku sebagai buku teks atau buku referensi. Kami melakukan penelitian, diskusi dan benchmarking selama 2 (dua tahun) tahun mengenai proses produksi apa yang paling cocok untuk memenuhi kebutuhan ini. Pada akhirnya kami berhasil menemukan polanya dan ternyata sangat menakjubkan. Kami dapat memproses semua naskah buku hanya dalam waktu 6 (enam) minggu! Kami tidak hanya berinovasi dalam sistem produksi, kami juga mengembangkan sistem lain untuk memudahkan para Cendekia.
                </p>
                <div class="about-features-list">
                    <div class="about-feature-item">
                        <div class="about-icon-badge">
                            <i class="bi bi-clock"></i>
                        </div>
                        <span class="about-feature-text">Proses 6 minggu untuk karya ilmiah dan buku ajar</span>
                    </div>
                    <div class="about-feature-item">
                        <div class="about-icon-badge">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <span class="about-feature-text">Perjanjian kerja sama jelas dan sistem produksi terstruktur</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 ps-lg-4 ps-xl-5" data-aos="fade-left" data-aos-duration="800">
                <div class="about-arch-frame">
                    <div class="about-arch-backdrop"></div>
                    <div class="about-arch-deco"><i class="bi bi-flower1"></i></div>
                    <div class="about-arch-card">
                        <img src="{{ asset('images/about-person-new.jpg') }}" alt="Penerbit Pena Cendekia" class="about-arch-img" onerror="this.onerror=null;this.src='{{ asset('images/about-person.jpg') }}';">
                    </div>
                    <div class="about-arch-badge">
                        <div class="about-arch-badge-val">90.000+</div>
                        <div class="about-arch-badge-lbl">Buku Tercetak<br><small style="font-size: 9px; opacity: 0.9; text-transform: none; display: block; margin-top: 2px;">Eksemplar Beredar</small></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mengapa Pena Cendekia (Matching Image 1) -->
<section class="why-us-section" id="keunggulan">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="why-us-kicker">Mengapa Harus Memilih kami?</div>
            <h2 class="why-us-heading">Penerbit Pena Cendekia</h2>
        </div>
        <div class="row g-4 g-lg-5">
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                <div class="why-us-card">
                    <div class="why-us-icon-badge">
                        <i class="bi bi-lightning-charge"></i>
                    </div>
                    <h4 class="why-us-title">Proses Cepat &amp; Pasti</h4>
                    <p class="why-us-desc">
                        Pelayanan yang ramah dan cepat adalah prioritas kami. Kami memastikan proses produksi berjalan terstruktur agar buku Anda siap terbit tepat waktu.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                <div class="why-us-card">
                    <div class="why-us-icon-badge">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h4 class="why-us-title">Distribusi Luas</h4>
                    <p class="why-us-desc">
                        Buku-buku yang diterbitkan oleh Kelompok Pena Cendekia dapat ditemukan di toko buku offline dan online seluruh Indonesia.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                <div class="why-us-card">
                    <div class="why-us-icon-badge">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h4 class="why-us-title">Royalti &amp; Akuisisi</h4>
                    <p class="why-us-desc">
                        Dapatkan biaya royalti dan akuisisi dengan menerbitkan buku bersama Kelompok Pena Cendekia.
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                <div class="why-us-card">
                    <div class="why-us-icon-badge">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h4 class="why-us-title">Perjanjian yang Jelas</h4>
                    <p class="why-us-desc">
                        Segala perjanjian dan kerja sama dalam bentuk apapun akan diatur melalui Surat Perjanjian Kerjasama (SPK) sehingga setiap perjanjian terjamin aman.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service & Layanan (Pinterest & United Carriers Clean Modern Aesthetic + Image 2 Content) -->
<section class="service-section" id="service">
    <span id="layanan"></span>
    <div class="content-balanced-container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="section-kicker-gold mb-1">Service &amp; Layanan</div>
            <h2 class="section-title-dark mb-2">Solusi Lengkap Penerbitan &amp; Percetakan</h2>
            <p class="text-muted max-w-2xl mx-auto" style="font-size: 0.95rem; line-height: 1.6;">Pendampingan komprehensif bagi dosen, guru, peneliti, dan penulis mulai dari pengurusan ISBN resmi, tata letak, cetak presisi hingga legalitas HAKI.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Service 1: Penerbitan Buku -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="service-clean-card">
                    <div class="service-clean-top">
                        <span class="service-clean-num">01 / ISBN RESMI</span>
                        <div class="service-clean-icon">
                            <i class="bi bi-book-half"></i>
                        </div>
                    </div>
                    <h3 class="service-clean-title">Penerbitan Buku</h3>
                    <p class="service-clean-desc">
                        Semua buku yang diterbitkan di Pena Cendekia pasti memperoleh ISBN resmi dari Perpustakaan Nasional Republik Indonesia dan barcode terdaftar.
                    </p>
                    <div class="service-clean-footer">
                        <span class="service-clean-tag"><i class="bi bi-patch-check-fill text-warning"></i> Perpusnas RI</span>
                        <a href="{{ route('layanan.penerbitan') }}" class="service-clean-link">
                            Lihat Paket <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service 2: Konversi Karya Ilmiah -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="service-clean-card">
                    <div class="service-clean-top">
                        <span class="service-clean-num">02 / AKADEMIK</span>
                        <div class="service-clean-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                    </div>
                    <h3 class="service-clean-title">Konversi Karya Ilmiah</h3>
                    <p class="service-clean-desc">
                        Penerbit Pena Cendekia melayani konversi Karya Tulis Ilmiah (Skripsi, Tesis, Disertasi, dll) untuk diubah menjadi monograf atau buku referensi ber-ISBN.
                    </p>
                    <div class="service-clean-footer">
                        <span class="service-clean-tag"><i class="bi bi-mortarboard text-warning"></i> Skripsi, Tesis &amp; Disertasi</span>
                        <a href="{{ route('layanan.konversi') }}" class="service-clean-link">
                            Lihat Alur &amp; Biaya <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service 3: Cetak Buku Berkualitas -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="service-clean-card">
                    <div class="service-clean-top">
                        <span class="service-clean-num">03 / PERCETAKAN</span>
                        <div class="service-clean-icon">
                            <i class="bi bi-printer-fill"></i>
                        </div>
                    </div>
                    <h3 class="service-clean-title">Cetak Buku Berkualitas</h3>
                    <p class="service-clean-desc">
                        Buku yang kami terbitkan dicetak menggunakan lini mesin cetak modern offset &amp; digital press dengan hasil warna tajam dan jilid lem panas presisi tinggi.
                    </p>
                    <div class="service-clean-footer">
                        <span class="service-clean-tag"><i class="bi bi-shield-check text-warning"></i> Cetak Presisi Tinggi</span>
                        <a href="{{ route('layanan.cetak') }}" class="service-clean-link">
                            Lihat Mesin Cetak <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service 4: Jasa Editing Naskah -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="service-clean-card">
                    <div class="service-clean-top">
                        <span class="service-clean-num">04 / EDITORIAL</span>
                        <div class="service-clean-icon">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                    </div>
                    <h3 class="service-clean-title">Jasa Editing Naskah</h3>
                    <p class="service-clean-desc">
                        Penyuntingan naskah oleh editor berpengalaman untuk mengeliminasi typo, menyelaraskan kaidah PUEBI, dan menata kerancuan kalimat agar nyaman dibaca.
                    </p>
                    <div class="service-clean-footer">
                        <span class="service-clean-tag"><i class="bi bi-spellcheck text-warning"></i> Sesuai Kaidah PUEBI</span>
                        <a href="{{ route('layanan.editing') }}" class="service-clean-link">
                            Lihat Paket Editing <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service 5: Jasa Pengurusan HAKI -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="500">
                <div class="service-clean-card">
                    <div class="service-clean-top">
                        <span class="service-clean-num">05 / LEGALITAS</span>
                        <div class="service-clean-icon">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                    </div>
                    <h3 class="service-clean-title">Jasa Pengurusan HAKI</h3>
                    <p class="service-clean-desc">
                        Perlindungan hak cipta resmi DJKI Kemenkumham RI untuk mengamankan karya dari plagiarisme dan menambah poin angka kredit KUM Dosen hingga 40 poin.
                    </p>
                    <div class="service-clean-footer">
                        <span class="service-clean-tag"><i class="bi bi-award text-warning"></i> Sertifikat Resmi DJKI</span>
                        <a href="{{ route('layanan.hki') }}" class="service-clean-link">
                            Lihat Biaya HAKI <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Service 6: Kerjasama Institusi & Pengadaan -->
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="600">
                <div class="service-clean-card">
                    <div class="service-clean-top">
                        <span class="service-clean-num">06 / KEMITRAAN</span>
                        <div class="service-clean-icon">
                            <i class="bi bi-buildings-fill"></i>
                        </div>
                    </div>
                    <h3 class="service-clean-title">Kerjasama Institusi</h3>
                    <p class="service-clean-desc">
                        Penerbit Pena Cendekia membuka peluang kerjasama dengan kampus, institusi pendidikan, dan perpustakaan dalam hal penerbitan, pengadaan buku, dan workshop kepenulisan.
                    </p>
                    <div class="service-clean-footer">
                        <span class="service-clean-tag"><i class="bi bi-bank2 text-warning"></i> MoU &amp; Workshop</span>
                        <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Penerbit Pena Cendekia, kami tertarik menjalin kemitraan institusi / pengadaan perpustakaan.') }}" target="_blank" rel="noopener" class="service-clean-link">
                            Konsultasi <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Stats Counter Bar synchronized with Home Real Data -->
        <div class="service-stats-bar mt-5" data-aos="fade-up" data-aos-delay="200">
            <div class="row g-2 g-sm-3 g-lg-4 align-items-stretch">
                <div class="col-6 col-lg-3">
                    <div class="service-stat-card">
                        <div class="service-stat-icon">
                            <i class="bi bi-book-half"></i>
                        </div>
                        <div class="service-stat-info">
                            <div class="service-stat-val">200+</div>
                            <div class="service-stat-lbl">Judul Buku Terbit</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="service-stat-card">
                        <div class="service-stat-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="service-stat-info">
                            <div class="service-stat-val">250+</div>
                            <div class="service-stat-lbl">Penulis &amp; Akademisi</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="service-stat-card">
                        <div class="service-stat-icon">
                            <i class="bi bi-printer-fill"></i>
                        </div>
                        <div class="service-stat-info">
                            <div class="service-stat-val">90.000+</div>
                            <div class="service-stat-lbl">Eksemplar Tercetak</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <div class="service-stat-card">
                        <div class="service-stat-icon">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div class="service-stat-info">
                            <div class="service-stat-val">100%</div>
                            <div class="service-stat-lbl">Resmi Ber-ISBN</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@php
    $portfolioBooks = \App\Http\Controllers\PublicController::getPortfolioBooksList();
    // Gandakan list jika buku kurang dari 6 agar efek melengkung 3D Coverflow tetap kaya dan simetris
    $carouselSlides = count($portfolioBooks) < 6 ? array_merge($portfolioBooks, $portfolioBooks) : $portfolioBooks;
@endphp

<!-- Portofolio 3D Coverflow Carousel (Image 1) -->
<section class="portfolio-section" id="karya">
    <div class="container">
        <div class="text-center mb-3" data-aos="fade-up">
            <div class="portfolio-kicker">Koleksi Terbitan Kami</div>
            <h2 class="portfolio-title mb-2">Buku Pilihan Pena Cendekia</h2>
            
            <div class="portfolio-desc-wrapper">
                <p class="portfolio-sub mb-0">
                    Pena Cendekia adalah penerbit independen yang berfokus pada buku-buku budaya, sastra, pemikiran, psikologi perkembangan, buku ajar dan buku referensi. Namun Pena Cendekia juga menerima buku-buku fiksi seperti prosa, novel, dan puisi. Pena Cendekia menerbitkan buku-buku terjemahan maupun naskah asli dalam bahasa Indonesia.
                </p>
            </div>
        </div>

        <!-- 3D Coverflow Container -->
        <div class="book-coverflow-wrapper" data-aos="fade-up" data-aos-delay="150">
            <!-- Nav buttons -->
            <button class="swiper-nav-btn swiper-btn-prev" aria-label="Buku Sebelumnya">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="swiper-nav-btn swiper-btn-next" aria-label="Buku Berikutnya">
                <i class="bi bi-chevron-right"></i>
            </button>

            <!-- Swiper Main -->
            <div class="swiper book-coverflow-swiper">
                <div class="swiper-wrapper">
                    @foreach($carouselSlides as $book)
                        <div class="swiper-slide book-slide">
                            <div class="book-3d-item open-book-modal"
                                 data-id="{{ $book['id'] }}"
                                 data-title="{{ $book['title'] }}"
                                 data-sub="{{ $book['sub'] }}"
                                 data-category="{{ $book['category'] }}"
                                 data-cover="{{ str_starts_with($book['cover'], 'http') ? $book['cover'] : asset($book['cover']) }}"
                                 data-author="{{ $book['author'] }}"
                                 data-editor="{{ $book['editor'] }}"
                                 data-isbn="{{ $book['isbn'] }}"
                                 data-year="{{ $book['year'] }}"
                                 data-pages="{{ $book['pages'] }}"
                                 data-size="{{ $book['size'] }}"
                                 data-synopsis="{{ $book['synopsis'] }}"
                                 title="Klik untuk melihat sinopsis {{ $book['title'] }}">
                                <img src="{{ str_starts_with($book['cover'], 'http') ? $book['cover'] : asset($book['cover']) }}" alt="Cover {{ $book['title'] }}">
                                <div class="book-overlay-info">
                                    <div class="book-mini-title">{{ $book['title'] }}</div>
                                    <button type="button" class="btn-book-inspect">
                                        <i class="bi bi-book-half"></i> Baca Sinopsis
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Swiper Pagination (Garis / Titik Halaman Buku) -->
            <div class="swiper-pagination swiper-pagination-books"></div>

            <!-- Tombol Show More (Membuka Halaman Baru Portofolio Buku) -->
            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <a href="{{ route('portofolio') }}" class="btn btn-show-more-books" title="Lihat Seluruh Katalog Portofolio Buku">
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i>
                    <span>Show More</span>
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Paket Penerbitan Buku (Resmi penacendekia.com/p/menerbitkan-buku.html) -->
<section class="pricing-section" id="paket">
    <div class="content-balanced-container">
        <!-- Section Header (Clean, Centered, Proportional) -->
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="section-kicker-gold mb-2">PAKET PENERBITAN BUKU</div>
            <h2 class="section-title-dark mb-3">Pilihan Paket Penerbitan Resmi</h2>
            <p class="text-muted mx-auto" style="max-width: 680px; font-size: 15px; line-height: 1.6;">
                Abadikan gagasan Anda menjadi buku cetak berkualitas tinggi. Terdaftar resmi di Perpustakaan Nasional RI dan didukung jaminan 99% naskah pasti terbit.
            </p>
        </div>

        <!-- 6 Official Packages Grid -->
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
                    <a href="{{ route('login') }}" class="btn-pricing-action">
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
                    <a href="{{ route('login') }}" class="btn-pricing-action">
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
                    <a href="{{ route('login') }}" class="btn-pricing-action btn-pricing-highlight">
                        Pilih Paket
                    </a>
                </div>
            </div>

            <!-- 4. Paket 25 Buku -->
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="pricing-card">
                    <div class="pricing-card-header">
                        <h3 class="pricing-tier-title">Paket 25 Buku</h3>
                    </div>
                    <div class="pricing-price-box">
                        <div class="pricing-price-num"><span>Rp</span>1.750.000</div>
                        <div class="pricing-price-desc">Maksimal 150 Halaman &bull; Standar UNESCO / A5</div>
                    </div>
                    <hr class="pricing-clean-divider">
                    <ul class="pricing-features">
                        <li><i class="bi bi-check2-circle"></i><span>Free ISBN &amp; Barcode Resmi Perpusnas</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Desain Kover Profesional</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Layout Berstandar Tinggi</span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Sertifikat Penulis</span></li>
                        <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 25 Eksemplar</strong></span></li>
                        <li><i class="bi bi-check2-circle"></i><span>Diskon Pengurusan HAKI Rp 50.000</span></li>
                    </ul>
                    <a href="{{ route('login') }}" class="btn-pricing-action">
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
                    <a href="{{ route('login') }}" class="btn-pricing-action">
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
                    <a href="{{ route('login') }}" class="btn-pricing-action">
                        Pilih Paket
                    </a>
                </div>
            </div>
        </div>

        <!-- Trust Note / Layanan Khusus (Sederhana & Bersih) -->
        <div class="text-center mt-5" data-aos="fade-up">
            <p class="text-muted small mb-2">
                <i class="bi bi-patch-check-fill text-warning me-1"></i> Penerbit Pena Cendekia Resmi Anggota IKAPI No. 379/JTI/2023.
            </p>
            <p class="text-muted small mb-0">
                Butuh paket khusus atau cetak di atas 100 eksemplar? 
                <a href="https://wa.me/6287850099491?text={{ urlencode('Halo Admin Pena Cendekia, saya ingin konsultasi paket penerbitan khusus / cetak skala besar.') }}"
                   target="_blank" rel="noopener" class="text-dark fw-bold text-decoration-underline">
                    Konsultasikan langsung dengan Admin kami
                </a>
            </p>
        </div>
    </div>
</section>

@if(isset($testimonials) && $testimonials->isNotEmpty())
<!-- Testimoni Penulis (Warm Cream Background Matching Tentang Kami) -->
<section class="pt-5 pb-5" id="testimoni" style="background-color: var(--bs-body-bg, #FBF9F5);">
    <div class="about-wide-container pt-2 pb-5">
        <!-- Section Header with Previous / Next navigation buttons -->
        <div class="testimonial-header-row" data-aos="fade-up">
            <div>
                <div class="section-kicker-gold mb-1">APA KATA PENULIS KAMI</div>
                <h2 class="section-title-dark mb-0">Kata Para Penulis</h2>
            </div>
            <div class="testimonial-nav-btns">
                <button type="button" class="btn-testimonial-nav" id="prevTestimonialBtn" aria-label="Testimoni Sebelumnya">
                    <i class="bi bi-chevron-left me-1"></i> Previous
                </button>
                <div class="testimonial-dots-wrap" id="testimonialDotsWrap">
                    @foreach($testimonials as $idx => $item)
                        <button type="button" class="testimonial-dot {{ $idx === 0 ? 'active' : '' }}" data-index="{{ $idx }}" aria-label="Penulis {{ $idx + 1 }}: {{ $item->name }}"></button>
                    @endforeach
                </div>
                <button type="button" class="btn-testimonial-nav" id="nextTestimonialBtn" aria-label="Testimoni Berikutnya">
                    Next <i class="bi bi-chevron-right ms-1"></i>
                </button>
            </div>
        </div>

        <!-- Featured Testimonial Showcase Card (Clean Card, Auto-sliding Silently) -->
        <div class="testimonial-showcase-card" data-aos="fade-up" data-aos-delay="100">
            @foreach($testimonials as $idx => $item)
                <div class="testimonial-slide {{ $idx === 0 ? 'active' : '' }}" data-slide="{{ $idx }}">
                    <div class="testimonial-slide-row">
                        <!-- Left Text Content in Desktop, Below Photo in Mobile -->
                        <div class="testimonial-slide-text">
                            <div class="testimonial-stars-gold" title="Rating {{ $item->rating ?? 5 }} dari 5 Bintang">
                                @for($s = 0; $s < ($item->rating ?? 5); $s++)
                                    <i class="bi bi-star-fill"></i>
                                @endfor
                            </div>
                            <blockquote class="testimonial-large-quote">
                                “{{ $item->quote }}”
                            </blockquote>
                            <div>
                                <h4 class="testimonial-author-name">{{ $item->name }}</h4>
                                <div class="testimonial-author-title">{{ $item->role }}</div>
                            </div>
                        </div>

                        <!-- Right Photo in Desktop, Top Photo in Mobile (HD & Crisp) -->
                        <div class="testimonial-slide-photo">
                            <div class="testimonial-slide-img-box">
                                <img src="{{ $item->photo_url }}"
                                     alt="Foto {{ $item->name }}"
                                     class="testimonial-slide-img"
                                     onerror="this.onerror=null;this.src='{{ asset('images/about-author.png') }}';">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA -->
<section class="py-5 text-white text-center" style="background-color: #271E17;" data-aos="fade-up">
    <div class="container py-4">
        <h2 class="display-6 fw-bold font-serif mb-3">Siap Menerbitkan Karya Pertama Anda?</h2>
        <p class="lead mx-auto mb-4" style="color: #c9b9ad; max-width: 620px;">
            Daftarkan diri sebagai penulis di PT. Pena Cendikia dan bawa naskah Anda ke hadapan pembaca.
        </p>
        <a href="{{ route('register') }}" class="btn btn-brand btn-lg px-4 px-sm-5 py-3 rounded-pill shadow">
            Daftar Sebagai Penulis Sekarang <i class="bi bi-arrow-right ms-2"></i>
        </a>
    </div>
</section>

<!-- Tombol WhatsApp Mengambang (Konsultasi) -->
<a href="https://wa.me/6287850099491" target="_blank" rel="noopener" class="wa-float" aria-label="Konsultasi via WhatsApp" title="Konsultasi via WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<!-- Modal Sinopsis Buku (Pop up interaktif saat buku diklik) -->
<div class="modal fade" id="bookDetailModal" tabindex="-1" aria-labelledby="bookDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden; background: #ffffff;">
            <div class="modal-header border-0 pb-0 pt-4 px-4 px-md-5">
                <span class="badge bg-warning-subtle text-dark border border-warning px-3 py-1 rounded-pill fw-semibold" id="modalBookCategory">Buku Ajar</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4 p-md-5 pt-3">
                <div class="row g-4 align-items-start">
                    <div class="col-md-5 text-center">
                        <div class="book-3d-item mx-auto shadow-lg" style="width: 220px; height: 330px; border-radius: 4px 14px 14px 4px; overflow: hidden;">
                            <img src="" id="modalBookCover" alt="Cover Buku" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <h4 class="fw-bold mb-1 text-dark" id="modalBookTitle" style="font-family: 'Poppins', sans-serif;"></h4>
                        <p class="text-muted small mb-3 fst-italic" id="modalBookSub"></p>
                        
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-upc-scan me-1 text-primary"></i> <span id="modalBookIsbn"></span></span>
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-calendar3 me-1 text-warning"></i> <span id="modalBookYear">2024</span></span>
                            <span class="badge bg-light text-dark border px-2 py-1"><i class="bi bi-file-earmark-text me-1 text-success"></i> <span id="modalBookPages"></span></span>
                        </div>

                        <div class="p-3 rounded-3 bg-light mb-3" style="font-size: 12.5px;">
                            <div class="mb-1"><strong>Penulis:</strong> <span id="modalBookAuthor" class="text-secondary"></span></div>
                            <div><strong>Editor:</strong> <span id="modalBookEditor" class="text-secondary"></span></div>
                        </div>

                        <h6 class="fw-bold text-dark mb-2" style="font-size: 13.5px;"><i class="bi bi-justify-left me-1 text-warning"></i> Sinopsis Lengkap</h6>
                        <p class="text-muted small mb-4" id="modalBookSynopsis" style="line-height: 1.7; text-align: justify; max-height: 190px; overflow-y: auto; padding-right: 6px;"></p>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="https://wa.me/6287850099491" target="_blank" rel="noopener" class="btn btn-brand rounded-pill px-4 btn-sm" id="modalBookWa">
                                <i class="bi bi-whatsapp me-1"></i> Konsultasi Terbitkan Naskah Serupa
                            </a>
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-3 btn-sm" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Swiper 3D Coverflow untuk Buku Portofolio (stretch: 0 agar buku proporsional & tidak gepeng)
        if (typeof Swiper !== 'undefined') {
            const bookSwiper = new Swiper('.book-coverflow-swiper', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 'auto',
                loop: true,
                slideToClickedSlide: true,
                speed: 600,
                autoplay: {
                    delay: 6000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                },
                coverflowEffect: {
                    rotate: 16,        // Sudut kemiringan natural dan elegan
                    stretch: 0,        // Zero stretch mencegah buku terlihat gepeng/tertekan secara horizontal
                    depth: 130,        // Kedalaman 3D proporsional
                    modifier: 1.0,
                    slideShadows: true,
                },
                navigation: {
                    nextEl: '.swiper-btn-next',
                    prevEl: '.swiper-btn-prev',
                },
                pagination: {
                    el: '.swiper-pagination-books',
                    clickable: true,
                    dynamicBullets: true,
                },
                keyboard: {
                    enabled: true,
                },
                mousewheel: {
                    forceToAxis: true,
                    releaseOnEdges: true,
                    sensitivity: 1,
                },
                on: {
                    // Hanya tampilkan 7 buku simetris (3 kiri, 1 tengah, 3 kanan).
                    // Sembunyikan buku ke-8 di paling ujung kiri agar rapi dan seimbang.
                    setTranslate: function () {
                        const swiper = this;
                        for (let i = 0; i < swiper.slides.length; i++) {
                            const slide = swiper.slides[i];
                            const progress = Math.abs(slide.progress || 0);
                            if (progress > 3.25) {
                                slide.style.opacity = '0';
                                slide.style.pointerEvents = 'none';
                                slide.style.visibility = 'hidden';
                            } else {
                                slide.style.opacity = '1';
                                slide.style.pointerEvents = 'auto';
                                slide.style.visibility = 'visible';
                            }
                        }
                    },
                    setTransition: function (swiper, duration) {
                        for (let i = 0; i < swiper.slides.length; i++) {
                            swiper.slides[i].style.transitionDuration = duration + 'ms';
                        }
                    }
                }
            });

            // Gliding simetris & mulus pada pergerakan kursor mouse (kiri dan kanan berjalan seimbang tanpa glitch)
            let hoverGlideTimer = null;
            const swiperContainer = document.querySelector('.book-coverflow-swiper');
            const slideElements = document.querySelectorAll('.book-coverflow-swiper .swiper-slide');

            slideElements.forEach(function (slide) {
                slide.addEventListener('mouseenter', function () {
                    if (window.innerWidth < 992) return; // Hanya di layar desktop/laptop
                    if (slide.classList.contains('swiper-slide-active')) return;

                    clearTimeout(hoverGlideTimer);
                    hoverGlideTimer = setTimeout(function () {
                        if (!swiperContainer || slide.classList.contains('swiper-slide-active')) return;
                        
                        const containerRect = swiperContainer.getBoundingClientRect();
                        const containerCenter = containerRect.left + (containerRect.width / 2);
                        const slideRect = slide.getBoundingClientRect();
                        const slideCenter = slideRect.left + (slideRect.width / 2);

                        // Geser secara presisi mengikuti arah sisi (kiri vs kanan)
                        if (slideCenter < containerCenter - 40) {
                            bookSwiper.slidePrev(550);
                        } else if (slideCenter > containerCenter + 40) {
                            bookSwiper.slideNext(550);
                        }
                    }, 130);
                });

                slide.addEventListener('mouseleave', function () {
                    clearTimeout(hoverGlideTimer);
                });
            });

            // Handle Book Click to Open Modal with Synopsis & Data
            const bookModalEl = document.getElementById('bookDetailModal');
            const bookModal = bookModalEl ? new bootstrap.Modal(bookModalEl) : null;

            document.querySelectorAll('.open-book-modal').forEach(function (bookCard) {
                bookCard.addEventListener('click', function (e) {
                    if (bookModal) {
                        document.getElementById('modalBookCover').src = bookCard.dataset.cover || '';
                        document.getElementById('modalBookTitle').textContent = bookCard.dataset.title || '';
                        document.getElementById('modalBookSub').textContent = bookCard.dataset.sub || '';
                        document.getElementById('modalBookCategory').textContent = bookCard.dataset.category || 'Karya Terbitan';
                        document.getElementById('modalBookIsbn').textContent = 'ISBN: ' + (bookCard.dataset.isbn || '-');
                        document.getElementById('modalBookYear').textContent = bookCard.dataset.year || '2024';
                        document.getElementById('modalBookPages').textContent = bookCard.dataset.pages || '-';
                        document.getElementById('modalBookAuthor').textContent = bookCard.dataset.author || '-';
                        document.getElementById('modalBookEditor').textContent = bookCard.dataset.editor || '-';
                        document.getElementById('modalBookSynopsis').textContent = bookCard.dataset.synopsis || '';
                        
                        const waUrl = 'https://wa.me/6285785522283?text=' + encodeURIComponent('Halo Penerbit Pena Cendekia, saya tertarik dengan buku "' + bookCard.dataset.title + '" (ISBN: ' + (bookCard.dataset.isbn || '') + '). Saya ingin konsultasi penerbitan naskah saya.');
                        document.getElementById('modalBookWa').href = waUrl;

                        const detailLink = document.getElementById('modalBookDetailLink');
                        if (detailLink) {
                            detailLink.href = '/buku/' + (bookCard.dataset.id || '');
                        }

                        bookModal.show();
                    }
                });
            });

            // Buka Sinopsis dari Modal Katalog Seluruh Buku
            document.querySelectorAll('.open-synopsis-from-catalog').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const bookId = btn.dataset.id;
                    const allBooksModalEl = document.getElementById('allBooksModal');
                    if (allBooksModalEl) {
                        const allModal = bootstrap.Modal.getInstance(allBooksModalEl);
                        if (allModal) allModal.hide();
                    }
                    
                    const bookCard = document.querySelector(`.open-book-modal[data-id="${bookId}"]`);
                    if (bookCard) {
                        setTimeout(function () {
                            bookCard.click();
                        }, 350);
                    }
                });
            });
        }

        /* =========================================================
           Interaktif: Testimonial Showcase Slider (Auto-slide 6 Detik & Indikator 3 Penulis)
           ========================================================= */
        const testimonialSlides = document.querySelectorAll('.testimonial-slide');
        const prevTestimonialBtn = document.getElementById('prevTestimonialBtn');
        const nextTestimonialBtn = document.getElementById('nextTestimonialBtn');
        const testimonialDots = document.querySelectorAll('.testimonial-dot');
        const testimonialCounter = document.getElementById('testimonialSlideCounter');
        let currentTestimonialIdx = 0;
        let testimonialAutoTimer = null;

        if (testimonialSlides.length > 0) {
            function showTestimonialSlide(index) {
                if (index < 0) {
                    currentTestimonialIdx = testimonialSlides.length - 1;
                } else if (index >= testimonialSlides.length) {
                    currentTestimonialIdx = 0;
                } else {
                    currentTestimonialIdx = index;
                }

                testimonialSlides.forEach(function (slide, idx) {
                    if (idx === currentTestimonialIdx) {
                        slide.classList.add('active');
                    } else {
                        slide.classList.remove('active');
                    }
                });

                if (testimonialDots.length > 0) {
                    testimonialDots.forEach(function (dot, idx) {
                        dot.classList.toggle('active', idx === currentTestimonialIdx);
                    });
                }

                if (testimonialCounter) {
                    testimonialCounter.innerHTML = `<i class="bi bi-person-fill text-warning me-1"></i> Penulis ${currentTestimonialIdx + 1} dari ${testimonialSlides.length}`;
                }
            }

            function startTestimonialAutoSlide() {
                stopTestimonialAutoSlide();
                testimonialAutoTimer = setInterval(function () {
                    showTestimonialSlide(currentTestimonialIdx + 1);
                }, 6000); // Geser otomatis tepat setiap 6 detik
            }

            function stopTestimonialAutoSlide() {
                if (testimonialAutoTimer) {
                    clearInterval(testimonialAutoTimer);
                    testimonialAutoTimer = null;
                }
            }

            if (prevTestimonialBtn) {
                prevTestimonialBtn.addEventListener('click', function () {
                    showTestimonialSlide(currentTestimonialIdx - 1);
                    startTestimonialAutoSlide();
                });
            }

            if (nextTestimonialBtn) {
                nextTestimonialBtn.addEventListener('click', function () {
                    showTestimonialSlide(currentTestimonialIdx + 1);
                    startTestimonialAutoSlide();
                });
            }

            if (testimonialDots.length > 0) {
                testimonialDots.forEach(function (dot) {
                    dot.addEventListener('click', function () {
                        const targetIdx = parseInt(this.getAttribute('data-index'));
                        if (!isNaN(targetIdx)) {
                            showTestimonialSlide(targetIdx);
                            startTestimonialAutoSlide();
                        }
                    });
                });
            }

            // Touch Swipe support for Mobile
            const showcaseCard = document.querySelector('.testimonial-showcase-card');
            if (showcaseCard) {
                let touchStartX = 0;
                let touchEndX = 0;

                showcaseCard.addEventListener('touchstart', function (e) {
                    touchStartX = e.changedTouches[0].screenX;
                    stopTestimonialAutoSlide();
                }, { passive: true });

                showcaseCard.addEventListener('touchend', function (e) {
                    touchEndX = e.changedTouches[0].screenX;
                    if (touchEndX < touchStartX - 50) {
                        showTestimonialSlide(currentTestimonialIdx + 1);
                    } else if (touchEndX > touchStartX + 50) {
                        showTestimonialSlide(currentTestimonialIdx - 1);
                    }
                    startTestimonialAutoSlide();
                }, { passive: true });

                // Pause on hover di desktop agar user nyaman membaca ulasan
                showcaseCard.addEventListener('mouseenter', stopTestimonialAutoSlide);
                showcaseCard.addEventListener('mouseleave', startTestimonialAutoSlide);
            }

            // Inisialisasi slide pertama dan mulai auto-slide 6 detik
            showTestimonialSlide(0);
            startTestimonialAutoSlide();
        }

    });
</script>
@endpush

