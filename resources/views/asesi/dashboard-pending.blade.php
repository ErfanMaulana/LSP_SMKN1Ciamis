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
            align-items: center;
            justify-content: center;
            color: #1e293b;
        }

        .waiting-shell {
            width: min(760px, calc(100vw - 32px));
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(148, 163, 184, 0.25);
            border-radius: 24px;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.12);
            padding: 44px 32px;
            text-align: center;
            backdrop-filter: blur(10px);
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
    <main class="waiting-shell">
        <div class="waiting-icon">
            <i class="bi bi-hourglass-split"></i>
        </div>
        <h1>Menunggu Verifikasi Admin</h1>
        <p>
            Formulir pendaftaran Anda sedang ditinjau oleh admin.
            Silakan menunggu sampai verifikasi selesai.
        </p>
    </main>
</body>
</html>
