<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Admin LSP SMKN 1 Ciamis</title>
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
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: #0F172A;
            color: #ecf0f1;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #1e293b;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 5px;
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header .admin-icon {
            width: 50px;
            height: 50px;
            background: #0061A5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 24px;
        }

        .sidebar-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-header p {
            font-size: 11px;
            color: #95a5a6;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            padding: 20px 0;
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
            font-weight: 600;
            color: #95a5a6;
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
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
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
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .menu-item:hover {
            background: rgba(0, 97, 165, 0.1);
            color: #fff;
        }

        .menu-item.active {
            background: rgba(0, 97, 165, 0.15);
            color: #fff;
            border-left: 3px solid #0061A5;
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

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            transition: all 0.3s ease;
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
            font-size: 24px;
            color: #0F172A;
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
            background: #0061A5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 16px;
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
            gap: 12px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .profile-toggle:hover {
            background: #f5f7fa;
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
            color: white;
            font-weight: 600;
            font-size: 18px;
            flex-shrink: 0;
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
            background: #0F172A;
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
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="admin-icon">
                    <i class="bi bi-person-circle"></i>
                </div>
                <h4>Admin LSP</h4>
                <p>{{ Auth::guard('admin')->user()->name }}</p>
            </div>

            <nav class="sidebar-menu">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

                <!-- WEBSITE Section -->
                <div class="menu-section">
                    <div class="menu-section-title" onclick="toggleMenuSection(this)">
                        <span>WEBSITE</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="menu-section-items">
                        <a href="{{ route('admin.carousel.index') }}" class="menu-item {{ request()->routeIs('admin.carousel.*') ? 'active' : '' }}">
                            <i class="bi bi-images"></i>
                            <span>Banner Carousel</span>
                        </a>

                        <a href="{{ route('admin.socialmedia.index') }}" class="menu-item {{ request()->routeIs('admin.socialmedia.*') ? 'active' : '' }}">
                            <i class="bi bi-share"></i>
                            <span>Sosial Media</span>
                        </a>

                        <a href="{{ route('admin.profile-content.index') }}" class="menu-item {{ request()->routeIs('admin.profile-content.*') ? 'active' : '' }}">
                            <i class="bi bi-book-fill"></i>
                            <span>Konten Profil</span>
                        </a>
                    </div>
                </div>

                <!-- ADMINISTRASI Section -->
                <div class="menu-section">
                    <div class="menu-section-title" onclick="toggleMenuSection(this)">
                        <span>ADMINISTRASI</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="menu-section-items">
                        <a href="{{ route('admin.asesor.index') }}" class="menu-item {{ request()->routeIs('admin.asesor.*') ? 'active' : '' }}">
                            <i class="bi bi-person-badge"></i>
                            <span>Asesor</span>
                        </a>
                        
                        <a href="{{ route('admin.asesi.index') }}" class="menu-item {{ request()->routeIs('admin.asesi.index', 'admin.asesi.create', 'admin.asesi.edit') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            <span>Asesi</span>
                        </a>

                        <a href="{{ route('admin.asesi.verifikasi') }}" class="menu-item {{ request()->routeIs('admin.asesi.verifikasi*') ? 'active' : '' }}">
                            <i class="bi bi-clipboard-check"></i>
                            <span>Verifikasi Asesi</span>
                            @php $pendingCount = \App\Models\Asesi::where('status', 'pending')->count(); @endphp
                            @if($pendingCount > 0)
                                <span style="margin-left:auto;font-size:10px;padding:2px 8px;background:#ef4444;color:#fff;border-radius:10px;font-weight:600;">{{ $pendingCount }}</span>
                            @endif
                        </a>

                        <a href="{{ route('admin.akun-asesi.index') }}" class="menu-item {{ request()->routeIs('admin.akun-asesi.*') ? 'active' : '' }}">
                            <i class="bi bi-person-vcard"></i>
                            <span>Akun Asesi (NIK)</span>
                        </a>
                        
                        <a href="{{ route('admin.jurusan.index') }}" class="menu-item {{ request()->routeIs('admin.jurusan.*') ? 'active' : '' }}">
                            <i class="bi bi-mortarboard"></i>
                            <span>Jurusan</span>
                        </a>

                        <a href="{{ route('admin.skema.index') }}" class="menu-item {{ request()->routeIs('admin.skema.*') ? 'active' : '' }}">
                            <i class="bi bi-patch-check"></i>
                            <span>Skema</span>
                        </a>

                        <a href="{{ route('admin.mitra.index') }}" class="menu-item {{ request()->routeIs('admin.mitra.*') ? 'active' : '' }}">
                            <i class="bi bi-building"></i>
                            <span>Mitra</span>
                        </a>
                    </div>
                </div>

                <!-- UJIAN KOMPETENSI Section -->
                <div class="menu-section">
                    <div class="menu-section-title" onclick="toggleMenuSection(this)">
                        <span>UJIAN KOMPETENSI</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="menu-section-items">
                        <a href="{{ route('admin.tuk.index') }}" class="menu-item {{ request()->routeIs('admin.tuk.*') ? 'active' : '' }}">
                            <i class="bi bi-geo-alt"></i>
                            <span>Tempat Uji (TUK)</span>
                        </a>

                        <a href="{{ route('admin.jadwal-ujikom.index') }}" class="menu-item {{ request()->routeIs('admin.jadwal-ujikom.*') ? 'active' : '' }}">
                            <i class="bi bi-calendar-event"></i>
                            <span>Jadwal Ujikom</span>
                        </a>
                    </div>
                </div>

                <!-- ASESOR Section -->
                <div class="menu-section">
                    <div class="menu-section-title" onclick="toggleMenuSection(this)">
                        <span>ASESOR</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="menu-section-items">
                        <a href="#" class="menu-item" style="opacity:0.5;pointer-events:none;">
                            <i class="bi bi-pencil-square"></i>
                            <span>Entry Penilaian</span>
                        </a>
                        
                        <a href="#" class="menu-item" style="opacity:0.5;pointer-events:none;">
                            <i class="bi bi-file-earmark-text"></i>
                            <span>Hasil Ujian</span>
                        </a>
                        
                        <a href="#" class="menu-item" style="opacity:0.5;pointer-events:none;">
                            <i class="bi bi-bar-chart"></i>
                            <span>Rekap Nilai Akhir</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

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
                    <div class="profile-dropdown" id="profileDropdown">
                        <button class="profile-toggle" onclick="toggleProfileMenu(event)">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                            </div>
                            <div class="user-details">
                                <span class="user-name">{{ Auth::guard('admin')->user()->name }}</span>
                                <span class="user-role">Admin LSP</span>
                            </div>
                            <i class="bi bi-chevron-down" style="font-size: 16px; color: #64748b;"></i>
                        </button>

                        <div class="profile-menu" id="profileMenu">
                            <div class="profile-header">
                                <div class="profile-avatar-lg">
                                    {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                                </div>
                                <div class="profile-header-info">
                                    <h4 class="profile-header-name">{{ Auth::guard('admin')->user()->name }}</h4>
                                    <p class="profile-header-role">Administrator</p>
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
                                <form method="POST" action="{{ route('admin.logout') }}" style="width: 100%; margin: 0;">
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
                        <span>{{ session('success') }}</span>
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

        // Restore menu states on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.menu-section-title').forEach(function(title) {
                const sectionName = title.querySelector('span').textContent;
                const state = localStorage.getItem('menu-' + sectionName);
                
                // By default, expand sections if no state is saved
                if (state === 'collapsed') {
                    title.classList.add('collapsed');
                    title.nextElementSibling.classList.add('collapsed');
                }
            });
        });

        // Close profile menu when clicking outside
        document.addEventListener('click', function(event) {
            const profileDropdown = document.getElementById('profileDropdown');
            const profileMenu = document.getElementById('profileMenu');
            
            if (profileDropdown && !profileDropdown.contains(event.target)) {
                profileMenu.classList.remove('show');
            }
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
