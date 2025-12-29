{{-- resources/views/admin/data_user/create_standalone.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Buat Akun | BUMDES PRO</title>
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

    {{-- bootstrap icons CDN (backup) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .margin-top { margin-top: 50px; }
        .card-centered { max-width: 900px; margin: 40px auto; }
        @media (max-width: 768px) {
            .card-centered { margin: 20px 12px; }
        }
    </style>
</head>
<body>

    <header class="py-3 border-bottom bg-white shadow-sm">
        <div class="container d-flex align-items-center gap-3">
            <img src="/assets/img/logo.png" alt="logo" width="48" height="48">
            <div>
                <h5 class="mb-0">BUMDES PRO</h5>
                <small class="text-muted">Buat akun baru</small>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="card card-centered">
            <div class="card-body">
                <div class="pt-4 pb-2 text-center">
                    <h5 class="card-title pb-0 fs-4">Buat Akun</h5>
                    <p class="small text-muted">Masukkan detail pribadi Anda untuk membuat akun</p>
                </div>

                <form method="POST" action="/admin/data-user/store" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <label for="name" class="form-label">Nama</label>
                        <input id="name" type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="no_wa" class="form-label">Nomor WA</label>
                        <input id="no_wa" type="text"
                               class="form-control @error('no_wa') is-invalid @enderror"
                               name="no_wa" value="{{ old('no_wa') }}" required>
                        @error('no_wa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" required autocomplete="new-password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                        <input id="password-confirm" type="password" class="form-control"
                               name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <!-- Wilayah (sama seperti template Anda) -->
                    <div class="col-12">
                        <label class="form-label">Kabupaten (Jawa Tengah)</label>
                        <div class="input-group">
                            <select id="kabupatenSelect" class="form-select"></select>
                            <button type="button" class="btn btn-outline-secondary" id="toggleKabupatenBtn">Tulis</button>
                        </div>
                        <input type="text" id="kabupatenInput" class="form-control mt-2 d-none" placeholder="Tulis nama kabupaten...">
                        <input type="hidden" name="kabupaten" id="kabupatenHidden">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Kecamatan</label>
                        <div class="input-group">
                            <select id="kecamatanSelect" class="form-select"></select>
                            <button type="button" class="btn btn-outline-secondary" id="toggleKecamatanBtn">Tulis</button>
                        </div>
                        <input type="text" id="kecamatanInput" class="form-control mt-2 d-none" placeholder="Tulis nama kecamatan...">
                        <input type="hidden" name="kecamatan" id="kecamatanHidden">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Desa</label>
                        <div class="input-group">
                            <select id="desaSelect" class="form-select"></select>
                            <button type="button" class="btn btn-outline-secondary" id="toggleDesaBtn">Tulis</button>
                        </div>
                        <input type="text" id="desaInput" class="form-control mt-2 d-none" placeholder="Tulis nama desa...">
                        <input type="hidden" name="desa" id="desaHidden">
                    </div>

                    <!-- Langganan -->

                    <div class="col-12 text-end">
                        <a href="{{ url('/') }}" class="btn btn-light">Batal</a>
                        <button type="submit" class="btn btn-primary">Buat Akun</button>
                    </div>
                </form>

                {{-- pesan validasi global --}}
                @if ($errors->any())
                    <div class="mt-3">
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </main>

    <footer class="py-4 text-center text-muted small">
        &copy; {{ date('Y') }} BUMDES PRO
    </footer>

    <!-- Vendor JS Files -->
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="/assets/vendor/chart.js/chart.umd.js"></script>
    <script src="/assets/vendor/echarts/echarts.min.js"></script>
    <script src="/assets/vendor/quill/quill.min.js"></script>
    <script src="/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="/assets/vendor/tinymce/tinymce.min.js"></script>

    <!-- Region JS (sama dengan sebelumnya) -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const kabupatenSelect = document.getElementById("kabupatenSelect");
        const kecamatanSelect = document.getElementById("kecamatanSelect");
        const desaSelect = document.getElementById("desaSelect");

        const kabupatenInput = document.getElementById("kabupatenInput");
        const kecamatanInput = document.getElementById("kecamatanInput");
        const desaInput = document.getElementById("desaInput");

        const kabupatenHidden = document.getElementById("kabupatenHidden");
        const kecamatanHidden = document.getElementById("kecamatanHidden");
        const desaHidden = document.getElementById("desaHidden");

        const toggleKabupatenBtn = document.getElementById("toggleKabupatenBtn");
        const toggleKecamatanBtn = document.getElementById("toggleKecamatanBtn");
        const toggleDesaBtn = document.getElementById("toggleDesaBtn");

        function toggleManual(select, input, btn) {
            const manualMode = !input.classList.contains("d-none");
            if (manualMode) {
                input.classList.add("d-none");
                select.classList.remove("d-none");
                btn.textContent = "Tulis";
            } else {
                select.classList.add("d-none");
                input.classList.remove("d-none");
                btn.textContent = "Pilih";
            }
        }

        toggleKabupatenBtn.addEventListener("click", () => toggleManual(kabupatenSelect, kabupatenInput, toggleKabupatenBtn));
        toggleKecamatanBtn.addEventListener("click", () => toggleManual(kecamatanSelect, kecamatanInput, toggleKecamatanBtn));
        toggleDesaBtn.addEventListener("click", () => toggleManual(desaSelect, desaInput, toggleDesaBtn));

        // Ambil daftar kabupaten (Jawa Tengah)
        fetch("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/33.json")
            .then(res => res.json())
            .then(data => {
                kabupatenSelect.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
                data.forEach(kab => {
                    const opt = document.createElement("option");
                    opt.value = kab.name;
                    opt.dataset.id = kab.id;
                    opt.textContent = kab.name;
                    kabupatenSelect.appendChild(opt);
                });
            }).catch(()=>{ console.warn('Gagal load kabupaten'); });

        kabupatenSelect.addEventListener("change", function() {
            kabupatenHidden.value = this.value;
            const kabupatenId = this.options[this.selectedIndex]?.dataset?.id;
            kecamatanSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
            desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
            if (!kabupatenId) return;

            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${kabupatenId}.json`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(kec => {
                        const opt = document.createElement("option");
                        opt.value = kec.name;
                        opt.dataset.id = kec.id;
                        opt.textContent = kec.name;
                        kecamatanSelect.appendChild(opt);
                    });
                })
                .catch(() => alert("❌ Gagal memuat kecamatan."));
        });

        kecamatanSelect.addEventListener("change", function() {
            kecamatanHidden.value = this.value;
            const kecamatanId = this.options[this.selectedIndex]?.dataset?.id;
            desaSelect.innerHTML = '<option value="">-- Pilih Desa --</option>';
            if (!kecamatanId) return;

            fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${kecamatanId}.json`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(desa => {
                        const opt = document.createElement("option");
                        opt.value = desa.name;
                        opt.textContent = desa.name;
                        desaSelect.appendChild(opt);
                    });
                })
                .catch(() => alert("❌ Gagal memuat desa."));
        });

        desaSelect.addEventListener("change", function() {
            desaHidden.value = this.value;
        });

        [kabupatenInput, kecamatanInput, desaInput].forEach(input => {
            input.addEventListener("input", function() {
                const hidden = document.getElementById(input.id.replace("Input", "Hidden"));
                hidden.value = input.value;
            });
        });

        // Validasi sebelum submit
        document.querySelector("form").addEventListener("submit", function(e) {
            if (!kabupatenHidden.value || !kecamatanHidden.value || !desaHidden.value) {
                e.preventDefault();
                alert("⚠️ Lengkapi semua data lokasi terlebih dahulu.");
            }
        });
    });
    </script>

</body>
</html>
