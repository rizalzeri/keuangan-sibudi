<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Halaman Login | BUMDES PRO</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="/assets/img/" rel="icon">
    <link href="/assets/img/" rel="apple-touch-icon">

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

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Mar 09 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->

    <style>
        @media (max-width: 768px) {
            .custom-margin {
                margin-top: 50px;
                /* Set margin-top khusus untuk layar kecil */
            }
        }

        @media (min-width: 769px) {
            .custom-margin {
                margin-top: 150px;
                /* Set margin-top khusus untuk layar besar */
            }
        }
    </style>
</head>

<body>
    @include('sweetalert::alert')
    <main>
        <div class="container">

            <section class="">
                <div class="container">
                    <div class="row cols-1 cols-lg-2">
                        <div class="col d-flex align-items-center justify-content-center d-none d-lg-block"
                            style=" margin:auto; margin-top: 150px;">
                            <div class="text-center">
                                <h2 class="fw-bold">Selamat Datang</h2>
                                <h2 class="fw-bold"> di BUMDES PRO</h2>
                                <img src="/assets/img/akuntansi.png" alt="">
                            </div>
                        </div>
                        <div class="text-center d-block d-lg-none mt-3">
                            <h2 class="fw-bold">Selamat Datang</h2>
                            <h2 class="fw-bold"> di BUMDES PRO</h2>
                        </div>
                        <div class="col-lg-4 mt-3 ">

                            <div class="card mb-3" style=" margin:auto; margin-top: 150px;">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Bantuan Lupa Password</h5>
                                        <p class="text-center small">Masukan data dengan benar</p>
                                    </div>

                                    <form action="/kirim-email" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="nama">Nama</label>
                                            <input type="text" name="nama" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="no_telepon">No Telepon</label>
                                            <input type="text" name="no_telepon" class="form-control" required>
                                        </div>
                                        <div class="mb-1">
                                            <label for="pesan">Pesan</label>
                                            <textarea name="pesan" class="form-control" required></textarea>
                                        </div>
                                        <input type="hidden" name="detail" value="Telah meminta merubah kata sandi">
                                        <div class="col-12 mt-1 mb-2">
                                            <p class="small mb-0">Sudah punya akun? <a href="/login">Log in</a>
                                            </p>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Kirim</button>
                                    </form>

                                </div>
                            </div>



                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="/assets/vendor/echarts/echarts.min.js"></script>
    <script src="/assets/vendor/quill/quill.min.js"></script>
    <script src="/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="/assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="/assets/js/main.js"></script>

</body>

</html>
