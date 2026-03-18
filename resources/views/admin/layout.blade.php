<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Admin LSP SMKN 1 Ciamis</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f7fa;
            overflow-x: hidden;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: #ffffff;
            color: #0061A5;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
            border-right: 1px solid #e2e8f0;
            box-shadow: 2px 0 8px rgba(0, 97, 165, 0.06);
            direction: rtl;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #bfdbfe;
            border-radius: 5px;
        }

        .sidebar-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
            direction: ltr;
        }

        .sidebar-header .admin-icon {
            width: 50px;
            height: 50px;
            background: #0061A5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 24px;
            color: #ffffff;
        }

        .sidebar-header h3,
        .sidebar-header h4 {
            font-size: 18px;
            font-weight: 700;
            color: #0061A5;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-header p {
            font-size: 11px;
            color: #64748b;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            padding: 20px 0;
            direction: ltr;
        }

        .menu-section {
            margin-top: 15px;
        }

        .menu-section:first-child {
            margin-top: 0;
        }

        .menu-section-title {
            padding: 12px 20px;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
            user-select: none;
        }

        .menu-section-title:hover {
            background: rgba(0, 97, 165, 0.05);
            color: #0061A5;
        }

        .menu-section-title i {
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .menu-section-title.collapsed i {
            transform: rotate(-90deg);
        }

        .menu-section-items {
            max-height: 1000px;
            overflow: hidden;
            transition: max-height 0.4s ease, opacity 0.3s ease;
            opacity: 1;
        }

        .menu-section-items.collapsed {
            max-height: 0;
            opacity: 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #475569;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            border-radius: 0;
        }

        .menu-item:hover {
            background: rgba(0, 97, 165, 0.08);
            color: #0061A5;
        }

        .menu-item.active {
            background: rgba(0, 97, 165, 0.12);
            color: #0061A5;
            border-left: 3px solid #0061A5;
            font-weight: 600;
        }

        .menu-item i {
            font-size: 18px;
            width: 24px;
            margin-right: 12px;
            text-align: center;
        }

        .menu-item span {
            font-size: 14px;
            font-weight: 500;
        }

        .menu-item.menu-nested-title {
            justify-content: space-between;
            cursor: pointer;
            width: 100%;
            border: none;
            background: transparent;
            font-family: inherit;
        }

        .menu-item.menu-nested-title .menu-nested-left {
            display: inline-flex;
            align-items: center;
        }

        .menu-item.menu-nested-title .nested-chevron {
            font-size: 12px;
            margin-left: auto;
            color: #94a3b8;
            transition: transform 0.25s ease;
        }

        .menu-item.menu-nested-title.collapsed .nested-chevron {
            transform: rotate(-90deg);
        }

        .menu-nested-items {
            max-height: 400px;
            overflow: hidden;
            transition: max-height 0.35s ease, opacity 0.25s ease;
            opacity: 1;
        }

        .menu-nested-items.collapsed {
            max-height: 0;
            opacity: 0;
        }

        .menu-item.menu-subitem {
            padding-left: 44px;
            font-size: 13px;
        }

        .menu-item.menu-subitem i {
            font-size: 11px;
            width: 16px;
            margin-right: 10px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            transition: all 0.3s ease;
        }

        .topbar {
            background: white;
            padding: 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar h1 {
            font-size: 24px;
            color: #0F172A;
            font-weight: 700;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 15px 15px;
            flex-shrink: 0;
        }

        /* Global Search */
        .global-search {
            position: relative;
            flex: 1;
            padding: 15px 15px;
        }

        .global-search-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            flex: 1;
        }

        .global-search-input-wrapper i.search-icon {
            position: absolute;
            left: 14px;
            font-size: 16px;
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.2s;
        }

        .global-search-input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            background: #f8fafc;
            color: #0F172A;
            transition: all 0.3s ease;
            outline: none;
        }

        .global-search-input:focus {
            border-color: #0061A5;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(0, 97, 165, 0.1);
        }

        .global-search-input:focus+.search-icon,
        .global-search-input:focus~i.search-icon {
            color: #0061A5;
        }

        .global-search-input::placeholder {
            color: #94a3b8;
        }

        .search-shortcut {
            position: absolute;
            right: 12px;
            background: #e2e8f0;
            color: #64748b;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
            pointer-events: none;
        }

        .search-results-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            border: 1px solid #e2e8f0;
            max-height: 420px;
            overflow-y: auto;
            z-index: 1001;
            display: none;
        }

        .search-results-dropdown.show {
            display: block;
        }

        .search-results-dropdown::-webkit-scrollbar {
            width: 5px;
        }

        .search-results-dropdown::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 5px;
        }

        .search-category-label {
            padding: 10px 16px 4px;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .search-result-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            text-decoration: none;
            color: #1e293b;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .search-result-item:hover,
        .search-result-item.active {
            background: #f0f7ff;
        }

        .search-result-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .search-result-info {
            flex: 1;
            min-width: 0;
        }

        .search-result-title {
            font-size: 14px;
            font-weight: 600;
            color: #0F172A;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-result-subtitle {
            font-size: 12px;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .search-result-category-badge {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .search-empty {
            padding: 24px 16px;
            text-align: center;
            color: #94a3b8;
        }

        .search-empty i {
            font-size: 32px;
            display: block;
            margin-bottom: 8px;
        }

        .search-loading {
            padding: 20px 16px;
            text-align: center;
            color: #64748b;
            font-size: 13px;
        }

        .search-loading .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid #e2e8f0;
            border-top: 2px solid #0061A5;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #0061A5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 2px 8px rgba(0, 97, 165, 0.3);
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
        }

        .user-role {
            font-size: 12px;
            color: #95a5a6;
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: relative;
        }

        .profile-toggle {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 10px;
            border-radius: 999px;
            transition: all 0.3s ease;
        }

        .profile-toggle:hover {
            background: #f1f5f9;
        }

        .profile-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
            min-width: 260px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .profile-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profile-avatar-lg {
            width: 48px;
            height: 48px;
            background: #0061A5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 97, 165, 0.3);
        }

        .profile-header-info {
            display: flex;
            flex-direction: column;
        }

        .profile-header-name {
            font-size: 14px;
            font-weight: 600;
            color: #0F172A;
            margin: 0;
        }

        .profile-header-role {
            font-size: 12px;
            color: #64748b;
            margin: 2px 0 0 0;
        }

        .profile-body {
            padding: 8px 0;
        }

        .profile-menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #475569;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .profile-menu-item:hover {
            background: #f8fafc;
            color: #0F172A;
        }

        .profile-menu-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            color: #94a3b8;
        }

        .profile-menu-item:hover i {
            color: #0061A5;
        }

        .profile-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 8px 0;
        }

        .profile-logout {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #dc2626;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .profile-logout:hover {
            background: #fef2f2;
            color: #991b1b;
        }

        .profile-logout i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .content-wrapper {
            padding: 30px;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert i {
            font-size: 18px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Mobile Toggle Button */
        .mobile-toggle {
            display: none;
            background: #0061A5;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .topbar h1 {
                font-size: 18px;
            }

            .content-wrapper {
                padding: 20px 15px;
            }

            .topbar {
                flex-direction: column;
                gap: 0;
                padding: 0;
            }

            .global-search {
                width: 100%;
                max-width: 100%;
                padding: 12px 15px !important;
                order: 1;
            }

            .topbar-right {
                width: 100%;
                padding: 12px 15px !important;
                order: 2;
            }

            .search-shortcut {
                display: none !important;
            }

            .topbar-right {
                justify-content: flex-end;
            }

            .profile-toggle {
                padding: 6px;
            }

            .profile-toggle .user-details,
            .profile-toggle .bi-chevron-down {
                display: none;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="">
                    <img src="{{ asset('images/lsp.png') }}" alt="LSP Logo"
                        style="width: 54px; height: 54px; object-fit: contain;">
                </div>
            </div>

            <nav class="sidebar-menu">

                <!-- Dashboard -->
                @if(Auth::guard('admin')->user()->hasPermission('dashboard.view'))
                    <a href="{{ route('admin.dashboard') }}"
                        class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                @endif

                <!-- ADMIN Section -->
                @if(Auth::guard('admin')->user()->hasAnyPermission(['role.view', 'admin.view']))
                    <div class="menu-section">
                        <div class="menu-section-title" onclick="toggleMenuSection(this)">
                            <span>ADMIN</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="menu-section-items">
                            @if(Auth::guard('admin')->user()->hasPermission('admin.view'))
                                <a href="{{ route('admin.admin-management.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.admin-management.*') ? 'active' : '' }}">
                                    <i class="bi bi-person-gear"></i>
                                    <span>Manajemen Admin</span>
                                </a>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermission('role.view'))
                                <a href="{{ route('admin.roles.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                    <i class="bi bi-shield-lock"></i>
                                    <span>Role & Permission</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- DATA MASTER Section -->
                @if(Auth::guard('admin')->user()->hasAnyPermission(['asesor.view', 'asesi.view', 'akun-asesi.view', 'jurusan.view', 'tuk.view', 'skema.view']))
                    <div class="menu-section">
                        <div class="menu-section-title" onclick="toggleMenuSection(this)">
                            <span>DATA MASTER</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="menu-section-items">
                            @if(Auth::guard('admin')->user()->hasPermission('asesi.view'))
                                <a href="{{ route('admin.asesi.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.asesi.index', 'admin.asesi.create', 'admin.asesi.edit') ? 'active' : '' }}">
                                    <i class="bi bi-people"></i>
                                    <span>Asesi</span>
                                </a>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermission('asesor.view'))
                                <a href="{{ route('admin.asesor.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.asesor.*') ? 'active' : '' }}">
                                    <i class="bi bi-person-badge"></i>
                                    <span>Asesor</span>
                                </a>
                            @endif

                            <!-- @if(Auth::guard('admin')->user()->hasPermission('akun-asesi.view'))
                                        <a href="{{ route('admin.akun-asesi.index') }}"
                                            class="menu-item {{ request()->routeIs('admin.akun-asesi.*') ? 'active' : '' }}">
                                            <i class="bi bi-person-vcard"></i>
                                            <span>Akun Asesi (NIK)</span>
                                        </a>
                                    @endif -->

                            @if(Auth::guard('admin')->user()->hasPermission('jurusan.view'))
                                <a href="{{ route('admin.jurusan.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.jurusan.*') ? 'active' : '' }}">
                                    <i class="bi bi-mortarboard"></i>
                                    <span>Jurusan</span>
                                </a>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermission('tuk.view'))
                                <a href="{{ route('admin.tuk.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.tuk.*') ? 'active' : '' }}">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>Tempat Uji (TUK)</span>
                                </a>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermission('skema.view'))
                                <a href="{{ route('admin.skema.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.skema.*') ? 'active' : '' }}">
                                    <i class="bi bi-patch-check"></i>
                                    <span>Skema</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- PROGRAM SERTIFIKASI Section -->
                @if(Auth::guard('admin')->user()->hasAnyPermission(['verifikasi-asesi.view', 'kelompok.view', 'jadwal-ujikom.view', 'asesmen-mandiri.view', 'nilai-asesor.view']))
                    <div class="menu-section">
                        <div class="menu-section-title" onclick="toggleMenuSection(this)">
                            <span>PROGRAM SERTIFIKASI</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="menu-section-items">
                            @if(Auth::guard('admin')->user()->hasPermission('verifikasi-asesi.view'))
                                <a href="{{ route('admin.asesi.verifikasi') }}"
                                    class="menu-item {{ request()->routeIs('admin.asesi.verifikasi*') ? 'active' : '' }}">
                                    <i class="bi bi-clipboard-check"></i>
                                    <span>Verifikasi Asesi</span>
                                    @php $pendingCount = \App\Models\Asesi::where('status', 'pending')->count(); @endphp
                                    @if($pendingCount > 0)
                                        <span
                                            style="margin-left:auto;font-size:10px;padding:2px 8px;background:#ef4444;color:#fff;border-radius:10px;font-weight:600;">{{ $pendingCount }}</span>
                                    @endif
                                </a>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermission('asesmen-mandiri.view'))
                                <a href="{{ route('admin.asesmen-mandiri.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.asesmen-mandiri.*') ? 'active' : '' }}">
                                    <i class="bi bi-journal-check"></i>
                                    <span>Asesmen Mandiri</span>
                                </a>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermission('nilai-asesor.view'))
                                <a href="{{ route('admin.nilai-asesor.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.nilai-asesor.*') ? 'active' : '' }}">
                                    <i class="bi bi-clipboard-data"></i>
                                    <span>Nilai</span>
                                </a>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermission('kelompok.view'))
                                <a href="{{ route('admin.kelompok.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.kelompok.*') ? 'active' : '' }}">
                                    <i class="bi bi-diagram-3-fill"></i>
                                    <span>Kelompok</span>
                                </a>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermission('jadwal-ujikom.view'))
                                <a href="{{ route('admin.jadwal-ujikom.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.jadwal-ujikom.*') ? 'active' : '' }}">
                                    <i class="bi bi-calendar-event"></i>
                                    <span>Jadwal Ujikom</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- WEBSITE Section -->
                @if(Auth::guard('admin')->user()->hasAnyPermission(['carousel.view', 'berita.view', 'kontak.view', 'socialmedia.view', 'profile-content.view', 'panduan.view']))
                    <div class="menu-section">
                        <div class="menu-section-title" onclick="toggleMenuSection(this)">
                            <span>WEBSITE</span>
                            <i class="bi bi-chevron-down"></i>
                        </div>
                        <div class="menu-section-items">
                            @if(Auth::guard('admin')->user()->hasPermission('carousel.view'))
                                <a href="{{ route('admin.carousel.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.carousel.*') ? 'active' : '' }}">
                                    <i class="bi bi-images"></i>
                                    <span>Banner Carousel</span>
                                </a>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermission('berita.view'))
                                <a href="{{ route('admin.berita.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}">
                                    <i class="bi bi-newspaper"></i>
                                    <span>Berita</span>
                                </a>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermission('kontak.view'))
                                <a href="{{ route('admin.kontak.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.kontak.*') ? 'active' : '' }}">
                                    <i class="bi bi-telephone"></i>
                                    <span>Kontak</span>
                                </a>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermission('socialmedia.view'))
                                <a href="{{ route('admin.socialmedia.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.socialmedia.*') ? 'active' : '' }}">
                                    <i class="bi bi-share"></i>
                                    <span>Sosial Media</span>
                                </a>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermission('profile-content.view'))
                                <a href="{{ route('admin.profile-content.index') }}"
                                    class="menu-item {{ request()->routeIs('admin.profile-content.*') ? 'active' : '' }}">
                                    <i class="bi bi-book-fill"></i>
                                    <span>Konten Profil</span>
                                </a>
                            @endif

                            @if(Auth::guard('admin')->user()->hasPermission('panduan.view'))
                                @php $isPanduanActive = request()->routeIs('admin.panduan.*'); @endphp
                                <button type="button"
                                    class="menu-item menu-nested-title {{ $isPanduanActive ? 'active' : '' }} {{ $isPanduanActive ? '' : 'collapsed' }}"
                                    data-menu-key="panduan"
                                    data-force-open="{{ $isPanduanActive ? '1' : '0' }}"
                                    onclick="toggleNestedMenu(this)">
                                    <span class="menu-nested-left">
                                        <i class="bi bi-journal-text"></i>
                                        <span>Panduan</span>
                                    </span>
                                    <i class="bi bi-chevron-down nested-chevron"></i>
                                </button>

                                <div class="menu-nested-items {{ $isPanduanActive ? '' : 'collapsed' }}" data-menu-key="panduan">
                                    <a href="{{ route('admin.panduan.index', 'alur-keseluruhan-sistem') }}"
                                        class="menu-item menu-subitem {{ request()->routeIs('admin.panduan.*') && request()->route('section') === 'alur-keseluruhan-sistem' ? 'active' : '' }}">
                                        <i class="bi bi-dot"></i>
                                        <span>Alur Keseluruhan Sistem</span>
                                    </a>
                                    <a href="{{ route('admin.panduan.index', 'peran-asesi') }}"
                                        class="menu-item menu-subitem {{ request()->routeIs('admin.panduan.*') && request()->route('section') === 'peran-asesi' ? 'active' : '' }}">
                                        <i class="bi bi-dot"></i>
                                        <span>Peran Asesi</span>
                                    </a>
                                    <a href="{{ route('admin.panduan.index', 'peran-asesor') }}"
                                        class="menu-item menu-subitem {{ request()->routeIs('admin.panduan.*') && request()->route('section') === 'peran-asesor' ? 'active' : '' }}">
                                        <i class="bi bi-dot"></i>
                                        <span>Peran Asesor</span>
                                    </a>
                                    <a href="{{ route('admin.panduan.index', 'peran-admin') }}"
                                        class="menu-item menu-subitem {{ request()->routeIs('admin.panduan.*') && request()->route('section') === 'peran-admin' ? 'active' : '' }}">
                                        <i class="bi bi-dot"></i>
                                        <span>Peran Admin</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="topbar">
                <!-- Global Search Full Width -->
                <div class="global-search" id="globalSearch">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <button class="mobile-toggle" onclick="toggleSidebar()">
                            <i class="bi bi-list"></i>
                        </button>
                        <div class="global-search-input-wrapper" style="flex: 1;">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" class="global-search-input" id="globalSearchInput"
                                placeholder="Cari asesi, asesor, kelompok, skema..." autocomplete="off">
                            <span class="search-shortcut" id="searchShortcut">Ctrl+K</span>
                        </div>
                    </div>
                    <div class="search-results-dropdown" id="searchResults"></div>
                </div>

                <div class="topbar-right">
                    <div class="profile-dropdown" id="profileDropdown">
                        <button class="profile-toggle" onclick="toggleProfileMenu(event)" type="button">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                            </div>
                            <div class="user-details">
                                <span class="user-name">{{ Auth::guard('admin')->user()->name }}</span>
                                <span class="user-role">{{ Auth::guard('admin')->user()->roles->pluck('display_name')->join(', ') ?: 'Administrator' }}</span>
                            </div>
                            <i class="bi bi-chevron-down" style="font-size: 14px; color: #64748b;"></i>
                        </button>

                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-header">
                                <div class="profile-avatar-lg">
                                    {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                                </div>
                                <div class="profile-header-info">
                                    <h4 class="profile-header-name">{{ Auth::guard('admin')->user()->name }}</h4>
                                    <p class="profile-header-role">
                                        {{ Auth::guard('admin')->user()->roles->pluck('display_name')->join(', ') ?: 'Administrator' }}
                                    </p>
                                </div>
                            </div>

                            <div class="profile-body">
                                <a href="#" class="profile-menu-item" onclick="event.preventDefault();">
                                    <i class="bi bi-person"></i>
                                    <span>Profil</span>
                                </a>
                                <a href="#" class="profile-menu-item" onclick="event.preventDefault();">
                                    <i class="bi bi-gear"></i>
                                    <span>Pengaturan</span>
                                </a>
                                <div class="profile-divider"></div>
                                <form method="POST" action="{{ route('admin.logout') }}"
                                    style="width: 100%; margin: 0;">
                                    @csrf
                                    <button type="submit" class="profile-logout">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{!! session('success') !!}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        function toggleProfileMenu(event) {
            event.stopPropagation();
            const menu = document.getElementById('profileMenu');
            menu.classList.toggle('show');
        }

        function toggleMenuSection(element) {
            const items = element.nextElementSibling;
            element.classList.toggle('collapsed');
            items.classList.toggle('collapsed');

            // Save state to localStorage
            const sectionName = element.querySelector('span').textContent;
            const isCollapsed = element.classList.contains('collapsed');
            localStorage.setItem('menu-' + sectionName, isCollapsed ? 'collapsed' : 'expanded');
        }

        function toggleNestedMenu(element) {
            const key = element.dataset.menuKey;
            if (!key) return;

            const items = document.querySelector('.menu-nested-items[data-menu-key="' + key + '"]');
            if (!items) return;

            element.classList.toggle('collapsed');
            items.classList.toggle('collapsed');

            const isCollapsed = element.classList.contains('collapsed');
            localStorage.setItem('nested-menu-' + key, isCollapsed ? 'collapsed' : 'expanded');
        }

        // Restore menu states on page load
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.menu-section-title').forEach(function (title) {
                const sectionName = title.querySelector('span').textContent;
                const state = localStorage.getItem('menu-' + sectionName);

                // By default, expand sections if no state is saved
                if (state === 'collapsed') {
                    title.classList.add('collapsed');
                    title.nextElementSibling.classList.add('collapsed');
                }
            });

            document.querySelectorAll('.menu-nested-title').forEach(function (title) {
                const key = title.dataset.menuKey;
                const forceOpen = title.dataset.forceOpen === '1';
                if (!key) return;

                const items = document.querySelector('.menu-nested-items[data-menu-key="' + key + '"]');
                if (!items) return;

                if (forceOpen) {
                    title.classList.remove('collapsed');
                    items.classList.remove('collapsed');
                    return;
                }

                const state = localStorage.getItem('nested-menu-' + key);
                if (state === 'collapsed') {
                    title.classList.add('collapsed');
                    items.classList.add('collapsed');
                } else {
                    title.classList.remove('collapsed');
                    items.classList.remove('collapsed');
                }
            });
        });

        // Close profile menu when clicking outside
        document.addEventListener('click', function (event) {
            const profileDropdown = document.getElementById('profileDropdown');
            const profileMenu = document.getElementById('profileMenu');

            if (profileDropdown && !profileDropdown.contains(event.target)) {
                profileMenu.classList.remove('show');
            }
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function (event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');

            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // ===== Global Search =====
        (function () {
            const searchInput = document.getElementById('globalSearchInput');
            const searchResults = document.getElementById('searchResults');
            const searchShortcut = document.getElementById('searchShortcut');
            let searchTimeout = null;
            let activeIndex = -1;
            let currentResults = [];

            // Ctrl+K shortcut
            document.addEventListener('keydown', function (e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    searchInput.focus();
                    searchInput.select();
                }
                // Escape to close
                if (e.key === 'Escape') {
                    closeSearch();
                    searchInput.blur();
                }
            });

            // Hide shortcut on focus
            searchInput.addEventListener('focus', function () {
                searchShortcut.style.display = 'none';
                if (searchInput.value.length >= 2) {
                    searchResults.classList.add('show');
                }
            });

            searchInput.addEventListener('blur', function () {
                setTimeout(function () {
                    searchShortcut.style.display = '';
                    closeSearch();
                }, 200);
            });

            // Live search on input
            searchInput.addEventListener('input', function () {
                const query = this.value.trim();
                activeIndex = -1;

                if (query.length < 2) {
                    closeSearch();
                    return;
                }

                // Show loading
                searchResults.innerHTML = '<div class="search-loading"><span class="spinner"></span>Mencari...</div>';
                searchResults.classList.add('show');

                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function () {
                    fetchResults(query);
                }, 300);
            });

            // Keyboard navigation
            searchInput.addEventListener('keydown', function (e) {
                const items = searchResults.querySelectorAll('.search-result-item');
                if (!items.length) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    activeIndex = Math.min(activeIndex + 1, items.length - 1);
                    updateActiveItem(items);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    activeIndex = Math.max(activeIndex - 1, 0);
                    updateActiveItem(items);
                } else if (e.key === 'Enter' && activeIndex >= 0) {
                    e.preventDefault();
                    items[activeIndex].click();
                }
            });

            function updateActiveItem(items) {
                items.forEach(function (item, i) {
                    item.classList.toggle('active', i === activeIndex);
                });
                if (items[activeIndex]) {
                    items[activeIndex].scrollIntoView({ block: 'nearest' });
                }
            }

            function fetchResults(query) {
                fetch('{{ route("admin.search") }}?q=' + encodeURIComponent(query), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                    .then(function (response) { return response.json(); })
                    .then(function (data) {
                        renderResults(data.results);
                    })
                    .catch(function () {
                        searchResults.innerHTML = '<div class="search-empty"><i class="bi bi-exclamation-circle"></i>Gagal memuat hasil</div>';
                    });
            }

            function renderResults(results) {
                currentResults = results;

                if (!results.length) {
                    searchResults.innerHTML = '<div class="search-empty"><i class="bi bi-search"></i>Tidak ada hasil ditemukan</div>';
                    searchResults.classList.add('show');
                    return;
                }

                // Group by category
                var grouped = {};
                results.forEach(function (r) {
                    if (!grouped[r.category]) grouped[r.category] = [];
                    grouped[r.category].push(r);
                });

                // Keep category order aligned with sidebar modules
                var categoryOrder = {
                    // Dashboard / Admin
                    'Dashboard': 1,
                    'Manajemen Admin': 2,
                    'Role & Permission': 3,

                    // Data Master
                    'Asesi': 10,
                    'Asesor': 11,
                    'Akun Asesi': 12,
                    'Jurusan': 13,
                    'TUK': 14,
                    'Skema': 15,
                    'Mitra': 16,

                    // Program Sertifikasi
                    'Verifikasi Asesi': 20,
                    'Asesmen Mandiri': 21,
                    'Nilai Asesor': 22,
                    'Kelompok': 23,
                    'Jadwal Ujikom': 24,
                    'Penugasan Asesor': 25,

                    // Website
                    'Carousel': 30,
                    'Berita': 31,
                    'Kontak': 32,
                    'Sosial Media': 33,
                    'Konten Profil': 34,
                    'Panduan': 35
                };

                var sortedCategories = Object.keys(grouped).sort(function (a, b) {
                    var orderA = categoryOrder[a] || 999;
                    var orderB = categoryOrder[b] || 999;

                    if (orderA === orderB) {
                        return a.localeCompare(b);
                    }

                    return orderA - orderB;
                });

                var html = '';
                sortedCategories.forEach(function (cat) {
                    html += '<div class="search-category-label">' + cat + '</div>';
                    grouped[cat].forEach(function (item) {
                        html += '<a href="' + item.url + '" class="search-result-item">';
                        html += '<div class="search-result-icon" style="background:' + item.color + '15;color:' + item.color + '"><i class="bi ' + item.icon + '"></i></div>';
                        html += '<div class="search-result-info">';
                        html += '<div class="search-result-title">' + escapeHtml(item.title) + '</div>';
                        html += '<div class="search-result-subtitle">' + escapeHtml(item.subtitle) + '</div>';
                        html += '</div>';
                        html += '<span class="search-result-category-badge" style="background:' + item.color + '15;color:' + item.color + '">' + cat + '</span>';
                        html += '</a>';
                    });
                });

                searchResults.innerHTML = html;
                searchResults.classList.add('show');
            }

            function closeSearch() {
                searchResults.classList.remove('show');
                activeIndex = -1;
            }

            function escapeHtml(text) {
                var div = document.createElement('div');
                div.textContent = text || '';
                return div.innerHTML;
            }

            // Close when clicking outside
            document.addEventListener('click', function (e) {
                var globalSearch = document.getElementById('globalSearch');
                if (!globalSearch.contains(e.target)) {
                    closeSearch();
                }
            });
        })();
    </script>
    @yield('scripts')
</body>

</html>