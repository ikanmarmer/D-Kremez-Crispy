<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full bg-gray-200 flex items-center justify-center p-4">

    <div class="text-center">
        <!-- Judul -->
        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            403 - Akses Ditolak
        </h1>

        <!-- Pesan umum -->
        @auth
            @if(auth()->user()->role === \App\Enums\Role::Karyawan->value)
                <p class="text-gray-700 mb-4">
                    Halo <strong>{{ auth()->user()->name }}</strong>, akses Anda sebagai
                    <span class="bg-red-600 text-white text-xs px-2 py-1 rounded">
                        {{ auth()->user()->role }}
                    </span> ditolak.
                </p>
                <p class="text-gray-600">
                    Anda akan dikembalikan ke <strong>Beranda</strong> dalam <span id="countdown">3</span> detik...
                </p>
            @else
                <p class="text-gray-700 mb-4">
                    Anda tidak memiliki izin untuk mengakses halaman ini.
                </p>
                <p class="text-gray-600">
                    Anda akan dikembalikan ke <strong>Beranda</strong> dalam <span id="countdown">3</span> detik...
                </p>
            @endif
        @else
            <p class="text-gray-700 mb-4">
                Silakan login untuk melanjutkan.
            </p>
            <p class="text-gray-600">
                Anda akan diarahkan ke <strong>Login</strong> dalam <span id="countdown">3</span> detik...
            </p>
        @endauth
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let countdown = 3;
            const el = document.getElementById('countdown');

            const interval = setInterval(() => {
                countdown--;
                el.textContent = countdown;
                if (countdown <= 0) {
                    clearInterval(interval);

                    @auth
                        @if(auth()->user()->role === \App\Enums\Role::Karyawan->value)
                            window.location.href = "/";
                        @else
                            window.location.href = "/";
                        @endif
                    @else
                        window.location.href = "/login";
                    @endauth
                }
            }, 1000);
        });
    </script>
</body>
</html>
