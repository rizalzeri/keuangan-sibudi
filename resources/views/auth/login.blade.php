<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Halaman Login | BUMDES PRO</title>

  <!-- Favicons -->
  <link href="/assets/img/logo.png" rel="icon">
  <link href="/assets/img/logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="/assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="/assets/css/style.css" rel="stylesheet">

  <style>
    .margin-top {
      margin-top: 150px !important;
    }

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

    /* Welcome column styles */
    .welcome-col {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 6px;
      padding: 6px;
    }

    /* Smaller welcome image and keep it responsive */
    .welcome-img {
      max-width: 160px;
      width: 100%;
      height: auto;
      display: block;
    }

    /* Header welcome: text + image sejajar */
    .welcome-header {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      flex-wrap: wrap;
    }

    .welcome-text h2 {
      margin: 0;
      line-height: 1.05;
    }

    /* portal-group and buttons */
    .portal-group {
      width: 100%;
      max-width: 520px;
      display: flex;
      flex-direction: column;
      gap: 18px;
      margin: 12px 0;
    }

    .portal-btn {
      width: 100%;
      text-align: left;
      padding: 14px 18px;
      border-radius: 12px;
      font-weight: 600;
      white-space: normal;
      box-shadow: 0 3px 0 rgba(0, 0, 0, 0.08);
      display: inline-flex;
      align-items: center;
      justify-content: space-between;
    }

    .portal-btn .label {
      flex: 1;
      text-align: left;
    }

    .portal-btn .arrow {
      width: 34px;
      height: 34px;
      background: #ff1a1a;
      color: #fff;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-left: 12px;
      font-weight: 700;
    }

    .portal-btn.blue {
      background: #1e73be;
      color: #fff;
      border: 2px solid #184f8a;
    }

    .portal-btn.green {
      background: #0f6b38;
      color: #fff;
      border: 2px solid #0a4a2b;
    }

    .portal-btn.outline {
      background: #fff;
      color: #0b1a2b;
      border: 2px solid #d1d7de;
    }

    /* caption/link 'Menuju halaman login' (nempel, no box) */
    .portal-caption {
      display: flex;
      justify-content: flex-end;
      margin-top: 0;
    }

    .portal-caption a.small {
      background: transparent;
      padding: 0;
      border: none;
      font-size: 13px;
      color: #0b1a2b;
      text-decoration: underline;
      font-weight: 500;
    }

    /* Templates modal content */
    .templates-panel {
      border: 2px solid #d0d5db;
      border-radius: 8px;
      padding: 14px;
      background: #fff;
    }

    .templates-title {
      text-align: center;
      border: 2px solid #222;
      padding: 8px 12px;
      font-weight: 700;
      margin-bottom: 12px;
      display: inline-block;
      width: 100%;
      box-sizing: border-box;
    }

    .templates-list {
      margin: 0;
      padding-left: 18px;
      list-style: decimal;
      font-weight: 600;
    }

    .templates-list li {
      margin-bottom: 10px;
    }

    .templates-list a.main-toggle {
      display: inline-block;
      width: 100%;
      text-decoration: none;
      color: #0b1a2b;
      padding: 6px 4px;
      font-weight: 700;
    }

    .templates-list a.main-toggle:hover {
      text-decoration: underline;
    }

    /* sublist that shows after clicking main li */
    .sub-list {
      margin-top: 8px;
      padding-left: 18px;
      list-style: lower-alpha;
      font-weight: 500;
    }

    .sub-list li {
      margin-bottom: 6px;
    }

    .sub-list a {
      text-decoration: none;
      color: #0b1a2b;
    }

    .sub-list a:hover {
      text-decoration: underline;
    }

    /* modal custom styling */
    .modal-body {
      max-height: 60vh;
      overflow: auto;
      padding: 18px;
    }

    .modal-header-blue {
      background: #1e73be;
      color: #fff;
      padding: 12px 16px;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      position: relative;
    }

    .modal-header-green {
      background: #0f6b38;
      color: #fff;
      padding: 12px 16px;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      position: relative;
    }

    .modal-header-plain {
      background: #ffffff;
      color: #0b1a2b;
      padding: 12px 16px;
      border-top-left-radius: 8px;
      border-top-right-radius: 8px;
      position: relative;
      border-bottom: 1px solid #e6e6e6;
    }

    .modal-header-blue h5,
    .modal-header-green h5,
    .modal-header-plain h5 {
      margin: 0;
      font-weight: 700;
    }

    /* close circle (putting X di luar sudut header) */
  .modal-close-circle {
      position: absolute;
      right: -18px;
      top: -18px;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #fff;

      display: flex;
      align-items: center;
      justify-content: center;

      border: 2px solid #222;
      cursor: pointer;

      color: #000;          /* ✅ WARNA TEKS HITAM */
      font-size: 18px;      /* opsional: biar proporsional */
      font-weight: 600;     /* opsional: biar tegas */
  }

    /* === PRICE SECTION === */
    
    .price-section {
    margin-top: 16px;
    }

    .price-title {
    font-weight: 700;
    margin-bottom: 8px;
    }

    /* container harga */
    .price-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 6px;
    font-weight: 700;
    }

    /* warna background mengikuti modal */
    .price-blue {
    background: #1e73be;
    }

    .price-green {
    background: #0f6b38;
    }

    /* harga lama */
    .price-bar .old {
    background: #d90429;     /* merah */
    color: #fff;
    padding: 6px 10px;
    border-radius: 4px;
    text-decoration: line-through;
    font-weight: 600;
    }

    /* harga baru */
    .price-bar .new {
    color: #ffd60a;          /* kuning */
    font-size: 16px;
    font-weight: 800;
    }
    /* === PRICE BAR FULL WIDTH DI DALAM <li> === */
    /* PRICE BAR KELUAR DARI INDENT <ol> */
    .modal-list li .price-bar {
    width: calc(100% + 32px); /* kompensasi padding ol */
    margin-left: -32px;
    box-sizing: border-box;
    }

    /* === BLACK THEME (REPLACE GREEN) === */

    /* portal button black */
    .portal-btn.black {
    background: #000;
    color: #fff;
    border: 2px solid #000;
    }

    /* modal header black */
    .modal-header-black {
    background: #000;
    color: #fff;
    padding: 12px 16px;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    position: relative;
    }

    /* price bar black */
    .price-black {
    background: #000;
    }



  </style>
