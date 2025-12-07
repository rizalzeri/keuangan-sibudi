<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BUMDES PRO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .detail-langganan {
            display: none;
        }

        .margin-top {
            margin-top: 100px !important;
        }

        @media only screen and (max-width: 768px) {

            .margin-top {
                margin-top: 10px !important;
            }
        }
    </style>
</head>

<body>


    <div class="container">
        @if (auth()->user()->status == 1)
            <a href="/" class="ms-3 fw-bold text-dark">Home</a>
            <div class="text-end d-none d-lg-block">
                <p class="fw-bold">Waktu Tersisa untuk Langganan Anda:</p>
                <div id="countdown" class="countdown"></div>
            </div>
        @endif
        @if (auth()->user()->status == 0)
            <form action="/logout" method="POST" class="text-end mt-3">
                @csrf
                <button type="submit" class="btn btn-primary">Keluar <i class="bi bi-box-arrow-in-right"></i></button>
            </form>
        @endif
        <div class="row cols-1 cols-lg-2">
            <div class="col d-flex align-items-center justify-content-center d-none d-lg-block"
                style=" margin:auto; margin-top: 20px;">
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
            <div class="col  mb-5">
                <div class="card margin-top">


                    <div class="card-body">
                        <div class="card-title">
                            <h3>Langganan Aplikasi BUMDES PRO</h3>
                            <ul>
                                <li>Buat akun dan pilih paket langganan yang tersedia, Batalkan kapan saja</li>
                                <li> Nikmati semua fitur yang tersedia di aplikasi ini dengan biaya langganan server
                                    {{ formatRupiah($mulai) }}/bulan
                                </li>
                                <li> Langganan berakhir hanya menutup fitur yang tersedia dan data akan tetap tersimpan.
                                </li>
                            </ul>
                        </div>
                        <hr>
                        <div class="col-lg-12">
                            @if (count($langganans) > 0)
                                <label class="card p-2 mb-3" style="cursor: pointer">
                                    <div class="form-check" for="1bulan">
                                        <input class="form-check-input" type="radio" name="langganan" id="1bulan"
                                            value="{{ $langganans->first()->jumlah_bulan }}" checked>
                                        <span class="harga">
                                            {{ formatRupiah($langganans->first()->harga) }}
                                        </span>/ <span class="waktu">{{ $langganans->first()->waktu }}</span>
                                    </div>
                                </label>
                                <div class="mb-3">
                                    <span class="detail-langganan"
                                        id="detail-{{ $langganans->first()->jumlah_bulan }}"></span>
                                </div>
                            @else
                                <label class="card p-2 mb-3" style="cursor: pointer">
                                    <div class="form-check" for="1bulan">
                                        <input class="form-check-input" type="radio" name="langganan" id="1bulan"
                                            value="1" checked>
                                        <span>
                                            Rp12.900
                                        </span>/1 bulan
                                    </div>
                                </label>
                            @endif
                            @foreach ($langganans->skip(1) as $langganan)
                                <label class="card p-2 mb-3" style="cursor: pointer">
                                    <div class="form-check" for="{{ $langganan->id }}bulan">
                                        <input class="form-check-input" type="radio" name="langganan"
                                            id="{{ $langganan->id }}bulan" value="{{ $langganan->jumlah_bulan }}">
                                        <span class="harga">
                                            {{ formatRupiah($langganan->harga) }}
                                        </span> /<span class="waktu">{{ $langganan->waktu }}</span>
                                    </div>
                                </label>
                                <div class="mb-3">
                                    <span class="detail-langganan" id="detail-{{ $langganan->jumlah_bulan }}"></span>
                                </div>
                            @endforeach

                            <p>Biaya langganan Sever +Rp 6.500</p>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Langganan
                                <span id="langganan"></span>
                            </button>


                            <!-- Modal -->
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Pembayaran</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6 class="fw-bold">Detail Product</h6>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            Product
                                                        </th>
                                                        <th>
                                                            Qty
                                                        </th>
                                                        <th>
                                                            Amount
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <span id="product"></span> Bulan
                                                        </td>
                                                        <td>
                                                            1
                                                        </td>
                                                        <td>
                                                            <span id="harga"></span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Biaya aktivasi dan transfer
                                                        </td>
                                                        <td>
                                                            1
                                                        </td>
                                                        <td>
                                                            <span id="admin"></span>
                                                        </td>
                                                    </tr>
                                                    <tr class="fw-bold">
                                                        <td>
                                                            Total
                                                        </td>
                                                        <td>
                                                            2
                                                        </td>
                                                        <td>
                                                            <span id="total"></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <h6 class="fw-bold">Detail Pelanggan</h6>

                                            <table class="table">
                                                <tr>
                                                    <td>Nama</td>
                                                    <td><input type="text" class="form-control text-dark"
                                                            value="{{ auth()->user()->name }}" disabled></td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td><input type="text" class="form-control text-dark"
                                                            value="{{ auth()->user()->email }}" disabled></td>
                                                </tr>
                                            </table>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <form id="payment-form" action="/langganan" method="POST">
                                                @csrf
                                                <input type="hidden" name="subscription_duration"
                                                    id="subscription_duration">
                                                <button type="submit"
                                                    class="btn bg-primary bg-gradient fw-bold text-white">Bayar</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap.js -->
    <script type="text/javascript" src="{{ config('midtrans.url_midtrans') }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script>
        $(document).ready(function() {
            // Update langganan harga saat radio button diubah
            function updateLangganan() {
                var harga = $('input[name="langganan"]:checked').closest('label').find('.harga').text();
                $('#langganan').text(harga + ' + Rp 6.500');
                var waktu = $('input[name="langganan"]:checked').closest('label').find('.waktu').text();


                // Set value untuk dikirim melalui form
                var duration = $('input[name="langganan"]:checked').val();
                $('#subscription_duration').val(duration);
                $('#product').text('Langganan akses aplikasi BUMDES PRO ' + duration)
                $('#harga').text(harga)
                $('#admin').text('Rp6.500')
                var angka = parseInt(harga.replace("Rp", "").replace(/\./g, ""));
                var hasil = angka + 6500
                var formatRupiah = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(hasil);
                $('#total').text(formatRupiah)


                // Sembunyikan semua detail
                $('.detail-langganan').hide();
                console.log(harga);

                // Tampilkan detail untuk langganan yang dipilih
                $('#detail-' + duration).html('Detail: Berlangganan selama ' + waktu +
                    ' dapat mengakses seluruh fitur dengan harga ' + harga + '<hr>').show();

            }

            // Jalankan fungsi saat halaman dimuat
            updateLangganan();

            // Jalankan fungsi saat salah satu radio button di-klik
            $('input[name="langganan"]').on('change', function() {
                updateLangganan();
            });

            $('#payment-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        window.location.href = response.redirect_url;
                        // Menggunakan token dari respons untuk memulai pembayaran
                        // window.snap.pay(response.snapToken, {
                        //     onSuccess: function(result) {
                        //         alert("Pembayaran berhasil");

                        //         // Ambil durasi dari radio yang dipilih
                        //         var selectedDays = $('#subscription_duration')
                        //             .val();

                        //         console.log(selectedDays);


                        //         // Kirim data pembayaran ke server setelah pembayaran berhasil
                        //         $.post('/langganan/berhasil', {
                        //             _token: '{{ csrf_token() }}',
                        //             transaction_id: result.transaction_id,
                        //             days: selectedDays
                        //         }).done(function(response) {
                        //             console.log("Success:",
                        //                 response); // Log respons
                        //             window.location.href =
                        //                 "/"; // Redirect setelah berhasil
                        //         }).fail(function(jqXHR, textStatus,
                        //             errorThrown) {
                        //             console.error("Error:", textStatus,
                        //                 errorThrown); // Log kesalahan
                        //             alert(
                        //                 "Terjadi kesalahan saat memproses pembayaran."
                        //             );
                        //         });

                        //     },
                        //     onPending: function(result) {
                        //         alert("Menunggu pembayaran");
                        //         console.log(result);
                        //     },
                        //     onError: function(result) {
                        //         alert("Pembayaran gagal");
                        //         console.log(result);
                        //     },
                        //     onClose: function() {
                        //         alert(
                        //             "Anda menutup pop-up tanpa menyelesaikan pembayaran"
                        //         );
                        //     }
                        // });
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan saat memproses pembayaran.');
                        console.error(error);
                    }
                });
            });
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
                $('#countdown').html(days + " Hari " + hours + " Jam " +
                    minutes + " Menit " + seconds + " Detik ");

                // Jika hitungan selesai, tampilkan pesan
                if (distance < 0) {
                    clearInterval(countdownInterval);
                    $('#countdown').html("Langganan telah berakhir");
                }
            }, 1000);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
