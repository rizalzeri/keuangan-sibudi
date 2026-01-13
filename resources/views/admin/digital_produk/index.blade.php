<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Kumpulan Template & Produk Digital | BUMDES PRO</title>

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
    /* --- copy of styles relevant to templates page (kept minimal) --- */
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

    .sub-list {
      margin-top: 8px;
      padding-left: 18px;
      list-style: lower-alpha;
      font-weight: 500;
    }

    .sub-list li { margin-bottom: 6px; }
    .sub-list a { text-decoration: none; color: #0b1a2b; }
    .sub-list a:hover { text-decoration: underline; }

    /* price bar (if used) */
    .price-bar { display:flex; align-items:center; gap:12px; padding:12px 14px; border-radius:6px; font-weight:700; }
    .price-blue { background:#1e73be; }
    .price-black { background:#000; }
    .price-green { background:#0f6b38; }
    .price-bar .old { background:#d90429; color:#fff; padding:6px 10px; border-radius:4px; text-decoration:line-through; }
    .price-bar .new { color:#ffd60a; font-size:16px; font-weight:800; }

    body { background:#f7fafc; }
    .card { box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
  </style>
</head>

<body>

  <header class="py-3 bg-white border-bottom">
    <div class="container d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center gap-3">
        <img src="/assets/img/logo.png" alt="logo" width="48" height="48">
        <div>
          <h5 class="mb-0">BUMDES PRO</h5>
          <small class="text-muted">Kumpulan Template & Produk Digital</small>
        </div>
      </div>
      <div>
        <a href="/" class="btn btn-outline-secondary">← Kembali ke Halaman Login</a>
      </div>
    </div>
  </header>

  <main class="container my-4" id="templatesPage">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <div class="card p-3">
          <div class="card-body">
            <h3 class="mb-2">Kumpulan Template dan Produk Digital</h3>
            <p class="text-muted">Daftar kategori dan produk digital.</p>

            <div class="templates-panel mt-3">
              <div class="templates-title">KATEGORI</div>

              <ol class="templates-list">
                @forelse($categories as $category)
                  <li>
                    <a class="main-toggle"
                       data-bs-toggle="collapse"
                       href="#subCat{{ $category->id }}"
                       role="button"
                       aria-expanded="false"
                       aria-controls="subCat{{ $category->id }}">
                      {{ $category->kategori }}
                    </a>

                    <ol class="collapse sub-list" id="subCat{{ $category->id }}">
                      @forelse($category->subCategories as $sub)
                        <li>
                          <a href="{{ $sub->link ?? '#' }}" target="_blank">{{ $sub->sub_kategori }}</a>
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
  </main>

  <footer class="py-4 text-center text-muted">
    <div class="container">© {{ date('Y') }} BUMDES PRO — All rights reserved</div>
  </footer>

  <!-- Vendor JS Files -->
  <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="/assets/vendor/chart.js/chart.umd.js"></script>
  <script src="/assets/vendor/echarts/echarts.min.js"></script>
  <script src="/assets/vendor/quill/quill.min.js"></script>

  <script>
    // close other opened sublists when opening one (UX improvement for this page)
    document.addEventListener('show.bs.collapse', function (e) {
      const opening = e.target;
      if (!opening) return;
      const allSub = document.querySelectorAll('#templatesPage .sub-list.collapse');
      allSub.forEach(function (el) {
        if (el !== opening) {
          const bs = bootstrap.Collapse.getInstance(el);
          if (bs) bs.hide();
        }
      });
    });

    // optional: close open collapse when clicking outside (nice-to-have)
    document.addEventListener('click', function (ev) {
      const open = document.querySelector('#templatesPage .sub-list.collapse.show');
      if (!open) return;
      const isInside = ev.target.closest('#templatesPage');
      const isToggle = ev.target.closest('.main-toggle');
      if (!isInside && !isToggle) {
        const bs = bootstrap.Collapse.getInstance(open);
        if (bs) bs.hide();
      }
    });
  </script>

</body>

</html>
