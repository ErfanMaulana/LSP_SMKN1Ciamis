<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background-color:#f5f7fa;font-family:'Segoe UI',Roboto,Arial,sans-serif;">
    <div style="max-width:600px;margin:30px auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <!-- Header -->
        <div style="background:linear-gradient(135deg,#2563eb 0%,#1e40af 100%);padding:30px 40px;text-align:center;">
            <h1 style="color:#ffffff;font-size:22px;margin:0 0 6px;">LSP SMKN 1 Ciamis</h1>
            <p style="color:rgba(255,255,255,0.8);font-size:13px;margin:0;">Lembaga Sertifikasi Profesi</p>
        </div>

        <!-- Body -->
        <div style="padding:35px 40px;">
            <!-- Warning Icon -->
            <div style="text-align:center;margin-bottom:25px;">
                <div style="display:inline-block;width:70px;height:70px;background:#fef2f2;border-radius:50%;line-height:70px;font-size:32px;">
                    &#10060;
                </div>
            </div>

            <h2 style="color:#1e293b;font-size:20px;text-align:center;margin:0 0 20px;">Pendaftaran Ditolak</h2>

            <p style="color:#475569;font-size:14px;line-height:1.7;margin:0 0 20px;">
                Halo <strong>{{ $asesi->nama }}</strong>,
            </p>

            <p style="color:#475569;font-size:14px;line-height:1.7;margin:0 0 20px;">
                Mohon maaf, pendaftaran Anda sebagai asesi di <strong>LSP SMKN 1 Ciamis</strong> <strong style="color:#dc2626;">belum dapat disetujui</strong> dengan alasan berikut:
            </p>

            <!-- Reason Box -->
            <div style="background:#fef2f2;border-left:4px solid #ef4444;padding:16px 20px;border-radius:0 8px 8px 0;margin:0 0 25px;">
                <p style="color:#991b1b;font-size:14px;margin:0;font-weight:500;">
                    {{ $asesi->catatan_admin ?? 'Tidak ada catatan dari admin.' }}
                </p>
            </div>

            <!-- Info Box -->
            <div style="background:#f8fafc;border:1px solid #e2e8f0;padding:16px 20px;border-radius:8px;margin:0 0 25px;">
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td style="color:#6b7280;font-size:13px;padding:4px 0;width:130px;">NIK</td>
                        <td style="color:#1e293b;font-size:13px;padding:4px 0;font-weight:600;">{{ $asesi->NIK }}</td>
                    </tr>
                    <tr>
                        <td style="color:#6b7280;font-size:13px;padding:4px 0;">Nama</td>
                        <td style="color:#1e293b;font-size:13px;padding:4px 0;font-weight:600;">{{ $asesi->nama }}</td>
                    </tr>
                    <tr>
                        <td style="color:#6b7280;font-size:13px;padding:4px 0;">Jurusan</td>
                        <td style="color:#1e293b;font-size:13px;padding:4px 0;font-weight:600;">{{ $asesi->jurusan->nama_jurusan ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <p style="color:#475569;font-size:14px;line-height:1.7;margin:0 0 20px;">
                Anda dapat melakukan pendaftaran ulang dengan melengkapi data dan dokumen yang diperlukan. Jika memiliki pertanyaan, silakan hubungi pihak LSP.
            </p>

            <p style="color:#475569;font-size:14px;line-height:1.7;margin:0;">
                Terima kasih atas perhatiannya.
            </p>
        </div>

        <!-- Footer -->
        <div style="background:#f8fafc;padding:20px 40px;border-top:1px solid #e2e8f0;text-align:center;">
            <p style="color:#94a3b8;font-size:12px;margin:0 0 4px;">LSP SMKN 1 Ciamis</p>
            <p style="color:#cbd5e1;font-size:11px;margin:0;">Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
