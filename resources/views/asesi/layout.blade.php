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
        }

        .asesi-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: #14532d;
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
            background: #166534;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #22c55e;
            border-radius: 5px;
        }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }

        .sidebar-header .user-icon {
            width: 60px;
            height: 60px;
            background: #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 28px;
        }

        .sidebar-header h4 {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
        }

        .sidebar-header p {
            font-size: 11px;
            color: #86efac;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-header .noreg {
            font-size: 12px;
            color: #bbf7d0;
            margin-top: 6px;
            font-family: monospace;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-section-title {
            padding: 8px 20px;
            font-size: 11px;
            font-weight: 600;
            color: #86efac;
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
            color: #bbf7d0;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .menu-item:hover {
            background: rgba(34, 197, 94, 0.2);
            color: #fff;
        }

        .menu-item.active {
            background: rgba(34, 197, 94, 0.25);
            color: #fff;
            border-left: 3px solid #22c55e;
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
            background: #22c55e;
            color: #fff;
            border-radius: 10px;
            font-weight: 600;
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
            font-size: 22px;
            color: #14532d;
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
            background: #16a34a;
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
            color: #1e293b;
        }

        .user-role {
            font-size: 12px;
            color: #64748b;
        }

        .btn-logout {
            background: #dc2626;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-logout:hover {
            background: #b91c1c;
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
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Mobile Toggle Button */
        .mobile-toggle {
            display: none;
            background: #14532d;
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

            .user-details {
                display: none;
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
                <div class="user-icon">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h4>{{ $asesi->nama ?? 'Asesi' }}</h4>
                <p>Asesi LSP</p>
                <div class="noreg">{{ $account->no_reg ?? '-' }}</div>
            </div>

            <nav class="sidebar-menu">
                <!-- Dashboard -->
                <a href="{{ route('asesi.dashboard') }}" class="menu-item {{ request()->routeIs('asesi.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>

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

                <a href="#" class="menu-item" style="opacity:0.5;pointer-events:none;">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Hasil Asesmen</span>
                </a>

                <!-- PROFIL Section -->
                <div class="menu-section-title">AKUN</div>
                
                <a href="{{ route('asesi.profil.edit') }}" class="menu-item {{ request()->routeIs('asesi.profil.*') && request('tab') !== 'password' ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i>
                    <span>Profil Saya</span>
                </a>
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
                            {{ strtoupper(substr($asesi->nama ?? 'A', 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <span class="user-name">{{ $asesi->nama ?? 'Asesi' }}</span>
                            <span class="user-role">Asesi</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('asesi.logout') }}" style="display: inline;">
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
