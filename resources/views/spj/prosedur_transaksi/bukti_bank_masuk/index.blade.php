@extends('layouts.spj.main')

@section('container')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .row-flex {
        display: flex;
        width: 100%;
        margin-bottom: 15px;
    }
    .row-flex label {
        width: 50%;
        padding-right: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    .row-flex .input-wrap {
        width: 50%;
    }
</style>

<div class="container-fluid py-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Buat Bukti Bank Masuk</h4>
    </div>
    <div class="card shadow-sm">
        <br>

        <form id="buktiForm" action="{{ url('/spj/bukti_bank_masuk') }}" method="POST" class="card-body">
            @csrf
            <div class="row-flex">
                <label for="tanggal">Tanggal</label>
                <div class="input-wrap">
                    <input type="text" id="tanggal" name="tanggal" class="form-control"
                        value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="row-flex">
                <label for="nama_transaksi">Nama Transaksi</label>
                <div class="input-wrap">
                    <input type="text" id="nama_transaksi" name="nama_transaksi"
                        class="form-control" placeholder="Contoh: Setoran modal, Pembayaran ..." required>
                </div>
            </div>

            <div class="row-flex">
                <label for="sumber">Sumber</label>
                <div class="input-wrap">
                    <input type="text" id="sumber" name="sumber" class="form-control"
                        placeholder="Contoh: bank, Bank, Donatur">
                </div>
            </div>

            <div class="row-flex">
                <label for="nominal_display">Nominal</label>
                <div class="input-wrap">
                    <input type="text" id="nominal_display" class="form-control"
                        placeholder="Rp.0" autocomplete="off" inputmode="numeric">
                    <input type="hidden" id="nominal" name="nominal">
                </div>
            </div>

            <div class="row-flex">
                <label for="penerima">Penerima</label>
                <div class="input-wrap">
                    <input type="text" id="penerima" name="penerima" class="form-control">
                </div>
            </div>

            <!-- MENYETUJUI -> dropdown (disabled awalnya) -->
            <div class="row-flex">
                <label for="menyetujui_select">Menyetujui</label>
                <div class="input-wrap">
                    <select id="menyetujui_select" class="form-control" disabled>
                        <option value="">-- Pilih Menyetujui --</option>
                    </select>

                    <!-- Hidden: hanya nama yang akan disimpan -->
                    <input type="hidden" id="menyetujui" name="menyetujui" value="">
                    <!-- optional id (tetap ada, dikosongkan sekarang) -->
                    <input type="hidden" id="menyetujui_id" name="menyetujui_id" value="">
                </div>
            </div>

            <!-- MENGETAHUI -> dropdown (disabled awalnya) -->
            <div class="row-flex">
                <label for="mengetahui_select">Mengetahui</label>
                <div class="input-wrap">
                    <select id="mengetahui_select" class="form-control" disabled>
                        <option value="">-- Pilih Mengetahui --</option>
                    </select>

                    <input type="hidden" id="mengetahui" name="mengetahui" value="">
                    <input type="hidden" id="mengetahui_id" name="mengetahui_id" value="">
                </div>
            </div>

            <div class="row-flex">
                <label>Kategori Pembukuan</label>
                <div class="input-wrap">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori_pembukuan" value="1" checked>
                        <label class="form-check-label">1</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="kategori_pembukuan" value="2">
                        <label class="form-check-label">2</label>
                    </div>
                </div>
            </div>

            <!-- Dokumen pendukung -->
            <div class="row-flex">
                <label>Dokumen Pendukung</label>
                <div class="input-wrap">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="kwitansi">
                                <label class="form-check-label">Kwitansi</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="nota">
                                <label class="form-check-label">Nota</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="bukti_transfer">
                                <label class="form-check-label">Bukti Transfer</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="surat">
                                <label class="form-check-label">Surat</label>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="berita_acara">
                                <label class="form-check-label">Berita Acara</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="laporan">
                                <label class="form-check-label">Laporan</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="tanda_terima">
                                <label class="form-check-label">Tanda Terima</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dokumen_pendukung[]" value="lainnya">
                                <label class="form-check-label">Lainnya</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-flex">
                <label for="link_gdrive">Link Google Drive</label>
                <div class="input-wrap">
                    <input type="url" id="link_gdrive" name="link_gdrive" class="form-control"
                           placeholder="https://drive.google.com/drive/folders/..." value="{{ old('link_gdrive') }}">
                    <div class="form-text">Opsional — masukkan link file/folder Google Drive yang terkait.</div>
                </div>
            </div>

            <div class="text-end mt-3">
                <button id="saveBtn" type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan & Cetak</button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert2 CDN (untuk alert) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const display = document.getElementById('nominal_display');
        const hidden  = document.getElementById('nominal');

        const mengetahuiSelect = document.getElementById('mengetahui_select');
        const menyetujuiSelect = document.getElementById('menyetujui_select');

        const mengetahuiHidden = document.getElementById('mengetahui');
        const mengetahuiIdHidden = document.getElementById('mengetahui_id');
        const menyetujuiHidden = document.getElementById('menyetujui');
        const menyetujuiIdHidden = document.getElementById('menyetujui_id');

        /* -----------------------
           FORMAT NOMINAL RUPIAH
        -------------------------*/
        function formatRupiah(angka) {
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function updateNominal(value) {
            const angka = value.replace(/\D/g, "").replace(/^0+/, "") || "0";
            hidden.value = angka;
            display.value = angka === "0" ? "" : "Rp." + formatRupiah(angka);
            // panggil API untuk memperbarui dropdown
            loadOtorisasiOptions(parseInt(angka || 0, 10));
        }

        display.addEventListener('input', function () {
            const hanyaAngka = this.value.replace(/\D/g, "");
            updateNominal(hanyaAngka);
        });

        display.addEventListener('focus', function () {
            const angka = hidden.value;
            display.value = angka === "" ? "" : formatRupiah(angka);
        });

        display.addEventListener('blur', function () {
            const angka = hidden.value;
            display.value = angka === "" ? "" : "Rp." + formatRupiah(angka);
        });

        display.addEventListener('keydown', function (e) {
            const allow = [8,9,13,27,37,38,39,40,46];
            if (allow.includes(e.keyCode)) return;
            const isNumber = (e.keyCode >= 48 && e.keyCode <= 57) ||
                             (e.keyCode >= 96 && e.keyCode <= 105);
            if (!isNumber) e.preventDefault();
        });

        /* ---------------------------
           Helpers: clear select (keep placeholder)
        ----------------------------*/
        function clearSelectKeepPlaceholder(sel) {
            while (sel.options.length > 1) sel.remove(1); // keep the first placeholder
        }

        function setSelectSingleOption(sel, value, text, personalisasi_id = '') {
            clearSelectKeepPlaceholder(sel);
            const opt = document.createElement('option');
            opt.value = value || '';
            opt.text = text || '';
            if (personalisasi_id) opt.setAttribute('data-personalisasi-id', personalisasi_id);
            sel.add(opt);
        }

        /* -----------------------------------
           Fetch opsi otorisasi berdasarkan nominal
           HANYA menampilkan 1 opsi rekomendasi (jika ada).
           Endpoint: /spj/klasifikasi/classify?nominal=...
        --------------------------------------*/
        async function loadOtorisasiOptions(nominalValue) {
            try {
                // jika nominal 0 atau kosong -> kosongkan dropdown dan disable
                if (!nominalValue || nominalValue <= 0) {
                    clearSelectKeepPlaceholder(mengetahuiSelect);
                    clearSelectKeepPlaceholder(menyetujuiSelect);
                    mengetahuiSelect.disabled = true;
                    menyetujuiSelect.disabled = true;

                    // kosongkan hidden fields
                    mengetahuiHidden.value = '';
                    mengetahuiIdHidden.value = '';
                    menyetujuiHidden.value = '';
                    menyetujuiIdHidden.value = '';
                    return;
                }

                const url = "{{ route('spj.klasifikasi.classify') }}?nominal=" + encodeURIComponent(nominalValue || 0);
                const res = await fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest", "Accept": "application/json" }});
                if (!res.ok) {
                    console.warn('classify API returned', res.status);
                    mengetahuiSelect.disabled = true;
                    menyetujuiSelect.disabled = true;
                    return;
                }
                const body = await res.json();
                if (!body.success) {
                    mengetahuiSelect.disabled = true;
                    menyetujuiSelect.disabled = true;
                    return;
                }

                const data = body.data || {};
                const recommended = data.recommended || {};

                // default: clear + disabled
                clearSelectKeepPlaceholder(mengetahuiSelect);
                clearSelectKeepPlaceholder(menyetujuiSelect);
                mengetahuiSelect.disabled = true;
                menyetujuiSelect.disabled = true;

                // --- MENGETAHUI: jika ada rekomendasi, tampilkan 1 option (nama saja) dan enable select
                if (recommended.mengetahui && recommended.mengetahui.nama) {
                    setSelectSingleOption(mengetahuiSelect, recommended.mengetahui.id, recommended.mengetahui.nama, recommended.mengetahui.personalisasi_id || '');
                    mengetahuiSelect.disabled = false;

                    // simpan *nama* ke hidden (sesuai permintaan)
                    mengetahuiHidden.value = recommended.mengetahui.nama || '';
                    mengetahuiIdHidden.value = '';
                } else {
                    mengetahuiHidden.value = '';
                    mengetahuiIdHidden.value = '';
                }

                // --- MENYETUJUI: jika ada rekomendasi, tampilkan 1 option (nama saja) dan enable select
                if (recommended.persetujuan && recommended.persetujuan.nama) {
                    setSelectSingleOption(menyetujuiSelect, recommended.persetujuan.id, recommended.persetujuan.nama, recommended.persetujuan.personalisasi_id || '');
                    menyetujuiSelect.disabled = false;

                    // simpan nama saja
                    menyetujuiHidden.value = recommended.persetujuan.nama || '';
                    menyetujuiIdHidden.value = '';
                } else {
                    menyetujuiHidden.value = '';
                    menyetujuiIdHidden.value = '';
                }

            } catch (err) {
                console.error('Error loadOtorisasiOptions', err);
                mengetahuiSelect.disabled = true;
                menyetujuiSelect.disabled = true;
            }
        }

        /* -----------------------------------
           Sync hidden fields jika user ubah pilihan manual (meski hanya 1 opsi)
        --------------------------------------*/
        mengetahuiSelect.addEventListener('change', function () {
            const selOpt = this.options[this.selectedIndex];
            const nama = selOpt ? selOpt.text : '';
            mengetahuiHidden.value = nama || '';
            mengetahuiIdHidden.value = '';
        });

        menyetujuiSelect.addEventListener('change', function () {
            const selOpt = this.options[this.selectedIndex];
            const nama = selOpt ? selOpt.text : '';
            menyetujuiHidden.value = nama || '';
            menyetujuiIdHidden.value = '';
        });

        /* -----------------------------------
           On page load: keep selects empty & disabled
        --------------------------------------*/
        clearSelectKeepPlaceholder(mengetahuiSelect);
        clearSelectKeepPlaceholder(menyetujuiSelect);
        mengetahuiSelect.disabled = true;
        menyetujuiSelect.disabled = true;

        /* -----------------------------------
           HANDLE SUBMIT: PRINT + SIMPAN DATA
        --------------------------------------*/
        const form = document.getElementById('buktiForm');

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // cegah submit biasa

            const fd = new FormData(form);
            const params = new URLSearchParams();

            // COPY semua field ke QueryString untuk halaman print
            for (const pair of fd.entries()) {
                const [k, v] = pair;
                // Jika array (dokumen_pendukung[]) akan muncul berkali-kali — ini ok
                params.append(k, v);
            }

            // === 1. Buka halaman print ===
            const printUrl = "{{ url('/spj/bukti_bank_masuk/print') }}?" + params.toString();
            window.open(printUrl, "_blank");

            // === 2. Simpan ke database via fetch POST ===
            fetch(form.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                },
                body: fd
            })
            .then(async (res) => {
                const contentType = res.headers.get('content-type') || '';
                if (contentType.indexOf('application/json') === -1) {
                    const txt = await res.text();
                    throw new Error("Unexpected response format: " + txt.slice(0, 300));
                }
                const json = await res.json();
                return { ok: res.ok, json };
            })
            .then(({ ok, json }) => {
                const isSuccess = (json && (json.success === true || json.status === 'success' || json.status === 'ok'));
                if (isSuccess) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: json.message || "Data berhasil disimpan",
                        confirmButtonText: "OK"
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: (json && (json.message || json.error || json.status)) || "Terjadi kesalahan saat menyimpan data"
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: "error",
                    title: "Gagal!",
                    text: "Terjadi error saat mengirim data! Cek console untuk detail."
                });
            });
        });

    });
</script>

<script>
    flatpickr("#tanggal", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "l, d F Y",
        locale: "id", // Bahasa Indonesia
        allowInput: true
    });
</script>

@endsection
