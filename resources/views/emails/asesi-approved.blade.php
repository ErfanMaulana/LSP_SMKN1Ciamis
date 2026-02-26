<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background-color:#f5f7fa;font-family:'Segoe UI',Roboto,Arial,sans-serif;">
    <div style="max-width:600px;margin:30px auto;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <!-- Header -->
        <div style="background:linear-gradient(135deg,#0073bd 0%,#004a7a 100%);padding:30px 40px;text-align:center;">
            <h1 style="color:#ffffff;font-size:22px;margin:0 0 6px;">LSP SMKN 1 Ciamis</h1>
            <p style="color:rgba(255,255,255,0.8);font-size:13px;margin:0;">Lembaga Sertifikasi Profesi</p>
        </div>

        <!-- Body -->
        <div style="padding:35px 40px;">
            <!-- Success Icon -->
            <div style="text-align:center;margin-bottom:25px;">
                <div style="display:inline-block;width:70px;height:70px;background:#d1fae5;border-radius:50%;line-height:70px;font-size:32px;">
                    &#10004;
                </div>
            </div>

            <h2 style="color:#1e293b;font-size:20px;text-align:center;margin:0 0 20px;">Pendaftaran Anda Disetujui!</h2>

            <p style="color:#475569;font-size:14px;line-height:1.7;margin:0 0 20px;">
                Halo <strong>{{ $asesi->nama }}</strong>,
            </p>

            <p style="color:#475569;font-size:14px;line-height:1.7;margin:0 0 25px;">
                Selamat! Pendaftaran Anda sebagai asesi di <strong>LSP SMKN 1 Ciamis</strong> telah <strong style="color:#059669;">disetujui</strong> oleh admin.
            </p>

            <!-- Login Credentials Box -->
            <div style="background:#eff6ff;border:2px dashed #0073bd;padding:20px;border-radius:8px;margin:0 0 25px;">
                <h3 style="color:#0073bd;font-size:16px;margin:0 0 12px;text-align:center;">
                    <span style="font-size:20px;">🔑</span> Akun Login Anda
                </h3>
                <p style="color:#475569;font-size:13px;margin:0 0 15px;text-align:center;">
                    Berikut adalah kredensial login Anda untuk mengakses sistem LSP:
                </p>
                <table style="width:100%;border-collapse:collapse;margin-bottom:15px;">
                    <tr>
                        <td style="color:#6b7280;font-size:13px;padding:8px 0;width:140px;">Nomor Registrasi</td>
                        <td style="color:#1e293b;font-size:14px;padding:8px 0;font-weight:700;font-family:monospace;background:#fff;padding-left:12px;border-radius:4px;">{{ $noReg }}</td>
                    </tr>
                    <tr>
                        <td style="color:#6b7280;font-size:13px;padding:8px 0;">Password</td>
                        <td style="color:#1e293b;font-size:14px;padding:8px 0;font-weight:700;font-family:monospace;background:#fff;padding-left:12px;border-radius:4px;">{{ $password }}</td>
                    </tr>
                </table>
                <p style="color:#dc2626;font-size:12px;margin:0;text-align:center;font-style:italic;">
                    ⚠️ Simpan kredensial ini dengan aman. Jangan bagikan kepada siapapun.
                </p>
            </div>

            <!-- Call to Action -->
            <div style="background:linear-gradient(135deg, #22c55e 0%, #16a34a 100%);padding:20px;border-radius:8px;margin:0 0 25px;text-align:center;">
                <h3 style="color:#ffffff;font-size:16px;margin:0 0 10px;">
                    📋 Langkah Selanjutnya
                </h3>
                <p style="color:rgba(255,255,255,0.95);font-size:14px;margin:0 0 15px;line-height:1.6;">
                    Silakan login dan <strong>segera lakukan Asesmen Mandiri</strong> untuk melengkapi proses sertifikasi Anda.
                </p>
                <a href="{{ url('/login') }}" style="display:inline-block;background:#ffffff;color:#16a34a;padding:12px 28px;border-radius:6px;text-decoration:none;font-weight:600;font-size:14px;box-shadow:0 2px 8px rgba(0,0,0,0.15);">
                    Login Sekarang
                </a>
            </div>

            <!-- Info Box -->
            <div style="background:#f0fdf4;border-left:4px solid #22c55e;padding:16px 20px;border-radius:0 8px 8px 0;margin:0 0 25px;">
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
                    <tr>
                        <td style="color:#6b7280;font-size:13px;padding:4px 0;">Status</td>
                        <td style="padding:4px 0;">
                            <span style="background:#dcfce7;color:#166534;font-size:12px;padding:3px 10px;border-radius:20px;font-weight:600;">DISETUJUI</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="color:#6b7280;font-size:13px;padding:4px 0;">Tanggal Verifikasi</td>
                        <td style="color:#1e293b;font-size:13px;padding:4px 0;font-weight:600;">{{ $asesi->verified_at ? $asesi->verified_at->format('d M Y, H:i') : '-' }}</td>
                    </tr>
                </table>
            </div>

            <p style="color:#475569;font-size:14px;line-height:1.7;margin:0 0 20px;">
                <strong>Penting untuk diperhatikan:</strong>
            </p>
            <ul style="color:#475569;font-size:14px;line-height:1.8;margin:0 0 25px;padding-left:20px;">
                <li>Login menggunakan Nomor Registrasi dan Password yang telah diberikan di atas</li>
                <li><strong>Segera lakukan Asesmen Mandiri</strong> setelah login untuk evaluasi kompetensi awal</li>
                <li>Pastikan nomor telepon dan email Anda selalu aktif untuk komunikasi lebih lanjut</li>
                <li>Anda akan dihubungi oleh pihak LSP untuk informasi jadwal asesmen selanjutnya</li>
            </ul>

            <p style="color:#475569;font-size:14px;line-height:1.7;margin:0;">
                Terima kasih atas kepercayaan Anda.
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
