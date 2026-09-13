@if(auth()->check() && auth()->user()->is_demo)
<div id="demo-academy-banner" class="alert shadow-sm border-0 mb-3" style="background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); color: #ffffff; border-radius: 10px;">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 p-1">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-warning text-dark fs-6 px-2 py-1 shadow-sm">
                🎓 Praktikum Academy
            </span>
            <div>
                <strong>Sesi Praktikum Aktif (Bypass Auth Token):</strong>
                <span class="d-block d-md-inline small opacity-90 ms-md-1">
                    Seluruh fitur terbuka. Data latihan bersifat <strong>sementara</strong> dan akan dibersihkan otomatis.
                </span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 ms-auto">
            <div class="bg-white text-primary px-3 py-1 rounded-pill fw-bold small shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-clock-history"></i> Sisa Waktu: 
                <span id="demo-countdown" class="text-danger">Memuat...</span>
            </div>
            <form action="{{ route('demo.reset') }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin mereset seluruh data praktikum? Semua data latihan yang Anda masukkan akan dihapus dan kembali ke kondisi awal.');">
                @csrf
                <button type="submit" class="btn btn-sm btn-light text-danger fw-semibold px-2 py-1 rounded-pill shadow-sm">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Data
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        // Target waktu kedaluwarsa dari database
        var expiresAtStr = "{{ auth()->user()->demo_expires_at ? auth()->user()->demo_expires_at->toIso8601String() : '' }}";
        if (!expiresAtStr) return;

        var targetTime = new Date(expiresAtStr).getTime();
        var countdownEl = document.getElementById('demo-countdown');

        function updateTimer() {
            var now = new Date().getTime();
            var distance = targetTime - now;

            if (distance <= 0) {
                countdownEl.innerText = "00:00 (Sesi Habis)";
                countdownEl.classList.add('text-danger');
                // Refresh halaman agar middleware membersihkan data
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
                return;
            }

            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            var minStr = (minutes < 10 ? '0' : '') + minutes;
            var secStr = (seconds < 10 ? '0' : '') + seconds;

            countdownEl.innerText = minStr + ":" + secStr;
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    })();
</script>
@endif
