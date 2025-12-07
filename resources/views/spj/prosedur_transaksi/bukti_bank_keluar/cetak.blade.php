<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak Bukti Bank Keluar - A4/Custom</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #e9e9e9;
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
        }

        /* ukuran A4 tampilan (tetap bisa disesuaikan) */
        .page {
            width: 794px;    /* A4 @96dpi */
            height: 1123px;  /* A4 @96dpi */
            background: #fff;
            box-sizing: border-box;
            padding: 40px 48px;
            position: relative;
            /* shadow hanya untuk preview — akan dihilangkan saat render */
            box-shadow: 0 0 10px rgba(0,0,0,0.22);
        }

        .header-left { position: absolute; left: 48px; top: 28px; font-size: 14px; }
        .header-right { position: absolute; right: 48px; top: 28px; font-size: 14px; text-align: right; }
        .title { margin-top: 90px; text-align: center; font-size: 44px; font-weight: 800; text-decoration: underline; }
        .content { margin-top: 40px; padding: 0 40px; }
        .line-row { display: flex; margin: 12px 0; font-size: 20px; }
        .label { width: 250px; font-weight: 600; }
        .colon { width: 20px; }
        .value { flex: 1; border-bottom: 1px dotted #000; padding-bottom: 4px; }

        .sign-area {
            position: absolute;
            bottom: 100px;
            left: 48px;
            right: 48px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        .sign-box { width: 220px; }
        .sign-line { margin-top: 75px; border-top: 1px dotted #000; padding-top: 5px; }

        /* tombol download tetap terlihat di preview */
        #downloadBtn {
            position: fixed;
            top: 15px;
            right: 15px;
            padding: 10px 14px;
            background: #1976d2;
            color: white;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            z-index: 50;
        }
    </style>
</head>
<body>

    <button id="downloadBtn">Download PDF</button>

    <div class="page" id="receipt">
        <div class="header-left">
            <b>BUMDESA</b><br>
            <span style="text-decoration: underline dotted;">Alamat baris 1</span><br>
            <span style="text-decoration: underline dotted;">Alamat baris 2</span>
        </div>

        <div class="header-right">
            Nomor<br>
            <span style="text-decoration: underline dotted;">.....................</span>
        </div>

        <div class="title">BUKTI BANK KELUAR</div>

        @php
            $tanggal = $data['tanggal'] ?? date('Y-m-d');
            $nama_transaksi = $data['nama_transaksi'] ?? '........';
            $tujuan = $data['tujuan'] ?? '........';
            $nominal = $data['nominal'] ?? '0';
            $penerima = $data['penerima'] ?? '........';
            $mengetahui = $data['mengetahui'] ?? '........';
        @endphp

        <div class="content">
            <div class="line-row">
                <div class="label">Diterima Uang Dari</div>
                <div class="colon">:</div>
                <div class="value">{{ $tujuan }}</div>
            </div>

            <div class="line-row">
                <div class="label">Senilai</div>
                <div class="colon">:</div>
                <div class="value">Rp. {{ number_format((int)$nominal,0,',','.') }}</div>
            </div>

            <div class="line-row">
                <div class="label">Transaksi</div>
                <div class="colon">:</div>
                <div class="value">{{ $nama_transaksi }}</div>
            </div>

            <div class="line-row">
                <div class="label">Tanggal</div>
                <div class="colon">:</div>
                <div class="value">{{ $tanggal }}</div>
            </div>

            <div class="line-row">
                <div class="label">Catatan</div>
                <div class="colon">:</div>
                <div class="value">-</div>
            </div>
        </div>

        <div class="sign-area">
            {{-- Box Mengetahui --}}
            <div class="sign-box" style = "margin-top: 20px;">
                Mengetahui
                <div class="sign-line">{{ $mengetahui }}</div>
            </div>

            {{-- Tanggal lokal --}}
            @php
                setlocale(LC_TIME, 'id_ID');
                $tanggal_lokal = \Carbon\Carbon::now()->translatedFormat('j F Y');
            @endphp

            {{-- Box Penerima --}}
            <div class="sign-box">
                <div>
                    ........, {{ $tanggal_lokal }}
                </div>
                Penerima
                <div class="sign-line">{{ $penerima }}</div>
            </div>
        </div>

    </div>

    <!-- html2pdf bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
    (function () {
        const btn = document.getElementById('downloadBtn');
        const element = document.getElementById('receipt');

        // Fungsi util: ambil ukuran elemen (int)
        function getElementSize(el) {
            const rect = el.getBoundingClientRect();
            return { width: Math.round(rect.width), height: Math.round(rect.height) };
        }

        // Render & download PDF dengan fidelity tinggi dan ukuran sama dengan canvas preview
        async function downloadPdfMatchingCanvas(filename = 'bukti_bank_keluar.pdf') {
            // 1) ambil ukuran element saat ini
            const size = getElementSize(element);

            // 2) simpan style sementara (hilangkan shadow agar tidak ikut tercetak)
            const prevBoxShadow = element.style.boxShadow;
            element.style.boxShadow = 'none';

            // 3) tentukan scale yang keluar akal (devicePixelRatio bisa > 1)
            const deviceScale = window.devicePixelRatio ? Math.min(window.devicePixelRatio, 2) : 1;
            const scale = Math.max(2, deviceScale); // gunakan minimal 2 untuk ketajaman

            // 4) setup opsi html2pdf dengan jsPDF page size sama dengan ukuran elemen
            const opt = {
                margin: 0,
                filename: filename,
                image: { type: 'jpeg', quality: 1 },
                html2canvas: {
                    scale: scale,
                    backgroundColor: "#ffffff",
                    useCORS: true,
                    removeContainer: true,
                    scrollX: 0,
                    scrollY: 0,
                },
                jsPDF: {
                    unit: 'px',
                    // set format ke ukuran elemen agar hasil PDF punya dimensi sama persis
                    format: [size.width, size.height],
                    orientation: 'portrait'
                }
            };

            try {
                // 5) Jalankan render dan simpan
                await html2pdf().set(opt).from(element).save();

                // selesai — optional: beri notifibanki / console
                console.log('PDF terdownload.');
            } catch (err) {
                console.error('Gagal membuat PDF:', err);
                alert('Gagal membuat PDF. Lihat console untuk detail.');
            } finally {
                // 6) kembalikan style semula
                element.style.boxShadow = prevBoxShadow || '';
            }
        }

        btn.addEventListener('click', function () {
            downloadPdfMatchingCanvas();
        });

        // Opsional — jika mau auto-download ketika tab dibuka, uncomment berikut:
        // window.addEventListener('load', () => downloadPdfMatchingCanvas());
    })();
    </script>

</body>
</html>
