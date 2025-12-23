<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Cetak Bukti Kas Masuk - A4/Custom</title>
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

        /* ---- sign area: both boxes sejajar ---- */
        .sign-area {
            position: absolute;
            top: 40%;
            left: 48px;
            right: 48px;
            bottom: 48px;
            display: flex;
            justify-content: space-between;
            gap: 24px;
        }

        .sign-box {
            width: 260px;
            /* fixed height ensures dotted lines are at the same vertical level */
            height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end; /* tempatkan garis dekat bottom sehingga sejajar */
            align-items: center;
            box-sizing: border-box;
            padding: 6px;
            text-align: center;
        }

        .sign-title {
            margin-bottom: 60px;
            font-size: 14px;
        }

        /* area nama di atas garis — jika kosong, min-height menjaga ruang */
        .sign-name {
            min-height: 22px;
            line-height: 1.2;
            display: block;
            text-align: center;
            width: 100%;
        }

        /* garis tanda tangan */
        .sign-line {
            width: 100%;
            border-top: 1px dotted #000;
            margin-bottom: 4px;
        }

        /* caption / jabatan_mengetahui di bawah garis */
        .sign-caption {
            font-weight: 500;
            min-height: 18px;
        }

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
    @php
        if (isset($record)) {
            $tanggal = optional($record->tanggal_transaksi)->format('Y-m-d') ?? date('Y-m-d');
            $nama_transaksi = $record->nama_transaksi ?? '........';
            $nomor_dokumen = $record->nomor_dokumen ?? '........';
            $sumber = $record->sumber ?? '........';
            $nominal = $record->nominal ?? 0;
            $penerima = $record->penerima ?? '........';
            $mengetahui = $record->mengetahui ?? null;
            $catatan = $record->catatan ?? '........';
            $jabatan_mengetahui = $jabatan_mengetahui ?? '........';
        } else {
            $tanggal = $data['tanggal'] ?? $data['tanggal_display'] ?? date('Y-m-d');
            $nama_transaksi = $data['transaksi'] ?? $data['nama_transaksi'] ?? '........';
            $nomor_dokumen = $data['nomor_dokumen'] ?? '........';
            $sumber = $data['sumber'] ?? '........';
            $nominal = $data['nominal'] ?? 0;
            $penerima = $data['penerima'] ?? '........';
            $mengetahui = $data['mengetahui'] ?? null;
            $catatan = $data['catatan'] ?? '........';
            $jabatan_mengetahui = $data['jabatan_mengetahui'] ?? '........';
        }
    @endphp


    <div class="page" id="receipt">
        <div class="header-left">
            <b>BUMDESA</b><br>
            <span>{{ $nama_bumdes }}</span><br>
            <span>{{ $alamat_bumdes }}</span>
        </div>

        <div class="header-right">
            <span style="text-decoration: underline dotted;">Nomor {{ $nomor_dokumen }}</span>
        </div>

        <div class="title">BUKTI KAS MASUK</div>

        <div class="content">
            <div class="line-row">
                <div class="label">Diterima Uang Dari</div>
                <div class="colon">:</div>
                <div class="value">{{ $sumber }}</div>
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

            <!-- <-- NEW: tampilkan Penerima di bawah Tanggal seperti yang diminta --> 
            <div class="line-row">
                <div class="label">Penerima</div>
                <div class="colon">:</div>
                <div class="value">{{ $penerima }}</div>
            </div>

            <div class="line-row">
                <div class="label">Catatan</div>
                <div class="colon">:</div>
                <div class="value">{{ $catatan }}</div>
            </div>
        </div>

        <div class="sign-area">

            {{-- MENGETAHUI (kiri) --}}
            <div class="sign-box">
                <div class="sign-title">Mengetahui</div>

                {{-- Nama yang mengetahui — tampil di atas garis jika ada, jika tidak biarkan kosong (ruang tetap ada) --}}
                <div class="sign-name">{{ $mengetahui ?? '' }}</div>

                <div class="sign-line"></div>

                <div class="sign-caption">{{ $jabatan_mengetahui }}</div>
            </div>

            {{-- PENERIMA (kanan) --}}
            @php
                use Carbon\Carbon;
                try {
                    $tanggal_for_format = !empty($tanggal) ? $tanggal : now();
                    $tanggal_lokal = Carbon::parse($tanggal_for_format)
                        ->locale('id')
                        ->translatedFormat('j F Y');
                } catch (\Exception $e) {
                    $tanggal_lokal = Carbon::now()->locale('id')->translatedFormat('j F Y');
                }
            @endphp

            <div class="sign-box">
                <!-- TITLE SEJAJAR DENGAN "Mengetahui" -->
                <div class="sign-subtitle">........, {{ $tanggal_lokal }}</div>
                <div class="sign-title">Penerima</div>

                <!-- TANGGAL DI ATAS AREA TTD -->
                

                <!-- ruang nama di atas garis (opsional, tetap agar sejajar) -->
                <div class="sign-name"></div>

                <!-- garis tanda tangan -->
                <div class="sign-line"></div>

                <!-- nama penerima -->
                <div class="sign-caption">{{ $penerima }}</div>
            </div>


        </div>

    </div>

    <!-- html2pdf bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
    (function () {
        const btn = document.getElementById('downloadBtn');
        const element = document.getElementById('receipt');

        function getElementSize(el) {
            const rect = el.getBoundingClientRect();
            return { width: Math.round(rect.width), height: Math.round(rect.height) };
        }

        async function downloadPdfMatchingCanvas(filename = 'bukti_kas_masuk.pdf') {
            const size = getElementSize(element);
            const prevBoxShadow = element.style.boxShadow;
            element.style.boxShadow = 'none';
            const deviceScale = window.devicePixelRatio ? Math.min(window.devicePixelRatio, 2) : 1;
            const scale = Math.max(2, deviceScale);

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
                    format: [size.width, size.height],
                    orientation: 'portrait'
                }
            };

            try {
                await html2pdf().set(opt).from(element).save();
                console.log('PDF terdownload.');
            } catch (err) {
                console.error('Gagal membuat PDF:', err);
                alert('Gagal membuat PDF. Lihat console untuk detail.');
            } finally {
                element.style.boxShadow = prevBoxShadow || '';
            }
        }

        btn.addEventListener('click', function () {
            downloadPdfMatchingCanvas();
        });

    })();
    </script>

</body>
</html>
