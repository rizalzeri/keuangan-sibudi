<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Halaman Email | BUMDES PRO</title>
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
    <main>
        <div class="container">
            @if (isset(auth()->user()->status) == 0)
                <form action="/logout" method="POST" class="text-end mt-3">
                    @csrf
                    <button type="submit" class="btn btn-primary">Keluar <i
                            class="bi bi-box-arrow-in-right"></i></button>
                </form>
            @endif
            <section class="">
                <div class="container">
                    <div class="row cols-1 cols-lg-2">
                        <div class="col d-flex align-items-center justify-content-center d-none d-lg-block"
                            style=" margin:auto; margin-top: 100px;">
                            <div class="text-center">
                                <h2 class="fw-bold">Selamat Datang</h2>
                                <h2 class="fw-bold"> di Aplikasi BUMDES PRO</h2>
                                <img src="/assets/img/akuntansi.png" alt="">
                            </div>
                        </div>
                        <div class="text-center d-block d-lg-none mt-3">
                            <h2 class="fw-bold">Selamat Datang</h2>
                            <h2 class="fw-bold"> di Aplikasi BUMDES PRO</h2>
                        </div>
                        <div class="col-lg-4 mt-3 ">

                            <div class="card mb-3" style=" margin:auto; margin-top: 150px;">
                                <div class="card-header">{{ __('Reset Password') }}</div>

                                <div class="card-body">
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf

                                        <div class="row mb-3">
                                            <div class="col-md-12">
                                                <label for="email"
                                                    class="text-md-end">{{ __('Email Address') }}</label>

                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    name="email" value="{{ old('email') }}" required
                                                    autocomplete="email" autofocus>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-0">
                                            <div class="col-md-6 offset-md-4">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('Lanjut') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>

</html>
