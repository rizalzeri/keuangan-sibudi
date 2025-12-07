@extends('layouts.main')

@section('container')
    <div class="pagetitle">
        <h1>Contact</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Pages</li>
                <li class="breadcrumb-item active">Contact</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section contact">

        <div class="row gy-4">

            <div class="col-xl-6">

                <div class="row">

                    <div class="col-lg-6">
                        <div class="info-box card">
                            <i class="bi bi-telephone"></i>
                            <h3>Call Us</h3>
                            <p>0821-3772-1941</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="info-box card">
                            <i class="bi bi-envelope"></i>
                            <h3>Email Us</h3>
                            <p>erihidayat549@gmail.com</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-6">
                <div class="card p-4">

                    <div class="card-body">

                        <div class="pb-2">
                            <h5 class="card-title pb-0 fs-4">Pesan atau Saran</h5>
                            <p class=" small">Jika ada keluhan atau saran bisa hubungi kami dengan mengisi formulir dibawah
                            </p>
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
                            <input type="hidden" name="detail" value="Telah memberikan pesan atau saran">
                            <button type="submit" class="btn btn-primary">Kirim</button>
                        </form>

                    </div>
                </div>

            </div>

        </div>

    </section>
@endsection
