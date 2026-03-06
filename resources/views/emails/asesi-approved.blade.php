<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Disetujui</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f7fa;font-family:'Segoe UI',Roboto,Arial,sans-serif;">
    <div style="max-width:560px;margin:40px auto;background:#ffffff;border-radius:10px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);">
        <!-- Header -->
        <div style="background:#16a34a;padding:24px 32px;text-align:center;">
            <h1 style="color:#ffffff;font-size:18px;margin:0 0 4px;font-weight:700;">LSP SMKN 1 Ciamis</h1>
            <p style="color:rgba(255,255,255,0.85);font-size:12px;margin:0;">Lembaga Sertifikasi Profesi</p>
        </div>

        <!-- Body -->
        <div style="padding:32px;">
            <p style="color:#374151;font-size:15px;line-height:1.7;margin:0 0 16px;">
                Yth. <strong>{{ $asesi->nama }}</strong>,
            </p>

            <p style="color:#374151;font-size:15px;line-height:1.7;margin:0 0 16px;">
                Kami informasikan bahwa pendaftaran Anda sebagai asesi di <strong>LSP SMKN 1 Ciamis</strong> telah diverifikasi oleh admin.
            </p>

            <!-- Status Badge -->
            <div style="background:#f0fdf4;border:1px solid #86efac;border-radius:8px;padding:16px 20px;margin:0 0 20px;text-align:center;">
                <span style="display:inline-block;background:#16a34a;color:#ffffff;font-size:14px;font-weight:700;padding:8px 24px;border-radius:6px;letter-spacing:0.5px;">
                    &#10003;&nbsp; DITERIMA
                </span>
                <p style="color:#166534;font-size:13px;margin:12px 0 0;">
                    Pendaftaran Anda telah <strong>disetujui</strong>. Silakan login ke sistem LSP untuk melanjutkan proses berikutnya.
                </p>
            </div>

            <p style="color:#374151;font-size:14px;line-height:1.7;margin:0 0 8px;">
                Jika ada pertanyaan, silakan hubungi pihak LSP secara langsung.
            </p>

            <p style="color:#374151;font-size:14px;line-height:1.7;margin:0;">
                Terima kasih.
            </p>
        </div>

        <!-- Footer -->
        <div style="background:#f9fafb;padding:16px 32px;border-top:1px solid #e5e7eb;text-align:center;">
            <p style="color:#9ca3af;font-size:12px;margin:0 0 2px;">LSP SMKN 1 Ciamis</p>
            <p style="color:#d1d5db;font-size:11px;margin:0;">Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
