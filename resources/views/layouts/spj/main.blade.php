<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>BUMDES PRO</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery Timepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css"/>

    <!-- jQuery Timepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Flatpickr (jika diperlukan) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <!-- Optional: Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

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
    @include('layouts.spj.header')

    @include('layouts.spj.sidebar')
    <main id="main" class="main">

        <section class="section dashboard">
            @include('layouts.spj.alert')
            @yield('container')
        </section>

    </main><!-- End #main -->
    @include('layouts.spj.footer')





    <script>
        function onlyNumberAmount(input) {
            let v = input.value.replace(/[^0-9\-\+]/g, ''); // Hapus semua karakter selain angka
            if (v.length > 14) v = v.slice(0, 14); // Batasi hingga 14 digit
            input.value = v.replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Tambahkan titik sebagai pemisah ribuan
        }

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (sessionStorage.getItem("scrollPosition") !== null) {
                window.scrollTo(0, sessionStorage.getItem("scrollPosition"));
            }

            window.addEventListener("beforeunload", function() {
                sessionStorage.setItem("scrollPosition", window.scrollY);
            });

            window.addEventListener("popstate", function() {
                if (sessionStorage.getItem("scrollPosition") !== null) {
                    window.scrollTo(0, sessionStorage.getItem("scrollPosition"));
                }
            });
        });
    </script>





</body>

</html>
