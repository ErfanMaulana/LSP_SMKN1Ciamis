<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Asesi LSP SMKN 1 Ciamis</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            max-width: 100%;
        }

        html {
            overflow-x: hidden;
            max-width: 100%;
        }

        .asesi-wrapper {
            display: flex;
            min-height: 100vh;
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            color: #475569;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.04);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            z-index: 950;
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #eff6ff;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #bfdbfe;
            border-radius: 5px;
        }

        .sidebar-header {
            padding: 22px 20px;
            border-bottom: 1px solid #f1f5f9;
            text-align: center;
        }

        .sidebar-header .user-icon {
            width: 60px;
            height: 60px;
            background: #0073bd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 24px;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(0,115,189,0.3);
        }

        .sidebar-header h4 {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .sidebar-header p {
            font-size: 11px;
            color: #0073bd;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .sidebar-header .noreg {
            font-size: 12px;
            color: #64748b;
            margin-top: 6px;
            font-family: monospace;
        }

        .sidebar-menu {
            padding: 16px 0;
        }

        .menu-section-title {
            padding: 8px 20px;
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 15px;
        }

        .menu-section-title:first-child {
            margin-top: 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 11px 20px;
            color: #475569;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background: rgba(0,115,189,0.07);
            color: #0073bd;
        }

        .menu-item.active {
            background: rgba(0,115,189,0.10);
            color: #0073bd;
            border-left: 3px solid #0073bd;
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

        .menu-item .badge-menu {
            margin-left: auto;
            font-size: 10px;
            padding: 2px 8px;
            background: #0073bd;
            color: #fff;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            transition: all 0.3s ease;
            min-width: 0;
        }

        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar h1 {
            font-size: 22px;
            color: #1e293b;
            font-weight: 700;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #0073bd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 2px 8px rgba(0,115,189,0.35);
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
            gap: 12px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .profile-toggle:hover {
            background: rgba(0, 115, 189, 0.1);
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

        .profile-dropdown.open .profile-menu,
        .profile-dropdown .profile-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .profile-menu-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .profile-avatar-lg {
            width: 48px;
            height: 48px;
            background: #0073bd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 115, 189, 0.3);
        }

        .profile-menu-header-info {
            display: flex;
            flex-direction: column;
        }

        .profile-menu-name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        .profile-menu-role {
            font-size: 12px;
            color: #64748b;
            margin: 2px 0 0 0;
        }

        .profile-menu-body {
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
            color: #1e293b;
        }

        .profile-menu-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            color: #94a3b8;
        }

        .profile-menu-item:hover i {
            color: #0073bd;
        }

        .profile-menu-divider {
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

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .user-role {
            font-size: 12px;
            color: #64748b;
        }



        .content-wrapper {
            padding: 30px;
            width: 100%;
            max-width: 100%;
        }

        .content-wrapper > * {
            max-width: 100%;
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
            background-color: #dbeafe;
            color: #0c4a6e;
            border: 1px solid #bfdbfe;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Mobile Toggle Button */
        .mobile-toggle {
            display: none;
            background: #0073bd;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
        }

        /* Bottom Navigation (Mobile) */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.06);
            z-index: 999;
            padding: 0;
            margin: 0;
        }

        .bottom-nav-menu {
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 70px;
            gap: 0;
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            height: 100%;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s ease;
            gap: 4px;
            border: none;
            background: none;
            cursor: pointer;
            padding: 8px 12px;
            font-size: 12px;
            text-align: center;
            min-width: 60px;
        }

        .bottom-nav-item:hover {
            color: #0073bd;
            background: rgba(0,115,189,0.05);
        }

        .bottom-nav-item.active {
            color: #0073bd;
            font-weight: 600;
        }

        .bottom-nav-item i {
            font-size: 22px;
            line-height: 1;
        }

        .bottom-nav-item span {
            font-size: 11px;
            font-weight: 500;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: 12px 0 28px rgba(15, 23, 42, 0.14);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: block;
            }

            .main-content {
                margin-left: 0;
                padding-bottom: 70px;
            }

            .bottom-nav {
                display: block;
            }

            .topbar {
                padding: 12px 14px;
                gap: 8px;
                min-width: 0;
            }

            .topbar > div:first-child {
                min-width: 0;
                flex: 1;
            }

            .mobile-toggle {
                display: block;
            }

            .topbar h1 {
                font-size: 16px;
                line-height: 1.25;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .topbar-right {
                gap: 8px;
                flex-shrink: 0;
            }

            .profile-toggle {
                padding: 6px 8px;
                gap: 8px;
            }

            .user-avatar {
                width: 36px;
                height: 36px;
                font-size: 13px;
            }

            .profile-menu {
                right: -4px;
                min-width: 230px;
                max-width: calc(100vw - 20px);
            }

            .content-wrapper {
                padding: 14px 12px;
            }

            .user-details {
                display: none;
            }
        }

        @media (max-width: 360px) {
            .topbar {
                padding: 10px 10px;
            }

            .mobile-toggle {
                padding: 7px 10px;
                font-size: 16px;
            }

            .topbar h1 {
                font-size: 15px;
            }

            .content-wrapper {
                padding: 12px 10px;
            }

            .profile-toggle {
                padding: 4px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="asesi-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/lsp.png') }}" alt="Logo LSP" style="width: 54px; height: 54px; object-fit: contain;">
            </div>

            @php
                $isApproved = $asesi && $asesi->status === 'approved';
                $isPending  = $asesi && $asesi->status === 'pending';
                $isRejected = $asesi && $asesi->status === 'rejected';
                $isBanned   = $asesi && $asesi->status === 'banned';
            @endphp

            {{-- Status banner for non-approved users --}}
            @if(!$isApproved)
                <div style="margin: 12px 14px 4px; border-radius: 10px; padding: 12px 14px; font-size: 12.5px; line-height: 1.5;
                    {{ $isPending ? 'background:#fef9c3;border:1px solid #fde047;color:#854d0e;' : ($isRejected ? 'background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;' : ($isBanned ? 'background:#1e293b;border:1px solid #334155;color:#e2e8f0;' : 'background:#eff6ff;border:1px solid #93c5fd;color:#0073bd;')) }}">
                    @if($isPending)
                        <i class="bi bi-hourglass-split" style="margin-right:5px;"></i>
                        <strong>Menunggu Verifikasi</strong><br>
                        Formulir Anda sedang ditinjau oleh admin.
                    @elseif($isRejected)
                        <i class="bi bi-x-circle" style="margin-right:5px;"></i>
                        <strong>Pendaftaran Ditolak</strong><br>
                        Silakan perbaiki dan kirim ulang formulir.
                    @elseif($isBanned)
                        <i class="bi bi-slash-circle" style="margin-right:5px;"></i>
                        <strong>Ditolak Permanen</strong><br>
                        Akun Anda diblokir. Hubungi pihak LSP.
                    @else
                        <i class="bi bi-info-circle" style="margin-right:5px;"></i>
                        <strong>Belum Mendaftar</strong><br>
                        Lengkapi formulir pendaftaran untuk mengakses fitur lainnya.
                    @endif
                </div>
            @endif

            <nav class="sidebar-menu">
                @if($isApproved)
                    <!-- Dashboard -->
                    <a href="{{ route('asesi.dashboard') }}" class="menu-item {{ request()->routeIs('asesi.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                @endif

                <!-- PENDAFTARAN (hidden for banned & approved users) -->
                @if(!$isBanned && !$isApproved)
                <a href="{{ route('asesi.pendaftaran.formulir') }}" class="menu-item {{ request()->routeIs('asesi.pendaftaran.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-plus"></i>
                    <span>Pendaftaran</span>
                </a>
                @endif

                @if($isApproved)
                    <!-- ASESMEN Section -->
                    <div class="menu-section-title">ASESMEN</div>

                    <a href="{{ route('asesi.asesmen-mandiri.index') }}" class="menu-item {{ request()->routeIs('asesi.asesmen-mandiri.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Asesmen Mandiri</span>
                    </a>

                    <a href="{{ route('asesi.jadwal.index') }}" class="menu-item {{ request()->routeIs('asesi.jadwal.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event-fill"></i>
                        <span>Jadwal Ujikom</span>
                    </a>

                    <a href="{{ route('asesi.hasil-ujikom.index') }}" class="menu-item {{ request()->routeIs('asesi.hasil-ujikom.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Hasil Asesmen</span>
                    </a>

                    <!-- AKUN Section -->
                    <div class="menu-section-title">AKUN</div>

                    <a href="{{ route('asesi.profil.edit') }}" class="menu-item {{ request()->routeIs('asesi.profil.*') && request('tab') !== 'password' ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i>
                        <span>Profil Saya</span>
                    </a>
                @endif
            </nav>
        </aside>
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

        <!-- Main Content -->
        <main class="main-content">
            <div class="topbar">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <button class="mobile-toggle" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1>@yield('page-title', 'Dashboard')</h1>
                </div>
                
                <div class="topbar-right">
                    <div class="profile-dropdown" id="asesiProfileDropdown">
                        <button class="profile-toggle" onclick="toggleAsesiProfile(event)" type="button">
                            <div class="user-avatar">
                                {{ strtoupper(substr($asesi->nama ?? ($account->nama ?? 'A'), 0, 1)) }}
                            </div>
                            <div class="user-details">
                                <span class="user-name">{{ $asesi->nama ?? ($account->nama ?? 'Asesi') }}</span>
                                <span class="user-role">Asesi</span>
                            </div>
                            <i class="bi bi-chevron-down" style="font-size: 16px; color: #64748b;"></i>
                        </button>

                        <div class="profile-menu" id="asesiProfileMenu">
                            <div class="profile-menu-header">
                                <div class="profile-avatar-lg">
                                    {{ strtoupper(substr($asesi->nama ?? ($account->nama ?? 'A'), 0, 1)) }}
                                </div>
                                <div class="profile-menu-header-info">
                                    <h4 class="profile-menu-name">{{ $asesi->nama ?? ($account->nama ?? 'Asesi') }}</h4>
                                    <p class="profile-menu-role">Asesi LSP</p>
                                </div>
                            </div>

                            <div class="profile-menu-body">
                                @if($isApproved)
                                    <a href="{{ route('asesi.profil.edit') }}" class="profile-menu-item">
                                        <i class="bi bi-person"></i>
                                        <span>Profil</span>
                                    </a>
                                    <a href="#" class="profile-menu-item" onclick="event.preventDefault();">
                                        <i class="bi bi-gear"></i>
                                        <span>Pengaturan</span>
                                    </a>
                                    <div class="profile-menu-divider"></div>
                                @endif
                                <form method="POST" action="{{ route('asesi.logout') }}" style="width: 100%; margin: 0;">
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

                @if(session('warning'))
                    <div class="alert" style="background:#fef9c3;color:#854d0e;border:1px solid #fde047;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span>{{ session('warning') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Bottom Navigation (Mobile Only) -->
        <nav class="bottom-nav" id="bottomNav">
            <div class="bottom-nav-menu">
                @if($isApproved)
                    <a href="{{ route('asesi.dashboard') }}" class="bottom-nav-item {{ request()->routeIs('asesi.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('asesi.asesmen-mandiri.index') }}" class="bottom-nav-item {{ request()->routeIs('asesi.asesmen-mandiri.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Asesmen</span>
                    </a>

                    <a href="{{ route('asesi.jadwal.index') }}" class="bottom-nav-item {{ request()->routeIs('asesi.jadwal.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event-fill"></i>
                        <span>Jadwal</span>
                    </a>

                    <a href="{{ route('asesi.profil.edit') }}" class="bottom-nav-item {{ request()->routeIs('asesi.profil.*') ? 'active' : '' }}">
                        <i class="bi bi-person-circle"></i>
                        <span>Profil</span>
                    </a>
                @else
                    <a href="{{ route('asesi.pendaftaran.formulir') }}" class="bottom-nav-item {{ request()->routeIs('asesi.pendaftaran.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-plus"></i>
                        <span>Daftar</span>
                    </a>

                    <button class="bottom-nav-item" onclick="toggleAsesiProfile(event)">
                        <i class="bi bi-person-circle"></i>
                        <span>Profil</span>
                    </button>
                @endif
            </div>
        </nav>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const isOpen = sidebar.classList.toggle('active');
            if (overlay) {
                overlay.classList.toggle('active', isOpen);
            }
            document.body.style.overflow = isOpen ? 'hidden' : '';
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar) sidebar.classList.remove('active');
            if (overlay) overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        function toggleAsesiProfile(e) {
            e.stopPropagation();
            const menu = document.getElementById('asesiProfileMenu');
            if (menu) menu.classList.toggle('show');
        }

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.getElementById('asesiProfileDropdown');
            const profileMenu = document.getElementById('asesiProfileMenu');
            if (profileDropdown && !profileDropdown.contains(event.target)) {
                if (profileMenu) profileMenu.classList.remove('show');
            }

            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && toggle && !toggle.contains(event.target)) {
                    closeSidebar();
                }
            }
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeSidebar();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSidebar();
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
