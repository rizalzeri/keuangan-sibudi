<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>BUMDES PRO</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="/assets/img/logo.png" rel="icon">
    <link href="/assets/img/logo.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="/assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="/assets/css/style.css" rel="stylesheet">

    {{-- icone bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- Trix editor --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>



    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Nov 17 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
    ========================================================
    Projek by : Eri Hidayat
    =========================================================
   -->
</head>

<body>

    @include('sweetalert::alert')
    @include('layouts.header')

    @include('layouts.sidebar')
    <main id="main" class="main">

        <section class="section dashboard">
            <div class="card">
                <div class="card-body">

                    @include('layouts_proker.breadcrumb')
                    <h5 class="card-title">{{ $title }}</h5>
                    @yield('container')
                </div>
            </div>

        </section>

        <style>
            .selanjutnya {
                width: 100%;
                padding: 15px;
                border-radius: 10px;
                position: sticky;
                bottom: 0;
                background-color: white;
            }

            .back-to-top {
                display: none !important;
            }
        </style>




        <div class="selanjutnya text-end">
            @if ($title == 'E. RENCANA KEGIATAN/PROGRAM')
                <a href="{{ $back }}" class="btn  btn-secondary">Kembali</a>
                <a href="/proker/penambahan/modal" class="btn  btn-primary">Selanjutnya</a>
            @elseif ($title == 'G. RENCANA PENAMBAHAN MODAL')
                <a href="{{ $back }}" class="btn  btn-secondary">Kembali</a>

                <a href="/cetak/proker" class="btn  btn-danger">Cetak</a>
            @elseif (isset($back))
                <a href="{{ $back }}" class="btn  btn-secondary">Kembali</a>
                <button type="submit" class="btn  btn-primary">Selanjutnya</button>
            @else
                <a href="{{ $next }}" class="btn  btn-primary">Selanjutnya</a>
            @endif
        </div>


    </main><!-- End #main -->

    @include('layouts.footer')





    <script>
        function onlyNumberAmount(input) {
            let v = input.value.replace(/[^0-9\-\+]/g, ''); // Hapus semua karakter selain angka
            if (v.length > 14) v = v.slice(0, 14); // Batasi hingga 14 digit
            input.value = v.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tambahkan titik sebagai pemisah ribuan
        }

        // Hapus format titik sebelum form disubmit
        $('form').on('submit', function() {
            // Cari semua input di dalam form ini yang memiliki type="text"
            $(this).find('input[type="text"]').each(function() {
                var inputVal = $(this).val();
                // Hapus titik pemisah ribuan
                $(this).val(inputVal.replace(/\./g, ''));
            });
        });
    </script>

    <script>
        // Simpan posisi scroll sebelum reload
        window.addEventListener("beforeunload", function() {
            localStorage.setItem("scrollPos", window.scrollY);
        });

        // Saat halaman load, kembalikan posisi scroll
        window.addEventListener("load", function() {
            const scrollPos = localStorage.getItem("scrollPos");
            if (scrollPos) {
                window.scrollTo(0, parseInt(scrollPos));
                localStorage.removeItem("scrollPos"); // hapus setelah dipakai
            }
        });
    </script>



    <script>
        $(document).ready(function() {
            // Tanggal akhir langganan (format YYYY-MM-DD)
            var endDate = new Date("{{ auth()->user()->tgl_langganan }}T00:00:00").getTime();

            // Update countdown setiap 1 detik
            var countdownInterval = setInterval(function() {
                // Waktu saat ini
                var now = new Date().getTime();

                // Hitung selisih waktu
                var distance = endDate - now;

                // Hitung waktu untuk hari, jam, menit, dan detik
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Tampilkan hasil hitung mundur
                $('#countdown').html(days + " Hari ");

                // Jika hitungan selesai, tampilkan pesan
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    $('#countdown').html("Langganan telah berakhir");
                }
            }, 1000);
        });
    </script>



</body>

</html>
