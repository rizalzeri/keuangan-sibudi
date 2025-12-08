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
        <h4 class="mb-0">Buat Bukti Bank Keluar</h4>
    </div>
    <div class="card shadow-sm">
        <br>

        <!-- NOTE: name="sumber" tetap agar controller menerima field yang sama dengan bank_masuk,
             namun label tetap "Tujuan" untuk UX -->
        <form id="buktiForm" action="{{ url('/spj/bukti_bank_keluar') }}" method="POST" class="card-body">
            @csrf
            <div class="row-flex">
                <label for="tanggal">Tanggal</label>
                <div class="input-wrap">
                    <input type="date" id="tanggal" name="tanggal" class="form-control"
                        value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="row-flex">
                <label for="nama_transaksi">Nama Transaksi</label>
                <div class="input-wrap">
                    <input type="text" id="nama_transaksi" name="nama_transaksi"
                        class="form-control" placeholder="Contoh: Pembayaran, Pembelian ..." required>
                </div>
            </div>

            <div class="row-flex">
                <label for="tujuan">Tujuan</label>
                <div class="input-wrap">
                    <!-- gunakan name="sumber" agar backend kompatibel -->
                    <input type="text" id="tujuan" name="tujuan" class="form-control"
                        placeholder="Contoh: Vendor, Karyawan, Bank">
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

            <div class="row-flex">
                <label for="menyetujui">Menyetujui</label>
                <div class="input-wrap">
                    <input type="text" id="menyetujui" name="menyetujui" class="form-control">
                </div>
            </div>

            <div class="row-flex">
                <label for="mengetahui">Mengetahui</label>
                <div class="input-wrap">
                    <input type="text" id="mengetahui" name="mengetahui" class="form-control">
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

            <!-- Dokumen pendukung : kirim sebagai dokumen_pendukung[] supaya controller bisa terima array -->
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

            // 1) Buka halaman print (new tab)
            const printUrl = "{{ url('/spj/bukti_bank_keluar/print') }}?" + params.toString();
            window.open(printUrl, "_blank");

            // 2) Simpan via fetch POST
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
                // coba parse json dengan aman
                const contentType = res.headers.get('content-type') || '';
                if (contentType.indexOf('application/json') === -1) {
                    // server mungkin mereturn HTML (redirect) meskipun data tersimpan
                    // baca teks untuk debugging dan lempar error supaya masuk catch
                    const txt = await res.text();
                    throw new Error("Unexpected response format: " + txt.slice(0, 300));
                }
                const json = await res.json();
                return { ok: res.ok, json };
            })
            .then(({ ok, json }) => {
                // terima dua format: { success: true } atau { status: "success" }
                const isSuccess = (json && (json.success === true || json.status === 'success' || json.status === 'ok'));
                if (isSuccess) {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil!",
                        text: json.message || "Data berhasil disimpan",
                        confirmButtonText: "OK"
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal!",
                        text: (json && (json.message || json.error || json.status)) || "Terjadi kesalahan saat menyimpan data"
                    });
                }
            })
            .catch(err => {
                console.error("Submit error:", err);
                Swal.fire({
                    icon: "error",
                    title: "Gagal!",
                    text: "Terjadi error saat mengirim data! Cek console untuk detail."
                });
            });

        });

    });
</script>

@endsection
