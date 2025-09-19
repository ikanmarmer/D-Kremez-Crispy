<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kode Verifikasi</title>
</head>
<body>
    <h2>Halo!</h2>
    <p>Terima kasih sudah mendaftar di <strong>{{ config('app.name') }}</strong>.</p>
    <p>Ini adalah kode verifikasi Anda:</p>
    <h1 style="letter-spacing: 5px;">{{ $code }}</h1>
    <p>Kode ini berlaku selama beberapa menit. Jangan berikan kode ini kepada siapa pun.</p>
    <br>
    <p>Salam,</p>
    <p><strong>{{ config('app.name') }}</strong></p>
</body>
</html>
