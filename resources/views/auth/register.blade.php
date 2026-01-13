<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Halaman Register | BUMDES PRO</title>
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

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Mar 09 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<style>
    @media only screen and (max-width: 768px) {

        .margin-top {
            margin-top: 10px !important;
        }

        .kontak {
            position: block;
        }
    }

    .kontak {
        height: 60px;
        max-width: 300px;
        background-color: white;
        padding: 5px 10px;
        border-radius: 0px 20px 20px 0px;
        position: fixed;
    }

    .email-kontak {
        font-size: 12px
    }

    .tooltip-text {
        visibility: hidden;
        width: 150px;
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 5px;
        border-radius: 5px;
        position: absolute;
        margin-left: 10px;
        margin-top: -5px;
        opacity: 0;
        transition: opacity 0.3s;
        display: inline-block;
    }

    .tooltip-icon.active+.tooltip-text {
        visibility: visible;
        opacity: 1;
    }
</style>

<body>

    <main>
        <div class="kontak shadow-sm mt-3 fixed-top">
            <div class="d-flex justify-content-start">
                <img src="/assets/img/logo.png" alt="" width="40px" height="40px">

                <span class="email-kontak ms-3">Kontak Kami <br> bumdespro@gmail.com</span>

            </div>
        </div>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row cols-1 cols-lg-2">
                        <div class="col d-flex align-items-center justify-content-center d-none d-lg-block"
                            style=" margin:auto; margin-top: 10px;">
                            <div class="text-center">
                                <h2 class="fw-bold">Selamat Datang</h2>
                                <h2 class="fw-bold"> di Aplikasi BUMDES PRO</h2>
                                <img src="/assets/img/akuntansi.png" alt="">
                                <p>Aplikasi BUMDES PRO merupakan website yang digunakan untuk mencatat, mengolah
                                    dan meyajikan laporan keuangan secara akurat,
                                    Memudahkan bagi pengelola keuangan BUMDesa untuk mengelola keuangan secara mudah,
                                    dengan fitur yang lebih modern serta menyimpan data dan dokumen keuangan BUMDesa
                                    secara aman dan terjamin.
                                    Nikamti semua kemudahan dalam satu aplikasi</p>
                            </div>
                        </div>
                        <div class="text-center d-block d-lg-none mt-3">
                            <h2 class="fw-bold">Selamat Datang</h2>
                            <h2 class="fw-bold"> di Aplikasi BUMDES PRO</h2>
                        </div>
                        <div class="col-lg-4 " style=" margin:auto;">


                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Buat Akun</h5>
                                        <p class="text-center small">Masukkan detail pribadi Anda untuk membuat akun</p>
                                    </div>

                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <div class="col-12 mt-3">
                                            <label for="yourName" class="form-label">Name</label>
                                            <input id="name" type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ old('name') }}" required autocomplete="name" autofocus>

                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-12 mt-3">
                                            <label for="yourEmail" class="form-label">Email</label>
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email">

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>


                                        <div class="col-12 mt-3">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="new-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label for="yourPassword" class="form-label">Confirmasi Password</label>
                                            <input id="password-confirm" type="password" class="form-control"
                                                name="password_confirmation" required autocomplete="new-password">
                                        </div>
                                        <div class="col-12 mt-3">
                                            <label for="referral" class="form-label">Pilih BUMDesa</label>
                                            <select class="form-select @error('referral') is-invalid @enderror"
                                                id="referral" name="referral">
                                                <option value="1" {{ old('referral') == '1' ? 'selected' : '' }}>
                                                    BUMDesa
                                                </option>
                                                <option value="0" {{ old('referral') == '0' ? 'selected' : '' }}>
                                                    Koperasi
                                                </option>

                                            </select>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <button class="btn btn-primary w-100" type="submit">Buat Akun</button>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <p class="small mb-0">Sudah punya akun? <a href="/login">Log in</a>
                                            </p>
                                        </div>
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


    <script>
        function toggleTooltip(element) {
            element.classList.toggle('active');
        }
    </script>

</body>

</html>
