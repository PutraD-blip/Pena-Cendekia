<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Penulis') &mdash; PT. Pena Cendekia</title>

    <!-- Google Fonts (Poppins & Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Favicon / Brand Icon -->
    <link rel="icon" type="image/png" href="{{ asset('images/pena.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/pena.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/pena.png') }}">

    <!-- Anti-flicker Early Theme Script -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('pc_author_theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <style>
        :root {
            --topbar-height: 64px;

            /* Literary Shell (Sidebar, Logo Box, Topbar are Midnight Ink #131C2E in Light Theme) */
            --author-sidebar-bg: #131C2E;
            --author-sidebar-surface: #1B263B;
            --author-sidebar-border: rgba(255, 255, 255, 0.08);
            --author-bg: #F1F5F9;
            --author-card-bg: #FFFFFF;
            --author-card-border: #CBD5E1;
            --author-border: #CBD5E1;
            --author-card-surface: #F8FAFC;
            
            --author-topbar-bg: #131C2E;
            --author-topbar-border: rgba(255, 255, 255, 0.08);

            --author-yellow: #D97706;
            --author-yellow-hover: #B45309;
            --author-gold: #D97706;
            --author-gold-hover: #B45309;
            --author-gold-dark: #92400E;
            --author-gold-subtle: #FFFBEB;
            --author-gold-glow: rgba(217, 119, 6, 0.35);
            
            --author-navy: #0F172A;
            --author-text-main: #0F172A;
            --author-text-sub: #475569;
            --author-text-muted: #64748B;
            
            --author-success: #10B981;
            --author-info: #0284C7;
            --author-purple: #7C3AED;
            --author-danger: #EF4444;

            --font-main: 'Plus Jakarta Sans', 'Poppins', sans-serif;
            --font-heading: 'Plus Jakarta Sans', sans-serif;
        }

        /* Dark Theme Variables */
        [data-theme="dark"] {
            --author-sidebar-bg: #0B111A;
            --author-sidebar-surface: #111A27;
            --author-sidebar-border: rgba(255, 255, 255, 0.08);
            --author-bg: #070A0E;
            --author-card-bg: #0F1722;
            --author-card-border: rgba(255, 255, 255, 0.08);
            --author-border: rgba(255, 255, 255, 0.08);
            --author-card-surface: #151F2D;
            
            --author-topbar-bg: #0B111A;
            --author-topbar-border: rgba(255, 255, 255, 0.08);

            --author-gold-subtle: rgba(217, 119, 6, 0.15);
            
            --author-navy: #0B111A;
            --author-text-main: #F8FAFC;
            --author-text-sub: #94A3B8;
            --author-text-muted: #64748B;
        }

        html, body {
            font-family: var(--font-main);
            background-color: var(--author-bg);
            color: var(--author-text-main);
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden !important;
            max-width: 100%;
            width: 100%;
            position: relative;
            -webkit-text-size-adjust: 100%;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        /* Top Notification Toast */
        .flash-alert-container {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1060;
            width: calc(100% - 32px);
            max-width: 600px;
            pointer-events: none;
        }
        .flash-alert-container .alert {
            pointer-events: auto;
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(12px);
        }

        /* Layout Structure */
        .author-wrapper {
            display: flex;
            height: 100vh;
            max-height: 100vh;
            width: 100%;
            max-width: 100%;
            overflow: hidden;
            position: relative;
        }

        /* Sidebar Styling (Art Academy Clean White Theme - No Scroll) */
        .author-sidebar {
            width: 255px !important;
            min-width: 255px !important;
            max-width: 255px !important;
            box-sizing: border-box !important;
            background-color: var(--author-sidebar-bg);
            border-right: 1.5px solid var(--author-sidebar-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1030;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.3s;
            overflow: hidden !important;
        }

        .sidebar-brand-box {
            height: var(--topbar-height);
            max-height: var(--topbar-height);
            box-sizing: border-box;
            padding: 0 20px;
            border-bottom: 1px solid var(--author-sidebar-border);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            flex-shrink: 0;
            background-color: var(--author-sidebar-bg);
            transition: background-color 0.25s ease, border-color 0.25s ease;
        }
        .sidebar-brand-logo {
            height: 38px;
            width: 38px;
            max-width: 38px;
            object-fit: contain;
            flex-shrink: 0;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.25));
        }
        .sidebar-brand-title {
            font-family: var(--font-heading);
            font-weight: 900;
            font-size: 15px;
            letter-spacing: -0.3px;
            color: #FFFFFF;
            line-height: 1.1;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand-title span {
            font-weight: 800;
            font-size: 13px;
            color: #F5A623;
            letter-spacing: 0.8px;
        }
        .sidebar-brand-sub {
            font-size: 10px;
            letter-spacing: 0.5px;
            color: #94A3B8;
            font-weight: 600;
            display: block;
            margin-top: 2px;
        }

        /* Navigation Links */
        .sidebar-nav-container {
            padding: 8px 12px;
            flex-grow: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 4px;
            scrollbar-width: thin;
        }
        .sidebar-nav-container::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .sidebar-section-title {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748B;
            padding: 6px 12px 2px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 14px;
            border-radius: 12px;
            color: #94A3B8;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
            position: relative;
            background: transparent;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: left;
        }
        .sidebar-link:hover {
            color: #FFFFFF;
            background-color: rgba(255, 255, 255, 0.06);
        }
        .sidebar-link.active {
            color: #FBBF24 !important;
            background: linear-gradient(135deg, rgba(217, 119, 6, 0.22), rgba(245, 158, 11, 0.12)) !important;
            border-left: 3.5px solid #F59E0B;
            font-weight: 700;
        }
        .sidebar-link.active::before {
            display: none;
        }
        .sidebar-link i {
            font-size: 17px;
            color: #94A3B8;
            transition: color 0.2s;
            flex-shrink: 0;
        }
        .sidebar-link:hover i {
            color: #FBBF24;
        }
        .sidebar-link.active i {
            color: #FBBF24 !important;
        }

        /* Accordion / Dropdown Submenu */
        .sidebar-dropdown-group {
            margin-bottom: 2px;
        }
        .sidebar-dropdown-toggle {
            display: flex;
            align-items: center;
            width: 100%;
        }
        .dropdown-chevron {
            font-size: 11px;
            transition: transform 0.25s ease;
            color: #64748B !important;
        }
        .sidebar-dropdown-toggle:not(.collapsed) .dropdown-chevron {
            transform: rotate(180deg);
            color: #F5A623 !important;
        }

        /* Tree Structure Submenu */
        .sidebar-submenu-tree {
            position: relative;
            margin-left: 18px;
            padding-left: 10px;
            border-left: 1.5px solid rgba(255, 255, 255, 0.12);
            display: flex;
            flex-direction: column;
            gap: 2px;
            padding-top: 4px;
            padding-bottom: 4px;
        }
        .sidebar-sublink {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px;
            border-radius: 9px;
            color: #94A3B8;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .sidebar-sublink:hover {
            color: #FFFFFF;
            background-color: rgba(255, 255, 255, 0.06);
        }
        .sidebar-sublink.active {
            color: #F5A623;
            font-weight: 700;
            background-color: rgba(245, 166, 35, 0.15);
        }
        .sidebar-sublink i {
            font-size: 13px;
            color: #64748B;
        }
        .sidebar-sublink:hover i,
        .sidebar-sublink.active i {
            color: #F5A623;
        }

        /* Help Center Widget (Image Bottom Box) */
        .sidebar-help-box {
            margin: auto 12px 10px;
            padding: 20px 12px 12px;
            border-radius: 16px;
            background-color: #1B1D2A;
            text-align: center;
            position: relative;
            color: #FFFFFF;
            flex-shrink: 0;
        }
        .sidebar-help-icon {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #F5A623;
            color: #111827;
            font-weight: 900;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(245, 166, 35, 0.4);
        }
        .sidebar-help-title {
            font-weight: 800;
            font-size: 12.5px;
            color: #FFFFFF;
            margin-bottom: 2px;
        }
        .sidebar-help-desc {
            font-size: 10px;
            color: #8E95A5;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        .sidebar-help-btn {
            background-color: #FFFFFF;
            color: #111827 !important;
            font-weight: 700;
            font-size: 11px;
            padding: 6px 12px;
            border-radius: 50px;
            text-decoration: none;
            display: block;
            width: 100%;
            transition: all 0.2s;
        }
        .sidebar-help-btn:hover {
            background-color: #F3F4F6;
            transform: translateY(-1px);
        }

        /* Sidebar Footer User Card */
        .sidebar-footer {
            padding: 8px 12px;
            border-top: 1px solid var(--author-sidebar-border);
            background-color: var(--author-sidebar-bg);
            flex-shrink: 0;
        }
        .author-mini-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px;
            border-radius: 12px;
            background-color: #141C24;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .author-mini-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #F5A623 0%, #E09210 100%);
            color: #111827;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            flex-shrink: 0;
            overflow: hidden;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
        }
        .author-mini-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .author-mini-name {
            font-size: 12px;
            font-weight: 700;
            color: #FFFFFF;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }
        .author-mini-role {
            font-size: 10px;
            color: #10B981;
            font-weight: 600;
            display: block;
        }

        /* Main Content Area (Edge to Edge Full Width) */
        .author-main {
            margin-left: 255px !important;
            flex-grow: 1;
            height: 100vh;
            max-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--author-bg);
            position: relative;
            min-width: 0;
            width: calc(100% - 255px) !important;
            max-width: calc(100% - 255px) !important;
            padding: 0 !important;
            overflow: hidden;
            min-height: 0;
        }

        @media (max-width: 991.98px) {
            .author-main {
                margin-left: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }

        /* Top Header Navbar (Fixed, Never Scrolls) */
        .author-topbar {
            height: var(--topbar-height);
            min-height: var(--topbar-height);
            max-height: var(--topbar-height);
            box-sizing: border-box;
            background-color: var(--author-topbar-bg) !important;
            border-bottom: 1.5px solid var(--author-topbar-border) !important;
            padding: 0 24px;
            flex-shrink: 0;
            position: relative;
            z-index: 1020;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            border-radius: 0 !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            transition: background-color 0.25s ease, border-color 0.25s ease;
        }

        /* Body Content (Independently Scrollable, Topbar Fixed) */
        .author-content {
            padding: 20px 24px;
            flex-grow: 1;
            width: 100%;
            min-width: 0;
            max-width: 100%;
            box-sizing: border-box;
            height: calc(100vh - var(--topbar-height));
            max-height: calc(100vh - var(--topbar-height));
            overflow-y: auto;
            overflow-x: hidden;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            background-color: var(--author-bg);
        }
        .author-content::-webkit-scrollbar {
            width: 6px;
        }
        .author-content::-webkit-scrollbar-track {
            background: transparent;
        }
        .author-content::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.35);
            border-radius: 10px;
        }
        .author-content::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.6);
        }

        @media (max-width: 767.98px) {
            .author-topbar {
                padding: 10px 16px;
            }
            .author-content {
                padding: 18px 14px 36px;
            }
        }

        .topbar-title {
            font-size: 19px;
            font-weight: 800;
            color: #FFFFFF !important;
            letter-spacing: -0.3px;
            margin: 0;
            line-height: 1.2;
            font-family: 'Plus Jakarta Sans', var(--font-heading), sans-serif;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-search-box {
            position: relative;
            width: 260px;
        }
        .topbar-search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 13px;
            pointer-events: none;
            transition: color 0.2s ease;
        }
        .topbar-search-box input {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 50px;
            padding: 7px 16px 7px 36px;
            color: #FFFFFF;
            font-size: 12.5px;
            outline: none;
            transition: all 0.2s ease;
        }
        .topbar-search-box input::placeholder {
            color: #94A3B8;
        }
        .topbar-search-box input:focus {
            background-color: rgba(255, 255, 255, 0.12);
            border-color: #F5A623;
            box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.2);
            color: #FFFFFF;
        }
        .topbar-search-box input:focus + i,
        .topbar-search-box:focus-within i {
            color: #F5A623;
        }

        .btn-topbar-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #E2E8F0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
        }
        .btn-topbar-icon:hover {
            color: #F5A623;
            background-color: rgba(245, 166, 35, 0.15);
            border-color: rgba(245, 166, 35, 0.35);
            transform: translateY(-1px);
        }
        .btn-topbar-icon .badge {
            position: absolute;
            top: -3px;
            right: -3px;
            font-size: 10px;
            padding: 2px 5px;
            background-color: #EF4444;
            color: #FFFFFF;
            border: 2px solid #0d1217;
        }

        .topbar-user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 3px 12px 3px 3px;
            border-radius: 50px;
            background-color: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.14);
            text-decoration: none;
            color: #FFFFFF;
            transition: all 0.2s ease;
        }
        .topbar-user-chip:hover {
            border-color: #F5A623;
            background-color: rgba(255, 255, 255, 0.12);
            color: #FFFFFF;
            transform: translateY(-1px);
        }
        .topbar-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #F5A623 0%, #E09210 100%);
            color: #111827;
            font-weight: 800;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            flex-shrink: 0;
            border: 1.5px solid rgba(255, 255, 255, 0.2);
        }
        .topbar-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: block;
        }
        .topbar-user-name {
            font-size: 12.5px;
            font-weight: 700;
            color: #FFFFFF;
            max-width: 130px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-topbar-cta {
            background: linear-gradient(135deg, #F5A623 0%, #E09210 100%);
            color: #111827 !important;
            font-weight: 700;
            font-size: 13px;
            padding: 8px 18px;
            border-radius: 50px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(245, 166, 35, 0.3);
        }
        .btn-topbar-cta:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(245, 166, 35, 0.45);
        }



        /* Modern Dashboard Cards (FoxHR White Cards) */
        .dash-card {
            background-color: var(--author-card-bg);
            border: 1px solid var(--author-card-border);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            position: relative;
        }
        .dash-card .text-white:not(.btn):not(.badge):not(.btn-gold):not([style*="background"]) {
            color: #0F172A !important;
        }
        .dash-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--author-card-border);
        }
        .dash-card-title {
            font-family: var(--font-heading);
            font-weight: 700;
            font-size: 17px;
            color: #0F172A;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Form Controls in Light & Dark Theme (High-Contrast Visible Borders) */
        .form-control, .form-select {
            background-color: #FFFFFF !important;
            border: 1.5px solid #94A3B8 !important;
            color: #0F172A !important;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 13.5px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.04);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .form-control:hover, .form-select:hover {
            border-color: #64748B !important;
        }
        .form-control:focus, .form-select:focus {
            background-color: #FFFFFF !important;
            border-color: #D97706 !important;
            color: #0F172A !important;
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.25) !important;
            outline: none;
        }
        .form-control::placeholder {
            color: #94A3B8 !important;
        }
        .form-label {
            color: #0F172A !important;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .form-control[readonly], .form-control:disabled {
            background-color: #F1F5F9 !important;
            color: #475569 !important;
            border: 1.5px solid #CBD5E1 !important;
        }
        .input-group-text {
            background-color: #F1F5F9 !important;
            border: 1.5px solid #94A3B8 !important;
            color: #334155 !important;
            font-weight: 600;
            border-radius: 12px;
        }
        .dash-card, .form-section-card, .flow-stepper-container, .template-box-card, .active-package-banner {
            border: 1.5px solid #CBD5E1 !important;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.03);
            border-radius: 18px;
        }
        .border {
            border-color: #CBD5E1 !important;
        }
        .form-check-input {
            border: 1.5px solid #94A3B8 !important;
            background-color: #FFFFFF;
            cursor: pointer;
        }
        .form-check-input:checked {
            background-color: #D97706 !important;
            border-color: #D97706 !important;
        }
        .pkg-choice-card {
            border: 1.5px solid #CBD5E1 !important;
            background-color: #FFFFFF;
        }
        .pkg-choice-card:hover {
            border-color: #D97706 !important;
        }
        .pkg-choice-card.selected {
            border: 2px solid #D97706 !important;
        }
        .service-nav-pill, .template-item-btn {
            border: 1.5px solid #CBD5E1 !important;
        }

        /* Light Theme Auto-adaptation for Author Content */
        html:not([data-theme="dark"]) .author-main .dash-card .text-white,
        html:not([data-theme="dark"]) .author-main .card .text-white,
        html:not([data-theme="dark"]) .author-main .form-section-card .text-white,
        html:not([data-theme="dark"]) .author-main h1.text-white,
        html:not([data-theme="dark"]) .author-main h2.text-white,
        html:not([data-theme="dark"]) .author-main h3.text-white,
        html:not([data-theme="dark"]) .author-main h4.text-white,
        html:not([data-theme="dark"]) .author-main h5.text-white,
        html:not([data-theme="dark"]) .author-main h6.text-white {
            color: #0F172A !important;
        }
        html:not([data-theme="dark"]) .author-main .text-white-50 {
            color: #64748B !important;
        }
        html:not([data-theme="dark"]) .btn-outline-light {
            color: #1E293B !important;
            border: 1.5px solid #94A3B8 !important;
            background-color: #FFFFFF !important;
        }
        html:not([data-theme="dark"]) .btn-outline-light:hover {
            color: #0F172A !important;
            border-color: #64748B !important;
            background-color: #F1F5F9 !important;
        }

        /* Dark Mode Form Controls */
        [data-theme="dark"] .form-control, [data-theme="dark"] .form-select {
            background-color: #151F2D !important;
            border: 1.5px solid rgba(255, 255, 255, 0.16) !important;
            color: #F8FAFC !important;
            box-shadow: none;
        }
        [data-theme="dark"] .form-control:focus, [data-theme="dark"] .form-select:focus {
            border-color: #F5A623 !important;
            box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.3) !important;
            background-color: #192536 !important;
        }
        [data-theme="dark"] .form-label {
            color: #F8FAFC !important;
        }
        [data-theme="dark"] .input-group-text {
            background-color: #111A27 !important;
            border: 1.5px solid rgba(255, 255, 255, 0.16) !important;
            color: #94A3B8 !important;
        }
        [data-theme="dark"] .dash-card, [data-theme="dark"] .form-section-card, [data-theme="dark"] .flow-stepper-container, [data-theme="dark"] .template-box-card, [data-theme="dark"] .active-package-banner {
            border: 1.5px solid rgba(255, 255, 255, 0.1) !important;
        }
        [data-theme="dark"] .pkg-choice-card, [data-theme="dark"] .service-nav-pill, [data-theme="dark"] .template-item-btn {
            border: 1.5px solid rgba(255, 255, 255, 0.12) !important;
            background-color: #0F1722;
        }
        [data-theme="dark"] .form-check-input {
            background-color: #151F2D !important;
            border: 1.5px solid rgba(255, 255, 255, 0.25) !important;
        }
        [data-theme="dark"] .form-check-input:checked {
            background-color: #F5A623 !important;
            border-color: #F5A623 !important;
        }
        [data-theme="dark"] .border {
            border-color: rgba(255, 255, 255, 0.12) !important;
        }
        [data-theme="dark"] .text-dark {
            color: #F8FAFC !important;
        }
        [data-theme="dark"] .text-muted {
            color: #94A3B8 !important;
        }
        [data-theme="dark"] .text-secondary {
            color: #94A3B8 !important;
        }
        [data-theme="dark"] .bg-white {
            background-color: var(--author-card-bg, #101722) !important;
            color: #F8FAFC !important;
        }
        [data-theme="dark"] .bg-light {
            background-color: var(--author-card-surface, #151F2D) !important;
            color: #F8FAFC !important;
        }
        [data-theme="dark"] .border-bottom,
        [data-theme="dark"] .border-top,
        [data-theme="dark"] .border-start,
        [data-theme="dark"] .border-end {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        [data-theme="dark"] .card {
            background-color: var(--author-card-bg, #101722) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #F8FAFC !important;
        }
        [data-theme="dark"] .card-header {
            background-color: var(--author-card-surface, #151F2D) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #F8FAFC !important;
        }
        [data-theme="dark"] .badge.bg-light {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #F8FAFC !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
        }
        [data-theme="dark"] .list-group-item {
            background-color: var(--author-card-bg, #101722) !important;
            color: #F8FAFC !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }

        /* Buttons & Badges */
        .btn-gold {
            background: linear-gradient(135deg, #F5A623 0%, #E09210 100%);
            color: #111827 !important;
            font-weight: 700;
            border: none;
            border-radius: 12px;
            padding: 10px 20px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(245, 166, 35, 0.25);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-gold:hover {
            color: #000000 !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(245, 166, 35, 0.35);
        }
        .btn-outline-gold {
            background: transparent;
            color: var(--author-gold-dark);
            border: 1.5px solid var(--author-gold);
            font-weight: 600;
            border-radius: 12px;
            padding: 9px 18px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-outline-gold:hover {
            background-color: var(--author-gold);
            color: #111827;
        }

        /* Modal Styles */
        .modal-theme-dark .modal-content {
            background-color: #0F1728;
            border: 1px solid rgba(245, 166, 35, 0.3);
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
            color: #ffffff;
            overflow: hidden;
        }
        .modal-theme-dark .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 22px 28px;
            background: linear-gradient(180deg, rgba(245, 166, 35, 0.08) 0%, transparent 100%);
        }
        .modal-theme-dark .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 18px 28px;
            background-color: rgba(0, 0, 0, 0.2);
        }
        .modal-theme-dark .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .service-pkg-card {
            background-color: #141E34;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 22px;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: all 0.25s ease;
            position: relative;
        }
        .service-pkg-card:hover {
            border-color: var(--author-gold);
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
        }
        .service-pkg-card.highlight {
            border-color: rgba(245, 166, 35, 0.6);
            background: linear-gradient(180deg, #18243E 0%, #131E34 100%);
            box-shadow: 0 8px 25px rgba(245, 166, 35, 0.15);
        }

        .pkg-badge {
            background: linear-gradient(135deg, #F5A623 0%, #E09210 100%);
            color: #111827;
            font-size: 11px;
            font-weight: 800;
            padding: 3px 10px;
            border-radius: 30px;
            position: absolute;
            top: 14px;
            right: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .pkg-title {
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 18px;
            color: #ffffff;
            margin-bottom: 6px;
        }
        .pkg-price {
            font-size: 26px;
            font-weight: 800;
            color: var(--author-gold);
            line-height: 1.2;
            margin-bottom: 4px;
        }
        .pkg-price span {
            font-size: 14px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            margin-right: 2px;
        }
        .pkg-sub {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 16px;
        }
        .pkg-features {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
            flex-grow: 1;
        }
        .pkg-features li {
            font-size: 12.5px;
            color: rgba(255, 255, 255, 0.76);
            margin-bottom: 8px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            line-height: 1.4;
        }
        .pkg-features li i {
            color: var(--author-gold);
            font-size: 14px;
            margin-top: 1px;
            flex-shrink: 0;
        }

        /* Nav Pills inside Modal */
        .modal-theme-dark .nav-pills .nav-link {
            color: rgba(255, 255, 255, 0.75);
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 16px;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        .modal-theme-dark .nav-pills .nav-link.active {
            background-color: var(--author-gold);
            color: #111827;
            font-weight: 700;
        }

        /* Mobile Responsive & Horizontal Scroll Prevention */
        @media (max-width: 991.98px) {
            html, body {
                overflow-x: hidden !important;
                max-width: 100vw !important;
                width: 100% !important;
                position: relative;
            }
            .author-wrapper {
                overflow-x: hidden !important;
                max-width: 100vw !important;
                width: 100% !important;
            }
            .author-sidebar {
                transform: translateX(-100%);
                visibility: hidden;
                pointer-events: none;
                transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.3s;
            }
            .author-sidebar.show {
                transform: translateX(0);
                visibility: visible;
                pointer-events: auto;
                box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
            }
            .sidebar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(2px);
                z-index: 1025;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }
            .sidebar-backdrop.show {
                opacity: 1;
                visibility: visible;
            }
            .author-main {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100vw !important;
                min-width: 0 !important;
                overflow-x: hidden !important;
            }
            .author-content {
                padding: 16px 12px !important;
                width: 100% !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
                box-sizing: border-box !important;
            }
            .author-content .container,
            .author-content .container-fluid {
                padding-left: 0 !important;
                padding-right: 0 !important;
                max-width: 100% !important;
                overflow-x: hidden !important;
            }
            .author-content .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            .author-content .row > * {
                padding-left: 6px !important;
                padding-right: 6px !important;
            }
            .author-topbar {
                padding: 10px 14px !important;
                max-width: 100vw !important;
                box-sizing: border-box !important;
            }
            .topbar-title {
                font-size: 16px !important;
                max-width: 130px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .topbar-search-box {
                display: none !important;
            }
            .btn-topbar-cta {
                padding: 6px 12px !important;
                font-size: 12px !important;
            }
            .btn-topbar-icon {
                width: 36px !important;
                height: 36px !important;
            }
        }

        /* ========================================================= */
        /* DARK THEME STYLING SYSTEM                                 */
        /* ========================================================= */
        [data-theme="dark"] .author-sidebar {
            background-color: var(--author-sidebar-bg) !important;
            border-right: 1px solid var(--author-sidebar-border) !important;
        }
        [data-theme="dark"] .sidebar-brand-box {
            background-color: var(--author-sidebar-bg) !important;
            border-bottom: 1px solid var(--author-sidebar-border) !important;
        }
        [data-theme="dark"] .sidebar-brand-title {
            color: #FFFFFF !important;
        }
        [data-theme="dark"] .sidebar-brand-title span {
            color: #F5A623 !important;
        }
        [data-theme="dark"] .sidebar-brand-sub {
            color: #94A3B8 !important;
        }
        [data-theme="dark"] .sidebar-section-title {
            color: #64748B !important;
        }
        [data-theme="dark"] .sidebar-link {
            color: #94A3B8 !important;
        }
        [data-theme="dark"] .sidebar-link:hover {
            color: #FFFFFF !important;
            background-color: rgba(255, 255, 255, 0.06) !important;
        }
        [data-theme="dark"] .sidebar-link.active {
            color: #FBBF24 !important;
            background: linear-gradient(135deg, rgba(217, 119, 6, 0.25), rgba(245, 158, 11, 0.15)) !important;
            border-left: 3.5px solid #F59E0B !important;
            font-weight: 700 !important;
        }
        [data-theme="dark"] .sidebar-link.active i {
            color: #FBBF24 !important;
        }
        [data-theme="dark"] .sidebar-dropdown-toggle:not(.collapsed) {
            color: #FFFFFF !important;
            background-color: rgba(255, 255, 255, 0.06) !important;
        }
        [data-theme="dark"] .sidebar-submenu-tree {
            border-left-color: rgba(255, 255, 255, 0.12) !important;
        }
        [data-theme="dark"] .sidebar-sublink {
            color: #94A3B8 !important;
        }
        [data-theme="dark"] .sidebar-sublink:hover {
            color: #FFFFFF !important;
            background-color: rgba(255, 255, 255, 0.06) !important;
        }
        [data-theme="dark"] .sidebar-sublink.active {
            color: #F5A623 !important;
            background-color: rgba(245, 166, 35, 0.15) !important;
        }
        [data-theme="dark"] .sidebar-sublink.active i {
            color: #F5A623 !important;
        }
        [data-theme="dark"] .sidebar-footer {
            border-top: 1px solid var(--author-sidebar-border) !important;
        }
        [data-theme="dark"] .author-mini-card {
            background-color: #141C24 !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
        }
        [data-theme="dark"] .author-mini-name {
            color: #FFFFFF !important;
        }
        [data-theme="dark"] .author-content {
            background-color: var(--author-bg) !important;
        }
        [data-theme="dark"] .author-main {
            background-color: var(--author-bg) !important;
        }
        [data-theme="dark"] .modal-content {
            background-color: #121820 !important;
            color: #F8FAFC !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        [data-theme="dark"] .modal-header,
        [data-theme="dark"] .modal-footer {
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        [data-theme="dark"] .modal-title {
            color: #F8FAFC !important;
        }
        [data-theme="dark"] .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        [data-theme="dark"] .service-pkg-card {
            background-color: #161F2A !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
            color: #F8FAFC !important;
        }
        [data-theme="dark"] .service-pkg-card .pkg-title {
            color: #FFFFFF !important;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Auto-Dismiss Flash Alert Toast -->
    @if(session('success') || session('error') || session('info') || session('warning'))
        <div class="flash-alert-container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 custom-flash-alert d-flex align-items-center gap-3 border-0 bg-white" role="alert">
                    <i class="bi bi-check-circle-fill fs-4 flex-shrink-0 text-success"></i>
                    <div>
                        <strong class="d-block text-success">Berhasil!</strong>
                        <span class="small text-dark">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 custom-flash-alert d-flex align-items-center gap-3 border-0 bg-white" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-4 flex-shrink-0 text-danger"></i>
                    <div>
                        <strong class="d-block text-danger">Perhatian!</strong>
                        <span class="small text-dark">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show rounded-4 custom-flash-alert d-flex align-items-center gap-3 border-0 bg-white" role="alert">
                    <i class="bi bi-info-circle-fill fs-4 flex-shrink-0 text-info"></i>
                    <div>
                        <strong class="d-block text-info">Informasi</strong>
                        <span class="small text-dark">{{ session('info') }}</span>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show rounded-4 custom-flash-alert d-flex align-items-center gap-3 border-0 bg-white" role="alert">
                    <i class="bi bi-exclamation-circle-fill fs-4 flex-shrink-0 text-warning"></i>
                    <div>
                        <strong class="d-block text-warning">Peringatan</strong>
                        <span class="small text-dark">{{ session('warning') }}</span>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    @endif

    <div class="author-wrapper">
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div class="sidebar-backdrop d-lg-none" id="sidebarBackdrop"></div>

        <!-- Sidebar (FoxHR Style Dark Canvas + Pena Cendekia Colors) -->
        <aside class="author-sidebar" id="authorSidebar">
            <!-- Brand -->
            <a href="{{ route('author.dashboard') }}" class="sidebar-brand-box">
                <img src="{{ asset('images/pena.png') }}" class="sidebar-brand-logo" alt="Logo Pena Cendekia">
                <div>
                    <span class="sidebar-brand-title">Pena <span>Cendekia</span></span>
                    <span class="sidebar-brand-sub">Ruang Kerja Penulis</span>
                </div>
            </a>

            <!-- Nav Links -->
            <div class="sidebar-nav-container">
                <span class="sidebar-section-title">Menu Utama</span>

                <!-- 1. Dashboard (FoxHR Grid Icon) -->
                <a href="{{ route('author.dashboard') }}" class="sidebar-link {{ request()->routeIs('author.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>

                <!-- 2. Layanan (Accordion Submenu - Concept from Image 2) -->
                <div class="sidebar-dropdown-group">
                    <button class="sidebar-link sidebar-dropdown-toggle {{ request()->routeIs('layanan.*') || request()->routeIs('template.*') ? '' : 'collapsed' }}" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#submenuLayanan" 
                            aria-expanded="{{ request()->routeIs('layanan.*') || request()->routeIs('template.*') ? 'true' : 'false' }}">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Layanan</span>
                        <i class="bi bi-chevron-down ms-auto dropdown-chevron"></i>
                    </button>
                    
                    <div class="collapse {{ request()->routeIs('layanan.*') || request()->routeIs('template.*') ? 'show' : 'show' }}" id="submenuLayanan">
                        <div class="sidebar-submenu-tree">
                            <a href="{{ route('author.submissions.create', ['service' => 'penerbitan']) }}" class="sidebar-sublink {{ request()->is('author/submissions/create*') && request('service', 'penerbitan') === 'penerbitan' ? 'active' : '' }}">
                                <i class="bi bi-book-half"></i>
                                <span>Paket Penerbitan Buku</span>
                            </a>
                            <a href="{{ route('author.submissions.create', ['service' => 'hki']) }}" class="sidebar-sublink {{ request('service') === 'hki' ? 'active' : '' }}">
                                <i class="bi bi-shield-check"></i>
                                <span>Pengurusan HKI</span>
                            </a>
                            <a href="{{ route('author.submissions.create', ['service' => 'editing']) }}" class="sidebar-sublink {{ request('service') === 'editing' ? 'active' : '' }}">
                                <i class="bi bi-pencil-square"></i>
                                <span>Editing &amp; Tata Letak</span>
                            </a>
                            <a href="{{ route('author.submissions.create', ['service' => 'konversi']) }}" class="sidebar-sublink {{ request('service') === 'konversi' ? 'active' : '' }}">
                                <i class="bi bi-journal-bookmark-fill"></i>
                                <span>Konversi KTI ke Buku</span>
                            </a>
                            <a href="{{ route('template.buku') }}" class="sidebar-sublink {{ request()->routeIs('template.buku') ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-arrow-down-fill"></i>
                                <span>Template Buku</span>
                            </a>
                        </div>
                    </div>
                </div>

                <span class="sidebar-section-title mt-2">Karya & Identitas</span>

                <!-- 4. Profil -->
                <a href="{{ route('author.profile.edit') }}" class="sidebar-link {{ request()->routeIs('author.profile.edit') ? 'active' : '' }}">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Profil</span>
                </a>

                <!-- 5. Notifikasi -->
                @php $unreadCount = auth()->user()->unreadNotificationsCount(); @endphp
                <a href="{{ route('notifications.index') }}" class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i class="bi bi-bell-fill"></i>
                    <span>Notifikasi</span>
                    @if($unreadCount > 0)
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 11px;">{{ $unreadCount }}</span>
                    @endif
                </a>

                <span class="sidebar-section-title mt-2">Bantuan & Redaksi</span>

                <!-- 6. Hubungi Admin Redaksi (WhatsApp) -->
                <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Redaksi Pena Cendekia, saya penulis ' . auth()->user()->name . ', ingin konsultasi mengenai penerbitan naskah.') }}" target="_blank" rel="noopener" class="sidebar-link">
                    <i class="bi bi-whatsapp text-success"></i>
                    <span>Hubungi Redaksi</span>
                </a>

                <!-- 7. Halaman Utama Web -->
                <a href="{{ route('home') }}" class="sidebar-link" target="_blank">
                    <i class="bi bi-globe2"></i>
                    <span>Kunjungi Website</span>
                </a>
            </div>

            <!-- Art Academy Help Center Widget -->
            <div class="sidebar-help-box">
                <div class="sidebar-help-icon">?</div>
                <h6 class="sidebar-help-title">Help center</h6>
                <p class="sidebar-help-desc">Ada kendala naskah? Hubungi tim redaksi kami!</p>
                <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Redaksi Pena Cendekia, saya ingin bertanya tentang naskah saya.') }}" target="_blank" rel="noopener" class="sidebar-help-btn">
                    Hubungi Redaksi WA
                </a>
            </div>

            <!-- Footer User Card -->
            <div class="sidebar-footer">
                <div class="author-mini-card mb-2">
                    <div class="author-mini-avatar">
                        @if(auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" referrerpolicy="no-referrer" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <span style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-weight: 800;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div style="min-width: 0; flex-grow: 1;">
                        <span class="author-mini-name d-block" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</span>
                        <span class="author-mini-role"><i class="bi bi-patch-check-fill me-1"></i>Penulis Resmi</span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-3 py-1 d-flex align-items-center justify-content-center gap-2" style="font-size: 12px;">
                        <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area (Edge to Edge Full Width) -->
        <main class="author-main">
            <!-- Top Bar (Warna Gelap Obsidian / Abu-abu Gelap seperti Web #0d1217) -->
            <header class="author-topbar">
                <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
                    <button type="button" class="btn btn-topbar-icon d-lg-none flex-shrink-0" id="sidebarToggle" aria-label="Toggle Sidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <a href="{{ route('author.dashboard') }}" class="d-lg-none d-flex align-items-center flex-shrink-0 text-decoration-none me-1" title="Pena Cendekia">
                        <img src="{{ asset('images/pena.png') }}" alt="Pena Cendekia" style="height: 30px; width: 30px; object-fit: contain; filter: drop-shadow(0 1px 3px rgba(0,0,0,0.3));">
                    </a>
                    <div class="d-flex align-items-center gap-2">
                        <h1 class="topbar-title text-truncate">@yield('page-title', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 gap-sm-3 flex-shrink-0">
                    <!-- Search Input (Sleek Dark Theme) -->
                    <div class="topbar-search-box d-none d-md-block">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Cari naskah, layanan, SOP..." id="topbarSearchInput">
                    </div>

                    <!-- Message / WhatsApp Quick Link -->
                    <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Redaksi Pena Cendekia, saya ingin bertanya tentang proses penerbitan.') }}" target="_blank" rel="noopener" class="btn-topbar-icon d-none d-sm-inline-flex" title="Pesan Redaksi">
                        <i class="bi bi-envelope"></i>
                    </a>

                    <!-- Theme Toggle Button (Light / Dark) -->
                    <button type="button" class="btn-topbar-icon" id="themeToggleBtn" onclick="toggleAuthorTheme()" title="Ganti Mode Gelap / Terang" aria-label="Toggle Theme">
                        <i class="bi bi-moon-stars-fill" id="themeToggleIcon"></i>
                    </button>

                    <!-- Notifikasi Icon -->
                    <a href="{{ route('notifications.index') }}" class="btn-topbar-icon" title="Notifikasi">
                        <i class="bi bi-bell"></i>
                        @if($unreadCount > 0)
                            <span class="badge rounded-pill">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </a>

                    <!-- User Profile Chip -->
                    <a href="{{ route('author.profile.edit') }}" class="topbar-user-chip" title="Profil Penulis">
                        <div class="topbar-user-avatar">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" referrerpolicy="no-referrer" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <span style="display: none; width: 100%; height: 100%; align-items: center; justify-content: center; font-weight: 800;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                        <span class="topbar-user-name">{{ Str::limit(auth()->user()->name, 16) }}</span>
                    </a>
                </div>
            </header>

            <!-- Page Body -->
            <div class="author-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- MODAL POPUP: PAKET SERVICE DI WEB -->
    <div class="modal fade modal-theme-dark" id="servicesModal" tabindex="-1" aria-labelledby="servicesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <div class="d-inline-flex align-items-center gap-2 mb-1" style="font-size: 11px; color: var(--author-gold); font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">
                            <i class="bi bi-patch-check-fill"></i> Resmi Terdaftar di Perpustakaan Nasional RI (Perpusnas)
                        </div>
                        <h4 class="modal-title fw-bold" id="servicesModalLabel" style="font-family: var(--font-heading);">
                            Pilihan Paket Layanan Penerbitan & Percetakan
                        </h4>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-pills mb-4 gap-2 justify-content-center" id="serviceTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-penerbitan-btn" data-bs-toggle="pill" data-bs-target="#tab-penerbitan" type="button" role="tab">
                                <i class="bi bi-book-half me-1"></i> Paket Penerbitan Ber-ISBN (6 Paket)
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-hki-btn" data-bs-toggle="pill" data-bs-target="#tab-hki" type="button" role="tab">
                                <i class="bi bi-shield-lock-fill me-1"></i> Pengurusan HKI / Hak Cipta
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Contents -->
                    <div class="tab-content" id="serviceTabsContent">
                        <!-- 1. Tab Penerbitan (6 Paket Utama) -->
                        <div class="tab-pane fade show active" id="tab-penerbitan" role="tabpanel">
                            <div class="row g-3">
                                <!-- Paket 1: 3 Buku -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="service-pkg-card">
                                        <div class="pkg-title">Paket 3 Buku</div>
                                        <div class="pkg-price"><span>Rp</span>750.000</div>
                                        <div class="pkg-sub">Maks 150 Halaman &bull; Standar UNESCO / A5</div>
                                        <ul class="pkg-features">
                                            <li><i class="bi bi-check2-circle"></i><span>Free ISBN & Barcode Resmi Perpusnas</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Desain Kover Menarik & Elegan</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Tata Letak / Layout Standar Tinggi</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>e-Sertifikat Penulis Resmi</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 3 Eksemplar</strong></span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Bukti Serah Simpan Karya Cetak</span></li>
                                        </ul>
                                        <a href="{{ route('author.submissions.create', ['package' => 'paket-3']) }}" class="btn btn-outline-gold w-100 mt-auto">
                                            Pilih Paket Ini
                                        </a>
                                    </div>
                                </div>

                                <!-- Paket 2: 5 Buku -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="service-pkg-card">
                                        <div class="pkg-title">Paket 5 Buku</div>
                                        <div class="pkg-price"><span>Rp</span>800.000</div>
                                        <div class="pkg-sub">Maks 150 Halaman &bull; Standar UNESCO / A5</div>
                                        <ul class="pkg-features">
                                            <li><i class="bi bi-check2-circle"></i><span>Free ISBN & Barcode Resmi Perpusnas</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Desain Kover Menarik & Elegan</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Tata Letak Standar UNESCO</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>e-Sertifikat Penulis Resmi</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 5 Eksemplar</strong></span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Bukti Serah Simpan Karya Cetak</span></li>
                                        </ul>
                                        <a href="{{ route('author.submissions.create', ['package' => 'paket-5']) }}" class="btn btn-outline-gold w-100 mt-auto">
                                            Pilih Paket Ini
                                        </a>
                                    </div>
                                </div>

                                <!-- Paket 3: 10 Buku (Paling Populer) -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="service-pkg-card highlight">
                                        <span class="pkg-badge">Paling Populer</span>
                                        <div class="pkg-title">Paket 10 Buku</div>
                                        <div class="pkg-price"><span>Rp</span>1.000.000</div>
                                        <div class="pkg-sub">Maks 150 Halaman &bull; Standar UNESCO / A5</div>
                                        <ul class="pkg-features">
                                            <li><i class="bi bi-check2-circle"></i><span>Free ISBN & Barcode Resmi Perpusnas</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Desain Kover Kreatif & Eksklusif</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Tata Letak & Proofing Profesional</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>e-Sertifikat Penulis Resmi</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 10 Eksemplar</strong></span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Bukti Serah Simpan Karya Cetak</span></li>
                                        </ul>
                                        <a href="{{ route('author.submissions.create', ['package' => 'paket-10']) }}" class="btn btn-gold w-100 mt-auto">
                                            Pilih Paket Populer
                                        </a>
                                    </div>
                                </div>

                                <!-- Paket 4: 25 Buku (Rekomendasi) -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="service-pkg-card">
                                        <span class="pkg-badge" style="background: #3B82F6; color: #fff;">Rekomendasi</span>
                                        <div class="pkg-title">Paket 25 Buku</div>
                                        <div class="pkg-price"><span>Rp</span>1.650.000</div>
                                        <div class="pkg-sub">Maks 150 Halaman &bull; Standar UNESCO / A5</div>
                                        <ul class="pkg-features">
                                            <li><i class="bi bi-check2-circle"></i><span>Free ISBN & Barcode Resmi Perpusnas</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Desain Kover Profesional Eksklusif</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Tata Letak & Proofing Buku</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>e-Sertifikat Penulis Resmi</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 25 Eksemplar</strong></span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Diskon Pengurusan HAKI Rp 50.000</span></li>
                                        </ul>
                                        <a href="{{ route('author.submissions.create', ['package' => 'paket-25']) }}" class="btn btn-outline-gold w-100 mt-auto">
                                            Pilih Paket Ini
                                        </a>
                                    </div>
                                </div>

                                <!-- Paket 5: 50 Buku -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="service-pkg-card">
                                        <div class="pkg-title">Paket 50 Buku</div>
                                        <div class="pkg-price"><span>Rp</span>2.650.000</div>
                                        <div class="pkg-sub">Maks 150 Halaman &bull; Standar UNESCO / A5</div>
                                        <ul class="pkg-features">
                                            <li><i class="bi bi-check2-circle"></i><span>Free ISBN & Barcode Resmi Perpusnas</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Desain Kover Premium Elegan</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Tata Letak Standar UNESCO</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>e-Sertifikat Penulis Resmi</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 50 Eksemplar</strong></span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Diskon Pengurusan HAKI Rp 100.000</span></li>
                                        </ul>
                                        <a href="{{ route('author.submissions.create', ['package' => 'paket-50']) }}" class="btn btn-outline-gold w-100 mt-auto">
                                            Pilih Paket Ini
                                        </a>
                                    </div>
                                </div>

                                <!-- Paket 6: 100 Buku (Free HKI) -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="service-pkg-card highlight">
                                        <span class="pkg-badge" style="background: #10B981; color: #fff;">Free HKI Resmi</span>
                                        <div class="pkg-title">Paket 100 Buku</div>
                                        <div class="pkg-price"><span>Rp</span>4.500.000</div>
                                        <div class="pkg-sub">Maks 150 Halaman &bull; Standar UNESCO / A5</div>
                                        <ul class="pkg-features">
                                            <li><i class="bi bi-check2-circle"></i><span>Free ISBN & Barcode Resmi Perpusnas</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Desain Kover Eksklusif & Proofing</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>Tata Letak Standar UNESCO</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span>e-Sertifikat Penulis Resmi</span></li>
                                            <li><i class="bi bi-check2-circle"></i><span><strong>Buku Cetak 100 Eksemplar</strong></span></li>
                                            <li><i class="bi bi-check2-circle"></i><span><strong>GRATIS Sertifikat HAKI Kemenkumham</strong></span></li>
                                        </ul>
                                        <a href="{{ route('author.submissions.create', ['package' => 'paket-100']) }}" class="btn btn-gold w-100 mt-auto">
                                            Pilih Paket 100 Buku
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Tab HKI -->
                        <div class="tab-pane fade" id="tab-hki" role="tabpanel">
                            <div class="p-3 rounded-4" style="background-color: #141E34; border: 1px solid rgba(255, 255, 255, 0.08);">
                                <h5 class="fw-bold text-white mb-2" style="font-family: var(--font-heading);">
                                    Pengurusan Hak Cipta (HKI) Resmi DJKI Kemenkumham
                                </h5>
                                <p class="text-muted small mb-3">
                                    Lindungi hak kekayaan intelektual karya tulis Anda dengan Surat Pencatatan Ciptaan resmi ber-barcode dari Kementerian Hukum dan HAM RI.
                                </p>

                                <div class="mb-3 p-3 rounded-3" style="background: rgba(255, 255, 255, 0.04); border: 1px dashed rgba(245, 166, 35, 0.3);">
                                    <span class="small text-warning fw-bold d-block mb-2"><i class="bi bi-file-earmark-arrow-down-fill me-1"></i> Unduh Format / Template Persyaratan HKI:</span>
                                    <div class="d-flex flex-wrap gap-2">
                                        <div class="btn-group btn-group-sm">
                                            <a href="https://docs.google.com/document/d/1mhW0iQCF87RybsrBG4nALvQWl57kaleb/edit" target="_blank" rel="noopener" class="btn btn-outline-info py-1 px-2" style="font-size: 11px;">
                                                <i class="bi bi-box-arrow-up-right me-1"></i> Form Isian Ciptaan
                                            </a>
                                            <a href="{{ asset('downloads/form-isian-hak-cipta.docx') }}" download class="btn btn-outline-light py-1 px-2" style="font-size: 11px;" title="Unduh .docx">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="https://docs.google.com/document/d/1mhQrLKIVESY2vE7pRz9CVXRINmrHkBn4/edit" target="_blank" rel="noopener" class="btn btn-outline-info py-1 px-2" style="font-size: 11px;">
                                                <i class="bi bi-box-arrow-up-right me-1"></i> Surat Pernyataan
                                            </a>
                                            <a href="{{ asset('downloads/surat-pernyataan-hak-cipta.docx') }}" download class="btn btn-outline-light py-1 px-2" style="font-size: 11px;" title="Unduh .docx">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            <a href="https://docs.google.com/document/d/1mgdAeH0-M01DUAKMnYFMnDlG8hNYNMdn/edit" target="_blank" rel="noopener" class="btn btn-outline-info py-1 px-2" style="font-size: 11px;">
                                                <i class="bi bi-box-arrow-up-right me-1"></i> Surat Pengalihan
                                            </a>
                                            <a href="{{ asset('downloads/surat-pengalihan-hak-cipta.docx') }}" download class="btn btn-outline-light py-1 px-2" style="font-size: 11px;" title="Unduh .docx">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-3" style="background-color: #18243E;">
                                            <h6 class="fw-bold text-warning mb-1">HKI Buku &amp; Modul Ajar</h6>
                                            <p class="small text-muted mb-0">Proses kilat 1-3 hari kerja. Sertifikat resmi langsung terbit dan diakui untuk BKD/PAK Dosen serta Penilaian Angka Kredit Guru.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-3" style="background-color: #18243E;">
                                            <h6 class="fw-bold text-warning mb-1">HKI Karya Ilmiah &amp; Seni</h6>
                                            <p class="small text-muted mb-0">Melindungi tesis, disertasi, naskah drama, kumpulan cerpen, dan karya seni visual dari pembajakan.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap mt-4">
                                    <a href="{{ route('author.submissions.create', ['service' => 'hki']) }}" class="btn btn-gold">
                                        <i class="bi bi-shield-check me-1"></i> Ajukan Pengurusan HKI ke Admin
                                    </a>
                                    <a href="https://wa.me/6285785522283?text={{ urlencode('Halo Admin Pena Cendekia, saya ingin tanya-tanya mengenai syarat HKI.') }}" target="_blank" rel="noopener" class="btn btn-outline-gold">
                                        <i class="bi bi-whatsapp"></i> Tanya-tanya via WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <span class="small text-muted">
                        <i class="bi bi-info-circle me-1"></i> Butuh bantuan konsultasi paket? Hubungi redaksi kami 24/7.
                    </span>
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Mobile Toggle, Modal Helper & Flash Auto-Dismiss -->
    <script>
        // Function to open specific tab inside services modal
        function openServiceModalTab(tabTarget) {
            const modalEl = document.getElementById('servicesModal');
            if (!modalEl) return;
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();

            const tabTrigger = document.querySelector(`[data-bs-target="${tabTarget}"]`);
            if (tabTrigger) {
                const tab = new bootstrap.Tab(tabTrigger);
                tab.show();
            }
        }

        // Theme Toggle Logic (Light / Dark Mode)
        function updateThemeToggleIcon(theme) {
            const icon = document.getElementById('themeToggleIcon');
            const btn = document.getElementById('themeToggleBtn');
            if (!icon) return;
            if (theme === 'dark') {
                icon.className = 'bi bi-sun-fill text-warning';
                if (btn) btn.setAttribute('title', 'Beralih ke Mode Terang (Light)');
            } else {
                icon.className = 'bi bi-moon-stars-fill';
                if (btn) btn.setAttribute('title', 'Beralih ke Mode Gelap (Dark)');
            }
        }

        function toggleAuthorTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('pc_author_theme', newTheme);
            updateThemeToggleIcon(newTheme);
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Sync theme toggle icon with current active theme
            const activeTheme = document.documentElement.getAttribute('data-theme') || 'light';
            updateThemeToggleIcon(activeTheme);

            // Sidebar toggle & backdrop for mobile
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('authorSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            function openSidebar() {
                sidebar.classList.add('show');
                if (backdrop) backdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                if (backdrop) backdrop.classList.remove('show');
                document.body.style.overflow = '';
            }

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    if (sidebar.classList.contains('show')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });

                if (backdrop) {
                    backdrop.addEventListener('click', closeSidebar);
                }

                document.addEventListener('click', function (e) {
                    if (window.innerWidth < 992 && sidebar.classList.contains('show') && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                        closeSidebar();
                    }
                });
            }

            // Auto-dismiss Flash Alerts in 5 seconds
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
                }, 5000);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>