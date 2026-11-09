<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Penerbitan Buku Mandiri & Berkualitas') — PT. Pena Cendikia</title>

    <!-- Google Fonts (Poppins & Plus Jakarta Sans for Hero) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800;900&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Swiper 11 CSS (for 3D Coverflow Book Carousel) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <style>
        :root {
            --bs-body-bg: #FBF9F5;
            --bs-body-color: #3D352E;
            --brand-primary: #8B5E3C;
            --brand-primary-hover: #734C2F;
            --brand-secondary: #D4A373;
            --brand-cream: #FDFBF7;
            --brand-soft-peach: #FAEDCD;
            --brand-soft-blue: #E2ECE9;
            --brand-sage: #CCD5AE;
            --brand-dark: #2B2118;
            --bs-body-font-family: 'Poppins', sans-serif;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6, .font-serif {
            font-family: 'Poppins', sans-serif;
        }

        .btn-brand {
            background-color: var(--brand-primary);
            color: #ffffff;
            font-weight: 500;
            border-radius: 10px;
            padding: 10px 22px;
            border: none;
            transition: all 0.2s ease-in-out;
        }

        .btn-brand:hover {
            background-color: var(--brand-primary-hover);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(139, 94, 60, 0.25);
        }

        .btn-outline-brand {
            color: var(--brand-primary);
            border: 1.5px solid var(--brand-primary);
            border-radius: 10px;
            padding: 9px 20px;
            font-weight: 500;
            background: transparent;
            transition: all 0.2s;
        }

        .btn-outline-brand:hover {
            background-color: var(--brand-primary);
            color: #ffffff;
        }

        .card-soft {
            background: #ffffff;
            border: 1px solid rgba(220, 210, 198, 0.45);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(70, 50, 30, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-soft:hover {
            box-shadow: 0 8px 30px rgba(70, 50, 30, 0.08);
        }

        .navbar-custom {
            background-color: #0d1217;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            transition: background-color 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
        }

        .navbar-custom.is-home {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            background-color: transparent !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            box-shadow: none;
        }

        .navbar-custom.is-home.scrolled {
            background-color: rgba(13, 18, 24, 0.96) !important;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
        }

        .brand-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: clamp(13px, 0.95vw, 16.5px);
            letter-spacing: -0.2px;
            color: #F5A623;
            line-height: 1.15;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .brand-subtitle {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: clamp(7.5px, 0.5vw, 9.5px);
            letter-spacing: 0.8px;
            color: rgba(255, 255, 255, 0.78);
            line-height: 1.15;
            margin-top: 1px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .navbar-brand-logo {
            height: clamp(28px, 1.95vw, 38px);
            width: auto;
            flex-shrink: 0;
        }

        .site-wide-container {
            width: 82.5% !important;
            max-width: 2200px;
            margin-left: auto !important;
            margin-right: auto !important;
            padding-left: 10px !important;
            padding-right: 10px !important;
        }

        @media (max-width: 1699.98px) {
            .site-wide-container {
                width: 90% !important;
            }
        }

        @media (max-width: 1399.98px) {
            .site-wide-container {
                width: 94% !important;
            }
        }

        @media (max-width: 1199.98px) {
            .site-wide-container {
                width: 100% !important;
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
        }

        @media (max-width: 575.98px) {
            .site-wide-container {
                padding-left: 14px !important;
                padding-right: 14px !important;
            }
            .brand-title {
                font-size: 14px;
            }
            .brand-subtitle {
                font-size: 8px;
                letter-spacing: 0.5px;
            }
            .navbar-brand-logo {
                height: 34px;
            }
        }

        .navbar-custom .navbar-nav {
            position: relative;
        }

        .navbar-custom .nav-link {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.88) !important;
            padding: 5px clamp(6px, 0.8vw, 14px) !important;
            font-size: clamp(12.5px, 0.82vw, 15px);
            position: relative;
            transition: color 0.15s ease;
            white-space: nowrap;
        }

        .navbar-custom .nav-link:hover {
            color: #ffffff !important;
        }

        .navbar-custom .nav-link.active {
            color: #ffffff !important;
            font-weight: 700;
        }

        /* Fallback static underline before JS initializes */
        .navbar-custom .navbar-nav:not(.sliding-ready) .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 18px;
            right: 18px;
            height: 3px;
            background-color: #F5A623;
            border-radius: 4px;
        }

        /* Sliding underline indicator */
        .nav-slide-line {
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 3px;
            background-color: #F5A623;
            border-radius: 4px;
            pointer-events: none;
            opacity: 0;
            transition: left 0.28s cubic-bezier(0.25, 1, 0.5, 1),
                        width 0.28s cubic-bezier(0.25, 1, 0.5, 1),
                        opacity 0.2s ease;
        }

        @media (max-width: 1199.98px) {
            .navbar-custom .nav-link {
                font-size: 16px;
                padding: 10px 14px !important;
            }
            .navbar-custom .nav-link.active::after,
            .nav-slide-line {
                display: none !important;
            }
            .navbar-custom .navbar-collapse {
                background-color: rgba(18, 24, 32, 0.98);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border-radius: 16px;
                padding: 16px;
                margin-top: 12px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                max-height: calc(100vh - 90px);
                overflow-y: auto;
            }
        }

        /* Dropdown Interactive & Popup Styling */
        .navbar-custom .nav-item.dropdown {
            position: relative;
        }

        .navbar-custom .dropdown-menu {
            background: rgba(16, 21, 28, 0.88);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.45), 0 0 0 1px rgba(255, 255, 255, 0.05);
            padding: 8px;
            min-width: 240px;
            margin-top: 4px;
            transition: opacity 0.2s ease, transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.2s;
        }

        /* Hover-bridge: invisible zone covering the space between nav-link and dropdown-menu so cursor never loses hover */
        .navbar-custom .dropdown-menu::before {
            content: '';
            position: absolute;
            top: -20px;
            left: 0;
            right: 0;
            height: 20px;
            background: transparent;
        }

        @media (min-width: 1200px) {
            .navbar-custom .nav-item.dropdown .dropdown-menu {
                display: block;
                opacity: 0;
                visibility: hidden;
                transform: translateY(10px) scale(0.97);
                pointer-events: none;
            }
            .navbar-custom .nav-item.dropdown:hover .dropdown-menu,
            .navbar-custom .nav-item.dropdown.is-open .dropdown-menu,
            .navbar-custom .nav-item.dropdown .dropdown-menu.show {
                opacity: 1;
                visibility: visible;
                transform: translateY(0) scale(1);
                pointer-events: auto;
            }
        }

        .navbar-custom .dropdown-item {
            color: rgba(255, 255, 255, 0.88);
            border-radius: 10px;
            padding: 9px 16px;
            font-size: 14.5px;
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .navbar-custom .dropdown-item i {
            display: inline-block;
            font-size: 16px;
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.2s ease;
        }

        .navbar-custom .dropdown-item:hover {
            background-color: rgba(245, 166, 35, 0.14);
            color: #F5A623;
            transform: translateX(4px);
        }

        .navbar-custom .dropdown-item:hover i {
            animation: iconFloatUpDown 0.6s ease-in-out infinite alternate;
            color: #F5A623 !important;
        }

        @keyframes iconFloatUpDown {
            0% {
                transform: translateY(0) scale(1.1);
            }
            100% {
                transform: translateY(-4px) scale(1.25);
            }
        }

        .navbar-custom .dropdown-toggle::after {
            display: inline-block;
            vertical-align: 0.18em;
            margin-left: 0.45em;
            font-size: 11px;
            transition: transform 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .navbar-custom .nav-item.dropdown:hover .dropdown-toggle::after,
        .navbar-custom .nav-item.dropdown.is-open .dropdown-toggle::after,
        .navbar-custom .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg) translateY(2px);
        }

        .btn-masuk-pill {
            background-color: #F5A623;
            color: #17120a !important;
            font-weight: 700;
            font-size: clamp(12px, 0.8vw, 14px);
            border-radius: 50px;
            padding: 6px clamp(14px, 1vw, 20px);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-masuk-pill:hover {
            background-color: #e2971a;
            color: #17120a !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(245, 166, 35, 0.35);
        }

        /* Astra-Inspired Modern Dark Grid Footer with Subtle Side Margins and Full Bottom */
        .footer-astra-wrapper {
            background-color: var(--bs-body-bg, #FBF9F5);
            padding: 0 clamp(6px, 0.75vw, 10px) 0;
            margin-top: clamp(40px, 6vh, 70px);
            margin-bottom: 0;
            width: 100%;
            box-sizing: border-box;
        }

        .footer-astra-shell {
            background-color: #0A0E17;
            background-image: 
                linear-gradient(to right, rgba(255, 255, 255, 0.035) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.035) 1px, transparent 1px);
            background-size: 38px 38px;
            border-radius: clamp(12px, 1.2vw, 18px) clamp(12px, 1.2vw, 18px) 0 0;
            border: 1.5px solid rgba(245, 166, 35, 0.35);
            border-bottom: none;
            color: #FFFFFF;
            position: relative;
            overflow: hidden;
            box-shadow: 0 -6px 28px rgba(10, 14, 23, 0.1);
            padding-top: clamp(40px, 5vw, 64px);
            padding-bottom: 24px;
            width: 100%;
            margin: 0;
        }

        .footer-astra-container {
            width: 100%;
            max-width: 1720px;
            margin: 0 auto;
            padding-left: clamp(20px, 4vw, 64px);
            padding-right: clamp(20px, 4vw, 64px);
            box-sizing: border-box;
        }

        .footer-ambient-dot {
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: rgba(245, 166, 35, 0.5);
            box-shadow: 0 0 14px rgba(245, 166, 35, 0.8);
            pointer-events: none;
        }
        .footer-ambient-dot.dot-left {
            top: 45%;
            left: 14px;
        }
        .footer-ambient-dot.dot-right {
            bottom: 35%;
            right: 14px;
        }

        .footer-brand-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 1.45rem;
            letter-spacing: -0.3px;
            color: #FFFFFF;
        }
        .footer-brand-bio {
            color: rgba(255, 255, 255, 0.72);
            font-size: 13.5px;
            line-height: 1.65;
            max-width: 420px;
            margin-bottom: 22px;
        }

        /* White Circular Social Icons */
        .footer-social-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 26px;
        }
        .footer-social-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #FFFFFF;
            color: #0A0E17;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }
        .footer-social-pill:hover {
            background-color: #F5A623;
            color: #111827;
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(245, 166, 35, 0.45);
        }

        /* Trust & Accreditation Reviews Row */
        .footer-trust-lbl {
            font-size: 11.5px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.5);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .footer-trust-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        .footer-trust-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 11.5px;
            font-weight: 600;
            color: #FFFFFF;
            transition: all 0.2s ease;
        }
        .footer-trust-badge:hover {
            border-color: rgba(245, 166, 35, 0.5);
            background: rgba(245, 166, 35, 0.1);
        }

        /* Menu Columns Styling */
        .footer-col-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 0.6px;
            margin-bottom: 18px;
        }
        .footer-col-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .footer-col-list li a, .footer-col-list li span {
            color: rgba(255, 255, 255, 0.68);
            font-size: 13.5px;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-block;
            line-height: 1.45;
        }
        .footer-col-list li a:hover {
            color: #F5A623;
            transform: translateX(3px);
        }

        /* Chevron Icon Down in Center */
        .footer-chevron-box {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 16px;
            color: rgba(255, 255, 255, 0.35);
            font-size: 18px;
            animation: bounceSoft 2s infinite ease-in-out;
        }
        @keyframes bounceSoft {
            0%, 100% { transform: translateY(0); opacity: 0.35; }
            50% { transform: translateY(4px); opacity: 0.8; }
        }

        /* Bottom Copyright Bar */
        .footer-bottom-line {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 18px;
            margin-top: 10px;
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.55);
        }

        .badge-soft-primary {
            background-color: rgba(139, 94, 60, 0.12);
            color: #8B5E3C;
        }
        /* Floating Flash Alert with smooth animation */
        .flash-alert-container {
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: calc(100% - 32px);
            max-width: 680px;
            pointer-events: none;
        }
        .flash-alert-container .alert {
            pointer-events: auto;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
            animation: flashSlideDown 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes flashSlideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-xl navbar-custom {{ request()->routeIs('home') ? 'is-home' : 'sticky-top' }} py-2">
        <div class="container-fluid site-wide-container">
            <a class="navbar-brand d-flex align-items-center text-decoration-none me-2" style="gap: clamp(10px, 1vw, 16px); cursor: pointer;" href="{{ route('login', ['portal' => 'staff']) }}" title="Klik Logo untuk Akses Login Admin & Editor">
                <img src="{{ asset('images/pena.png') }}" class="navbar-brand-logo" alt="Logo Penerbit Pena Cendekia">
                <div class="d-flex flex-column text-start">
                    <span class="brand-title">PENERBIT PENA CENDEKIA</span>
                    <span class="brand-subtitle">WAHANA PENYALUR ILMU PENGETAHUAN</span>
                </div>
            </a>

            <button class="navbar-toggler border-0 text-white ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2 text-white"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav mx-auto mb-2 mb-xl-0 align-items-xl-center gap-1 gap-xxl-3" id="mainNavbarNav">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" id="navLinkHome">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#karya" id="navLinkPortofolio" title="Klik untuk ke Portofolio, Klik 2x untuk Lihat Seluruh Buku">Portofolio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#paket" id="navLinkPaket">Paket Penerbitan</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('layanan*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Service
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item {{ request()->routeIs('layanan.penerbitan') ? 'active' : '' }}" href="{{ route('layanan.penerbitan') }}"><i class="bi bi-book me-2 text-warning"></i> Penerbitan Buku</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('layanan.konversi') ? 'active' : '' }}" href="{{ route('layanan.konversi') }}"><i class="bi bi-mortarboard me-2 text-warning"></i> Konversi Karya Ilmiah</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('layanan.cetak') ? 'active' : '' }}" href="{{ route('layanan.cetak') }}"><i class="bi bi-printer me-2 text-warning"></i> Cetak Buku Berkualitas</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('layanan.editing') ? 'active' : '' }}" href="{{ route('layanan.editing') }}"><i class="bi bi-pencil-square me-2 text-warning"></i> Jasa Editing Naskah</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('layanan.hki') ? 'active' : '' }}" href="{{ route('layanan.hki') }}"><i class="bi bi-shield-check me-2 text-warning"></i> Jasa Pengurusan HAKI</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('printing.offset') ? 'active' : '' }}" href="{{ route('printing.offset') }}" id="navLinkPrinting">Printing dan Offset</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#testimoni" id="navLinkTestimoni">Testimoni</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('template.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="navLinkUnduhan">
                            Unduhan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item {{ request()->routeIs('template.buku') ? 'active' : '' }}" href="{{ route('template.buku') }}"><i class="bi bi-book me-2"></i> Template Buku</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('template.haki') ? 'active' : '' }}" href="{{ route('template.haki') }}"><i class="bi bi-shield-check me-2"></i> Template HAKI</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontak</a>
                    </li>
                    <span class="nav-slide-line" id="navSlideLine" aria-hidden="true"></span>
                </ul>

                <div class="d-flex align-items-center gap-2 mt-2 mt-xl-0">
                    <a href="{{ route('login') }}" class="btn btn-masuk-pill">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Masuk</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages (Floating Alert with 5-second Auto-Dismiss) -->
    @if(session('success') || session('warning') || session('info') || session('error'))
    <div id="flashAlertContainer" class="flash-alert-container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 custom-flash-alert" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show rounded-3 shadow-sm border-0 custom-flash-alert" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show rounded-3 shadow-sm border-0 custom-flash-alert" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 custom-flash-alert" role="alert">
                <i class="bi bi-x-circle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    @endif

    <!-- Main Content -->
    <main class="flex-grow-1 d-flex flex-column">
        @yield('content')
    </main>

    <!-- Footer: Modern Dark Curved Grid Theme (Inspired by Astra Security UI) with Clean Space Margins -->
    @unless(request()->routeIs('login', 'register'))
    <div class="footer-astra-wrapper">
        <footer class="footer-astra-shell">
            <!-- Ambient subtle glow dots -->
            <span class="footer-ambient-dot dot-left" aria-hidden="true"></span>
            <span class="footer-ambient-dot dot-right" aria-hidden="true"></span>

            <div class="footer-astra-container">
                <div class="row g-4 g-lg-5 justify-content-between">
                    <!-- Left Brand & Trust Column -->
                    <div class="col-lg-4 col-xl-4 col-md-12">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ asset('images/pena.png') }}" style="height: 44px; width: auto;" alt="Logo Pena Cendekia">
                            <h4 class="footer-brand-title mb-0">Pena Cendekia</h4>
                        </div>
                        <p class="footer-brand-bio">
                            Mitra profesional penerbitan buku mandiri dan wahana penyalur ilmu pengetahuan terpercaya di Indonesia. Terdaftar resmi di IKAPI dan Perpustakaan Nasional RI.
                        </p>

                        <!-- Circular White Social Icons Row -->
                        <div class="footer-social-row">
                            <a href="https://wa.me/6285785522283" target="_blank" rel="noopener" class="footer-social-pill" title="WhatsApp Admin" aria-label="WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="https://www.penacendekia.com" target="_blank" rel="noopener" class="footer-social-pill" title="Website Resmi" aria-label="Website">
                                <i class="bi bi-globe"></i>
                            </a>
                            <a href="https://instagram.com/penacendekia" target="_blank" rel="noopener" class="footer-social-pill" title="Instagram" aria-label="Instagram">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="mailto:penacendekiasby@gmail.com" class="footer-social-pill" title="Email Redaksi" aria-label="Email">
                                <i class="bi bi-envelope-fill"></i>
                            </a>
                            <a href="{{ route('contact') }}" class="footer-social-pill" title="Lokasi & Kontak" aria-label="Kontak">
                                <i class="bi bi-geo-alt-fill"></i>
                            </a>
                        </div>

                        <!-- Trust / Legal Review Badges (Astra Reviews Style) -->
                        <div>
                            <div class="footer-trust-lbl">Akreditasi &amp; Legalitas Resmi</div>
                            <div class="footer-trust-row">
                                <span class="footer-trust-badge">
                                    <i class="bi bi-patch-check-fill text-warning"></i> Anggota IKAPI No. 379
                                </span>
                                <span class="footer-trust-badge">
                                    <i class="bi bi-award-fill text-warning"></i> Perpusnas RI
                                </span>
                                <span class="footer-trust-badge">
                                    <i class="bi bi-shield-fill-check text-warning"></i> DJKI HAKI
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Nav Columns (3 Columns: Layanan, Navigasi/Unduhan, Kontak) -->
                    <div class="col-lg-8 col-xl-7 col-md-12 ms-auto">
                        <div class="row g-4 justify-content-between">
                            <!-- Column 1: Layanan Penerbitan -->
                            <div class="col-6 col-sm-4 col-md-3">
                                <h6 class="footer-col-title">Layanan</h6>
                                <ul class="footer-col-list">
                                    <li><a href="{{ route('layanan.penerbitan') }}">Penerbitan Buku</a></li>
                                    <li><a href="{{ route('layanan.konversi') }}">Konversi Ilmiah</a></li>
                                    <li><a href="{{ route('layanan.cetak') }}">Cetak Berkualitas</a></li>
                                    <li><a href="{{ route('layanan.editing') }}">Editing Naskah</a></li>
                                    <li><a href="{{ route('layanan.hki') }}">Pengurusan HAKI</a></li>
                                    <li><a href="{{ route('printing.offset') }}">Printing &amp; Offset</a></li>
                                </ul>
                            </div>

                            <!-- Column 2: Navigasi & Unduhan -->
                            <div class="col-6 col-sm-4 col-md-4">
                                <h6 class="footer-col-title">Navigasi</h6>
                                <ul class="footer-col-list">
                                    <li><a href="{{ route('home') }}">Beranda (Home)</a></li>
                                    <li><a href="{{ route('portofolio') }}">Portofolio Buku</a></li>
                                    <li><a href="{{ route('home') }}#paket">Paket Penerbitan</a></li>
                                    <li><a href="{{ route('template.buku') }}">Template Buku</a></li>
                                    <li><a href="{{ route('template.haki') }}">Template HAKI</a></li>
                                    <li><a href="{{ route('home') }}#testimoni">Testimoni Penulis</a></li>
                                    <li><a href="{{ route('about') }}">Tentang Kami</a></li>
                                </ul>
                            </div>

                            <!-- Column 3: Kontak & Informasi Perusahaan -->
                            <div class="col-12 col-sm-4 col-md-5">
                                <h6 class="footer-col-title">Kontak &amp; Kantor</h6>
                                <ul class="footer-col-list">
                                    <li>
                                        <span class="d-flex align-items-start gap-2">
                                            <i class="bi bi-geo-alt-fill text-warning flex-shrink-0 mt-1"></i>
                                            <span>Jl. Jemur Wonosari Gg. Lebar No. 140, Surabaya</span>
                                        </span>
                                    </li>
                                    <li>
                                        <a href="https://wa.me/6285785522283" target="_blank" rel="noopener" class="d-flex align-items-center gap-2">
                                            <i class="bi bi-whatsapp text-warning flex-shrink-0"></i>
                                            <span>0857-8552-2283</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="mailto:penacendekiasby@gmail.com" class="d-flex align-items-center gap-2">
                                            <i class="bi bi-envelope text-warning flex-shrink-0"></i>
                                            <span>penacendekiasby@gmail.com</span>
                                        </a>
                                    </li>
                                    <li>
                                        <span class="d-flex align-items-center gap-2 small text-white-50">
                                            <i class="bi bi-clock text-warning flex-shrink-0"></i>
                                            <span>Senin - Sabtu (09:00 - 16:00 WIB)</span>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chevron Down in Center (Astra Aesthetic Detail) -->
                <div class="footer-chevron-box" aria-hidden="true">
                    <i class="bi bi-chevron-down"></i>
                </div>

                <!-- Bottom Copyright Bar -->
                <div class="footer-bottom-line d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                    <div>
                        &copy; {{ date('Y') }} <strong>PT. Pena Cendekia Pustaka</strong>. Hak Cipta Dilindungi Undang-Undang.
                    </div>
                    <div>
                        <span class="badge bg-transparent border border-secondary text-white-50 px-3 py-1 rounded-pill">
                            Anggota Resmi IKAPI No. 379/JTI/2023
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    @endunless

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nav = document.querySelector('.navbar-custom.is-home');
            if (nav) {
                const handleScroll = function () {
                    // Cari elemen tulisan/kotak ber-border di bagian Home (hero-top-badge, hero-stats-card, hero-feature-pill)
                    const heroBorderBox = document.querySelector('.hero-top-badge') 
                                       || document.querySelector('.hero-stats-card')
                                       || document.querySelector('.hero-feature-pill');
                    
                    const navHeight = nav.offsetHeight || 65;
                    let shouldBeSolid = false;

                    if (heroBorderBox) {
                        const rect = heroBorderBox.getBoundingClientRect();
                        // Saat scroll mulai masuk ke / menyentuh batas elemen border box
                        if (rect.top <= navHeight || window.scrollY >= 40) {
                            shouldBeSolid = true;
                        }
                    } else if (window.scrollY >= 40) {
                        shouldBeSolid = true;
                    }

                    if (shouldBeSolid) {
                        nav.classList.add('scrolled');
                    } else {
                        nav.classList.remove('scrolled');
                    }
                };
                window.addEventListener('scroll', handleScroll, { passive: true });
                window.addEventListener('resize', handleScroll);
                handleScroll();
            }

            // Sliding underline for navbar items (active only on non-dropdown items)
            const navList = document.getElementById('mainNavbarNav');
            const slideLine = document.getElementById('navSlideLine');
            if (navList && slideLine) {
                const navItems = navList.querySelectorAll('.nav-item');
                let currentActiveLink = navList.querySelector('.nav-link.active');

                const moveLineToLink = function (link) {
                    if (!link || window.innerWidth < 1200) {
                        slideLine.style.opacity = '0';
                        return;
                    }
                    const navRect = navList.getBoundingClientRect();
                    const linkRect = link.getBoundingClientRect();
                    const style = window.getComputedStyle(link);
                    const padL = parseFloat(style.paddingLeft) || 0;
                    const padR = parseFloat(style.paddingRight) || 0;

                    const left = (linkRect.left - navRect.left) + padL;
                    const width = Math.max(0, linkRect.width - padL - padR);

                    slideLine.style.left = left + 'px';
                    slideLine.style.width = width + 'px';
                    slideLine.style.opacity = '1';
                };

                const hideLine = function () {
                    slideLine.style.opacity = '0';
                };

                const resetToActive = function () {
                    if (currentActiveLink && !currentActiveLink.classList.contains('dropdown-toggle')) {
                        moveLineToLink(currentActiveLink);
                    } else {
                        hideLine();
                    }
                };

                const setActiveLink = function (newLink) {
                    if (!newLink || newLink === currentActiveLink) return;
                    navList.querySelectorAll('.nav-link').forEach(function (l) {
                        l.classList.remove('active');
                    });
                    newLink.classList.add('active');
                    currentActiveLink = newLink;
                    moveLineToLink(currentActiveLink);
                };

                navList.classList.add('sliding-ready');
                resetToActive();

                navItems.forEach(function (item) {
                    const link = item.querySelector('.nav-link');
                    if (!link) return;

                    const isDropdown = item.classList.contains('dropdown') || link.classList.contains('dropdown-toggle');

                    item.addEventListener('mouseenter', function () {
                        if (window.innerWidth < 1200) return;
                        if (isDropdown) {
                            // Disable/hide sliding line on items with dropdown toggle
                            hideLine();
                        } else {
                            moveLineToLink(link);
                        }
                    });
                });

                navList.addEventListener('mouseleave', function () {
                    if (window.innerWidth < 1200) return;
                    resetToActive();
                });

                window.addEventListener('resize', function () {
                    resetToActive();
                });

                if (document.fonts && document.fonts.ready) {
                    document.fonts.ready.then(resetToActive);
                }

                // Scroll Spy: Geser list kuning di navbar saat user scroll ke Karya Terbitan / Portofolio, Paket Penerbitan, Testimoni, dan Home
                const karyaSection = document.getElementById('karya');
                const paketSection = document.getElementById('paket');
                const testimoniSection = document.getElementById('testimoni');
                const portofolioNav = document.getElementById('navLinkPortofolio');
                const paketNav = document.getElementById('navLinkPaket');
                const testimoniNav = document.getElementById('navLinkTestimoni');
                const homeNav = document.getElementById('navLinkHome');

                if (karyaSection || paketSection || testimoniSection) {
                    const handleScrollSpy = function () {
                        const scrollY = window.scrollY || window.pageYOffset;
                        const triggerOffset = 240; // Viewport trigger point

                        if (testimoniSection && (scrollY + triggerOffset >= testimoniSection.offsetTop)) {
                            setActiveLink(testimoniNav);
                        } else if (paketSection && (scrollY + triggerOffset >= paketSection.offsetTop)) {
                            setActiveLink(paketNav);
                        } else if (karyaSection && (scrollY + triggerOffset >= karyaSection.offsetTop)) {
                            setActiveLink(portofolioNav);
                        } else if (homeNav && (!karyaSection || scrollY < karyaSection.offsetTop - 250)) {
                            setActiveLink(homeNav);
                        }
                    };

                    window.addEventListener('scroll', handleScrollSpy, { passive: true });
                    handleScrollSpy();
                }

                // Double click pada menu Portofolio -> Buka Halaman Portofolio Buku
                if (portofolioNav) {
                    portofolioNav.addEventListener('dblclick', function (e) {
                        e.preventDefault();
                        window.location.href = "{{ route('portofolio') }}";
                    });
                }
            }

            // Dropdown hover persistence for desktop (prevents closing when moving mouse into submenu)
            const dropdownNavItems = document.querySelectorAll('.navbar-custom .nav-item.dropdown');
            dropdownNavItems.forEach(function (drop) {
                let closeTimer = null;
                drop.addEventListener('mouseenter', function () {
                    if (window.innerWidth < 1200) return;
                    if (closeTimer) clearTimeout(closeTimer);
                    drop.classList.add('is-open');
                });
                drop.addEventListener('mouseleave', function () {
                    if (window.innerWidth < 1200) return;
                    closeTimer = setTimeout(function () {
                        drop.classList.remove('is-open');
                    }, 220);
                });
            });
        });
    </script>

    <!-- AOS Animation JS (Bidirectional Scroll: Up and Down) -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 700,
                    easing: 'ease-out-cubic',
                    once: false,   // re-animate on every scroll up and down
                    mirror: true,  // animate elements out and back in
                    offset: 50
                });
            }
        });
    </script>

    <!-- Swiper 11 JS (3D Coverflow Book Carousel) -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Auto-Dismiss Flash Alert (5 Detik Otomatis Hilang) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const flashAlerts = document.querySelectorAll('.custom-flash-alert');
            flashAlerts.forEach(function (alert) {
                setTimeout(function () {
                    alert.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-15px)';
                    setTimeout(function () {
                        if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                            const bsAlert = bootstrap.Alert.getInstance(alert) || new bootstrap.Alert(alert);
                            bsAlert.close();
                        } else {
                            alert.remove();
                        }
                    }, 600);
                }, 5000); // 5000 ms = 5 detik
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
