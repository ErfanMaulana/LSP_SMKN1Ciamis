<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background-color:#f5f7fa;font-family:'Segoe UI',Roboto,Arial,sans-serif;">
    <div style="max-width:600px;margin:30px auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <!-- Header -->
        <div style="background:{{ $asesi->status === 'banned' ? '#1e293b' : '#dc2626' }};padding:24px 32px;text-align:center;">
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

            @if($asesi->status === 'banned')
            <!-- Banned Badge -->
            <div style="background:#f1f5f9;border:1px solid #334155;border-radius:8px;padding:16px 20px;margin:0 0 20px;text-align:center;">
                <span style="display:inline-block;background:#1e293b;color:#f8fafc;font-size:14px;font-weight:700;padding:8px 24px;border-radius:6px;letter-spacing:0.5px;">
                    &#9940;&nbsp; DITOLAK PERMANEN
                </span>
                <p style="color:#475569;font-size:13px;margin:12px 0 0;">
                    Akun Anda telah <strong>diblokir secara permanen</strong>. Anda tidak dapat melakukan pendaftaran ulang.
                </p>
            </div>
            @else
            <!-- Rejected Badge -->
            <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:16px 20px;margin:0 0 20px;text-align:center;">
                <span style="display:inline-block;background:#dc2626;color:#ffffff;font-size:14px;font-weight:700;padding:8px 24px;border-radius:6px;letter-spacing:0.5px;">
                    &#10007;&nbsp; DITOLAK
                </span>
                <p style="color:#991b1b;font-size:13px;margin:12px 0 0;">
                    Pendaftaran Anda <strong>tidak dapat disetujui</strong> pada saat ini.
                </p>
            </div>
            @endif

            @if($asesi->catatan_admin)
            <!-- Reason -->
            <p style="color:#374151;font-size:14px;line-height:1.7;margin:0 0 8px;"><strong>Catatan dari admin:</strong></p>
            <div style="background:#f9fafb;border-left:3px solid {{ $asesi->status === 'banned' ? '#1e293b' : '#dc2626' }};padding:12px 16px;border-radius:0 6px 6px 0;margin:0 0 20px;">
                <p style="color:#6b7280;font-size:14px;margin:0;line-height:1.6;">{{ $asesi->catatan_admin }}</p>
            </div>
            @endif

            @if($asesi->status === 'banned')
            <p style="color:#374151;font-size:14px;line-height:1.7;margin:0 0 8px;">
                Jika Anda merasa ini adalah kesalahan, silakan hubungi pihak LSP secara langsung.
            </p>
            @else
            <p style="color:#374151;font-size:14px;line-height:1.7;margin:0 0 8px;">
                Anda dapat melakukan pendaftaran ulang dengan melengkapi data dan dokumen yang diperlukan.
            </p>
            @endif

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
