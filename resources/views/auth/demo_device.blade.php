<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Menyiapkan Sesi Praktikum...</title>
    <style>
        body {
            margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); color: #fff;
        }
        .box { text-align: center; padding: 2rem; }
        .spinner {
            width: 48px; height: 48px; margin: 0 auto 1rem; border-radius: 50%;
            border: 4px solid rgba(255,255,255,.3); border-top-color: #fff;
            animation: spin .8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="box">
        <div class="spinner"></div>
        <h1 style="font-size:1.2rem;margin:0 0 .25rem;">Menyiapkan Sesi Praktikum...</h1>
        <p style="opacity:.85;margin:0;">Mohon tunggu sebentar.</p>
        <noscript><p>JavaScript harus diaktifkan untuk masuk ke sesi praktikum.</p></noscript>
    </div>

    <script>
        (function () {
            var KEY = 'sibudi_demo_device_id';

            function generateId() {
                if (window.crypto && typeof window.crypto.randomUUID === 'function') {
                    return window.crypto.randomUUID();
                }
                var s = '';
                var chars = 'abcdef0123456789';
                for (var i = 0; i < 32; i++) {
                    s += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return s;
            }

            var deviceId = null;
            try {
                deviceId = localStorage.getItem(KEY);
                if (!deviceId || !/^[A-Za-z0-9\-]{16,64}$/.test(deviceId)) {
                    deviceId = generateId();
                    localStorage.setItem(KEY, deviceId);
                }
            } catch (e) {
                // localStorage tidak tersedia (mis. mode privat ketat) -> pakai ID sementara
                deviceId = generateId();
            }

            var params = new URLSearchParams();
            params.set('token', @json($token));
            params.set('device_id', deviceId);

            window.location.replace(@json(url('/login')) + '?' + params.toString());
        })();
    </script>
</body>
</html>
