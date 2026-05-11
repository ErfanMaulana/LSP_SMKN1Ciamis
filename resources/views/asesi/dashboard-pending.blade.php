<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Asesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: radial-gradient(circle at top, #eef6ff 0%, #f8fafc 45%, #eef2f7 100%);
            display: flex;
            flex-direction: column;
            color: #1e293b;
        }

        .page-shell {
            flex: 1;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }

        .waiting-shell {
            position: relative;
            width: min(760px, calc(100vw - 32px));
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(148, 163, 184, 0.25);
            border-radius: 24px;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.12);
            padding: 44px 32px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .card-menu {
            position: absolute;
            top: 18px;
            right: 18px;
            z-index: 2;
        }

        .menu-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border: none;
            background: transparent;
            color: #64748b;
            border-radius: 999px;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, opacity 0.2s ease;
            opacity: 0.7;
        }

        .menu-toggle:hover,
        .card-menu:hover .menu-toggle,
        .card-menu:focus-within .menu-toggle {
            background: rgba(148, 163, 184, 0.12);
            color: #0f172a;
            opacity: 1;
        }

        .menu-dropdown {
            position: absolute;
            top: 44px;
            right: 0;
            min-width: 160px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            padding: 6px;
            display: none;
        }

        .card-menu:hover .menu-dropdown,
        .card-menu:focus-within .menu-dropdown {
            display: block;
        }

        .menu-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 8px;
            border: none;
            background: transparent;
            color: #0f172a;
            font-size: 14px;
            font-weight: 600;
            padding: 10px 12px;
            border-radius: 10px;
            text-align: left;
            cursor: pointer;
            text-decoration: none;
        }

        .menu-item:hover {
            background: #f8fafc;
        }

        .waiting-icon {
            width: 92px;
            height: 92px;
            border-radius: 50%;
            margin: 0 auto 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
            font-size: 38px;
            box-shadow: 0 18px 30px rgba(245, 158, 11, 0.18);
        }

        h1 {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 12px;
        }

        p {
            font-size: 16px;
            line-height: 1.75;
            color: #475569;
            margin-bottom: 0;
        }

        .waiting-note {
            margin-top: 22px;
            padding: 14px 18px;
            border-radius: 14px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #0c4a6e;
            text-align: left;
            display: inline-flex;
            gap: 10px;
            align-items: flex-start;
        }

        .waiting-note i {
            font-size: 18px;
            margin-top: 2px;
        }

        @media (max-width: 640px) {
            .waiting-shell {
                padding: 32px 20px;
            }

            h1 {
                font-size: 24px;
            }

            p {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <main class="page-shell">
        <div class="waiting-shell">
            <div class="card-menu">
                <button type="button" class="menu-toggle" aria-label="Menu akun">
                    <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="menu-dropdown">
                    <form method="POST" action="{{ route('asesi.logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="menu-item">
                            <i class="bi bi-box-arrow-right"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            <div class="waiting-icon">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <h1>Menunggu Verifikasi Admin</h1>
            <p>
                Formulir pendaftaran Anda sedang ditinjau oleh admin.
                Silakan menunggu sampai verifikasi selesai.
            </p>
        </div>
    </main>
</body>
</html>