</head>

<body>

  <main>
    <div class="kontak shadow-sm mt-3 fixed-top">
      <div class="d-flex justify-content-start">
        <img src="/assets/img/logo.png" alt="" width="40px" height="40px">
        <span class="email-kontak ms-3">Kontak Kami <br> erihidayat549@gmail.com</span>
      </div>
    </div>

    <div class="container">
      <section class="">
        <div class="container">
          <div class="row cols-1 cols-lg-2">

            <!-- LEFT: welcome panel for large screens -->
            <div class="col d-flex align-items-center justify-content-center d-flex welcome-wrapper" style="margin:auto; margin-top: 100px;">
              <div class="welcome-col text-center">

                <div class="welcome-header">
                  <div class="welcome-text text-center text-lg-start">
                    <h2 class="fw-bold">Selamat Datang</h2>
                    <h2 class="fw-bold">di BUMDES PRO</h2>
                  </div>

                  <img src="/assets/img/akuntansi.png" alt="akuntansi" class="welcome-img">
                </div>

                <!-- Buttons group -->
                <div class="portal-group">

                  <!-- Tombol 1 - Pembukuan (buka modal biru) -->
                  <a href="#" class="btn portal-btn blue" data-bs-toggle="modal" data-bs-target="#modalPembukuan">
                    <span class="label">Aplikasi Pembukuan dan Pelaporan Keuangan BUMDesa</span>
                    <span class="arrow">▶</span>
                  </a>
                  <div class="portal-caption">
                    <a href="https://bumdespro.my.id/login" class="small">Menuju halaman login</a>
                  </div>

                  <!-- Tombol 2 - Pengelolaan Website Desa (Website Desa) -->
                  <a href="#" class="btn portal-btn black" data-bs-toggle="modal" data-bs-target="#modalTataAdmin">
                    <span class="label">Aplikasi Pengelolaan Website BUMDES</span>
                    <span class="arrow">▶</span>
                  </a>
                  <div class="portal-caption">
                    <a href="/login" class="small">Menuju halaman login</a>
                  </div>

                  <!-- Tombol 3 - Template: buka modal templates -->
                  <a href="#" class="btn portal-btn outline" data-bs-toggle="modal" data-bs-target="#modalTemplates">
                    <span class="label">Kumpulan Template dan Produk Digital</span>
                    <span class="arrow" style="background:#fff;color:#000;border:1px solid #d1d7de">▶</span>
                  </a>

                </div>
              </div>
            </div>

            <!-- RIGHT: login card -->
            <div class="col-lg-4 mt-3 margin-top">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Silahkan Login Akun Anda</h5>
                    <p class="text-center small">Masukan Email dan Password Anda</p>
                  </div>

                  <!-- Blade session and form unchanged -->
                  @if(session('success'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                  @endif

                  <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="col-12 mt-3">
                      <label for="email" class="form-label">Email</label>
                      <div class="input-group has-validation">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                      </div>
                    </div>

                    <div class="col-12 mt-3">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                      @error('password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <a href="/ganti-password">Lupa password</a>

                    <div class="col-12 mt-3">
                      <button class="btn btn-primary w-100" type="submit">Login</button>
                    </div>
                  </form>

                  <div class="col-12 mt-3">
                    <p class="small mb-0">Tidak punya akun? <a href="{{ url('/admin/data-user/create') }}">Buat akun baru</a></p>
                  </div>
                  <hr>
                  <div class="text-center mt-3">
                    <a href="https://portalbumdes.com/"><i class="bi bi-question-circle"></i> Klik disini</a> untuk pelajari bumdes
                  </div>

                </div>
              </div>
            </div>

          </div>
        </div>
      </section>
    </div>
  </main>

  <!-- Modal Pembukuan (blue) -->
  <div class="modal fade" id="modalPembukuan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header-blue">
            <h5>Aplikasi Pembukuan dan Pelaporan Keuangan BUMDesa</h5>
             <div class="modal-close-circle" data-bs-dismiss="modal">✕</div>
        </div>

        <div class="modal-body">
            <ol class="modal-list">

            <li>
                <strong>Pembukuan yang dapat digunakan</strong>
                <ul>
                  <li>Buku Kas Umum</li>
                  <li>Buku Inventaris</li>
                  <li>Buku Persediaan</li>
                  <li>Buku Piutang dan Hutang</li>
                  <li>Buku Riwayat Bagi Hasil</li>
                  <li>Lembar Laba Rugi bulanan</li>
                  <li>Laporan Neraca</li>
                  <li>Laporan Laba Rugi</li>
                  <li>Laporan Arus Kas</li>
                  <li>Menyusun Laporan Pertanggungjawaban (LPJ)</li>
                  <li>Menyusun Rencana Program Kerja (Proker)</li>
                  <li>Menyusun Analisa Kelayakan Usaha (Ketapang)</li>
                </ul>
            </li>

            <li>
                <strong>Fitur dan Kelebihan</strong>
                <ul>
                  <li>Double Entri Otomatis</li>
                  <li>Perhitungan Penyusutan Otomatis</li>
                  <li>Pencatatan Berkesinambungan</li>
                  <li>Reset Otomatis saat ganti tahun pembukuan</li>
                  <li>Komparasi Proyeksi dan Realisasi Otomatis</li>
                  <li>Dapat Langsung digunakan orang awam akuntansi</li>
                </ul>
            </li>

            <!-- ✅ LI KE-3: HARGA APLIKASI -->
            <li>
                <strong>Harga Aplikasi</strong>

                <div class="price-bar price-blue mt-2">
                <span class="old">Rp. 300.000 / bulan</span>
                <span class="new">Rp. 10.000 / bulan</span>
                </div>
            </li>

            </ol>

            <div class="text-center mt-4">
            <a href="https://bumdespro.my.id/login" class="btn btn-outline-dark">Menuju Halaman Login</a>
            </div>
        </div>
        </div>
    </div>
    </div>


  <!-- Modal Produk Digital (green) -->
    <div class="modal fade" id="modalTataAdmin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

            <div class="modal-header-black">
                <h5>Aplikasi Pengelolaan Website BUMDES</h5>
                <div class="modal-close-circle" data-bs-dismiss="modal">✕</div>
            </div>

            <div class="modal-body">
                <ol class="modal-list">

                <li>
                    <strong>Informasi yang dapat dipublikasikan:</strong>
                    <ul>
                      <li>Profil BUMDesa</li>
                      <li>Unit Usaha dan Kegiatan</li>
                      <li>Galeri Kegiatan</li>
                      <li>Papan Informasi, Berita dan Artikel</li>
                      <li>Papan Katalog BUMDesa/ Desa</li>
                      <li>Produk Ketahanan Pangan</li>
                      <li>Mitra Kerjasama BUMDesa</li>
                      <li>Kelengkapan Struktur Organisasi</li>
                      <li>Publikasi Kinerja dan Capaian</li>
                      <li>Transparansi dan Akuntabilitas</li>
                      <li>Map Kantor / Sekretariat BUMDesa</li>
                      <li>Kontak Person</li>
                    </ul>
                </li>

                <li>
                    <strong>Fitur dan Kelebihan</strong>
                    <ul>
                      <li>Template tersedia, tidak perlu coding</li>
                      <li>Mengisi Konten Mudah</li>
                      <li>Publikasi Cepat</li>
                      <li>Domain Gratis</li>
                      <li>Terintegrasi dengan akun Dinas</li>
                    </ul>
                </li>

                <li>
                    <strong>Harga Aplikasi</strong>
                    <div class="price-bar price-black mt-2">
                    <span class="old">Rp. 300.000 / bulan</span>
                    <span class="new">Rp. 10.000 / bulan</span>
                    </div>
                </li>

                </ol>

                <div class="text-center mt-4">
                <a href="/login" class="btn btn-outline-dark">Menuju Halaman Login</a>
                </div>
            </div>

            </div>
        </div>
        </div>

        <!-- Modal Templates (dynamic dari DB) -->
        <div class="modal fade" id="modalTemplates" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">

              <div class="modal-header-plain">
                  <h5>Kumpulan Template dan Produk Digital</h5>
                  <div class="modal-close-circle" data-bs-dismiss="modal">✕</div>
              </div>

              <div class="modal-body">
                  <div class="templates-panel">
                  <div class="templates-title">KATEGORI</div>

                  <ol class="templates-list">
                      @forelse($categories as $category)
                      <li>
                          <a class="main-toggle"
                          data-bs-toggle="collapse"
                          href="#subCat{{ $category->id }}"
                          role="button">
                          {{ $category->kategori }}
                          </a>

                          <ol class="collapse sub-list" id="subCat{{ $category->id }}">
                          @forelse($category->subCategories as $sub)
                              <li>
                              <a href="{{ $sub->link ?? '#' }}" target="_blank">
                                  {{ $sub->sub_kategori }}
                              </a>
                              </li>
                          @empty
                              <li><em>Tidak ada sub kategori</em></li>
                          @endforelse
                          </ol>
                      </li>
                      @empty
                      <li>Tidak ada kategori</li>
                      @endforelse
                  </ol>

                  </div>
              </div>

              </div>
          </div>
        </div>


  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/vendor/chart.js/chart.umd.js"></script>
  <script src="/assets/vendor/echarts/echarts.min.js"></script>
  <script src="/assets/vendor/quill/quill.min.js"></script>
  <script src="/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="/assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="/assets/vendor/php-email-form/validate.js"></script>

  <!-- Optional JS: close other opened sublists when opening one (UX improvement) -->
  <script>
    document.addEventListener('show.bs.collapse', function (e) {
      // when a sublist is opened, close other open sublists inside templates panel
      const opening = e.target;
      if (!opening) return;
      const allSub = document.querySelectorAll('#modalTemplates .sub-list.collapse');
      allSub.forEach(function (el) {
        if (el !== opening) {
          // hide other open ones
          const bs = bootstrap.Collapse.getInstance(el);
          if (bs) bs.hide();
        }
      });
    });

    // optional: if user clicks a sub-list link, close modal (optional) or you can let navigation happen
    document.querySelectorAll('#modalTemplates .sub-list a').forEach(function (el) {
      el.addEventListener('click', function () {
        // let link navigate; if you want to close modal before navigation uncomment:
        // bootstrap.Modal.getInstance(document.getElementById('modalTemplates'))?.hide();
      });
    });
  </script>

  <!-- Template Main JS File -->
  <script src="/assets/js/main.js"></script>

</body>

</html>
