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

        .menu-section-title {
            padding: 8px 20px;
            font-size: 11px;
            font-weight: 600;
            color: #95a5a6;
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

        .btn-logout {
            background: #0061A5;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: #003961;
            transform: translateY(-1px);
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
                <div class="menu-section-title">WEBSITE</div>

                <a href="{{ route('admin.carousel.index') }}" class="menu-item {{ request()->routeIs('admin.carousel.*') ? 'active' : '' }}">
                    <i class="bi bi-images"></i>
                    <span>Banner Carousel</span>
                </a>

                <a href="{{ route('admin.socialmedia.index') }}" class="menu-item {{ request()->routeIs('admin.socialmedia.*') ? 'active' : '' }}">
                    <i class="bi bi-share"></i>
                    <span>Sosial Media</span>
                </a>

                <!-- ADMINISTRASI Section -->
                <div class="menu-section-title">ADMINISTRASI</div>
                
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

                <!-- UJIAN Section -->
                <div class="menu-section-title">UJIAN KOMPETENSI</div>

                <a href="{{ route('admin.tuk.index') }}" class="menu-item {{ request()->routeIs('admin.tuk.*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i>
                    <span>Tempat Uji (TUK)</span>
                </a>

                <a href="{{ route('admin.jadwal-ujikom.index') }}" class="menu-item {{ request()->routeIs('admin.jadwal-ujikom.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i>
                    <span>Jadwal Ujikom</span>
                </a>

                <!-- ASESOR Section -->
                <!-- <div class="menu-section-title">ASESOR</div>
                
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
                </a> -->
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
                    <div class="user-info">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::guard('admin')->user()->name, 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <span class="user-name">{{ Auth::guard('admin')->user()->name }}</span>
                            <span class="user-role">Admin LSP</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
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
