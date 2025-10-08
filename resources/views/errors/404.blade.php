<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-gray-200 flex items-center justify-center p-4">

    <div class="text-center max-w-md">
        <!-- Judul -->
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            404 - Halaman Tidak Ditemukan
        </h1>

        <!-- Pesan umum -->
        @auth
            <p class="text-gray-700 mb-4">
                Hai <strong>{{ auth()->user()->name }}</strong>, halaman yang Anda cari tidak ditemukan.
            </p>
            <p class="text-gray-600 mb-6">
                Anda bisa <a href="/" class="font-bold hover:underline">kembali ke halaman beranda</a>.
            </p>
        @else
            <p class="text-gray-700 mb-4">
                Halaman yang Anda cari tidak ditemukan.
            </p>
            <p class="text-gray-600 mb-6">
                Anda bisa <a href="/" class="font-bold hover:underline">kembali ke halaman beranda</a>.
            </p>
        @endauth
    </div>

    <!-- Countdown tetap ditampilkan tapi tidak mengarahkan -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let countdown = 3;
            const el = document.getElementById('countdown');

            if (el) {
                const interval = setInterval(() => {
                    countdown--;
                    el.textContent = countdown;
                    if (countdown <= 0) {
                        clearInterval(interval);
                    }
                }, 1000);
            }
        });
    </script>
</body>
</html>
