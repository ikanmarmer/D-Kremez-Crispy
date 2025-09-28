<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kode Verifikasi</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .verification-code {
            background: #f8f9fa;
            border: 2px dashed #667eea;
            padding: 20px;
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            margin: 20px 0;
            border-radius: 5px;
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            padding: 20px;
            background: #f8f9fa;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $customDomain }}</h1>
            <p>Verifikasi Alamat Email Anda</p>
        </div>

        <div class="content">
            <h2>Halo!</h2>
            <p>Terima kasih telah mendaftar di <strong>{{ $customDomain }}</strong>. Gunakan kode verifikasi berikut untuk melengkapi proses pendaftaran Anda:</p>

            <div class="verification-code">
                {{ $verificationCode }}
            </div>

            <div class="info-box">
                <p><strong>Informasi Penting:</strong></p>
                <p>• Kode verifikasi ini akan kedaluwarsa dalam 30 menit</p>
                <p>• Jangan bagikan kode ini kepada siapapun</p>
                <p>• Jika Anda tidak melakukan pendaftaran ini, silakan abaikan email ini</p>
            </div>

            <p>Jika Anda mengalami kesulitan, jangan ragu untuk menghubungi tim support kami.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $customDomain }}. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
