<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kode Verifikasi</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 650px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f0f2f5;
        }
        .container {
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }
        .header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 35px 30px;
        }
        .content h2 {
            margin-top: 0;
            font-size: 22px;
            color: #111827;
        }
        .verification-code {
            background: #f9fafb;
            border: 2px dashed #4f46e5;
            padding: 25px;
            text-align: center;
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            margin: 25px 0;
            border-radius: 10px;
            color: #111827;
        }
        .info-box {
            background: #eff6ff;
            border-left: 5px solid #3b82f6;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 6px;
            font-size: 14px;
        }
        .info-box p {
            margin: 6px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff !important;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            margin-top: 15px;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 12px;
            color: #6b7280;
            padding: 20px;
            background: #f9fafb;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $customDomain }}</h1>
            <p>Verifikasi Email Anda</p>
        </div>

        <!-- Content -->
        <div class="content">
            <h2>Halo 👋</h2>
            <p>Terima kasih telah mendaftar di <strong>{{ $customDomain }}</strong>.
               Gunakan kode berikut untuk melengkapi proses pendaftaran Anda:</p>

            <div class="verification-code">
                {{ $verificationCode }}
            </div>

            <div class="info-box">
                <p><strong>⚠️ Informasi Penting:</strong></p>
                <p>• Kode ini berlaku <strong>30 menit</strong></p>
                <p>• Jangan bagikan kode kepada siapapun</p>
                <p>• Jika Anda tidak merasa melakukan pendaftaran, abaikan email ini</p>
            </div>

            <p style="margin-bottom: 0;">Jika mengalami kendala, hubungi tim support kami.</p>
            <a href="mailto:support@{{ $customDomain }}" class="btn">Hubungi Support</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $customDomain }}. Semua hak dilindungi.</p>
            <p>Email ini dikirim otomatis, mohon jangan dibalas.</p>
        </div>
    </div>
</body>
</html>
