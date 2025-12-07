@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="pagetitle text-center">
            <h1>APLIKASI PEMBUKUAN DAN KEUANGAN BUMDESA TERINTEGRASI</h1>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">ID BUMDES</h5>

                    <!-- Modal Form -->
                    <div class="modal fade" id="lokasiModal" tabindex="-1" aria-labelledby="lokasiModalLabel"
                        aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form id="lokasiForm" action="/{{ $profil->id }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <p class="modal-title" id="lokasiModalLabel">
                                            <i class="bi bi-exclamation-triangle text-danger"></i>
                                            Demi Kemudahan Pengelolaan Akun, Mohon Lengkapi Lokasi & Kontak
                                        </p>
                                    </div>

                                    <div class="modal-body">
                                        <small class="text-danger mb-3">Jika diluar Jawa Tengah Silahkan Klik Tombol Tulis
                                            Untuk
                                            Menulis Manual</small>
                                        <hr>
                                        <!-- Kabupaten -->
                                        <div class="mb-3">
                                            <label for="kabupaten" class="form-label">Kabupaten</label>
                                            <div class="input-group">
                                                <select class="form-select" id="kabupatenSelect" required>
                                                    <option value="">Pilih Kabupaten...</option>
                                                </select>
                                                <button type="button" class="btn btn-outline-secondary"
                                                    id="toggleKabupatenBtn">Tulis</button>
                                            </div>
                                            <input type="text" class="form-control mt-2 d-none" id="kabupatenInput"
                                                placeholder="Tulis nama kabupaten..." />
                                            <input type="hidden" name="kabupaten" id="kabupatenHidden">
                                        </div>

                                        <!-- Kecamatan -->
                                        <div class="mb-3">
                                            <label for="kecamatan" class="form-label">Kecamatan</label>
                                            <div class="input-group">
                                                <select class="form-select" id="kecamatanSelect" required>
                                                    <option value="">Pilih Kecamatan...</option>
                                                </select>
                                                <button type="button" class="btn btn-outline-secondary"
                                                    id="toggleKecamatanBtn">Tulis</button>
                                            </div>
                                            <input type="text" class="form-control mt-2 d-none" id="kecamatanInput"
                                                placeholder="Tulis nama kecamatan..." />
                                            <input type="hidden" name="kecamatan" id="kecamatanHidden">
                                        </div>

                                        <!-- Desa -->
                                        <div class="mb-3">
                                            <label for="desa" class="form-label">Desa</label>
                                            <div class="input-group">
                                                <select class="form-select" id="desaSelect" required>
                                                    <option value="">Pilih Desa...</option>
                                                </select>
                                                <button type="button" class="btn btn-outline-secondary"
                                                    id="toggleDesaBtn">Tulis</button>
                                            </div>
                                            <input type="text" class="form-control mt-2 d-none" id="desaInput"
                                                placeholder="Tulis nama desa..." />
                                            <input type="hidden" name="desa" id="desaHidden">
                                        </div>

                                        <!-- Nomor WA -->
                                        <div class="mb-3">
                                            <label for="no_wa" class="form-label">Nomor WhatsApp</label>
                                            <input type="text" class="form-control" id="no_wa" name="no_wa"
                                                placeholder="08xxxxxxxxxx" value="{{ $profil->no_wa ?? '' }}" required>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

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

                            // --- toggle manual mode dengan atur atribut required ---
                            function toggleManual(select, input, btn) {
                                const isManual = !input.classList.contains("d-none");

                                if (isManual) {
                                    // 🔙 Balik ke mode pilih
                                    input.classList.add("d-none");
                                    select.classList.remove("d-none");
                                    input.removeAttribute("required");
                                    select.setAttribute("required", true);
                                    btn.textContent = "Tulis";
                                } else {
                                    // ✍️ Ubah ke mode tulis manual
                                    select.classList.add("d-none");
                                    input.classList.remove("d-none");
                                    select.removeAttribute("required");
                                    input.setAttribute("required", true);
                                    btn.textContent = "Pilih";
                                }
                            }

                            toggleKabupatenBtn.addEventListener("click", () =>
                                toggleManual(kabupatenSelect, kabupatenInput, toggleKabupatenBtn)
                            );
                            toggleKecamatanBtn.addEventListener("click", () =>
                                toggleManual(kecamatanSelect, kecamatanInput, toggleKecamatanBtn)
                            );
                            toggleDesaBtn.addEventListener("click", () =>
                                toggleManual(desaSelect, desaInput, toggleDesaBtn)
                            );

                            // --- Fetch Kabupaten (Jawa Tengah ID 33) ---
                            fetch("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/33.json")
                                .then(res => res.json())
                                .then(data => {
                                    data.forEach(kab => {
                                        const opt = document.createElement("option");
                                        opt.value = kab.name;
                                        opt.dataset.id = kab.id;
                                        opt.textContent = kab.name;
                                        kabupatenSelect.appendChild(opt);
                                    });
                                });

                            kabupatenSelect.addEventListener("change", function() {
                                const kabupatenId = this.options[this.selectedIndex].dataset.id;
                                kabupatenHidden.value = this.value;
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
                                const kecamatanId = this.options[this.selectedIndex].dataset.id;
                                kecamatanHidden.value = this.value;
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

                            // --- sinkron input manual ke hidden value ---
                            [kabupatenInput, kecamatanInput, desaInput].forEach(input => {
                                input.addEventListener("input", () => {
                                    const targetHidden = document.getElementById(input.id.replace("Input",
                                        "Hidden"));
                                    targetHidden.value = input.value;
                                });
                            });

                            // --- tampilkan modal kalau data belum lengkap ---
                            @if (empty($profil->kabupaten) || empty($profil->kecamatan) || empty($profil->desa) || empty($profil->no_wa))
                                const lokasiModal = new bootstrap.Modal(document.getElementById('lokasiModal'), {
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                lokasiModal.show();
                            @endif
                        });
                    </script>



                    <form action="/{{ $profil->id }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Table for input fields -->
                        <table class="table table-bordered">
                            <tbody>
                                <!-- Row 1: Nama BUMDes -->
                                <tr>
                                    <td><label for="nm_bumdes">Nama BUMDes</label></td>
                                    <td>
                                        <input type="text" class="form-control @error('nm_bumdes') is-invalid @enderror"
                                            id="nm_bumdes" name="nm_bumdes" value="{{ old('nm_bumdes', $profil) }}">
                                        @error('nm_bumdes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 3: No Wa -->
                                <tr>
                                    <td><label for="no_wa">No Whatsapp</label></td>
                                    <td>
                                        <input type="text" class="form-control @error('no_wa') is-invalid @enderror"
                                            id="no_wa" name="no_wa" value="{{ old('no_wa', $profil) }}">
                                        @error('no_wa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                                <!-- Row 3: Kecamatan -->
                                <tr>
                                    <td><label for="kabupaten">Kabupaten</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('kabupaten') is-invalid @enderror" id="kabupaten"
                                            name="kabupaten" value="{{ old('kabupaten', $profil) }}">
                                        @error('kabupaten')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                                <!-- Row 3: Kecamatan -->
                                <tr>
                                    <td><label for="kecamatan">Kecamatan</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('kecamatan') is-invalid @enderror" id="kecamatan"
                                            name="kecamatan" value="{{ old('kecamatan', $profil) }}">
                                        @error('kecamatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                                <!-- Row 2: Desa -->
                                <tr>
                                    <td><label for="desa">Desa</label></td>
                                    <td>
                                        <input type="text" class="form-control @error('desa') is-invalid @enderror"
                                            id="desa" name="desa" value="{{ old('desa', $profil) }}">
                                        @error('desa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>


                                <!-- Row 4: Nama Direktur -->
                                <tr>
                                    <td><label for="nm_direktur">Nama Direktur</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_direktur') is-invalid @enderror"
                                            id="nm_direktur" name="nm_direktur"
                                            value="{{ old('nm_direktur', $profil) }}">
                                        @error('nm_direktur')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 5: Nama Sekretaris -->
                                <tr>
                                    <td><label for="nm_serkertaris">Nama Sekretaris</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_serkertaris') is-invalid @enderror"
                                            id="nm_serkertaris" name="nm_serkertaris"
                                            value="{{ old('nm_serkertaris', $profil) }}">
                                        @error('nm_serkertaris')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 6: Nama Bendahara -->
                                <tr>
                                    <td><label for="nm_bendahara">Nama Bendahara</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_bendahara') is-invalid @enderror"
                                            id="nm_bendahara" name="nm_bendahara"
                                            value="{{ old('nm_bendahara', $profil) }}">
                                        @error('nm_bendahara')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 7: Nama Pengawas -->
                                <tr>
                                    <td><label for="nm_pengawas">Nama Pengawas</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_pengawas') is-invalid @enderror"
                                            id="nm_pengawas" name="nm_pengawas"
                                            value="{{ old('nm_pengawas', $profil) }}">
                                        @error('nm_pengawas')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 8: Nama Penasehat -->
                                <tr>
                                    <td><label for="nm_penasehat">Nama Penasehat</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('nm_penasehat') is-invalid @enderror"
                                            id="nm_penasehat" name="nm_penasehat"
                                            value="{{ old('nm_penasehat', $profil) }}">
                                        @error('nm_penasehat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>


                                <!-- Row 17: No Badan -->
                                <tr>
                                    <td><label for="no_badan">No Badan</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('no_badan') is-invalid @enderror" id="no_badan"
                                            name="no_badan" value="{{ old('no_badan', $profil) }}">
                                        @error('no_badan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 18: No Perdes -->
                                <tr>
                                    <td><label for="no_perdes">No Perdes</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('no_perdes') is-invalid @enderror" id="no_perdes"
                                            name="no_perdes" value="{{ old('no_perdes', $profil) }}">
                                        @error('no_perdes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Row 19: No SK -->
                                <tr>
                                    <td><label for="no_sk">No SK</label></td>
                                    <td>
                                        <input type="text" class="form-control @error('no_sk') is-invalid @enderror"
                                            id="no_sk" name="no_sk" value="{{ old('no_sk', $profil) }}">
                                        @error('no_sk')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                                <!-- Row 19: No NIB -->
                                <tr>
                                    <td><label for="unt_usaha1">No ijin Berusaha (NIB)</label></td>
                                    <td>
                                        <input type="text"
                                            class="form-control @error('unt_usaha1') is-invalid @enderror"
                                            id="unt_usaha1" name="unt_usaha1" value="{{ old('unt_usaha1', $profil) }}">
                                        @error('unt_usaha1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
