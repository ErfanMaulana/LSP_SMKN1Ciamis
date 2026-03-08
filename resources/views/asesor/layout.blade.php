<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Asesor LSP SMKN 1 Ciamis</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f1f5f9;
            overflow-x: hidden;
        }

        .asesor-wrapper { display: flex; min-height: 100vh; }

        /* ── Sidebar ─────────────────────────────────────── */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e3a5f 0%, #1a3050 100%);
            color: #e2e8f0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: #1a3050; }
        .sidebar::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 4px; }

        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-header .user-icon {
            width: 64px;
            height: 64px;
            background: #0073bd;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 30px;
            color: white;
            box-shadow: 0 4px 14px rgba(37,99,235,0.4);
        }

        .sidebar-header h4 { font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 3px; }
        .sidebar-header .role-badge {
            display: inline-block;
            background: rgba(59,130,246,0.3);
            color: #93c5fd;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 3px 10px;
            border-radius: 20px;
            margin-bottom: 6px;
        }
        .sidebar-header .noreg { font-size: 11px; color: #93c5fd; font-family: monospace; }

        .sidebar-menu { padding: 16px 0; }

        .menu-section-title {
            padding: 10px 20px 4px;
            font-size: 10px;
            font-weight: 700;
            color: #60a5fa;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 11px 20px;
            color: #bfdbfe;
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .menu-item:hover { background: rgba(59,130,246,0.15); color: #fff; }
        .menu-item.active { background: rgba(59,130,246,0.2); color: #fff; border-left-color: #3b82f6; }
        .menu-item.disabled { opacity: 0.4; pointer-events: none; }

        .menu-item i { font-size: 17px; width: 22px; margin-right: 12px; }
        .menu-item span { font-size: 13.5px; font-weight: 500; }

        .menu-badge {
            margin-left: auto;
            background: #0073bd;
            color: white;
            font-size: 10px;
            padding: 2px 7px;
            border-radius: 10px;
            font-weight: 600;
        }

        /* ── Main ────────────────────────────────────────── */
        .main-content { flex: 1; margin-left: 260px; }

        .topbar {
            background: white;
            padding: 14px 28px;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .topbar h1 { font-size: 20px; color: #1e3a5f; font-weight: 700; }

        .topbar-right { display: flex; align-items: center; gap: 16px; }

        .user-avatar-sm {
            width: 38px; height: 38px;
            background: #0073bd;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 15px;
        }

        .user-details { display: flex; flex-direction: column; }
        .user-name { font-size: 13px; font-weight: 600; color: #1e293b; }
        .user-role { font-size: 11px; color: #64748b; }

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
            background: rgba(59, 130, 246, 0.1);
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

        .profile-header-info {
            display: flex;
            flex-direction: column;
        }

        .profile-header-name {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
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
            color: #1e293b;
        }

        .profile-menu-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
            color: #94a3b8;
        }

        .profile-menu-item:hover i {
            color: #2563eb;
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

        .content-wrapper { padding: 28px; }

        .alert {
            padding: 13px 16px; border-radius: 8px; margin-bottom: 18px;
            font-size: 14px; display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── Mobile ──────────────────────────────────────── */
        .mobile-toggle {
            display: none; background: #1e3a5f; color: white;
            border: none; padding: 9px 14px; border-radius: 6px;
            cursor: pointer; font-size: 19px;
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .mobile-toggle { display: block; }
            .content-wrapper { padding: 16px; }
            .user-details { display: none; }
        }
    </style>
    @yield('styles')
</head>
<body>
<div class="asesor-wrapper">

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="user-icon">
                <i class="bi bi-person-badge-fill"></i>
            </div>
            <h4>{{ $asesor->nama ?? 'Asesor' }}</h4>
            <div class="role-badge">Asesor LSP</div>
            <div class="noreg">{{ $account->id ?? '-' }}</div>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('asesor.dashboard') }}"
               class="menu-item {{ request()->routeIs('asesor.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <div class="menu-section-title">PENILAIAN</div>

            <a href="{{ route('asesor.asesi.index') }}"
               class="menu-item {{ request()->routeIs('asesor.asesi.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Daftar Asesi</span>
            </a>

            <div class="menu-section-title">AKUN</div>

            <a href="#" class="menu-item disabled">
                <i class="bi bi-person-circle"></i>
                <span>Profil Saya</span>
            </a>

            <a href="#" class="menu-item disabled">
                <i class="bi bi-key"></i>
                <span>Ubah Password</span>
            </a>
        </nav>
    </aside>

    {{-- Main --}}
    <main class="main-content">
        <div class="topbar">
            <div style="display:flex;align-items:center;gap:14px;">
                <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('active')">
                    <i class="bi bi-list"></i>
                </button>
                <h1>@yield('page-title', 'Dashboard')</h1>
            </div>

            <div class="topbar-right">
                <div class="profile-dropdown" id="profileDropdown">
                    <button class="profile-toggle" onclick="toggleProfileMenu(event)">
                        <div class="user-avatar-sm">
                            {{ strtoupper(substr($asesor->nama ?? 'A', 0, 1)) }}
                        </div>
                        <div class="user-details">
                            <span class="user-name">{{ $asesor->nama ?? 'Asesor' }}</span>
                            <span class="user-role">Asesor</span>
                        </div>
                        <i class="bi bi-chevron-down" style="font-size: 16px; color: #64748b;"></i>
                    </button>

                    <div class="profile-menu" id="profileMenu">
                        <div class="profile-header">
                            <div class="profile-avatar-lg">
                                {{ strtoupper(substr($asesor->nama ?? 'A', 0, 1)) }}
                            </div>
                            <div class="profile-header-info">
                                <h4 class="profile-header-name">{{ $asesor->nama ?? 'Asesor' }}</h4>
                                <p class="profile-header-role">Asesor LSP</p>
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
                            <form method="POST" action="{{ route('asesor.logout') }}"
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
    function toggleProfileMenu(event) {
        event.stopPropagation();
        const menu = document.getElementById('profileMenu');
        menu.classList.toggle('show');
    }

    // Close profile menu when clicking outside
    document.addEventListener('click', function (event) {
        const profileDropdown = document.getElementById('profileDropdown');
        const profileMenu = document.getElementById('profileMenu');

        if (profileDropdown && !profileDropdown.contains(event.target)) {
            if (profileMenu) profileMenu.classList.remove('show');
        }
    });
</script>

@yield('scripts')
</body>
</html>
