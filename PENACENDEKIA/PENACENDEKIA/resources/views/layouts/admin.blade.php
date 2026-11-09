<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Super Admin') &mdash; PT. Pena Cendekia</title>

    <!-- Google Fonts (Poppins & Plus Jakarta Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Anti-flicker Early Theme Script -->
    <script>
        (function () {
            const savedTheme = localStorage.getItem('pc_theme') || localStorage.getItem('pc_author_theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>

    <style>
        :root {
            --topbar-height: 64px;

            /* Dark Shell (Sidebar, Logo Box, Topbar are Obsidian Black #0d1217 in Light Theme) */
            --admin-sidebar-bg: #0d1217;
            --admin-sidebar-surface: #141c24;
            --admin-sidebar-border: rgba(255, 255, 255, 0.08);
            --admin-bg: #F1F5F9;
            --admin-card-bg: #FFFFFF;
            --admin-card-border: #CBD5E1;
            --admin-border: #CBD5E1;
            --admin-card-surface: #F8FAFC;
            
            --admin-topbar-bg: #0d1217;
            --admin-topbar-border: rgba(255, 255, 255, 0.08);

            --admin-yellow: #F5A623;
            --admin-yellow-hover: #E09210;
            --admin-gold: #F5A623;
            --admin-gold-hover: #E09210;
            --admin-gold-dark: #D48010;
            --admin-gold-subtle: #FFF8EE;
            --admin-gold-glow: rgba(245, 166, 35, 0.35);
            
            --admin-navy: #1B1D2A;
            --admin-text-main: #111827;
            --admin-text-sub: #4B5563;
            --admin-text-muted: #64748B;
            
            --admin-success: #10B981;
            --admin-info: #06B6D4;
            --admin-purple: #8B5CF6;
            --admin-danger: #EF4444;

            --font-main: 'Plus Jakarta Sans', 'Poppins', sans-serif;
            --font-heading: 'Plus Jakarta Sans', sans-serif;

            /* Legacy compatibility */
            --bs-body-bg: var(--admin-bg);
            --bs-body-color: var(--admin-text-main);
            --brand-primary: var(--admin-gold);
            --brand-primary-hover: var(--admin-gold-hover);
        }

        /* Dark Theme Variables */
        [data-theme="dark"] {
            --admin-sidebar-bg: #0d1217;
            --admin-sidebar-surface: #131a22;
            --admin-sidebar-border: rgba(255, 255, 255, 0.08);
            --admin-bg: #070a0e;
            --admin-card-bg: #111720;
            --admin-card-border: rgba(255, 255, 255, 0.08);
            --admin-border: rgba(255, 255, 255, 0.08);
            --admin-card-surface: #16202c;
            
            --admin-topbar-bg: #0d1217;
            --admin-topbar-border: rgba(255, 255, 255, 0.08);

            --admin-gold-subtle: rgba(245, 166, 35, 0.12);
            
            --admin-navy: #0d1217;
            --admin-text-main: #F8FAFC;
            --admin-text-sub: #94A3B8;
            --admin-text-muted: #64748B;

            --bs-body-bg: var(--admin-bg);
            --bs-body-color: var(--admin-text-main);
        }

        html, body {
            font-family: var(--font-main);
            background-color: var(--admin-bg);
            color: var(--admin-text-main);
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

        /* Flash Alerts */
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
        .admin-wrapper {
            display: flex;
            height: 100vh;
            max-height: 100vh;
            width: 100%;
            max-width: 100%;
            overflow: hidden;
            position: relative;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: 255px;
            background-color: var(--admin-sidebar-bg);
            border-right: 1.5px solid var(--admin-sidebar-border);
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
            border-bottom: 1.5px solid var(--admin-sidebar-border);
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            flex-shrink: 0;
            background-color: var(--admin-sidebar-bg);
            transition: background-color 0.25s ease, border-color 0.25s ease;
        }
        .sidebar-brand-logo {
            height: 36px;
            width: auto;
            object-fit: contain;
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
            font-size: 9.5px;
            letter-spacing: 0.5px;
            color: #94A3B8;
            font-weight: 600;
            display: block;
            margin-top: 2px;
        }

        /* Super Admin Role Badge inside Sidebar */
        .sidebar-role-indicator {
            padding: 10px 16px 6px;
        }
        .super-admin-pill {
            background: linear-gradient(135deg, rgba(245, 166, 35, 0.18), rgba(245, 166, 35, 0.05));
            border: 1px solid rgba(245, 166, 35, 0.35);
            border-radius: 10px;
            padding: 6px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #F5A623;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.6px;
            text-transform: uppercase;
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
            padding: 10px 12px 2px;
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
            color: #111827 !important;
            background: #F5A623 !important;
            font-weight: 800;
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 20px;
            background: #111827;
            border-radius: 0 4px 4px 0;
        }
        .sidebar-link i {
            font-size: 17px;
            color: #94A3B8;
            transition: color 0.2s;
            flex-shrink: 0;
        }
        .sidebar-link:hover i {
            color: #F5A623;
        }
        .sidebar-link.active i {
            color: #111827 !important;
        }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 12px 16px;
            border-top: 1px solid var(--admin-sidebar-border);
            background-color: var(--admin-sidebar-surface);
            flex-shrink: 0;
        }
        .admin-mini-card {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .admin-mini-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #F5A623, #D48010);
            color: #111827;
            font-weight: 800;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(245, 166, 35, 0.25);
        }
        .admin-mini-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }
        .admin-mini-name {
            font-size: 12.5px;
            font-weight: 700;
            color: #FFFFFF;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .admin-mini-role {
            font-size: 10.5px;
            color: #F5A623;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        /* Main Content Layout */
        .admin-main {
            flex-grow: 1;
            margin-left: 255px;
            height: 100vh;
            max-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--admin-bg);
            transition: margin-left 0.3s ease, background-color 0.25s ease;
            width: calc(100% - 255px);
            max-width: calc(100% - 255px);
            overflow: hidden;
            min-height: 0;
        }

        /* Topbar Styling */
        .admin-topbar {
            height: var(--topbar-height);
            min-height: var(--topbar-height);
            max-height: var(--topbar-height);
            box-sizing: border-box;
            background-color: var(--admin-topbar-bg);
            border-bottom: 1.5px solid var(--admin-topbar-border);
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
            position: relative;
            z-index: 1020;
            transition: background-color 0.25s ease, border-color 0.25s ease;
        }
        .topbar-title {
            font-family: var(--font-heading);
            font-weight: 800;
            font-size: 17px;
            letter-spacing: -0.3px;
            margin: 0;
            color: #FFFFFF;
            line-height: 1.2;
        }
        .topbar-search-box {
            position: relative;
            width: 280px;
        }
        .topbar-search-box input {
            width: 100%;
            background-color: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 7px 14px 7px 36px;
            font-size: 13px;
            color: #FFFFFF;
            transition: all 0.2s ease;
            outline: none;
        }
        .topbar-search-box input:focus {
            background-color: rgba(255, 255, 255, 0.12);
            border-color: #F5A623;
            box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.18);
        }
        .topbar-search-box input::placeholder {
            color: #64748B;
        }
        .topbar-search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748B;
            font-size: 14px;
            pointer-events: none;
        }
        .btn-topbar-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background-color: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #CBD5E1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
            position: relative;
        }
        .btn-topbar-icon:hover {
            background-color: rgba(255, 255, 255, 0.12);
            color: #F5A623;
            border-color: rgba(245, 166, 35, 0.3);
        }
        .btn-topbar-icon .badge {
            position: absolute;
            top: -3px;
            right: -3px;
            background-color: #EF4444;
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 5px;
            border: 2px solid #0d1217;
        }

        .topbar-user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 4px 12px 4px 5px;
            text-decoration: none;
            color: #FFFFFF;
            transition: all 0.2s ease;
        }
        .topbar-user-chip:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(245, 166, 35, 0.3);
            color: #F5A623;
        }
        .topbar-user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #F5A623;
            color: #111827;
            font-weight: 800;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .topbar-user-name {
            font-size: 13px;
            font-weight: 700;
        }

        /* Body Content (Independently Scrollable, Topbar Fixed) */
        .admin-content {
            padding: 20px 24px;
            flex-grow: 1;
            height: calc(100vh - var(--topbar-height));
            max-height: calc(100vh - var(--topbar-height));
            overflow-y: auto;
            overflow-x: hidden;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }
        .admin-content::-webkit-scrollbar {
            width: 6px;
        }
        .admin-content::-webkit-scrollbar-track {
            background: transparent;
        }
        .admin-content::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.35);
            border-radius: 10px;
        }
        .admin-content::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.6);
        }

        /* Generic Admin Cards & Components */
        .card-soft, .admin-card {
            background: var(--admin-card-bg);
            border: 1.5px solid var(--admin-card-border) !important;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            transition: background-color 0.25s ease, border-color 0.25s ease, transform 0.2s ease, box-shadow 0.2s ease;
            color: var(--admin-text-main);
        }
        [data-theme="dark"] .card-soft, [data-theme="dark"] .admin-card {
            background: var(--admin-card-bg);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .btn-brand {
            background: #F5A623;
            color: #111827 !important;
            font-weight: 700;
            border-radius: 10px;
            padding: 8px 18px;
            border: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-brand:hover {
            background: #E09210;
            color: #111827 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(245, 166, 35, 0.35);
        }

        /* High-Contrast Crisp Borders for Forms & Cards (Admin & Designer) */
        .form-control, .form-select {
            background-color: #FFFFFF !important;
            border: 1.5px solid #94A3B8 !important;
            border-radius: 10px;
            color: #0F172A !important;
            font-size: 13.5px;
            padding: 8px 14px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.04);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .form-control:hover, .form-select:hover {
            border-color: #64748B !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: #F5A623 !important;
            box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.25) !important;
            outline: none;
            background-color: #FFFFFF !important;
            color: #0F172A !important;
        }
        .form-control::placeholder {
            color: #94A3B8 !important;
        }
        .form-control[readonly], .form-control:disabled {
            background-color: #F1F5F9 !important;
            color: #475569 !important;
            border-color: #CBD5E1 !important;
        }
        .input-group {
            border-radius: 10px;
        }
        .input-group > .input-group-text {
            background-color: #FFFFFF !important;
            border: 1.5px solid #94A3B8 !important;
            border-right: none !important;
            border-top-left-radius: 10px !important;
            border-bottom-left-radius: 10px !important;
            border-top-right-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
            color: #64748B !important;
            font-weight: 600;
            padding: 8px 12px;
            transition: border-color 0.2s ease, color 0.2s ease;
        }
        .input-group > .form-control {
            border: 1.5px solid #94A3B8 !important;
            border-left: none !important;
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
            border-top-right-radius: 10px !important;
            border-bottom-right-radius: 10px !important;
        }
        .input-group:focus-within > .input-group-text {
            border-color: #F5A623 !important;
            color: #F5A623 !important;
        }
        .input-group:focus-within > .form-control {
            border-color: #F5A623 !important;
            box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.25) !important;
        }
        .card, .card-soft, .dash-card, .admin-card {
            border: 1.5px solid var(--admin-card-border) !important;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.03);
            border-radius: 16px;
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
            background-color: #F5A623 !important;
            border-color: #F5A623 !important;
        }
        .form-label {
            color: #0F172A !important;
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 6px;
        }

        /* Modern Admin Tables */
        .table-modern {
            color: var(--admin-text-main);
            vertical-align: middle;
            margin-bottom: 0;
        }
        .table-modern thead th {
            background-color: var(--admin-card-surface, #F8FAFC) !important;
            color: var(--admin-text-sub, #4B5563) !important;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5px solid var(--admin-card-border) !important;
            padding: 13px 16px;
            white-space: nowrap;
        }
        .table-modern tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--admin-card-border);
            vertical-align: middle;
        }
        .table-modern tbody tr:hover td {
            background-color: rgba(245, 166, 35, 0.04);
        }
        [data-theme="dark"] .table-modern thead th {
            background-color: #16202c !important;
            border-bottom-color: rgba(255, 255, 255, 0.08) !important;
            color: #94A3B8 !important;
        }
        [data-theme="dark"] .table-modern tbody td {
            border-bottom-color: rgba(255, 255, 255, 0.06);
        }
        [data-theme="dark"] .table-modern tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.02);
        }

        /* Global Clean Pagination for Admin Tables */
        .pagination {
            margin-bottom: 0 !important;
            gap: 4px !important;
            display: inline-flex !important;
            flex-wrap: wrap !important;
        }
        .page-item .page-link {
            color: var(--admin-text-main) !important;
            background-color: var(--admin-card-bg) !important;
            border: 1px solid var(--admin-border) !important;
            border-radius: 8px !important;
            padding: 6px 12px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 34px !important;
            box-shadow: none !important;
        }
        .page-item .page-link:hover {
            background-color: rgba(245, 166, 35, 0.15) !important;
            border-color: #F5A623 !important;
            color: #F5A623 !important;
        }
        .page-item.active .page-link {
            background-color: #F5A623 !important;
            border-color: #F5A623 !important;
            color: #111827 !important;
            font-weight: 700 !important;
            box-shadow: 0 2px 8px rgba(245, 166, 35, 0.35) !important;
        }
        .page-item.disabled .page-link {
            color: #94A3B8 !important;
            background-color: transparent !important;
            border-color: var(--admin-border) !important;
            opacity: 0.6 !important;
        }

        /* Light Theme Auto-adaptation for Admin Content */
        html:not([data-theme="dark"]) .admin-main .card .text-white,
        html:not([data-theme="dark"]) .admin-main .card-soft .text-white,
        html:not([data-theme="dark"]) .admin-main .dash-card .text-white,
        html:not([data-theme="dark"]) .admin-main h1.text-white,
        html:not([data-theme="dark"]) .admin-main h2.text-white,
        html:not([data-theme="dark"]) .admin-main h3.text-white,
        html:not([data-theme="dark"]) .admin-main h4.text-white,
        html:not([data-theme="dark"]) .admin-main h5.text-white,
        html:not([data-theme="dark"]) .admin-main h6.text-white {
            color: #111827 !important;
        }
        html:not([data-theme="dark"]) .admin-main .text-white-50 {
            color: #64748B !important;
        }
        html:not([data-theme="dark"]) .btn-outline-light {
            color: #1E293B !important;
            border: 1.5px solid #94A3B8 !important;
            background-color: #FFFFFF !important;
        }
        html:not([data-theme="dark"]) .btn-outline-light:hover {
            color: #111827 !important;
            border-color: #64748B !important;
            background-color: #F1F5F9 !important;
        }

        /* Table stylings for Dark & Light */
        .table {
            color: var(--admin-text-main);
            border-color: #CBD5E1;
        }
        .table > :not(caption) > * > * {
            border-bottom-color: #E2E8F0;
        }
        [data-theme="dark"] .table {
            border-color: rgba(255, 255, 255, 0.08);
        }
        [data-theme="dark"] .table-light {
            background-color: var(--admin-card-surface) !important;
            color: var(--admin-text-sub) !important;
            border-color: var(--admin-border) !important;
        }
        [data-theme="dark"] .table-hover > tbody > tr:hover > * {
            background-color: rgba(255, 255, 255, 0.03);
            color: var(--admin-text-main);
        }
        [data-theme="dark"] .form-control, [data-theme="dark"] .form-select {
            background-color: #16202c !important;
            border: 1.5px solid rgba(255, 255, 255, 0.16) !important;
            color: #F8FAFC !important;
            box-shadow: none;
        }
        [data-theme="dark"] .form-control:focus, [data-theme="dark"] .form-select:focus {
            border-color: #F5A623 !important;
            box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.3) !important;
            background-color: #1a2533 !important;
        }
        [data-theme="dark"] .input-group-text,
        [data-theme="dark"] .input-group > .input-group-text {
            background-color: #16202c !important;
            border: 1.5px solid rgba(255, 255, 255, 0.16) !important;
            border-right: none !important;
            color: #94A3B8 !important;
        }
        [data-theme="dark"] .input-group:focus-within > .input-group-text {
            border-color: #F5A623 !important;
            color: #F5A623 !important;
        }
        [data-theme="dark"] .card, [data-theme="dark"] .card-soft, [data-theme="dark"] .dash-card, [data-theme="dark"] .admin-card {
            border: 1.5px solid rgba(255, 255, 255, 0.1) !important;
        }
        [data-theme="dark"] .form-check-input {
            background-color: #16202c !important;
            border: 1.5px solid rgba(255, 255, 255, 0.25) !important;
        }
        [data-theme="dark"] .form-check-input:checked {
            background-color: #F5A623 !important;
            border-color: #F5A623 !important;
        }
        [data-theme="dark"] .border {
            border-color: rgba(255, 255, 255, 0.12) !important;
        }
        [data-theme="dark"] .form-label {
            color: #F8FAFC !important;
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
            background-color: var(--admin-card-bg, #101722) !important;
            color: #F8FAFC !important;
        }
        [data-theme="dark"] .bg-light {
            background-color: var(--admin-card-surface, #151F2D) !important;
            color: #F8FAFC !important;
        }
        [data-theme="dark"] .border-bottom,
        [data-theme="dark"] .border-top,
        [data-theme="dark"] .border-start,
        [data-theme="dark"] .border-end {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        [data-theme="dark"] .card {
            background-color: var(--admin-card-bg, #101722) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #F8FAFC !important;
        }
        [data-theme="dark"] .card-header {
            background-color: var(--admin-card-surface, #151F2D) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #F8FAFC !important;
        }
        [data-theme="dark"] .badge.bg-light {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #F8FAFC !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
        }

        /* Responsive Mobile Drawer */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-main {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            .sidebar-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
                z-index: 1025;
                display: none;
            }
            .sidebar-backdrop.show {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    @php
        $pendingSubmissionsCount = \App\Models\Submission::where('status', 'pending_review')->count();
        $pendingPaymentsCount = \App\Models\Payment::where('status', 'pending')->whereNotNull('proof_file')->count();
        $unreadCount = auth()->check() ? auth()->user()->unreadNotificationsCount() : 0;
    @endphp

    <!-- Flash Alerts Container -->
    @if(session('success') || session('error') || session('info') || session('warning'))
        <div class="flash-alert-container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 d-flex align-items-center gap-2 py-3 px-4" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <div class="small fw-semibold">{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 d-flex align-items-center gap-2 py-3 px-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                    <div class="small fw-semibold">{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show rounded-4 border-0 d-flex align-items-center gap-2 py-3 px-4" role="alert">
                    <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                    <div class="small fw-semibold">{{ session('info') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show rounded-4 border-0 d-flex align-items-center gap-2 py-3 px-4" role="alert">
                    <i class="bi bi-exclamation-circle-fill fs-5 text-warning"></i>
                    <div class="small fw-semibold">{{ session('warning') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
    @endif

    <div class="admin-wrapper">
        <!-- Sidebar Backdrop for Mobile -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- Super Admin Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <!-- Brand Logo Box (Obsidian Black Header) -->
            <a href="{{ auth()->user()?->isDesigner() ? route('designer.dashboard') : route('admin.dashboard') }}" class="sidebar-brand-box">
                <img src="{{ asset('images/pena.png') }}" class="sidebar-brand-logo" alt="Pena Cendekia">
                <div class="sidebar-brand-title">
                    Pena Cendekia
                    <span>{{ auth()->user()?->isDesigner() ? 'Editorial & Desain' : 'Penerbit Buku' }}</span>
                </div>
            </a>

            <!-- Role Indicator Pill -->
            <div class="sidebar-role-indicator">
                <div class="super-admin-pill" @if(auth()->user()?->isDesigner()) style="background: rgba(139, 92, 246, 0.12); color: #A78BFA; border-color: rgba(139, 92, 246, 0.25);" @endif>
                    <i class="bi {{ auth()->user()?->isDesigner() ? 'bi-palette-fill' : 'bi-shield-check' }}"></i>
                    <span>{{ auth()->user()?->isDesigner() ? 'Editor & Desain' : 'Administrator' }}</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="sidebar-nav-container">
                @if(auth()->user()?->isDesigner())
                    <span class="sidebar-section-title">Ruang Kerja</span>

                    <a href="{{ route('designer.dashboard') }}" class="sidebar-link {{ request()->routeIs('designer.*') ? 'active' : '' }}">
                        <i class="bi bi-palette2"></i>
                        <span>Dashboard</span>
                    </a>

                    <span class="sidebar-section-title">Website Publik</span>

                    <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                        <i class="bi bi-globe2"></i>
                        <span>Kunjungi Website</span>
                        <i class="bi bi-box-arrow-up-right ms-auto text-muted" style="font-size: 11px;"></i>
                    </a>
                @else
                    <span class="sidebar-section-title">Pusat Kendali</span>

                    <!-- 1. Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard Utama</span>
                    </a>

                    <span class="sidebar-section-title">Alur Penerbitan Author</span>

                    <!-- 2. Naskah Masuk -->
                    <a href="{{ route('admin.submissions.index') }}" class="sidebar-link {{ request()->routeIs('admin.submissions.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-text"></i>
                        <span>Kelola Naskah</span>
                        @if($pendingSubmissionsCount > 0)
                            <span class="badge rounded-pill ms-auto" style="background: #F5A623; color: #111827; font-weight: 800; font-size: 11px;">
                                {{ $pendingSubmissionsCount }}
                            </span>
                        @endif
                    </a>

                    <!-- 3. Verifikasi Pembayaran -->
                    <a href="{{ route('admin.payments.index') }}" class="sidebar-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                        <i class="bi bi-credit-card-2-front"></i>
                        <span>Verifikasi Bayar</span>
                        @if($pendingPaymentsCount > 0)
                            <span class="badge rounded-pill bg-danger ms-auto" style="font-weight: 800; font-size: 11px;">
                                {{ $pendingPaymentsCount }}
                            </span>
                        @endif
                    </a>

                    <span class="sidebar-section-title">Sistem & Master Data</span>

                    <!-- 4. Pengguna -->
                    <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Manajemen User</span>
                    </a>

                    <!-- 5. Rekening Bank Penerbit -->
                    <a href="{{ route('admin.paymentAccounts.index') }}" class="sidebar-link {{ request()->routeIs('admin.paymentAccounts.*') ? 'active' : '' }}">
                        <i class="bi bi-bank"></i>
                        <span>Rekening Bank</span>
                    </a>

                    <!-- 6. Portofolio Buku Terbitan -->
                    <a href="{{ route('admin.featuredBooks.index') }}" class="sidebar-link {{ request()->routeIs('admin.featuredBooks.*') ? 'active' : '' }}">
                        <i class="bi bi-journal-album"></i>
                        <span>Portofolio Buku</span>
                    </a>

                    <!-- 7. Katalog E-Book Resmi -->
                    <a href="{{ route('admin.ebooks.index') }}" class="sidebar-link {{ request()->routeIs('admin.ebooks.*') ? 'active' : '' }}">
                        <i class="bi bi-tablet"></i>
                        <span>Katalog E-Book</span>
                    </a>

                    <!-- 8. Testimoni Penulis (Terpisah) -->
                    <a href="{{ route('admin.testimonials.index') }}" class="sidebar-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                        <i class="bi bi-chat-quote-fill"></i>
                        <span>Testimoni Penulis</span>
                    </a>

                    <!-- 8. Log Aktivitas Login & Keamanan -->
                    <a href="{{ route('admin.activityLogs.index') }}" class="sidebar-link {{ request()->routeIs('admin.activityLogs.*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Log Aktivitas Login</span>
                    </a>

                    <span class="sidebar-section-title">Website Publik</span>

                    <!-- Kunjungi Website Utama -->
                    <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                        <i class="bi bi-globe2"></i>
                        <span>Kunjungi Website</span>
                        <i class="bi bi-box-arrow-up-right ms-auto text-muted" style="font-size: 11px;"></i>
                    </a>
                @endif
            </div>

            <!-- Sidebar Footer User Card -->
            <div class="sidebar-footer">
                <div class="admin-mini-card mb-2">
                    <div class="admin-mini-avatar">
                        @if(auth()->user()?->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name ?? 'User' }}" referrerpolicy="no-referrer">
                        @else
                            {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                        @endif
                    </div>
                    <div style="min-width: 0; flex-grow: 1;">
                        <span class="admin-mini-name d-block" title="{{ auth()->user()->name ?? 'Staf Redaksi' }}">{{ auth()->user()->name ?? 'Staf Redaksi' }}</span>
                        <span class="admin-mini-role">
                            @if(auth()->user()?->isAdmin())
                                <i class="bi bi-shield-check me-1"></i>Administrator
                            @else
                                <i class="bi bi-palette me-1"></i>Editor &amp; Desain
                            @endif
                        </span>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="redirect" value="staff">
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100 rounded-3 py-1 d-flex align-items-center justify-content-center gap-2" style="font-size: 12px; font-weight: 600;">
                        <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="admin-main">
            <!-- Topbar (Obsidian Black Header aligned with Author style) -->
            <header class="admin-topbar">
                <div class="d-flex align-items-center gap-2 gap-sm-3 min-w-0">
                    <button type="button" class="btn btn-topbar-icon d-lg-none flex-shrink-0" id="sidebarToggle" aria-label="Toggle Sidebar">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                    <div class="d-flex align-items-center gap-2">
                        <h1 class="topbar-title text-truncate">@yield('page-title', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 gap-sm-3 flex-shrink-0">
                    <!-- Global Search -->
                    <form action="{{ route('admin.submissions.index') }}" method="GET" class="topbar-search-box d-none d-md-block">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" placeholder="Cari naskah, kode, penulis..." value="{{ request('search') }}">
                    </form>

                    <!-- Theme Toggle Button (Light / Dark) -->
                    <button type="button" class="btn-topbar-icon" id="themeToggleBtn" onclick="toggleAdminTheme()" title="Ganti Mode Gelap / Terang" aria-label="Toggle Theme">
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

                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <button class="topbar-user-chip border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="topbar-user-avatar">
                                @if(auth()->user()?->avatar)
                                    <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name ?? 'User' }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                                @endif
                            </div>
                            <span class="topbar-user-name d-none d-sm-inline">{{ Str::limit(auth()->user()?->name ?? 'Super Admin', 16) }}</span>
                            <i class="bi bi-chevron-down text-muted" style="font-size: 10px;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" style="min-width: 210px; background-color: var(--admin-card-bg); border: 1px solid var(--admin-card-border) !important;">
                            <li class="px-3 py-2 border-bottom" style="border-color: var(--admin-border) !important;">
                                <span class="d-block fw-bold text-truncate" style="color: var(--admin-text-main);">{{ auth()->user()?->name ?? 'Staf Redaksi' }}</span>
                                <small class="text-muted d-block text-truncate">{{ auth()->user()?->email }}</small>
                                <span class="badge mt-1" style="background: {{ auth()->user()?->isDesigner() ? 'rgba(139, 92, 246, 0.15)' : 'rgba(245, 166, 35, 0.15)' }}; color: {{ auth()->user()?->isDesigner() ? '#8B5CF6' : '#F5A623' }}; font-size: 10px;">
                                    {{ auth()->user()?->isAdmin() ? 'Administrator' : 'Editor & Desain' }}
                                </span>
                            </li>
                            @if(auth()->user()?->isDesigner())
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('designer.dashboard') }}" style="color: var(--admin-text-main);">
                                        <i class="bi bi-palette text-muted"></i> Dashboard Tugas
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('admin.submissions.index') }}" style="color: var(--admin-text-main);">
                                        <i class="bi bi-journal-text text-muted"></i> Kelola Naskah
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('admin.payments.index') }}" style="color: var(--admin-text-main);">
                                        <i class="bi bi-credit-card-2-front text-muted"></i> Verifikasi Bayar
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('notifications.index') }}" style="color: var(--admin-text-main);">
                                    <i class="bi bi-bell text-muted"></i> Notifikasi
                                    @if($unreadCount > 0)
                                        <span class="badge bg-danger rounded-pill ms-auto">{{ $unreadCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li><hr class="dropdown-divider" style="border-color: var(--admin-border);"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="redirect" value="staff">
                                    <button type="submit" class="dropdown-item py-2 text-danger d-flex align-items-center gap-2">
                                        <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Theme Management (Light / Dark)
        function updateThemeIcon(theme) {
            const icon = document.getElementById('themeToggleIcon');
            if (!icon) return;
            if (theme === 'dark') {
                icon.classList.remove('bi-moon-stars-fill');
                icon.classList.add('bi-sun-fill');
                icon.style.color = '#F5A623';
            } else {
                icon.classList.remove('bi-sun-fill');
                icon.classList.add('bi-moon-stars-fill');
                icon.style.color = '#CBD5E1';
            }
        }

        function toggleAdminTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('pc_theme', newTheme);
            localStorage.setItem('pc_author_theme', newTheme);
            updateThemeIcon(newTheme);
        }

        // Initialize Theme Icon on page load
        document.addEventListener('DOMContentLoaded', function () {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            updateThemeIcon(currentTheme);

            // Mobile Sidebar Toggle
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (toggleBtn && sidebar && backdrop) {
                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                });

                backdrop.addEventListener('click', function () {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
